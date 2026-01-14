@extends('layouts.admin')

@section('title', 'Gestión de Pagos')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-display font-bold text-gray-900">Gestión de Pagos</h1>
            <p class="text-gray-600 mt-1">Verificación y seguimiento de pagos de pacientes</p>
        </div>
        <a href="{{ route('pagos.create') }}" class="btn btn-primary shadow-lg shadow-emerald-200">
            <i class="bi bi-plus-lg mr-2"></i>
            <span>Registrar Pago</span>
        </a>
    </div>

    <!-- Stats Cards -->
    {{-- Estadísticas calculadas en Controller --}}

    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="card p-6 bg-gradient-to-br from-emerald-50 to-white border-emerald-100">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-emerald-600 rounded-xl flex items-center justify-center shadow-sm">
                    <i class="bi bi-check-circle text-white text-xl"></i>
                </div>
                <div>
                    <p class="text-sm font-bold text-emerald-700 uppercase">Confirmados</p>
                    <p class="text-2xl font-bold text-gray-900">${{ number_format($totalConfirmados, 2) }}</p>
                </div>
            </div>
        </div>

        <div class="card p-6 bg-gradient-to-br from-amber-50 to-white border-amber-100">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-amber-600 rounded-xl flex items-center justify-center shadow-sm">
                    <i class="bi bi-clock-history text-white text-xl"></i>
                </div>
                <div>
                    <p class="text-sm font-bold text-amber-700 uppercase">Pendientes</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $totalPendientes }}</p>
                </div>
            </div>
        </div>

        <div class="card p-6 bg-gradient-to-br from-blue-50 to-white border-blue-100">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-blue-600 rounded-xl flex items-center justify-center shadow-sm">
                    <i class="bi bi-calendar-day text-white text-xl"></i>
                </div>
                <div>
                    <p class="text-sm font-bold text-blue-700 uppercase">Hoy</p>
                    <p class="text-2xl font-bold text-gray-900">${{ number_format($totalHoy, 2) }}</p>
                </div>
            </div>
        </div>

        <div class="card p-6 bg-gradient-to-br from-purple-50 to-white border-purple-100">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-purple-600 rounded-xl flex items-center justify-center shadow-sm">
                    <i class="bi bi-receipt text-white text-xl"></i>
                </div>
                <div>
                    <p class="text-sm font-bold text-purple-700 uppercase">Total Pagos</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $pagos->total() }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="card">
        <div class="p-6 border-b border-gray-200 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <h3 class="text-lg font-bold text-gray-900">Listado de Pagos</h3>
            
            <form action="{{ route('pagos.index') }}" method="GET" class="flex gap-2 w-full md:w-auto">
                <div class="relative flex-1 md:w-80">
                    <i class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    <input type="text" name="buscar" value="{{ request('buscar') }}" class="input pl-10 h-10 text-sm" placeholder="Paciente, CI o Referencia...">
                </div>
                <button type="submit" class="btn btn-primary h-10 px-4">
                    <i class="bi bi-funnel"></i>
                </button>
                @if(request()->filled('buscar'))
                <a href="{{ route('pagos.index') }}" class="btn btn-outline h-10 px-4" title="Limpiar búsqueda">
                    <i class="bi bi-x-lg"></i>
                </a>
                @endif
            </form>
        </div>
        <div class="overflow-x-auto">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th class="w-32">Referencia</th>
                        <th>Paciente</th>
                        <th>Cita/Factura</th>
                        <th class="w-32">Método</th>
                        <th class="w-32">Fecha Pago</th>
                        <th class="w-32">Monto</th>
                        <th class="w-24">Estado</th>
                        <th class="w-48">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pagos as $pago)
                    <tr class="{{ $pago->estado == 'Pendiente' ? 'bg-amber-50' : '' }}">
                        <td>
                            <span class="font-mono text-sm font-bold text-gray-900">{{ $pago->referencia ?? 'N/A' }}</span>
                        </td>
                        <td>
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                                    <i class="bi bi-person text-purple-600"></i>
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-900">
                                        {{ $pago->facturaPaciente->cita->paciente->primer_nombre ?? 'N/A' }}
                                        {{ $pago->facturaPaciente->cita->paciente->primer_apellido ?? '' }}
                                    </p>
                                    <p class="text-sm text-gray-500">
                                        {{ $pago->facturaPaciente->cita->paciente->tipo_documento ?? '' }}-{{ $pago->facturaPaciente->cita->paciente->numero_documento ?? '' }}
                                    </p>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="text-sm">
                                <p class="text-gray-700 font-medium">{{ $pago->facturaPaciente->numero_factura ?? 'N/A' }}</p>
                                <p class="text-gray-500">Cita #{{ $pago->facturaPaciente->cita_id }}</p>
                            </div>
                        </td>
                        <td>
                            <span class="badge badge-info text-xs">{{ $pago->metodoPago->nombre ?? 'N/A' }}</span>
                        </td>
                        <td>
                            <span class="text-gray-600">{{ \Carbon\Carbon::parse($pago->fecha_pago)->format('d/m/Y') }}</span>
                        </td>
                        <td>
                            <div class="text-sm">
                                <p class="font-bold text-gray-900">Bs. {{ number_format($pago->monto_pagado_bs, 2) }}</p>
                                <p class="text-gray-500">${{ number_format($pago->monto_equivalente_usd, 2) }}</p>
                            </div>
                        </td>
                        <td>
                            @if($pago->estado == 'Confirmado')
                            <span class="badge badge-success">Confirmado</span>
                            @elseif($pago->estado == 'Pendiente')
                            <span class="badge badge-warning">Pendiente</span>
                            @elseif($pago->estado == 'Rechazado')
                            <span class="badge badge-danger">Rechazado</span>
                            @else
                            <span class="badge badge-secondary">{{ $pago->estado }}</span>
                            @endif
                        </td>
                        <td>
                            <div class="flex gap-2">
                                <a href="{{ route('pagos.show', $pago->id_pago) }}" class="btn btn-sm btn-outline" title="Ver Detalles">
                                    <i class="bi bi-eye"></i>
                                </a>
                                
                                @if($pago->estado == 'Pendiente')
                                <button onclick="openConfirmModal({{ $pago->id_pago }})" class="btn btn-sm btn-success" title="Confirmar Pago">
                                    <i class="bi bi-check-lg"></i>
                                </button>
                                <button onclick="openRejectModal({{ $pago->id_pago }})" class="btn btn-sm btn-danger" title="Rechazar Pago">
                                    <i class="bi bi-x-lg"></i>
                                </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-12">
                            <div class="flex flex-col items-center">
                                <i class="bi bi-inbox text-6xl text-gray-300 mb-3"></i>
                                <p class="text-gray-500 font-medium">No se encontraron pagos</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($pagos->hasPages())
        <div class="p-6 border-t border-gray-200">
            {{ $pagos->links() }}
        </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
    let currentPagoId = null;

    function openConfirmModal(pagoId) {
        currentPagoId = pagoId;
        const modal = document.getElementById('modalConfirmarPago');
        const modalContent = modal.querySelector('.modal-content');
        modal.classList.remove('hidden');
        setTimeout(() => {
            modal.classList.remove('opacity-0');
            modalContent.classList.remove('scale-95', 'opacity-0');
        }, 10);
    }

    function openRejectModal(pagoId) {
        currentPagoId = pagoId;
        const modal = document.getElementById('modalRechazarPago');
        const modalContent = modal.querySelector('.modal-content');
        modal.classList.remove('hidden');
        setTimeout(() => {
            modal.classList.remove('opacity-0');
            modalContent.classList.remove('scale-95', 'opacity-0');
        }, 10);
    }

    function closeModal(id) {
        const modal = document.getElementById(id);
        const modalContent = modal.querySelector('.modal-content');
        modal.classList.add('opacity-0');
        modalContent.classList.add('scale-95', 'opacity-0');
        setTimeout(() => {
            modal.classList.add('hidden');
            currentPagoId = null;
            if (id === 'modalRechazarPago') {
                document.getElementById('motivo_input').value = '';
                document.getElementById('motivo_error').classList.add('hidden');
            }
        }, 300);
    }

    async function ejecutarConfirmacion() {
        const adminId = {{ auth()->user()->administrador->id ?? 'null' }};
        if (!adminId) {
            alert('No se encontró el ID del administrador');
            return;
        }

        const btn = document.getElementById('confirmBtn');
        const originalText = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = '<i class="bi bi-hourglass-split animate-spin mr-2"></i> Procesando...';

        try {
            const formData = new FormData();
            formData.append('confirmado_por', adminId);
            formData.append('_token', '{{ csrf_token() }}');

            const response = await fetch(`{{ url('pagos') }}/${currentPagoId}/confirmar`, {
                method: 'POST',
                body: formData,
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });

            const data = await response.json();

            if (response.ok && data.success !== false) {
                btn.innerHTML = '<i class="bi bi-check-lg mr-2"></i> ¡Hecho!';
                setTimeout(() => location.reload(), 1000);
            } else {
                alert(data.message || 'Error al confirmar el pago');
                btn.disabled = false;
                btn.innerHTML = originalText;
            }
        } catch (error) {
            console.error(error);
            alert('Error de conexión');
            btn.disabled = false;
            btn.innerHTML = originalText;
        }
    }

    async function ejecutarRechazo() {
        const motivo = document.getElementById('motivo_input').value.trim();
        if (!motivo) {
            document.getElementById('motivo_error').classList.remove('hidden');
            return;
        }

        const btn = document.getElementById('rejectBtn');
        const originalText = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = '<i class="bi bi-hourglass-split animate-spin mr-2"></i> Procesando...';

        try {
            const formData = new FormData();
            formData.append('motivo', motivo);
            formData.append('_token', '{{ csrf_token() }}');

            const response = await fetch(`{{ url('pagos') }}/${currentPagoId}/rechazar`, {
                method: 'POST',
                body: formData,
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });

            const data = await response.json();

            if (response.ok && data.success !== false) {
                btn.innerHTML = '<i class="bi bi-check-lg mr-2"></i> ¡Hecho!';
                setTimeout(() => location.reload(), 1000);
            } else {
                alert(data.message || 'Error al rechazar el pago');
                btn.disabled = false;
                btn.innerHTML = originalText;
            }
        } catch (error) {
            console.error(error);
            alert('Error de conexión');
            btn.disabled = false;
            btn.innerHTML = originalText;
        }
    }
