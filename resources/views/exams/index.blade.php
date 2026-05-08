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

    <div class="flex flex-col max-w-[1400px] w-full mt-2 mx-auto">
        <div class="mb-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h1 class="text-3xl font-bold text-slate-800 tracking-tight">Catálogo de Exámenes</h1>
                <p class="text-slate-400 mt-1 font-medium">Gestiona la lista de pruebas clínicas y precios oficiales.</p>
            </div>
            <div class="flex items-center space-x-4">
                <div class="flex items-center space-x-4 px-6 py-3 bg-[#f1f5f9] border border-blue-100 rounded-2xl">
                    <div class="w-10 h-10 rounded-xl bg-white flex items-center justify-center text-blue-500 shadow-sm border border-blue-50">
                        <i data-lucide="trending-up" class="w-5 h-5"></i>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-[11px] uppercase font-extrabold text-[#1e293b] leading-tight tracking-wider">TASA BCV</span>
                        <span class="text-base font-bold text-blue-700 leading-tight">Bs. {{ number_format($tasaBcv, 2, ',', '.') }}</span>
                    </div>
                </div>
                <button type="button" onclick="openCreate()"
                    class="flex items-center space-x-2 px-5 py-2.5 bg-[#198754] text-white font-medium rounded-lg hover:bg-green-700 hover:ring-4 hover:ring-green-100 active:scale-95 transition-all shadow-sm cursor-pointer">
                    <i data-lucide="plus" class="w-4 h-4"></i>
                    <span>Nuevo Examen</span>
                </button>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden flex flex-col">
            <div class="p-6 md:p-8 border-b border-gray-100 flex items-center gap-4">
                <div class="relative w-full md:w-[450px]">
                    <i data-lucide="search" class="absolute left-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-slate-400"></i>
                    <input type="text" id="examSearch" placeholder="Buscar por código o nombre del examen"
                        class="w-full pl-9 pr-4 py-2.5 bg-white border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-blue-400 transition-all text-slate-700 placeholder-slate-400 font-medium">
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-600 table-auto border-separate border-spacing-0">
                    <thead class="bg-[#f1f7ff] text-slate-500 font-bold text-[11px] uppercase tracking-wider border-b border-blue-100">
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
                            <tr class="hover:bg-gray-50/50 transition-colors exam-row">
                                <td class="px-6 py-4 text-center">
                                    <span class="bg-blue-50/50 font-mono text-blue-600 text-xs font-semibold px-2.5 py-1 rounded-md">{{ $examen->codigo_examen }}</span>
                                </td>
                                <td class="px-6 py-4 font-semibold text-slate-800 text-center exam-name">{{ $examen->nombre_examen }}</td>
                                <td class="px-6 py-4 font-bold text-slate-800 text-center">{{ '$' . number_format($examen->precio_actual, 2) }}</td>
                                <td class="px-6 py-4 font-medium text-slate-500 text-center">Bs. {{ number_format($examen->precio_actual * $tasaBcv, 2, ',', '.') }}</td>
                                <td class="px-6 py-4 text-center font-bold text-slate-700">{{ $examen->tiempo_entrega ?? 'N/A' }}</td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-center space-x-3">
                                        <button data-id="{{ $examen->id_examen }}" data-codigo="{{ $examen->codigo_examen }}"
                                            data-nombre="{{ $examen->nombre_examen }}"
                                            data-precio="{{ $examen->precio_actual }}"
                                            data-tiempo="{{ $examen->tiempo_entrega }}" onclick="openEdit(this)"
                                            class="w-8 h-8 flex items-center justify-center bg-blue-50 text-blue-500 hover:text-blue-700 hover:bg-blue-100 rounded-lg transition-colors cursor-pointer">
                                            <i data-lucide="edit-2" class="w-4 h-4"></i>
                                        </button>
                                        
                                        <!-- Eliminar -->
                                        <form id="delete-exam-{{ $examen->id_examen }}" action="/examenes/{{ $examen->id_examen }}" method="POST" class="m-0">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" onclick="confirmDelete('delete-exam-{{ $examen->id_examen }}', '{{ $examen->nombre_examen }}')" class="w-8 h-8 flex items-center justify-center bg-red-50 text-red-500 hover:text-red-700 hover:bg-red-100 rounded-lg transition-colors cursor-pointer">
                                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-16 text-center">
                                    <div class="flex flex-col items-center justify-center space-y-2">
                                        <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mb-2 border border-gray-100">
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
        </div>
    </div>



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
                        <input type="text" id="f_codigo" name="codigo_examen" required maxlength="15" placeholder="Ej: HEM-01"
                            class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:border-blue-500 transition-all uppercase text-slate-700 font-medium">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Nombre del Examen</label>
                        <input type="text" id="f_nombre" name="nombre_examen" required maxlength="50" placeholder="Ej: Hematología Completa"
                            class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:border-blue-500 transition-all text-slate-700 font-medium">
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Tiempo</label>
                        <input type="text" id="f_tiempo" name="tiempo_entrega" maxlength="50" placeholder="Ej: 24 horas"
                            class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:border-blue-500 transition-all text-slate-700 font-medium">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Precio (USD)</label>
                        <div class="price-group flex items-center bg-white border border-gray-200 rounded-lg overflow-hidden">
                            <div class="flex-none bg-gray-50 px-3 py-2 text-slate-400 font-bold">$</div>
                            <input type="number" step="0.01" id="f_usd" name="precio_actual" required max="1000" placeholder="0.00"
                                oninput="calcVes(this.value,'f_ves')"
                                class="price-input grow px-3 py-2 bg-transparent border-none outline-none text-slate-700 font-bold" spellcheck="false">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Equivalente (VES)</label>
                        <div class="price-group flex items-center bg-blue-50/30 border border-gray-200 rounded-lg overflow-hidden transition-all">
                            <div class="flex-none bg-blue-50 px-3 py-2 text-blue-600 font-bold">Bs.</div>
                            <input type="text" id="f_ves" placeholder="0,00" oninput="calcUsd(this.value,'f_usd')"
                                class="price-input grow px-3 py-2 bg-transparent border-none outline-none text-blue-700 font-bold" spellcheck="false">
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



        function calcVes(usd, id) {
            const n = parseFloat(usd);
            document.getElementById(id).value = n > 0 ? (n * tasaBcv).toFixed(2).replace('.', ',') : '';
        }

        function calcUsd(ves, id) {
            const n = parseFloat(ves.replace(',', '.'));
            document.getElementById(id).value = n > 0 ? (n / tasaBcv).toFixed(2) : '';
        }

        document.getElementById('examSearch').addEventListener('input', function (e) {
            const q = e.target.value.toLowerCase();
            let count = 0;
            document.querySelectorAll('.exam-row').forEach(row => {
                const match = row.innerText.toLowerCase().includes(q);
                row.classList.toggle('hidden', !match);
                if (match) count++;
            });
            const info = document.getElementById('tableInfo');
            if (info) info.innerText = `Total: ${count} exámenes registrados`;
        });
    </script>
@endsection