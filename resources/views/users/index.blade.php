@extends('layouts.app')

@section('title', 'Gestión de Usuarios')

@section('content')
    <style>
        .toast-container {
            position: fixed;
            bottom: 2rem;
            left: 50%;
            transform: translateX(-50%);
            z-index: 9999;
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
            pointer-events: none;
        }

        .toast-container > div {
            pointer-events: auto;
        }

        @keyframes toast-slide-up {
            0% { transform: translateY(100%); opacity: 0; }
            100% { transform: translateY(0); opacity: 1; }
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
    </style>

    @push('modals')
    <div class="toast-container">
        @php
            $toastIcon = ''; $toastTitle = ''; $toastMsg = '';
            $iconBg = '#fef2f2'; $iconColor = '#ef4444';

            if (session('success')) {
                $toastIcon = 'check-circle'; $toastTitle = '¡Éxito!'; $toastMsg = session('success');
                $iconBg = '#f0fdf4'; $iconColor = '#16a34a';
            } elseif (session('deleted')) {
                $toastIcon = 'trash-2'; $toastTitle = 'Eliminado'; $toastMsg = session('deleted');
            } elseif ($errors->any()) {
                $toastIcon = 'alert-circle'; $toastTitle = 'Error';
                $toastMsg = implode('<br>', $errors->all());
            }
        @endphp

        @if($toastMsg)
            <div id="toast-message" style="background:white;border:1px solid #f1f5f9;border-radius:12px;box-shadow:0 8px 30px rgba(0,0,0,0.12);min-width:320px;padding:1rem 1.25rem;animation:toast-slide-up 0.3s cubic-bezier(0.16,1,0.3,1) forwards;">
                <div style="display:grid;grid-template-columns:4px 1fr 24px;align-items:center;">
                    <div></div>
                    <div style="display:flex;align-items:center;justify-content:center;gap:10px;">
                        <div style="width:36px;height:36px;border-radius:50%;background:{{ $iconBg }};display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <i data-lucide="{{ $toastIcon }}" style="width:16px;height:16px;color:{{ $iconColor }};"></i>
                        </div>
                        <span style="font-weight:700;font-size:15px;color:#1e293b;">{{ $toastTitle }}</span>
                    </div>
                    <button onclick="document.getElementById('toast-message').remove()" style="background:none;border:none;cursor:pointer;color:#94a3b8;padding:0;display:flex;align-items:center;justify-content:center;" onmouseover="this.style.color='#475569'" onmouseout="this.style.color='#94a3b8'">
                        <i data-lucide="x" style="width:16px;height:16px;"></i>
                    </button>
                </div>
                <p style="text-align:center;font-size:13.5px;color:#64748b;margin:0.3rem 0 0;line-height:1.4;">{!! $toastMsg !!}</p>
            </div>
            <script>setTimeout(() => document.getElementById('toast-message')?.remove(), {{ $errors->any() ? 6000 : 4000 }});</script>
        @endif
    </div>
    @endpush

    <div class="flex flex-col max-w-[1400px] w-full mt-2">
        
        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-slate-800 tracking-tight">Gestión de Usuarios</h1>
        </div>

        <!-- KPI Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <!-- Card 1 -->
            <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-sm flex flex-col relative">
                <div class="flex justify-between items-start">
                    <span class="text-slate-400 font-medium text-sm">Facturas Generadas Hoy</span>
                    <div class="w-10 h-10 rounded-xl bg-blue-600 text-white flex items-center justify-center shadow-sm">
                        <i data-lucide="receipt" class="w-5 h-5"></i>
                    </div>
                </div>
                <div class="mt-4">
                    <h3 class="text-4xl font-bold text-slate-800">{{ $facturasHoy }}</h3>
                    <p class="text-green-500 font-medium text-sm mt-2">Facturas del día</p>
                </div>
            </div>

            <!-- Card 2 -->
            <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-sm flex flex-col relative">
                <div class="flex justify-between items-start">
                    <span class="text-slate-400 font-medium text-sm">Pacientes Atendidos</span>
                    <div class="w-10 h-10 rounded-xl bg-blue-600 text-white flex items-center justify-center shadow-sm">
                        <i data-lucide="users" class="w-5 h-5"></i>
                    </div>
                </div>
                <div class="mt-4">
                    <h3 class="text-4xl font-bold text-slate-800">{{ $pacientesTotal }}</h3>
                    <p class="text-green-500 font-medium text-sm mt-2">Registrados en total</p>
                </div>
            </div>

            <!-- Card 3 -->
            <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-sm flex flex-col relative">
                <div class="flex justify-between items-start">
                    <span class="text-slate-400 font-medium text-sm">Exámenes Procesados</span>
                    <div class="w-10 h-10 rounded-xl bg-blue-600 text-white flex items-center justify-center shadow-sm">
                        <!-- Use flask or test-tube -->
                        <i data-lucide="flask-conical" class="w-5 h-5"></i>
                    </div>
                </div>
                <div class="mt-4">
                    <h3 class="text-4xl font-bold text-slate-800">{{ $examenesTotal }}</h3>
                    <p class="text-slate-400 font-medium text-sm mt-2">Pruebas en sistema</p>
                </div>
            </div>
        </div>

        <!-- Main Card -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden flex flex-col">
            
            <!-- Controls area -->
            <div class="p-6 md:p-8 border-b border-gray-100 flex flex-col md:flex-row items-center justify-between gap-4">
                <!-- Search -->
                <div class="relative w-full md:w-[320px]">
                    <form action="{{ route('users.index') }}" method="GET" class="relative w-full md:w-[320px]">
                        <i data-lucide="search" class="absolute left-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-slate-400"></i>
                    <input type="text" placeholder="Buscar usuario por nombre o rol..." 
                        class="w-full pl-9 pr-4 py-2 bg-white border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-1 focus:ring-blue-400 focus:border-blue-400 transition-all text-slate-700 placeholder-slate-400"
                        name="search">
                    </form>
                    
                </div>

                <!-- Right Controls -->
                <div class="flex items-center space-x-3 w-full md:w-auto">
                    <button class="flex items-center space-x-2 px-5 py-2.5 bg-gray-50 border border-gray-200 text-slate-700 font-medium rounded-lg hover:bg-gray-100 transition-colors">
                        <i data-lucide="history" class="w-4 h-4"></i>
                        <span>Ver movimientos realizados</span>
                    </button>
                    <a href="{{ route('users.create') }}" class="flex items-center space-x-2 px-5 py-2.5 bg-[#0d6efd] text-white font-medium rounded-lg hover:bg-blue-700 transition-colors shadow-sm">
                        <i data-lucide="plus" class="w-4 h-4"></i>
                        <span>Nuevo Usuario</span>
                    </a>
                </div>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-600">
                    <thead class="bg-slate-50 text-slate-500 font-bold text-xs uppercase tracking-wider border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-4 w-16">ID</th>
                            <th class="px-6 py-4 w-48">USUARIO</th>
                            <th class="px-6 py-4">CORREO ELECTRÓNICO</th>
                            <th class="px-6 py-4">CÉDULA</th>
                            <th class="px-6 py-4 w-52">ROL / NIVEL DE ACCESO</th>
                            <th class="px-6 py-4 w-32">ESTADO</th>
                            <th class="px-6 py-4 w-48">ÚLTIMO ACCESO</th>
                            <th class="px-6 py-4 w-28 text-center">ACCIONES</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-[15px]">
                        @foreach($usuarios as $user)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-6 py-4 text-slate-500 font-medium">#{{ $user->id }}</td>
                            <td class="px-6 py-4">
                                <div class="font-bold text-slate-800">{{ $user->name }} {{ $user->apellido }}</div>
                            </td>
                            <td class="px-6 py-4 text-slate-500">{{ $user->email }}</td>
                            <td class="px-6 py-4 text-slate-700 font-medium">{{ $user->cedula ?? 'N/A' }}</td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ strtolower($user->rol) == 'admin' ? 'bg-[#0d6efd] text-white' : 'bg-orange-50 text-orange-600 border border-orange-100' }}">
                                    {{ strtolower($user->rol) == 'admin' ? 'Administrador' : ucfirst(strtolower($user->rol)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 font-semibold">
                                <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-bold {{ $user->estado == 'Activo' ? 'text-green-600 bg-green-50' : 'text-slate-500 bg-slate-100' }}">
                                    {{ $user->estado }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-slate-500">{{ $user->updated_at->diffForHumans() }}</td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-center space-x-2"> 

                                    <!-- Editar -->
                                    <a href="{{ route('users.edit', $user->id) }}" class="text-blue-500 hover:text-blue-700 hover:bg-blue-50 p-1.5 rounded-lg hover:ring-4 hover:ring-blue-200 transition-all active:scale-90 cursor-pointer flex items-center justify-center">
                                        <i data-lucide="edit-2" class="w-5 h-5"></i>
                                    </a>

                                    <!-- Eliminar -->
                                    @if(Auth::id() !== $user->id)
                                    <button type="button" data-id="{{ $user->id }}" data-nombre="{{ $user->name }} {{ $user->apellido }}" onclick="openDelete(this)" class="text-red-500 hover:text-red-700 hover:bg-red-50 p-1.5 rounded-lg hover:ring-4 hover:ring-red-200 transition-all active:scale-90 cursor-pointer flex items-center justify-center">
                                        <i data-lucide="trash-2" class="w-5 h-5"></i>
                                    </button>
                                    @else
                                    <button type="button" disabled class="text-slate-300 p-1.5 rounded-lg cursor-not-allowed flex items-center justify-center" title="No puedes eliminar tu propio usuario">
                                        <i data-lucide="trash-2" class="w-5 h-5"></i>
                                    </button>
                                    @endif
                                    
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>
    </div>

    <dialog id="deleteDialog" style="max-width:30rem;overflow:visible;">
        <div style="padding:2rem;text-align:center;">
            <span style="display:inline-flex;align-items:center;justify-content:center;width:3.5rem;height:3.5rem;border-radius:9999px;background:#fee2e2;margin-bottom:1.25rem;">
                <i data-lucide="trash-2" style="width:1.6rem;height:1.6rem;color:#ef4444;"></i>
            </span>
            <h3 style="font-size:1.05rem;font-weight:700;color:#1e293b;margin:0 0 0.4rem;">¿Eliminar usuario?</h3>
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

    <script>
        function openDelete(btn) {
            document.getElementById('deleteForm').action = '/usuarios/' + btn.dataset.id;
            document.getElementById('deleteNombre').textContent = btn.dataset.nombre;
            document.getElementById('deleteDialog').showModal();
        }
    </script>
@endsection
