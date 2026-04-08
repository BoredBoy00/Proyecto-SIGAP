@extends('layouts.app')

@section('title', 'Registro de Auditoría')

@section('header_left')
    <button class="w-10 h-10 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center hover:bg-blue-100 transition-colors">
        <i data-lucide="arrow-left" class="w-5 h-5"></i>
    </button>
    <h2 class="text-xl font-bold text-slate-800 tracking-tight">Registro de Auditoría</h2>
@endsection

@section('content')
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden flex flex-col mt-4">
        
        <!-- Controls area -->
        <div class="p-6 md:p-8 border-b border-gray-100 flex flex-col md:flex-row gap-4 items-center justify-between">
            <!-- Search -->
            <div class="relative w-full md:w-96">
                <i data-lucide="search" class="absolute left-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-slate-400"></i>
                <input type="text" placeholder="Buscar por usuario, acción o módulo..." 
                       class="w-full pl-9 pr-4 py-2 bg-white border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-400 transition-all text-slate-700 placeholder-slate-400">
            </div>

            <!-- Right Controls -->
            <div class="flex items-center space-x-3 w-full md:w-auto">
                <button class="flex items-center justify-between space-x-2 px-4 py-2 bg-white border border-gray-200 rounded-lg text-sm text-slate-700 hover:bg-gray-50 transition-colors min-w-[120px]">
                    <div class="flex items-center space-x-2">
                        <i data-lucide="calendar" class="w-4 h-4 text-slate-400"></i>
                        <span>Hoy</span>
                    </div>
                    <i data-lucide="chevron-down" class="w-4 h-4 text-slate-400"></i>
                </button>

                <button class="flex items-center justify-between space-x-2 px-4 py-2 bg-white border border-gray-200 rounded-lg text-sm text-slate-700 hover:bg-gray-50 transition-colors min-w-[170px]">
                    <div class="flex items-center space-x-2">
                        <i data-lucide="filter" class="w-4 h-4 text-slate-400"></i>
                        <span>Módulo: Todos</span>
                    </div>
                    <i data-lucide="chevron-down" class="w-4 h-4 text-slate-400"></i>
                </button>

                <button class="flex items-center space-x-2 px-5 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700 transition-colors shadow-sm">
                    <i data-lucide="download" class="w-4 h-4"></i>
                    <span>Exportar Reporte</span>
                </button>
            </div>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-blue-50 text-blue-600 font-medium border-b border-blue-100">
                    <tr>
                        <th class="px-6 py-4 px-8 w-48">Fecha y Hora</th>
                        <th class="px-6 py-4">Usuario</th>
                        <th class="px-6 py-4 w-32">Módulo</th>
                        <th class="px-6 py-4 w-40">Acción</th>
                        <th class="px-6 py-4">Descripción del Evento</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <!-- Row 1 -->
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-6 py-4 px-8">
                            <div class="flex flex-col">
                                <span class="text-slate-800 font-medium">24 Oct 2023</span>
                                <span class="text-slate-400 text-xs">14:32:05</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                                    <i data-lucide="user" class="w-4 h-4"></i>
                                </div>
                                <div class="flex flex-col">
                                    <span class="text-slate-800 font-medium">Dra. Ana López</span>
                                    <span class="text-slate-400 text-xs">Administrador</span>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 font-medium text-slate-700">Clientes</td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700">
                                Creación
                            </span>
                        </td>
                        <td class="px-6 py-4 text-slate-400 text-xs md:text-sm">Registro exitoso del paciente V-12345678<br>(Carlos Silva).</td>
                    </tr>
                    <!-- Row 2 -->
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-6 py-4 px-8">
                            <div class="flex flex-col">
                                <span class="text-slate-800 font-medium">24 Oct 2023</span>
                                <span class="text-slate-400 text-xs">13:15:22</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                                    <i data-lucide="user" class="w-4 h-4"></i>
                                </div>
                                <div class="flex flex-col">
                                    <span class="text-slate-800 font-medium">María Gómez</span>
                                    <span class="text-slate-400 text-xs">Recepcionista</span>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 font-medium text-slate-700">Facturación</td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-700">
                                Actualización
                            </span>
                        </td>
                        <td class="px-6 py-4 text-slate-400 text-xs md:text-sm">Edición de factura #F-001042. Se añadió<br>examen Perfil 20.</td>
                    </tr>
                    <!-- Row 3 -->
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-6 py-4 px-8">
                            <div class="flex flex-col">
                                <span class="text-slate-800 font-medium">24 Oct 2023</span>
                                <span class="text-slate-400 text-xs">11:45:10</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                                    <i data-lucide="user" class="w-4 h-4"></i>
                                </div>
                                <div class="flex flex-col">
                                    <span class="text-slate-800 font-medium">Dra. Ana López</span>
                                    <span class="text-slate-400 text-xs">Administrador</span>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 font-medium text-slate-700">Convenios</td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-700">
                                Eliminación
                            </span>
                        </td>
                        <td class="px-6 py-4 text-slate-400 text-xs md:text-sm">Eliminación del convenio inactivo "Empresa<br>XYZ C.A.".</td>
                    </tr>
                    <!-- Row 4 -->
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-6 py-4 px-8">
                            <div class="flex flex-col">
                                <span class="text-slate-800 font-medium">24 Oct 2023</span>
                                <span class="text-slate-400 text-xs">09:30:00</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                                    <i data-lucide="user" class="w-4 h-4"></i>
                                </div>
                                <div class="flex flex-col">
                                    <span class="text-slate-800 font-medium">José Medina</span>
                                    <span class="text-slate-400 text-xs">Bioanalista</span>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 font-medium text-slate-700">Sistema</td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-700">
                                Autenticación
                            </span>
                        </td>
                        <td class="px-6 py-4 text-slate-400 text-xs md:text-sm">Inicio de sesión exitoso desde la estación<br>LAB-02.</td>
                    </tr>
                    <!-- Row 5 -->
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-6 py-4 px-8">
                            <div class="flex flex-col">
                                <span class="text-slate-800 font-medium">24 Oct 2023</span>
                                <span class="text-slate-400 text-xs">08:15:45</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                                    <i data-lucide="user" class="w-4 h-4"></i>
                                </div>
                                <div class="flex flex-col">
                                    <span class="text-slate-800 font-medium">María Gómez</span>
                                    <span class="text-slate-400 text-xs">Recepcionista</span>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 font-medium text-slate-700">Facturación</td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700">
                                Creación
                            </span>
                        </td>
                        <td class="px-6 py-4 text-slate-400 text-xs md:text-sm">Generación de factura #F-001041 y orden<br>#ORD-5012.</td>
                    </tr>
                    <!-- Row 6 -->
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-6 py-4 px-8">
                            <div class="flex flex-col">
                                <span class="text-slate-800 font-medium">23 Oct 2023</span>
                                <span class="text-slate-400 text-xs">17:50:33</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                                    <i data-lucide="user" class="w-4 h-4"></i>
                                </div>
                                <div class="flex flex-col">
                                    <span class="text-slate-800 font-medium">Dra. Ana López</span>
                                    <span class="text-slate-400 text-xs">Administrador</span>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 font-medium text-slate-700">Exámenes</td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-700">
                                Actualización
                            </span>
                        </td>
                        <td class="px-6 py-4 text-slate-400 text-xs md:text-sm">Ajuste de precio base para "Hematología<br>Completa" a $15.</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Footer / Pagination -->
        <div class="px-8 py-4 border-t border-gray-100 flex items-center justify-between mt-auto">
            <p class="text-sm text-slate-400">Mostrando 1 a 6 de 142 registros</p>
            <div class="flex items-center space-x-1">
                <button class="w-8 h-8 flex items-center justify-center rounded border border-gray-200 text-slate-400 hover:bg-gray-50 disabled:opacity-50 text-sm">
                    <i data-lucide="chevron-left" class="w-4 h-4"></i>
                </button>
                <button class="w-8 h-8 flex items-center justify-center rounded bg-blue-600 text-white text-sm font-medium shadow-sm">1</button>
                <button class="w-8 h-8 flex items-center justify-center rounded border border-transparent text-slate-600 hover:bg-gray-50 text-sm font-medium">2</button>
                <button class="w-8 h-8 flex items-center justify-center rounded border border-transparent text-slate-600 hover:bg-gray-50 text-sm font-medium">3</button>
                <span class="flex items-center justify-center px-1 text-slate-400 text-sm font-medium">...</span>
                <button class="w-8 h-8 flex items-center justify-center rounded border border-transparent text-slate-600 hover:bg-gray-50 text-sm font-medium">24</button>
                <button class="w-8 h-8 flex items-center justify-center rounded border border-gray-200 text-slate-600 hover:bg-gray-50 text-sm">
                    <i data-lucide="chevron-right" class="w-4 h-4"></i>
                </button>
            </div>
        </div>
    </div>
@endsection
