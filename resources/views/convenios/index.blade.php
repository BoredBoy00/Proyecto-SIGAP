@extends('layouts.app')

@section('title', 'Gestión de Convenios')

@section('content')
    <div class="flex flex-col max-w-5xl w-full mt-2 mx-auto">
        
        <!-- Header -->
        <div class="mb-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h1 class="text-3xl font-bold text-slate-800 tracking-tight">Gestión de Convenios</h1>
                <p class="text-slate-400 mt-1">Gestiona los convenios con proveedores y clientes.</p>
            </div>
            <button class="flex items-center space-x-2 px-5 py-2.5">
                <a href="{{ route('convens.create') }}" class="flex items-center space-x-2 px-5 py-2.5 bg-[#0d6efd] text-white font-medium rounded-lg hover:bg-blue-700 transition-colors shadow-sm">
                    <i data-lucide="plus" class="w-4 h-4"></i>
                    <span>Nuevo Convenio</span>
                </a>
            </button>
        </div>

        <!-- Main Card -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden flex flex-col">
            
            <!-- Controls area -->
            <form action="{{ route('convens.index') }}" method="GET" class="p-6 md:p-8 border-b border-gray-100 flex flex-col md:flex-row items-center justify-between gap-4">
                <!-- Search -->
                <div class="relative w-full md:w-[400px]">
                    <i data-lucide="search" class="absolute left-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-slate-400"></i>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Buscar por código, RIF o nombre de la empresa..." 
                           class="w-full pl-9 pr-4 py-2 bg-white border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-1 focus:ring-blue-400 focus:border-blue-400 transition-all text-slate-700 placeholder-slate-400">
                </div>

                <!-- Right Control -->
                <div class="relative min-w-[200px] w-full md:w-auto">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                        <i data-lucide="list-filter" class="w-4 h-4 text-slate-500"></i>
                    </div>
                    <select name="sort" onchange="this.form.submit()" class="w-full text-center pl-10 pr-4 py-2 bg-white border border-gray-200 rounded-lg text-sm text-slate-700 hover:bg-gray-50 transition-colors focus:outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400 appearance-none cursor-pointer" style="text-align-last: center;">
                        <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Más nuevos</option>
                        <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Más viejos</option>
                        <option value="az" {{ request('sort') == 'az' ? 'selected' : '' }}>Nombre (A-Z)</option>
                        <option value="za" {{ request('sort') == 'za' ? 'selected' : '' }}>Nombre (Z-A)</option>
                    </select>
                </div>
            </form>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-600">
                    <thead class="bg-blue-50 text-slate-500 font-bold text-xs uppercase tracking-wider border-b border-blue-100">
                        <tr>
                            <th class="px-6 py-4 w-32">CÓDIGO</th>
                            <th class="px-6 py-4 w-48">RIF</th>
                            <th class="px-6 py-4">NOMBRE DE LA EMPRESA</th>
                            <th class="px-6 py-4 w-32 text-center">DIRECCIÓN</th>
                            <th class="px-6 py-4 w-40 text-center">% DE DESCUENTO</th>
                            <th class="px-6 py-4 w-28 text-center">ACCIONES</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-[15px]">
                        @forelse($empresas as $empresa)
                        <tr class="hover:bg-blue-50/50 transition-colors group">
                            <td class="px-6 py-4 text-slate-500 font-medium">#{{ str_pad($empresa->id_empresa, 4, '0', STR_PAD_LEFT) }}</td>
                            <td class="px-6 py-4 font-medium text-slate-700">{{ $empresa->rif }}</td>
                            <td class="px-6 py-4 text-slate-600">{{ $empresa->nombre_empresa }}</td>
                            <td class="px-6 py-4 text-center text-slate-600">{{ $empresa->direccion }}</td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700">
                                    {{ $empresa->porcentaje_descuento }}%
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-center space-x-2"> 

                                    <!-- Editar -->
                                    <a href="{{ route('convens.edit', $empresa->id_empresa) }}">
                                    <button type="button" class="w-8 h-8 rounded-full flex items-center justify-center text-slate-400 hover:text-blue-500 hover:bg-blue-50 transition-colors">
                                        <i data-lucide="edit-2" class="w-4 h-4"></i>
                                    </button>
                                    </a>

                                    <!-- Eliminar -->
                                    <form action="{{ route('convens.destroy', $empresa->id_empresa) }}" method="POST" onsubmit="return confirm('¿Estás seguro de que deseas eliminar este convenio?');">
                                        @csrf
                                        @method('DELETE') 
                                        <button type="submit" class="w-8 h-8 rounded-full flex items-center justify-center text-slate-400 hover:text-red-500 hover:bg-red-50 transition-colors">
                                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                                        </button>
                                    </form>
                                    
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-slate-500">
                                <div class="flex flex-col items-center justify-center space-y-3">
                                    <i data-lucide="building-2" class="w-8 h-8 text-slate-300"></i>
                                    <p>No hay convenios registrados aún.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Footer / Pagination -->
            @if($empresas->hasPages())
            <div class="px-8 py-4 border-t border-gray-100 mt-auto bg-white">
                {{ $empresas->links() }}
            </div>
            @else
            <div class="px-8 py-4 border-t border-gray-100 mt-auto bg-white flex justify-between items-center">
                <p class="text-sm text-slate-400">Mostrando todos los registros ({{ $empresas->total() }} en total)</p>
            </div>
            @endif
        </div>
    </div>
@endsection
