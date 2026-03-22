import { useQuery, useMutation, useQueryClient } from '@tanstack/react-query';
import { alunoService } from '../services/aluno.service';
import { Aluno } from '../types/models';

export const ALUNOS_KEYS = {
  all: ['alunos'] as const,
  list: (params?: any) => [...ALUNOS_KEYS.all, 'list', params] as const,
  detail: (id: string) => [...ALUNOS_KEYS.all, 'detail', id] as const,
};

export const useGetAlunos = (params?: any) => {
  return useQuery({
    queryKey: ALUNOS_KEYS.list(params),
    queryFn: () => alunoService.getAll(params),
  });
};

export const useGetAlunoById = (id: string) => {
  return useQuery({
    queryKey: ALUNOS_KEYS.detail(id),
    queryFn: () => alunoService.getById(id),
    enabled: !!id,
  });
};

export const useCreateAluno = () => {
  const queryClient = useQueryClient();

  return useMutation({
    mutationFn: (payload: Partial<Aluno>) => alunoService.create(payload),
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ALUNOS_KEYS.all });
    },
  });
};

export const useUpdateAluno = () => {
  const queryClient = useQueryClient();

  return useMutation({
    mutationFn: ({ id, payload }: { id: string; payload: Partial<Aluno> }) =>
      alunoService.update(id, payload),
    onSuccess: (_, variables) => {
      queryClient.invalidateQueries({ queryKey: ALUNOS_KEYS.all });
      queryClient.invalidateQueries({ queryKey: ALUNOS_KEYS.detail(variables.id) });
    },
  });
};

export const useDeleteAluno = () => {
  const queryClient = useQueryClient();

  return useMutation({
    mutationFn: (id: string) => alunoService.delete(id),
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ALUNOS_KEYS.all });
    },
  });
};
