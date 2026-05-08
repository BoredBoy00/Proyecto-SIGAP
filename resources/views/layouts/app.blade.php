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
        </style>
    </head>
    <body class="text-slate-800 font-sans antialiased flex h-screen overflow-hidden" 
          style="background: 
                 linear-gradient(110deg, #f9fafb 55%, rgba(239, 246, 255, 0.9) 55%),
                 url('https://images.unsplash.com/photo-1581091226825-a6a2a5aee158?q=80&w=2070&auto=format&fit=crop') right center / cover no-repeat;">

        <!-- Sidebar -->
        <aside class="w-64 bg-white border-r border-gray-100 flex flex-col items-center py-6 shrink-0 z-20">
            <!-- Logo -->
            <div class="mb-10 w-full px-8 flex items-center space-x-3">
                <div class="w-10 h-10 rounded-full bg-blue-50 flex items-center justify-center">
                    <i data-lucide="activity" class="w-6 h-6 text-blue-600"></i>
                </div>
                <h1 class="text-2xl font-black text-blue-600 tracking-tighter">SIGAP</h1>
            </div>

            <!-- Navigation -->
            <nav class="w-full px-4 flex flex-col space-y-1">
                @php
                    $navItems = [
                        ['name' => 'Dashboard', 'icon' => 'layout-grid', 'route' => route('dashboard'), 'active' => request()->routeIs('dashboard'), 'hasDropdown' => false],
                        ['name' => 'Facturación', 'icon' => 'file-text', 'route' => '#', 'active' => false, 'hasDropdown' => true],
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
                        class="flex items-center justify-between px-4 py-3 rounded-xl transition-colors {{ $item['active'] ? 'bg-blue-50 text-blue-600 font-medium' : 'text-slate-500 hover:bg-gray-50 hover:text-slate-800' }}">
                        <div class="flex items-center space-x-3">
                            <i data-lucide="{{ $item['icon'] }}" class="w-5 h-5 {{ $item['active'] ? 'text-blue-600' : 'text-slate-400' }}"></i>
                            <span>{{ $item['name'] }}</span>
                        </div>
                        @if($item['hasDropdown'])
                            <i data-lucide="chevron-down" class="w-4 h-4 text-slate-400"></i>
                        @endif
                    </a>
                @endforeach
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 flex flex-col h-screen relative items-center">
            <!-- Header wrapper -->
            <header class="w-full max-w-[1400px] h-16 bg-white md:bg-transparent border-b md:border-none border-gray-100 flex items-center justify-between px-8 shrink-0 relative top-0 z-10">
                <div class="flex items-center space-x-4">
                    @hasSection('header_left')
                        @yield('header_left')
                    @endif
                </div>

                <div class="flex items-center space-x-4">
                    @auth
                        <div class="text-right flex flex-col justify-center">
                            <span class="text-sm font-semibold text-slate-800 leading-tight">{{ Auth::user()->name }} {{ Auth::user()->apellido }}</span>
                            <span class="text-xs text-slate-500 leading-tight capitalize">{{ Auth::user()->rol }}</span>
                        </div>
                        <div class="relative group">
                            <div class="w-10 h-10 rounded-full bg-blue-50 flex items-center justify-center text-blue-600 cursor-pointer">
                                <i data-lucide="user" class="w-5 h-5"></i>
                            </div>
                            <div class="absolute right-0 pt-2 w-48 hidden group-hover:block z-50">
                                <div class="bg-white rounded-xl shadow-lg py-2 border border-gray-100">
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 flex items-center transition-colors">
                                            <i data-lucide="log-out" class="w-4 h-4 mr-2"></i> Cerrar Sesión
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endauth
                </div>
            </header>

            @hasSection('search_form')
                <div class="w-full max-w-[1400px] px-8 py-4">
                    @yield('search_form')
                </div>
            @endif

            <div class="w-full max-w-[1400px] px-8">
                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                        <strong class="font-bold">¡Éxito! </strong>
                        <span class="block sm:inline">{{ session('success') }}</span>
                    </div>
                @endif
            </div>

            <div class="w-full max-w-[1400px] flex-1 overflow-y-auto px-8 pb-8 pt-0">
                @yield('content')
            </div>
        </main>

        @stack('modals')

        <!-- Lucide Icons -->
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
