import { useQuery, useMutation, useQueryClient } from '@tanstack/react-query';
import { tagService } from '../services/tag.service';
import { Tag } from '../types/models';

export const TAGS_KEYS = {
  all: ['tags'] as const,
  list: (params?: any) => [...TAGS_KEYS.all, 'list', params] as const,
  detail: (id: string) => [...TAGS_KEYS.all, 'detail', id] as const,
};

export const useGetTags = (params?: any) => {
  return useQuery({
    queryKey: TAGS_KEYS.list(params),
    queryFn: () => tagService.getAll(params),
  });
};

export const useGetTagById = (id: string) => {
  return useQuery({
    queryKey: TAGS_KEYS.detail(id),
    queryFn: () => tagService.getById(id),
    enabled: !!id,
  });
};

export const useCreateTag = () => {
  const queryClient = useQueryClient();

  return useMutation({
    mutationFn: (payload: Partial<Tag>) => tagService.create(payload),
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: TAGS_KEYS.all });
    },
  });
};

export const useUpdateTag = () => {
  const queryClient = useQueryClient();

  return useMutation({
    mutationFn: ({ id, payload }: { id: string; payload: Partial<Tag> }) =>
      tagService.update(id, payload),
    onSuccess: (_, variables) => {
      queryClient.invalidateQueries({ queryKey: TAGS_KEYS.all });
      queryClient.invalidateQueries({ queryKey: TAGS_KEYS.detail(variables.id) });
    },
  });
};

export const useDeleteTag = () => {
  const queryClient = useQueryClient();

  return useMutation({
    mutationFn: (id: string) => tagService.delete(id),
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: TAGS_KEYS.all });
    },
  });
};
