@extends('layouts.app')

@section('title', 'Nuevo Convenio')

@section('header_left')
<a href="{{ route('convens.index') }}">
    <button class="w-10 h-10 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center hover:bg-blue-100 transition-colors">
        <i data-lucide="arrow-left" class="w-5 h-5"></i>
    </button>
    <h2 class="text-xl font-bold text-slate-800 tracking-tight">Nuevo Convenio</h2>
</a>
@endsection

@section('content')
    <div class="flex flex-col items-center justify-center w-full mt-8 pb-12">
        <div class="w-full max-w-3xl bg-white rounded-2xl border border-gray-100 shadow-[0_2px_12px_-4px_rgba(0,0,0,0.05)] overflow-hidden">
            
            <div class="p-8 border-b border-gray-100">
                <h2 class="text-2xl font-bold text-slate-800">Información del Convenio</h2>
                <p class="text-slate-400 text-sm mt-1">Ingresa los datos del nuevo convenio.</p>
                
                @if ($errors->any())
                    <div class="mt-4 p-4 bg-red-50 text-red-600 rounded-lg text-sm">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                
                @if (session('success'))
                    <div class="mt-4 p-4 bg-green-50 text-green-600 rounded-lg text-sm">
                        {{ session('success') }}
                    </div>
                @endif
            </div>

            <form action="{{ route('convens.store') }}" method="POST" class="p-8 flex flex-col space-y-6">
                @csrf
                <!-- Row 1: Nombres y RIF -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="flex flex-col space-y-2">
                        <label class="text-sm font-semibold text-slate-700">Nombre de la empresa</label>
                        <input type="text" name="nombre_empresa" value="{{ old('nombre_empresa') }}" required placeholder="Ej. Empresas Polar" class="px-4 py-3 bg-white border border-gray-200 rounded-xl text-sm text-slate-700 placeholder-slate-300 focus:outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400 transition-all">
                    </div>
                    <div class="flex flex-col space-y-2">
                        <label class="text-sm font-semibold text-slate-700">RIF</label>
                        <input type="text" name="rif" value="{{ old('rif') }}" required placeholder="Ej. J-29906664-5" maxlength="9" oninput="this.value = this.value.replace(/[^0-9]/g, '')" class="px-4 py-3 bg-white border border-gray-200 rounded-xl text-sm text-slate-700 placeholder-slate-300 focus:outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400 transition-all">
                    </div>
                </div>

                <!-- Row 2: Correo -->
                <div class="flex flex-col space-y-2">
                    <label class="text-sm font-semibold text-slate-700">Correo Electrónico (Empresa)</label>
                    <input type="email" name="email_empresa" value="{{ old('email_empresa') }}" required placeholder="Ej: ovejitas@gmail.com" class="px-4 py-3 bg-white border border-gray-200 rounded-xl text-sm text-slate-700 placeholder-slate-300 focus:outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400 transition-all">
                </div>


                <!-- Row 3: Dirección -->
                <div class="flex flex-col space-y-2">
                    <label class="text-sm font-semibold text-slate-700">Dirección (Empresa)</label>
                    <input type="text" name="direccion" value="{{ old('direccion') }}" required placeholder="Ej: Calle 3 la Candelaria" class="px-4 py-3 bg-white border border-gray-200 rounded-xl text-sm text-slate-700 placeholder-slate-300 focus:outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400 transition-all">
                </div>

                <!-- Row 4: Número de Contacto --> 
                <div class="flex flex-col space-y-2">
                    <label class="text-sm font-semibold text-slate-700">Teléfono de Contacto  (Empresa)</label>
                    <input type="text" name="telefono" value="{{ old('telefono') }}" required placeholder="Ej: 04141234567" maxlength="11" oninput="this.value = this.value.replace(/[^0-9]/g, '')" class="px-4 py-3 bg-white border border-gray-200 rounded-xl text-sm text-slate-700 placeholder-slate-300 focus:outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400 transition-all">
                </div>

                <!-- Row 5: % de Descuento -->
                <div class="flex flex-col space-y-2">
                    <label class="text-sm font-semibold text-slate-700">% de Descuento (Empresa)</label>
                    <input type="number" name="porcentaje_descuento" value="{{ old('porcentaje_descuento') }}" required placeholder="Ej: 10%" min="1" max="20" onkeydown="return !['e', 'E', '+', '-', '.'].includes(event.key);" oninput="if(this.value > 20) this.value = 20;" class="px-4 py-3 bg-white border border-gray-200 rounded-xl text-sm text-slate-700 placeholder-slate-300 focus:outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400 transition-all">
                </div>
            

            <div class="bg-gray-50 border-t border-gray-100 p-6 flex flex-col md:flex-row items-center justify-end space-y-3 md:space-y-0 md:space-x-3">
                <a href="{{ route('users.index') }}" class="w-full text-center md:w-auto px-6 py-2.5 bg-white text-slate-700 font-medium rounded-lg border border-gray-200 hover:bg-gray-100 transition-colors">
                    Cancelar
                </a>
                <button type="submit" class="w-full md:w-auto px-6 py-2.5 bg-[#0d6efd] text-white font-medium rounded-lg hover:bg-blue-700 transition-colors shadow-sm">
                    Agregar Empresa
                </button>
            </div> 
            </form>
            
        </div>
    </div>
@endsection
