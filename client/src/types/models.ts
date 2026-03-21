

export interface Usuario {
  id: string;
  escritorio_id?: string | null;
  name: string;
  email: string;
  is_gestor: boolean;
  dark_mode?: boolean;
  created_at?: string;
  updated_at?: string;
}


export type ConfigResponse = {
  initial_state?: Record<string, string>;
  [key: string]: string | Record<string, string> | undefined;
};
