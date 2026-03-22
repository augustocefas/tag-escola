// hooks/useEscolaSocket.ts
// Instale: npm install socket.io-client

import { useEffect, useRef, useCallback, useState } from 'react'
import { io, Socket } from 'socket.io-client'

export interface ResponsavelChegouEvent {
  tipo: 'responsavel_chegou'
  sala_id: string
  sala_nome: string | null
  sala_sigla: string | null
  aluno_id: string
  aluno_nome: string
  matricula: string | null
  responsavel: string | null
  tag_apelido: string | null
  mac_address: string
  chegou_em: string
}

interface UseEscolaSocketOptions {
  /** URL do servidor socket */
  serverUrl?: string
  /** 'professor' precisa de sala_id. 'publico' e 'admin' não. */
  tipo: 'professor' | 'publico' | 'admin'
  sala_id?: string
  onChegada?: (evento: ResponsavelChegouEvent) => void
}

interface UseEscolaSocketReturn {
  conectado: boolean
  eventos: ResponsavelChegouEvent[]
  limparEventos: () => void
}

export function useEscolaSocket({
  serverUrl = import.meta.env.VITE_SOCKET_URL || 'http://localhost:3001',
  tipo,
  sala_id,
  onChegada,
}: UseEscolaSocketOptions): UseEscolaSocketReturn {
  const socketRef = useRef<Socket | null>(null)
  const [conectado, setConectado] = useState(false)
  const [eventos, setEventos] = useState<ResponsavelChegouEvent[]>([])

  const limparEventos = useCallback(() => setEventos([]), [])

  useEffect(() => {
    const socket = io(serverUrl, {
      transports: ['websocket'],
      reconnection: true,
      reconnectionDelay: 2000,
      reconnectionAttempts: Infinity,
    })

    socketRef.current = socket

    socket.on('connect', () => {
      setConectado(true)
      console.log('[Socket] Conectado:', socket.id)

      // Identifica o tipo de cliente logo após conectar
      socket.emit('entrar', { tipo, sala_id })
    })

    socket.on('disconnect', () => {
      setConectado(false)
      console.log('[Socket] Desconectado')
    })

    socket.on('responsavel_chegou', (evento: ResponsavelChegouEvent) => {
      console.log('[Socket] Responsável chegou:', evento)

      setEventos((prev) => {
        // Evita duplicatas (mesmo aluno + mesmo timestamp)
        const jaExiste = prev.some(
          (e) => e.aluno_id === evento.aluno_id && e.chegou_em === evento.chegou_em
        )
        if (jaExiste) return prev
        // Mantém os 50 eventos mais recentes
        return [evento, ...prev].slice(0, 50)
      })

      onChegada?.(evento)
    })

    socket.on('connect_error', (err) => {
      console.error('[Socket] Erro de conexão:', err.message)
    })

    return () => {
      socket.disconnect()
    }
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, [serverUrl, tipo, sala_id])

  return { conectado, eventos, limparEventos }
}
