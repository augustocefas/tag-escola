import axios from 'axios';

import { useAuthStore } from '../stores/authStore';

export const api = axios.create({
    baseURL: import.meta.env.VITE_API_CLIENT_URL,
    withCredentials: import.meta.env.VITE_API_WITH_CREDENTIAL === 'true',
    timeout: 300000,
    headers: {
        'X-Tenant-Id': import.meta.env.VITE_API_TENANT_ID ?? null,
    },
});

api.interceptors.request.use((config) => {
    const token = useAuthStore.getState().token;
    if (token) {
        config.headers.Authorization = `Bearer ${token}`;
    }
    return config;
});

api.interceptors.response.use(
    (response) => {
        // Caso a API retorne sucesso Http (Ex: 200), mas o body contenha o erro do token
        if (
            response.data &&
            response.data.success === false &&
            response.data.error === 'token_expired'
        ) {
            useAuthStore.getState().logout();
            window.location.href = '/login'; // Ajuste 'login' conforme as rotas
        }
        return response;
    },
    (error) => {
        // Caso a API retorne erro Http (Ex: 401 Unathorized) com a mensagem de token expirado
        if (error.response?.data) {
            const { success, error: errCode } = error.response.data;
            if ((success === false && errCode === 'token_expired') || error.response.status === 401) {
                useAuthStore.getState().logout();
                window.location.href = '/login';
            }
        }
        return Promise.reject(error);
    },
);
