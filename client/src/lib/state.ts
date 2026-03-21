import { create } from 'zustand';

export const useSidebarStore = create<any>((set) => ({
    isCollapsed: false,
    toggleSidebar: () =>
        set((state: any) => ({ isCollapsed: !state.isCollapsed })),
}));
