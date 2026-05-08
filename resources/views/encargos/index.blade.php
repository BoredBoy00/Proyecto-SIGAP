@extends('layouts.app')
@section('title', 'Exámenes Pendientes')

@section('content')
<style>
    .badge-pendiente  { background:#fef9c3; color:#a16207; }
    .badge-proceso    { background:#dbeafe; color:#1d4ed8; }
    .badge-completado { background:#dcfce7; color:#15803d; }
</style>

<div class="flex flex-col max-w-7xl w-full mx-auto mt-2">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-slate-800 tracking-tight">Exámenes Pendientes</h1>
        <p class="text-slate-500 mt-1">Lista de encargos generados desde facturación, pendientes de procesamiento.</p>
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden flex flex-col">
        <!-- Stats bar -->
        <div class="p-6 border-b border-gray-100 flex flex-wrap gap-4">
            @php
                $pendientes  = $encargos->where('estado', 'Pendiente')->count();
                $enProceso   = $encargos->where('estado', 'En proceso')->count();
                $completados = $encargos->where('estado', 'Completado')->count();
            @endphp
            <span class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-bold badge-pendiente">
                <i data-lucide="clock" class="w-4 h-4"></i> {{ $pendientes }} Pendientes
            </span>
            <span class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-bold badge-proceso">
                <i data-lucide="loader-2" class="w-4 h-4"></i> {{ $enProceso }} En Proceso
            </span>
            <span class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-bold badge-completado">
                <i data-lucide="check-circle" class="w-4 h-4"></i> {{ $completados }} Completados
            </span>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 text-slate-500 font-bold text-xs uppercase tracking-wider border-b border-gray-200">
                    <tr>
                        <th class="px-4 py-3 text-center w-16">ORD.</th>
                        <th class="px-4 py-3">PACIENTE</th>
                        <th class="px-4 py-3">EXÁMENES ENCARGADOS</th>
                        <th class="px-4 py-3 text-center">CREADO POR</th>
                        <th class="px-4 py-3 text-center">FECHA</th>
                        <th class="px-4 py-3 text-center w-40">ESTADO</th>
                        @if(in_array(strtolower(auth()->user()->rol), ['administrador', 'admin', 'administrador principal']))
                        <th class="px-4 py-3 text-center w-24">ACCIONES</th>
                        @endif
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($encargos as $enc)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-4 py-3 text-center">
                            <span class="font-mono text-blue-600 text-xs font-semibold bg-blue-50 px-2 py-1 rounded-md">
                                ORD-{{ str_pad($enc->id_orden, 4, '0', STR_PAD_LEFT) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 font-semibold text-slate-800">{{ $enc->nombre_paciente }}</td>
                        <td class="px-4 py-3 text-slate-600">
                            <div class="flex flex-wrap gap-1">
                                @foreach(explode(', ', $enc->examenes) as $ex)
                                <span class="text-xs bg-indigo-50 text-indigo-700 px-2 py-0.5 rounded-md font-medium">{{ trim($ex) }}</span>
                                @endforeach
                            </div>
                        </td>
                        <td class="px-4 py-3 text-center text-xs text-slate-500">{{ $enc->creado_por ?? '—' }}</td>
                        <td class="px-4 py-3 text-center text-xs text-slate-500">
                            {{ $enc->created_at->format('d/m/Y H:i') }}
                        </td>
                        <td class="px-4 py-3 text-center">
                            @if($enc->estado === 'Completado')
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold badge-completado">
                                    <i data-lucide="check-circle" class="w-3.5 h-3.5"></i> Completado
                                </span>
                            @elseif($enc->estado === 'En proceso')
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold badge-proceso">
                                    <i data-lucide="loader-2" class="w-3.5 h-3.5"></i> En proceso
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold badge-pendiente">
                                    <i data-lucide="clock" class="w-3.5 h-3.5"></i> Pendiente
                                </span>
                            @endif
                        </td>
                        @if(in_array(strtolower(auth()->user()->rol), ['administrador', 'admin', 'administrador principal']))
                        <td class="px-4 py-3 text-center">
                            <button type="button"
                                data-id="{{ $enc->id }}" data-nombre="ORD-{{ str_pad($enc->id_orden, 4, '0', STR_PAD_LEFT) }} — {{ $enc->nombre_paciente }}"
                                onclick="openDelete(this)"
                                class="text-red-500 hover:text-red-700 hover:bg-red-50 p-1.5 rounded-lg hover:ring-4 hover:ring-red-200 transition-all active:scale-90">
                                <i data-lucide="trash-2" class="w-5 h-5"></i>
                            </button>
                        </td>
                        @endif
                    </tr>
                    @empty
                    <tr>
                        <td colspan="{{ in_array(strtolower(auth()->user()->rol), ['administrador', 'admin', 'administrador principal']) ? 7 : 6 }}" class="px-6 py-14 text-center text-slate-400">
                            <div class="flex flex-col items-center space-y-3">
                                <i data-lucide="inbox" class="w-10 h-10 text-slate-300"></i>
                                <p>No hay exámenes pendientes en este momento.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination Footer -->
        <div class="px-8 py-4 border-t border-gray-100 bg-white flex flex-col md:flex-row justify-between items-center gap-4">
            <span class="text-xs font-semibold text-slate-500">Mostrando {{ $encargos->count() }} de {{ $encargos->total() }} encargos</span>
            
            <x-pagination :paginator="$encargos" />
        </div>
    </div>
</div>

@if(in_array(strtolower(auth()->user()->rol), ['administrador', 'admin', 'administrador principal']))
<dialog id="deleteDialog" style="border:none; padding:0; border-radius:1rem; box-shadow:0 25px 50px -12px rgba(0,0,0,.25); max-width:26rem; width:90%; margin:auto;">
    <div style="padding:2rem;text-align:center;background:white;border-radius:1rem;">
        <span style="display:inline-flex;align-items:center;justify-content:center;width:3.5rem;height:3.5rem;border-radius:9999px;background:#fee2e2;margin-bottom:1.25rem;">
            <i data-lucide="trash-2" style="width:1.6rem;height:1.6rem;color:#ef4444;"></i>
        </span>
        <h3 style="font-size:1.05rem;font-weight:700;color:#1e293b;margin:0 0 0.4rem;">¿Eliminar encargo?</h3>
        <p id="deleteNombre" style="font-weight:700;color:#334155;font-size:0.9rem;margin:0 0 1.75rem;"></p>
        <form id="deleteForm" method="POST">
            @csrf @method('DELETE')
            <div style="display:flex;gap:0.75rem;">
                <button type="button" onclick="document.getElementById('deleteDialog').close()"
                    style="flex:1;padding:0.6rem 0;font-size:0.875rem;font-weight:600;color:#475569;background:#f1f5f9;border:none;border-radius:0.5rem;cursor:pointer;"
                    onmouseover="this.style.background='#e2e8f0'" onmouseout="this.style.background='#f1f5f9'">Cancelar</button>
                <button type="submit"
                    style="flex:1;padding:0.6rem 0;font-size:0.875rem;font-weight:700;color:white;background:#ef4444;border:none;border-radius:0.5rem;cursor:pointer;"
                    onmouseover="this.style.background='#dc2626'" onmouseout="this.style.background='#ef4444'">Sí, eliminar</button>
            </div>
        </form>
    </div>
</dialog>

<script>
    function openDelete(btn) {
        document.getElementById('deleteForm').action = '{{ url("encargos") }}/' + btn.dataset.id;
        document.getElementById('deleteNombre').textContent = btn.dataset.nombre;
        document.getElementById('deleteDialog').showModal();
    }
</script>
@endif
@endsection
