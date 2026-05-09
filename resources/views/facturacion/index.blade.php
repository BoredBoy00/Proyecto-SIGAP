@extends('layouts.app')

@section('title', 'Gestión de Facturación')

@section('content')
    <div class="flex flex-col max-w-7xl w-full mt-2 mx-auto">
        
        <!-- Header -->
        <div class="mb-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h1 class="text-3xl font-bold text-slate-800 dark:text-slate-100 tracking-tight">Facturación</h1>
                <p class="text-slate-400 dark:text-slate-500 mt-1">Consulta y gestión de las facturas generadas en el sistema.</p>
            </div>
            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 sm:gap-0 sm:space-x-4 w-full md:w-auto">
                <div class="flex items-center space-x-4 px-6 py-3 bg-[#f1f5f9] dark:bg-slate-800 border-2 border-blue-200 dark:border-blue-900/40 rounded-2xl shadow-sm">
                    <div class="w-10 h-10 rounded-xl bg-white dark:bg-slate-700 flex items-center justify-center text-blue-500 dark:text-blue-400 shadow-sm border-2 border-blue-400 dark:border-blue-700">
                        <i data-lucide="trending-up" class="w-5 h-5"></i>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-[11px] uppercase font-extrabold text-[#1e293b] dark:text-slate-300 leading-tight tracking-wider">TASA BCV</span>
                        <span class="text-base font-bold text-blue-700 dark:text-blue-400 leading-tight">Bs. {{ number_format($tasaBcv, 2, ',', '.') }}</span>
                    </div>
                </div>
                <a href="{{ route('facturacion.create') }}" class="flex items-center space-x-2 px-5 py-2.5 bg-[#0d6efd] text-white font-medium rounded-lg hover:bg-blue-700 hover:ring-4 hover:ring-blue-100 active:scale-95 transition-all shadow-sm cursor-pointer">
                    <i data-lucide="plus" class="w-4 h-4"></i>
                    <span>Nueva Factura</span>
                </a>
            </div>
        </div>

        <!-- Main Card -->
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-gray-100 dark:border-slate-800 shadow-sm overflow-hidden flex flex-col">
            
            <!-- Controls area -->
            <form action="{{ route('facturacion.index') }}" method="GET" class="p-6 md:p-8 border-b border-gray-100 dark:border-slate-800 flex flex-col md:flex-row items-center justify-between gap-4">
                <!-- Search -->
                <div class="relative w-full md:w-[400px]">
                    <i data-lucide="search" class="absolute left-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-slate-400"></i>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Buscar por ID factura, orden o tipo de pago..." 
                           class="w-full pl-9 pr-4 py-2 bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-lg text-sm focus:outline-none focus:ring-1 focus:ring-blue-400 focus:border-blue-400 transition-all text-slate-700 dark:text-slate-200 placeholder-slate-400 dark:placeholder-slate-500">
                </div>

                <!-- Right Control -->
                <div class="relative min-w-[200px] w-full md:w-auto">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                        <i data-lucide="list-filter" class="w-4 h-4 text-slate-500"></i>
                    </div>
                    <select name="sort" onchange="this.form.submit()" class="w-full text-center pl-10 pr-4 py-2 bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-lg text-sm text-slate-700 dark:text-slate-200 hover:bg-gray-50 dark:hover:bg-slate-700 transition-colors focus:outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400 appearance-none cursor-pointer" style="text-align-last: center;">
                        <option value="fecha_emision" {{ request('sort') == 'fecha_emision' ? 'selected' : '' }}>Fecha de Emisión</option>
                        <option value="id_facturacion" {{ request('sort') == 'id_facturacion' ? 'selected' : '' }}>ID de Facturación</option>
                        <option value="monto_total" {{ request('sort') == 'monto_total' ? 'selected' : '' }}>Monto Total</option>
                    </select>
                </div>
            </form>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-600 dark:text-slate-400">
                    <thead class="bg-blue-50 dark:bg-blue-900/20 text-slate-500 dark:text-slate-400 font-bold text-xs uppercase tracking-wider border-b border-blue-100 dark:border-blue-900/30">
                        <tr>
                            <th class="px-4 py-3 w-28 text-center border-b border-blue-100 dark:border-blue-900/30">ID ORDEN</th>
                            <th class="px-4 py-3 text-center border-b border-blue-100 dark:border-blue-900/30">TIPO DE PAGO</th>
                            <th class="px-4 py-3 text-center border-b border-blue-100 dark:border-blue-900/30">SUBTOTAL</th>
                            <th class="px-4 py-3 text-center border-b border-blue-100 dark:border-blue-900/30">DESCUENTO</th>
                            <th class="px-4 py-3 text-center border-b border-blue-100 dark:border-blue-900/30">MONTO TOTAL</th>
                            <th class="px-4 py-3 text-center border-b border-blue-100 dark:border-blue-900/30">FECHA DE EMISIÓN</th>
                            <th class="px-4 py-3 text-center w-36 border-b border-blue-100 dark:border-blue-900/30">ACTIONS</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-slate-800 text-[15px]">
                        @forelse($facturas as $factura)
                        <tr id="item-{{ $factura->id_facturacion }}" class="hover:bg-blue-50/50 dark:hover:bg-blue-900/10 transition-colors group {{ request('highlight') == $factura->id_facturacion ? 'highlight-flash' : '' }}">
                            <td class="px-4 py-3 text-center font-bold text-slate-800 dark:text-slate-100">ORD-{{ str_pad($factura->id_orden, 4, '0', STR_PAD_LEFT) }}</td>
                            <td class="px-4 py-3 text-center text-slate-600 dark:text-slate-400">{{ $factura->tipo_pago ?? 'No especificado' }}</td>
                            <td class="px-4 py-3 text-center font-medium">
                                ${{ number_format($factura->subtotal, 2) }}<br>
                                <span class="text-xs text-slate-400">Bs.{{ number_format($factura->subtotal * $tasaBcv, 2) }}</span>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <span class="text-green-600 font-medium">${{ number_format($factura->monto_descuento, 2) }}</span><br>
                                <span class="text-xs text-green-500/70 font-medium">Bs.{{ number_format($factura->monto_descuento * $tasaBcv, 2) }}</span>
                            </td>
                            <td class="px-4 py-3 text-center font-bold text-slate-800 dark:text-slate-100">
                                ${{ number_format($factura->monto_total, 2) }}<br>
                                <span class="text-xs text-slate-500 dark:text-slate-400 font-medium">Bs.{{ number_format($factura->monto_total * $tasaBcv, 2) }}</span>
                            </td>
                            <td class="px-4 py-3 text-center text-slate-600 dark:text-slate-400">
                                {{ \Carbon\Carbon::parse($factura->fecha_emision)->format('d/m/Y h:i A') }}
                            </td>
                            <td class="px-4 py-3 text-center">
                                <div class="flex items-center justify-center space-x-2">
                                    <button type="button" 
                                            data-factura="{{ base64_encode(json_encode($factura)) }}"
                                            onclick="openPrintModal(this)"
                                            class="text-indigo-500 hover:text-indigo-700 hover:bg-indigo-50 p-1.5 rounded-lg hover:ring-4 hover:ring-indigo-200 transition-all active:scale-90 cursor-pointer flex items-center justify-center" title="Imprimir Factura">
                                        <i data-lucide="printer" class="w-5 h-5"></i>
                                    </button>
                                    @if(in_array(strtolower(auth()->user()->rol), ['admin', 'administrador', 'administrador principal', 'recepcionista']))
                                        <button type="button" 
                                                onclick="openDelete({{ $factura->id_facturacion }}, 'ORD-{{ str_pad($factura->id_orden, 4, '0', STR_PAD_LEFT) }}')"
                                                class="text-red-500 hover:text-red-700 hover:bg-red-50 dark:hover:bg-red-900/20 p-1.5 rounded-lg hover:ring-4 hover:ring-red-200 dark:hover:ring-red-900/30 transition-all active:scale-90 cursor-pointer flex items-center justify-center" title="Eliminar Factura">
                                            <i data-lucide="trash-2" class="w-5 h-5"></i>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-6 py-10 text-center text-slate-500">
                                <div class="flex flex-col items-center justify-center space-y-3">
                                    <i data-lucide="receipt" class="w-10 h-10 text-slate-300"></i>
                                    <p>No se encontraron registros de facturación.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Footer / Pagination -->
            @if($facturas->hasPages())
            <div class="px-8 py-4 border-t border-gray-100 dark:border-slate-800 mt-auto bg-white dark:bg-transparent">
                {{ $facturas->links() }}
            </div>
            @else
            <div class="px-8 py-4 border-t border-gray-100 dark:border-slate-800 mt-auto bg-white dark:bg-transparent flex justify-between items-center">
                <p class="text-sm text-slate-400 dark:text-slate-500">Mostrando todos los registros ({{ $facturas->total() }} en total)</p>
            </div>
            @endif
        </div>
    </div>

    <!-- Modal Imprimir Factura -->
    <div id="printModal" class="fixed inset-0 z-[100] flex items-center justify-center hidden bg-slate-900/60 backdrop-blur-sm transition-opacity px-4 py-6">
        <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-xl w-full max-w-sm mx-auto overflow-hidden flex flex-col border border-gray-100 dark:border-slate-800" style="max-height: 80vh;">


            <!-- Print Content -->
            <div class="p-5 overflow-y-auto overflow-x-auto flex flex-col flex-1 min-h-0 text-[13px] leading-tight" id="printArea">
                <style>
                    @media print {
                        @page { margin: 0; size: A4 portrait; }
                        body * { visibility: hidden; }
                        #printArea, #printArea * { visibility: visible; }
                        #printArea { position: absolute; left: 0; top: 0; width: 100%; height: 140mm; padding: 20px; display: flex; flex-direction: column; overflow: hidden; }
                        .print-text-gray { color: #475569 !important; }
                        .print-text-black { color: #000 !important; }
                        .print-border { border-color: #e2e8f0 !important; border-bottom-width: 1px !important; border-bottom-style: solid !important; }
                        .print-mt-auto { margin-top: auto !important; }
                    }
                </style>

                <div class="text-center mb-4">
                    <h2 class="text-xl font-black text-slate-800 dark:text-slate-100 print-text-black">CLÍNICA REMARLI</h2>
                    <p class="text-xs text-slate-500 dark:text-slate-400 print-text-gray mt-1">Rif: J-12345678-9</p>
                    <p class="text-xs text-slate-500 dark:text-slate-400 print-text-gray">Av. Principal, Caracas, Venezuela</p>
                    <p class="text-xs text-slate-500 dark:text-slate-400 print-text-gray">Tel: 0414-1234567</p>
                </div>

                <div class="flex justify-between items-end mb-4 pb-3 border-b border-gray-200 print-border">
                    <div>
                        <p class="text-xs font-bold text-slate-700 print-text-black">Factura a:</p>
                        <p class="font-semibold text-slate-800 print-text-gray" id="printPacienteNombre">Nombre del Paciente</p>
                        <p class="text-xs text-slate-500 print-text-gray" id="printPacienteCedula">V-00000000</p>
                    </div>
                    <div class="text-right">
                        <p class="text-xs font-bold text-slate-700 print-text-black">Factura N°:</p>
                        <p class="font-mono font-semibold text-blue-600 print-text-gray" id="printFacturaId">#00000</p>
                        <p class="text-xs text-slate-500 print-text-gray" id="printFecha">00/00/0000</p>
                    </div>
                </div>

                <div class="mb-4">
                    <table class="w-full text-xs text-left whitespace-nowrap">
                        <thead class="border-b border-gray-200 print-border text-slate-600 print-text-black">
                            <tr>
                                <th class="py-2 pr-4">Descripción (Exámenes)</th>
                                <th class="py-2 text-right">Importe</th>
                            </tr>
                        </thead>
                        <tbody id="printExamenesList" class="text-slate-700 print-text-black divide-y divide-gray-100 print-border">
                            <!-- Inyectados por JS -->
                        </tbody>
                    </table>
                </div>

                <div class="pt-4 mt-4 text-left">
                    <div class="w-full space-y-2">
                        <div class="flex justify-between text-sm text-slate-600 print-text-gray">
                            <span>Subtotal:</span>
                            <span id="printSubtotal">$0.00</span>
                        </div>
                        <div class="flex justify-between text-sm text-green-600 print-text-gray">
                            <span>Descuento:</span>
                            <span id="printDescuento">-$0.00</span>
                        </div>
                        <div class="flex justify-between text-lg font-bold text-slate-800 print-text-black pt-2 border-t border-gray-100">
                            <span>Total (USD):</span>
                            <span id="printTotal">$0.00</span>
                        </div>
                        <div class="flex justify-between text-sm font-semibold text-slate-500 print-text-gray">
                            <span>Total (VES):</span>
                            <span id="printTotalVES">Bs. 0.00</span>
                        </div>
                    </div>
                </div>

                <div class="mt-8 text-center text-[11px] text-slate-400 dark:text-slate-500 print-text-gray print-mt-auto">
                    <p>Método de Pago: <span id="printMetodoPago" class="font-semibold text-slate-600 dark:text-slate-300 print-text-black">Efectivo</span></p>
                    <p class="mt-2 font-medium text-xs">¡Gracias por su confianza en REMARLI!</p>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="px-6 py-4 border-t border-gray-100 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/50 flex justify-end space-x-3 shrink-0">
                <button onclick="closePrintModal()" class="px-5 py-2.5 text-sm font-medium text-slate-600 dark:text-slate-300 bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-lg hover:bg-gray-50 dark:hover:bg-slate-700 transition-colors">
                    Cancelar
                </button>
                <button onclick="executePrint()" class="flex items-center px-5 py-2.5 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-colors shadow-sm shadow-blue-500/30">
                    <i data-lucide="printer" class="w-4 h-4 mr-2"></i>
                    Confirmar e Imprimir
                </button>
            </div>
            
        </div>
    </div>

    <!-- Delete Dialog -->
    <dialog id="deleteDialog" class="max-w-[30rem] overflow-visible border-none p-0 rounded-2xl shadow-2xl w-[90%] m-auto bg-white dark:bg-slate-900 border dark:border-slate-800">
        <div class="p-8 text-center bg-white dark:bg-slate-900 rounded-2xl">
            <span class="inline-flex items-center justify-center w-14 h-14 rounded-full bg-red-100 dark:bg-red-900/30 mb-5">
                <i data-lucide="trash-2" class="w-6 h-6 text-red-600 dark:text-red-400"></i>
            </span>
            <h3 class="text-xl font-bold text-slate-800 dark:text-slate-100 mb-2">¿Eliminar Factura?</h3>
            <p class="text-sm text-slate-500 dark:text-slate-400 mb-1">Estás a punto de eliminar la factura:</p>
            <p id="deleteFactura" class="font-bold text-slate-700 dark:text-slate-200 text-base mb-7"></p>
            <form id="deleteForm" method="POST">
                @csrf
                @method('DELETE')
                <div class="flex gap-3">
                    <button type="button" onclick="document.getElementById('deleteDialog').close()"
                        class="flex-1 py-2.5 text-sm font-semibold text-slate-600 dark:text-slate-300 bg-slate-100 dark:bg-slate-800 rounded-lg cursor-pointer hover:bg-slate-200 dark:hover:bg-slate-700 transition-colors">
                        Cancelar
                    </button>
                    <button type="submit"
                        class="flex-1 py-2.5 text-sm font-bold text-white bg-red-600 rounded-lg cursor-pointer hover:bg-red-700 transition-colors shadow-lg shadow-red-500/30">
                        Sí, eliminar
                    </button>
                </div>
            </form>
        </div>
    </dialog>

    <script>
        const tasaBcv = {{ $tasaBcv }};

        function openPrintModal(btnElem) {
            const encodedData = btnElem.getAttribute('data-factura');
            const factura = JSON.parse(atob(encodedData));
            
            // Set header info
            document.getElementById('printFacturaId').textContent = 'FACTURA #' + String(factura.id_facturacion).padStart(5, '0');
            const fecha = new Date(factura.fecha_emision);
            document.getElementById('printFecha').textContent = fecha.toLocaleDateString() + ' ' + fecha.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
            
            // Set patient info
            if(factura.orden && factura.orden.paciente) {
                document.getElementById('printPacienteNombre').textContent = factura.orden.paciente.nombre + ' ' + factura.orden.paciente.apellido;
                document.getElementById('printPacienteCedula').textContent = factura.orden.paciente.cedula || 'N/A';
            } else {
                document.getElementById('printPacienteNombre').textContent = 'Paciente No Asignado';
                document.getElementById('printPacienteCedula').textContent = 'N/A';
            }

            // Set exams list
            const tbody = document.getElementById('printExamenesList');
            tbody.innerHTML = '';
            if(factura.orden && factura.orden.detalles && factura.orden.detalles.length > 0) {
                factura.orden.detalles.forEach(d => {
                    if(d.examen) {
                        tbody.innerHTML += `
                            <tr class="border-b border-gray-200 print-border">
                                <td class="py-2">${d.examen.nombre_examen}</td>
                                <td class="py-2 text-right">$${parseFloat(d.examen.precio_actual).toFixed(2)}</td>
                            </tr>
                        `;
                    }
                });
            } else {
                tbody.innerHTML = `<tr><td class="py-2 text-slate-400 italic">Sin exámenes registrados</td><td class="py-2"></td></tr>`;
            }

            // Set totals
            document.getElementById('printSubtotal').textContent = '$' + parseFloat(factura.subtotal).toFixed(2);
            document.getElementById('printDescuento').textContent = '-$' + parseFloat(factura.monto_descuento).toFixed(2);
            document.getElementById('printTotal').textContent = '$' + parseFloat(factura.monto_total).toFixed(2);
            
            const totalVES = parseFloat(factura.monto_total) * tasaBcv;
            document.getElementById('printTotalVES').textContent = 'Bs. ' + totalVES.toFixed(2);

            document.getElementById('printMetodoPago').textContent = factura.tipo_pago || 'No especificado';

            // Show modal
            document.getElementById('printModal').classList.remove('hidden');
        }

        function closePrintModal() {
            document.getElementById('printModal').classList.add('hidden');
        }

        function executePrint() {
            const originalTitle = document.title;
            document.title = "FACTURA - CLÍNICA REMARLI";
            window.print();
            document.title = originalTitle;
        }

        function openDelete(id, ordenId) {
            document.getElementById('deleteFactura').innerText = `Factura #${String(id).padStart(5, '0')} (${ordenId})`;
            document.getElementById('deleteForm').action = `{{ url('facturacion') }}/${id}`;
            document.getElementById('deleteDialog').showModal();
        }
    </script>
@endsection
