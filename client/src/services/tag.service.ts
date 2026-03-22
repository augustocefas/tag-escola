import { api } from '../lib/apiClient';
import { Tag, PaginatedResponse, ApiResponse } from '../types/models';

export const tagService = {
  getAll: async (params?: any): Promise<ApiResponse<Tag[] | PaginatedResponse<Tag>>> => {
    const { data } = await api.get('/tags', { params });
    return data;
  },

  getById: async (id: string): Promise<ApiResponse<Tag>> => {
    const { data } = await api.get(`/tags/${id}`);
    return data;
  },

  create: async (payload: Partial<Tag>): Promise<ApiResponse<Tag>> => {
    const { data } = await api.post('/tags', payload);
    return data;
  },

  update: async (id: string, payload: Partial<Tag>): Promise<ApiResponse<Tag>> => {
    const { data } = await api.put(`/tags/${id}`, payload);
    return data;
  },

  delete: async (id: string): Promise<ApiResponse<void>> => {
    const { data } = await api.delete(`/tags/${id}`);
    return data;
  },
};
