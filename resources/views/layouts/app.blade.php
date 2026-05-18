<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>SIGAP - @yield('title', 'Dashboard')</title>

    <!-- Fuentes -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

    <!-- Estilos / Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script>
        if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark')
        }
    </script>
    <style>
        :root {
            --app-bg-1: #f9fafb;
            --app-bg-2: #e8f0fe;
            --sidebar-bg-1: #ffffff;
            --sidebar-bg-2: #f8fafc;
        }

        .dark, :is(.dark) {
            --app-bg-1: #0f172a;
            --app-bg-2: #1e293b;
            --sidebar-bg-1: #1e293b;
            --sidebar-bg-2: #0f172a;
        }

        .app-gradient {
            background: linear-gradient(110deg, var(--app-bg-1) 55%, var(--app-bg-2) 55%) !important;
        }

        .sidebar-gradient {
            background: linear-gradient(180deg, var(--sidebar-bg-1) 0%, var(--sidebar-bg-2) 100%) !important;
        }

        @keyframes highlight-fade {
            0%   { background-color: rgba(34, 197, 94, 0.4); }
            50%  { background-color: rgba(34, 197, 94, 0.2); }
            100% { background-color: transparent; }
        }
        .highlight-flash {
            animation: highlight-fade 4s ease-in-out forwards;
            border-left: 5px solid #16a34a !important;
            outline: 2px solid rgba(22, 163, 74, 0.3);
        }
        .nav-item-hover:hover {
            box-shadow: 0 10px 25px -5px rgba(37, 99, 235, 0.4), 0 0 20px rgba(37, 99, 235, 0.15) !important;
            background-color: white !important;
        }
        html.dark .nav-item-hover:hover {
            background-color: rgba(30, 41, 59, 0.5) !important;
            box-shadow: 0 10px 25px -5px rgba(37, 99, 235, 0.2), 0 0 20px rgba(37, 99, 235, 0.1) !important;
        }
        @keyframes toast-slide-up-center {
            0%   { transform: translate(-50%, 100%); opacity: 0; }
            100% { transform: translate(-50%, 0); opacity: 1; }
        }
        .toast-fixed-center {
            position: fixed;
            bottom: 2rem;
            left: 50%;
            z-index: 9999;
            animation: toast-slide-up-center 0.3s cubic-bezier(0.16,1,0.3,1) forwards;
        }


        /* Modo oscuro: fondo nativo de <dialog> */
        :where(html.dark) dialog {
            background: #0f172a;
            color: #e2e8f0;
            border: 1px solid #1e293b;
        }

        /* Modo oscuro: widget de selección de fecha / datetime-local */
        :where(html.dark) input[type="date"],
        :where(html.dark) input[type="datetime-local"],
        :where(html.dark) input[type="time"] {
            color-scheme: dark;
        }

        /* Modo oscuro: sobreescribir fondo blanco de autocompletado del navegador */
        :where(html.dark) input:-webkit-autofill,
        :where(html.dark) input:-webkit-autofill:hover,
        :where(html.dark) input:-webkit-autofill:focus,
        :where(html.dark) input:-webkit-autofill:active {
            -webkit-box-shadow: 0 0 0 1000px #1e293b inset !important;
            -webkit-text-fill-color: #e2e8f0 !important;
            caret-color: #e2e8f0;
        }

        /* Modo oscuro: fondo de opciones select */
        :where(html.dark) select option {
            background-color: #1e293b;
            color: #e2e8f0;
        }
        :where(html.dark) select option:checked {
            background-color: #1d4ed8;
            color: #ffffff;
        }

        /* Modo oscuro: barra de desplazamiento para selects */
        :where(html.dark) select::-webkit-scrollbar { width: 6px; }
        :where(html.dark) select::-webkit-scrollbar-track { background: #0f172a; }
        :where(html.dark) select::-webkit-scrollbar-thumb { background: #475569; border-radius: 3px; }

        /* Modo oscuro: barra de desplazamiento global */
        :where(html.dark) ::-webkit-scrollbar { 
            width: 10px; 
            height: 10px; 
        }
        :where(html.dark) ::-webkit-scrollbar-track { 
            background: #0f172a; 
        }
        :where(html.dark) ::-webkit-scrollbar-thumb { 
            background: #334155; 
            border: 2px solid #0f172a; 
            border-radius: 10px; 
        }
        :where(html.dark) ::-webkit-scrollbar-thumb:hover { 
            background: #475569; 
        }

        /* Solucionar tooltip de ApexCharts en modo oscuro */
        :where(html.dark) .apexcharts-tooltip {
            background: #1e293b !important;
            border-color: #334155 !important;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.5) !important;
            color: #f8fafc !important;
        }
        :where(html.dark) .apexcharts-tooltip-title {
            background: #0f172a !important;
            border-bottom: 1px solid #334155 !important;
            color: #f8fafc !important;
        }
        :where(html.dark) .apexcharts-tooltip-text {
            color: #f8fafc !important;
        }
        :where(html.dark) .apexcharts-tooltip.apexcharts-theme-light {
            background: #1e293b !important;
            color: #f8fafc !important;
        }
    </style>
</head>

<body class="text-slate-800 dark:text-slate-200 font-sans antialiased flex h-screen overflow-hidden app-gradient">

    <!-- Superposición Móvil -->
    <div id="mobile-overlay" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-30 hidden lg:hidden transition-opacity" onclick="toggleSidebar()"></div>

    <!-- Barra Lateral -->
    <aside id="sidebar" class="fixed lg:static inset-y-0 left-0 w-64 border-r border-gray-100 dark:border-slate-800 flex flex-col items-center py-6 shrink-0 z-40 sidebar-gradient transform -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out shadow-2xl lg:shadow-none">
        <!-- Logotipo -->
        <a href="{{ route('dashboard') }}" class="mb-10 w-full px-8 flex items-center space-x-3 hover:opacity-80 transition-opacity cursor-pointer">
            <div class="w-10 h-10 rounded-full bg-blue-600/10 flex items-center justify-center">
                <i data-lucide="activity" class="w-6 h-6 text-blue-600"></i>
            </div>
            <span class="text-2xl font-black text-blue-600 tracking-tighter">SIGAP</span>
        </a>

        <!-- Navegacion -->
        <nav class="w-full px-4 flex flex-col space-y-1">
            @php
                $rol = strtolower(Auth::user()->rol ?? '');
                $isBioanalista = $rol === 'bioanalista';
                $isAdmin = in_array($rol, ['admin', 'administrador', 'administrador principal']);
                $isRecepcionista = in_array($rol, ['recepcionista', 'recepcion']);

                $navItems = [
                    ['name' => 'Inicio', 'icon' => 'layout-grid', 'route' => route('dashboard'), 'active' => request()->routeIs('dashboard')],
                ];

                // Facturación: visible para todos EXCEPTO bioanalista
                if (!$isBioanalista) {
                    $navItems[] = ['name' => 'Facturación', 'icon' => 'file-text', 'route' => route('facturacion.index'), 'active' => request()->routeIs('facturacion.*')];
                }

                // Usuarios: solo para admin
                if ($isAdmin) {
                    $navItems[] = ['name' => 'Usuarios', 'icon' => 'users', 'route' => route('users.index'), 'active' => request()->routeIs('users.*')];
                }

                // Pacientes: NO visible para bioanalista
                if (!$isBioanalista) {
                    $navItems[] = ['name' => 'Pacientes', 'icon' => 'user-plus', 'route' => route('patients.create'), 'active' => request()->routeIs('patients.*')];
                }

                // Convenios: visible para todos excepto bioanalista
                if (!$isBioanalista) {
                    $navItems[] = ['name' => 'Convenios', 'icon' => 'handshake', 'route' => route('convens.index'), 'active' => request()->routeIs('convens.*')];
                }

                // Exámenes: visible para TODOS excepto Bioanalista (solo tiene Encargos)
                if (!$isBioanalista) {
                    $navItems[] = ['name' => 'Exámenes', 'icon' => 'test-tube', 'route' => route('exams.index'), 'active' => request()->routeIs('exams.*')];
                }

                // Encargos Pendientes: solo para bioanalista, admin y recepcionista
                if ($isBioanalista || $isAdmin || $isRecepcionista) {
                    $navItems[] = ['name' => 'Encargos', 'icon' => 'clipboard-list', 'route' => route('encargos.index'), 'active' => request()->routeIs('encargos.*')];
                }

                // Resultados Examen: para bioanalista, admin y recepcionista
                if ($isBioanalista || $isAdmin || $isRecepcionista) {
                    $navItems[] = ['name' => 'Resultados', 'icon' => 'microscope', 'route' => route('resulexamen.index'), 'active' => request()->routeIs('resulexamen.*')];
                }
            @endphp

            @foreach($navItems as $item)
                <a href="{{ $item['route'] }}"
                    class="flex items-center justify-between px-4 py-3 rounded-xl transition-all duration-200 group nav-item-hover {{ $item['active'] ? 'text-white shadow-lg shadow-blue-500/30' : 'text-slate-500 dark:text-slate-400 hover:bg-white dark:hover:bg-slate-800 hover:text-blue-600 dark:hover:text-blue-400 hover:-translate-y-0.5' }}"
                    style="{{ $item['active'] ? 'background: linear-gradient(110deg, #2563eb 55%, #1d4ed8 55%);' : '' }}">
                    <div class="flex items-center space-x-3">
                        <i data-lucide="{{ $item['icon'] }}"
                            class="w-5 h-5 {{ $item['active'] ? 'text-white' : 'text-slate-400 group-hover:text-blue-600' }}"></i>
                        <span class="font-bold text-sm tracking-tight">{{ $item['name'] }}</span>
                    </div>
                    @if(!empty($item['hasDropdown']))
                        <i data-lucide="chevron-down"
                            class="w-4 h-4 {{ $item['active'] ? 'text-blue-100' : 'text-slate-400' }}"></i>
                    @endif
                </a>
            @endforeach
        </nav>
    </aside>

    <!-- Contenido Principal -->
    <main class="flex-1 flex flex-col h-screen relative w-full overflow-x-hidden">
        <!-- Contenedor del Encabezado -->
        <header
            class="w-full max-w-[1400px] mx-auto mt-4 mb-2 flex items-center justify-between px-4 md:px-8 shrink-0 relative top-0 z-10">
            <div class="flex items-center space-x-2 md:space-x-4">
                <!-- Menú Hamburguesa (Solo Móvil) -->
                <button type="button" onclick="toggleSidebar()" class="lg:hidden text-slate-500 dark:text-slate-400 bg-white/80 dark:bg-slate-900/80 backdrop-blur-md shadow-sm border border-gray-200/50 dark:border-slate-800/50 hover:bg-gray-100 dark:hover:bg-slate-800 rounded-xl p-2.5 transition-all active:scale-95 cursor-pointer flex items-center justify-center">
                    <i data-lucide="menu" class="w-5 h-5"></i>
                </button>

                @hasSection('header_left')
                    @yield('header_left')
                @endif
            </div>

            <!-- Píldora Flotante del Perfil de Usuario -->
            <div class="flex items-center space-x-4 bg-white/80 dark:bg-slate-900/80 backdrop-blur-md rounded-2xl p-2 px-4 shadow-sm border border-gray-200/50 dark:border-slate-800/50">
                <button id="theme-toggle" type="button" title="Cambiar tema"
                    class="text-slate-500 dark:text-slate-400 hover:bg-gray-100 dark:hover:bg-slate-800 rounded-xl p-2.5 transition-all active:scale-95 cursor-pointer flex items-center justify-center">
                    {{-- Icono de luna (mostrado en modo claro) --}}
                    <svg id="theme-toggle-dark-icon" xmlns="http://www.w3.org/2000/svg" class="w-4.5 h-4.5 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"/>
                    </svg>
                    {{-- Icono de sol (mostrado en modo oscuro) --}}
                    <svg id="theme-toggle-light-icon" xmlns="http://www.w3.org/2000/svg" class="w-4.5 h-4.5 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="5"/>
                        <line x1="12" y1="1" x2="12" y2="3"/><line x1="12" y1="21" x2="12" y2="23"/>
                        <line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/>
                        <line x1="1" y1="12" x2="3" y2="12"/><line x1="21" y1="12" x2="23" y2="12"/>
                        <line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/>
                    </svg>
                </button>

                @auth
                    <div class="h-8 w-px bg-gray-200 dark:bg-slate-700 hidden md:block"></div>

                    <div class="text-right flex flex-col justify-center mx-2 hidden sm:flex">
                        <span class="text-sm font-bold text-slate-800 dark:text-slate-200 leading-tight">{{ Auth::user()->name }}
                            {{ Auth::user()->apellido }}</span>
                        <span class="text-[11px] font-medium text-blue-600 dark:text-blue-400 leading-tight tracking-wide uppercase">{{ Auth::user()->rol }}</span>
                    </div>

                    <form method="POST" action="{{ route('logout') }}" class="m-0 p-0 flex items-center">
                        @csrf
                        <button type="submit" class="flex items-center justify-center px-3 sm:px-4 py-2 bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 hover:bg-red-600 hover:text-white dark:hover:bg-red-600 dark:hover:text-white rounded-xl font-bold transition-all shadow-sm hover:shadow-md hover:shadow-red-500/20 active:scale-95 cursor-pointer group" title="Cerrar Sesión">
                            <i data-lucide="log-out" class="w-4 h-4 sm:mr-2 group-hover:translate-x-1 transition-transform"></i>
                            <span class="text-sm hidden sm:inline">Salir</span>
                        </button>
                    </form>
                @endauth
            </div>
        </header>

        @hasSection('search_form')
            <div class="w-full max-w-[1400px] mx-auto px-4 md:px-8 py-4">
                @yield('search_form')
            </div>
        @endif

        <x-toast />

        <div class="w-full max-w-[1400px] mx-auto flex-1 overflow-y-auto px-4 md:px-8 pb-8 pt-4 md:pt-10">
            @yield('content')
        </div>
    </main>

    @stack('modals')

    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('mobile-overlay');
            
            sidebar.classList.toggle('-translate-x-full');
            overlay.classList.toggle('hidden');
        }

        // Funcionalidad de Modo Oscuro y Highlight
        (function() {
            function initTheme() {
                const themeToggleBtn = document.getElementById('theme-toggle');
                const darkIcon = document.getElementById('theme-toggle-dark-icon');
                const lightIcon = document.getElementById('theme-toggle-light-icon');
                
                if (typeof lucide !== 'undefined') {
                    lucide.createIcons();
                }

                if (!themeToggleBtn || !darkIcon || !lightIcon) return;

                // Estado inicial de los iconos
                if (document.documentElement.classList.contains('dark')) {
                    lightIcon.classList.remove('hidden');
                    darkIcon.classList.add('hidden');
                } else {
                    lightIcon.classList.add('hidden');
                    darkIcon.classList.remove('hidden');
                }

                themeToggleBtn.onclick = function() {
                    // Cambiar clase dark en html
                    const isDark = document.documentElement.classList.toggle('dark');
                    
                    // Intercambiar iconos
                    darkIcon.classList.toggle('hidden');
                    lightIcon.classList.toggle('hidden');

                    // Guardar preferencia
                    localStorage.setItem('color-theme', isDark ? 'dark' : 'light');
                };
            }

            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', initTheme);
            } else {
                initTheme();
            }

            // Lógica de resaltado (Highlight)
            window.addEventListener('DOMContentLoaded', () => {
                initLiveSearch();
            });

            window.addEventListener('load', () => {
                const urlParams = new URLSearchParams(window.location.search);
                const highlightId = urlParams.get('highlight');
                if (highlightId) {
                    const element = document.getElementById('item-' + highlightId);
                    if (element) {
                        element.classList.add('highlight-flash');
                        setTimeout(() => {
                            element.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        }, 500);
                    }
                }
            });

            window.showJsToast = function(title, message, type = 'error') {
                const oldToast = document.getElementById('toast-message');
                if(oldToast) oldToast.remove();

                const iconBgClass = type === 'error' ? 'bg-red-50 dark:bg-red-900/20' : 'bg-green-50 dark:bg-green-900/20';
                const iconColorClass = type === 'error' ? 'text-red-500 dark:text-red-400' : 'text-green-500 dark:text-green-400';
                const iconName = type === 'error' ? 'alert-circle' : 'check-circle';

                const toastHTML = `
                    <div id="toast-message" class="toast-fixed-center bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 rounded-xl shadow-2xl min-w-[320px] p-4">
                        <div class="grid grid-cols-[4px_1fr_24px] items-center">
                            <div></div>
                            <div class="flex items-center justify-center gap-2.5">
                                <div class="w-9 h-9 rounded-full flex items-center justify-center shrink-0 ${iconBgClass}">
                                    <i data-lucide="${iconName}" class="w-4 h-4 ${iconColorClass}"></i>
                                </div>
                                <span class="font-bold text-sm text-slate-800 dark:text-slate-100">${title}</span>
                            </div>
                            <button onclick="this.closest('#toast-message').remove()"
                                class="bg-none border-none cursor-pointer text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 p-0 flex items-center justify-center transition-colors">
                                <i data-lucide="x" class="w-4 h-4"></i>
                            </button>
                        </div>
                        <p class="text-center text-xs text-slate-500 dark:text-slate-400 mt-1.5 leading-relaxed">${message}</p>
                    </div>
                `;
                
                const container = document.createElement('div');
                container.innerHTML = toastHTML;
                document.body.appendChild(container.firstElementChild);
                if(typeof lucide !== 'undefined') lucide.createIcons();
                
                setTimeout(() => {
                    const t = document.getElementById('toast-message');
                    if(t) t.remove();
                }, 4000);
            };

            function initLiveSearch() {
                const searchInputs = document.querySelectorAll('input[name="search"]');
                searchInputs.forEach(input => {
                    let debounceTimeout = null;
                    const form = input.closest('form');
                    
                    if (form) {
                        form.addEventListener('submit', e => {
                            e.preventDefault();
                        });
                    }

                    input.addEventListener('input', function() {
                        clearTimeout(debounceTimeout);
                        const query = this.value.trim();
                        const url = new URL(window.location.href);
                        
                        if (query) {
                            url.searchParams.set('search', query);
                        } else {
                            url.searchParams.delete('search');
                        }
                        url.searchParams.delete('page');

                        debounceTimeout = setTimeout(() => {
                            const originalClasses = input.className;
                            input.style.opacity = '0.6';

                            fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                            .then(res => res.text())
                            .then(html => {
                                const parser = new DOMParser();
                                const doc = parser.parseFromString(html, 'text/html');
                                
                                const currentTableBody = document.querySelector('tbody');
                                const newTableBody = doc.querySelector('tbody');
                                
                                if (currentTableBody && newTableBody) {
                                    currentTableBody.innerHTML = newTableBody.innerHTML;
                                    if(typeof lucide !== 'undefined') lucide.createIcons();
                                    
                                    const rows = newTableBody.querySelectorAll('tr');
                                    const isEmpty = rows.length === 0 || 
                                                    (rows.length === 1 && rows[0].cells.length === 1 && rows[0].cells[0].hasAttribute('colspan'));
                                    
                                    if (isEmpty && query !== '') {
                                        window.showJsToast('Búsqueda sin resultados', `No se encontró ningún registro coincidente con '${query}'`, 'error');
                                    }
                                }
                                
                                window.history.replaceState({}, '', url);

                                // Actualizar contenedores de paginación si se encuentran
                                const currentPagination = document.querySelector('div.mt-auto') || document.querySelector('nav[role="navigation"]')?.parentElement;
                                const newPagination = doc.querySelector('div.mt-auto') || doc.querySelector('nav[role="navigation"]')?.parentElement;
                                
                                if (currentPagination && newPagination) {
                                    currentPagination.innerHTML = newPagination.innerHTML;
                                }
                            })
                            .catch(err => console.error('Live search error:', err))
                            .finally(() => {
                                input.style.opacity = '1';
                            });
                        }, 400);
                    });
                });
            }
        })();
    </script>
    
    @stack('scripts')
</body>

</html>