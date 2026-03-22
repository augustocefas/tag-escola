<?php
// app/Http/Controllers/TagEventoController.php

namespace App\Http\Controllers;


use App\Http\Controllers\Client\AController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TagEventoController extends AController
{
    /**
     * Recebe o evento do Gateway BLE e repassa para o servidor Socket.
     *
     * O gateway deve chamar:
     * POST /api/tag/evento
     * Authorization: Bearer {gateway_token}
     * Body: { "mac_address": "AA:BB:CC:DD:EE:FF" }
     *
     * Adicione no .env do Laravel:
     *   SOCKET_SERVER_URL=http://localhost:3001
     *   SOCKET_INTERNAL_SECRET=mesmo_valor_do_.env_do_socket
     *   GATEWAY_TOKEN=token_que_o_gateway_envia
     */
    public function receber(Request $request): JsonResponse
    {
        // 1. Valida o token do gateway
        $token = $request->bearerToken();
        if ($token !== config('services.gateway.token')) {
            Log::warning('Tag evento: token de gateway inválido', [
                'ip' => $request->ip(),
            ]);
            return response()->json(['erro' => 'Não autorizado'], 401);
        }

        // 2. Valida o payload
        $validated = $request->validate([
            'mac_address' => ['required', 'string', 'regex:/^([0-9A-Fa-f]{2}[:-]){5}[0-9A-Fa-f]{2}$/'],
        ]);

        $mac = strtoupper($validated['mac_address']);

        try {
            // 3. Repassa para o servidor socket (chamada HTTP interna)
            $response = Http::timeout(5)->post(
                config('services.socket.url') . '/interno/tag-detectada',
                [
                    'mac_address' => $mac,
                    'secret'      => config('services.socket.secret'),
                ]
            );

            if ($response->failed()) {
                Log::error('Tag evento: falha ao notificar socket', [
                    'mac'    => $mac,
                    'status' => $response->status(),
                    'body'   => $response->body(),
                ]);
                return response()->json(['erro' => 'Falha ao notificar'], 502);
            }

            return response()->json($response->json());

        } catch (\Exception $e) {
            Log::error('Tag evento: exceção ao contatar socket', [
                'mac'   => $mac,
                'error' => $e->getMessage(),
            ]);
            return response()->json(['erro' => 'Servidor socket indisponível'], 503);
        }
    }
}
