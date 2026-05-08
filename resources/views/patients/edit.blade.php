@extends('layouts.app')

@section('title', 'Editar Paciente')

@section('content')
    <div class="flex flex-col max-w-5xl mx-auto mt-2">
        
        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-slate-800 tracking-tight">Editar Paciente</h1>
        </div>

        <!-- Form Card -->
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden flex flex-col">
            
            <div class="p-6 md:p-8 border-b border-gray-100">
                <h2 class="text-xl font-bold text-slate-800">Editar Paciente</h2>
                <p class="text-slate-400 text-sm mt-1">Actualiza los datos personales del paciente.</p>
                
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

            <form action="{{ route('patients.update', $patient->id) }}" method="POST" class="p-6 md:p-8 flex flex-col space-y-6 flex-1">
                @csrf
                @method('PATCH')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    
                    <!-- Nombre -->
                    <div class="flex flex-col space-y-2">
                        <label class="text-sm font-semibold text-slate-700">Nombre</label>
                        <input type="text" name="nombres" value="{{ $patient->nombre }}" required placeholder="Ej. Carlos" maxlength="15" oninput="this.value = this.value.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑ ]/g, '')" class="px-4 py-3 bg-white border border-gray-200 rounded-lg text-sm text-slate-700 placeholder-slate-300 focus:outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400 transition-all">
                    </div>

                    <!-- Apellido -->
                    <div class="flex flex-col space-y-2">
                        <label class="text-sm font-semibold text-slate-700">Apellido</label>
                        <input type="text" name="apellidos" value="{{ $patient->apellido }}" required placeholder="Ej. Rodriguez" maxlength="15" oninput="this.value = this.value.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑ ]/g, '')" class="px-4 py-3 bg-white border border-gray-200 rounded-lg text-sm text-slate-700 placeholder-slate-300 focus:outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400 transition-all">
                    </div>

                    <!-- Cédula -->
                    <div class="flex flex-col space-y-2">
                        <label class="text-sm font-semibold text-slate-700">Cédula de Identidad</label>
                        <input type="text" name="cedula" value="{{ $patient->cedula }}" placeholder="Ej. V-12345678" maxlength="9" oninput="this.value = this.value.replace(/[^0-9]/g, '')" class="px-4 py-3 bg-white border border-gray-200 rounded-lg text-sm text-slate-700 placeholder-slate-300 focus:outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400 transition-all">
                    </div>

                    <!-- Fecha de Nacimiento -->
                    <div class="flex flex-col space-y-2 relative">
                        <label class="text-sm font-semibold text-slate-700">Fecha de Nacimiento</label>
                        <i data-lucide="calendar" class="absolute right-4 top-[38px] w-4 h-4 text-slate-400"></i>
                        <input type="date" name="fecha_nacimiento" value="{{ $patient->fecha_nacimiento }}" placeholder="DD/MM/AAAA" class="px-4 py-3 pr-10 bg-white border border-gray-200 rounded-lg text-sm text-slate-700 placeholder-slate-300 focus:outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400 transition-all">
                    </div>

                    <!-- Teléfono -->
                    <div class="flex flex-col space-y-2">
                        <label class="text-sm font-semibold text-slate-700">Teléfono</label>
                        <input type="text" name="telefono" value="{{ $patient->telefono }}" placeholder="Ej. 0414-1234567" maxlength="11" oninput="this.value = this.value.replace(/[^0-9]/g, '')" class="px-4 py-3 bg-white border border-gray-200 rounded-lg text-sm text-slate-700 placeholder-slate-300 focus:outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400 transition-all">
                    </div>

                    <!-- Correo -->
                    <div class="flex flex-col space-y-2">
                        <label class="text-sm font-semibold text-slate-700">Correo Electrónico (Opcional)</label>
                        <input type="email" name="email" value="{{ $patient->email }}" placeholder="ejemplo@correo.com" class="px-4 py-3 bg-white border border-gray-200 rounded-lg text-sm text-slate-700 placeholder-slate-300 focus:outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400 transition-all">
                    </div>

                </div>
            

            <div class="bg-blue-50/50 border-t border-blue-100 p-6 flex items-center justify-end space-x-3 mt-auto">
                <a href=" {{ route('patients.look') }}">
                    <button type="button" class="px-6 py-2.5 bg-white text-slate-700 font-medium rounded-lg border border-gray-200 hover:bg-gray-50 transition-colors">
                    Cancelar
                </button>
                </a>
                <button type="submit" class="flex items-center space-x-2 px-6 py-2.5 bg-[#198754] text-white font-medium rounded-lg hover:bg-green-700 transition-colors shadow-sm">
                    <i data-lucide="save" class="w-4 h-4"></i>
                    <span>Actualizar Registro</span>
                </button>
            </div>
            </form>
            
        </div>
    </div>
@endsection