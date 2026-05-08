@extends('layouts.app')

@section('title', 'Dashboard Principal')

@section('content')
    <div class="flex flex-col max-w-[1400px] w-full mt-2">
        
        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-slate-800 tracking-tight">Dashboard Principal</h1>
            <p class="text-slate-500 mt-1">Resumen general del sistema y actividad reciente.</p>
        </div>

        <!-- KPI Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <!-- Card 1 -->
            <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-sm flex flex-col relative">
                <div class="flex justify-between items-start">
                    <span class="text-slate-400 font-medium text-sm">Facturas Generadas Hoy</span>
                    <div class="w-10 h-10 rounded-xl bg-blue-600 text-white flex items-center justify-center shadow-sm">
                        <i data-lucide="receipt" class="w-5 h-5"></i>
                    </div>
                </div>
                <div class="mt-4">
                    <h3 class="text-4xl font-bold text-slate-800">{{ $facturasHoy }}</h3>
                    <p class="text-green-500 font-medium text-sm mt-2">Facturas del día</p>
                </div>
            </div>

            <!-- Card 2 -->
            <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-sm flex flex-col relative">
                <div class="flex justify-between items-start">
                    <span class="text-slate-400 font-medium text-sm">Pacientes Atendidos</span>
                    <div class="w-10 h-10 rounded-xl bg-blue-600 text-white flex items-center justify-center shadow-sm">
                        <i data-lucide="users" class="w-5 h-5"></i>
                    </div>
                </div>
                <div class="mt-4">
                    <h3 class="text-4xl font-bold text-slate-800">{{ $pacientesTotal }}</h3>
                    <p class="text-green-500 font-medium text-sm mt-2">Registrados en total</p>
                </div>
            </div>

            <!-- Card 3 -->
            <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-sm flex flex-col relative">
                <div class="flex justify-between items-start">
                    <span class="text-slate-400 font-medium text-sm">Exámenes Procesados</span>
                    <div class="w-10 h-10 rounded-xl bg-blue-600 text-white flex items-center justify-center shadow-sm">
                        <i data-lucide="flask-conical" class="w-5 h-5"></i>
                    </div>
                </div>
                <div class="mt-4">
                    <h3 class="text-4xl font-bold text-slate-800">{{ $examenesTotal }}</h3>
                    <p class="text-slate-400 font-medium text-sm mt-2">Pruebas en sistema</p>
                </div>
            </div>
        </div>

        <!-- Dashboard Recepcionista -->
        @if(in_array(strtolower(auth()->user()->rol), ['recepcionista', 'recepcion']))
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-8">
            <!-- Últimos 3 Pacientes -->
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden flex flex-col">
                <div class="p-6 border-b border-gray-100 bg-slate-50/50">
                    <h2 class="text-lg font-bold text-slate-800 flex items-center">
                        <i data-lucide="users" class="w-5 h-5 mr-2 text-blue-600"></i> Últimos 3 Pacientes Nuevos
                    </h2>
                </div>
                <div class="p-4">
                    <div class="space-y-4">
                        @foreach($recentPatients as $paciente)
                        <div class="flex items-center justify-between p-3 bg-white border border-gray-100 rounded-xl hover:shadow-md transition-shadow">
                            <div class="flex flex-col">
                                <span class="font-bold text-slate-800">{{ $paciente->name }} {{ $paciente->apellido }}</span>
                                <span class="text-xs text-slate-500 font-mono">V-{{ $paciente->cedula }}</span>
                            </div>
                            <span class="text-xs font-semibold text-blue-600 bg-blue-50 px-2 py-1 rounded-md">{{ $paciente->created_at->diffForHumans() }}</span>
                        </div>
                        @endforeach
                        @if($recentPatients->isEmpty())
                        <p class="text-sm text-slate-400 text-center py-4">No hay pacientes registrados recientemente.</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Últimos 3 Exámenes -->
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden flex flex-col">
                <div class="p-6 border-b border-gray-100 bg-slate-50/50">
                    <h2 class="text-lg font-bold text-slate-800 flex items-center">
                        <i data-lucide="flask-conical" class="w-5 h-5 mr-2 text-green-600"></i> Últimos 3 Exámenes
                    </h2>
                </div>
                <div class="p-4">
                    <div class="space-y-4">
                        @foreach($recentExams as $examen)
                        <div class="flex items-center justify-between p-3 bg-white border border-gray-100 rounded-xl hover:shadow-md transition-shadow">
                            <div class="flex flex-col">
                                <span class="font-bold text-slate-800">{{ $examen->nombre_del_examen }}</span>
                                <span class="text-xs text-slate-500">ID: {{ str_pad($examen->id_examen, 4, '0', STR_PAD_LEFT) }}</span>
                            </div>
                            <span class="text-xs font-semibold text-green-600 bg-green-50 px-2 py-1 rounded-md">{{ $examen->created_at->diffForHumans() }}</span>
                        </div>
                        @endforeach
                        @if($recentExams->isEmpty())
                        <p class="text-sm text-slate-400 text-center py-4">No hay exámenes registrados recientemente.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Dashboard Bioanalista -->
        @if(in_array(strtolower(auth()->user()->rol), ['bioanalista']))
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden flex flex-col mt-8">
            <div class="p-6 md:p-8 border-b border-gray-100">
                <h2 class="text-xl font-bold text-slate-800">Métricas de Exámenes Realizados</h2>
                <p class="text-sm text-slate-500 mt-1">Gráficos de rendimiento y cantidad de pruebas procesadas.</p>
            </div>
            <div class="p-8 grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Gráfico Día -->
                <div class="flex flex-col items-center">
                    <div class="relative w-32 h-32 flex items-center justify-center rounded-full border-8 border-blue-50 shadow-inner">
                        <svg class="absolute inset-0 w-full h-full transform -rotate-90">
                            <circle cx="64" cy="64" r="56" stroke="currentColor" stroke-width="8" fill="transparent" class="text-blue-500" stroke-dasharray="351.8" stroke-dashoffset="{{ 351.8 - (351.8 * min($examenesHoy / 50, 1)) }}"></circle>
                        </svg>
                        <div class="flex flex-col items-center justify-center">
                            <span class="text-3xl font-black text-slate-800">{{ $examenesHoy }}</span>
                        </div>
                    </div>
                    <span class="mt-4 font-bold text-slate-700">Hoy</span>
                </div>

                <!-- Gráfico Semana -->
                <div class="flex flex-col items-center">
                    <div class="relative w-32 h-32 flex items-center justify-center rounded-full border-8 border-green-50 shadow-inner">
                        <svg class="absolute inset-0 w-full h-full transform -rotate-90">
                            <circle cx="64" cy="64" r="56" stroke="currentColor" stroke-width="8" fill="transparent" class="text-green-500" stroke-dasharray="351.8" stroke-dashoffset="{{ 351.8 - (351.8 * min($examenesSemana / 300, 1)) }}"></circle>
                        </svg>
                        <div class="flex flex-col items-center justify-center">
                            <span class="text-3xl font-black text-slate-800">{{ $examenesSemana }}</span>
                        </div>
                    </div>
                    <span class="mt-4 font-bold text-slate-700">Esta Semana</span>
                </div>

                <!-- Gráfico Mes -->
                <div class="flex flex-col items-center">
                    <div class="relative w-32 h-32 flex items-center justify-center rounded-full border-8 border-purple-50 shadow-inner">
                        <svg class="absolute inset-0 w-full h-full transform -rotate-90">
                            <circle cx="64" cy="64" r="56" stroke="currentColor" stroke-width="8" fill="transparent" class="text-purple-500" stroke-dasharray="351.8" stroke-dashoffset="{{ 351.8 - (351.8 * min($examenesMes / 1000, 1)) }}"></circle>
                        </svg>
                        <div class="flex flex-col items-center justify-center">
                            <span class="text-3xl font-black text-slate-800">{{ $examenesMes }}</span>
                        </div>
                    </div>
                    <span class="mt-4 font-bold text-slate-700">Este Mes</span>
                </div>
            </div>
        </div>
        @endif

        <!-- Recent Activity -->
        @if(in_array(strtolower(auth()->user()->rol), ['admin', 'administrador', 'administrador principal']))
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden flex flex-col mt-8">
            <div class="p-6 md:p-8 border-b border-gray-100">
                <h2 class="text-xl font-bold text-slate-800">Actividad Reciente</h2>
                <p class="text-sm text-slate-500 mt-1">Últimos movimientos realizados en el sistema por los usuarios.</p>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-600">
                    <thead class="bg-slate-50 text-slate-500 font-bold text-xs uppercase tracking-wider border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-4 w-24 text-center">ROL</th>
                            <th class="px-6 py-4 w-48">USUARIO</th>
                            <th class="px-6 py-4 w-32 text-center">TIPO</th>
                            <th class="px-6 py-4 text-center">DESCRIPCIÓN</th>
                            <th class="px-6 py-4 w-48 text-center">HORA / FECHA</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-[15px]">
                        @forelse($recentActivity as $activity)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-6 py-4 text-center">
                                @php
                                    $rolClasses = 'bg-slate-100 text-slate-600';
                                    if(in_array(strtolower($activity->user->rol), ['admin', 'administrador'])) $rolClasses = 'bg-red-50 text-red-500';
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold uppercase {{ $rolClasses }}">
                                    {{ $activity->user->rol }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center space-x-3">
                                    <div class="w-8 h-8 rounded-full bg-blue-50 flex items-center justify-center text-blue-600">
                                        <i data-lucide="user" class="w-4 h-4"></i>
                                    </div>
                                    <div class="font-bold text-slate-800">{{ $activity->user->name }}</div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                @php
                                    $typeClasses = 'bg-slate-100 text-slate-600';
                                    if($activity->type == 'Paciente') $typeClasses = 'bg-blue-50 text-blue-600';
                                    if($activity->type == 'Examen') $typeClasses = 'bg-green-50 text-green-600';
                                    if($activity->type == 'Factura') $typeClasses = 'bg-purple-50 text-purple-600';
                                    if($activity->type == 'Convenio') $typeClasses = 'bg-orange-50 text-orange-600';
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold {{ $typeClasses }}">
                                    {{ $activity->type }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center text-slate-700">{{ $activity->description }}</td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex flex-col text-xs text-slate-500">
                                    <span class="font-bold text-slate-700">{{ $activity->created_at->format('d/m/Y') }}</span>
                                    <span>{{ $activity->created_at->format('h:i A') }}</span>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-10 text-center text-slate-400 italic">
                                No se han registrado actividades recientemente.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($recentActivity->count() > 0)
            <div class="p-4 border-t border-gray-100 text-center">
                <a href="#" class="text-sm font-semibold text-blue-600 hover:text-blue-700">Ver todas las actividades</a>
            </div>
            @endif
        </div>
        @endif
    </div>
@endsection
