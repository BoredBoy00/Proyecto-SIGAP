@extends('layouts.app')

@section('title', 'Editar Usuario')

@section('header_left')
<a href="{{ route('users.index') }}">
    <button class="w-10 h-10 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center hover:bg-blue-100 transition-colors">
        <i data-lucide="arrow-left" class="w-5 h-5"></i>
    </button>
    <h2 class="text-xl font-bold text-slate-800 tracking-tight">Editar Usuario</h2>
</a>
@endsection

@section('content')
    <div class="flex flex-col items-center justify-center w-full mt-8 pb-12">
        <div class="w-full max-w-3xl bg-white rounded-2xl border border-gray-100 shadow-[0_2px_12px_-4px_rgba(0,0,0,0.05)] overflow-hidden">
            
            <div class="p-8 border-b border-gray-100">
                <h2 class="text-2xl font-bold text-slate-800">Información del Usuario</h2>
                <p class="text-slate-400 text-sm mt-1">Ingresa los datos del usuario que necesitas corregir</p>
                
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

            <form action="{{ route('users.update', $user->id) }}" method="POST" class="p-8 flex flex-col space-y-6">
                @method('PATCH')
                @csrf
                <!-- Row 1: Nombres y Apellidos -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="flex flex-col space-y-2">
                        <label class="text-sm font-semibold text-slate-700">Nombres</label>
                        <input type="text" name="nombres" value="{{ $user->name }}" required placeholder="Ej. Carlos Andrés" maxlength="25" oninput="this.value = this.value.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑ ]/g, '')" class="px-4 py-3 bg-white border border-gray-200 rounded-xl text-sm text-slate-700 placeholder-slate-300 focus:outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400 transition-all">
                    </div>
                    <div class="flex flex-col space-y-2">
                        <label class="text-sm font-semibold text-slate-700">Apellidos</label>
                        <input type="text" name="apellidos" value="{{ $user->apellido }}" required placeholder="Ej. Gómez Silva" maxlength="25" oninput="this.value = this.value.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑ ]/g, '')" class="px-4 py-3 bg-white border border-gray-200 rounded-xl text-sm text-slate-700 placeholder-slate-300 focus:outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400 transition-all">
                    </div>
                </div>

                <!-- Row 2: Cédula y Correo -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="flex flex-col space-y-2">
                        <label class="text-sm font-semibold text-slate-700">Cédula de Identidad</label>
                        <input type="text" name="cedula" value="{{ $user->cedula }}" required placeholder="Ej. 12345678" maxlength="9" oninput="this.value = this.value.replace(/[^0-9]/g, '')" class="px-4 py-3 bg-white border border-gray-200 rounded-xl text-sm text-slate-700 placeholder-slate-300 focus:outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400 transition-all">
                    </div>
                    <div class="flex flex-col space-y-2">
                        <label class="text-sm font-semibold text-slate-700">Correo Electrónico (Usuario)</label>
                        <input type="email" name="email" value="{{ $user->email }}" required placeholder="usuario@sigap.com" class="px-4 py-3 bg-white border border-gray-200 rounded-xl text-sm text-slate-700 placeholder-slate-300 focus:outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400 transition-all">
                    </div>
                </div>


                <!-- Row 4: Rol -->
                <div class="flex flex-col space-y-2 relative pb-2">
                    <label class="text-sm font-semibold text-slate-700">Rol / Nivel de Acceso</label>
                    <i data-lucide="chevron-down" class="absolute right-4 top-[38px] w-4 h-4 text-slate-400"></i>
                    <select name="rol" required class="px-4 py-3 pr-10 bg-white border border-gray-200 rounded-xl text-sm text-slate-700 appearance-none focus:outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400 transition-all">
                        <option value="" disabled>Seleccionar rol</option>
                        <option value="admin" {{ strtolower($user->rol) == 'admin' ? 'selected' : '' }}>Administrador</option>
                        <option value="recepcionista" {{ strtolower($user->rol) == 'recepcionista' ? 'selected' : '' }}>Recepcionista</option>
                        <option value="bioanalista" {{ strtolower($user->rol) == 'bioanalista' ? 'selected' : '' }}>Bioanalista</option>
                    </select>
                </div>
            

            <div class="bg-gray-50 border-t border-gray-100 p-6 flex flex-col md:flex-row items-center justify-end space-y-3 md:space-y-0 md:space-x-3">
                <a href="{{ route('users.index') }}" class="w-full text-center md:w-auto px-6 py-2.5 bg-white text-slate-700 font-medium rounded-lg border border-gray-200 hover:bg-gray-100 transition-colors">
                    Cancelar
                </a>
                <button type="submit" class="w-full md:w-auto px-6 py-2.5 bg-[#0d6efd] text-white font-medium rounded-lg hover:bg-blue-700 transition-colors shadow-sm">
                    Guardar Cambios
                </button>
            </div>
            </form>
            
        </div>
    </div>
@endsection