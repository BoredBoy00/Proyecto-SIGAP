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
    </head>
    <body class="bg-gray-50 text-slate-800 font-sans antialiased flex h-screen overflow-hidden">

        <!-- Sidebar -->
        <aside class="w-64 bg-white border-r border-gray-100 flex flex-col items-center py-6 shrink-0 z-20">
            <!-- Logo -->
            <div class="mb-10 w-full px-8">
                <h1 class="text-2xl font-bold text-blue-600 tracking-wide">SIGAP</h1>
            </div>

            <!-- Navigation -->
            <nav class="w-full px-4 flex flex-col space-y-1">
                @php
                    $navItems = [
                        ['name' => 'Dashboard', 'icon' => 'layout-grid', 'route' => route('dashboard'), 'active' => request()->routeIs('dashboard'), 'hasDropdown' => false],
                        ['name' => 'Facturación', 'icon' => 'file-text', 'route' => '#', 'active' => false, 'hasDropdown' => true],
                    ];

                    if (Auth::check() && in_array(strtolower(Auth::user()->rol), ['admin', 'administrador'])) {
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
            <header class="w-full max-w-[1400px] h-20 bg-white md:bg-transparent border-b md:border-none border-gray-100 flex items-center justify-between px-8 shrink-0 relative top-0 z-10 pt-4 pb-2">
                <div class="flex items-center space-x-4">
                    @hasSection('header_left')
                        @yield('header_left')
                    @else
                        <!-- Default empty space to push user profile to right -->
                    @endif
                </div>

                <div class="flex items-center space-x-4">
                    @auth
                        <div class="text-right flex flex-col justify-center">
                            <span class="text-sm font-semibold text-slate-800 leading-tight">{{ Auth::user()->name }} {{ Auth::user()->apellido }}</span>
                            <span class="text-xs text-slate-500 leading-tight capitalize">{{ Auth::user()->rol }}</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <div class="w-10 h-10 rounded-full bg-blue-50 flex items-center justify-center text-blue-600">
                                <i data-lucide="user" class="w-5 h-5"></i>
                            </div>
                            <form method="POST" action="{{ route('logout') }}" class="m-0">
                                @csrf
                                <button type="submit" title="Cerrar Sesión" class="w-10 h-10 rounded-full bg-gray-50 flex items-center justify-center text-slate-500 hover:bg-red-50 hover:text-red-600 transition-colors cursor-pointer">
                                    <i data-lucide="log-out" class="w-5 h-5"></i>
                                </button>
                            </form>
                        </div>
                    @endauth
                </div>
            </header>

            <div class="w-full max-w-[1400px] px-8 py-4">
                @yield('search_form')
            </div>

            <div class="w-full max-w-[1400px] px-8">
                <!-- Toasts are handled by SweetAlert2 -->
            </div>

            <div class="w-full max-w-[1400px] flex-1 overflow-y-auto px-8 pb-8 pt-4">
                @yield('content')
            </div>
        </main>

        @stack('modals')

        <!-- SweetAlert2 -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        
        <!-- Lucide Icons -->
        <script src="https://unpkg.com/lucide@latest"></script>
        <script>
            lucide.createIcons();

            // Global Toast setup
            const Toast = Swal.mixin({
                toast: true,
                position: 'bottom-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            });

            @if(session('success'))
                Toast.fire({
                    icon: 'success',
                    title: '{{ session('success') }}'
                });
            @endif

            // Global function for Delete Confirmation
            function confirmDelete(formId, itemName) {
                Swal.fire({
                    title: '¿Estás seguro?',
                    text: `Vas a eliminar ${itemName}. Esta acción no se puede deshacer.`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: '#64748b',
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar',
                    customClass: {
                        popup: 'rounded-2xl',
                        confirmButton: 'rounded-xl',
                        cancelButton: 'rounded-xl'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById(formId).submit();
                    }
                });
            }
        </script>
    </body>
</html>
