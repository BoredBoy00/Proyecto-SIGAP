@extends('layouts.app')
@section('title', 'Resultados de Exámenes')

@section('content')
<style>
    dialog { border:none; padding:0; border-radius:1rem; box-shadow:0 25px 50px -12px rgba(0,0,0,.25); max-width:48rem; width:90%; margin:auto; }
    dialog::backdrop { background:rgba(15,23,42,.6); backdrop-filter:blur(4px); }
    #deleteDialog { max-width:26rem; }
</style>

<div class="flex flex-col max-w-7xl w-full mx-auto mt-2">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-slate-800 tracking-tight">Resultados de Exámenes</h1>
        <p class="text-slate-500 mt-1">Historial de resultados con archivos PDF adjuntos.</p>
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden flex flex-col">
        <div class="p-6 border-b border-gray-100 flex items-center justify-between gap-4">
            <p class="text-sm text-slate-400">{{ $resultados->total() }} resultado(s) registrado(s)</p>
            @if(!$readonly)
            <a href="{{ route('resulexamen.create') }}" class="flex items-center space-x-2 px-5 py-2.5 bg-[#0d6efd] text-white font-medium rounded-lg hover:bg-blue-700 transition-colors shadow-sm">
                <i data-lucide="plus" class="w-4 h-4"></i>
                <span>Nuevo Resultado</span>
            </a>
            @endif
        </div>

        <!-- Controls area -->
        <form action="{{ route('resulexamen.index') }}" method="GET" class="p-6 md:p-8 border-b border-gray-100 flex flex-col md:flex-row items-center justify-between gap-4">
            <!-- Search -->
            <div class="relative w-full md:w-[400px]">
                <i data-lucide="search" class="absolute left-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-slate-400"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Buscar por ID, paciente o examen..." 
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
                    <option value="az" {{ request('sort') == 'az' ? 'selected' : '' }}>Paciente (A-Z)</option>
                    <option value="za" {{ request('sort') == 'za' ? 'selected' : '' }}>Paciente (Z-A)</option>
                </select>
            </div>
        </form>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 text-slate-500 font-bold text-xs uppercase tracking-wider border-b border-gray-200">
                    <tr>
                        <th class="px-4 py-3 w-16 text-center">ID</th>
                        <th class="px-4 py-3">PACIENTE</th>
                        <th class="px-4 py-3">EXAMEN</th>
                        <th class="px-4 py-3">BIOANALISTA</th>
                        <th class="px-4 py-3 text-center">ARCHIVO PDF</th>
                        <th class="px-4 py-3 text-center">FECHA</th>
                        @if(!$readonly)
                        <th class="px-4 py-3 text-center w-32">ACCIONES</th>
                        @endif
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($resultados as $r)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-4 py-3 text-center">
                            <span class="font-mono text-blue-600 text-xs font-semibold bg-blue-50 px-2 py-1 rounded-md">#{{ str_pad($r->id, 4, '0', STR_PAD_LEFT) }}</span>
                        </td>
                        <td class="px-4 py-3 font-semibold text-slate-800">{{ $r->nombre_paciente }}</td>
                        <td class="px-4 py-3 text-slate-600">{{ $r->nombre_examen }}</td>
                        <td class="px-4 py-3 text-slate-500 text-xs">{{ $r->bioanalista ?? '—' }}</td>
                        <td class="px-4 py-3 text-center">
                            @if($r->imagen_path)
                                <a href="{{ asset('storage/' . $r->imagen_path) }}" target="_blank"
                                   class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-bold bg-red-50 text-red-600 hover:bg-red-600 hover:text-white rounded-lg transition-all">
                                    <i data-lucide="file-text" class="w-4 h-4"></i> Ver PDF
                                </a>
                                <a href="{{ asset('storage/' . $r->imagen_path) }}" download
                                   class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-bold bg-slate-100 text-slate-600 hover:bg-slate-700 hover:text-white rounded-lg transition-all ml-1">
                                    <i data-lucide="download" class="w-4 h-4"></i> Descargar
                                </a>
                            @else
                                <span class="text-slate-300 text-xs">Sin archivo</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-center text-xs text-slate-500">
                            {{ \Carbon\Carbon::parse($r->fecha_resultado)->format('d/m/Y H:i') }}
                        </td>
                        @if(!$readonly)
                        <td class="px-4 py-3 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('resulexamen.edit', $r->id) }}"
                                   class="text-blue-500 hover:text-blue-700 hover:bg-blue-50 p-1.5 rounded-lg hover:ring-4 hover:ring-blue-200 transition-all active:scale-90"
                                   title="Editar">
                                    <i data-lucide="edit-2" class="w-5 h-5"></i>
                                </a>
                                @if(in_array(strtolower(auth()->user()->rol), ['administrador', 'admin', 'administrador principal']))
                                <button type="button"
                                    data-id="{{ $r->id }}" data-nombre="{{ $r->nombre_examen }} — {{ $r->nombre_paciente }}"
                                    onclick="openDelete(this)"
                                    class="text-red-500 hover:text-red-700 hover:bg-red-50 p-1.5 rounded-lg hover:ring-4 hover:ring-red-200 transition-all active:scale-90">
                                    <i data-lucide="trash-2" class="w-5 h-5"></i>
                                </button>
                                @endif
                            </div>
                        </td>
                        @endif
                    </tr>
                    @empty
                    <tr>
                        <td colspan="{{ $readonly ? 6 : 7 }}" class="px-6 py-12 text-center text-slate-400">
                            <div class="flex flex-col items-center space-y-3">
                                <i data-lucide="file-search" class="w-10 h-10 text-slate-300"></i>
                                <p>No hay resultados registrados.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination Footer -->
        <div class="px-8 py-4 border-t border-gray-100 bg-white flex flex-col md:flex-row justify-between items-center gap-4">
            <span class="text-xs font-semibold text-slate-500">Mostrando {{ $resultados->count() }} de {{ $resultados->total() }} resultados</span>
            
            <x-pagination :paginator="$resultados" />
        </div>
    </div>
</div>

@if(in_array(strtolower(auth()->user()->rol), ['administrador', 'admin', 'administrador principal']))
<dialog id="deleteDialog">
    <div style="padding:2rem;text-align:center;background:white;border-radius:1rem;">
        <span style="display:inline-flex;align-items:center;justify-content:center;width:3.5rem;height:3.5rem;border-radius:9999px;background:#fee2e2;margin-bottom:1.25rem;">
            <i data-lucide="trash-2" style="width:1.6rem;height:1.6rem;color:#ef4444;"></i>
        </span>
        <h3 style="font-size:1.05rem;font-weight:700;color:#1e293b;margin:0 0 0.4rem;">¿Eliminar resultado?</h3>
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
@endif

<script>
    function openDelete(btn) {
        document.getElementById('deleteForm').action = '{{ url("resulexamen") }}/' + btn.dataset.id;
        document.getElementById('deleteNombre').textContent = btn.dataset.nombre;
        document.getElementById('deleteDialog').showModal();
    }
</script>
@endsection
