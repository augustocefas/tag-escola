import React, { useState } from 'react';
import { PiPlus, PiTrash, PiSpinner, PiNotePencil } from 'react-icons/pi';
import {
    useUsuarios,
    useCreateUsuario,
    useUpdateUsuario,
    useDeleteUsuario,
} from '../../hooks/useUsuario';
//import { useEscritorios } from '../../hooks/useEscritorio';
import type { Usuario } from '../../types/models';

import { CustomDataGrid } from '../../components/common/CustomDataGrid';
import { CustomDrawer } from '../../components/common/CustomDrawer';
import { GridActionsCellItem } from '@mui/x-data-grid-pro';
import type { GridPaginationModel, GridColDef } from '@mui/x-data-grid-pro';
import { PiUsers } from 'react-icons/pi';

export const UsuariosConfig: React.FC = () => {
    const [paginationModel, setPaginationModel] = useState<GridPaginationModel>(
        {
            page: 0,
            pageSize: 15,
        },
    );

    const { data: result, isLoading } = useUsuarios();

    // The backend returns an array of users inside data
    const usuarios = result?.data || [];

    const createUsuario = useCreateUsuario();
    const updateUsuario = useUpdateUsuario();
    const deleteUsuario = useDeleteUsuario();

    // const { data: escritoriosResult } = useEscritorios(1);
    // const escritorios: Escritorio[] = (escritoriosResult as any)?.data?.escritorios?.data || (escritoriosResult as any)?.data?.data || (escritoriosResult as any)?.data || [];

    const [isModalOpen, setIsModalOpen] = useState(false);

    const emptyUsuario: Partial<Usuario> & { password?: string } = {
        name: '',
        email: '',
        is_gestor: false,
        password: '',
        escritorio_id: '',
    };

    const [editingUsuario, setEditingUsuario] = useState<
        Partial<Usuario> & { password?: string }
    >(emptyUsuario);

    const handleOpenAdd = () => {
        setEditingUsuario(emptyUsuario);
        setIsModalOpen(true);
    };

    const handleOpenEdit = (usuarioData: Usuario) => {
        setEditingUsuario({ ...usuarioData, password: '' }); // Don't preload password
        setIsModalOpen(true);
    };

    const handleSave = (e: React.FormEvent) => {
        e.preventDefault();

        if (editingUsuario.id) {
            // If editing, only send password if it was filled
            const payload: Partial<Usuario> & { password?: string } = {
                name: editingUsuario.name,
                email: editingUsuario.email,
                is_gestor: editingUsuario.is_gestor,
                escritorio_id: editingUsuario.escritorio_id,
            };
            if (editingUsuario.password) {
                payload.password = editingUsuario.password;
            }
            updateUsuario.mutate({ id: editingUsuario.id, payload });
        } else {
            createUsuario.mutate(editingUsuario);
        }
        setIsModalOpen(false);
    };

    const handleDelete = (id: string, name: string) => {
        if (
            window.confirm(
                `Tem certeza que deseja deletar o usuário "${name}"?`,
            )
        ) {
            deleteUsuario.mutate(id);
        }
    };

    const columns: GridColDef[] = [
        { field: 'name', headerName: 'Nome', flex: 1, minWidth: 200 },
        { field: 'email', headerName: 'E-mail', flex: 1, minWidth: 200 },
        {
            field: 'is_gestor',
            headerName: 'Perfil',
            width: 130,
            renderCell: (params) => (
                <span
                    className={`px-2 py-1 text-xs font-semibold rounded-full ${
                        params.row.is_gestor
                            ? 'bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-400'
                            : 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300'
                    }`}
                >
                    {params.row.is_gestor ? 'Gestor' : 'Padrão'}
                </span>
            ),
        },
        {
            field: 'actions',
            type: 'actions',
            headerName: '',
            width: 100,
            getActions: (params) => [
                <GridActionsCellItem
                    key="edit"
                    icon={<PiNotePencil size={20} className="text-blue-500" />}
                    label="Editar"
                    onClick={() => handleOpenEdit(params.row)}
                />,
                <GridActionsCellItem
                    key="delete"
                    icon={<PiTrash size={20} className="text-red-500" />}
                    label="Excluir"
                    onClick={() => handleDelete(params.row.id, params.row.name)}
                />,
            ],
        },
    ];

    return (
        <div className="flex flex-col h-[calc(90vh-10px)] gap-4">
            <div className="flex justify-between items-center mb-2">
                <h1 className="text-2xl font-semibold text-gray-800 dark:text-white capitalize">
                    Configurações
                </h1>
                <button
                    onClick={handleOpenAdd}
                    className="cursor-pointer flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm font-medium shadow-sm"
                >
                    <PiPlus size={18} /> Adicionar Usuário
                </button>
            </div>

            <div className="flex-1 overflow-hidden flex flex-col bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
                {isLoading ? (
                    <div className="flex-1 flex justify-center items-center">
                        <PiSpinner
                            className="animate-spin text-gray-400"
                            size={32}
                        />
                    </div>
                ) : usuarios.length === 0 ? (
                    <div className="flex-1 flex flex-col items-center justify-center p-8 text-sm text-gray-500">
                        <div className="p-4 bg-blue-50 dark:bg-blue-900/20 rounded-full mb-3">
                            <PiUsers
                                size={40}
                                className="text-blue-500 dark:text-blue-400"
                            />
                        </div>
                        Nenhum usuário cadastrado.
                    </div>
                ) : (
                    <CustomDataGrid
                        gridId="usuarios"
                        rows={usuarios}
                        loading={isLoading}
                        paginationModel={paginationModel}
                        onPaginationModelChange={setPaginationModel}
                        columns={columns}
                        sx={{
                            borderTop: 'none',
                            borderLeft: 'none',
                            borderRight: 'none',
                            borderBottom: 'none',
                        }}
                    />
                )}
            </div>

            <CustomDrawer
                open={isModalOpen}
                onClose={() => setIsModalOpen(false)}
                title={editingUsuario.id ? 'Editar Usuário' : 'Novo Usuário'}
                width="60%"
                footer={
                    <>
                        <button
                            type="button"
                            onClick={() => setIsModalOpen(false)}
                            className="cursor-pointer px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none"
                        >
                            Cancelar
                        </button>
                        <button
                            type="submit"
                            form="usuario-form"
                            disabled={
                                createUsuario.isPending ||
                                updateUsuario.isPending
                            }
                            className="cursor-pointer px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50"
                        >
                            {editingUsuario.id ? 'Atualizar' : 'Salvar'}
                        </button>
                    </>
                }
            >
                <form
                    id="usuario-form"
                    onSubmit={handleSave}
                    className="space-y-4 p-4"
                >
                    <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div className="col-span-1 md:col-span-2">
                            <label className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Nome *
                            </label>
                            <input
                                type="text"
                                required
                                value={editingUsuario.name || ''}
                                onChange={(e) =>
                                    setEditingUsuario({
                                        ...editingUsuario,
                                        name: e.target.value,
                                    })
                                }
                                className="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                            />
                        </div>

                        <div className="col-span-1 md:col-span-2">
                            <label className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                E-mail *
                            </label>
                            <input
                                autoComplete="new-password"
                                type="email"
                                required
                                value={editingUsuario.email || ''}
                                onChange={(e) =>
                                    setEditingUsuario({
                                        ...editingUsuario,
                                        email: e.target.value,
                                    })
                                }
                                className="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                            />
                        </div>

                        <div className="col-span-1 md:col-span-2">
                            <label className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Escritório *
                            </label>
                            <select
                                value={editingUsuario.escritorio_id || ''}
                                onChange={(e) =>
                                    setEditingUsuario({
                                        ...editingUsuario,
                                        escritorio_id: e.target.value,
                                    })
                                }
                                required
                                className="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                            >
                                <option value="">
                                    Selecione um escritório
                                </option>
                                {/* {escritorios.map((esc: any) => (
                  <option key={esc.id} value={esc.id}>{esc.nome}</option>
                ))} */}
                            </select>
                        </div>

                        <div className="col-span-1 md:col-span-2">
                            <label className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Senha{' '}
                                {editingUsuario.id && (
                                    <span className="text-gray-400 text-xs font-normal">
                                        (deixe em branco para manter a atual)
                                    </span>
                                )}{' '}
                                {!editingUsuario.id && '*'}
                            </label>
                            <input
                                type="password"
                                autoComplete="new-password"
                                required={!editingUsuario.id}
                                minLength={6}
                                value={editingUsuario.password || ''}
                                onChange={(e) =>
                                    setEditingUsuario({
                                        ...editingUsuario,
                                        password: e.target.value,
                                    })
                                }
                                className="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                            />
                        </div>

                        <div className="col-span-1 md:col-span-2 flex items-center mt-2">
                            <input
                                id="is_gestor"
                                type="checkbox"
                                checked={editingUsuario.is_gestor || false}
                                onChange={(e) =>
                                    setEditingUsuario({
                                        ...editingUsuario,
                                        is_gestor: e.target.checked,
                                    })
                                }
                                className="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded cursor-pointer"
                            />
                            <label
                                htmlFor="is_gestor"
                                className="ml-2 block text-sm text-gray-900 dark:text-gray-300 cursor-pointer"
                            >
                                Usuário possui permissão de Gestor
                            </label>
                        </div>
                    </div>
                </form>
            </CustomDrawer>
        </div>
    );
};
