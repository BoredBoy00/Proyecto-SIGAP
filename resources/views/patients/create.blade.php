@extends('layouts.app')

@section('title', 'Gestión de Pacientes')

@section('content')
    <div class="flex flex-col max-w-5xl mx-auto mt-2">
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-slate-800 tracking-tight">Gestión de Pacientes</h1>
            <p class="text-slate-400 mt-1">Administra los registros de pacientes individuales o realiza cargas masivas.</p>
        </div>

        <!-- Navigation Buttons -->
        <div class="flex items-center space-x-4 mb-8">
            <a href="{{ route('patients.create') }}"
                class="inline-flex items-center px-5 py-2.5 text-sm font-bold rounded-xl bg-blue-600 text-white shadow-md shadow-blue-500/20 hover:bg-blue-700 hover:-translate-y-0.5 transition-all duration-200">
                <i data-lucide="user-plus" class="w-4 h-4 mr-2"></i>
                Registro Individual
            </a>
            <a href="{{ route('patients.look') }}"
                class="inline-flex items-center px-5 py-2.5 text-sm font-bold rounded-xl bg-white border border-slate-200 text-slate-600 hover:text-blue-600 hover:border-blue-200 hover:shadow-sm hover:-translate-y-0.5 transition-all duration-200">
                <i data-lucide="users" class="w-4 h-4 mr-2"></i>
                Ver Pacientes
            </a>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden flex flex-col">
            <div class="p-6 md:p-8 border-b border-gray-100">
                <h2 class="text-xl font-bold text-slate-800">Nuevo Paciente</h2>
                <p class="text-slate-400 text-sm mt-1">Ingresa los datos personales para registrar un nuevo paciente.</p>

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

            <form action="{{ route('patients.store') }}" method="POST" class="p-6 md:p-8 flex flex-col space-y-6 flex-1">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="flex flex-col space-y-2">
                        <label class="text-sm font-semibold text-slate-700">Nombre</label>
                        <input type="text" name="nombres" value="{{ old('nombres') }}" required placeholder="Ej. Carlos"
                            maxlength="25" oninput="this.value = this.value.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑ ]/g, '')"
                            class="px-4 py-3 bg-white border border-gray-200 rounded-lg text-sm text-slate-700 placeholder-slate-300 focus:outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400 transition-all">
                    </div>
                    <div class="flex flex-col space-y-2">
                        <label class="text-sm font-semibold text-slate-700">Apellido</label>
                        <input type="text" name="apellidos" value="{{ old('apellidos') }}" required
                            placeholder="Ej. Rodriguez" maxlength="25"
                            oninput="this.value = this.value.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑ ]/g, '')"
                            class="px-4 py-3 bg-white border border-gray-200 rounded-lg text-sm text-slate-700 placeholder-slate-300 focus:outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400 transition-all">
                    </div>
                    <div class="flex flex-col space-y-2">
                        <label class="text-sm font-semibold text-slate-700">Cédula de Identidad</label>
                        <input type="text" id="cedula-input" name="cedula" value="{{ old('cedula') }}" placeholder="Ej. V-12345678" maxlength="10"
                            class="px-4 py-3 bg-white border border-gray-200 rounded-lg text-sm text-slate-700 placeholder-slate-300 focus:outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400 transition-all">
                    </div>
                    <div class="flex flex-col space-y-2 relative">
                        <label class="text-sm font-semibold text-slate-700">Fecha de Nacimiento</label>
                        <i data-lucide="calendar" class="absolute right-4 top-[38px] w-4 h-4 text-slate-400"></i>
                        <input type="date" name="fecha_nacimiento" required value="{{ old('fecha_nacimiento') }}"
                            class="px-4 py-3 pr-10 bg-white border border-gray-200 rounded-lg text-sm text-slate-700 placeholder-slate-300 focus:outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400 transition-all">
                    </div>
                    <div class="flex flex-col space-y-2">
                        <label class="text-sm font-semibold text-slate-700">Teléfono</label>
                        <input type="text" id="telefono-input" name="telefono" value="{{ old('telefono') }}" placeholder="Ej. 0414-1234567" maxlength="12"
                            class="px-4 py-3 bg-white border border-gray-200 rounded-lg text-sm text-slate-700 placeholder-slate-300 focus:outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400 transition-all">
                    </div>
                    <div class="flex flex-col space-y-2">
                        <label class="text-sm font-semibold text-slate-700">Correo Electrónico (Opcional)</label>
                        <input type="email" name="email" value="{{ old('email') }}" placeholder="ejemplo@correo.com"
                            class="px-4 py-3 bg-white border border-gray-200 rounded-lg text-sm text-slate-700 placeholder-slate-300 focus:outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400 transition-all">
                    </div>
                </div>

                <div class="bg-blue-50/50 border-t border-blue-100 p-6 flex items-center justify-end space-x-3 mt-auto">
                    <button type="button"
                        class="px-6 py-2.5 bg-white text-slate-700 font-medium rounded-lg border border-gray-200 hover:bg-gray-50 transition-colors">
                        Cancelar
                    </button>
                    <button type="submit"
                        class="flex items-center space-x-2 px-6 py-2.5 bg-[#198754] text-white font-medium rounded-lg hover:bg-green-700 transition-colors shadow-sm">
                        <i data-lucide="save" class="w-4 h-4"></i>
                        <span>Guardar Paciente</span>
                    </button>
                </div>
            </form>
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