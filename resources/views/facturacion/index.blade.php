@extends('layouts.app')

@section('title', 'Gestión de Facturación')

@section('content')
    <div class="flex flex-col max-w-[1400px] w-full mt-2 mx-auto">
        
        <!-- Header -->
        <div class="mb-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h1 class="text-3xl font-bold text-slate-800 tracking-tight">Facturación</h1>
                <p class="text-slate-400 mt-1">Consulta y gestión de las facturas generadas en el sistema.</p>
            </div>
            <button class="flex items-center space-x-2 px-5 py-2.5">
                <a href="{{ route('facturacion.create') }}" class="flex items-center space-x-2 px-5 py-2.5 bg-[#0d6efd] text-white font-medium rounded-lg hover:bg-blue-700 transition-colors shadow-sm cursor-pointer">
                    <i data-lucide="plus" class="w-4 h-4"></i>
                    <span>Nueva Factura</span>
                </a>
            </button>
        </div>

        <!-- Main Card -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden flex flex-col">
            
            <!-- Controls area -->
            <form action="{{ route('facturacion.index') }}" method="GET" class="p-6 md:p-8 border-b border-gray-100 flex flex-col md:flex-row items-center justify-between gap-4">
                <!-- Search -->
                <div class="relative w-full md:w-[400px]">
                    <i data-lucide="search" class="absolute left-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-slate-400"></i>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Buscar por ID factura, orden o tipo de pago..." 
                           class="w-full pl-9 pr-4 py-2 bg-white border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-1 focus:ring-blue-400 focus:border-blue-400 transition-all text-slate-700 placeholder-slate-400">
                </div>

                <!-- Right Control -->
                <div class="relative min-w-[200px] w-full md:w-auto">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                        <i data-lucide="list-filter" class="w-4 h-4 text-slate-500"></i>
                    </div>
                    <select name="sort" onchange="this.form.submit()" class="w-full text-center pl-10 pr-4 py-2 bg-white border border-gray-200 rounded-lg text-sm text-slate-700 hover:bg-gray-50 transition-colors focus:outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400 appearance-none cursor-pointer" style="text-align-last: center;">
                        <option value="fecha_emision" {{ request('sort') == 'fecha_emision' ? 'selected' : '' }}>Fecha de Emisión</option>
                        <option value="id_facturacion" {{ request('sort') == 'id_facturacion' ? 'selected' : '' }}>ID de Facturación</option>
                        <option value="monto_total" {{ request('sort') == 'monto_total' ? 'selected' : '' }}>Monto Total</option>
                    </select>
                </div>
            </form>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-600">
                    <thead class="bg-blue-50 text-slate-500 font-bold text-xs uppercase tracking-wider border-b border-blue-100">
                        <tr>
                            <th class="px-6 py-4 w-32">ID FACTURA</th>
                            <th class="px-6 py-4 w-32">ID ORDEN</th>
                            <th class="px-6 py-4">TIPO DE PAGO</th>
                            <th class="px-6 py-4 text-center">SUBTOTAL</th>
                            <th class="px-6 py-4 text-center">DESCUENTO</th>
                            <th class="px-6 py-4 text-center">MONTO TOTAL</th>
                            <th class="px-6 py-4 text-center">FECHA DE EMISIÓN</th>
                            <th class="px-6 py-4 text-center w-32">ESTATUS</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-[15px]">
                        @forelse($facturas as $factura)
                        <tr class="hover:bg-blue-50/50 transition-colors group">
                            <td class="px-6 py-4 text-slate-500 font-medium">#{{ str_pad($factura->id_facturacion, 5, '0', STR_PAD_LEFT) }}</td>
                            <td class="px-6 py-4 font-medium text-slate-700">ORD-{{ str_pad($factura->id_orden, 4, '0', STR_PAD_LEFT) }}</td>
                            <td class="px-6 py-4 text-slate-600">{{ $factura->tipo_pago ?? 'No especificado' }}</td>
                            <td class="px-6 py-4 text-center font-medium">${{ number_format($factura->subtotal, 2) }}</td>
                            <td class="px-6 py-4 text-center">
                                <span class="text-green-600 font-medium">${{ number_format($factura->monto_descuento, 2) }}</span>
                            </td>
                            <td class="px-6 py-4 text-center font-bold text-slate-800">${{ number_format($factura->monto_total, 2) }}</td>
                            <td class="px-6 py-4 text-center text-slate-600">
                                {{ \Carbon\Carbon::parse($factura->fecha_emision)->format('d/m/Y h:i A') }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                @php
                                    $estatus = strtolower($factura->estatus);
                                    $bgClass = 'bg-slate-100 text-slate-700';
                                    if(in_array($estatus, ['pagada', 'completada', 'aprobada'])) {
                                        $bgClass = 'bg-green-100 text-green-700';
                                    } elseif(in_array($estatus, ['pendiente', 'en espera'])) {
                                        $bgClass = 'bg-yellow-100 text-yellow-700';
                                    } elseif(in_array($estatus, ['anulada', 'cancelada', 'rechazada'])) {
                                        $bgClass = 'bg-red-100 text-red-700';
                                    }
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold uppercase {{ $bgClass }}">
                                    {{ ucfirst($factura->estatus) }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="px-6 py-10 text-center text-slate-500">
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
            <div class="px-8 py-4 border-t border-gray-100 mt-auto bg-white">
                {{ $facturas->links() }}
            </div>
            @else
            <div class="px-8 py-4 border-t border-gray-100 mt-auto bg-white flex justify-between items-center">
                <p class="text-sm text-slate-400">Mostrando todos los registros ({{ $facturas->total() }} en total)</p>
            </div>
            @endif
        </div>
    </div>
@endsection
