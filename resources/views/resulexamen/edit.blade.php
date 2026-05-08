@extends('layouts.app')
@section('title', 'Editar Resultado')

@section('content')
<div class="flex flex-col max-w-2xl w-full mx-auto mt-2">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-slate-800 tracking-tight">Editar Resultado</h1>
            <p class="text-slate-500 mt-1">Actualiza las observaciones o reemplaza el PDF adjunto.</p>
        </div>
        <a href="{{ route('resulexamen.index') }}"
           class="flex items-center gap-2 px-4 py-2 bg-white border border-gray-200 text-slate-600 rounded-lg text-sm font-medium hover:bg-gray-50 transition-colors">
            <i data-lucide="arrow-left" class="w-4 h-4"></i> Volver
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-8 space-y-6">

        <!-- Info readonly -->
        <div class="grid grid-cols-2 gap-4 p-4 bg-slate-50 rounded-xl border border-slate-200">
            <div>
                <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Paciente</p>
                <p class="font-semibold text-slate-800 mt-1">{{ $resultado->nombre_paciente }}</p>
            </div>
            <div>
                <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Examen</p>
                <p class="font-semibold text-slate-800 mt-1">{{ $resultado->nombre_examen }}</p>
            </div>
            <div>
                <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Bioanalista</p>
                <p class="text-slate-600 mt-1">{{ $resultado->bioanalista ?? '—' }}</p>
            </div>
            <div>
                <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Fecha Original</p>
                <p class="text-slate-600 mt-1">{{ \Carbon\Carbon::parse($resultado->fecha_resultado)->format('d/m/Y H:i') }}</p>
            </div>
        </div>

        <form action="{{ route('resulexamen.update', $resultado->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PATCH')

            <!-- PDF actual -->
            @if($resultado->imagen_path)
            <div class="flex items-center gap-3 p-3 bg-red-50 border border-red-100 rounded-xl">
                <i data-lucide="file-text" class="w-5 h-5 text-red-500 shrink-0"></i>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-slate-700">PDF actual adjunto</p>
                    <p class="text-xs text-slate-400 truncate">{{ basename($resultado->imagen_path) }}</p>
                </div>
                <a href="{{ asset('storage/' . $resultado->imagen_path) }}" target="_blank"
                   class="text-xs px-3 py-1.5 bg-white border border-gray-200 text-slate-600 rounded-lg hover:bg-gray-50 font-medium transition-colors shrink-0">
                    Ver PDF
                </a>
            </div>
            @endif

            <!-- Observaciones -->
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Observaciones</label>
                <textarea name="observaciones" rows="4"
                          class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-blue-400 transition-all resize-none"
                          placeholder="Notas adicionales...">{{ old('observaciones', $resultado->observaciones) }}</textarea>
            </div>

            <!-- Reemplazar PDF -->
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                    Reemplazar PDF <span class="text-slate-400 font-normal">(opcional)</span>
                </label>
                <div id="dropzone"
                     class="w-full border-2 border-dashed border-gray-200 rounded-xl p-6 text-center cursor-pointer hover:border-red-400 hover:bg-red-50/30 transition-all"
                     onclick="document.getElementById('pdfInput').click()">
                    <i data-lucide="upload-cloud" class="w-8 h-8 text-slate-300 mx-auto mb-2"></i>
                    <p class="text-sm text-slate-500">Arrastra un nuevo PDF o <span class="text-red-600 underline">haz clic aquí</span></p>
                    <p class="text-xs text-slate-400 mt-1">PDF · Máx. 10MB</p>
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
                    <i data-lucide="save" class="w-4 h-4 mr-2 inline-block"></i>
                    Guardar Cambios
                </button>
            </div>
        </form>
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
    dropzone.addEventListener('dragover', e => { e.preventDefault(); dropzone.classList.add('border-red-400','bg-red-50/30'); });
    dropzone.addEventListener('dragleave', () => dropzone.classList.remove('border-red-400','bg-red-50/30'));
    dropzone.addEventListener('drop', e => {
        e.preventDefault();
        dropzone.classList.remove('border-red-400','bg-red-50/30');
        const input = document.getElementById('pdfInput');
        input.files = e.dataTransfer.files;
        handleFile(input);
    });
</script>
@endsection
