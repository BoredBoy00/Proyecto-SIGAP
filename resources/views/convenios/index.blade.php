@extends('layouts.app')

@section('title', 'Gestión de Convenios')

@section('content')
    <style>
        .toast-container {
            position: fixed;
            bottom: 2rem;
            left: 50%;
            transform: translateX(-50%);
            z-index: 9999;
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
            pointer-events: none;
        }

        .toast-container > div {
            pointer-events: auto;
        }

        @keyframes toast-slide-up {
            0% { transform: translateY(100%); opacity: 0; }
            100% { transform: translateY(0); opacity: 1; }
        }
        
        dialog {
            border: none;
            padding: 0;
            border-radius: 1rem;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            max-width: 48rem;
            width: 90%;
            margin: auto;
        }

        dialog::backdrop {
            background: rgba(15, 23, 42, 0.6);
            backdrop-filter: blur(4px);
        }

        #deleteDialog {
            max-width: 26rem;
        }
    </style>

    @push('modals')
    @endpush

    <div class="flex flex-col max-w-[1400px] w-full mt-2 mx-auto">
        
        <!-- Header -->
        <div class="mb-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h1 class="text-3xl font-bold text-slate-800 dark:text-slate-100 tracking-tight">Gestión de Convenios</h1>
                <p class="text-slate-400 dark:text-slate-500 mt-1">Gestiona los convenios con proveedores y clientes.</p>
            </div>
            <button class="flex items-center space-x-2 px-5 py-2.5">
                <a href="{{ route('convens.create') }}" class="flex items-center space-x-2 px-5 py-2.5 bg-[#0d6efd] text-white font-medium rounded-lg hover:bg-blue-700 transition-colors shadow-sm">
                    <i data-lucide="plus" class="w-4 h-4"></i>
                    <span>Nuevo Convenio</span>
                </a>
            </button>
        </div>

        <!-- Main Card -->
        <div class="bg-white dark:bg-slate-900/50 rounded-2xl border border-gray-100 dark:border-slate-800 shadow-sm overflow-hidden flex flex-col">
            
            <!-- Controls area -->
            <form action="{{ route('convens.index') }}" method="GET" class="p-6 md:p-8 border-b border-gray-100 dark:border-slate-800 flex flex-col md:flex-row items-center justify-between gap-4">
                <!-- Search -->
                <div class="relative w-full md:w-[400px]">
                    <i data-lucide="search" class="absolute left-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-slate-400"></i>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Buscar por código, RIF o nombre de la empresa..." 
                           class="w-full pl-9 pr-4 py-2 bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-lg text-sm focus:outline-none focus:ring-1 focus:ring-blue-400 focus:border-blue-400 transition-all text-slate-700 dark:text-slate-200 placeholder-slate-400">
                </div>

                <!-- Right Control -->
                <div class="relative min-w-[200px] w-full md:w-auto">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                        <i data-lucide="list-filter" class="w-4 h-4 text-slate-500"></i>
                    </div>
                    <select name="sort" onchange="this.form.submit()" class="w-full text-center pl-10 pr-4 py-2 bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-lg text-sm text-slate-700 dark:text-slate-200 hover:bg-gray-50 dark:hover:bg-slate-700 transition-colors focus:outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400 appearance-none cursor-pointer" style="text-align-last: center;">
                        <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Más nuevos</option>
                        <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Más viejos</option>
                        <option value="az" {{ request('sort') == 'az' ? 'selected' : '' }}>Nombre (A-Z)</option>
                        <option value="za" {{ request('sort') == 'za' ? 'selected' : '' }}>Nombre (Z-A)</option>
                    </select>
                </div>
            </form>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-600 dark:text-slate-400 table-auto border-separate border-spacing-0">
                    <thead class="bg-slate-50 dark:bg-slate-800/50 text-slate-500 dark:text-slate-400 font-bold text-[11px] uppercase tracking-wider border-b border-slate-100 dark:border-slate-800">
                        <tr>
                            <th class="px-6 py-4 w-32 border-b border-slate-100 dark:border-slate-800 text-center">CÓDIGO</th>
                            <th class="px-6 py-4 w-48 border-b border-slate-100 dark:border-slate-800 text-center">RIF</th>
                            <th class="px-6 py-4 border-b border-slate-100 dark:border-slate-800 text-center">NOMBRE DE LA EMPRESA</th>
                            <th class="px-6 py-4 w-32 text-center border-b border-slate-100 dark:border-slate-800">DIRECCIÓN</th>
                            <th class="px-6 py-4 w-40 text-center border-b border-slate-100 dark:border-slate-800">% DE DESCUENTO</th>
                            <th class="px-6 py-4 w-28 text-center border-b border-slate-100 dark:border-slate-800">ACCIONES</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-slate-800 text-[15px]">
                        @forelse($empresas as $empresa)
                        <tr id="item-{{ $empresa->id_empresa }}" class="hover:bg-blue-50/50 dark:hover:bg-blue-900/10 transition-colors group {{ (request('highlight') == $empresa->id_empresa || request('highlight') == $empresa->rif) ? 'highlight-flash' : '' }}">
                            <td class="px-6 py-4 text-center">
                                <span class="bg-blue-50/50 dark:bg-blue-900/30 font-mono text-blue-600 dark:text-blue-400 text-xs font-semibold px-2.5 py-1 rounded-md">#{{ str_pad($empresa->id_empresa, 4, '0', STR_PAD_LEFT) }}</span>
                            </td>
                            <td class="px-6 py-4 font-semibold text-slate-800 dark:text-slate-200 text-center">{{ $empresa->rif }}</td>
                            <td class="px-6 py-4 font-semibold text-slate-800 dark:text-slate-200 text-center">{{ $empresa->nombre_empresa }}</td>
                            <td class="px-6 py-4 text-center text-slate-600 dark:text-slate-400">{{ $empresa->direccion }}</td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700">
                                    {{ $empresa->porcentaje_descuento }}%
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-center space-x-2"> 

                                    <!-- Editar -->
                                    <a href="{{ route('convens.edit', $empresa->id_empresa) }}" class="text-blue-500 hover:text-blue-700 hover:bg-blue-50 p-1.5 rounded-lg hover:ring-4 hover:ring-blue-200 transition-all active:scale-90 cursor-pointer flex items-center justify-center">
                                        <i data-lucide="edit-2" class="w-5 h-5"></i>
                                    </a>

                                    <!-- Eliminar -->
                                    <button type="button" data-id="{{ $empresa->id_empresa }}" data-nombre="{{ $empresa->nombre_empresa }}" onclick="openDelete(this)" class="text-red-500 hover:text-red-700 hover:bg-red-50 p-1.5 rounded-lg hover:ring-4 hover:ring-red-200 transition-all active:scale-90 cursor-pointer flex items-center justify-center">
                                        <i data-lucide="trash-2" class="w-5 h-5"></i>
                                    </button>
                                    
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

            <!-- Pagination Footer -->
            <div class="px-8 py-4 border-t border-gray-100 dark:border-slate-800 bg-white dark:bg-transparent flex flex-col md:flex-row justify-between items-center gap-4">
                <span class="text-xs font-semibold text-slate-500 dark:text-slate-400">Mostrando {{ $empresas->count() }} de {{ $empresas->total() }} convenios</span>
                
                <x-pagination :paginator="$empresas" />
            </div>
        </div>
    </div>

    <dialog id="deleteDialog" style="max-width:30rem;overflow:visible;">
        <div style="padding:2rem;text-align:center;">
            <span style="display:inline-flex;align-items:center;justify-content:center;width:3.5rem;height:3.5rem;border-radius:9999px;background:#fee2e2;margin-bottom:1.25rem;">
                <i data-lucide="trash-2" style="width:1.6rem;height:1.6rem;color:#ef4444;"></i>
            </span>
            <h3 style="font-size:1.05rem;font-weight:700;color:#1e293b;margin:0 0 0.4rem;">¿Eliminar convenio?</h3>
            <p style="font-size:0.85rem;color:#64748b;margin:0 0 0.25rem;">Estás a punto de eliminar:</p>
            <p id="deleteNombre" style="font-weight:700;color:#334155;font-size:0.95rem;margin:0 0 1.75rem;"></p>
            <form id="deleteForm" method="POST">
                @csrf
                @method('DELETE')
                <div style="display:flex;gap:0.75rem;">
                    <button type="button" onclick="document.getElementById('deleteDialog').close()"
                        style="flex:1;padding:0.6rem 0;font-size:0.875rem;font-weight:600;color:#475569;background:#f1f5f9;border:none;border-radius:0.5rem;cursor:pointer;"
                        onmouseover="this.style.background='#e2e8f0'" onmouseout="this.style.background='#f1f5f9'">
                        Cancelar
                    </button>
                    <button type="submit"
                        style="flex:1;padding:0.6rem 0;font-size:0.875rem;font-weight:700;color:white;background:#ef4444;border:none;border-radius:0.5rem;cursor:pointer;"
                        onmouseover="this.style.background='#dc2626'" onmouseout="this.style.background='#ef4444'">
                        Sí, eliminar
                    </button>
                </div>
            </form>
        </div>
    </dialog>

    <script>
        function openDelete(btn) {
            document.getElementById('deleteForm').action = '/convenios/' + btn.dataset.id;
            document.getElementById('deleteNombre').textContent = btn.dataset.nombre;
            document.getElementById('deleteDialog').showModal();
        }
    </script>
@endsection
