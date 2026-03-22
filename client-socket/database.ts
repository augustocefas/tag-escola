// src/database.ts
import mysql from 'mysql2/promise'
import { AlunoInfo } from './types'

let pool: mysql.Pool | null = null

export function getPool(): mysql.Pool {
  if (!pool) {
    pool = mysql.createPool({
      host: process.env.DB_HOST || 'localhost',
      port: Number(process.env.DB_PORT) || 3306,
      user: process.env.DB_USER || 'root',
      password: process.env.DB_PASSWORD || '',
      database: process.env.DB_NAME || '',
      waitForConnections: true,
      connectionLimit: 10,
      queueLimit: 0,
      timezone: '+00:00',
    })
  }
  return pool
}

/**
 * Busca os dados do aluno e sala a partir do MAC address da tag BLE.
 *
 * Fluxo: tag → tag_aluno → aluno → aluno_sala → sala
 * Um aluno pode estar em várias salas (ex: manhã e tarde),
 * por isso retornamos todas as combinações ativas.
 */
export async function buscarAlunoPorMac(
  mac_address: string
): Promise<AlunoInfo[]> {
  const db = getPool()

  const [rows] = await db.execute<mysql.RowDataPacket[]>(
    `
    SELECT
      a.id           AS aluno_id,
      a.nome         AS aluno_nome,
      a.matricula,
      s.id           AS sala_id,
      s.nome         AS sala_nome,
      s.sigla        AS sala_sigla,
      t.id           AS tag_id,
      t.apelido      AS tag_apelido,
      t.responsavel,
      t.mac_address
    FROM tag t
      INNER JOIN tag_aluno  ta ON ta.tag_id  = t.id
      INNER JOIN aluno       a  ON a.id       = ta.aluno_id
      INNER JOIN aluno_sala  as2 ON as2.aluno_id = a.id
      INNER JOIN sala        s  ON s.id       = as2.sala_id
    WHERE
      t.mac_address = ?
      AND a.deleted_at IS NULL
      AND s.deleted_at IS NULL
    `,
    [mac_address]
  )

  return rows as AlunoInfo[]
}

/**
 * Alternativa: busca por key da tag (caso o gateway envie a key ao invés do MAC)
 */
export async function buscarAlunoPorKey(
  key: string
): Promise<AlunoInfo[]> {
  const db = getPool()

  const [rows] = await db.execute<mysql.RowDataPacket[]>(
    `
    SELECT
      a.id           AS aluno_id,
      a.nome         AS aluno_nome,
      a.matricula,
      s.id           AS sala_id,
      s.nome         AS sala_nome,
      s.sigla        AS sala_sigla,
      t.id           AS tag_id,
      t.apelido      AS tag_apelido,
      t.responsavel,
      t.mac_address
    FROM tag t
      INNER JOIN tag_aluno  ta  ON ta.tag_id   = t.id
      INNER JOIN aluno       a   ON a.id        = ta.aluno_id
      INNER JOIN aluno_sala  as2 ON as2.aluno_id = a.id
      INNER JOIN sala        s   ON s.id        = as2.sala_id
    WHERE
      t.key = ?
      AND a.deleted_at IS NULL
      AND s.deleted_at IS NULL
    `,
    [key]
  )

  return rows as AlunoInfo[]
}

export async function testarConexao(): Promise<void> {
  const db = getPool()
  await db.execute('SELECT 1')
  console.log('[DB] Conexão com MySQL estabelecida.')
}
