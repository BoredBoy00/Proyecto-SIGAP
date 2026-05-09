@extends('layouts.app')

@section('title', 'Gestión de Pacientes')

@section('content')
<div class="flex flex-col max-w-7xl w-full mx-auto mt-2">

    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-slate-800 dark:text-slate-100 tracking-tight">Gestión de Pacientes</h1>
        <p class="text-slate-500 dark:text-slate-400 mt-1">Administra los registros de pacientes individuales o realiza búsquedas.</p>
    </div>

    <!-- Nav Pills -->
    <div class="flex items-center space-x-3 mb-6">
        <a href="{{ route('patients.create') }}"
           class="inline-flex items-center px-5 py-2.5 text-sm font-bold rounded-xl border border-gray-200 dark:border-slate-700 text-slate-600 dark:text-slate-300 bg-white dark:bg-slate-800 hover:text-blue-600 hover:border-blue-200 hover:shadow-sm transition-all">
            <i data-lucide="user-plus" class="w-4 h-4 mr-2"></i>
            Registro Individual
        </a>
        <a href="{{ route('patients.look') }}"
           class="inline-flex items-center px-5 py-2.5 text-sm font-bold rounded-xl bg-blue-600 text-white shadow-md shadow-blue-500/20 hover:bg-blue-700 transition-all">
            <i data-lucide="users" class="w-4 h-4 mr-2"></i>
            Ver Pacientes
        </a>
    </div>

    <!-- Main Card -->
    <div class="bg-white dark:bg-slate-900/50 rounded-2xl border border-gray-100 dark:border-slate-800 shadow-sm overflow-hidden flex flex-col">

        <!-- Search + Controls -->
        <div class="p-6 border-b border-gray-100 dark:border-slate-800 flex flex-col md:flex-row items-center justify-between gap-4">
            <form method="GET" action="{{ route('patients.look') }}" class="relative w-full md:w-[360px]">
                <i data-lucide="search" class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400"></i>
                <input type="text" name="search" value="{{ $search ?? '' }}"
                       placeholder="Buscar por nombre, apellido o cédula..."
                       class="w-full pl-9 pr-4 py-2.5 bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-lg text-sm focus:outline-none focus:ring-1 focus:ring-blue-400 focus:border-blue-400 transition-all text-slate-700 dark:text-slate-200 placeholder-slate-400">
            </form>
            <span class="text-sm text-slate-400 dark:text-slate-500">Total: <span class="font-semibold text-slate-700 dark:text-slate-300">{{ $patients->total() }}</span> paciente(s)</span>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600 dark:text-slate-400">
                <thead class="bg-slate-50 dark:bg-slate-800 text-slate-500 dark:text-slate-400 font-bold text-xs uppercase tracking-wider border-b border-gray-200 dark:border-slate-700">
                    <tr>
                        <th class="px-4 py-3">NOMBRE</th>
                        <th class="px-4 py-3">APELLIDO</th>
                        <th class="px-4 py-3 text-center">CÉDULA</th>
                        <th class="px-4 py-3 text-center">NACIMIENTO</th>
                        <th class="px-4 py-3 text-center">TELÉFONO</th>
                        <th class="px-4 py-3">CORREO</th>
                        <th class="px-4 py-3 text-center w-24">ACCIONES</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-slate-800">
                    @forelse($patients as $patient)
                    <tr id="item-{{ $patient->id }}" class="hover:bg-gray-50/50 dark:hover:bg-slate-800/50 transition-colors {{ (request('highlight') == $patient->id || request('highlight') == $patient->cedula) ? 'highlight-flash' : '' }}">
                        <td class="px-4 py-3 font-semibold text-slate-800 dark:text-slate-200">{{ $patient->nombre }}</td>
                        <td class="px-4 py-3 dark:text-slate-300">{{ $patient->apellido }}</td>
                        <td class="px-4 py-3 text-center font-medium dark:text-slate-300">{{ $patient->cedula }}</td>
                        <td class="px-4 py-3 text-center text-slate-500 dark:text-slate-400">
                            {{ $patient->fecha_nacimiento ? \Carbon\Carbon::parse($patient->fecha_nacimiento)->format('d/m/Y') : '—' }}
                        </td>
                        <td class="px-4 py-3 text-center">{{ $patient->telefono }}</td>
                        <td class="px-4 py-3 text-slate-500">{{ $patient->email }}</td>
                        <td class="px-4 py-3">
                            <div class="flex items-center justify-center space-x-2">
                                <!-- Editar -->
                                <a href="{{ route('patients.edit', $patient->id) }}"
                                   class="text-blue-500 hover:text-blue-700 hover:bg-blue-50 p-1.5 rounded-lg hover:ring-4 hover:ring-blue-200 transition-all active:scale-90 flex items-center justify-center"
                                   title="Editar paciente">
                                    <i data-lucide="edit-2" class="w-5 h-5"></i>
                                </a>
                                <!-- Eliminar -->
                                <button type="button"
                                    data-id="{{ $patient->id }}"
                                    data-nombre="{{ $patient->nombre }} {{ $patient->apellido }}"
                                    onclick="openDelete(this)"
                                    class="text-red-500 hover:text-red-700 hover:bg-red-50 p-1.5 rounded-lg hover:ring-4 hover:ring-red-200 transition-all active:scale-90 flex items-center justify-center"
                                    title="Eliminar paciente">
                                    <i data-lucide="trash-2" class="w-5 h-5"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-slate-400 italic">
                            No se encontraron pacientes registrados.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination Footer -->
        <div class="px-6 py-4 border-t border-gray-100 dark:border-slate-800 bg-white dark:bg-transparent flex flex-col md:flex-row justify-between items-center gap-4">
            <span class="text-xs font-semibold text-slate-500 dark:text-slate-400">Mostrando {{ $patients->count() }} de {{ $patients->total() }} pacientes</span>
            
            <x-pagination :paginator="$patients" />
        </div>
    </div>
</div>

<!-- Delete Dialog -->
<dialog id="deleteDialog" style="max-width:26rem;overflow:visible;border:none;padding:0;border-radius:1rem;box-shadow:0 25px 50px -12px rgba(0,0,0,.25);width:90%;margin:auto;">
    <div style="padding:2rem;text-align:center;background:white;border-radius:1rem;">
        <span style="display:inline-flex;align-items:center;justify-content:center;width:3.5rem;height:3.5rem;border-radius:9999px;background:#fee2e2;margin-bottom:1.25rem;">
            <i data-lucide="trash-2" style="width:1.6rem;height:1.6rem;color:#ef4444;"></i>
        </span>
        <h3 style="font-size:1.05rem;font-weight:700;color:#1e293b;margin:0 0 0.4rem;">¿Eliminar paciente?</h3>
        <p style="font-size:0.85rem;color:#64748b;margin:0 0 0.25rem;">Estás a punto de eliminar:</p>
        <p id="deleteNombre" style="font-weight:700;color:#334155;font-size:0.95rem;margin:0 0 1.75rem;"></p>
        <form id="deleteForm" method="POST">
            @csrf
            @method('DELETE')
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
        document.getElementById('deleteForm').action = '{{ url("pacientes") }}/' + btn.dataset.id;
        document.getElementById('deleteNombre').textContent = btn.dataset.nombre;
        document.getElementById('deleteDialog').showModal();
    }
</script>
@endsection
