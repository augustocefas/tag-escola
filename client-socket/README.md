# Escola Socket Server

Servidor WebSocket (Node.js + TypeScript + Socket.io) para notificação em tempo real
da chegada de responsáveis/pais na escola via tags Bluetooth BLE.

## Fluxo completo

```
Gateway BLE → POST /api/tag/evento (Laravel)
                  ↓
           POST /interno/tag-detectada (Socket Server)
                  ↓
           Consulta MySQL: tag → aluno → sala
                  ↓
        broadcast via Socket.io
          ↙              ↘
Room "sala:{id}"      Room "public"
(tela do professor)   (tela pública + admin)
```

## Estrutura do projeto

```
src/
  index.ts        — Entry point, inicia HTTP + Socket
  socketServer.ts — Configuração do Socket.io e rooms
  routes.ts       — Endpoints HTTP internos
  database.ts     — Pool MySQL e queries
  types.ts        — Interfaces TypeScript

laravel/
  TagEventoController.php  — Controller que recebe do gateway
  services_example.php     — Configurações services.php

frontend/
  useEscolaSocket.ts       — Hook React para consumir o socket
  TelaExemplo.tsx          — Exemplos de uso nas telas
```

## Configuração e instalação

### 1. Instalar dependências

```bash
npm install
```

### 2. Configurar variáveis de ambiente

```bash
cp .env.example .env
# Edite o .env com suas configurações
```

Variáveis obrigatórias no `.env`:
| Variável | Descrição |
|---|---|
| `PORT` | Porta do servidor (padrão: 3001) |
| `INTERNAL_SECRET` | Segredo compartilhado com o Laravel |
| `DB_HOST` / `DB_USER` / `DB_PASSWORD` / `DB_NAME` | Conexão MySQL |
| `CORS_ORIGINS` | Origens permitidas para o React (separadas por vírgula) |

### 3. Configurar o Laravel

No `.env` do Laravel:
```env
SOCKET_SERVER_URL=http://localhost:3001
SOCKET_INTERNAL_SECRET=mesmo_valor_do_.env_do_socket
GATEWAY_TOKEN=token_que_o_gateway_vai_enviar
```

No `config/services.php`, adicione as entradas do arquivo `laravel/services_example.php`.

Registre a rota no `routes/api.php`:
```php
Route::post('/tag/evento', [TagEventoController::class, 'receber']);
```

### 4. Rodar em desenvolvimento

```bash
npm run dev
```

### 5. Build para produção

```bash
npm run build
npm start
```

## Rooms do Socket.io

| Room | Quem entra | O que recebe |
|---|---|---|
| `public` | Telas públicas, totens | Todas as chegadas |
| `sala:{uuid}` | Professor da sala | Chegadas dos alunos da sua sala |
| `admin` | Painel administrativo | Tudo (public + admin) |

## Eventos

### Cliente → Servidor

```js
// Identificação obrigatória após conectar
socket.emit('entrar', { tipo: 'professor', sala_id: 'uuid-da-sala' })
socket.emit('entrar', { tipo: 'publico' })
socket.emit('entrar', { tipo: 'admin' })
```

### Servidor → Cliente

```js
// Chegada de responsável
socket.on('responsavel_chegou', (evento) => {
  // evento.aluno_nome, evento.responsavel, evento.sala_nome, evento.chegou_em ...
})

// Confirmação de entrada no room
socket.on('entrou', ({ room, mensagem }) => { ... })

// Erros
socket.on('erro', ({ mensagem }) => { ... })
```

## Segurança

- **Secret interno**: O endpoint `/interno/tag-detectada` valida um `INTERNAL_SECRET` compartilhado com o Laravel.
- **Token do gateway**: O Laravel valida o `GATEWAY_TOKEN` vindo do gateway BLE.
- **CORS**: Apenas origens configuradas em `CORS_ORIGINS` são aceitas pelo Socket.io.
- **Rede**: Em produção, coloque o socket em rede privada — o endpoint `/interno` não deve ser exposto à internet.

## Produção com PM2

```bash
npm install -g pm2
npm run build
pm2 start dist/index.js --name escola-socket
pm2 save
pm2 startup
```

## Teste rápido via curl

```bash
# Simula um gateway enviando uma tag
curl -X POST http://localhost:3001/interno/tag-detectada \
  -H "Content-Type: application/json" \
  -d '{"mac_address": "AA:BB:CC:DD:EE:FF", "secret": "seu_secret_aqui"}'
```