</script>

<!-- Custom Modal: Confirmar Pago -->
<div id="modalConfirmarPago" class="fixed inset-0 z-50 hidden opacity-0 transition-opacity duration-300 flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm" onclick="closeModal('modalConfirmarPago')"></div>
    <div class="modal-content relative bg-white w-full max-w-md rounded-2xl shadow-2xl overflow-hidden transform scale-95 opacity-0 transition-all duration-300 border border-gray-100">
        <div class="h-2 bg-gradient-to-r from-emerald-500 to-teal-600"></div>
        <div class="p-8">
            <div class="w-16 h-16 bg-emerald-50 rounded-2xl flex items-center justify-center mb-6 ring-4 ring-emerald-50/50">
                <i class="bi bi-check-circle-fill text-emerald-500 text-3xl"></i>
            </div>
            <h3 class="text-2xl font-display font-bold text-gray-900 mb-2">¿Confirmar este pago?</h3>
            <p class="text-gray-500 mb-8 font-medium">Esta acción cambiará el estado de la cita a "Confirmada" y ejecutará la facturación correspondiente.</p>
            <div class="flex gap-3">
                <button onclick="closeModal('modalConfirmarPago')" 
                    class="flex-1 px-6 py-3.5 rounded-xl font-bold text-gray-600 bg-gray-100 hover:bg-gray-200 transition-all active:scale-95">
                    Cancelar
                </button>
                <button id="confirmBtn" onclick="ejecutarConfirmacion()" 
                    class="flex-1 px-6 py-3.5 rounded-xl font-bold text-white bg-emerald-600 hover:bg-emerald-700 shadow-lg shadow-emerald-200 transition-all active:scale-95 flex items-center justify-center">
                    Sí, Confirmar
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Custom Modal: Rechazar Pago -->
<div id="modalRechazarPago" class="fixed inset-0 z-50 hidden opacity-0 transition-opacity duration-300 flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm" onclick="closeModal('modalRechazarPago')"></div>
    <div class="modal-content relative bg-white w-full max-w-md rounded-2xl shadow-2xl overflow-hidden transform scale-95 opacity-0 transition-all duration-300 border border-gray-100">
        <div class="h-2 bg-gradient-to-r from-red-500 to-rose-600"></div>
        <div class="p-8">
            <div class="w-16 h-16 bg-red-50 rounded-2xl flex items-center justify-center mb-6 ring-4 ring-red-50/50">
                <i class="bi bi-x-circle-fill text-red-500 text-3xl"></i>
            </div>
            <h3 class="text-2xl font-display font-bold text-gray-900 mb-2">¿Rechazar este pago?</h3>
            <p class="text-gray-500 mb-6 font-medium">Por favor, indique el motivo del rechazo para informar al paciente.</p>
            <div class="space-y-1.5 text-left">
                <label for="motivo_input" class="text-sm font-bold text-gray-700 ml-1">Motivo del rechazo</label>
                <textarea id="motivo_input" rows="3" 
                    class="w-full px-4 py-3 rounded-xl border-gray-200 bg-gray-50 text-gray-900 focus:bg-white focus:ring-2 focus:ring-red-500/20 focus:border-red-500 transition-all resize-none placeholder:text-gray-400"
                    placeholder="Escriba aquí el motivo..."
                    oninput="document.getElementById('motivo_error').classList.add('hidden')"></textarea>
                <p id="motivo_error" class="hidden text-xs font-bold text-red-500 mt-1 flex items-center gap-1">
                    <i class="bi bi-exclamation-circle"></i> Debe ingresar un motivo
                </p>
            </div>
            <div class="flex gap-3 mt-8">
                <button onclick="closeModal('modalRechazarPago')" 
                    class="flex-1 px-6 py-3.5 rounded-xl font-bold text-gray-600 bg-gray-100 hover:bg-gray-200 transition-all active:scale-95">
                    Cancelar
                </button>
                <button id="rejectBtn" onclick="ejecutarRechazo()" 
                    class="flex-1 px-6 py-3.5 rounded-xl font-bold text-white bg-red-600 hover:bg-red-700 shadow-lg shadow-red-200 transition-all active:scale-95 flex items-center justify-center">
                    Rechazar
                </button>
            </div>
        </div>
    </div>
</div>
@endpush
@endsection
