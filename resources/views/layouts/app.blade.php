<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>SIGAP - @yield('title', 'Dashboard')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

    <!-- Styles / Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @keyframes highlight-fade {
            0% {
                background-color: rgba(34, 197, 94, 0.2);
            }

            100% {
                background-color: transparent;
            }
        }

        .highlight-flash {
            animation: highlight-fade 3s ease-out forwards;
            border-left: 4px solid #22c55e !important;
        }

        .nav-item-hover:hover {
            box-shadow: 0 10px 25px -5px rgba(37, 99, 235, 0.4), 0 0 20px rgba(37, 99, 235, 0.15) !important;
            background-color: white !important;
        }

        @keyframes toast-slide-up {
            0% {
                transform: translateY(100%);
                opacity: 0;
            }

            100% {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .toast-container {
            position: fixed;
            bottom: 2rem;
            left: 50%;
            transform: translateX(-50%);
            z-index: 9999;
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
            pointer-events: none;
        }

        .toast-container>div {
            pointer-events: auto;
        }
    </style>
</head>

<body class="text-slate-800 font-sans antialiased flex h-screen overflow-hidden"
    style="background: linear-gradient(110deg, #f9fafb 55%, #e8f0fe 55%);">

    <!-- Sidebar -->
    <aside class="w-64 border-r border-gray-100 flex flex-col items-center py-6 shrink-0 z-20"
        style="background: linear-gradient(180deg, #ffffff 0%, #f8fafc 100%);">
        <!-- Logo -->
        <div class="mb-10 w-full px-8 flex items-center space-x-3">
            <div class="w-10 h-10 rounded-full bg-blue-600/10 flex items-center justify-center">
                <i data-lucide="activity" class="w-6 h-6 text-blue-600"></i>
            </div>
            <h1 class="text-2xl font-black text-blue-600 tracking-tighter">SIGAP</h1>
        </div>

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
                    class="flex items-center justify-between px-4 py-3 rounded-xl transition-all duration-200 group nav-item-hover {{ $item['active'] ? 'text-white shadow-lg shadow-blue-500/30' : 'text-slate-500 hover:bg-white hover:text-blue-600 hover:-translate-y-0.5' }}"
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

    <!-- Main Content -->
    <main class="flex-1 flex flex-col h-screen relative items-center">
        <!-- Header wrapper -->
        <header
            class="w-full max-w-[1400px] h-16 bg-white md:bg-transparent border-b md:border-none border-gray-100 flex items-center justify-between px-8 shrink-0 relative top-0 z-10">
            <div class="flex items-center space-x-4">
                @hasSection('header_left')
                    @yield('header_left')
                @endif
            </div>

            <div class="flex items-center space-x-4">
                @auth
                    <div class="text-right flex flex-col justify-center mr-2">
                        <span class="text-sm font-semibold text-slate-800 leading-tight">{{ Auth::user()->name }}
                            {{ Auth::user()->apellido }}</span>
                        <span class="text-xs text-slate-500 leading-tight capitalize">{{ Auth::user()->rol }}</span>
                    </div>
                    <form method="POST" action="{{ route('logout') }}" class="m-0 p-0 flex items-center">
                        @csrf
                        <button type="submit" class="flex items-center justify-center px-4 py-2 bg-red-50 text-red-600 hover:bg-red-600 hover:text-white rounded-lg font-medium transition-all shadow-sm active:scale-95 cursor-pointer" title="Cerrar Sesión">
                            <i data-lucide="log-out" class="w-4 h-4 mr-2"></i>
                            <span class="text-sm">Salir</span>
                        </button>
                    </form>
                @endauth
            </div>
        </header>

        @hasSection('search_form')
            <div class="w-full max-w-[1400px] px-8 py-4">
                @yield('search_form')
            </div>
        @endif

        <div class="toast-container">
            <x-toast />
        </div>

        <div class="w-full max-w-[1400px] flex-1 overflow-y-auto px-8 pb-8 pt-0">
            @yield('content')
        </div>
    </main>

    @stack('modals')

    <!-- Iconos -->

    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
        lucide.createIcons();

        // Highlight logic
        document.addEventListener('DOMContentLoaded', () => {
            const urlParams = new URLSearchParams(window.location.search);
            const highlightId = urlParams.get('highlight');
            console.log('Highlighting ID:', highlightId);
            if (highlightId) {
                const element = document.getElementById('item-' + highlightId);
                if (element) {
                    console.log('Found element:', element);
                    element.classList.add('highlight-flash');
                    setTimeout(() => {
                        element.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    }, 500);
                }
            }
        });
    </script>
</body>

</html>