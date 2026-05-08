<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

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
            0% { background-color: rgba(34, 197, 94, 0.2); }
            100% { background-color: transparent; }
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
            0% { transform: translateY(100%); opacity: 0; }
            100% { transform: translateY(0); opacity: 1; }
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

        .toast-container > div {
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
                $navItems = [
                    ['name' => 'Dashboard', 'icon' => 'layout-grid', 'route' => route('dashboard'), 'active' => request()->routeIs('dashboard'), 'hasDropdown' => false],
                    ['name' => 'Facturación', 'icon' => 'file-text', 'route' => route('facturacion.index'), 'active' => request()->routeIs('facturacion.*'), 'hasDropdown' => false],
                ];

                if (Auth::check() && in_array(Auth::user()->rol, ['Administrador', 'admin'])) {
                    $navItems[] = ['name' => 'Usuarios', 'icon' => 'users', 'route' => route('users.index'), 'active' => request()->routeIs('users.*'), 'hasDropdown' => false];
                }

                $navItems = array_merge($navItems, [
                    ['name' => 'Pacientes', 'icon' => 'user-plus', 'route' => route('patients.create'), 'active' => request()->routeIs('patients.*'), 'hasDropdown' => false],
                    ['name' => 'Convenios', 'icon' => 'handshake', 'route' => route('convens.index'), 'active' => request()->routeIs('convens.*'), 'hasDropdown' => false],
                    ['name' => 'Exámenes', 'icon' => 'test-tube', 'route' => route('exams.index'), 'active' => request()->routeIs('exams.*'), 'hasDropdown' => false],
                ]);
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
                    @if($item['hasDropdown'])
                        <i data-lucide="chevron-down" class="w-4 h-4 {{ $item['active'] ? 'text-blue-100' : 'text-slate-400' }}"></i>
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
                        <button type="submit" class="flex items-center justify-center px-4 py-2 bg-red-50 text-red-600 hover:bg-red-600 hover:text-white rounded-lg font-medium transition-all shadow-sm active:scale-95" title="Cerrar Sesión">
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
            @php
                $toastIcon = ''; $toastTitle = ''; $toastMsg = '';
                $iconBg = '#fef2f2'; $iconColor = '#ef4444';

                if (session('success')) {
                    $toastIcon = 'check-circle'; $toastTitle = '¡Éxito!'; $toastMsg = session('success');
                    $iconBg = '#f0fdf4'; $iconColor = '#16a34a';
                } elseif (session('deleted')) {
                    $toastIcon = 'trash-2'; $toastTitle = 'Eliminado'; $toastMsg = session('deleted');
                } elseif (session('error')) {
                    $toastIcon = 'alert-circle'; $toastTitle = 'Error'; $toastMsg = session('error');
                } elseif ($errors->any()) {
                    $toastIcon = 'alert-circle'; $toastTitle = 'Error';
                    $toastMsg = implode('<br>', $errors->all());
                }
            @endphp

            @if($toastMsg)
                <div id="toast-message" style="background:white;border:1px solid #f1f5f9;border-radius:12px;box-shadow:0 8px 30px rgba(0,0,0,0.12);min-width:320px;padding:1rem 1.25rem;animation:toast-slide-up 0.3s cubic-bezier(0.16,1,0.3,1) forwards;">
                    <div style="display:grid;grid-template-columns:4px 1fr 24px;align-items:center;">
                        <div></div>
                        <div style="display:flex;align-items:center;justify-content:center;gap:10px;">
                            <div style="width:36px;height:36px;border-radius:50%;background:{{ $iconBg }};display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                <i data-lucide="{{ $toastIcon }}" style="width:16px;height:16px;color:{{ $iconColor }};"></i>
                            </div>
                            <span style="font-weight:700;font-size:15px;color:#1e293b;">{{ $toastTitle }}</span>
                        </div>
                        <button onclick="document.getElementById('toast-message').remove()" style="background:none;border:none;cursor:pointer;color:#94a3b8;padding:0;display:flex;align-items:center;justify-content:center;" onmouseover="this.style.color='#475569'" onmouseout="this.style.color='#94a3b8'">
                            <i data-lucide="x" style="width:16px;height:16px;"></i>
                        </button>
                    </div>
                    <p style="text-align:center;font-size:13.5px;color:#64748b;margin:0.3rem 0 0;line-height:1.4;">{!! $toastMsg !!}</p>
                </div>
                <script>setTimeout(() => document.getElementById('toast-message')?.remove(), {{ $errors->any() ? 6000 : 4000 }});</script>
            @endif
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