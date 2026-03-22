import { useQuery, useMutation, useQueryClient } from '@tanstack/react-query';
import { salaService } from '../services/sala.service';
import { Sala } from '../types/models';

export const SALAS_KEYS = {
  all: ['salas'] as const,
  list: (params?: any) => [...SALAS_KEYS.all, 'list', params] as const,
  detail: (id: string) => [...SALAS_KEYS.all, 'detail', id] as const,
};

export const useGetSalas = (params?: any) => {
  return useQuery({
    queryKey: SALAS_KEYS.list(params),
    queryFn: () => salaService.getAll(params),
  });
};

export const useGetSalaById = (id: string) => {
  return useQuery({
    queryKey: SALAS_KEYS.detail(id),
    queryFn: () => salaService.getById(id),
    enabled: !!id,
  });
};

export const useCreateSala = () => {
  const queryClient = useQueryClient();

  return useMutation({
    mutationFn: (payload: Partial<Sala>) => salaService.create(payload),
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: SALAS_KEYS.all });
    },
  });
};

export const useUpdateSala = () => {
  const queryClient = useQueryClient();

  return useMutation({
    mutationFn: ({ id, payload }: { id: string; payload: Partial<Sala> }) =>
      salaService.update(id, payload),
    onSuccess: (_, variables) => {
      queryClient.invalidateQueries({ queryKey: SALAS_KEYS.all });
      queryClient.invalidateQueries({ queryKey: SALAS_KEYS.detail(variables.id) });
    },
  });
};

export const useDeleteSala = () => {
  const queryClient = useQueryClient();

  return useMutation({
    mutationFn: (id: string) => salaService.delete(id),
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: SALAS_KEYS.all });
    },
  });
};
