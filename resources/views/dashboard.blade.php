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
                            <th class="px-6 py-4 w-24 text-center">ROL</th>
                            <th class="px-6 py-4 w-48">USUARIO</th>
                            <th class="px-6 py-4 w-32 text-center">TIPO</th>
                            <th class="px-6 py-4 text-center">DESCRIPCIÓN</th>
                            <th class="px-6 py-4 w-32 text-center">ACCIONES</th>
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
                                @php
                                    $targetName = match(strtolower($activity->type)) {
                                        'paciente' => 'Pacientes',
                                        'examen' => 'Exámenes',
                                        'convenio' => 'Convenios',
                                        'usuario' => 'Usuarios',
                                        default => 'Detalle'
                                    };
                                    $viewRoute = match(strtolower($activity->type)) {
                                        'paciente' => route('patients.look'),
                                        'examen' => route('exams.index'),
                                        'convenio' => route('convens.index'),
                                        'usuario' => route('users.index'),
                                        default => '#'
                                    };
                                @endphp
                                @if($viewRoute !== '#' && !str_contains($activity->description, 'Eliminó'))
                                    <a href="{{ $viewRoute . '?highlight=' . $activity->identifier }}" 
                                       class="inline-flex items-center justify-center px-4 py-1.5 bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white rounded-xl font-bold text-[11px] whitespace-nowrap transition-all duration-300 group shadow-sm hover:shadow-md hover:shadow-blue-500/20">
                                        <span>Ver {{ $targetName }}</span>
                                        <i data-lucide="arrow-right" class="w-3.5 h-3.5 ml-1.5 opacity-70 group-hover:opacity-100 group-hover:translate-x-1 transition-all"></i>
                                    </a>
                                @else
                                    <span class="inline-flex items-center justify-center px-3 py-1.5 bg-gray-50 text-slate-400 rounded-xl font-bold text-[10px] whitespace-nowrap border border-dashed border-gray-200">
                                        No Disponible
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex flex-col text-xs text-slate-500">
                                    <span class="font-bold text-slate-700">{{ $activity->created_at->format('d/m/Y') }}</span>
                                    <span>{{ $activity->created_at->format('h:i A') }}</span>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-10 text-center text-slate-400 italic">
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
