import { useQuery } from '@tanstack/react-query';
import { configService } from '@/services/config.service';

export const useConfig = () => {
  return useQuery({
    queryKey: ['global-config'],
    queryFn: () => configService.getConfig(),
    staleTime: 1000 * 60 * 5, // Cache for 5 minutes since configs rarely change
  });
};
