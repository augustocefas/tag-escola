import { api } from '@/lib/apiClient';
import type { ApiResponse } from '@/types/api';

const BASE_PATH = '/auth';

export interface LoginPayload {
  email: string;
  password: string;
}

export interface AuthResponse {
  status: string;
  message: string;
  data: {
    access_token: string;
    token_type: string;
    expires_in: number;
    user?: any;
  };
}

export const authService = {
  login: async (payload: LoginPayload): Promise<AuthResponse> => {
    const { data } = await api.post(`${BASE_PATH}/login`, payload);
    return data;
  },

  me: async (): Promise<ApiResponse<any>> => {
    const { data } = await api.post(`${BASE_PATH}/me`);
    return data;
  },

  logout: async (): Promise<ApiResponse<any>> => {
    const { data } = await api.post(`${BASE_PATH}/logout`);
    return data;
  },

  refresh: async (): Promise<AuthResponse> => {
    const { data } = await api.post(`${BASE_PATH}/refresh`);
    return data;
  },
};
