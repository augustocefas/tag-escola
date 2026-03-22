import { api } from '../lib/apiClient';
import { Usuario, PaginatedResponse, ApiResponse } from '../types/models';

export const usuarioService = {
  getAll: async (params?: any): Promise<ApiResponse<Usuario[] | PaginatedResponse<Usuario>>> => {
    const { data } = await api.get('/usuarios', { params });
    return data;
  },

  create: async (payload: Partial<Usuario>): Promise<ApiResponse<Usuario>> => {
    const { data } = await api.post('/usuarios', payload);
    return data;
  },

  update: async (id: string, payload: Partial<Usuario>): Promise<ApiResponse<Usuario>> => {
    const { data } = await api.put(`/usuarios/${id}`, payload);
    return data;
  },

  delete: async (id: string): Promise<ApiResponse<void>> => {
    const { data } = await api.delete(`/usuarios/${id}`);
    return data;
  },

  setDarkMode: async (darkMode: boolean): Promise<ApiResponse<Usuario>> => {
    const { data } = await api.post(`/usuarios/set-dark-mode/${darkMode}`);
    return data;
  },

  changePassword: async (payload: any): Promise<ApiResponse<any>> => {
    const { data } = await api.post('/usuarios/change-password', payload);
    return data;
  },
};
