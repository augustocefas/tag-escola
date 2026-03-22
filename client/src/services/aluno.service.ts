import { api } from '../lib/apiClient';
import { Aluno, PaginatedResponse, ApiResponse } from '../types/models';

export const alunoService = {
  getAll: async (params?: any): Promise<ApiResponse<Aluno[] | PaginatedResponse<Aluno>>> => {
    const { data } = await api.get('/alunos', { params });
    return data;
  },

  getById: async (id: string): Promise<ApiResponse<Aluno>> => {
    const { data } = await api.get(`/alunos/${id}`);
    return data;
  },

  create: async (payload: Partial<Aluno>): Promise<ApiResponse<Aluno>> => {
    const { data } = await api.post('/alunos', payload);
    return data;
  },

  update: async (id: string, payload: Partial<Aluno>): Promise<ApiResponse<Aluno>> => {
    const { data } = await api.put(`/alunos/${id}`, payload);
    return data;
  },

  delete: async (id: string): Promise<ApiResponse<void>> => {
    const { data } = await api.delete(`/alunos/${id}`);
    return data;
  },
};
