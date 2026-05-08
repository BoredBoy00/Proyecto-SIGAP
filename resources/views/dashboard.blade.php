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

        <!-- Recent Activity -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden flex flex-col">
            <div class="p-6 md:p-8 border-b border-gray-100">
                <h2 class="text-xl font-bold text-slate-800">Actividad Reciente</h2>
                <p class="text-sm text-slate-500 mt-1">Últimos movimientos realizados en el sistema por los usuarios.</p>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-600">
                    <thead class="bg-slate-50 text-slate-500 font-bold text-xs uppercase tracking-wider border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-4 w-48">USUARIO</th>
                            <th class="px-6 py-4 w-32">TIPO</th>
                            <th class="px-6 py-4">DESCRIPCIÓN</th>
                            <th class="px-6 py-4 w-32">IDENTIFICADOR</th>
                            <th class="px-6 py-4 w-48">HORA / FECHA</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-[15px]">
                        @forelse($recentActivity as $activity)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center space-x-3">
                                    <div class="w-8 h-8 rounded-full bg-blue-50 flex items-center justify-center text-blue-600">
                                        <i data-lucide="user" class="w-4 h-4"></i>
                                    </div>
                                    <div class="font-bold text-slate-800">{{ $activity->user->name }}</div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
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
                            <td class="px-6 py-4 text-slate-700">{{ $activity->description }}</td>
                            <td class="px-6 py-4 font-medium text-slate-500">{{ $activity->identifier ?? 'N/A' }}</td>
                            <td class="px-6 py-4 text-slate-500">{{ $activity->created_at->format('d/m/Y H:i A') }}</td>
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
    </div>
@endsection
