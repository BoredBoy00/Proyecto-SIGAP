@extends('layouts.app')
@section('title', 'Registrar Resultado de Examen')

@section('content')
<div class="flex flex-col max-w-2xl w-full mx-auto mt-2">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-slate-800 tracking-tight">Entregar Resultado</h1>
            <p class="text-slate-500 mt-1">Selecciona el encargo pendiente y adjunta el PDF del resultado.</p>
        </div>
        <a href="{{ route('resulexamen.index') }}"
           class="flex items-center gap-2 px-4 py-2 bg-white border border-gray-200 text-slate-600 rounded-lg text-sm font-medium hover:bg-gray-50 transition-colors">
            <i data-lucide="arrow-left" class="w-4 h-4"></i> Volver
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-8">

        @if($encargos->isEmpty())
        <div class="flex flex-col items-center py-12 text-center">
            <i data-lucide="inbox" class="w-14 h-14 text-slate-200 mb-4"></i>
            <p class="font-bold text-slate-600 text-lg">No hay encargos pendientes</p>
            <p class="text-slate-400 text-sm mt-1">Todos los exámenes han sido entregados o no se ha creado ninguna factura aún.</p>
        </div>
        @else

        <form action="{{ route('resulexamen.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <!-- Seleccionar Encargo -->
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">
                    Encargo Pendiente <span class="text-red-500">*</span>
                </label>
                <p class="text-xs text-slate-400 mb-3">Selecciona la orden de exámenes a la que subirás el resultado.</p>

                <div class="space-y-2 max-h-64 overflow-y-auto pr-1" id="encargos-list">
                    @foreach($encargos as $enc)
                    <label class="flex items-start gap-3 p-4 border border-gray-200 rounded-xl cursor-pointer hover:border-blue-400 hover:bg-blue-50/30 transition-all has-[:checked]:border-blue-500 has-[:checked]:bg-blue-50">
                        <input type="radio" name="id_encargo" value="{{ $enc->id }}" class="mt-0.5 w-4 h-4 text-blue-600 accent-blue-600" required>
                        <div class="flex-1">
                            <div class="flex items-center gap-2 flex-wrap">
                                <span class="font-mono text-xs font-bold text-blue-700 bg-blue-100 px-2 py-0.5 rounded">
                                    ORD-{{ str_pad($enc->id_orden, 4, '0', STR_PAD_LEFT) }}
                                </span>
                                <span class="font-bold text-slate-800">{{ $enc->nombre_paciente }}</span>
                                <span class="text-xs px-2 py-0.5 rounded-full font-semibold
                                    {{ $enc->estado === 'En proceso' ? 'bg-blue-100 text-blue-700' : 'bg-yellow-100 text-yellow-700' }}">
                                    {{ $enc->estado }}
                                </span>
                            </div>
                            <p class="text-xs text-slate-500 mt-1">
                                <span class="font-semibold text-slate-600">Exámenes:</span> {{ $enc->examenes }}
                            </p>
                            <p class="text-xs text-slate-400 mt-0.5">{{ $enc->created_at->format('d/m/Y H:i') }} — Creado por {{ $enc->creado_por }}</p>
                        </div>
                    </label>
                    @endforeach
                </div>
                @error('id_encargo') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- Fecha automática -->
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Fecha y Hora del Resultado</label>
                <input type="text" value="{{ now()->format('d/m/Y H:i') }}" disabled
                       class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm bg-slate-50 text-slate-500 cursor-not-allowed">
                <p class="text-xs text-slate-400 mt-1">Registrada automáticamente con la hora del sistema.</p>
            </div>

            <!-- Observaciones -->
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Observaciones</label>
                <textarea name="observaciones" rows="3"
                          class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-blue-400 transition-all resize-none"
                          placeholder="Notas o comentarios del bioanalista...">{{ old('observaciones') }}</textarea>
            </div>

            <!-- PDF -->
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                    Archivo PDF del Resultado <span class="text-red-500">*</span>
                </label>
                <div id="dropzone"
                     class="w-full border-2 border-dashed border-gray-200 rounded-xl p-8 text-center cursor-pointer hover:border-red-400 hover:bg-red-50/30 transition-all"
                     onclick="document.getElementById('pdfInput').click()">
                    <i data-lucide="file-text" class="w-10 h-10 text-red-300 mx-auto mb-3"></i>
                    <p class="text-sm font-medium text-slate-500">Arrastra el PDF o <span class="text-red-600 underline">haz clic aquí</span></p>
                    <p class="text-xs text-slate-400 mt-1">Solo formato PDF · Máx. 10MB</p>
                    <p id="fileName" class="text-xs text-red-600 font-semibold mt-2 hidden"></p>
                </div>
                <input type="file" id="pdfInput" name="pdf" accept=".pdf" class="hidden" onchange="handleFile(this)">
                @error('pdf') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- Botones -->
            <div class="flex justify-end space-x-3 pt-2">
                <a href="{{ route('resulexamen.index') }}"
                   class="px-5 py-2.5 text-sm font-medium text-slate-600 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                    Cancelar
                </a>
                <button type="submit"
                        class="px-6 py-2.5 text-sm font-bold text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-colors shadow-sm shadow-blue-500/30">
                    <i data-lucide="upload" class="w-4 h-4 mr-2 inline-block"></i>
                    Entregar Resultado
                </button>
            </div>
        </form>
        @endif
    </div>
</div>

<script>
    function handleFile(input) {
        const file = input.files[0];
        if (!file) return;
        const fn = document.getElementById('fileName');
        fn.textContent = '📄 ' + file.name;
        fn.classList.remove('hidden');
    }
    const dropzone = document.getElementById('dropzone');
    if (dropzone) {
        dropzone.addEventListener('dragover', e => { e.preventDefault(); dropzone.classList.add('border-red-400','bg-red-50/30'); });
        dropzone.addEventListener('dragleave', () => dropzone.classList.remove('border-red-400','bg-red-50/30'));
        dropzone.addEventListener('drop', e => {
            e.preventDefault();
            dropzone.classList.remove('border-red-400','bg-red-50/30');
            const input = document.getElementById('pdfInput');
            input.files = e.dataTransfer.files;
            handleFile(input);
        });
    }
</script>
@endsection
