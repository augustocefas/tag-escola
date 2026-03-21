import React, { useState } from 'react';
import {
    PiHouse,
    PiUsersThree,
    PiIdentificationCard,
    PiBuildings,
    PiGear,
    PiCaretDown,
    PiCaretRight,
    PiList,
    PiChatTeardropDots,
    PiCalendarBlank,
    PiCertificate,
} from 'react-icons/pi';
//import { RecentItem } from '../common/RecentItem';

import { useAuthStore } from '../../stores/authStore';
//import { api } from '../../lib/apiClient';
//import { authService } from '../../services/auth.service';

interface SidebarProps {
    sidebarOpen: boolean;
    activeTab: string;
}

interface NavItemProps {
    icon: React.ReactNode;
    label: string;
    active: boolean;
    onClick: () => void;
    className?: string;
}

const NavItem: React.FC<NavItemProps> = ({
    icon,
    label,
    active,
    onClick,
    className = '',
}) => (
    <button
        onClick={onClick}
        className={`cursor-pointer w-full flex items-center space-x-2 px-3 py-2 rounded-lg transition-colors
      ${
          active
              ? 'bg-blue-50 dark:bg-blue-900/50 text-blue-600 dark:text-blue-400'
              : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700'
      } ${className}`}
    >
        {icon}
        <span className="text-sm font-medium">{label}</span>
    </button>
);

interface NavGroupProps {
    icon: React.ReactNode;
    label: string;
    active: boolean;
    children: React.ReactNode;
}

const NavGroup: React.FC<NavGroupProps> = ({
    icon,
    label,
    active,
    children,
}) => {
    const [isOpen, setIsOpen] = useState(active);

    return (
        <div className="space-y-1">
            <button
                onClick={() => setIsOpen(!isOpen)}
                className={`cursor-pointer w-full flex items-center justify-between px-3 py-2 rounded-lg transition-colors
          ${
              active
                  ? 'bg-blue-50/50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400'
                  : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700'
          }`}
            >
                <div className="flex items-center space-x-2">
                    {icon}
                    <span className="text-sm font-medium">{label}</span>
                </div>
                {isOpen ? (
                    <PiCaretDown size={16} />
                ) : (
                    <PiCaretRight size={16} />
                )}
            </button>
            {isOpen && <div className="pl-6 space-y-1 mt-1">{children}</div>}
        </div>
    );
};

import { useNavigate } from 'react-router-dom';

