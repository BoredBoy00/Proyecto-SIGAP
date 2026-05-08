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
        <div class="border-b border-gray-200 w-full mb-6 flex">
            <a href="{{ route('patients.create') }}">
                <button class="text-blue-600 font-semibold border-b-2 pb-3 px-1">
                    Registro Individual
                </button>
            </a>
            <a href="{{ route('patients.look') }}">
                <button class="text-blue-600 font-semibold border-b-2 border-blue-600 pb-3 px-1">
                    Ver Pacientes
                </button>
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
                                <td class="px-6 py-4 text-center">
                                    <!-- Editar -->
                                    <a href="{{ route('patients.edit', $patient->id) }}">
                                        <button class="w-8 h-8 rounded-full flex items-center justify-center text-slate-400 hover:text-blue-500 hover:bg-blue-50 transition-colors">
                                            <i data-lucide="edit-2" class="w-4 h-4"></i>
                                        </button>
                                    </a>
                                </td>
                                <td>
                                    <!-- Eliminar -->
                                    <form action="{{ route('patients.destroy', $patient->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="...">
                                            <i data-lucide="trash-2"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

        </div>
@endsection
