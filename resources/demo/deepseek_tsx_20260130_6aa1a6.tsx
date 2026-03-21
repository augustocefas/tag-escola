import React, { useState } from 'react';
import {
  DataGridPro,
  GridColDef,
  GridRowParams,
  GridToolbar,
  GridActionsCellItem,
  GridRowSelectionModel,
  GridFilterModel,
  GridSortModel,
} from '@mui/x-data-grid-pro';
import {
  Box,
  Chip,
  IconButton,
  Typography,
  Paper,
  LinearProgress,
  Alert,
  Button,
  Menu,
  MenuItem,
  Dialog,
  DialogTitle,
  DialogContent,
  DialogActions,
} from '@mui/material';
import {
  FaTractor,
  FaEye,
  FaEdit,
  FaTrash,
  FaChartLine,
  FaTools,
  FaMapMarkerAlt,
  FaBell,
  FaFilter,
  FaDownload,
  FaPrint,
  FaShare,
  FaSync,
  FaPlus,
} from 'react-icons/fa';
import { FiBarChart2, FiTool } from 'react-icons/fi';

// Tipos das máquinas
interface Machine {
  id: number;
  name: string;
  model: string;
  type: 'trator' | 'colheitadeira' | 'pulverizador' | 'plantadeira' | 'outro';
  status: 'ativo' | 'manutencao' | 'parado' | 'alerta';
  hours: number;
  fuelLevel: number;
  lastMaintenance: string;
  location: string;
  operator: string;
  efficiency: number;
  alerts: number;
}

