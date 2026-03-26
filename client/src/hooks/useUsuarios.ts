import { useQuery, useMutation, useQueryClient } from '@tanstack/react-query';
import { usuarioService } from '../services/usuario.service';
import type { Usuario } from '../types/models';

export const USUARIOS_KEYS = {
  all: ['usuarios'] as const,
  list: (params?: any) => [...USUARIOS_KEYS.all, 'list', params] as const,
};

export const useGetUsuarios = (params?: any) => {
  return useQuery({
    queryKey: USUARIOS_KEYS.list(params),
    queryFn: () => usuarioService.getAll(params),
  });
};

export const useCreateUsuario = () => {
  const queryClient = useQueryClient();

  return useMutation({
    mutationFn: (payload: Partial<Usuario>) => usuarioService.create(payload),
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: USUARIOS_KEYS.all });
    },
  });
};

export const useUpdateUsuario = () => {
  const queryClient = useQueryClient();

  return useMutation({
    mutationFn: ({ id, payload }: { id: string; payload: Partial<Usuario> }) =>
      usuarioService.update(id, payload),
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: USUARIOS_KEYS.all });
    },
  });
};

export const useDeleteUsuario = () => {
  const queryClient = useQueryClient();

  return useMutation({
    mutationFn: (id: string) => usuarioService.delete(id),
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: USUARIOS_KEYS.all });
    },
  });
};

export const useSetDarkMode = () => {
  return useMutation({
    mutationFn: (darkMode: boolean) => usuarioService.setDarkMode(darkMode),
  });
};

export const useChangePassword = () => {
  return useMutation({
    mutationFn: (payload: any) => usuarioService.changePassword(payload),
  });
};
