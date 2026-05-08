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
                    <div class="flex flex-col space-y-3">
                        @foreach($recentPatients as $paciente)
                        <a href="{{ route('patients.look') }}" class="flex items-center justify-between p-3 bg-white border border-gray-100 rounded-xl hover:shadow-md hover:border-blue-200 hover:bg-blue-50/10 transition-all group">
                            <div class="flex flex-col">
                                <span class="font-bold text-slate-800 group-hover:text-blue-600 transition-colors">{{ $paciente->nombre }} {{ $paciente->apellido }}</span>
                                <span class="text-xs text-slate-500 font-mono">{{ str_starts_with(strtoupper($paciente->cedula), 'V-') ? $paciente->cedula : 'V-' . $paciente->cedula }}</span>
                            </div>
                            <span class="text-xs font-semibold text-blue-600 bg-blue-50 px-2 py-1 rounded-md">Ver Perfil</span>
                        </a>
                        @endforeach
                        @if($recentPatients->isEmpty())
                        <p class="text-sm text-slate-400 text-center py-4">No hay pacientes registrados recientemente.</p>
                        @endif
                    </div>

                    {{-- Pagination for Patients --}}
                    <div class="mt-6 flex justify-center">
                        <x-pagination :paginator="$recentPatients" />
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
                    <div class="flex flex-col space-y-3">
                        @foreach($recentExams as $examen)
                        <a href="{{ route('exams.index') }}" class="flex items-center justify-between p-3 bg-white border border-gray-100 rounded-xl hover:shadow-md hover:border-green-200 hover:bg-green-50/10 transition-all group">
                            <div class="flex flex-col">
                                <span class="font-bold text-slate-800 group-hover:text-green-600 transition-colors">{{ $examen->examen->nombre_examen }}</span>
                                <span class="text-xs text-slate-500">Paciente: {{ $examen->orden->paciente->nombre ?? 'N/A' }}</span>
                            </div>
                            <span class="text-xs font-semibold text-green-600 bg-green-50 px-2 py-1 rounded-md">Ver Examen</span>
                        </a>
                        @endforeach
                        @if($recentExams->isEmpty())
                        <p class="text-sm text-slate-400 text-center py-4">No hay exámenes registrados recientemente.</p>
                        @endif
                    </div>

                    {{-- Pagination for Exams --}}
                    <div class="mt-6 flex justify-center">
                        <x-pagination :paginator="$recentExams" />
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Dashboard Bioanalista -->
        @if(in_array(strtolower(auth()->user()->rol), ['bioanalista']))
        <div class="mt-8">
            <div class="mb-6">
                <h2 class="text-2xl font-bold text-slate-800">Panel de Rendimiento</h2>
                <p class="text-slate-500">Exámenes procesados por periodos de tiempo.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Hoy -->
                <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-sm flex flex-col justify-between min-h-[160px]">
                    <div class="flex justify-between items-start">
                        <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
                            <i data-lucide="clock" class="w-6 h-6"></i>
                        </div>
                        <span class="text-[10px] font-black uppercase tracking-widest text-indigo-400 bg-indigo-50 px-2 py-1 rounded-md">Hoy</span>
                    </div>
                    <div class="mt-4">
                        <div class="text-4xl font-bold text-slate-800">{{ $examenesHoy }}</div>
                        <div class="text-sm font-medium text-slate-400 mt-1 uppercase tracking-tight">Exámenes Realizados</div>
                    </div>
                </div>

                <!-- Semana -->
                <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-sm flex flex-col justify-between min-h-[160px]">
                    <div class="flex justify-between items-start">
                        <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                            <i data-lucide="calendar" class="w-6 h-6"></i>
                        </div>
                        <span class="text-[10px] font-black uppercase tracking-widest text-emerald-400 bg-emerald-50 px-2 py-1 rounded-md">Semana</span>
                    </div>
                    <div class="mt-4">
                        <div class="text-4xl font-bold text-slate-800">{{ $examenesSemana }}</div>
                        <div class="text-sm font-medium text-slate-400 mt-1 uppercase tracking-tight">Pruebas Procesadas</div>
                    </div>
                </div>

                <!-- Mes -->
                <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-sm flex flex-col justify-between min-h-[160px]">
                    <div class="flex justify-between items-start">
                        <div class="w-12 h-12 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center">
                            <i data-lucide="trending-up" class="w-6 h-6"></i>
                        </div>
                        <span class="text-[10px] font-black uppercase tracking-widest text-purple-400 bg-purple-50 px-2 py-1 rounded-md">Mes</span>
                    </div>
                    <div class="mt-4">
                        <div class="text-4xl font-bold text-slate-800">{{ $examenesMes }}</div>
                        <div class="text-sm font-medium text-slate-400 mt-1 uppercase tracking-tight">Volumen Mensual</div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Recent Activity -->
        @if(in_array(strtolower(auth()->user()->rol), ['admin', 'administrador', 'administrador principal', 'recepcionista', 'recepcion']))
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
                            <th class="px-6 py-4 w-32 text-center">ACCIONES</th>
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
                                                  <td class="px-6 py-4 text-center">
                                 @php
                                     $isDeletion = str_contains(strtolower($activity->description), 'eliminó') || 
                                                  str_contains(strtolower($activity->description), 'borró') || 
                                                  str_contains(strtolower($activity->description), 'eliminado');
                                     
                                     $route = '#';
                                     $btnText = 'Ver Detalle';
                                     $btnClass = 'bg-white border-gray-200 text-slate-700 hover:bg-slate-50 hover:border-blue-300 hover:text-blue-600';
                                     $iconColor = 'text-slate-400';

                                     if (!$isDeletion) {
                                         if($activity->type == 'Paciente') { $route = route('patients.look'); $btnText = 'Ver Paciente'; }
                                         elseif($activity->type == 'Examen') { $route = route('exams.index'); $btnText = 'Ver Examen'; }
                                         elseif($activity->type == 'Factura') { $route = route('facturacion.index'); $btnText = 'Ver Factura'; }
                                         elseif($activity->type == 'Convenio') { $route = route('convens.index'); $btnText = 'Ver Convenio'; }
                                     } else {
                                         $btnText = 'No disponible';
                                         $btnClass = 'bg-slate-50 border-slate-200 text-slate-400 cursor-not-allowed opacity-70';
                                         $iconColor = 'text-slate-300';
                                     }
                                 @endphp

                                 @if($isDeletion)
                                     <div class="inline-flex items-center px-3 py-1.5 border {{ $btnClass }} rounded-lg text-xs font-bold shadow-sm">
                                         <i data-lucide="eye-off" class="w-3.5 h-3.5 mr-1.5 {{ $iconColor }}"></i>
                                         {{ $btnText }}
                                     </div>
                                 @else
                                     <a href="{{ $route }}" class="inline-flex items-center px-3 py-1.5 bg-white border {{ $btnClass }} rounded-lg text-xs font-bold transition-all shadow-sm group">
                                         <i data-lucide="eye" class="w-3.5 h-3.5 mr-1.5 {{ $iconColor }} group-hover:text-blue-500"></i>
                                         {{ $btnText }}
                                     </a>
                                 @endif
                             </td>        </td>
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
            
            <div class="px-6 py-4 bg-white border-t border-gray-100 flex justify-center">
                <x-pagination :paginator="$recentActivity" />
            </div>
        </div>
        @endif
    </div>
@endsection
