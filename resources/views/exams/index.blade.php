@extends('layouts.app')

@section('title', 'Catálogo de Exámenes')

@section('content')
    <style>
        input:focus,
        button:focus {
            outline: none !important;
            box-shadow: none !important;
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

        dialog input:not(.price-input):hover {
            border-color: #93c5fd !important;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15) !important;
        }

        dialog .price-group:hover {
            border-color: #93c5fd !important;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15) !important;
        }
    </style>

    @push('modals')
    @endpush

    <div class="flex flex-col max-w-[1400px] w-full mt-2 mx-auto">
        <div class="mb-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h1 class="text-3xl font-bold text-slate-800 tracking-tight">Catálogo de Exámenes</h1>
                <p class="text-slate-400 mt-1 font-medium">Gestiona la lista de pruebas clínicas y precios oficiales.</p>
            </div>
            <div class="flex items-center space-x-4">
                <div class="flex items-center space-x-4 px-6 py-3 bg-[#f1f5f9] border-2 border-blue-200 rounded-2xl shadow-sm">
                    <div
                        class="w-10 h-10 rounded-xl bg-white flex items-center justify-center text-blue-500 shadow-sm border-2 border-blue-400">
                        <i data-lucide="trending-up" class="w-5 h-5"></i>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-[11px] uppercase font-extrabold text-[#1e293b] leading-tight tracking-wider">TASA
                            BCV</span>
                        <span class="text-base font-bold text-blue-700 leading-tight">Bs.
                            {{ number_format($tasaBcv, 2, ',', '.') }}</span>
                    </div>
                </div>
                @if(!in_array(strtolower(auth()->user()->rol), ['bioanalista']))
                <button type="button" onclick="openCreate()"
                    class="flex items-center space-x-2 px-5 py-2.5 bg-[#0d6efd] text-white font-medium rounded-lg hover:bg-blue-700 hover:ring-4 hover:ring-blue-100 active:scale-95 transition-all shadow-sm cursor-pointer">
                    <i data-lucide="plus" class="w-4 h-4"></i>
                    <span>Nuevo Examen</span>
                </button>
                @endif
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden flex flex-col">
            <!-- Controls area -->
            <form action="{{ route('exams.index') }}" method="GET" class="p-6 md:p-8 border-b border-gray-100 flex flex-col md:flex-row items-center justify-between gap-4">
                <!-- Search -->
                <div class="relative w-full md:w-[400px]">
                    <i data-lucide="search" class="absolute left-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-slate-400"></i>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Buscar por código o nombre del examen..." 
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
                        <option value="az" {{ request('sort') == 'az' ? 'selected' : '' }}>Nombre (A-Z)</option>
                        <option value="za" {{ request('sort') == 'za' ? 'selected' : '' }}>Nombre (Z-A)</option>
                    </select>
                </div>
            </form>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-600 table-auto border-separate border-spacing-0">
                    <thead
                        class="bg-[#f1f7ff] text-slate-500 font-bold text-[11px] uppercase tracking-wider border-b border-blue-100">
                        <tr>
                            <th class="px-6 py-4 w-32 text-center border-b border-blue-100">CÓDIGO</th>
                            <th class="px-6 py-4 text-center border-b border-blue-100">NOMBRE DEL EXAMEN</th>
                            <th class="px-6 py-4 w-40 text-center border-b border-blue-100">PRECIO (USD)</th>
                            <th class="px-6 py-4 w-48 text-center border-b border-blue-100">PRECIO (VES)</th>
                            <th class="px-6 py-4 w-44 text-center border-b border-blue-100">TIEMPO ENTREGA</th>
                            <th class="px-6 py-4 w-32 text-center border-b border-blue-100">ACCIONES</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-[15px]" id="examTableBody">
                        @forelse($examenes as $examen)
                            <tr id="item-{{ $examen->id_examen }}"
                                class="hover:bg-gray-50/50 transition-colors exam-row {{ (request('highlight') == $examen->id_examen || request('highlight') == $examen->codigo_examen) ? 'highlight-flash' : '' }}">
                                <td class="px-6 py-4 text-center">
                                    <span
                                        class="bg-blue-50/50 font-mono text-blue-600 text-xs font-semibold px-2.5 py-1 rounded-md">{{ $examen->codigo_examen }}</span>
                                </td>
                                <td class="px-6 py-4 font-semibold text-slate-800 text-center exam-name">
                                    {{ $examen->nombre_examen }}</td>
                                <td class="px-6 py-4 font-bold text-slate-800 text-center">
                                    {{ '$' . number_format($examen->precio_actual, 2) }}</td>
                                <td class="px-6 py-4 font-bold text-blue-600 text-center">Bs.
                                    {{ number_format($examen->precio_actual * $tasaBcv, 2, ',', '.') }}</td>
                                <td class="px-6 py-4 text-center font-bold text-slate-700">
                                    {{ $examen->tiempo_entrega ?? 'N/A' }}</td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-center space-x-3">
                                        @if(!in_array(strtolower(auth()->user()->rol), ['bioanalista']))
                                        <button data-id="{{ $examen->id_examen }}" data-codigo="{{ $examen->codigo_examen }}"
                                            data-nombre="{{ $examen->nombre_examen }}"
                                            data-precio="{{ $examen->precio_actual }}"
                                            data-tiempo="{{ $examen->tiempo_entrega }}" onclick="openEdit(this)"
                                            class="text-blue-500 hover:text-blue-700 hover:bg-blue-50 p-1.5 rounded-lg hover:ring-4 hover:ring-blue-200 transition-all active:scale-90 cursor-pointer">
                                            <i data-lucide="edit-2" class="w-4 h-4"></i>
                                        </button>
                                        <button data-id="{{ $examen->id_examen }}" data-nombre="{{ $examen->nombre_examen }}"
                                            onclick="openDelete(this)"
                                            class="text-red-500 hover:text-red-700 hover:bg-red-50 p-1.5 rounded-lg hover:ring-4 hover:ring-red-200 transition-all active:scale-90 cursor-pointer">
                                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                                        </button>
                                        @else
                                        <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-semibold text-slate-500 bg-slate-100">
                                            <i data-lucide="eye" class="w-3.5 h-3.5 mr-1"></i> Solo lectura
                                        </span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-16 text-center">
                                    <div class="flex flex-col items-center justify-center space-y-2">
                                        <div
                                            class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mb-2 border border-gray-100">
                                            <i data-lucide="inbox" class="w-8 h-8 text-slate-300"></i>
                                        </div>
                                        <p class="text-slate-500 font-medium text-base">No hay exámenes registrados</p>
                                        <p class="text-slate-400 text-sm">Haz clic en "Nuevo Examen" para comenzar.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination Footer -->
            <div class="px-8 py-4 border-t border-gray-100 bg-white flex flex-col md:flex-row justify-between items-center gap-4">
                <span class="text-xs font-semibold text-slate-500">Mostrando {{ $examenes->count() }} de {{ $examenes->total() }} exámenes</span>
                
                <x-pagination :paginator="$examenes" />
            </div>
        </div>
    </div>

    <dialog id="deleteDialog" style="max-width:30rem;overflow:visible;">
        <div style="padding:2rem;text-align:center;">
            <span
                style="display:inline-flex;align-items:center;justify-content:center;width:3.5rem;height:3.5rem;border-radius:9999px;background:#fee2e2;margin-bottom:1.25rem;">
                <i data-lucide="trash-2" style="width:1.6rem;height:1.6rem;color:#ef4444;"></i>
            </span>
            <h3 style="font-size:1.05rem;font-weight:700;color:#1e293b;margin:0 0 0.4rem;">¿Eliminar examen?</h3>
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

    <dialog id="examDialog">
        <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
            <h3 id="dialogTitle" class="text-lg font-bold text-slate-800">Nuevo Examen</h3>
            <button type="button" onclick="document.getElementById('examDialog').close()"
                class="text-slate-400 hover:text-slate-600 transition-colors cursor-pointer p-1">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>
        <form id="examForm" method="POST" action="{{ route('exams.store') }}" class="p-6">
            @csrf
            <input type="hidden" id="methodField" name="_method" value="PATCH" disabled>
            <div class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="md:col-span-1">
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Código</label>
                        <input type="text" id="f_codigo" name="codigo_examen" required maxlength="15"
                            placeholder="Ej: HEM-01"
                            class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:border-blue-500 transition-all uppercase text-slate-700 font-medium">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Nombre del Examen</label>
                        <input type="text" id="f_nombre" name="nombre_examen" required maxlength="50"
                            placeholder="Ej: Hematología Completa"
                            class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:border-blue-500 transition-all text-slate-700 font-medium">
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Tiempo</label>
                        <input type="text" id="f_tiempo" name="tiempo_entrega" required maxlength="4"
                            placeholder="Ej: 24h o 2d" oninput="this.value = this.value.replace(/[^0-9hdm]/g, '')"
                            class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:border-blue-500 transition-all text-slate-700 font-medium">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Precio (USD)</label>
                        <div
                            class="price-group flex items-center bg-white border border-gray-200 rounded-lg overflow-hidden">
                            <div class="flex-none bg-gray-50 px-3 py-2 text-slate-400 font-bold">$</div>
                            <input type="number" step="0.01" id="f_usd" name="precio_actual" required max="1000"
                                placeholder="0.00" oninput="calcVes(this.value,'f_ves')"
                                class="price-input grow px-3 py-2 bg-transparent border-none outline-none text-slate-700 font-bold"
                                spellcheck="false">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Equivalente (VES)</label>
                        <div
                            class="price-group flex items-center bg-blue-50/30 border border-gray-200 rounded-lg overflow-hidden transition-all">
                            <div class="flex-none bg-blue-50 px-3 py-2 text-blue-600 font-bold">Bs.</div>
                            <input type="text" id="f_ves" placeholder="0,00" oninput="calcUsd(this.value,'f_usd')"
                                class="price-input grow px-3 py-2 bg-transparent border-none outline-none text-blue-700 font-bold"
                                spellcheck="false">
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-8 flex justify-end space-x-3">
                <button type="button" onclick="document.getElementById('examDialog').close()"
                    class="px-4 py-2 text-sm font-medium text-slate-700 hover:bg-gray-100 rounded-lg transition-colors cursor-pointer">Cancelar</button>
                <button type="submit" id="submitBtn"
                    class="px-6 py-2 bg-blue-600 text-white text-sm font-bold rounded-lg hover:bg-blue-700 hover:ring-4 hover:ring-blue-100 active:scale-95 transition-all shadow-md shadow-blue-200 cursor-pointer">Guardar</button>
            </div>
        </form>
    </dialog>

    <script>
        const tasaBcv = {{ $tasaBcv }};

        function openCreate() {
            const form = document.getElementById('examForm');
            form.reset();
            form.action = "{{ route('exams.store') }}";
            document.getElementById('methodField').disabled = true;
            document.getElementById('dialogTitle').textContent = 'Nuevo Examen';
            document.getElementById('submitBtn').textContent = 'Guardar';
            document.getElementById('examDialog').showModal();
        }

        function openEdit(btn) {
            const form = document.getElementById('examForm');
            form.action = '/examenes/' + btn.dataset.id;
            document.getElementById('methodField').disabled = false;
            document.getElementById('dialogTitle').textContent = 'Editar Examen';
            document.getElementById('submitBtn').textContent = 'Actualizar';
            document.getElementById('f_codigo').value = btn.dataset.codigo;
            document.getElementById('f_nombre').value = btn.dataset.nombre;
            document.getElementById('f_tiempo').value = btn.dataset.tiempo || '';
            document.getElementById('f_usd').value = btn.dataset.precio;
            calcVes(btn.dataset.precio, 'f_ves');
            document.getElementById('examDialog').showModal();
        }

        function openDelete(btn) {
            document.getElementById('deleteForm').action = '/examenes/' + btn.dataset.id;
            document.getElementById('deleteNombre').textContent = btn.dataset.nombre;
            document.getElementById('deleteDialog').showModal();
        }

        function calcVes(usd, id) {
            const n = parseFloat(usd);
            document.getElementById(id).value = n > 0 ? (n * tasaBcv).toFixed(2).replace('.', ',') : '';
        }

        function calcUsd(ves, id) {
            const n = parseFloat(ves.replace(',', '.'));
            document.getElementById(id).value = n > 0 ? (n / tasaBcv).toFixed(2) : '';
        }


    </script>
@endsection