export const Sidebar: React.FC<SidebarProps> = ({ sidebarOpen, activeTab }) => {
    const navigate = useNavigate();
    // const { data: result, isLoading } = useEscritorios(1);
    // const escritorios = result?.data?.escritorios?.data || [];

    const user = useAuthStore((state) => state.user);
    //const setAuth = useAuthStore((state) => state.setAuth);
    //const token = useAuthStore((state) => state.token);

    const [selectedEscritorio, setSelectedEscritorio] = useState<string>(
        user?.escritorio_id || '',
    );

    React.useEffect(() => {
        if (user?.escritorio_id && user.escritorio_id !== selectedEscritorio) {
            setSelectedEscritorio(user.escritorio_id);
        }
    }, [user?.escritorio_id]);

    /*const handleEscritorioChange = async (
        e: React.ChangeEvent<HTMLSelectElement>,
    ) => {
        const newId = e.target.value;
        setSelectedEscritorio(newId);

        if (newId) {
            try {
                await api.get(`/usuarios/set-escritorio/${newId}`);
                // Refresh the user data from backend
                const meResponse = await authService.me();
                if (meResponse?.data && token) {
                    setAuth(meResponse.data, token);
                }
            } catch (error) {
                console.error('Falha ao definir o escritório', error);
            }
        }
    };*/

    return (
        <aside
            className={`fixed inset-y-0 left-0 transform ${
                sidebarOpen ? 'translate-x-0' : '-translate-x-full'
            } lg:translate-x-0 transition duration-200 ease-in-out z-20 w-64 bg-white dark:bg-gray-800 border-r border-gray-200 dark:border-gray-700 pt-16`}
        >
            <nav className="h-full overflow-y-auto p-4">
                {/* Workspace/Escritorio Selector 
        <div className="mb-6">
          <label className="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2 flex items-center gap-2 px-1">
            <PiBuildings size={16} /> Escritório Atual
          </label>
          <select 
            value={selectedEscritorio}
            onChange={handleEscritorioChange}
            className="w-full bg-gray-50 dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-lg px-3 py-2 text-sm text-gray-800 dark:text-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500 appearance-none cursor-pointer"
            disabled={isLoading}
          >
            <option value="">Selecione um escritório...</option>
            {escritorios.map((esc: any) => (
              <option key={esc.id} value={esc.id}>
                {esc.nome}
              </option>
            ))}
          </select>
        </div>*/}

                {/* Navigation Items */}
                <div className="space-y-1">
                    <NavItem
                        icon={<PiHouse size={20} />}
                        label="Dashboard"
                        active={activeTab === 'dashboard'}
                        onClick={() => navigate('/dashboard')}
                    />
                    {/*<NavItem
            icon={<PiBriefcase size={20} />}
            label="Processos"
            active={activeTab === 'processos'}
            onClick={() => navigate('/processos')}
          />*/}
                    <NavItem
                        icon={<PiUsersThree size={20} />}
                        label="Pessoas"
                        active={activeTab === 'pessoas'}
                        onClick={() => navigate('/pessoas')}
                    />
                    {/*<NavItem
            icon={<PiClockCounterClockwise size={20} />}
            label="Movimentações"
            active={activeTab === 'movimentacoes'}
            onClick={() => navigate('/movimentacoes')}
           />*/}

                    <NavItem
                        icon={<PiCalendarBlank size={20} />}
                        label="Agenda"
                        active={activeTab === 'agenda'}
                        onClick={() => navigate('/agenda')}
                    />
                    {/*<NavItem
            icon={<PiFileText size={20} />}
            label="Documentos"
            active={activeTab === 'documentos'}
            onClick={() => navigate('/documentos')}
          />*/}
                    <NavItem
                        icon={<PiChatTeardropDots size={20} />}
                        label="Solicitação"
                        active={activeTab === 'solicitacoes'}
                        onClick={() => navigate('/solicitacoes')}
                    />

                    <div className="pt-4 pb-2">
                        <div className="border-t border-gray-200 dark:border-gray-700"></div>
                    </div>

                    <NavGroup
                        icon={<PiGear size={20} />}
                        label="Configurações"
                        active={activeTab.startsWith('config-')}
                    >
                        <NavItem
                            icon={<PiUsersThree size={18} />}
                            label="Usuários"
                            active={activeTab === 'config-usuarios'}
                            onClick={() => navigate('/config/usuarios')}
                            className="!py-1.5"
                        />
                        <NavItem
                            icon={<PiBuildings size={18} />}
                            label="Escritório"
                            active={activeTab === 'config-escritorio'}
                            onClick={() => navigate('/config/escritorio')}
                            className="!py-1.5"
                        />
                        <NavItem
                            icon={<PiList size={18} />}
                            label="Domínios"
                            active={activeTab === 'config-dominios'}
                            onClick={() => navigate('/config/dominios')}
                            className="!py-1.5"
                        />
                        <NavItem
                            icon={<PiIdentificationCard size={20} />}
                            label="Modelo de Procuração"
                            active={activeTab === 'procuracao'}
                            onClick={() => navigate('/procuracao')}
                        />
                        <NavItem
                            icon={<PiCertificate size={20} />}
                            label="Certificado digital"
                            active={activeTab === 'certificado-digital'}
                            onClick={() => navigate('/certificado-digital')}
                        />
                    </NavGroup>
                </div>

                {/* Recent Items 
        <div className="mt-8">
          <h3 className="px-3 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
            Recentes
          </h3>
          <div className="mt-2 space-y-1">
            <RecentItem label="Processo 2024/001234" />
            <RecentItem label="Cliente: Maria Oliveira" />
            <RecentItem label="Procuração - João Santos" />
          </div>
        </div>
*/}
                {/* Logout 
        <div className="absolute bottom-4 left-4 right-4">
          <button className="cursor-pointer w-full flex items-center space-x-2 px-3 py-2 text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors">
            <PiSignOut size={20} />
            <span>Sair</span>
          </button>
        </div>
        */}
            </nav>
        </aside>
    );
};
