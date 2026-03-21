import { create } from 'zustand';
import { persist } from 'zustand/middleware';
import type { GridColumnVisibilityModel } from '@mui/x-data-grid-pro';

interface DataGridState {
  columnVisibility: Record<string, GridColumnVisibilityModel>;
  setColumnVisibility: (gridId: string, model: GridColumnVisibilityModel) => void;
}

export const useDataGridStore = create<DataGridState>()(
  persist(
    (set) => ({
      columnVisibility: {},
      setColumnVisibility: (gridId, model) =>
        set((state) => ({
          columnVisibility: {
            ...state.columnVisibility,
            [gridId]: model,
          },
        })),
    }),
    {
      name: 'jus-datagrid-columns-storage', // key in localStorage
    }
  )
);
