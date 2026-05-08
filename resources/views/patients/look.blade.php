@extends('layouts.app')

@section('title', 'Gestión de Pacientes')

@section('content')
    <section class="flex flex-col max-w-5x1 mx-auto mt-2">
        
        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-slate-800 tracking-tight">Gestión de Pacientes</h1>
            <p class="text-slate-400 mt-1">Administra los registros de pacientes individuales o realiza cargas masivas.</p>
        </div>

        <!-- Search Bar -->
        <form method="GET" action="{{ route('patients.look') }}" class="flex items-center space-x-3 mb-8 bg-white border border-gray-200 rounded-xl p-2 shadow-[0_2px_8px_-4px_rgba(0,0,0,0.05)] text-sm">
            @if(!empty($search))

            @endif
            <div class="flex-1 relative pl-2">
                <i data-lucide="search" class="absolute left-1 top-1/2 transform -translate-y-1/2 w-5 h-5 text-slate-400"></i>
                <input
                    type="text"
                    name="search"
                    value="{{ $search ?? '' }}"
                    placeholder="Buscar cliente por nombre, apellido o cédula de identidad..."
                    class="w-full pl-8 py-2 bg-transparent text-slate-700 placeholder-slate-400 focus:outline-none"
                >
            </div>
            <button type="submit" class="px-6 py-2.5 bg-[#0d6efd] text-white font-medium rounded-lg hover:bg-blue-700 transition-colors">
                Buscar
            </button>
        </form>

        <!-- Tabs -->
        <div class="flex items-center space-x-2 mb-6">
            <a href="{{ route('patients.create') }}" class="px-5 py-2.5 rounded-full font-medium text-slate-500 hover:bg-gray-100 transition-all">
                Registro Individual
            </a>
            <a href="{{ route('patients.look') }}" class="px-5 py-2.5 rounded-full font-bold bg-blue-50 text-blue-600 border border-blue-100 shadow-sm transition-all">
                Ver Pacientes
            </a>
        </div>
    </section>
    
    <!-- Form Card -->
    <div class="flex flex-col max-w-5x1 mx-auto mt-2">
                <table class="w-full text-left text-sm text-slate-600">
                    <thead class="bg-slate-50 text-slate-500 font-bold text-xs uppercase tracking-wider border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-4 w-48">Nombre</th>
                            <th class="px-6 py-4">Apellido</th>
                            <th class="px-6 py-4 w-52">Cédula de Identidad</th>
                            <th class="px-6 py-4 w-32">Fecha de Nacimiento</th>
                            <th class="px-6 py-4 w-48">Teléfono</th>
                            <th class="px-6 py-4 w-48">Correo Electrónico</th>
                            <th class="px-6 py-4 w-28 text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-[15px]">
                        @foreach($patients as $patient)
                            <tr id="item-{{ $patient->id }}" class="hover:bg-gray-50/50 transition-colors {{ (request('highlight') == $patient->id || request('highlight') == $patient->cedula) ? 'highlight-flash' : '' }}">
                                <td class="px-6 py-4">{{ $patient->nombre }}</td>
                                <td class="px-6 py-4">{{ $patient->apellido }}</td>
                                <td class="px-6 py-4">{{ $patient->cedula }}</td>
                                <td class="px-6 py-4">{{ $patient->fecha_nacimiento ? \Carbon\Carbon::parse($patient->fecha_nacimiento)->format('d/m/Y') : '' }}</td>
                                <td class="px-6 py-4">{{ $patient->telefono }}</td>
                                <td class="px-6 py-4">{{ $patient->email }}</td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-center space-x-2">
                                        <!-- Editar -->
                                        <a href="{{ route('patients.edit', $patient->id) }}" title="Editar" class="w-[28px] h-[28px] flex items-center justify-center bg-blue-50/50 hover:bg-blue-100 text-blue-500 rounded-md transition-all">
                                            <i data-lucide="edit-2" class="w-4 h-4"></i>
                                        </a>

                                        <!-- Eliminar -->
                                        <form action="{{ route('patients.destroy', $patient->id) }}" method="POST" class="m-0 p-0 form-delete inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" title="Eliminar" class="w-[28px] h-[28px] flex items-center justify-center bg-red-50/50 hover:bg-red-100 text-red-500 rounded-md transition-all">
                                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

        </div>
@endsection
