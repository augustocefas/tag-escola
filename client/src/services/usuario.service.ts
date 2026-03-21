import { api } from '@/lib/apiClient';
import type { ApiResponse } from '@/types/api';
import type { Usuario } from '@/types/models';

const BASE_PATH = '/usuarios';

export const usuarioService = {
  getUsuarios: async (): Promise<ApiResponse<Usuario[]>> => {
    const { data } = await api.get(BASE_PATH);
    return data;
  },

  createUsuario: async (payload: Partial<Usuario> & { password?: string }): Promise<ApiResponse<string>> => {
    const { data } = await api.post(BASE_PATH, payload);
    return data;
  },

  updateUsuario: async (id: string, payload: Partial<Usuario> & { password?: string }): Promise<ApiResponse<string>> => {
    const { data } = await api.put(`${BASE_PATH}/${id}`, payload);
    return data;
  },

  deleteUsuario: async (id: string): Promise<ApiResponse<string>> => {
    const { data } = await api.delete(`${BASE_PATH}/${id}`);
    return data;
  },
};
