@extends('layouts.app')

@section('title', 'Gestión de Usuarios')

@section('content')


    <div class="flex flex-col max-w-[1400px] w-full mt-2">
        
        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-slate-800 tracking-tight">Gestión de Usuarios</h1>
        </div>

        <!-- Main Card -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden flex flex-col">
            
            <!-- Controls area -->
            <div class="p-6 md:p-8 border-b border-gray-100 flex flex-col md:flex-row items-center justify-between gap-4">
                <!-- Search -->
                <div class="relative w-full md:w-[320px]">
                    <form action="{{ route('users.index') }}" method="GET" class="relative w-full md:w-[320px]">
                        <i data-lucide="search" class="absolute left-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-slate-400"></i>
                    <input type="text" placeholder="Buscar usuario por nombre o rol..." 
                        class="w-full pl-9 pr-4 py-2 bg-white border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-1 focus:ring-blue-400 focus:border-blue-400 transition-all text-slate-700 placeholder-slate-400"
                        name="search">
                    </form>
                </div>

                <!-- Right Controls -->
                <div class="flex items-center space-x-3 w-full md:w-auto">
                    <a href="{{ route('users.create') }}" class="flex items-center space-x-2 px-5 py-2.5 bg-[#0d6efd] text-white font-medium rounded-lg hover:bg-blue-700 transition-colors shadow-sm">
                        <i data-lucide="plus" class="w-4 h-4"></i>
                        <span>Nuevo Usuario</span>
                    </a>
                </div>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-600">
                    <thead class="bg-slate-50 text-slate-500 font-bold text-xs uppercase tracking-wider border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-4 w-16">ID</th>
                            <th class="px-6 py-4 w-48">USUARIO</th>
                            <th class="px-6 py-4">CORREO ELECTRÓNICO</th>
                            <th class="px-6 py-4">CÉDULA</th>
                            <th class="px-6 py-4 w-52">ROL / NIVEL DE ACCESO</th>
                            <th class="px-6 py-4 w-32">ESTADO</th>
                            <th class="px-6 py-4 w-48">ÚLTIMO ACCESO</th>
                            <th class="px-6 py-4 w-28 text-center">ACCIONES</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-[15px]">
                        @foreach($usuarios as $user)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-6 py-4 text-slate-500 font-medium">#{{ $user->id }}</td>
                            <td class="px-6 py-4">
                                <div class="font-bold text-slate-800">{{ $user->name }} {{ $user->apellido }}</div>
                            </td>
                            <td class="px-6 py-4 text-slate-500">{{ $user->email }}</td>
                            <td class="px-6 py-4 text-slate-700 font-medium">{{ $user->cedula ?? 'N/A' }}</td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ strtolower($user->rol) == 'admin' ? 'bg-[#0d6efd] text-white' : 'bg-orange-50 text-orange-600 border border-orange-100' }}">
                                    {{ strtolower($user->rol) == 'admin' ? 'Administrador' : ucfirst(strtolower($user->rol)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 font-semibold">
                                <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-bold {{ $user->estado == 'Activo' ? 'text-green-600 bg-green-50' : 'text-slate-500 bg-slate-100' }}">
                                    {{ $user->estado }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-slate-500">{{ $user->updated_at->diffForHumans() }}</td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-center space-x-2"> 

                                    <!-- Editar -->
                                    <a href="{{ route('users.edit', $user->id) }}" class="w-8 h-8 flex items-center justify-center bg-blue-50 text-blue-500 hover:text-blue-700 hover:bg-blue-100 rounded-lg transition-colors">
                                        <i data-lucide="edit-2" class="w-4 h-4"></i>
                                    </a>

                                    <!-- Eliminar -->
                                    @if(Auth::id() !== $user->id)
                                    <form id="delete-user-{{ $user->id }}" action="{{ route('users.destroy', $user->id) }}" method="POST" class="m-0">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" onclick="confirmDelete('delete-user-{{ $user->id }}', '{{ $user->name }} {{ $user->apellido }}')" class="w-8 h-8 flex items-center justify-center bg-red-50 text-red-500 hover:text-red-700 hover:bg-red-100 rounded-lg transition-colors">
                                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                                        </button>
                                    </form>
                                    @else
                                    <button type="button" disabled class="w-8 h-8 flex items-center justify-center bg-gray-50 text-slate-300 rounded-lg cursor-not-allowed" title="No puedes eliminar tu propio usuario">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </button>
                                    @endif
                                    
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>
    </div>

@endsection
