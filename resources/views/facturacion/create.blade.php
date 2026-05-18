@extends('layouts.app')

@section('title', 'Nueva Factura')

@section('content')
    <div class="flex flex-col max-w-[1400px] w-full mt-2 mx-auto">
        
        <div class="mb-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h1 class="text-3xl font-bold text-slate-800 dark:text-slate-100 tracking-tight">Nueva Factura</h1>
                <p class="text-slate-400 dark:text-slate-500 mt-1">Registra una nueva factura en el sistema y asóciala a una orden.</p>
            </div>
            <a href="{{ route('facturacion.index') }}" class="flex items-center space-x-2 px-5 py-2.5 bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-300 font-medium rounded-lg border border-gray-200 dark:border-slate-700 hover:bg-gray-50 dark:hover:bg-slate-700 transition-colors shadow-sm">
                <i data-lucide="arrow-left" class="w-4 h-4"></i>
                <span>Volver a Facturación</span>
            </a>
        </div>

        <div class="bg-white dark:bg-slate-900 rounded-xl border border-gray-200 dark:border-slate-800 shadow-sm overflow-hidden flex flex-col">
            <div class="p-6 md:p-8 border-b border-gray-100 dark:border-slate-800">
                <h2 class="text-xl font-bold text-slate-800 dark:text-slate-100">Datos de Facturación</h2>
                <p class="text-slate-400 dark:text-slate-500 text-sm mt-1">Ingresa los detalles para generar la factura.</p>


            </div>

            <form action="{{ route('facturacion.store') }}" method="POST" class="p-6 md:p-8 flex flex-col space-y-8 flex-1" id="facturacionForm">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    
                    <!-- Left Column -->
                    <div class="space-y-6">
                        <h3 class="text-lg font-bold text-slate-800 dark:text-slate-100 border-b border-gray-100 dark:border-slate-700 pb-2">1. Datos del Paciente</h3>
                        
                        <!-- ID Paciente -->
                        <div class="flex flex-col space-y-2">
                            <label class="text-sm font-semibold text-slate-700 dark:text-slate-300">Seleccionar Paciente</label>
                            <div class="relative">
                                <i data-lucide="search" class="absolute left-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-slate-400 pointer-events-none"></i>
                                <input type="text" id="buscarPaciente" placeholder="Buscar por nombre o cédula..." class="w-full pl-9 pr-4 py-2 mb-2 bg-slate-50 dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-lg text-sm text-slate-700 dark:text-slate-200 focus:outline-none focus:border-blue-400 transition-all placeholder-slate-400 dark:placeholder-slate-500">
                            </div>
                            <div class="h-48 overflow-y-auto border border-gray-200 dark:border-slate-700 rounded-lg bg-white dark:bg-slate-800 p-2 space-y-1" id="pacientesList">
                                @foreach($pacientes as $paciente)
                                    @php
                                        $descuento = $paciente->empresa ? $paciente->empresa->porcentaje_descuento : 0;
                                    @endphp
                                    <label class="flex items-center space-x-3 p-3 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-lg cursor-pointer border border-transparent hover:border-blue-100 dark:hover:border-blue-900/30 transition-all group paciente-item">
                                        <input type="radio" name="id_paciente" value="{{ $paciente->id }}" 
                                               data-descuento="{{ $descuento }}" 
                                               class="w-4 h-4 text-blue-600 border-gray-300 dark:border-slate-600 focus:ring-blue-500" 
                                               required {{ old('id_paciente') == $paciente->id ? 'checked' : '' }}>
                                        <div class="flex flex-col flex-1">
                                            <span class="text-sm font-bold text-slate-700 dark:text-slate-200">{{ $paciente->cedula }} - {{ $paciente->nombre }} {{ $paciente->apellido }}</span>
                                            @if($descuento > 0)
                                                <span class="text-xs font-medium text-blue-600 dark:text-blue-400">Convenio: {{ $paciente->empresa->nombre_empresa }} (-{{ $descuento }}%)</span>
                                            @endif
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                            <input type="hidden" id="porcentaje_aplicado" value="0">
                        </div>

                        <!-- Fecha de Emisión -->
                        <div class="flex flex-col space-y-2 relative">
                            <label class="text-sm font-semibold text-slate-700 dark:text-slate-300">Fecha de Emisión</label>
                            <i data-lucide="calendar" class="absolute right-4 top-[38px] w-4 h-4 text-slate-400 pointer-events-none"></i>
                            <input type="datetime-local" name="fecha_emision" readonly value="{{ \Carbon\Carbon::now()->format('Y-m-d\TH:i') }}" class="px-4 py-3 pr-10 bg-slate-50 dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-lg text-sm text-slate-500 dark:text-slate-400 focus:outline-none cursor-not-allowed dark:[color-scheme:dark]">
                        </div>

                        <!-- Tipo de Pago -->
                        <div class="flex flex-col space-y-2">
                            <label class="text-sm font-semibold text-slate-700 dark:text-slate-300">Tipo de Pago</label>
                            <select name="tipo_pago" class="px-4 py-3 bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-lg text-sm text-slate-700 dark:text-slate-200 focus:outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400 transition-all cursor-pointer">
                                <option value="">No especificado (Pendiente)</option>
                                <option value="Efectivo" {{ old('tipo_pago') == 'Efectivo' ? 'selected' : '' }}>Efectivo (Divisas)</option>
                                <option value="Efectivo VES" {{ old('tipo_pago') == 'Efectivo VES' ? 'selected' : '' }}>Efectivo (Bs)</option>
                                <option value="Punto de Venta" {{ old('tipo_pago') == 'Punto de Venta' ? 'selected' : '' }}>Punto de Venta</option>
                                <option value="Pago Movil" {{ old('tipo_pago') == 'Pago Movil' ? 'selected' : '' }}>Pago Móvil</option>
                                <option value="Transferencia" {{ old('tipo_pago') == 'Transferencia' ? 'selected' : '' }}>Transferencia</option>
                            </select>
                        </div>

                        <!-- Estatus -->
                        <div class="flex flex-col space-y-2">
                            <label class="text-sm font-semibold text-slate-700 dark:text-slate-300">Estatus</label>
                            <select name="estatus" required class="px-4 py-3 bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-lg text-sm text-slate-700 dark:text-slate-200 focus:outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400 transition-all cursor-pointer">
                                <option value="Pendiente" {{ old('estatus') == 'Pendiente' ? 'selected' : '' }}>Pendiente</option>
                                <option value="Pagada" {{ old('estatus') == 'Pagada' ? 'selected' : '' }}>Pagada</option>
                                <option value="Anulada" {{ old('estatus') == 'Anulada' ? 'selected' : '' }}>Anulada</option>
                            </select>
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="space-y-6">
                        <h3 class="text-lg font-bold text-slate-800 dark:text-slate-100 border-b border-gray-100 dark:border-slate-700 pb-2">2. Exámenes a Facturar</h3>
                        
                        <div class="flex flex-col space-y-2">
                            <label class="text-sm font-semibold text-slate-700 dark:text-slate-300">Seleccionar Exámenes</label>
                            <div class="relative">
                                <i data-lucide="search" class="absolute left-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-slate-400 pointer-events-none"></i>
                                <input type="text" id="buscarExamen" placeholder="Buscar examen por nombre..." class="w-full pl-9 pr-4 py-2 mb-2 bg-slate-50 dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-lg text-sm text-slate-700 dark:text-slate-200 focus:outline-none focus:border-blue-400 transition-all placeholder-slate-400 dark:placeholder-slate-500">
                            </div>
                            
                            <div class="h-64 overflow-y-auto border border-gray-200 dark:border-slate-700 rounded-lg bg-white dark:bg-slate-800 p-2 space-y-1" id="examenesList">
                                @foreach($examenes as $examen)
                                    <label class="flex items-center space-x-3 p-3 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-lg cursor-pointer border border-transparent hover:border-blue-100 dark:hover:border-blue-900/30 transition-all">
                                        <input type="checkbox" name="examenes[]" value="{{ $examen->id_examen }}" data-precio="{{ $examen->precio_actual }}" class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500 exam-checkbox">
                                        <div class="flex flex-col flex-1">
                                            <span class="text-sm font-bold text-slate-700 dark:text-slate-200">{{ $examen->nombre_examen }}</span>
                                            <span class="text-xs font-medium text-slate-500 dark:text-slate-400">Cód: {{ $examen->codigo_examen }} | ${{ number_format($examen->precio_actual, 2) }}</span>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <!-- Resumen -->
                        <div class="bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl p-5 space-y-4">
                            <h4 class="text-sm font-bold text-slate-800 dark:text-slate-100 uppercase tracking-wider mb-2 flex items-center"><i data-lucide="receipt" class="w-4 h-4 mr-2 text-blue-600"></i> Resumen de Factura</h4>
                            
                            <!-- Subtotal -->
                            <div class="flex justify-between items-center text-sm">
                                <span class="font-medium text-slate-600">Subtotal</span>
                                <div class="font-bold text-slate-700 text-base">$<span id="txt_subtotal">0.00</span></div>
                                <input type="hidden" name="subtotal" id="val_subtotal" value="0.00">
                            </div>

                            <!-- Descuento -->
                            <div class="flex justify-between items-center text-sm text-green-600">
                                <span class="font-medium flex items-center">Descuento Convenio (<span id="txt_porcentaje">0</span>%)</span>
                                <div class="font-bold text-base">-$<span id="txt_descuento">0.00</span></div>
                                <input type="hidden" name="monto_descuento" id="val_descuento" value="0.00">
                            </div>

                            <hr class="border-slate-200">

                            <!-- Total -->
                            <div class="flex justify-between items-center">
                                <span class="font-bold text-slate-800 text-base">MONTO TOTAL</span>
                                <div class="text-right">
                                    <div class="font-black text-blue-600 text-2xl tracking-tight">$<span id="txt_total">0.00</span></div>
                                    <div class="text-sm font-semibold text-slate-500">Bs. <span id="txt_total_ves">0.00</span></div>
                                </div>
                                <input type="hidden" name="monto_total" id="val_total" value="0.00">
                            </div>
                        </div>

                    </div>
                </div>

                <div class="bg-blue-50/50 dark:bg-slate-800 border-t border-blue-100 dark:border-slate-700 p-6 flex items-center justify-end space-x-3 mt-auto rounded-b-xl -mx-6 md:-mx-8 -mb-6 md:-mb-8">
                    <a href="{{ route('facturacion.index') }}" class="px-6 py-2.5 bg-white dark:bg-slate-700 text-slate-700 dark:text-slate-200 font-medium rounded-lg border border-gray-200 dark:border-slate-600 hover:bg-gray-50 dark:hover:bg-slate-600 transition-colors cursor-pointer">
                        Cancelar
                    </a>
                    <button type="submit" class="flex items-center space-x-2 px-6 py-2.5 bg-[#198754] text-white font-medium rounded-lg hover:bg-green-700 transition-colors shadow-sm cursor-pointer" id="btnSubmit">
                        <i data-lucide="save" class="w-4 h-4"></i>
                        <span>Generar Factura</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const buscarPaciente = document.getElementById('buscarPaciente');
            const buscarExamen = document.getElementById('buscarExamen');
            const examCheckboxes = document.querySelectorAll('.exam-checkbox');
            const btnSubmit = document.getElementById('btnSubmit');
            const tasaBcv = {{ $tasaBcv ?? 1 }};

            // Search Patient
            buscarPaciente.addEventListener('input', function(e) {
                const term = e.target.value.toLowerCase();
                const labels = document.getElementById('pacientesList').querySelectorAll('.paciente-item');
                labels.forEach(label => {
                    const text = label.textContent.toLowerCase();
                    label.style.display = text.includes(term) ? 'flex' : 'none';
                });
            });

            // Search Exam
            buscarExamen.addEventListener('input', function(e) {
                const term = e.target.value.toLowerCase();
                const labels = document.getElementById('examenesList').querySelectorAll('label');
                labels.forEach(label => {
                    const text = label.textContent.toLowerCase();
                    label.style.display = text.includes(term) ? '' : 'none';
                });
            });

            // Update Discount and Total when Patient is selected
            document.querySelectorAll('input[name="id_paciente"]').forEach(radio => {
                radio.addEventListener('change', function() {
                    const descuento = parseFloat(this.getAttribute('data-descuento')) || 0;
                    document.getElementById('porcentaje_aplicado').value = descuento;
                    document.getElementById('txt_porcentaje').textContent = descuento;
                    calcularTotales();
                });
            });

            // Update Totals when Exam is selected/unselected
            examCheckboxes.forEach(chk => {
                chk.addEventListener('change', calcularTotales);
            });

            // Prevent form submission if no exam selected
            document.getElementById('facturacionForm').addEventListener('submit', function(e) {
                const checked = document.querySelectorAll('.exam-checkbox:checked').length;
                if(checked === 0) {
                    e.preventDefault();
                    alert('Debe seleccionar al menos un examen para facturar.');
                }
            });

            function calcularTotales() {
                let subtotal = 0;
                examCheckboxes.forEach(chk => {
                    if (chk.checked) {
                        subtotal += parseFloat(chk.getAttribute('data-precio')) || 0;
                    }
                });

                const porcentaje = parseFloat(document.getElementById('porcentaje_aplicado').value) || 0;
                const descuento = subtotal * (porcentaje / 100);
                const total = subtotal - descuento;
                const totalVES = total * tasaBcv;

                // Update UI values
                document.getElementById('txt_subtotal').textContent = subtotal.toFixed(2);
                document.getElementById('txt_descuento').textContent = descuento.toFixed(2);
                document.getElementById('txt_total').textContent = total.toFixed(2);
                document.getElementById('txt_total_ves').textContent = totalVES.toFixed(2);

                // Update Hidden Inputs for submission
                document.getElementById('val_subtotal').value = subtotal.toFixed(2);
                document.getElementById('val_descuento').value = descuento.toFixed(2);
                document.getElementById('val_total').value = total.toFixed(2);

                // Enable/Disable submit
                btnSubmit.disabled = subtotal === 0;
                if(subtotal === 0) {
                    btnSubmit.classList.add('opacity-50', 'cursor-not-allowed');
                } else {
                    btnSubmit.classList.remove('opacity-50', 'cursor-not-allowed');
                }
            }

            // Initial calculation
            calcularTotales();
        });
    </script>
@endsection
