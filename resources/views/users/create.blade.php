@extends('layouts.app')

@section('title', 'Nuevo Usuario')



@section('content')
    <div class="flex flex-col items-center justify-center w-full mt-8 pb-12">
        <div
            class="w-full max-w-3xl bg-white rounded-2xl border border-gray-100 shadow-[0_2px_12px_-4px_rgba(0,0,0,0.05)] overflow-hidden">

            <div class="p-8 border-b border-gray-100">
                <h2 class="text-2xl font-bold text-slate-800">Información del Usuario</h2>
                <p class="text-slate-400 text-sm mt-1">Ingresa los datos del nuevo usuario y asigna sus credenciales de
                    acceso.</p>

                @if ($errors->any())
                    <div class="mt-4 p-4 bg-red-50 text-red-600 rounded-lg text-sm">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if (session('success'))
                    <div class="mt-4 p-4 bg-green-50 text-green-600 rounded-lg text-sm">
                        {{ session('success') }}
                    </div>
                @endif
            </div>

            <form action="{{ route('users.store') }}" method="POST" class="p-8 flex flex-col space-y-6">
                @csrf
                <!-- Row 1: Nombres y Apellidos -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="flex flex-col space-y-2">
                        <label class="text-sm font-semibold text-slate-700">Nombres</label>
                        <input type="text" name="nombres" value="{{ old('nombres') }}" required
                            placeholder="Ej. Carlos Andrés"
                            class="px-4 py-3 bg-white border border-gray-200 rounded-xl text-sm text-slate-700 placeholder-slate-300 focus:outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400 transition-all">
                    </div>
                    <div class="flex flex-col space-y-2">
                        <label class="text-sm font-semibold text-slate-700">Apellidos</label>
                        <input type="text" name="apellidos" value="{{ old('apellidos') }}" required
                            placeholder="Ej. Gómez Silva"
                            class="px-4 py-3 bg-white border border-gray-200 rounded-xl text-sm text-slate-700 placeholder-slate-300 focus:outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400 transition-all">
                    </div>
                </div>

                <!-- Row 2: Cédula -->
                <div class="flex flex-col space-y-2">
                    <label class="text-sm font-semibold text-slate-700">Cédula de Identidad</label>
                    <input type="text" id="cedula-input" name="cedula" value="{{ old('cedula') }}"
                        placeholder="Ejemplo V-12345678" maxlength="10"
                        class="px-4 py-3 bg-white border border-gray-200 rounded-xl text-sm text-slate-700 placeholder-slate-300 focus:outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400 transition-all">
                </div>

                <!-- Row 3: Correo -->
                <div class="flex flex-col space-y-2">
                    <label class="text-sm font-semibold text-slate-700">Correo Electrónico (Usuario)</label>
                    <input type="email" name="email" value="{{ old('email') }}" required placeholder="usuario@sigap.com"
                        class="px-4 py-3 bg-white border border-gray-200 rounded-xl text-sm text-slate-700 placeholder-slate-300 focus:outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400 transition-all">
                </div>

                <!-- Row 3: Contraseñas -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="flex flex-col space-y-2">
                        <label class="text-sm font-semibold text-slate-700">Contraseña</label>
                        <div class="relative w-full" style="position: relative;">
                            <input type="password" id="password" name="password" required placeholder="••••••••"
                                class="w-full px-4 py-3 pr-12 bg-white border border-gray-200 rounded-xl text-sm text-slate-700 placeholder-slate-400 focus:outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400 transition-all">
                            <button type="button" onclick="togglePassword('password', this)" 
                                class="text-slate-400 hover:text-blue-600 transition-colors cursor-pointer flex items-center justify-center"
                                style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); border: none; background: none; padding: 4px; z-index: 10; margin: 0;">
                                <i data-lucide="eye" style="width: 20px; height: 20px;"></i>
                            </button>
                        </div>
                    </div>
                    <div class="flex flex-col space-y-2">
                        <label class="text-sm font-semibold text-slate-700">Confirmar Contraseña</label>
                        <div class="relative w-full" style="position: relative;">
                            <input type="password" id="password_confirmation" name="password_confirmation" required placeholder="••••••••"
                                class="w-full px-4 py-3 pr-12 bg-white border border-gray-200 rounded-xl text-sm text-slate-700 placeholder-slate-400 focus:outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400 transition-all">
                            <button type="button" onclick="togglePassword('password_confirmation', this)" 
                                class="text-slate-400 hover:text-blue-600 transition-colors cursor-pointer flex items-center justify-center"
                                style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); border: none; background: none; padding: 4px; z-index: 10; margin: 0;">
                                <i data-lucide="eye" style="width: 20px; height: 20px;"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Row 4: Rol -->
                <div class="flex flex-col space-y-2 relative pb-2">
                    <label class="text-sm font-semibold text-slate-700">Rol / Nivel de Acceso</label>
                    <i data-lucide="chevron-down" class="absolute right-4 top-[38px] w-4 h-4 text-slate-400"></i>
                    <select name="rol" required
                        class="px-4 py-3 pr-10 bg-white border border-gray-200 rounded-xl text-sm text-slate-700 appearance-none focus:outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400 transition-all">
                        <option value="" disabled selected>Seleccionar rol</option>
                        <option value="Administrador">Administrador</option>
                        <option value="Recepcionista">Recepcionista</option>
                        <option value="Bioanalista">Bioanalista</option>
                    </select>
                </div>


                <div
                    class="bg-gray-50 border-t border-gray-100 p-6 flex flex-col md:flex-row items-center justify-end space-y-3 md:space-y-0 md:space-x-3">
                    <a href="{{ route('users.index') }}"
                        class="w-full text-center md:w-auto px-6 py-2.5 bg-white text-slate-700 font-medium rounded-lg border border-gray-200 hover:bg-gray-100 transition-colors">
                        Cancelar
                    </a>
                    <button type="submit"
                        class="w-full md:w-auto px-6 py-2.5 bg-[#0d6efd] text-white font-medium rounded-lg hover:bg-blue-700 transition-colors shadow-sm cursor-pointer">
                        Crear Usuario
                    </button>
                </div>
            </form>

        </div>
    </div>
    </div>
    <script>
        const formatField = (id, regex, fn) => {
            const el = document.getElementById(id);
            if (!el) return;
            let last = el.value;
            el.oninput = () => {
                if (el.value.length < last.length) return last = el.value;
                el.value = last = fn(el.value.replace(regex, '').toUpperCase());
            };
        };
        formatField('cedula-input', /[^V\-0-9]/g, v => {
            if (/^[0-9]/.test(v)) v = 'V-' + v;
            return (v[0] === 'V' && v[1] !== '-' && v.length > 1 ? 'V-' + v.slice(1) : v).slice(0, 10);
        });

        function togglePassword(inputId, btn) {
            const input = document.getElementById(inputId);
            const icon = btn.querySelector('i');
            if (input.type === 'password') {
                input.type = 'text';
                icon.setAttribute('data-lucide', 'eye-off');
            } else {
                input.type = 'password';
                icon.setAttribute('data-lucide', 'eye');
            }
            lucide.createIcons();
        }
    </script>
@endsection