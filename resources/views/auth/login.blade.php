@extends('layouts.auth')

@section('title', 'Iniciar Sesión')

@section('content')
    <!-- Toast Notification Global -->
    @if ($errors->any())
        <div id="toast-error"
            class="bg-red-50/90 backdrop-blur-md border border-red-200 px-5 py-4 rounded-xl shadow-[0_8px_30px_rgba(239,68,68,0.2)] z-50 flex flex-col"
            style="position: fixed; top: 20px; right: 20px; max-width: 24rem; width: 90%; transition: opacity 0.5s ease-out;">
            <div class="flex items-center mb-1.5">
                <i data-lucide="alert-circle" class="h-5 w-5 text-red-500" style="margin-right: 10px;"></i>
                <h3 class="text-sm font-bold text-red-700">Error de acceso</h3>
            </div>
            <p class="text-sm text-red-600/90 leading-snug" style="padding-left: 30px;">{{ $errors->first() }}</p>
        </div>
        <script>
            setTimeout(function () {
                var toast = document.getElementById('toast-error');
                if (toast) {
                    toast.style.opacity = '0';
                    setTimeout(function () { toast.style.display = 'none'; }, 500);
                }
            }, 5000);
        </script>
    @endif

    <div
        class="max-w-4xl flex rounded-3xl shadow-[0_20px_50px_rgba(8,_112,_184,_0.1)] bg-white/90 backdrop-blur-sm overflow-hidden relative z-10 m-4">
        <!-- Seccion Izquierda: Formulario -->
        <div class="md:w-1/2 p-8 lg:p-10 flex flex-col justify-center border border-slate-200 rounded-xl relative">

            <div class="mb-8 text-center">
                <h1 class="text-3xl font-bold text-blue-500 tracking-wide mb-2">SIGAP</h1>
                <p class="text-slate-500 text-sm">Sistema Integral de Gestión y Administración de Pacientes</p>
            </div>

            <form method="POST" action="{{ route('login') }}" class="space-y-0">
                @csrf

                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-slate-700 mb-1.5">Correo Electrónico</label>
                    <div class="relative">
                        <div class="absolute left-3 flex items-center pointer-events-none"
                            style="top: 50%; transform: translateY(-50%);">
                            <i data-lucide="mail" class="h-5 w-5 text-slate-400"></i>
                        </div>
                        <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus
                            class="block w-full pr-4 py-3 bg-slate-50/50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-600/20 focus:border-blue-600 focus:bg-white sm:text-sm transition-all duration-200 @error('email') border-red-500 focus:ring-red-500/20 focus:border-red-500 @enderror"
                            style="padding-left: 2.75rem;" placeholder="tu@email.com">
                    </div>
                </div>

                <div class="mb-4">
                    <label for="password" class="block text-sm font-medium text-slate-700 mb-1.5">Contraseña</label>
                    <div class="relative">
                        <div class="absolute left-3 flex items-center pointer-events-none"
                            style="top: 50%; transform: translateY(-50%);">
                            <i data-lucide="lock" class="h-5 w-5 text-slate-400"></i>
                        </div>
                        <input type="password" name="password" id="password" required
                            class="block w-full pr-4 py-3 bg-slate-50/50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-600/20 focus:border-blue-600 focus:bg-white sm:text-sm transition-all duration-200 @error('password') border-red-500 focus:ring-red-500/20 focus:border-red-500 @enderror"
                            style="padding-left: 2.75rem;" placeholder="••••••••">
                    </div>
                </div>

                <div class="flex items-center justify-between mt-1">
                    <div class="flex items-center">
                        <input id="remember_me" name="remember" type="checkbox"
                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-slate-300 rounded cursor-pointer transition-colors">
                        <label for="remember_me" class="ml-2 block text-sm text-slate-600 cursor-pointer select-none">
                            Recordarme
                        </label>
                    </div>
                </div>

                <div class="pt-4">
                    <button type="submit"
                        class="cursor-pointer w-full flex justify-center py-2.5 px-4 border border-transparent rounded-xl shadow-sm text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 transform hover:-translate-y-0.5">
                        Iniciar Sesión
                    </button>
                </div>
            </form>
        </div>

        <!-- Seccion Derecha: Imagen/Decoración -->
        <div
            class="hidden md:flex md:w-1/2 bg-gradient-to-br from-blue-50 to-indigo-50/50 flex-col items-center justify-center p-12 relative overflow-hidden border-l border-white">
            <!-- Elementos decorativos -->
            <div class="absolute top-0 right-0 -mr-20 -mt-20 w-72 h-72 rounded-full bg-blue-400/20 blur-3xl"></div>
            <div class="absolute bottom-0 left-0 -ml-20 -mb-20 w-80 h-80 rounded-full bg-indigo-400/20 blur-3xl"></div>

            <div class="relative z-10 text-center w-full max-w-sm">
                <div
                    class="w-48 h-48 bg-white/80 backdrop-blur-xl rounded-full shadow-2xl flex items-center justify-center mx-auto mb-10 border border-white relative">
                    <!-- Anillo decorativo animado -->
                    <div
                        class="absolute inset-0 border-2 border-blue-200 rounded-full animate-[spin_10s_linear_infinite] border-t-blue-500">
                    </div>
                    <div
                        class="absolute inset-2 border border-indigo-100 rounded-full animate-[spin_15s_linear_infinite_reverse]">
                    </div>

                    <i data-lucide="microscope" class="w-20 h-20 text-blue-600 drop-shadow-md"></i>
                </div>
                <h3 class="text-2xl font-bold text-slate-800 mb-4 tracking-tight">Gestión Eficiente</h3>
                <p class="text-slate-600 leading-relaxed">Optimiza los procesos de tu laboratorio clínico y ofrece la mejor
                    atención a tus pacientes con herramientas de vanguardia.</p>
            </div>
        </div>
    </div>

@endsection