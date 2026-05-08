@extends('layouts.app')

@section('title', 'Gestión de Pacientes')

@section('content')
    <div class="flex flex-col max-w-6xl mx-auto mt-2 pb-10">
        
        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-slate-800 tracking-tight">Gestión de Pacientes</h1>
            <p class="text-slate-400 mt-1">Administra los registros de pacientes individuales o realiza búsquedas.</p>
        </div>

        <!-- Navigation Buttons -->
        <div class="flex items-center space-x-4 mb-8">
            <a href="{{ route('patients.create') }}" class="inline-flex items-center px-5 py-2.5 text-sm font-bold rounded-xl bg-white border border-slate-200 text-slate-600 hover:text-blue-600 hover:border-blue-200 hover:shadow-sm hover:-translate-y-0.5 transition-all duration-200">
                <i data-lucide="user-plus" class="w-4 h-4 mr-2"></i>
                Registro Individual
            </a>
            <a href="{{ route('patients.look') }}" class="inline-flex items-center px-5 py-2.5 text-sm font-bold rounded-xl bg-blue-600 text-white shadow-md shadow-blue-500/20 hover:bg-blue-700 hover:-translate-y-0.5 transition-all duration-200">
                <i data-lucide="users" class="w-4 h-4 mr-2"></i>
                Ver Pacientes
            </a>
        </div>

        <!-- Search Bar -->
        <form method="GET" action="{{ route('patients.look') }}" class="flex items-center space-x-3 mb-6 bg-white border border-gray-200 rounded-2xl p-2 shadow-sm transition-shadow focus-within:shadow-md focus-within:border-blue-300">
            <div class="flex-1 relative">
                <i data-lucide="search" class="absolute top-1/2 transform -translate-y-1/2 w-5 h-5 text-slate-400" style="left: 1rem;"></i>
                <input
                    type="text"
                    name="search"
                    value="{{ $search ?? '' }}"
                    placeholder="Buscar paciente por nombre, apellido o cédula..."
                    class="w-full py-2 bg-transparent text-slate-700 text-sm placeholder-slate-400 focus:outline-none"
                    style="padding-left: 2.75rem;"
                >
            </div>
            <button type="submit" class="px-6 py-2.5 bg-blue-600 text-white font-semibold text-sm rounded-xl hover:bg-blue-700 shadow-sm hover:shadow-md hover:shadow-blue-500/20 transition-all cursor-pointer">
                Buscar
            </button>
        </form>

        <!-- Table Container -->
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden flex flex-col">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-600">
                    <thead class="bg-slate-50 text-slate-500 font-bold text-[11px] uppercase tracking-wider border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-4 w-48">Nombre</th>
                            <th class="px-6 py-4">Apellido</th>
                            <th class="px-6 py-4 w-40 text-center">Cédula</th>
                            <th class="px-6 py-4 w-32 text-center">Nacimiento</th>
                            <th class="px-6 py-4 w-36 text-center">Teléfono</th>
                            <th class="px-6 py-4 w-48">Correo Electrónico</th>
                            <th class="px-6 py-4 w-28 text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-[14px]">
                        @forelse($patients as $patient)
                            <tr id="item-{{ $patient->id }}" class="hover:bg-blue-50/30 transition-colors {{ (request('highlight') == $patient->id || request('highlight') == $patient->cedula) ? 'highlight-flash' : '' }}">
                                <td class="px-6 py-4 font-semibold text-slate-800">{{ $patient->nombre }}</td>
                                <td class="px-6 py-4">{{ $patient->apellido }}</td>
                                <td class="px-6 py-4 text-center font-medium">{{ $patient->cedula }}</td>
                                <td class="px-6 py-4 text-center text-slate-500">{{ $patient->fecha_nacimiento ? \Carbon\Carbon::parse($patient->fecha_nacimiento)->format('d/m/Y') : '--/--/----' }}</td>
                                <td class="px-6 py-4 text-center">{{ $patient->telefono }}</td>
                                <td class="px-6 py-4 text-slate-500">{{ $patient->email }}</td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-center space-x-4">
                                        <!-- Editar -->
                                        <a href="{{ route('patients.edit', $patient->id) }}" class="text-blue-500 hover:text-blue-700 hover:scale-110 transition-transform cursor-pointer" title="Editar paciente">
                                            <i data-lucide="edit-2" class="w-5 h-5"></i>
                                        </a>
                                        <!-- Eliminar -->
                                        <form action="{{ route('patients.destroy', $patient->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar este paciente?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-500 hover:text-red-700 hover:scale-110 transition-transform cursor-pointer" title="Eliminar paciente">
                                                <i data-lucide="trash-2" class="w-5 h-5"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center text-slate-400 italic">
                                    No se encontraron pacientes registrados.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Footer -->
            <div class="px-6 py-4 border-t border-gray-100 bg-slate-50 text-xs text-slate-500 flex justify-between items-center">
                <span>Mostrando resultados</span>
                <span class="font-semibold text-slate-700">Total: {{ count($patients) }} pacientes</span>
            </div>
        </div>
    </div>
@endsection
