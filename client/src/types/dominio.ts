export interface TipoDominio {
  id: string;
  tipo_dominio: string; // migrated from 'nome'
  navegacao_opc?: string;
  navegacao_subopc?: string;
  rota?: string;
  publico: boolean;
  datasource: boolean;
  icone?: string;
  fonte_cor?: string;
  bg_cor?: string;
  ativo: boolean; // migrated from 'status'
  subtitulo?: string;
  tenant_id?: string;
  created_at?: string;
  updated_at?: string;
  // Fallbacks for compatibility during transition
  nome?: string;
  descricao?: string;
  status?: boolean;
}

export interface Dominio {
  id: string;
  tipo_dominio_id: string;
  anexo_id?: string;
  dominio: string; // migrated from 'nome'
  navegacao_opc?: string;
  navegacao_subopc?: string;
  icone?: string;
  fonte_cor?: string;
  bg_cor?: string;
  ativo: boolean; // migrated from 'status'
  tenant_id?: string;
  created_at?: string;
  updated_at?: string;
  tipo_dominio?: TipoDominio;
  // Fallbacks for compatibility during transition
  nome?: string;
  descricao?: string;
  status?: boolean;
  valor?: string;
}
