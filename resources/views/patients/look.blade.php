@extends('layouts.app')

@section('title', 'Gestión de Pacientes')

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

    <section class="flex flex-col max-w-5x1 mx-auto mt-2">
        
        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-slate-800 tracking-tight">Gestión de Pacientes</h1>
            <p class="text-slate-400 mt-1">Administra los registros de pacientes individuales o realiza cargas masivas.</p>
        </div>

        <!-- Search Bar -->
        <form method="GET" action="{{ route('patients.look') }}" class="flex items-center space-x-3 mb-8 bg-white border border-gray-200 rounded-xl p-2 shadow-[0_2px_8px_-4px_rgba(0,0,0,0.05)] text-sm">
            @if(!empty($search))

            @endif
            <div class="flex-1 relative pl-2">
                <i data-lucide="search" class="absolute left-1 top-1/2 transform -translate-y-1/2 w-5 h-5 text-slate-400"></i>
                <input
                    type="text"
                    name="search"
                    value="{{ $search ?? '' }}"
                    placeholder="Buscar cliente por nombre, apellido o cédula de identidad..."
                    class="w-full pl-8 py-2 bg-transparent text-slate-700 placeholder-slate-400 focus:outline-none"
                >
            </div>
            <button type="submit" class="px-6 py-2.5 bg-[#0d6efd] text-white font-medium rounded-lg hover:bg-blue-700 transition-colors">
                Buscar
            </button>
        </form>

        <!-- Tabs -->
        <div class="flex items-center justify-between mb-8">
            <div class="flex space-x-2">
                <a href="{{ route('patients.create') }}" class="px-4 py-2 bg-slate-100 text-slate-700 rounded-lg hover:bg-slate-200 transition-colors">Registro Individual</a>
                <a href="{{ route('patients.look') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">Ver Pacientes</a>
            </div>
        </div>
    </section>
    
    <!-- Form Card -->
    <div class="flex flex-col max-w-5x1 mx-auto mt-2">
                <table class="w-full text-left text-sm text-slate-600">
                    <thead class="bg-slate-50 text-slate-500 font-bold text-xs uppercase tracking-wider border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-4 w-48">Nombre</th>
                            <th class="px-6 py-4">Apellido</th>
                            <th class="px-6 py-4 w-52">Cédula de Identidad</th>
                            <th class="px-6 py-4 w-32">Fecha de Nacimiento</th>
                            <th class="px-6 py-4 w-48">Teléfono</th>
                            <th class="px-6 py-4 w-48">Correo Electrónico</th>
                            <th class="px-6 py-4 w-28 text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-[15px]">
                        @foreach($patients as $patient)
                            <tr>
                                <td class="px-6 py-4">{{ $patient->nombre }}</td>
                                <td class="px-6 py-4">{{ $patient->apellido }}</td>
                                <td class="px-6 py-4">{{ $patient->cedula }}</td>
                                <td class="px-6 py-4">{{ $patient->fecha_nacimiento ? \Carbon\Carbon::parse($patient->fecha_nacimiento)->format('d/m/Y') : '' }}</td>
                                <td class="px-6 py-4">{{ $patient->telefono }}</td>
                                <td class="px-6 py-4">{{ $patient->email }}</td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-center space-x-2"> 
                                        <!-- Editar -->
                                        <a href="{{ route('patients.edit', $patient->id) }}" class="text-blue-500 hover:text-blue-700 hover:bg-blue-50 p-1.5 rounded-lg hover:ring-4 hover:ring-blue-200 transition-all active:scale-90 cursor-pointer flex items-center justify-center">
                                            <i data-lucide="edit-2" class="w-5 h-5"></i>
                                        </a>

                                        <!-- Eliminar -->
                                        <button type="button" data-id="{{ $patient->id }}" data-nombre="{{ $patient->nombre }} {{ $patient->apellido }}" onclick="openDelete(this)" class="text-red-500 hover:text-red-700 hover:bg-red-50 p-1.5 rounded-lg hover:ring-4 hover:ring-red-200 transition-all active:scale-90 cursor-pointer flex items-center justify-center">
                                            <i data-lucide="trash-2" class="w-5 h-5"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

        </div>

    <dialog id="deleteDialog" style="max-width:30rem;overflow:visible;">
        <div style="padding:2rem;text-align:center;">
            <span style="display:inline-flex;align-items:center;justify-content:center;width:3.5rem;height:3.5rem;border-radius:9999px;background:#fee2e2;margin-bottom:1.25rem;">
                <i data-lucide="trash-2" style="width:1.6rem;height:1.6rem;color:#ef4444;"></i>
            </span>
            <h3 style="font-size:1.05rem;font-weight:700;color:#1e293b;margin:0 0 0.4rem;">¿Eliminar paciente?</h3>
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
            document.getElementById('deleteForm').action = '/patients/' + btn.dataset.id;
            document.getElementById('deleteNombre').textContent = btn.dataset.nombre;
            document.getElementById('deleteDialog').showModal();
        }
    </script>
@endsection
