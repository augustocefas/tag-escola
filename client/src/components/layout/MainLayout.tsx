import React, { useState, useEffect } from 'react';
import { Outlet, useLocation } from 'react-router-dom';
import { Sidebar } from './Sidebar';
import { Topbar } from './Topbar';
import { useAuthStore } from '../../stores/authStore';

import { api } from '../../lib/apiClient';

import { ToastContainer } from '../common/ToastContainer';
import { GlobalStyles } from '@mui/material';

export const MainLayout: React.FC = () => {
  const location = useLocation();
  const activeTab = location.pathname.split('/').filter(Boolean).join('-') || 'dashboard';
  const [sidebarOpen, setSidebarOpen] = useState(false);
  const user = useAuthStore((state) => state.user);
  const token = useAuthStore((state) => state.token);
  const setAuth = useAuthStore((state) => state.setAuth);



  const [darkMode, setDarkMode] = useState<boolean>(user?.dark_mode || false);

  // Sync state if user changes independently via global store listener instead of pure useEffect setState
  useEffect(() => {
    if (user?.dark_mode !== undefined && user.dark_mode !== darkMode) {
      setDarkMode(user.dark_mode);
      if (user.dark_mode) {
        document.documentElement.classList.add('dark');
      } else {
        document.documentElement.classList.remove('dark');
      }
    }
  }, [user?.dark_mode, darkMode]);

  const handleToggleDarkMode = async () => {
    const newDarkMode = !darkMode;
    setDarkMode(newDarkMode);

    // HTML class logic
    if (newDarkMode) {
      document.documentElement.classList.add('dark');
    } else {
      document.documentElement.classList.remove('dark');
    }

    if (user && token) {
      // Optimistic upate the store natively so next render has it
      setAuth({ ...user, dark_mode: newDarkMode }, token);

      try {
        await api.get(`/usuarios/set-darkmode/${newDarkMode}`);
      } catch (err) {
        console.error('Falha ao definir o dark mode', err);
        // Optionally revert local failure logic here if needed handling
      }
    }
  };

  // Toggle dark mode initially and on dependency change manually bypassing react virtual dom
  useEffect(() => {
    if (darkMode) {
      document.documentElement.classList.add('dark');
    } else {
      document.documentElement.classList.remove('dark');
    }
  }, [darkMode]);

  return (
    <>
    <GlobalStyles styles={{
      '.dark .MuiPaper-root': { backgroundColor: '#1f2937 !important', color: '#f9fafb !important' },
      '.dark .MuiMenuItem-root:hover': { backgroundColor: '#374151 !important' },
      '.dark .MuiList-root': { backgroundColor: '#1f2937' },
    }} />
    <div className="min-h-screen bg-gray-50 dark:bg-gray-900 transition-colors duration-300">
      <Topbar
        sidebarOpen={sidebarOpen}
        setSidebarOpen={setSidebarOpen}
        darkMode={darkMode}
        toggleDarkMode={handleToggleDarkMode}
      />

      <Sidebar
        sidebarOpen={sidebarOpen}
        activeTab={activeTab}
      />

      {/* Main Content */}
      <main className="lg:ml-64 pt-16 min-h-screen">
        <div className="p-6">
          <Outlet />
        </div>
      </main>



      {/* Global Toasts */}
      <ToastContainer />
    </div>
    </>
  );
};
