@extends('layouts.app')

@section('title', 'Nuevo Convenio')

@section('content')
    <div class="flex flex-col max-w-[1400px] w-full mt-2 mx-auto pb-12">
        
        <div class="mb-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-4 px-4 md:px-0">
            <div>
                <h1 class="text-3xl font-bold text-slate-800 dark:text-slate-100 tracking-tight">Nuevo Convenio</h1>
                <p class="text-slate-400 dark:text-slate-500 mt-1">Registra una nueva empresa o convenio en el sistema.</p>
            </div>
            <a href="{{ route('convens.index') }}" class="flex items-center space-x-2 px-5 py-2.5 bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-300 font-medium rounded-lg border border-gray-200 dark:border-slate-700 hover:bg-gray-50 dark:hover:bg-slate-700 transition-colors shadow-sm">
                <i data-lucide="arrow-left" class="w-4 h-4"></i>
                <span>Volver a Convenios</span>
            </a>
        </div>

        <div class="w-full bg-white dark:bg-slate-900 rounded-2xl border border-gray-100 dark:border-slate-800 shadow-[0_2px_12px_-4px_rgba(0,0,0,0.05)] overflow-hidden">
            
            <div class="p-8 border-b border-gray-100 dark:border-slate-800">
                <h2 class="text-xl font-bold text-slate-800 dark:text-slate-100">Información del Convenio</h2>
                <p class="text-slate-400 dark:text-slate-500 text-sm mt-1">Ingresa los datos del nuevo convenio.</p>
                

            </div>

            <form action="{{ route('convens.store') }}" method="POST" class="p-8 flex flex-col space-y-6">
                @csrf
                <!-- Row 1: Nombres y RIF -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="flex flex-col space-y-2">
                        <label class="text-sm font-semibold text-slate-700 dark:text-slate-300">Nombre de la empresa</label>
                        <input type="text" name="nombre_empresa" value="{{ old('nombre_empresa') }}" required placeholder="Ej. Empresas Polar" class="px-4 py-3 bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-xl text-sm text-slate-700 dark:text-slate-200 placeholder-slate-300 dark:placeholder-slate-500 focus:outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400 transition-all">
                    </div>
                    <div class="flex flex-col space-y-2">
                        <label class="text-sm font-semibold text-slate-700 dark:text-slate-300">RIF</label>
                        <input type="text" name="rif" value="{{ old('rif') }}" required placeholder="Ej. J-29906664-5" maxlength="9" oninput="this.value = this.value.replace(/[^0-9]/g, '')" class="px-4 py-3 bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-xl text-sm text-slate-700 dark:text-slate-200 placeholder-slate-300 dark:placeholder-slate-500 focus:outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400 transition-all">
                    </div>
                </div>

                <!-- Row 2: Correo -->
                <div class="flex flex-col space-y-2">
                    <label class="text-sm font-semibold text-slate-700 dark:text-slate-300">Correo Electrónico (Empresa)</label>
                    <input type="email" name="email_empresa" value="{{ old('email_empresa') }}" required placeholder="Ej: ovejitas@gmail.com" class="px-4 py-3 bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-xl text-sm text-slate-700 dark:text-slate-200 placeholder-slate-300 dark:placeholder-slate-500 focus:outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400 transition-all">
                </div>


                <!-- Row 3: Dirección -->
                <div class="flex flex-col space-y-2">
                    <label class="text-sm font-semibold text-slate-700 dark:text-slate-300">Dirección (Empresa)</label>
                    <input type="text" name="direccion" value="{{ old('direccion') }}" required placeholder="Ej: Calle 3 la Candelaria" class="px-4 py-3 bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-xl text-sm text-slate-700 dark:text-slate-200 placeholder-slate-300 dark:placeholder-slate-500 focus:outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400 transition-all">
                </div>

                <!-- Row 4: Número de Contacto --> 
                <div class="flex flex-col space-y-2">
                    <label class="text-sm font-semibold text-slate-700 dark:text-slate-300">Teléfono de Contacto  (Empresa)</label>
                    <input type="text" name="telefono" value="{{ old('telefono') }}" required placeholder="Ej: 04141234567" maxlength="11" oninput="this.value = this.value.replace(/[^0-9]/g, '')" class="px-4 py-3 bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-xl text-sm text-slate-700 dark:text-slate-200 placeholder-slate-300 dark:placeholder-slate-500 focus:outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400 transition-all">
                </div>

                <!-- Row 5: % de Descuento -->
                <div class="flex flex-col space-y-2">
                    <label class="text-sm font-semibold text-slate-700 dark:text-slate-300">% de Descuento (Empresa)</label>
                    <input type="number" name="porcentaje_descuento" value="{{ old('porcentaje_descuento') }}" required placeholder="Ej: 10%" min="1" max="20" onkeydown="return !['e', 'E', '+', '-', '.'].includes(event.key);" oninput="if(this.value > 20) this.value = 20;" class="px-4 py-3 bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-xl text-sm text-slate-700 dark:text-slate-200 placeholder-slate-300 dark:placeholder-slate-500 focus:outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400 transition-all">
                </div>
            

            <div class="bg-gray-50 dark:bg-slate-800 border-t border-gray-100 dark:border-slate-700 p-6 flex flex-col md:flex-row items-center justify-end space-y-3 md:space-y-0 md:space-x-3">
                <a href="{{ route('convens.index') }}" class="w-full text-center md:w-auto px-6 py-2.5 bg-white dark:bg-slate-700 text-slate-700 dark:text-slate-200 font-medium rounded-lg border border-gray-200 dark:border-slate-600 hover:bg-gray-100 dark:hover:bg-slate-600 transition-colors">
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
