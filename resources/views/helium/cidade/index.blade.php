@extends('helium.layout.app')
@section('title', 'Cidades')
@section('content')

    <div class="container mx-auto p-4">
        <div class="flex justify-between items-center mb-4">
            <h1 class="text-2xl font-semibold">Lista de Cidades</h1>
            <button onclick="openDrawer()" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Adicionar Cidade
            </button>
        </div>

        @if(session('success'))
            <div class="bg-green-50 border-l-4 border-green-400 p-4 mb-4">
                <p class="text-green-700">{{ session('success') }}</p>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-50 border-l-4 border-red-400 p-4 mb-4">
                <p class="text-red-700">{{ session('error') }}</p>
            </div>
        @endif

        @if ($errors->any())
            <div class="bg-red-50 border-l-4 border-red-400 p-4 mb-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800">Erro ao processar cidade:</h3>
                        <ul class="mt-2 text-sm text-red-700 list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <!-- Filtro de Estado -->
        <div class="bg-white border border-gray-200 rounded-lg p-4 mb-4">
            <form method="GET" action="{{ route('helium.cidade.index') }}" class="flex items-center gap-4">
                <div class="flex-1">
                    <label for="filter_estado_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Filtrar por Estado
                    </label>
                    <select 
                        id="filter_estado_id" 
                        name="estado_id" 
                        onchange="this.form.submit()"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline focus:border-blue-500">
                        <option value="">Todos os Estados</option>
                        @foreach($estados as $est)
                            <option value="{{ $est->id }}" {{ request('estado_id') == $est->id ? 'selected' : '' }}>
                                {{ $est->nome }} - {{ $est->pais->nome ?? '' }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                @if(request('estado_id'))
                    <div class="pt-6">
                        <a href="{{ route('helium.cidade.index') }}" 
                           class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            Limpar Filtro
                        </a>
                    </div>
                @endif
            </form>
            
            @if(request('estado_id') && isset($estado))
                <div class="mt-3 flex items-center text-sm text-gray-600">
                    <svg class="w-4 h-4 mr-2 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                    </svg>
                    Mostrando cidades de: <strong class="ml-1">{{ $estado->nome }} - {{ $estado->pais->nome ?? '' }}</strong>
                </div>
            @endif
        </div>

        @if(!isset($cidades) || $cidades->isEmpty())
            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4">
                <p class="text-yellow-700">Nenhuma cidade encontrada.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white border border-gray-200">
                    <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Nome</th>
                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Estado</th>
                        <th class="px-4 py-2 text-center text-sm font-medium text-gray-700">IBGE</th>
                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-700"></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($cidades as $cidade)
                        <tr class="border-t">
                            <td class="px-4 py-2 align-top text-sm text-gray-800">{{ $cidade->nome }}</td>
                            <td class="px-4 py-2 align-top text-sm text-gray-800">{{ $cidade->estado->nome ?? '—' }}</td>
                            <td class="px-4 py-2 align-top text-center text-sm text-gray-800">{{ $cidade->ibge ?? '—' }}</td>
                            <td class="px-4 py-2 align-top text-center text-sm text-gray-800">{{ optional($cidade->created_at)->format('d/m/Y H:i') ?? '—' }}</td>
                            <td class="px-4 py-2 align-top text-right text-sm text-gray-800">
                                <a href="{{ route('helium.estado.index', $cidade->id) }}"
                                    class="text-gray-400 hover:underline hover:text-blue-400 mr-2"
                                    title="Ver Estados">
                                    <i class="fas fa-list"></i>
                                </a>
                                <button
                                    onclick="openEditDrawer({{ json_encode($cidade) }})"
                                    class="text-gray-400 hover:underline hover:text-green-400 mr-2"
                                    title="Editar">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button
                                    onclick="openDeleteModal({{ json_encode($cidade) }})"
                                    class="text-gray-400 hover:underline hover:text-red-400"
                                    title="Excluir">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Paginação -->
            <div class="mt-4">
                {{ $cidades->links() }}
            </div>
        @endif
    </div>

    <!-- Drawer Overlay -->
    <div id="drawerOverlay" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 z-40" onclick="closeDrawer()"></div>

    <!-- Create Drawer -->
    <div id="cidadeDrawer" class="fixed top-0 right-0 h-full w-full md:w-2/3 lg:w-1/2 bg-white shadow-lg transform translate-x-full transition-transform duration-300 ease-in-out z-50 overflow-y-auto">
        <div class="p-6">
            <div class="flex justify-between items-center mb-6 border-b pb-4">
                <h2 class="text-2xl font-semibold">Adicionar Nova Cidade</h2>
                <button onclick="closeDrawer()" class="text-gray-400 hover:text-gray-600">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <form action="{{ route('helium.cidade.store') }}" method="POST">
                @csrf

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="nome">
                        Nome <span class="text-red-500">*</span>
                    </label>
                    <input
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('nome') border-red-500 @enderror"
                        id="nome"
                        name="nome"
                        type="text"
                        placeholder="São Paulo"
                        value="{{ old('nome') }}"
                        required
                    >
                    @error('nome')
                        <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="estado_id">
                        Estado <span class="text-red-500">*</span>
                    </label>
                    <select
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('estado_id') border-red-500 @enderror"
                        id="estado_id"
                        name="estado_id"
                        required
                    >
                        <option value="">Selecione um estado</option>
                        @foreach($estados as $estado)
                            <option value="{{ $estado->id }}" {{ old('estado_id') == $estado->id ? 'selected' : '' }}>
                                {{ $estado->nome }} - {{ $estado->pais->nome ?? '' }}
                            </option>
                        @endforeach
                    </select>
                    @error('estado_id')
                        <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="ibge">
                        Código IBGE
                    </label>
                    <input
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('ibge') border-red-500 @enderror"
                        id="ibge"
                        name="ibge"
                        type="number"
                        placeholder="3550308"
                        value="{{ old('ibge') }}"
                    >
                    @error('ibge')
                        <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-between border-t pt-4">
                    <button
                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
                        type="submit"
                    >
                        Criar Cidade
                    </button>
                    <button
                        type="button"
                        onclick="closeDrawer()"
                        class="inline-block align-baseline font-bold text-sm text-gray-500 hover:text-gray-800"
                    >
                        Cancelar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Drawer Overlay -->
    <div id="editDrawerOverlay" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 z-40" onclick="closeEditDrawer()"></div>

    <!-- Edit Drawer -->
    <div id="cidadeEditDrawer" class="fixed top-0 right-0 h-full w-full md:w-2/3 lg:w-1/2 bg-white shadow-lg transform translate-x-full transition-transform duration-300 ease-in-out z-50 overflow-y-auto">
        <div class="p-6">
            <div class="flex justify-between items-center mb-6 border-b pb-4">
                <h2 class="text-2xl font-semibold">Editar Cidade</h2>
                <button onclick="closeEditDrawer()" class="text-gray-400 hover:text-gray-600">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <form id="editCidadeForm" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="edit_nome">
                        Nome <span class="text-red-500">*</span>
                    </label>
                    <input
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        id="edit_nome"
                        name="nome"
                        type="text"
                        required
                    >
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="edit_estado_id">
                        Estado <span class="text-red-500">*</span>
                    </label>
                    <select
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        id="edit_estado_id"
                        name="estado_id"
                        required
                    >
                        <option value="">Selecione um estado</option>
                        @foreach($estados as $estado)
                            <option value="{{ $estado->id }}">
                                {{ $estado->nome }} - {{ $estado->pais->nome ?? '' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="edit_ibge">
                        Código IBGE
                    </label>
                    <input
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        id="edit_ibge"
                        name="ibge"
                        type="number"
                    >
                </div>

                <div class="flex items-center justify-between border-t pt-4">
                    <button
                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
                        type="submit"
                    >
                        Atualizar Cidade
                    </button>
                    <button
                        type="button"
                        onclick="closeEditDrawer()"
                        class="inline-block align-baseline font-bold text-sm text-gray-500 hover:text-gray-800"
                    >
                        Cancelar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Delete Modal -->
    <div id="deleteModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3 text-center">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                    <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                </div>
                <h3 class="text-lg leading-6 font-medium text-gray-900 mt-5">Excluir Cidade</h3>
                <h2 class="text-lg font-medium text-gray-900 mt-2"><strong id="deleteCidadeNome"></strong></h2>
                <div class="mt-2 px-7 py-3">
                    <p class="text-sm text-gray-500">
                        Tem certeza que deseja excluir esta cidade?
                        Esta ação não pode ser desfeita.
                    </p>
                </div>
                <div class="items-center px-4 py-3">
                    <form id="deleteForm" method="POST">
                        @csrf
                        @method('DELETE')
                        <button
                            type="submit"
                            class="px-4 py-2 bg-red-500 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-300"
                        >
                            Confirmar Exclusão
                        </button>
                    </form>
                    <button
                        onclick="closeDeleteModal()"
                        class="mt-3 px-4 py-2 bg-gray-100 text-gray-700 text-base font-medium rounded-md w-full shadow-sm hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-300"
                    >
                        Cancelar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Drawer functions
        function openDrawer() {
            document.getElementById('cidadeDrawer').classList.remove('translate-x-full');
            document.getElementById('drawerOverlay').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeDrawer() {
            document.getElementById('cidadeDrawer').classList.add('translate-x-full');
            document.getElementById('drawerOverlay').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        // Abrir drawer se houver erros de validação
        @if ($errors->any())
            window.addEventListener('DOMContentLoaded', function() {
                openDrawer();
            });
        @endif

        // Edit drawer functions
        function openEditDrawer(cidade) {
            document.getElementById('edit_nome').value = cidade.nome || '';
            document.getElementById('edit_estado_id').value = cidade.estado_id || '';
            document.getElementById('edit_ibge').value = cidade.ibge || '';

            const form = document.getElementById('editCidadeForm');
            form.action = `/helium/cidade/${cidade.id}`;

            document.getElementById('cidadeEditDrawer').classList.remove('translate-x-full');
            document.getElementById('editDrawerOverlay').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeEditDrawer() {
            document.getElementById('cidadeEditDrawer').classList.add('translate-x-full');
            document.getElementById('editDrawerOverlay').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        // Delete modal functions
        function openDeleteModal(cidade) {
            document.getElementById('deleteCidadeNome').textContent = cidade.nome;
            const form = document.getElementById('deleteForm');
            form.action = `/helium/cidade/${cidade.id}`;
            document.getElementById('deleteModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }
    </script>
@endsection
