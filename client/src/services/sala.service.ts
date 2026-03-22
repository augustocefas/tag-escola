import { api } from '../lib/apiClient';
import { Sala, PaginatedResponse, ApiResponse } from '../types/models';

export const salaService = {
  getAll: async (params?: any): Promise<ApiResponse<Sala[] | PaginatedResponse<Sala>>> => {
    const { data } = await api.get('/salas', { params });
    return data;
  },

  getById: async (id: string): Promise<ApiResponse<Sala>> => {
    const { data } = await api.get(`/salas/${id}`);
    return data;
  },

  create: async (payload: Partial<Sala>): Promise<ApiResponse<Sala>> => {
    const { data } = await api.post('/salas', payload);
    return data;
  },

  update: async (id: string, payload: Partial<Sala>): Promise<ApiResponse<Sala>> => {
    const { data } = await api.put(`/salas/${id}`, payload);
    return data;
  },

  delete: async (id: string): Promise<ApiResponse<void>> => {
    const { data } = await api.delete(`/salas/${id}`);
    return data;
  },
};
