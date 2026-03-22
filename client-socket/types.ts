// src/types.ts

/** Payload enviado pelo Gateway BLE via HTTP para o Laravel */
export interface TagEventoPayload {
  mac_address: string
  key?: string
  passkey?: string
  rssi?: number
  timestamp?: string
}

/** Dados do aluno/responsável resolvidos a partir da tag */
export interface AlunoInfo {
  aluno_id: string
  aluno_nome: string
  matricula: string | null
  sala_id: string
  sala_nome: string | null
  sala_sigla: string | null
  tag_id: string
  tag_apelido: string | null
  responsavel: string | null
  mac_address: string
}

/**
 * Evento emitido pelo socket para os clientes.
 * Vai para o room "public" e para o room "sala:{sala_id}"
 */
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
  chegou_em: string // ISO 8601
}

/**
 * Evento de erro, emitido apenas para o autor da requisição
 */
export interface ErroEvent {
  tipo: 'erro'
  mensagem: string
  mac_address?: string
}

/** Payload enviado pelo Laravel para o socket via HTTP interno */
export interface InternalTagPayload {
  mac_address: string
  secret: string // valida que veio do Laravel
}

/** Estado da conexão de um cliente */
export interface ClienteInfo {
  tipo: 'professor' | 'publico' | 'admin'
  sala_id?: string // somente para professores
}
