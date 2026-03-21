import React, { useState } from 'react';
import { DataGridPro, GridToolbarContainer } from '@mui/x-data-grid-pro';
import type { DataGridProProps, GridColumnVisibilityModel } from '@mui/x-data-grid-pro';
import { CustomDrawer } from './CustomDrawer';
import { useDataGridStore } from '../../stores/useDataGridStore';
import { PiColumns, PiFunnel } from 'react-icons/pi';
import { Tooltip, Badge } from '@mui/material';


export interface CustomDataGridProps extends DataGridProProps {
  gridId?: string;
  filterContent?: React.ReactNode;
  activeFiltersCount?: number;
}

export const CustomDataGrid: React.FC<CustomDataGridProps> = (props) => {
  const { gridId, ...dataGridProps } = props;
  const [drawerOpen, setDrawerOpen] = useState(false);
  const [filterDrawerOpen, setFilterDrawerOpen] = useState(false);
  
  const storedVisibility = useDataGridStore((state) => state.columnVisibility);
  const setStoredVisibility = useDataGridStore((state) => state.setColumnVisibility);

  // If gridId is not provided, fallback to uncontrolled or props-controlled model
  const visibilityModel = gridId ? (storedVisibility[gridId] || {}) : props.columnVisibilityModel || {};
  
  const handleVisibilityChange = (newModel: GridColumnVisibilityModel, details?: any) => {
    if (gridId) {
      setStoredVisibility(gridId, newModel);
    }
    if (props.onColumnVisibilityModelChange) {
      props.onColumnVisibilityModelChange(newModel, details as any);
    }
  };

  const CustomToolbar = () => (
    <GridToolbarContainer className="flex justify-end p-2 border-b border-gray-200 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-800/30">
      <div className="flex pt-1 pb-2 pr-2 gap-1">
        <Tooltip title="Gerenciar Colunas">
          <button
            type="button"
            onClick={() => setDrawerOpen(true)}
            className="cursor-pointer flex items-center gap-2 px-3 py-1.5 text-sm font-medium text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-800 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors"
          >
            <PiColumns size={18} />
          </button>
        </Tooltip>
        {props.filterContent && (
          <Tooltip title="Aplicar filtros">
            <button
              type="button"
              onClick={() => setFilterDrawerOpen(!filterDrawerOpen)}
              className="cursor-pointer flex items-center gap-2 px-3 py-1.5 text-sm font-medium text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-800 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors"
            >
              <Badge 
                badgeContent={props.activeFiltersCount || 0} 
                color="primary"
                sx={{
                  '& .MuiBadge-badge': {
                    fontSize: '0.65rem',
                    height: '16px',
                    minWidth: '16px',
                  }
                }}
              >
                <PiFunnel size={18} />
              </Badge>
            </button>
          </Tooltip>
        )}
      </div>
    </GridToolbarContainer>
  );

  return (
    <>
      <DataGridPro
        disableRowSelectionOnClick
        pagination
        paginationMode="server"
        sortingMode="server"
        filterMode="server"
        disableColumnMenu={false}
        rowHeight={50}
        {...dataGridProps}
        // Override visibility models with our state if gridId is provided
        columnVisibilityModel={gridId ? visibilityModel : props.columnVisibilityModel}
        onColumnVisibilityModelChange={gridId ? handleVisibilityChange : props.onColumnVisibilityModelChange}
        slots={{
          toolbar: CustomToolbar,
          ...props.slots, // Preserve overridden slots if any
        }}
        sx={{
          border: 'none',
          color: 'inherit',
          '& .MuiDataGrid-cell:focus': {
            outline: 'none',
          },
          '& .MuiDataGrid-columnHeader:focus': {
            outline: 'none',
          },
          '& .MuiDataGrid-columnHeaderTitle': {
            fontWeight: 600,
          },
          '& .MuiDataGrid-columnHeaders': {
            backgroundColor: 'transparent',
          },
          // Dark mode styles - corrigido para melhor contraste
          '.dark &': {
            color: '#e5e7eb',
            '& .MuiDataGrid-cell': {
              borderColor: '#374151',
            },
            '& .MuiDataGrid-filler': {
              backgroundColor: '#1f2937',
               borderColor: '#374151',
            },
            '& .MuiDataGrid-cell--pinnedRight': {
              backgroundColor: '#1f2937',
            },
            '& .MuiDataGrid-columnHeaders': {
              borderColor: '#374151',
              backgroundColor: '#1f2937', // Fundo mais escuro para o header
              color: '#ffffff', // Texto branco para melhor contraste
            },
            '& .MuiDataGrid-columnHeader': {
              backgroundColor: '#1f2937', // Fundo escuro consistente
              color: '#ffffff', // Texto branco
            },
            '& .MuiDataGrid-columnHeaderTitle': {
              color: '#ffffff', // Título das colunas em branco
              fontWeight: 600,
            },
            '& .MuiDataGrid-columnHeader:focus': {
              outline: 'none',
            },
            '& .MuiDataGrid-columnSeparator': {
              color: '#4b5563', // Separador mais visível mas suave
            },
            '& .MuiDataGrid-menuIcon': {
              color: '#ffffff', // Ícone do menu em branco
            },
            '& .MuiDataGrid-sortIcon': {
              color: '#ffffff', // Ícone de ordenação em branco
            },
            '& .MuiDataGrid-filterIcon': {
              color: '#ffffff', // Ícone de filtro em branco
            },
            '& .MuiDataGrid-footerContainer': {
              borderColor: '#374151',
              backgroundColor: '#1f2937', // Fundo escuro para o footer também
              color: '#ffffff',
            },
            '& .MuiDataGrid-withBorderColor': {
              borderColor: '#374151',
            },
            '& .MuiIconButton-root': {
              color: '#ffffff', // Ícones em branco
            },
            '& .MuiTablePagination-root': {
              color: '#ffffff', // Texto da paginação em branco
            },
            '& .MuiTablePagination-selectIcon': {
              color: '#ffffff', // Ícone de seleção em branco
            },
            '& .MuiTablePagination-select': {
              color: '#ffffff', // Texto do select em branco
            },
            '& .MuiInputBase-root': {
              color: '#ffffff', // Input da paginação em branco
            },
            '& .MuiSvgIcon-root': {
              color: '#ffffff', // Todos os ícones SVG em branco
            },
          },
          ...props.sx,
        }}
      />

      <CustomDrawer
        open={drawerOpen}
        onClose={() => setDrawerOpen(false)}
        title="Gerenciar Colunas"
        width={400} // Smaller width for column drawer
      >
        <div className="space-y-2 px-3 py-3">
          {props.columns
  ?.filter((col) => col.field !== 'actions').map((col) => {
            // Check if column is currently visible. Defaults to true if not defined.
            const isVisible = visibilityModel?.[col.field] !== false;
            
            return (
              <div key={col.field} className="flex items-center justify-between p-2 bg-gray-50 dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700">
                <span className="text-sm font-medium text-gray-700 dark:text-gray-300">
                  {col.headerName || col.field}
                </span>
                <label className="relative inline-flex items-center cursor-pointer">
                  <input
                    type="checkbox"
                    className="sr-only peer"
                    checked={isVisible}
                    onChange={(e) => {
                      handleVisibilityChange({
                        ...visibilityModel,
                        [col.field]: e.target.checked
                      });
                    }}
                  />
                  <div className="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>
                </label>
              </div>
            );
          })}
        </div>
      </CustomDrawer>
      
      {props.filterContent && (
        <CustomDrawer
          open={filterDrawerOpen}
          onClose={() => setFilterDrawerOpen(false)}
          title="Filtros"
          width="400px"
        >
          <div className="p-2 relative">
            {props.filterContent}
          </div>
        </CustomDrawer>
      )}
    </>
  );
};