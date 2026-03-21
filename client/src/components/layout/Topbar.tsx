import React, { useState, useEffect } from 'react';
import { useNavigate } from 'react-router-dom';
import {
  PiList,
  PiX,
  PiScales,
  PiMagnifyingGlass,
  PiSun,
  PiMoon,
  PiBell,
  PiUserCircle,
  PiCaretDown,
} from 'react-icons/pi';
import { useAuthStore } from '../../stores/authStore';
import { authService } from '../../services/auth.service';
import { api } from '../../lib/apiClient';

interface SearchResult {
  id: string;
  nome: string;
  pfpj: string;
  processos_count: number;
  codigo?: string;
}

interface TopbarProps {
  sidebarOpen: boolean;
  setSidebarOpen: (open: boolean) => void;
  darkMode: boolean;
  toggleDarkMode: () => void;
}

export const Topbar: React.FC<TopbarProps> = ({
  sidebarOpen,
  setSidebarOpen,
  darkMode,
  toggleDarkMode,
}) => {
  const [dropdownOpen, setDropdownOpen] = useState(false);
  const user = useAuthStore((state) => state.user);
  const logoutStore = useAuthStore((state) => state.logout);
  const navigate = useNavigate();

  const [searchQuery, setSearchQuery] = useState('');
  const [searchResults, setSearchResults] = useState<SearchResult[]>([]);
  const [isSearching, setIsSearching] = useState(false);
  const [showSearchResults, setShowSearchResults] = useState(false);

  useEffect(() => {
    const delayDebounceFn = setTimeout(async () => {
      if (searchQuery.trim().length >= 2) {
        setIsSearching(true);
        try {
          const params = new URLSearchParams({ 
            nome: searchQuery,
            // Poderíamos adicionar cpf_cnpj aqui em um cenário de busca multi-campo, mas o filtro de `nome` no Controller atual faz select por nome apenas.
          });
          const { data } = await api.get(`/pessoa?${params.toString()}`);
          setSearchResults(data.data?.pessoas?.data || []);
          setShowSearchResults(true);
        } catch (error) {
          console.error("Erro na busca:", error);
          setSearchResults([]);
        } finally {
          setIsSearching(false);
        }
      } else {
        setSearchResults([]);
        setShowSearchResults(false);
      }
    }, 500);

    return () => clearTimeout(delayDebounceFn);
  }, [searchQuery]);

  const handleLogout = async () => {
    try {
      await authService.logout();
    } catch (err) {
      console.error('Logout failed on backend:', err);
    } finally {
      logoutStore();
      setDropdownOpen(false);
      navigate('/login');
    }
  };

  return (
    <header className="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 fixed top-0 w-full z-30">
      <div className="px-4 sm:px-6 lg:px-8">
        <div className="flex items-center justify-between h-16">
          {/* Left section */}
          <div className="flex items-center">
            <button
              onClick={() => setSidebarOpen(!sidebarOpen)}
              className="lg:hidden p-2 rounded-md text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700"
            >
              {sidebarOpen ? <PiX size={24} /> : <PiList size={24} />}
            </button>

            {/* Logo */}
            <div className="flex items-center ml-2 lg:ml-0">
              <PiScales className="h-8 w-8 text-blue-600 dark:text-blue-400" />
              <span className="ml-2 text-xl font-semibold text-gray-800 dark:text-white">
                SysJudis
              </span>
            </div>
          </div>

        {/* Search Bar */}
        <div className="hidden md:flex flex-1 max-w-xl mx-8 relative">
          <div className="relative w-full">
            <PiMagnifyingGlass
              className="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 dark:text-gray-500"
              size={20}
            />
            <input
              type="text"
              value={searchQuery}
              onChange={(e) => setSearchQuery(e.target.value)}
              onFocus={() => {
                if (searchResults.length > 0 || isSearching) {
                  setShowSearchResults(true);
                }
              }}
              onBlur={() => {
                // Diminuto atraso para permitir clicar no resultado antes de sumir
                setTimeout(() => setShowSearchResults(false), 200);
              }}
              placeholder="Buscar pessoas..."
              className="w-full pl-10 pr-4 py-2 border border-gray-200 dark:border-gray-600 rounded-lg 
                     bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white
                     focus:ring-2 focus:ring-blue-500 focus:border-transparent
                     placeholder-gray-400 dark:placeholder-gray-500"
            />
          </div>

          {/* Search Dropdown */}
          {showSearchResults && (
            <div className="absolute top-12 left-0 w-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-lg z-50 overflow-hidden">
              {isSearching ? (
                <div className="p-4 text-sm text-gray-500 dark:text-gray-400 text-center">Buscando...</div>
              ) : searchResults.length > 0 ? (
                <ul className="max-h-64 overflow-y-auto divide-y divide-gray-100 dark:divide-gray-700">
                  {searchResults.map((result) => (
                    <li key={result.id}>
                      <button
                        onMouseDown={() => {
                          navigate(`/pessoas/${result.id}`);
                          setShowSearchResults(false);
                          setSearchQuery('');
                        }}
                        className="cursor-pointer w-full text-left px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-700 transition flex justify-between items-center"
                      >
                        <div>
                          <p className="text-sm font-medium text-gray-900 dark:text-white">{`${result.nome} `}</p>
                          <p className="text-xs text-gray-500 dark:text-gray-400 uppercase">{result?.codigo && `(${result.codigo})`} {result.pfpj =="pj" ? 'Pessoa Jurídica' : 'Pessoa Física'}</p>
                        </div>
                        {result.processos_count !== undefined && (
                          <span className="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/40 dark:text-blue-300">
                            {result.processos_count} {result.processos_count === 1 ? 'Processo' : 'Processos'}
                          </span>
                        )}
                      </button>
                    </li>
                  ))}
                </ul>
              ) : (
                <div className="p-4 text-sm text-gray-500 dark:text-gray-400 text-center">Nenhum resultado encontrado.</div>
              )}
            </div>
          )}
        </div>

          {/* Right section */}
          <div className="flex items-center space-x-3 relative">
            {/* Dark mode toggle */}
            <button
              onClick={toggleDarkMode}
              className="p-2 text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg"
            >
              {darkMode ? <PiSun size={20} /> : <PiMoon size={20} />}
            </button>

            {/* Notifications */}
            <button className="cursor-pointer relative p-2 text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg">
              <PiBell size={20} />
              <span className="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full"></span>
            </button>

            {/* User menu */}
            <div className="relative">
              <button 
                onClick={() => setDropdownOpen(!dropdownOpen)}
                className="cursor-pointer flex items-center space-x-2 p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg"
              >
                <PiUserCircle size={24} className="text-gray-600 dark:text-gray-300" />
                <span className="hidden sm:block text-sm text-gray-700 dark:text-gray-200">
                  {user?.name || 'Usuário'}
                </span>
                <PiCaretDown size={16} className="text-gray-500 dark:text-gray-400" />
              </button>

              {/* Dropdown Menu */}
              {dropdownOpen && (
                <div className="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-md shadow-lg py-1 border border-gray-200 dark:border-gray-700 z-50">
                  <button
                    onClick={handleLogout}
                    className="cursor-pointer block w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700"
                  >
                    Sair
                  </button>
                </div>
              )}
            </div>
          </div>
        </div>
      </div>
    </header>
  );
};
