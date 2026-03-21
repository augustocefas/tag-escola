@extends('helium.layout.app')
@section('title', 'Adicionar Domínio')
@section('content')

    <div class="container mx-auto p-4">
        <div class="max-w-2xl mx-auto">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-semibold">Adicionar Novo Domínio</h1>
                <a href="{{ route('helium.domain.index') }}" class="text-blue-600 hover:underline">
                    ← Voltar para lista
                </a>
            </div>

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

            <form action="{{ route('helium.domain.store') }}" method="POST" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
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
                        Navegação (Opção) 
                    </label>
                    <input 
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('navigatio_opc') border-red-500 @enderror" 
                        id="navigatio_opc" 
                        name="navigatio_opc" 
                        type="text" 
                        placeholder="menu_principal"
                        value="{{ old('navigatio_opc') }}"
                        
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

                <!-- Modal do Icon Picker -->
                <div id="iconPickerModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
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
                </script>

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

                <script>
                    // Sincronizar o color picker com o campo de texto
                    document.getElementById('font_cor').addEventListener('input', function(e) {
                        document.getElementById('font_cor_text').value = e.target.value.toUpperCase();
                    });
                    
                    document.getElementById('bg_cor').addEventListener('input', function(e) {
                        document.getElementById('bg_cor_text').value = e.target.value.toUpperCase();
                    });
                </script>

                <div class="mb-4">
                    <label class="flex items-center">
                        <input 
                            type="checkbox" 
                            name="datasource" 
                            value="1"
                            {{ old('datasource', true) ? 'checked' : '' }}
                            class="form-checkbox h-5 w-5 text-blue-600"
                        >
                        <span class="ml-2 text-gray-700 text-sm font-bold">Datasource Ativo</span>
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
                        <span class="ml-2 text-gray-700 text-sm font-bold">Domínio Ativo</span>
                    </label>
                </div>

                <div class="flex items-center justify-between">
                    <button 
                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" 
                        type="submit"
                    >
                        Criar Domínio
                    </button>
                    <a 
                        href="{{ route('helium.domain.index') }}" 
                        class="inline-block align-baseline font-bold text-sm text-gray-500 hover:text-gray-800"
                    >
                        Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
