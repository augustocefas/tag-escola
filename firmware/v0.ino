/**
 * Gateway BLE → WiFi para ESP32
 * ─────────────────────────────────────────────────────────────
 * Escaneia dispositivos BLE próximos, filtra por RSSI mínimo
 * para garantir que o responsável está realmente na porta,
 * e notifica o Laravel via HTTP POST.
 *
 * Bibliotecas necessárias (Arduino IDE / PlatformIO):
 *   - ESP32 BLE Arduino (já inclusa no pacote esp32 do Arduino)
 *   - ArduinoJson  → instalar via Library Manager: "ArduinoJson" by Benoit Blanchon
 *   - HTTPClient   → já inclusa no pacote esp32
 *
 * Board: "ESP32 Dev Module" (ou equivalente)
 * ─────────────────────────────────────────────────────────────
 */

#include <Arduino.h>
#include <BLEDevice.h>
#include <BLEScan.h>
#include <BLEAdvertisedDevice.h>
#include <WiFi.h>
#include <HTTPClient.h>
#include <ArduinoJson.h>

// ─── CONFIGURAÇÕES — edite aqui ──────────────────────────────

// WiFi
const char* WIFI_SSID     = "NOME_DA_SUA_REDE";
const char* WIFI_PASSWORD = "SENHA_DA_REDE";

// Laravel API
const char* API_URL       = "http://192.168.1.100/api/tag/evento";  // IP do servidor Laravel
const char* GATEWAY_TOKEN = "0851bf791d045d4a561a74556403f2a29d2b049085e42f92c30fabc181d5b6a2";            // mesmo GATEWAY_TOKEN do .env Laravel

// BLE Scan
const int   SCAN_DURATION    = 3;     // segundos por ciclo de scan
const int   RSSI_MINIMO      = -70;   // dBm — só notifica se o sinal for forte o suficiente
                                       // -70 ≈ ~2-3 metros | -85 ≈ ~8-10 metros
const int   COOLDOWN_MS      = 30000; // 30s entre notificações do mesmo MAC (evita spam)
const int   MAX_MACS_CACHE   = 50;    // tamanho do cache de MACs recentes

// LED de status (GPIO 2 na maioria das ESP32)
const int   LED_PIN          = 2;

// ─────────────────────────────────────────────────────────────

BLEScan* pBLEScan;

// Cache para evitar notificar o mesmo MAC várias vezes seguidas
struct MacCache {
  String mac;
  unsigned long ultimaNotificacao;
};

MacCache cache[MAX_MACS_CACHE];
int cacheCount = 0;

// ─── Funções auxiliares ───────────────────────────────────────

void piscarLED(int vezes, int intervaloMs = 100) {
  for (int i = 0; i < vezes; i++) {
    digitalWrite(LED_PIN, HIGH);
    delay(intervaloMs);
    digitalWrite(LED_PIN, LOW);
    delay(intervaloMs);
  }
}

/**
 * Verifica se o MAC está em cooldown (já foi notificado recentemente).
 * Retorna true se PODE notificar, false se ainda está em cooldown.
 */
bool podeNotificar(const String& mac) {
  unsigned long agora = millis();

  for (int i = 0; i < cacheCount; i++) {
    if (cache[i].mac == mac) {
      if (agora - cache[i].ultimaNotificacao < COOLDOWN_MS) {
        return false; // ainda em cooldown
      }
      // Cooldown expirou — atualiza o timestamp
      cache[i].ultimaNotificacao = agora;
      return true;
    }
  }

  // MAC não está no cache — adiciona
  if (cacheCount < MAX_MACS_CACHE) {
    cache[cacheCount].mac = mac;
    cache[cacheCount].ultimaNotificacao = agora;
    cacheCount++;
  } else {
    // Cache cheio — substitui o mais antigo
    int maisAntigoIdx = 0;
    for (int i = 1; i < MAX_MACS_CACHE; i++) {
      if (cache[i].ultimaNotificacao < cache[maisAntigoIdx].ultimaNotificacao) {
        maisAntigoIdx = i;
      }
    }
    cache[maisAntigoIdx].mac = mac;
    cache[maisAntigoIdx].ultimaNotificacao = agora;
  }

  return true;
}

/**
 * Envia o MAC para o Laravel via HTTP POST.
 * Retorna true se a requisição foi bem-sucedida.
 */
