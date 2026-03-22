// src/routes.ts
import { Router, Request, Response } from 'express'
import { buscarAlunoPorMac } from './database'
import { emitirChegadaResponsavel } from './socketServer'
import { InternalTagPayload, ResponsavelChegouEvent } from './types'

const router = Router()

/**
 * POST /interno/tag-detectada
 *
 * Chamado pelo Laravel quando o gateway BLE informa que uma tag chegou.
 *
 * Body esperado:
 * {
 *   "mac_address": "AA:BB:CC:DD:EE:FF",
 *   "secret": "valor_do_INTERNAL_SECRET"
 * }
 *
 * No Laravel, adicione no .env:
 *   SOCKET_SERVER_URL=http://localhost:3001
 *   SOCKET_INTERNAL_SECRET=mesmo_valor_do_.env_do_socket
 *
 * No controller Laravel:
 *   Http::post(config('services.socket.url') . '/interno/tag-detectada', [
 *     'mac_address' => $macAddress,
 *     'secret'      => config('services.socket.secret'),
 *   ]);
 */
router.post('/interno/tag-detectada', async (req: Request, res: Response) => {
  const { mac_address, secret } = req.body as InternalTagPayload

  // 1. Valida o segredo interno
  if (!secret || secret !== process.env.INTERNAL_SECRET) {
    console.warn(`[HTTP] Tentativa com secret inválido de ${req.ip}`)
    return res.status(401).json({ erro: 'Não autorizado' })
  }

  // 2. Valida o MAC
  if (!mac_address || typeof mac_address !== 'string') {
    return res.status(400).json({ erro: 'mac_address é obrigatório' })
  }

  const mac = mac_address.toUpperCase().trim()

  try {
    // 3. Busca aluno(s) vinculados a essa tag no banco
    const alunos = await buscarAlunoPorMac(mac)

    if (alunos.length === 0) {
      console.log(`[HTTP] Tag não vinculada: ${mac}`)
      return res.status(404).json({ erro: 'Tag não vinculada a nenhum aluno' })
    }

    const chegou_em = new Date().toISOString()

    // 4. Um responsável pode ter filhos em salas diferentes
    //    Emitimos um evento para cada aluno/sala
    for (const aluno of alunos) {
      const evento: ResponsavelChegouEvent = {
        tipo: 'responsavel_chegou',
        sala_id: aluno.sala_id,
        sala_nome: aluno.sala_nome,
        sala_sigla: aluno.sala_sigla,
        aluno_id: aluno.aluno_id,
        aluno_nome: aluno.aluno_nome,
        matricula: aluno.matricula,
        responsavel: aluno.responsavel,
        tag_apelido: aluno.tag_apelido,
        mac_address: aluno.mac_address,
        chegou_em,
      }

      emitirChegadaResponsavel(evento)
    }

    return res.json({
      ok: true,
      notificados: alunos.map((a) => ({
        aluno: a.aluno_nome,
        sala: a.sala_nome ?? a.sala_sigla,
      })),
    })

  } catch (err) {
    console.error('[HTTP] Erro ao processar tag:', err)
    return res.status(500).json({ erro: 'Erro interno do servidor' })
  }
})

/**
 * GET /health
 * Endpoint de health check para monitoramento
 */
router.get('/health', (_req: Request, res: Response) => {
  res.json({ status: 'ok', uptime: process.uptime() })
})

export default router
