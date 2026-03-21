import { create } from 'zustand';

interface DrawerState {
  isPessoaDrawerOpen: boolean;
  pessoaToEdit: any | null;
  openPessoaDrawer: (pessoaToEdit?: any) => void;
  closePessoaDrawer: () => void;

  isProcessoDrawerOpen: boolean;
  processoToEdit: any | null;
  openProcessoDrawer: (processoToEdit?: any) => void;
  closeProcessoDrawer: () => void;

  isChamadoDrawerOpen: boolean;
  editingChamado: any | null;
  openChamadoDrawer: (chamado?: any) => void;
  closeChamadoDrawer: () => void;
}

export const useDrawerStore = create<DrawerState>((set) => ({
  isPessoaDrawerOpen: false,
  pessoaToEdit: null,
  openPessoaDrawer: (pessoa = null) => set({ isPessoaDrawerOpen: true, pessoaToEdit: pessoa }),
  closePessoaDrawer: () => set({ isPessoaDrawerOpen: false, pessoaToEdit: null }),

  isProcessoDrawerOpen: false,
  processoToEdit: null,
  openProcessoDrawer: (processo = null) => set({ isProcessoDrawerOpen: true, processoToEdit: processo }),
  closeProcessoDrawer: () => set({ isProcessoDrawerOpen: false, processoToEdit: null }),

  isChamadoDrawerOpen: false,
  editingChamado: null,
  openChamadoDrawer: (chamado = null) => set({ isChamadoDrawerOpen: true, editingChamado: chamado }),
  closeChamadoDrawer: () => set({ isChamadoDrawerOpen: false, editingChamado: null }),
}));
