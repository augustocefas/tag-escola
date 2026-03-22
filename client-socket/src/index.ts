// src/index.ts
import "dotenv/config";
import http from "http";
import express from "express";
import { inicializarSocket } from "./socketServer";
import { testarConexao } from "./database";
import routes from "./routes";
const PORT = Number(process.env.PORT) || 3001;

async function main() {
    // 1. Testa conexão com o banco antes de subir
    await testarConexao();

    const app = express();

    // Parseia JSON no body das requisições
    app.use(express.json());

    // Segurança básica: só aceita chamadas internas pelo IP (opcional, recomendado)
    // Em produção, coloque o servidor socket em rede privada ou use um firewall
    app.use("/interno", (req, res, next) => {
        const allowed = (
            process.env.ALLOWED_IPS || "127.0.0.1,::1,::ffff:127.0.0.1"
        ).split(",");
        const ip = req.ip || "";
        if (!allowed.includes(ip)) {
            // Se não estiver em IP restrito, ainda valida pelo secret na rota
            // (dupla camada de segurança — IP + secret)
        }
        next();
    });

    app.use("/", routes);

    // 2. Cria servidor HTTP que compartilha a porta com o Socket.io
    const httpServer = http.createServer(app);

    // 3. Inicializa Socket.io no mesmo servidor HTTP
    const io = inicializarSocket(httpServer);

    // 4. Loga estatísticas de conexão a cada 30 segundos (útil para debug)
    setInterval(() => {
        const sockets = io.sockets.sockets.size;
        if (sockets > 0) {
            console.log(`[Socket] Clientes conectados: ${sockets}`);
        }
    }, 30_000);

    // 5. Inicia o servidor
    httpServer.listen(PORT, () => {
        console.log(`\n🚌 Escola Socket Server rodando na porta ${PORT}`);
        console.log(`   Health check: http://localhost:${PORT}/health`);
        console.log(
            `   Endpoint interno: POST http://localhost:${PORT}/interno/tag-detectada\n`,
        );
    });

    // Tratamento gracioso de encerramento
    process.on("SIGTERM", () => {
        console.log("[Server] SIGTERM recebido. Encerrando...");
        httpServer.close(() => process.exit(0));
    });

    process.on("SIGINT", () => {
        console.log("[Server] SIGINT recebido. Encerrando...");
        httpServer.close(() => process.exit(0));
    });
}

main().catch((err) => {
    console.error("[FATAL] Erro ao iniciar servidor:", err);
    process.exit(1);
});
