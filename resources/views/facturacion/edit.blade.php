@extends('layouts.app')

@section('title', 'Editar Factura')

@section('header_left')
<a href="{{ route('facturacion.index') }}">
    <button class="w-10 h-10 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center hover:bg-blue-100 transition-colors">
        <i data-lucide="arrow-left" class="w-5 h-5"></i>
    </button>
    <h2 class="text-xl font-bold text-slate-800 tracking-tight">Editar Factura #{{ str_pad($factura->id_facturacion, 4, '0', STR_PAD_LEFT) }}</h2>
</a>
@endsection

@section('content')
    <div class="flex flex-col items-center justify-center w-full mt-8 pb-12">
        <div class="w-full max-w-2xl bg-white rounded-2xl border border-gray-100 shadow-[0_2px_12px_-4px_rgba(0,0,0,0.05)] overflow-hidden">
            
            <div class="p-8 border-b border-gray-100">
                <h2 class="text-2xl font-bold text-slate-800">Actualizar Estado y Pago</h2>
                <p class="text-slate-400 text-sm mt-1">Modifica el estado o el método de pago de esta factura. Nota: Los montos y exámenes no se pueden modificar una vez facturados.</p>
                
                @if ($errors->any())
                    <div class="mt-4 p-4 bg-red-50 text-red-600 rounded-lg text-sm">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>

            <form action="{{ route('facturacion.update', $factura->id_facturacion) }}" method="POST" class="p-8 flex flex-col space-y-6">
                @csrf
                @method('PATCH')
                
                <!-- Info Paciente -->
                <div class="bg-slate-50 p-4 rounded-lg border border-slate-100 mb-2">
                    <p class="text-sm text-slate-500 mb-1">Paciente asociado:</p>
                    <p class="font-bold text-slate-800">{{ $factura->orden->paciente->nombre ?? 'N/A' }} {{ $factura->orden->paciente->apellido ?? '' }}</p>
                    <p class="text-sm font-semibold text-blue-600 mt-2">Monto Total: ${{ number_format($factura->monto_total, 2) }}</p>
                </div>

                <div class="grid grid-cols-1 gap-6">
                    <!-- Tipo de Pago -->
                    <div class="flex flex-col space-y-2 relative pb-2">
                        <label class="text-sm font-semibold text-slate-700">Tipo de Pago</label>
                        <i data-lucide="chevron-down" class="absolute right-4 top-[38px] w-4 h-4 text-slate-400"></i>
                        <select name="tipo_pago" class="px-4 py-3 pr-10 bg-white border border-gray-200 rounded-xl text-sm text-slate-700 appearance-none focus:outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400 transition-all">
                            <option value="" {{ !$factura->tipo_pago ? 'selected' : '' }}>No especificado (Pendiente)</option>
                            <option value="Efectivo" {{ $factura->tipo_pago == 'Efectivo' ? 'selected' : '' }}>Efectivo (Divisas)</option>
                            <option value="Efectivo VES" {{ $factura->tipo_pago == 'Efectivo VES' ? 'selected' : '' }}>Efectivo (Bs)</option>
                            <option value="Punto de Venta" {{ $factura->tipo_pago == 'Punto de Venta' ? 'selected' : '' }}>Punto de Venta</option>
                            <option value="Pago Movil" {{ $factura->tipo_pago == 'Pago Movil' ? 'selected' : '' }}>Pago Móvil</option>
                            <option value="Transferencia" {{ $factura->tipo_pago == 'Transferencia' ? 'selected' : '' }}>Transferencia</option>
                        </select>
                    </div>

                    <!-- Estatus -->
                    <div class="flex flex-col space-y-2 relative pb-2">
                        <label class="text-sm font-semibold text-slate-700">Estatus de la Factura</label>
                        <i data-lucide="chevron-down" class="absolute right-4 top-[38px] w-4 h-4 text-slate-400"></i>
                        <select name="estatus" required class="px-4 py-3 pr-10 bg-white border border-gray-200 rounded-xl text-sm text-slate-700 appearance-none focus:outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400 transition-all">
                            <option value="Pendiente" {{ $factura->estatus == 'Pendiente' ? 'selected' : '' }}>Pendiente</option>
                            <option value="Pagada" {{ $factura->estatus == 'Pagada' ? 'selected' : '' }}>Pagada</option>
                            <option value="Anulada" {{ $factura->estatus == 'Anulada' ? 'selected' : '' }}>Anulada</option>
                        </select>
                    </div>
                </div>
            
                <div class="bg-gray-50 border-t border-gray-100 p-6 flex flex-col md:flex-row items-center justify-end space-y-3 md:space-y-0 md:space-x-3 -mx-8 -mb-8 mt-4 rounded-b-2xl">
                    <a href="{{ route('facturacion.index') }}" class="w-full text-center md:w-auto px-6 py-2.5 bg-white text-slate-700 font-medium rounded-lg border border-gray-200 hover:bg-gray-100 transition-colors">
                        Cancelar
                    </a>
                    <button type="submit" class="w-full md:w-auto px-6 py-2.5 bg-[#0d6efd] text-white font-medium rounded-lg hover:bg-blue-700 transition-colors shadow-sm">
                        Guardar Cambios
                    </button>
                </div>
            </form>
            
        </div>
    </div>
@endsection