export default function LogSenseDataGrid() {
  const [selectionModel, setSelectionModel] = useState<GridRowSelectionModel>([]);
  const [filterModel, setFilterModel] = useState<GridFilterModel>({ items: [] });
  const [sortModel, setSortModel] = useState<GridSortModel>([]);
  const [pageSize, setPageSize] = useState(10);
  const [deleteDialogOpen, setDeleteDialogOpen] = useState(false);
  const [selectedMachine, setSelectedMachine] = useState<Machine | null>(null);
  const [exportMenuAnchor, setExportMenuAnchor] = useState<null | HTMLElement>(null);
  const [loading, setLoading] = useState(false);

  // Dados de exemplo
  const rows: Machine[] = [
    {
      id: 1,
      name: 'Trator John Deere',
      model: '7250',
      type: 'trator',
      status: 'ativo',
      hours: 1248,
      fuelLevel: 85,
      lastMaintenance: '2023-06-15',
      location: 'Fazenda São Paulo',
      operator: 'João Silva',
      efficiency: 94,
      alerts: 1,
    },
    {
      id: 2,
      name: 'Colheitadeira',
      model: 'CX8080',
      type: 'colheitadeira',
      status: 'manutencao',
      hours: 892,
      fuelLevel: 45,
      lastMaintenance: '2023-05-10',
      location: 'Fazenda Central',
      operator: 'Maria Santos',
      efficiency: 87,
      alerts: 3,
    },
    {
      id: 3,
      name: 'Pulverizador',
      model: 'Jacto 3000',
      type: 'pulverizador',
      status: 'ativo',
      hours: 567,
      fuelLevel: 92,
      lastMaintenance: '2023-06-22',
      location: 'Fazenda Norte',
      operator: 'Pedro Oliveira',
      efficiency: 96,
      alerts: 0,
    },
    {
      id: 4,
      name: 'Plantadeira',
      model: 'M-600',
      type: 'plantadeira',
      status: 'alerta',
      hours: 345,
      fuelLevel: 32,
      lastMaintenance: '2023-04-30',
      location: 'Fazenda Sul',
      operator: 'Ana Costa',
      efficiency: 76,
      alerts: 2,
    },
    {
      id: 5,
      name: 'Trator Valtra',
      model: 'BH180',
      type: 'trator',
      status: 'ativo',
      hours: 987,
      fuelLevel: 78,
      lastMaintenance: '2023-06-10',
      location: 'Fazenda Oeste',
      operator: 'Carlos Lima',
      efficiency: 91,
      alerts: 0,
    },
    {
      id: 6,
      name: 'Colheitadeira New Holland',
      model: 'CR10.90',
      type: 'colheitadeira',
      status: 'parado',
      hours: 1342,
      fuelLevel: 12,
      lastMaintenance: '2023-03-15',
      location: 'Fazenda Leste',
      operator: 'Roberto Alves',
      efficiency: 89,
      alerts: 1,
    },
    {
      id: 7,
      name: 'Pulverizador Uniport',
      model: '5030',
      type: 'pulverizador',
      status: 'ativo',
      hours: 423,
      fuelLevel: 67,
      lastMaintenance: '2023-06-18',
      location: 'Fazenda Central',
      operator: 'Fernanda Lima',
      efficiency: 93,
      alerts: 0,
    },
    {
      id: 8,
      name: 'Trator Massey Ferguson',
      model: '6713',
      type: 'trator',
      status: 'manutencao',
      hours: 1567,
      fuelLevel: 23,
      lastMaintenance: '2023-01-20',
      location: 'Fazenda Norte',
      operator: 'José Pereira',
      efficiency: 82,
      alerts: 2,
    },
  ];

  // Função para renderizar ícone do tipo de máquina
  const renderTypeIcon = (type: string) => {
    switch (type) {
      case 'trator':
        return <FaTractor className="text-emerald-600 text-lg" />;
      case 'colheitadeira':
        return <FiBarChart2 className="text-amber-600 text-lg" />;
      case 'pulverizador':
        return <FaChartLine className="text-blue-600 text-lg" />;
      case 'plantadeira':
        return <FaPlus className="text-purple-600 text-lg" />;
      default:
        return <FaTools className="text-gray-600 text-lg" />;
    }
  };

  // Função para renderizar status
  const renderStatusChip = (status: string) => {
    const config = {
      ativo: { color: 'bg-emerald-100 text-emerald-800 border-emerald-200', icon: '🟢' },
      manutencao: { color: 'bg-amber-100 text-amber-800 border-amber-200', icon: '🛠️' },
      parado: { color: 'bg-gray-100 text-gray-800 border-gray-200', icon: '⏸️' },
      alerta: { color: 'bg-red-100 text-red-800 border-red-200', icon: '⚠️' },
    }[status] || { color: 'bg-gray-100 text-gray-800', icon: '❓' };

    return (
      <Chip
        label={
          <div className="flex items-center space-x-1">
            <span>{config.icon}</span>
            <span className="capitalize">{status}</span>
          </div>
        }
        className={`${config.color} border font-medium capitalize rounded-full px-3 py-1 text-sm`}
        size="small"
      />
    );
  };

  // Colunas do DataGrid
  const columns: GridColDef[] = [
    {
      field: 'name',
      headerName: 'MÁQUINA',
      flex: 2,
      renderCell: (params) => (
        <div className="flex items-center space-x-3 py-2">
          <div className="w-10 h-10 bg-gradient-to-br from-emerald-50 to-emerald-100 rounded-lg flex items-center justify-center">
            {renderTypeIcon(params.row.type)}
          </div>
          <div>
            <Typography className="font-semibold text-gray-800">
              {params.value}
            </Typography>
            <Typography className="text-xs text-gray-500">
              #{params.row.id.toString().padStart(3, '0')}
            </Typography>
          </div>
        </div>
      ),
    },
    {
      field: 'model',
      headerName: 'MODELO',
      flex: 1,
      renderCell: (params) => (
        <Chip
          label={params.value}
          className="bg-gray-100 text-gray-700 font-medium rounded-lg"
          size="small"
        />
      ),
    },
    {
      field: 'type',
      headerName: 'TIPO',
      flex: 1,
      renderCell: (params) => (
        <div className="flex items-center space-x-2">
          {renderTypeIcon(params.value)}
          <Typography className="capitalize text-gray-700">
            {params.value}
          </Typography>
        </div>
      ),
    },
    {
      field: 'status',
      headerName: 'STATUS',
      flex: 1.2,
      renderCell: (params) => renderStatusChip(params.value),
    },
    {
      field: 'hours',
      headerName: 'HORÍMETRO',
      flex: 1,
      renderCell: (params) => (
        <div>
          <Typography className="font-semibold text-gray-800">
            {params.value.toLocaleString()}h
          </Typography>
          <div className="w-full bg-gray-200 rounded-full h-1.5 mt-1">
            <div
              className="bg-emerald-500 h-1.5 rounded-full"
              style={{ width: `${Math.min((params.value / 2000) * 100, 100)}%` }}
            />
          </div>
        </div>
      ),
    },
    {
      field: 'fuelLevel',
      headerName: 'COMBUSTÍVEL',
      flex: 1,
      renderCell: (params) => (
        <div className="relative">
          <div className="w-16 bg-gray-200 rounded-full h-2">
            <div
              className={`h-2 rounded-full ${
                params.value > 70
                  ? 'bg-emerald-500'
                  : params.value > 30
                  ? 'bg-amber-500'
                  : 'bg-red-500'
              }`}
              style={{ width: `${params.value}%` }}
            />
          </div>
          <Typography className="text-xs text-gray-600 mt-1">
            {params.value}%
          </Typography>
        </div>
      ),
    },
    {
      field: 'efficiency',
      headerName: 'EFICIÊNCIA',
      flex: 1,
      renderCell: (params) => (
        <div className="relative">
          <div className="w-16 bg-gray-200 rounded-full h-2">
            <div
              className={`h-2 rounded-full ${
                params.value > 90
                  ? 'bg-emerald-500'
                  : params.value > 80
                  ? 'bg-amber-500'
                  : 'bg-red-500'
              }`}
              style={{ width: `${params.value}%` }}
            />
          </div>
          <Typography
            className={`text-xs font-medium ${
              params.value > 90
                ? 'text-emerald-600'
                : params.value > 80
                ? 'text-amber-600'
                : 'text-red-600'
            }`}
          >
            {params.value}%
          </Typography>
        </div>
      ),
    },
    {
      field: 'alerts',
      headerName: 'ALERTAS',
      flex: 0.8,
      renderCell: (params) => (
        <div className="flex justify-center">
          {params.value > 0 ? (
            <div className="relative">
              <FaBell className="text-amber-600" />
              <span className="absolute -top-2 -right-2 w-5 h-5 bg-red-500 text-white text-xs rounded-full flex items-center justify-center animate-pulse">
                {params.value}
              </span>
            </div>
          ) : (
            <span className="text-gray-400">—</span>
          )}
        </div>
      ),
    },
    {
      field: 'lastMaintenance',
      headerName: 'ÚLT. MANUTENÇÃO',
      flex: 1.2,
      renderCell: (params) => (
        <div>
          <Typography className="text-gray-800">
            {new Date(params.value).toLocaleDateString('pt-BR')}
          </Typography>
          <Typography className="text-xs text-gray-500">
            Há {Math.floor((new Date().getTime() - new Date(params.value).getTime()) / (1000 * 60 * 60 * 24))} dias
          </Typography>
        </div>
      ),
    },
    {
      field: 'actions',
      headerName: 'AÇÕES',
      type: 'actions',
      flex: 1,
      getActions: (params: GridRowParams) => [
        <GridActionsCellItem
          icon={<FaEye className="text-emerald-600" />}
          label="Visualizar"
          onClick={() => handleView(params.row)}
          showInMenu
        />,
        <GridActionsCellItem
          icon={<FaMapMarkerAlt className="text-blue-600" />}
          label="Rastrear"
          onClick={() => handleTrack(params.row)}
          showInMenu
        />,
        <GridActionsCellItem
          icon={<FaEdit className="text-amber-600" />}
          label="Editar"
          onClick={() => handleEdit(params.row)}
          showInMenu
        />,
        <GridActionsCellItem
          icon={<FiTool className="text-purple-600" />}
          label="Manutenção"
          onClick={() => handleMaintenance(params.row)}
          showInMenu
        />,
        <GridActionsCellItem
          icon={<FaTrash className="text-red-600" />}
          label="Excluir"
          onClick={() => handleDeleteClick(params.row)}
          showInMenu
        />,
      ],
    },
  ];

  // Handlers
  const handleView = (machine: Machine) => {
    console.log('Visualizar:', machine);
    // Navegar para detalhes da máquina
  };

  const handleTrack = (machine: Machine) => {
    console.log('Rastrear:', machine);
    // Abrir mapa com localização
  };

  const handleEdit = (machine: Machine) => {
    console.log('Editar:', machine);
    // Abrir modal de edição
  };

  const handleMaintenance = (machine: Machine) => {
    console.log('Manutenção:', machine);
    // Abrir histórico de manutenção
  };

  const handleDeleteClick = (machine: Machine) => {
    setSelectedMachine(machine);
    setDeleteDialogOpen(true);
  };

  const handleDeleteConfirm = () => {
    console.log('Excluir:', selectedMachine);
    setDeleteDialogOpen(false);
    setSelectedMachine(null);
  };

  const handleExportClick = (event: React.MouseEvent<HTMLElement>) => {
    setExportMenuAnchor(event.currentTarget);
  };

  const handleExportClose = () => {
    setExportMenuAnchor(null);
  };

  const handleExport = (format: string) => {
    console.log('Exportar como:', format);
    handleExportClose();
  };

  const handleRefresh = () => {
    setLoading(true);
    setTimeout(() => setLoading(false), 1000);
  };

  // Custom Toolbar
  const CustomToolbar = () => (
    <div className="flex flex-col space-y-4 p-4 bg-gradient-to-r from-emerald-50 to-white border-b border-emerald-100">
      {/* Header com estatísticas */}
      <div className="flex flex-col md:flex-row justify-between items-start md:items-center space-y-3 md:space-y-0">
        <div>
          <Typography variant="h6" className="font-bold text-gray-800">
            Frota de Máquinas
          </Typography>
          <Typography variant="body2" className="text-gray-600">
            Gerencie e monitore todas as suas máquinas agrícolas
          </Typography>
        </div>
        
        <div className="flex items-center space-x-3">
          <Button
            startIcon={<FaPlus />}
            className="bg-gradient-to-r from-emerald-500 to-emerald-600 text-white hover:from-emerald-600 hover:to-emerald-700 shadow-lg hover:shadow-xl transition-all duration-300 rounded-xl"
          >
            Nova Máquina
          </Button>
          
          <IconButton
            onClick={handleRefresh}
            className="bg-white border border-emerald-200 hover:bg-emerald-50 transition-colors rounded-xl"
          >
            <FaSync className={`text-emerald-600 ${loading ? 'animate-spin' : ''}`} />
          </IconButton>
          
          <IconButton
            onClick={handleExportClick}
            className="bg-white border border-emerald-200 hover:bg-emerald-50 transition-colors rounded-xl"
          >
            <FaDownload className="text-emerald-600" />
          </IconButton>
          
          <Menu
            anchorEl={exportMenuAnchor}
            open={Boolean(exportMenuAnchor)}
            onClose={handleExportClose}
          >
            <MenuItem onClick={() => handleExport('csv')}>
              <FaDownload className="mr-2" /> Exportar como CSV
            </MenuItem>
            <MenuItem onClick={() => handleExport('excel')}>
              <FaDownload className="mr-2" /> Exportar como Excel
            </MenuItem>
            <MenuItem onClick={() => handleExport('pdf')}>
              <FaPrint className="mr-2" /> Gerar PDF
            </MenuItem>
          </Menu>
        </div>
      </div>

      {/* Estatísticas rápidas */}
      <div className="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div className="bg-white p-3 rounded-xl border border-emerald-100">
          <div className="flex items-center justify-between">
            <Typography variant="body2" className="text-gray-600">
              Máquinas Ativas
            </Typography>
            <div className="w-8 h-8 bg-emerald-100 rounded-lg flex items-center justify-center">
              <FaTractor className="text-emerald-600" />
            </div>
          </div>
          <Typography variant="h5" className="font-bold text-gray-800 mt-2">
            {rows.filter(m => m.status === 'ativo').length}
          </Typography>
        </div>

        <div className="bg-white p-3 rounded-xl border border-emerald-100">
          <div className="flex items-center justify-between">
            <Typography variant="body2" className="text-gray-600">
              Em Manutenção
            </Typography>
            <div className="w-8 h-8 bg-amber-100 rounded-lg flex items-center justify-center">
              <FaTools className="text-amber-600" />
            </div>
          </div>
          <Typography variant="h5" className="font-bold text-gray-800 mt-2">
            {rows.filter(m => m.status === 'manutencao').length}