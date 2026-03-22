/**
 * Gateway BLE → WiFi para ESP32 — v1.2
 * Sem ArduinoJson — JSON montado com snprintf
 */

#include <BLEDevice.h>
#include <BLEScan.h>
#include <BLEAdvertisedDevice.h>
#include <WiFi.h>
#include <HTTPClient.h>

// ─── CONFIGURAÇÕES ──────────────────────────────

const char* WIFI_SSID     = "TODDY 2.4";
const char* WIFI_PASSWORD = "@vocecria";
const char* API_URL       = "http://192.168.1.51/api/tag/evento";
const char* GATEWAY_TOKEN = "0851bf791d045d4a561a74556403f2a29d2b049085e42f92c30fabc181d5b6a2";

const int  SCAN_DURATION = 3;
const int  RSSI_MINIMO   = -50;
// -40 -> ~1m
// -60 -> ~3-5m
// -70 -> ~5-10m
// -80 -> ~10-20m
// -85 -> ~20-301m
// -90 -> ~30-50m
const long COOLDOWN_MS   = 30000;
const int  LED_PIN       = 2;
const int  MAX_CACHE     = 50;

// ─── Cache com char fixo (sem String) ────────────

struct MacCache {
  char mac[18];
  unsigned long ts;
};

MacCache cache[MAX_CACHE];
int cacheCount = 0;

BLEScan* pBLEScan;

// ─── Helpers ─────────────────────────────────────

void piscarLED(int n, int ms = 100) {
  for (int i = 0; i < n; i++) {
    digitalWrite(LED_PIN, HIGH); delay(ms);
    digitalWrite(LED_PIN, LOW);  delay(ms);
  }
}

bool podeNotificar(const char* mac) {
  unsigned long agora = millis();
  for (int i = 0; i < cacheCount; i++) {
    if (strncmp(cache[i].mac, mac, 17) == 0) {
      if (agora - cache[i].ts < (unsigned long)COOLDOWN_MS) return false;
      cache[i].ts = agora;
      return true;
    }
  }
  // Novo MAC — encontra slot
  int idx = (cacheCount < MAX_CACHE) ? cacheCount++ : 0;
  if (cacheCount == MAX_CACHE) {
    for (int i = 1; i < MAX_CACHE; i++)
      if (cache[i].ts < cache[idx].ts) idx = i;
  }
  strncpy(cache[idx].mac, mac, 17);
  cache[idx].mac[17] = '\0';
  cache[idx].ts = agora;
  return true;
}

bool notificarLaravel(const char* mac, int rssi) {
  if (WiFi.status() != WL_CONNECTED) return false;

  HTTPClient http;
  http.begin(API_URL);
  http.addHeader("Content-Type", "application/json");
  http.addHeader("Authorization", String("Bearer ") + GATEWAY_TOKEN);
  http.setTimeout(5000);

  // JSON sem biblioteca externa
  char body[80];
  snprintf(body, sizeof(body), "{\"mac_address\":\"%s\",\"rssi\":%d}", mac, rssi);

  Serial.printf("[HTTP] %s | RSSI:%d\n", mac, rssi);
  int code = http.POST(body);
  Serial.printf("[HTTP] %d\n", code);
  http.end();

  return (code == 200 || code == 201);
}

void conectarWiFi() {
  Serial.printf("[WiFi] Conectando a %s", WIFI_SSID);
  WiFi.mode(WIFI_STA);
  WiFi.begin(WIFI_SSID, WIFI_PASSWORD);
  int t = 0;
  while (WiFi.status() != WL_CONNECTED && t++ < 30) {
    delay(500); Serial.print(".");
    digitalWrite(LED_PIN, !digitalRead(LED_PIN));
  }
  if (WiFi.status() == WL_CONNECTED) {
    Serial.printf("\n[WiFi] IP: %s\n", WiFi.localIP().toString().c_str());
    piscarLED(3, 200);
  } else {
    Serial.println("\n[WiFi] Falha. Reiniciando...");
    delay(3000); ESP.restart();
  }
}

// ─── Callback BLE ─────────────────────────────────

class GatewayCallback : public BLEAdvertisedDeviceCallbacks {
  void onResult(BLEAdvertisedDevice dev) override {
    int rssi = dev.getRSSI();
    if (rssi < RSSI_MINIMO) return;

    char mac[18];
    strncpy(mac, dev.getAddress().toString().c_str(), 17);
    mac[17] = '\0';
    for (int i = 0; mac[i]; i++) mac[i] = toupper(mac[i]);

    Serial.printf("[BLE] %s RSSI:%d\n", mac, rssi);

    if (!podeNotificar(mac)) return;

    bool ok = notificarLaravel(mac, rssi);
    piscarLED(ok ? 2 : 5, ok ? 150 : 50);
  }
};

// ─── Setup / Loop ─────────────────────────────────

void setup() {
  Serial.begin(115200);
  pinMode(LED_PIN, OUTPUT);
  Serial.println("\n[Escola] BLE Gateway v1.2\n");

  conectarWiFi();

  BLEDevice::init("");
  pBLEScan = BLEDevice::getScan();
  pBLEScan->setAdvertisedDeviceCallbacks(new GatewayCallback(), true);
  pBLEScan->setActiveScan(true);
  pBLEScan->setInterval(100);
  pBLEScan->setWindow(99);

  Serial.printf("[BLE] Pronto | RSSI min:%d | Cooldown:%ds\n",
                RSSI_MINIMO, COOLDOWN_MS / 1000);
}

void loop() {
  if (WiFi.status() != WL_CONNECTED) conectarWiFi();

  BLEScanResults* r = pBLEScan->start(SCAN_DURATION, false);
  Serial.printf("[BLE] %d dispositivos\n", r->getCount());
  pBLEScan->clearResults();
  delay(500);
}