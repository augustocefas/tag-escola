<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', 'Laravel'))</title>

    <!-- Tailwind CSS via CDN (ou substitua pelo seu build) -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Font Awesome 6 Free -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Opcional: configuração customizada do Tailwind -->
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#3B82F6',
                    }
                }
            }
        }
    </script>

    @stack('styles')
</head>
<body class="bg-gray-50 text-gray-900 antialiased">
<!-- Container Principal com Sidebar -->
<div class="min-h-screen flex">
    <!-- Sidebar à esquerda -->
    <aside class="bg-white shadow-sm border-r w-64 flex-shrink-0">
        <!-- Logo -->
        <div class="p-2 border-b flex items-center justify-start">
            <img src="{{ asset('images/logo.jpeg') }}" class="w-10" alt="Logo"> 
            <div class="text-stroke text-3xl bg-gradient-to-r from-sky-300 via-indigo-500 to-purple-800 bg-clip-text text-transparent font-bold ml-2">Helium</div>
        </div>

        <div class="py-2 px-6 border-b">
            @@@@
        </div>

        <!-- Navegação -->
        <nav class="p-4 space-y-2">

                <a href="{{ route('helium.dashboard') }}"
                   class="flex items-center p-3 text-gray-600 hover:bg-gray-100 hover:text-primary rounded-lg transition">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    Dashboard
                </a>

                <a href="{{ route('helium.tenant.index') }}"
                   class="flex items-center p-3 text-gray-600 hover:bg-gray-100 hover:text-primary rounded-lg transition">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2"/>
                    </svg>
                    Tenants
                </a>

            <a href="#"
               class="flex items-center p-3 text-gray-600 hover:bg-gray-100 hover:text-primary rounded-lg transition">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Financeiros
            </a>

            <a href="{{ route('helium.domain.index') }}"
               class="flex items-center p-3 text-gray-600 hover:bg-gray-100 hover:text-primary rounded-lg transition">
                <svg class="w-5 h-5 mr-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h14a2 2 0 012 2v14a2 2 0 01-2 2H5a2 2 0 01-2-2V5zm4 0v14m8-14v14M3 9h18M3 15h18"/>
                </svg>
                Dominios
            </a>

            <!-- Menu com Submenu: Localização -->
            <div class="menu-item">
                <button onclick="toggleSubmenu('localizacao')" 
                        class="flex items-center justify-between w-full p-3 text-gray-600 hover:bg-gray-100 hover:text-primary rounded-lg transition">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Localização
                    </div>
                    <svg id="localizacao-icon" class="w-4 h-4 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                
                <!-- Submenu -->
                <div id="localizacao-submenu" class="hidden ml-8 mt-1 space-y-1">
                    <a href="{{ route('helium.pais.index') }}"
                       class="flex items-center p-2 text-sm text-gray-600 hover:bg-gray-100 hover:text-primary rounded-lg transition">
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <circle cx="10" cy="10" r="2"/>
                        </svg>
                        País
                    </a>
                    <a href="{{ route('helium.estado.index') }}"
                       class="flex items-center p-2 text-sm text-gray-600 hover:bg-gray-100 hover:text-primary rounded-lg transition">
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <circle cx="10" cy="10" r="2"/>
                        </svg>
                        Estado
                    </a>
                    <a href="{{ route('helium.cidade.index') }}"
                       class="flex items-center p-2 text-sm text-gray-600 hover:bg-gray-100 hover:text-primary rounded-lg transition">
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <circle cx="10" cy="10" r="2"/>
                        </svg>
                        Cidade
                    </a>
                </div>
            </div>

                <a href="/users"
                   class="flex items-center p-3 text-gray-600 hover:bg-gray-100 hover:text-primary rounded-lg transition">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.67 3.357a9 9 0 01-13.67 0"/>
                    </svg>
                    Usuários
                </a>


        </nav>

        <!-- Rodapé da Sidebar (menu de usuário) -->
        @auth
            <div class="absolute bottom-0 w-64 p-4 border-t bg-white">
                <div class="flex items-center justify-between">
                    <span class="text-gray-600 text-sm">{{ Auth::user()->name ?? 'Usuário' }}</span>
                    <form method="POST" action="{{ route('helium.auth.logout') }}">
                        @csrf
                        <button type="submit"
                                class="p-2 text-gray-600 hover:bg-gray-100 hover:text-primary rounded-lg transition"
                                title="Sair">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        @endauth
    </aside>

    <!-- Conteúdo Principal -->
    <div class="flex-1 flex flex-col min-h-screen">
        <!-- Header simplificado (apenas para mobile/estados não autenticados) -->
        <header class="bg-white shadow-sm border-b md:hidden">
            <div class="px-4 py-4">
                <div class="flex justify-between items-center">
                    <!-- Botão para mostrar/esconder sidebar em mobile -->
                    <button id="sidebar-toggle" class="text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>

                    <!-- Links de autenticação para mobile (quando não logado) -->
                    @guest
                        <div class="flex space-x-4">
                            <a href="{{ route('helium.auth.login') }}" class="text-gray-600 hover:text-primary transition">
                                Entrar
                            </a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="text-gray-600 hover:text-primary transition">
                                    Registrar
                                </a>
                            @endif
                        </div>
                    @endguest
                </div>
            </div>
        </header>

        <!-- Conteúdo -->
        <main class="flex-grow p-6">
            @yield('content')
        </main>

        <!-- Footer -->
        <footer class="bg-white border-t py-6">
            <div class="container mx-auto px-4 text-center text-gray-500 text-sm">
                <p>&copy; {{ date('Y') }} {{ config('app.name', 'App') }}. Todos os direitos reservados.</p>
            </div>
        </footer>
    </div>
</div>

<!-- Script para toggle da sidebar em mobile -->
<script>
    // Função para alternar submenu
    function toggleSubmenu(menuId) {
        const submenu = document.getElementById(menuId + '-submenu');
        const icon = document.getElementById(menuId + '-icon');
        
        if (submenu.classList.contains('hidden')) {
            // Expandir submenu
            submenu.classList.remove('hidden');
            icon.style.transform = 'rotate(180deg)';
        } else {
            // Colapsar submenu
            submenu.classList.add('hidden');
            icon.style.transform = 'rotate(0deg)';
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        const sidebarToggle = document.getElementById('sidebar-toggle');
        const sidebar = document.querySelector('aside');

        if (sidebarToggle) {
            sidebarToggle.addEventListener('click', function() {
                sidebar.classList.toggle('hidden');
                sidebar.classList.toggle('block');
                sidebar.classList.toggle('absolute');
                sidebar.classList.toggle('z-50');
            });
        }

        // Fechar sidebar ao clicar fora (apenas mobile)
        document.addEventListener('click', function(event) {
            if (window.innerWidth < 768 &&
                sidebar &&
                !sidebar.contains(event.target) &&
                event.target !== sidebarToggle &&
                !sidebarToggle.contains(event.target)) {
                sidebar.classList.add('hidden');
                sidebar.classList.remove('block');
            }
        });

        // Auto-expandir submenu se um item filho estiver ativo
        const currentPath = window.location.pathname;
        const submenus = document.querySelectorAll('[id$="-submenu"]');
        
        submenus.forEach(submenu => {
            const links = submenu.querySelectorAll('a');
            links.forEach(link => {
                if (link.getAttribute('href') === currentPath) {
                    const menuId = submenu.id.replace('-submenu', '');
                    toggleSubmenu(menuId);
                    link.classList.add('bg-blue-50', 'text-primary', 'font-medium');
                }
            });
        });
    });
</script>

<!-- Scripts -->
@stack('scripts')
</body>
</html>
