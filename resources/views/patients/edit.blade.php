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
                        <input type="text" id="cedula-input" name="cedula" value="{{ $patient->cedula }}" placeholder="Ej. V-12345678" maxlength="10" class="px-4 py-3 bg-white border border-gray-200 rounded-lg text-sm text-slate-700 placeholder-slate-300 focus:outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400 transition-all">
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
                        <input type="text" id="telefono-input" name="telefono" value="{{ $patient->telefono }}" placeholder="Ej. 0414-1234567" maxlength="12" class="px-4 py-3 bg-white border border-gray-200 rounded-lg text-sm text-slate-700 placeholder-slate-300 focus:outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400 transition-all">
                    </div>

                    <div class="flex flex-col space-y-2">
                        <label class="text-sm font-semibold text-slate-700">Correo Electrónico (Opcional)</label>
                        <input type="email" name="email" value="{{ $patient->email }}" placeholder="ejemplo@correo.com" class="px-4 py-3 bg-white border border-gray-200 rounded-lg text-sm text-slate-700 placeholder-slate-300 focus:outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400 transition-all">
                    </div>
                    
                    <div class="flex flex-col space-y-2">
                        <label class="text-sm font-semibold text-slate-700">Convenio / Empresa (Opcional)</label>
                        <select name="id_empresa" class="px-4 py-3 bg-white border border-gray-200 rounded-lg text-sm text-slate-700 focus:outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400 transition-all cursor-pointer">
                            <option value="">Sin convenio</option>
                            @foreach($empresas as $empresa)
                                <option value="{{ $empresa->id_empresa }}" {{ $patient->id_empresa == $empresa->id_empresa ? 'selected' : '' }}>
                                    {{ $empresa->nombre_empresa }} (-{{ $empresa->porcentaje_descuento }}%)
                                </option>
                            @endforeach
                        </select>
                    </div>

                </div>

                </div>
            

            <div class="bg-blue-50/50 border-t border-blue-100 p-6 flex items-center justify-end space-x-3 mt-auto">
                <a href="{{ route('patients.look') }}">
                    <button type="button" class="px-6 py-2.5 bg-white text-slate-700 font-medium rounded-lg border border-gray-200 hover:bg-gray-50 transition-colors cursor-pointer">
                    Cancelar
                </button>
                </a>
                <button type="submit" class="flex items-center space-x-2 px-6 py-2.5 bg-[#198754] text-white font-medium rounded-lg hover:bg-green-700 transition-colors shadow-sm cursor-pointer">
                    <i data-lucide="save" class="w-4 h-4"></i>
                    <span>Actualizar Registro</span>
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
        formatField('telefono-input', /[^0-9\-]/g, v => {
            return (v.length >= 4 && !v.includes('-') ? v.slice(0, 4) + '-' + v.slice(4) : v).slice(0, 12);
        });
    </script>
@endsection