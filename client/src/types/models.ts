export interface PaginatedResponse<T> {
  current_page: number;
  data: T[];
  first_page_url: string;
  from: number;
  last_page: number;
  last_page_url: string;
  next_page_url: string | null;
  path: string;
  per_page: number;
  prev_page_url: string | null;
  to: number;
  total: number;
}

export interface ApiResponse<T> {
  success?: boolean;
  message?: string;
  data?: T;
  // If the endpoint is paginated but we don't use the 'data' wrapper:
  // Sometimes Laravel directly returns the paginated object if we don't wrap it.
  // Assuming the `success` wrapper places the payload in `data`.
}

// Basic Entity
export interface BaseEntity {
  id: string;
  created_at?: string;
  updated_at?: string;
}

export interface Aluno extends BaseEntity {
  nome: string;
  nascimento: string;
  matricula: string;
  anexo_id?: string | null;
  dados_adicionais?: Record<string, any> | null;
  tags?: Tag[];
  salas?: Sala[];
  anexo?: any; // You can specify Anexo type if needed
}

export interface Sala extends BaseEntity {
  tp_dominio_turno_id?: string | null;
  tp_dominio_periodo_id?: string | null;
  ano: number;
  nome: string;
  sigla?: string | null;
  dados_adicionais?: Record<string, any> | null;
  alunos?: Aluno[];
  usuarios?: Usuario[];
  turno?: any;
  periodo?: any;
}

export interface Usuario extends BaseEntity {
  name: string;
  email: string;
  is_gestor: boolean;
  dark_mode: boolean;
  change_pass: boolean;
  // relations
  anexo?: any;
}

export interface Tag extends BaseEntity {
  apelido: string;
  mac_address: string;
  key?: string | null;
  passkey?: string | null;
  responsavel?: string | null;
  dados_adicionais?: Record<string, any> | null;
  alunos?: Aluno[];
}

export interface Config extends BaseEntity {
  // Add more fields if needed
  [key: string]: any;
}

export interface Dominio extends BaseEntity {
  // Add more fields if needed
  [key: string]: any;
}

export interface TpDominio extends BaseEntity {
  // Add more fields if needed
  [key: string]: any;
}

export interface Anexo extends BaseEntity {
  [key: string]: any;
}

export interface AlunoSala extends BaseEntity {
  aluno_id: string;
  sala_id: string;
}

export interface TagAluno extends BaseEntity {
  tag_id: string;
  aluno_id: string;
}

export interface UsuarioSala extends BaseEntity {
  usuario_id: string;
  sala_id: string;
}
