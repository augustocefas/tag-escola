import { api } from '@/lib/apiClient';
import type { ConfigResponse } from '@/types/models';

const BASE_PATH = '/config';

export const configService = {
  getConfig: async (): Promise<ConfigResponse> => {
    // Note: This endpoint '/getconfig' returns the custom mapped config structure directly,
    // not wrapped in an ApiResponse, or maybe it is wrapped. Let's see the controller:
    // It returns `$resultado ?? []` directly or wrapped?
    // Looking at ConfigController.php: `public function getConfig(){ ... return $resultado ?? []; }`
    // It is NOT returning `$this->success(...)` just a raw array.
    // So axios data will be the object exactly.
    const { data } = await api.get(`${BASE_PATH}/getconfig`);
    return data;
  },
};
