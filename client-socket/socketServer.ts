// src/socketServer.ts
import { Server as SocketIOServer, Socket } from 'socket.io'
import { Server as HttpServer } from 'http'
import { ClienteInfo, ResponsavelChegouEvent } from './types'

let io: SocketIOServer | null = null

/**
 * Inicializa o servidor Socket.io com as configurações de CORS e rooms.
 *
 * Rooms utilizados:
 *   "public"       → tela pública (portal da escola, totens)
 *   "sala:{uuid}"  → tela específica de cada professor
 *   "admin"        → painel administrativo
 */
export function inicializarSocket(httpServer: HttpServer): SocketIOServer {
  const origensPermitidas = (process.env.CORS_ORIGINS || 'http://localhost:5173')
    .split(',')
    .map((s) => s.trim())

  io = new SocketIOServer(httpServer, {
    cors: {
      origin: origensPermitidas,
      methods: ['GET', 'POST'],
      credentials: true,
    },
    // Permite reconexão automática do cliente
    pingTimeout: 20000,
    pingInterval: 10000,
  })

  io.on('connection', (socket: Socket) => {
    console.log(`[Socket] Cliente conectado: ${socket.id}`)

    /**
     * Cliente se identifica após conectar.
     * O front-end deve emitir "entrar" logo após a conexão:
     *
     * Para professor:
     *   socket.emit('entrar', { tipo: 'professor', sala_id: 'uuid-da-sala' })
     *
     * Para tela pública:
     *   socket.emit('entrar', { tipo: 'publico' })
     *
     * Para admin:
     *   socket.emit('entrar', { tipo: 'admin' })
     */
    socket.on('entrar', (dados: ClienteInfo) => {
      // Sai de todos os rooms anteriores (exceto o room do próprio socket)
      socket.rooms.forEach((room) => {
        if (room !== socket.id) socket.leave(room)
      })

      if (dados.tipo === 'professor' && dados.sala_id) {
        const room = `sala:${dados.sala_id}`
        socket.join(room)
        console.log(`[Socket] Professor entrou no room: ${room} (${socket.id})`)
        socket.emit('entrou', { room, mensagem: `Aguardando notificações da sala` })

      } else if (dados.tipo === 'publico') {
        socket.join('public')
        console.log(`[Socket] Tela pública conectada (${socket.id})`)
        socket.emit('entrou', { room: 'public', mensagem: 'Tela pública conectada' })

      } else if (dados.tipo === 'admin') {
        socket.join('admin')
        socket.join('public') // admin recebe tudo
        console.log(`[Socket] Admin conectado (${socket.id})`)
        socket.emit('entrou', { room: 'admin', mensagem: 'Painel admin conectado' })

      } else {
        socket.emit('erro', { tipo: 'erro', mensagem: 'Dados de identificação inválidos' })
      }
    })

    socket.on('disconnect', (reason) => {
      console.log(`[Socket] Cliente desconectado: ${socket.id} — motivo: ${reason}`)
    })

    socket.on('error', (err) => {
      console.error(`[Socket] Erro no socket ${socket.id}:`, err)
    })
  })

  return io
}

/**
 * Emite o evento de chegada do responsável para os clientes corretos.
 *
 * - Room "sala:{sala_id}" → tela do professor daquela sala
 * - Room "public"         → tela pública (portal)
 * - Room "admin"          → painel admin (já escuta public também)
 */
export function emitirChegadaResponsavel(evento: ResponsavelChegouEvent): void {
  if (!io) {
    console.error('[Socket] IO não inicializado!')
    return
  }

  const roomSala = `sala:${evento.sala_id}`

  // Emite para a sala específica do professor
  io.to(roomSala).emit('responsavel_chegou', evento)

  // Emite para a tela pública
  io.to('public').emit('responsavel_chegou', evento)

  console.log(
    `[Socket] Evento emitido → ${roomSala} + public | Aluno: ${evento.aluno_nome} | Responsável: ${evento.responsavel ?? 'desconhecido'}`
  )
}

export function getIO(): SocketIOServer {
  if (!io) throw new Error('Socket.io não foi inicializado')
  return io
}
