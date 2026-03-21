@extends('helium.layout.app')
@section('title', 'Dashboard')
@section('content')

    <div class="container mx-auto p-4">
        <div class="flex justify-between items-center mb-4">
            <h1 class="text-2xl font-semibold">Lista de Dominios</h1>
            <button onclick="openDrawer()" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Adicionar Domínio
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
                        <h3 class="text-sm font-medium text-red-800">Erro ao criar domínio:</h3>
                        <ul class="mt-2 text-sm text-red-700 list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        @if(!isset($domains) || $domains->isEmpty())
            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4">
                <p class="text-yellow-700">Nenhum dominio encontrado.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white border border-gray-200">
                    <thead class="bg-gray-50">
                    <tr>
                       
                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Dominio</th>
                        <th class="px-4 py-2 text-left text-sm text-center font-medium text-gray-700">Icone</th>
                        <th class="px-4 py-2 text-left text-sm text-center font-medium text-gray-700">Datasource</th>
                        <th class="px-4 py-2 text-left text-sm text-center font-medium text-gray-700">Ativo</th>
                        <th class="px-4 py-2 text-left text-sm text-center font-medium text-gray-700">Criado em</th>
                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-700"></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($domains as $domain)
                        <tr class="border-t">
                           
                            <td class="px-4 py-2 align-top text-sm text-gray-800">
                                {{ $domain->domain }}
                            </td>
                            <td class="px-4 py-2 align-top text-center text-sm text-gray-800">
                                @if($domain->icon)
                                    <i class="fas {{ $domain->icon }}"></i> 
                                @else
                                    —
                                @endif
                            </td>
                            <td class="px-4 py-2 align-top text-center text-sm text-gray-800 break-all">{{ $domain->datasource ? 'Sim' : '-' }}</td>
                            <td class="px-4 py-2 align-top text-center text-sm text-gray-800 break-all">{{ $domain->ativo ? 'Sim' : '-' }}</td>
                            <td class="px-4 py-2 align-top text-center text-sm text-gray-800">{{ optional($domain->created_at)->format('d/m/Y H:i') ?? '—' }}</td>
                            <td class="px-4 py-2 align-top text-right text-sm text-gray-800">
                                <a href="{{ route('helium.subdomain.index', $domain->id) }}" 
                                    class="text-gray-400 hover:underline hover:text-blue-400 mr-2">
                                    <i class="fas fa-list"></i>
                                </a>
                                <button 
                                    onclick="openEditDrawer({{ json_encode($domain) }})" 
                                    class="text-gray-400 hover:underline hover:text-red-400"
                                >
                                    <i class="fas fa-edit"></i>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Paginação, se disponível --}}
            @if(method_exists($domains, 'links'))
                <div class="mt-4">
                    {{ $domains->links() }}
                </div>
            @endif
        @endif
    </div>

    <!-- Drawer Overlay -->
    <div id="drawerOverlay" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 z-40" onclick="closeDrawer()"></div>

    <!-- Drawer -->
    <div id="domainDrawer" class="fixed top-0 right-0 h-full w-full md:w-2/3 lg:w-1/2 bg-white shadow-lg transform translate-x-full transition-transform duration-300 ease-in-out z-50 overflow-y-auto">
        <div class="p-6">
            <!-- Header do Drawer -->
            <div class="flex justify-between items-center mb-6 border-b pb-4">
                <h2 class="text-2xl font-semibold">Adicionar Novo Domínio</h2>
                <button onclick="closeDrawer()" class="text-gray-400 hover:text-gray-600">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <!-- Formulário -->
            <form action="{{ route('helium.domain.store') }}" method="POST" id="domainForm">
                @csrf

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="domain">
                        Domínio <span class="text-red-500">*</span>
                    </label>
                    <input 
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('domain') border-red-500 @enderror" 
                        id="domain" 
                        name="domain" 
                        type="text" 
                        placeholder="exemplo"
                        value="{{ old('domain') }}"
                        required
                    >
                    @error('domain')
                        <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="navigatio_opc">
                        Navegação (Opção) <span class="text-red-500">*</span>
                    </label>
                    <input 
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('navigatio_opc') border-red-500 @enderror" 
                        id="navigatio_opc" 
                        name="navigatio_opc" 
                        type="text" 
                        placeholder="menu_principal"
                        value="{{ old('navigatio_opc') }}"
                        required
                    >
                    @error('navigatio_opc')
                        <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="navigation_subopc">
                        Navegação (Sub-opção)
                    </label>
                    <input 
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('navigation_subopc') border-red-500 @enderror" 
                        id="navigation_subopc" 
                        name="navigation_subopc" 
                        type="text" 
                        placeholder="submenu_item"
                        value="{{ old('navigation_subopc') }}"
                    >
                    @error('navigation_subopc')
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

                <div class="mb-4">
                    <label class="flex items-center">
                        <input 
                            type="checkbox" 
                            name="datasource" 
                            value="1"
                            {{ old('datasource', true) ? 'checked' : '' }}
                            class="form-checkbox h-5 w-5 text-blue-600"
                        >
                        <span class="ml-2 text-gray-700 text-sm font-bold">Fonte de dados (datasource)</span>
                    </label>
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
                        <span class="ml-2 text-gray-700 text-sm font-bold">Ativo</span>
                    </label>
                </div>

                <div class="flex items-center justify-between border-t pt-4">
                    <button 
                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" 
                        type="submit"
                    >
                        Criar Domínio
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
    <div id="domainEditDrawer" class="fixed top-0 right-0 h-full w-full md:w-2/3 lg:w-1/2 bg-white shadow-lg transform translate-x-full transition-transform duration-300 ease-in-out z-50 overflow-y-auto">
        <div class="p-6">
            <!-- Header do Drawer -->
            <div class="flex justify-between items-center mb-6 border-b pb-4">
                <h2 class="text-2xl font-semibold">Editar Domínio</h2>
                <button onclick="closeEditDrawer()" class="text-gray-400 hover:text-gray-600">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <!-- Formulário de Edição -->
            <form id="editDomainForm" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="edit_domain">
                        Domínio <span class="text-red-500">*</span>
                    </label>
                    <input 
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" 
                        id="edit_domain" 
                        name="domain" 
                        type="text" 
                        placeholder="exemplo"
                        required
                    >
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="edit_navigatio_opc">
                        Navegação (Opção) <span class="text-red-500">*</span>
                    </label>
                    <input 
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" 
                        id="edit_navigatio_opc" 
                        name="navigatio_opc" 
                        type="text" 
                        placeholder="menu_principal"
                        
                    >
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="edit_navigation_subopc">
                        Navegação (Sub-opção)
                    </label>
                    <input 
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" 
                        id="edit_navigation_subopc" 
                        name="navigation_subopc" 
                        type="text" 
                        placeholder="submenu_item"
                    >
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

                
                <div class="mb-4">
                    <label class="flex items-center">
                        <input 
                            type="checkbox" 
                            id="edit_datasource"
                            name="datasource" 
                            value="1"
                            class="form-checkbox h-5 w-5 text-blue-600"
                        >
                        <span class="ml-2 text-gray-700 text-sm font-bold">Fonte de dados (datasource)</span>
                    </label>
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
                        <span class="ml-2 text-gray-700 text-sm font-bold">Ativo</span>
                    </label>
                </div>

                <div class="flex items-center justify-between border-t pt-4">
                    <button 
                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" 
                        type="submit"
                    >
                        Atualizar Domínio
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
            document.getElementById('domainDrawer').classList.remove('translate-x-full');
            document.getElementById('drawerOverlay').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeDrawer() {
            document.getElementById('domainDrawer').classList.add('translate-x-full');
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

        // Lista de ícones Font Awesome 6 Free mais populares
        const fontAwesomeIcons = [
            // Interface & Navegação
            'fa-house', 'fa-home', 'fa-bars', 'fa-xmark', 'fa-magnifying-glass', 'fa-search',
            'fa-gear', 'fa-cog', 'fa-sliders', 'fa-ellipsis', 'fa-ellipsis-vertical',
            
            // Usuário & Pessoas
            'fa-user', 'fa-users', 'fa-user-group', 'fa-user-tie', 'fa-user-gear',
            'fa-circle-user', 'fa-id-card', 'fa-address-card',
            
            // Comunicação
            'fa-envelope', 'fa-phone', 'fa-comment', 'fa-comments', 'fa-message',
            'fa-bell', 'fa-inbox', 'fa-paper-plane',
            
            // Arquivos & Documentos
            'fa-file', 'fa-folder', 'fa-folder-open', 'fa-file-pdf', 'fa-file-word',
            'fa-file-excel', 'fa-file-image', 'fa-clipboard', 'fa-copy',
            
            // Ações
            'fa-plus', 'fa-minus', 'fa-pen', 'fa-pencil', 'fa-trash', 'fa-check',
            'fa-xmark', 'fa-download', 'fa-upload', 'fa-share', 'fa-save',
            
            // Setas & Direções
            'fa-arrow-right', 'fa-arrow-left', 'fa-arrow-up', 'fa-arrow-down',
            'fa-chevron-right', 'fa-chevron-left', 'fa-chevron-up', 'fa-chevron-down',
            'fa-angles-right', 'fa-angles-left',
            
            // Negócios & Finanças
            'fa-chart-line', 'fa-chart-bar', 'fa-chart-pie', 'fa-dollar-sign',
            'fa-credit-card', 'fa-wallet', 'fa-briefcase', 'fa-building',
            
            // Compras & E-commerce
            'fa-cart-shopping', 'fa-bag-shopping', 'fa-store', 'fa-tag', 'fa-tags',
            'fa-barcode', 'fa-receipt',
            
            // Mídia & Conteúdo
            'fa-image', 'fa-video', 'fa-camera', 'fa-play', 'fa-pause', 'fa-stop',
            'fa-music', 'fa-film', 'fa-photo-film',
            
            // Tecnologia
            'fa-laptop', 'fa-mobile', 'fa-tablet', 'fa-desktop', 'fa-keyboard',
            'fa-database', 'fa-server', 'fa-wifi', 'fa-cloud',
            
            // Localização & Mapas
            'fa-location-dot', 'fa-map', 'fa-map-pin', 'fa-globe', 'fa-compass',
            
            // Tempo & Calendário
            'fa-clock', 'fa-calendar', 'fa-calendar-days', 'fa-hourglass',
            
            // Status & Indicadores
            'fa-circle-check', 'fa-circle-xmark', 'fa-circle-exclamation',
            'fa-triangle-exclamation', 'fa-circle-info', 'fa-star', 'fa-heart',
            
            // Segurança
            'fa-lock', 'fa-unlock', 'fa-key', 'fa-shield', 'fa-eye', 'fa-eye-slash',
            
            // Social
            'fa-share-nodes', 'fa-thumbs-up', 'fa-thumbs-down', 'fa-bookmark',
            
            // Outros
            'fa-box', 'fa-gift', 'fa-trophy', 'fa-flag', 'fa-lightbulb',
            'fa-bolt', 'fa-fire', 'fa-rocket', 'fa-crown', 'fa-diamond'
        ];

        // Renderizar ícones no grid
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

        // Selecionar ícone
        function selectIcon(icon) {
            document.getElementById('icon').value = icon;
            document.getElementById('icon_preview').className = `fas ${icon} text-2xl text-gray-700`;
            closeIconPicker();
        }

        // Abrir modal
        function openIconPicker() {
            document.getElementById('iconPickerModal').classList.remove('hidden');
            renderIcons();
        }

        // Fechar modal
        function closeIconPicker() {
            document.getElementById('iconPickerModal').classList.add('hidden');
            document.getElementById('iconSearch').value = '';
            renderIcons();
        }

        // Filtrar ícones
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

        // Atualizar preview quando o campo é alterado manualmente
        document.getElementById('icon').addEventListener('input', function(e) {
            const iconClass = e.target.value;
            if (iconClass) {
                document.getElementById('icon_preview').className = `fas ${iconClass} text-2xl text-gray-700`;
            } else {
                document.getElementById('icon_preview').className = 'fas fa-question text-2xl text-gray-400';
            }
        });

        // Inicializar preview se houver valor antigo
        window.addEventListener('DOMContentLoaded', function() {
            const iconValue = document.getElementById('icon').value;
            if (iconValue) {
                document.getElementById('icon_preview').className = `fas ${iconValue} text-2xl text-gray-700`;
            }
        });

        // ===== EDIT DRAWER FUNCTIONS =====
        
        // Abrir drawer de edição e preencher com dados do domínio
        function openEditDrawer(domain) {
            // Preencher campos do formulário
            document.getElementById('edit_domain').value = domain.domain || '';
            document.getElementById('edit_navigatio_opc').value = domain.navigatio_opc || '';
            document.getElementById('edit_navigation_subopc').value = domain.navigation_subopc || '';
            document.getElementById('edit_icon').value = domain.icon || '';
            
            // Atualizar preview do ícone
            if (domain.icon) {
                document.getElementById('edit_icon_preview').className = `fas ${domain.icon} text-2xl text-gray-700`;
            } else {
                document.getElementById('edit_icon_preview').className = 'fas fa-question text-2xl text-gray-400';
            }
            
            // Preencher cores
            const fontCor = domain.font_cor || '#000000';
            const bgCor = domain.bg_cor || '#FFFFFF';
            document.getElementById('edit_font_cor').value = fontCor;
            document.getElementById('edit_font_cor_text').value = fontCor.toUpperCase();
            document.getElementById('edit_bg_cor').value = bgCor;
            document.getElementById('edit_bg_cor_text').value = bgCor.toUpperCase();
            
            // Marcar checkboxes
            document.getElementById('edit_datasource').checked = domain.datasource == 1;
            document.getElementById('edit_ativo').checked = domain.ativo == 1;
            
            // Configurar action do formulário
            const form = document.getElementById('editDomainForm');
            form.action = `/helium/domain/${domain.id}`;
            
            // Abrir drawer
            document.getElementById('domainEditDrawer').classList.remove('translate-x-full');
            document.getElementById('editDrawerOverlay').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        // Fechar drawer de edição
        function closeEditDrawer() {
            document.getElementById('domainEditDrawer').classList.add('translate-x-full');
            document.getElementById('editDrawerOverlay').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        // Sincronizar color pickers do edit drawer
        document.getElementById('edit_font_cor').addEventListener('input', function(e) {
            document.getElementById('edit_font_cor_text').value = e.target.value.toUpperCase();
        });
        
        document.getElementById('edit_bg_cor').addEventListener('input', function(e) {
            document.getElementById('edit_bg_cor_text').value = e.target.value.toUpperCase();
        });

        // Icon picker para edit drawer
        let editMode = false;

        function openEditIconPicker() {
            editMode = true;
            document.getElementById('iconPickerModal').classList.remove('hidden');
            renderIcons();
        }

        // Modificar selectIcon para suportar edit mode
        const originalSelectIcon = selectIcon;
        selectIcon = function(icon) {
            if (editMode) {
                document.getElementById('edit_icon').value = icon;
                document.getElementById('edit_icon_preview').className = `fas ${icon} text-2xl text-gray-700`;
                editMode = false;
            } else {
                document.getElementById('icon').value = icon;
                document.getElementById('icon_preview').className = `fas ${icon} text-2xl text-gray-700`;
            }
            closeIconPicker();
        };

        // Atualizar preview do ícone de edição quando alterado manualmente
        document.getElementById('edit_icon').addEventListener('input', function(e) {
            const iconClass = e.target.value;
            if (iconClass) {
                document.getElementById('edit_icon_preview').className = `fas ${iconClass} text-2xl text-gray-700`;
            } else {
                document.getElementById('edit_icon_preview').className = 'fas fa-question text-2xl text-gray-400';
            }
        });
    </script>
@endsection
