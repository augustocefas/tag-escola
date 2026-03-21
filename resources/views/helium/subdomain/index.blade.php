@extends('helium.layout.app')
@section('title', 'Subdomínios')
@section('content')

    <div class="container mx-auto p-4">
        <div class="flex justify-between items-center mb-4">
            
            <h1 class="text-2xl font-semibold">Lista de Subdomínios {{ $domain->domain ? ' > '.$domain->domain : '' }}</h1>
            <button onclick="openDrawer()" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Adicionar 
            </button>
        </div>

        @if(session('success'))
            <div class="bg-green-50 border-l-4 border-green-400 p-4 mb-4">
                <p class="text-green-700">{{ session('success') }}</p>
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
                        <h3 class="text-sm font-medium text-red-800">Erro ao processar subdomínio:</h3>
                        <ul class="mt-2 text-sm text-red-700 list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        @if(!isset($subdomains) || $subdomains->isEmpty())
            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4">
                <p class="text-yellow-700">Nenhum subdomínio encontrado.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white border border-gray-200">
                    <thead class="bg-gray-50">
                    <tr>
                        
                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Subdomínio</th>
                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Domínio</th>
                        <th class="px-4 py-2 text-left text-sm text-center font-medium text-gray-700">Ícone</th>
                        <th class="px-4 py-2 text-left text-sm text-center font-medium text-gray-700">Ativo</th>
                        <th class="px-4 py-2 text-left text-sm text-center font-medium text-gray-700">Criado em</th>
                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-700"></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($subdomains as $subdomain)
                        <tr class="border-t">
                            
                            <td class="px-4 py-2 align-top text-sm text-gray-800">{{ $subdomain->subdomain }}</td>
                            <td class="px-4 py-2 align-top text-sm text-gray-800">{{ optional($subdomain->domain)->domain ?? '—' }}</td>
                            <td class="px-4 py-2 align-top text-center text-sm text-gray-800">
                                @if($subdomain->icon)
                                    <i class="fas {{ $subdomain->icon }}"></i> 
                                @else
                                    —
                                @endif
                            </td>
                            <td class="px-4 py-2 align-top text-center text-sm text-gray-800">
                                <span class="px-2 py-1 text-xs rounded {{ $subdomain->ativo ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $subdomain->ativo ? 'Sim' : 'Não' }}
                                </span>
                            </td>
                            <td class="px-4 py-2 align-top text-center text-sm text-gray-800">{{ optional($subdomain->created_at)->format('d/m/Y H:i') ?? '—' }}</td>
                            <td class="px-4 py-2 align-top text-right text-sm text-gray-800">
                                <button 
                                    onclick="openEditDrawer({{ json_encode($subdomain) }})" 
                                    class="text-gray-400 hover:underline hover:text-green-400 mr-2"
                                >
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button 
                                    onclick="openDeleteModal({{ json_encode($subdomain) }})" 
                                    class="text-gray-400 hover:underline hover:text-red-400"
                                >
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    <!-- Drawer Overlay -->
    <div id="drawerOverlay" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 z-40" onclick="closeDrawer()"></div>

    <!-- Create Drawer -->
    <div id="subdomainDrawer" class="fixed top-0 right-0 h-full w-full md:w-2/3 lg:w-1/2 bg-white shadow-lg transform translate-x-full transition-transform duration-300 ease-in-out z-50 overflow-y-auto">
        <div class="p-6">
            <div class="flex justify-between items-center mb-6 border-b pb-4">
                <h2 class="text-2xl font-semibold">Adicionar {{ $domain->domain ? $domain->domain : 'Subdomínio' }}</h2>
                <button onclick="closeDrawer()" class="text-gray-400 hover:text-gray-600">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <form action="{{ route('helium.subdomain.store') }}" method="POST" id="subdomainForm">
                @csrf

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="subdomain">
                        Subdomínio <span class="text-red-500">*</span>
                    </label>
                    <input 
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('subdomain') border-red-500 @enderror" 
                        id="subdomain" 
                        name="subdomain" 
                        type="text" 
                        placeholder="exemplo"
                        value="{{ old('subdomain') }}"
                        required
                    >
                    @error('subdomain')
                        <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="domain_id">
                        Domínio <span class="text-red-500">*</span>
                    </label>
                    <select 
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('domain_id') border-red-500 @enderror" 
                        id="domain_id" 
                        name="domain_id"
                        required
                    >
                       
                       
                       
                            <option value="{{ $domain->id }}" {{ old('domain_id') == $domain->id ? 'selected' : $domain->id }}>
                                {{ $domain->domain }}
                            </option>
                       
                    </select>
                    @error('domain_id')
                        <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="icon">
                        Ícone
                    </label>
                    <div class="flex items-center gap-2">
                        <div class="flex items-center justify-center h-10 w-16 border rounded bg-gray-50">
                            <i id="icon_preview" class="fas fa-question text-2xl text-gray-400"></i>
                        </div>
                        <input 
                            class="shadow appearance-none border rounded flex-1 py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('icon') border-red-500 @enderror" 
                            id="icon" 
                            name="icon" 
                            type="text" 
                            placeholder="fa-home"
                            value="{{ old('icon') }}"
                            readonly
                        >
                        <button 
                            type="button"
                            onclick="openIconPicker()"
                            class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded"
                        >
                            Escolher
                        </button>
                    </div>
                    @error('icon')
                        <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="font_cor">
                            Cor da Fonte
                        </label>
                        <div class="flex items-center gap-2">
                            <input 
                                class="h-10 w-16 border rounded cursor-pointer @error('font_cor') border-red-500 @enderror" 
                                id="font_cor" 
                                name="font_cor" 
                                type="color" 
                                value="{{ old('font_cor', '#000000') }}"
                            >
                            <input 
                                type="text" 
                                class="shadow appearance-none border rounded flex-1 py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" 
                                id="font_cor_text"
                                value="{{ old('font_cor', '#000000') }}"
                                readonly
                            >
                        </div>
                        @error('font_cor')
                            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="bg_cor">
                            Cor de Fundo
                        </label>
                        <div class="flex items-center gap-2">
                            <input 
                                class="h-10 w-16 border rounded cursor-pointer @error('bg_cor') border-red-500 @enderror" 
                                id="bg_cor" 
                                name="bg_cor" 
                                type="color" 
                                value="{{ old('bg_cor', '#FFFFFF') }}"
                            >
                            <input 
                                type="text" 
                                class="shadow appearance-none border rounded flex-1 py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" 
                                id="bg_cor_text"
                                value="{{ old('bg_cor', '#FFFFFF') }}"
                                readonly
                            >
                        </div>
                        @error('bg_cor')
                            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mb-6">
                    <label class="flex items-center">
                        <input 
                            type="checkbox" 
                            name="ativo" 
                            value="1"
                            {{ old('ativo', true) ? 'checked' : '' }}
                            class="form-checkbox h-5 w-5 text-blue-600"
                        >
                        <span class="ml-2 text-gray-700 text-sm font-bold">Subdomínio Ativo</span>
                    </label>
                </div>

                <div class="flex items-center justify-between border-t pt-4">
                    <button 
                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" 
                        type="submit"
                    >
                        Criar Subdomínio
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
    <div id="subdomainEditDrawer" class="fixed top-0 right-0 h-full w-full md:w-2/3 lg:w-1/2 bg-white shadow-lg transform translate-x-full transition-transform duration-300 ease-in-out z-50 overflow-y-auto">
        <div class="p-6">
            <div class="flex justify-between items-center mb-6 border-b pb-4">
                <h2 class="text-2xl font-semibold">Editar {{ $domain->domain ? $domain->domain : 'Subdomínio' }}</h2>
                <button onclick="closeEditDrawer()" class="text-gray-400 hover:text-gray-600">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <form id="editSubdomainForm" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="edit_subdomain">
                        Subdomínio <span class="text-red-500">*</span>
                    </label>
                    <input 
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" 
                        id="edit_subdomain" 
                        name="subdomain" 
                        type="text" 
                        placeholder="exemplo"
                        required
                    >
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="edit_domain_id">
                        Domínio <span class="text-red-500">*</span>
                    </label>
                    <select 
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" 
                        id="edit_domain_id" 
                        name="domain_id"
                        required
                    >
                        
                        
                            <option value="{{ $domain->id }}">{{ $domain->domain }}</option>
                       
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="edit_icon">
                        Ícone
                    </label>
                    <div class="flex items-center gap-2">
                        <div class="flex items-center justify-center h-10 w-16 border rounded bg-gray-50">
                            <i id="edit_icon_preview" class="fas fa-question text-2xl text-gray-400"></i>
                        </div>
                        <input 
                            class="shadow appearance-none border rounded flex-1 py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" 
                            id="edit_icon" 
                            name="icon" 
                            type="text" 
                            placeholder="fa-home"
                            readonly
                        >
                        <button 
                            type="button"
                            onclick="openEditIconPicker()"
                            class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded"
                        >
                            Escolher
                        </button>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="edit_font_cor">
                            Cor da Fonte
                        </label>
                        <div class="flex items-center gap-2">
                            <input 
                                class="h-10 w-16 border rounded cursor-pointer" 
                                id="edit_font_cor" 
                                name="font_cor" 
                                type="color" 
                                value="#000000"
                            >
                            <input 
                                type="text" 
                                class="shadow appearance-none border rounded flex-1 py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" 
                                id="edit_font_cor_text"
                                value="#000000"
                                readonly
                            >
                        </div>
                    </div>

                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="edit_bg_cor">
                            Cor de Fundo
                        </label>
                        <div class="flex items-center gap-2">
                            <input 
                                class="h-10 w-16 border rounded cursor-pointer" 
                                id="edit_bg_cor" 
                                name="bg_cor" 
                                type="color" 
                                value="#FFFFFF"
                            >
                            <input 
                                type="text" 
                                class="shadow appearance-none border rounded flex-1 py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" 
                                id="edit_bg_cor_text"
                                value="#FFFFFF"
                                readonly
                            >
                        </div>
                    </div>
                </div>

                <div class="mb-6">
                    <label class="flex items-center">
                        <input 
                            type="checkbox" 
                            id="edit_ativo"
                            name="ativo" 
                            value="1"
                            class="form-checkbox h-5 w-5 text-blue-600"
                        >
                        <span class="ml-2 text-gray-700 text-sm font-bold">Subdomínio Ativo</span>
                    </label>
                </div>

                <div class="flex items-center justify-between border-t pt-4">
                    <button 
                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" 
                        type="submit"
                    >
                        Atualizar Subdomínio
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
                <h3 class="text-lg leading-6 font-medium text-gray-900 mt-5">Excluir {{ $domain->domain ? $domain->domain : 'Subdomínio' }}</h3>
                <h2 class="text-lg font-medium text-gray-900 mt-2"><strong id="deleteSubdomainName"></strong></h2>
                <div class="mt-2 px-7 py-3">
                    <p class="text-sm text-gray-500">
                        Tem certeza que deseja excluir o subdomínio?
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

    <!-- Modal do Icon Picker -->
    <div id="iconPickerModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-[60]">
        <div class="relative top-20 mx-auto p-5 border w-11/12 max-w-4xl shadow-lg rounded-md bg-white">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-bold">Escolher Ícone - Font Awesome 6 Free</h3>
                <button onclick="closeIconPicker()" class="text-gray-400 hover:text-gray-600">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <div class="mb-4">
                <input 
                    type="text" 
                    id="iconSearch" 
                    placeholder="Buscar ícone..."
                    class="w-full px-3 py-2 border rounded"
                    onkeyup="filterIcons()"
                >
            </div>

            <div class="max-h-96 overflow-y-auto">
                <div class="grid grid-cols-6 gap-4" id="iconGrid">
                    <!-- Ícones Populares -->
                </div>
            </div>
        </div>
    </div>

    <script>
        // Drawer functions
        function openDrawer() {
            document.getElementById('subdomainDrawer').classList.remove('translate-x-full');
            document.getElementById('drawerOverlay').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeDrawer() {
            document.getElementById('subdomainDrawer').classList.add('translate-x-full');
            document.getElementById('drawerOverlay').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        // Abrir drawer se houver erros de validação
        @if ($errors->any())
            window.addEventListener('DOMContentLoaded', function() {
                openDrawer();
            });
        @endif

        // Sincronizar o color picker com o campo de texto
        document.getElementById('font_cor').addEventListener('input', function(e) {
            document.getElementById('font_cor_text').value = e.target.value.toUpperCase();
        });
        
        document.getElementById('bg_cor').addEventListener('input', function(e) {
            document.getElementById('bg_cor_text').value = e.target.value.toUpperCase();
        });

        // Lista de ícones Font Awesome 6 Free
        const fontAwesomeIcons = [
            'fa-house', 'fa-home', 'fa-bars', 'fa-xmark', 'fa-magnifying-glass', 'fa-search',
            'fa-gear', 'fa-cog', 'fa-sliders', 'fa-ellipsis', 'fa-ellipsis-vertical',
            'fa-user', 'fa-users', 'fa-user-group', 'fa-user-tie', 'fa-user-gear',
            'fa-circle-user', 'fa-id-card', 'fa-address-card',
            'fa-envelope', 'fa-phone', 'fa-comment', 'fa-comments', 'fa-message',
            'fa-bell', 'fa-inbox', 'fa-paper-plane',
            'fa-file', 'fa-folder', 'fa-folder-open', 'fa-file-pdf', 'fa-file-word',
            'fa-file-excel', 'fa-file-image', 'fa-clipboard', 'fa-copy',
            'fa-plus', 'fa-minus', 'fa-pen', 'fa-pencil', 'fa-trash', 'fa-check',
            'fa-xmark', 'fa-download', 'fa-upload', 'fa-share', 'fa-save',
            'fa-arrow-right', 'fa-arrow-left', 'fa-arrow-up', 'fa-arrow-down',
            'fa-chevron-right', 'fa-chevron-left', 'fa-chevron-up', 'fa-chevron-down',
            'fa-angles-right', 'fa-angles-left',
            'fa-chart-line', 'fa-chart-bar', 'fa-chart-pie', 'fa-dollar-sign',
            'fa-credit-card', 'fa-wallet', 'fa-briefcase', 'fa-building',
            'fa-cart-shopping', 'fa-bag-shopping', 'fa-store', 'fa-tag', 'fa-tags',
            'fa-barcode', 'fa-receipt',
            'fa-image', 'fa-video', 'fa-camera', 'fa-play', 'fa-pause', 'fa-stop',
            'fa-music', 'fa-film', 'fa-photo-film',
            'fa-laptop', 'fa-mobile', 'fa-tablet', 'fa-desktop', 'fa-keyboard',
            'fa-database', 'fa-server', 'fa-wifi', 'fa-cloud',
            'fa-location-dot', 'fa-map', 'fa-map-pin', 'fa-globe', 'fa-compass',
            'fa-clock', 'fa-calendar', 'fa-calendar-days', 'fa-hourglass',
            'fa-circle-check', 'fa-circle-xmark', 'fa-circle-exclamation',
            'fa-triangle-exclamation', 'fa-circle-info', 'fa-star', 'fa-heart',
            'fa-lock', 'fa-unlock', 'fa-key', 'fa-shield', 'fa-eye', 'fa-eye-slash',
            'fa-share-nodes', 'fa-thumbs-up', 'fa-thumbs-down', 'fa-bookmark',
            'fa-box', 'fa-gift', 'fa-trophy', 'fa-flag', 'fa-lightbulb',
            'fa-bolt', 'fa-fire', 'fa-rocket', 'fa-crown', 'fa-diamond'
        ];

        function renderIcons() {
            const iconGrid = document.getElementById('iconGrid');
            iconGrid.innerHTML = '';
            
            fontAwesomeIcons.forEach(icon => {
                const iconDiv = document.createElement('div');
                iconDiv.className = 'icon-item flex flex-col items-center justify-center p-3 border rounded hover:bg-blue-50 cursor-pointer transition';
                iconDiv.setAttribute('data-icon', icon);
                iconDiv.onclick = () => selectIcon(icon);
                iconDiv.innerHTML = `
                    <i class="fas ${icon} text-2xl mb-2"></i>
                    <span class="text-xs text-gray-600 text-center">${icon.replace('fa-', '')}</span>
                `;
                iconGrid.appendChild(iconDiv);
            });
        }

        let editMode = false;

        function selectIcon(icon) {
            if (editMode) {
                document.getElementById('edit_icon').value = icon;
                document.getElementById('edit_icon_preview').className = `fas ${icon} text-2xl text-gray-700`;
                editMode = false;
            } else {
                document.getElementById('icon').value = icon;
                document.getElementById('icon_preview').className = `fas ${icon} text-2xl text-gray-700`;
            }
            closeIconPicker();
        }

        function openIconPicker() {
            editMode = false;
            document.getElementById('iconPickerModal').classList.remove('hidden');
            renderIcons();
        }

        function openEditIconPicker() {
            editMode = true;
            document.getElementById('iconPickerModal').classList.remove('hidden');
            renderIcons();
        }

        function closeIconPicker() {
            document.getElementById('iconPickerModal').classList.add('hidden');
            document.getElementById('iconSearch').value = '';
            renderIcons();
        }

        function filterIcons() {
            const searchTerm = document.getElementById('iconSearch').value.toLowerCase();
            const iconItems = document.querySelectorAll('.icon-item');
            
            iconItems.forEach(item => {
                const iconName = item.getAttribute('data-icon').toLowerCase();
                if (iconName.includes(searchTerm)) {
                    item.style.display = 'flex';
                } else {
                    item.style.display = 'none';
                }
            });
        }

        document.getElementById('icon').addEventListener('input', function(e) {
            const iconClass = e.target.value;
            if (iconClass) {
                document.getElementById('icon_preview').className = `fas ${iconClass} text-2xl text-gray-700`;
            } else {
                document.getElementById('icon_preview').className = 'fas fa-question text-2xl text-gray-400';
            }
        });

        window.addEventListener('DOMContentLoaded', function() {
            const iconValue = document.getElementById('icon').value;
            if (iconValue) {
                document.getElementById('icon_preview').className = `fas ${iconValue} text-2xl text-gray-700`;
            }
        });

        // ===== EDIT DRAWER FUNCTIONS =====
        
        function openEditDrawer(subdomain) {
            document.getElementById('edit_subdomain').value = subdomain.subdomain || '';
            document.getElementById('edit_domain_id').value = subdomain.domain_id || '';
            document.getElementById('edit_icon').value = subdomain.icon || '';
            
            if (subdomain.icon) {
                document.getElementById('edit_icon_preview').className = `fas ${subdomain.icon} text-2xl text-gray-700`;
            } else {
                document.getElementById('edit_icon_preview').className = 'fas fa-question text-2xl text-gray-400';
            }
            
            const fontCor = subdomain.font_cor || '#000000';
            const bgCor = subdomain.bg_cor || '#FFFFFF';
            document.getElementById('edit_font_cor').value = fontCor;
            document.getElementById('edit_font_cor_text').value = fontCor.toUpperCase();
            document.getElementById('edit_bg_cor').value = bgCor;
            document.getElementById('edit_bg_cor_text').value = bgCor.toUpperCase();
            
            document.getElementById('edit_ativo').checked = subdomain.ativo == 1;
            
            const form = document.getElementById('editSubdomainForm');
            form.action = `/helium/subdomain/${subdomain.id}`;
            
            document.getElementById('subdomainEditDrawer').classList.remove('translate-x-full');
            document.getElementById('editDrawerOverlay').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeEditDrawer() {
            document.getElementById('subdomainEditDrawer').classList.add('translate-x-full');
            document.getElementById('editDrawerOverlay').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        document.getElementById('edit_font_cor').addEventListener('input', function(e) {
            document.getElementById('edit_font_cor_text').value = e.target.value.toUpperCase();
        });
        
        document.getElementById('edit_bg_cor').addEventListener('input', function(e) {
            document.getElementById('edit_bg_cor_text').value = e.target.value.toUpperCase();
        });

        document.getElementById('edit_icon').addEventListener('input', function(e) {
            const iconClass = e.target.value;
            if (iconClass) {
                document.getElementById('edit_icon_preview').className = `fas ${iconClass} text-2xl text-gray-700`;
            } else {
                document.getElementById('edit_icon_preview').className = 'fas fa-question text-2xl text-gray-400';
            }
        });

        // ===== DELETE MODAL FUNCTIONS =====
        
        function openDeleteModal(subdomain) {
            document.getElementById('deleteSubdomainName').textContent = subdomain.subdomain;
            const form = document.getElementById('deleteForm');
            form.action = `/helium/subdomain/${subdomain.id}`;
            document.getElementById('deleteModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }
    </script>
@endsection