bool notificarLaravel(const String& mac, int rssi) {
  if (WiFi.status() != WL_CONNECTED) {
    Serial.println("[WiFi] Sem conexão — pulando notificação");
    return false;
  }

  HTTPClient http;
  http.begin(API_URL);
  http.addHeader("Content-Type", "application/json");
  http.addHeader("Authorization", String("Bearer ") + GATEWAY_TOKEN);
  http.setTimeout(5000); // 5 segundos de timeout

  // Monta o JSON
  StaticJsonDocument<128> doc;
  doc["mac_address"] = mac;
  doc["rssi"]        = rssi;

  String body;
  serializeJson(doc, body);

  Serial.printf("[HTTP] POST %s | MAC: %s | RSSI: %d\n", API_URL, mac.c_str(), rssi);

  int httpCode = http.POST(body);

  if (httpCode > 0) {
    String response = http.getString();
    Serial.printf("[HTTP] Resposta %d: %s\n", httpCode, response.c_str());
    http.end();
    return (httpCode == 200 || httpCode == 201);
  } else {
    Serial.printf("[HTTP] Erro: %s\n", http.errorToString(httpCode).c_str());
    http.end();
    return false;
  }
}

/**
 * Conecta ao WiFi com retry. Pisca LED enquanto aguarda.
 */
void conectarWiFi() {
  Serial.printf("[WiFi] Conectando a %s", WIFI_SSID);
  WiFi.mode(WIFI_STA);
  WiFi.begin(WIFI_SSID, WIFI_PASSWORD);

  int tentativas = 0;
  while (WiFi.status() != WL_CONNECTED && tentativas < 30) {
    delay(500);
    Serial.print(".");
    digitalWrite(LED_PIN, !digitalRead(LED_PIN)); // pisca enquanto conecta
    tentativas++;
  }

  if (WiFi.status() == WL_CONNECTED) {
    Serial.printf("\n[WiFi] Conectado! IP: %s\n", WiFi.localIP().toString().c_str());
    piscarLED(3, 200); // 3 piscadas = conectado
  } else {
    Serial.println("\n[WiFi] Falha na conexão. Reiniciando...");
    delay(3000);
    ESP.restart();
  }
}

// ─── Callback do BLE Scan ─────────────────────────────────────

class GatewayCallback : public BLEAdvertisedDeviceCallbacks {
  void onResult(BLEAdvertisedDevice device) override {
    int rssi = device.getRSSI();

    // Filtra por intensidade de sinal — ignora dispositivos distantes
    if (rssi < RSSI_MINIMO) {
      return;
    }

    String mac = String(device.getAddress().toString().c_str());
    mac.toUpperCase();

    Serial.printf("[BLE] Detectado: %s | RSSI: %d dBm", mac.c_str(), rssi);

    // Exibe o nome do dispositivo se disponível (útil para debug)
    if (device.haveName()) {
      Serial.printf(" | Nome: %s", device.getName().c_str());
    }
    Serial.println();

    // Verifica cooldown antes de notificar
    if (!podeNotificar(mac)) {
      Serial.printf("[BLE] %s em cooldown — ignorando\n", mac.c_str());
      return;
    }

    // Notifica o Laravel
    Serial.printf("[BLE] Notificando chegada: %s\n", mac.c_str());
    bool ok = notificarLaravel(mac, rssi);

    if (ok) {
      piscarLED(2, 150); // 2 piscadas rápidas = notificado com sucesso
    } else {
      piscarLED(5, 50);  // 5 piscadas rápidas = falha na notificação
    }
  }
};

// ─── Setup e Loop ─────────────────────────────────────────────

void setup() {
  Serial.begin(115200);
  pinMode(LED_PIN, OUTPUT);

  Serial.println("\n╔══════════════════════════════╗");
  Serial.println("║  Escola BLE Gateway v1.0     ║");
  Serial.println("╚══════════════════════════════╝\n");

  // Conecta ao WiFi
  conectarWiFi();

  // Inicializa BLE
  BLEDevice::init("ESP32-Gateway-Escola");
  pBLEScan = BLEDevice::getScan();
  pBLEScan->setAdvertisedDeviceCallbacks(new GatewayCallback(), true);
  pBLEScan->setActiveScan(true);  // Active scan = obtém mais informações do dispositivo
  pBLEScan->setInterval(100);     // ms entre scans
  pBLEScan->setWindow(99);        // ms de janela de escuta (deve ser <= interval)

  Serial.printf("[BLE] Scanner iniciado | RSSI mínimo: %d dBm | Cooldown: %ds\n",
                RSSI_MINIMO, COOLDOWN_MS / 1000);
  Serial.println("[BLE] Aguardando chaveiros...\n");
}

void loop() {
  // Reconecta WiFi se cair
  if (WiFi.status() != WL_CONNECTED) {
    Serial.println("[WiFi] Conexão perdida. Reconectando...");
    conectarWiFi();
  }

  // Executa um ciclo de scan BLE
  // O callback onResult() é chamado para cada dispositivo encontrado
  BLEScanResults* results = pBLEScan->start(SCAN_DURATION, false);

  Serial.printf("[BLE] Ciclo concluído | Dispositivos encontrados: %d\n",
                results->getCount());

  // Limpa os resultados para o próximo ciclo
  pBLEScan->clearResults();

  // Pequena pausa entre ciclos para não sobrecarregar
  delay(500);
}
