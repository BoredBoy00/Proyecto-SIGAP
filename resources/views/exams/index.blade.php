@extends('layouts.app')

@section('title', 'Catálogo de Exámenes')

@section('content')
    <div class="flex flex-col max-w-[1400px] w-full mt-2">
        
        <!-- Header -->
        <div class="mb-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h1 class="text-3xl font-bold text-slate-800 tracking-tight">Catálogo de Exámenes</h1>
                <p class="text-slate-400 mt-1">Gestiona la lista de pruebas clínicas, precios base y tiempos de entrega.</p>
            </div>
            <button class="flex items-center space-x-2 px-5 py-2.5 bg-[#198754] text-white font-medium rounded-lg hover:bg-green-700 transition-colors shadow-sm">
                <i data-lucide="plus" class="w-4 h-4"></i>
                <span>Nuevo Examen</span>
            </button>
        </div>

        <!-- Main Card -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden flex flex-col">
            
            <!-- Controls area -->
            <div class="p-6 md:p-8 border-b border-gray-100 flex items-center justify-between gap-4">
                <!-- Search -->
                <div class="relative w-full md:w-[400px]">
                    <i data-lucide="search" class="absolute left-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-slate-400"></i>
                    <input type="text" placeholder="Buscar por código o nombre del examen..." 
                           class="w-full pl-9 pr-4 py-2 bg-white border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-1 focus:ring-blue-400 focus:border-blue-400 transition-all text-slate-700 placeholder-slate-400">
                </div>

                <!-- Right Control -->
                <button class="flex items-center justify-between space-x-2 px-4 py-2 bg-white border border-gray-200 rounded-lg text-sm text-slate-700 hover:bg-gray-50 transition-colors min-w-[200px]">
                    <div class="flex items-center space-x-2">
                        <i data-lucide="filter" class="w-4 h-4 text-slate-400"></i>
                        <span>Categoría: Todas</span>
                    </div>
                    <i data-lucide="chevron-down" class="w-4 h-4 text-slate-400"></i>
                </button>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-600">
                    <thead class="bg-blue-50 text-slate-500 font-bold text-xs uppercase tracking-wider border-b border-blue-100">
                        <tr>
                            <th class="px-6 py-4 w-32">CÓDIGO</th>
                            <th class="px-6 py-4">NOMBRE DEL EXAMEN</th>
                            <th class="px-6 py-4 w-48">CATEGORÍA</th>
                            <th class="px-6 py-4 w-32 text-center">PRECIO (USD)</th>
                            <th class="px-6 py-4 w-40 text-center">TIEMPO ENTREGA</th>
                            <th class="px-6 py-4 w-32text-center">ACCIONES</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-[15px]">
                        @foreach($examenes as $examen)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-6 py-4">
                                <span class="bg-blue-50/50 font-mono text-slate-400 text-xs font-semibold px-2.5 py-1 rounded-md">
                                    {{ $examen->codigo_examen }}
                                </span>
                            </td>
                            <td class="px-6 py-4 font-semibold text-slate-800">{{ $examen->nombre_examen }}</td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gray-50 text-gray-600">
                                    General
                                </span>
                            </td>
                            <td class="px-6 py-4 font-semibold text-slate-800 text-center">${{ $examen->precio_actual }}</td>
                            <td class="px-6 py-4 font-medium text-slate-800 text-center">N/A</td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-center space-x-2">
                                    <button class="w-8 h-8 rounded-full flex items-center justify-center text-blue-500 hover:bg-blue-50 transition-colors border border-blue-100">
                                        <i data-lucide="edit-2" class="w-4 h-4"></i>
                                    </button>
                                    <button class="w-8 h-8 rounded-full flex items-center justify-center text-red-500 hover:bg-red-50 transition-colors border border-red-100">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Footer / Pagination -->
            <div class="px-8 py-4 border-t border-gray-100 flex items-center justify-between mt-auto bg-white">
                <p class="text-sm text-slate-400">Mostrando 1 a 6 de 124 exámenes</p>
                <div class="flex items-center space-x-1">
                    <button class="w-8 h-8 flex items-center justify-center rounded border border-gray-200 text-slate-400 hover:bg-gray-50 disabled:opacity-50 text-sm">
                        <i data-lucide="chevron-left" class="w-4 h-4"></i>
                    </button>
                    <button class="w-8 h-8 flex items-center justify-center rounded bg-blue-600 text-white text-sm font-medium shadow-sm">1</button>
                    <button class="w-8 h-8 flex items-center justify-center rounded border border-transparent text-slate-600 hover:bg-gray-50 text-sm font-medium">2</button>
                    <button class="w-8 h-8 flex items-center justify-center rounded border border-transparent text-slate-600 hover:bg-gray-50 text-sm font-medium">3</button>
                    <span class="flex items-center justify-center px-1 text-slate-400 text-sm font-medium">...</span>
                    <button class="w-8 h-8 flex items-center justify-center rounded border border-transparent text-slate-600 hover:bg-gray-50 text-sm font-medium">21</button>
                    <button class="w-8 h-8 flex items-center justify-center rounded border border-gray-200 text-slate-600 hover:bg-gray-50 text-sm">
                        <i data-lucide="chevron-right" class="w-4 h-4"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection
