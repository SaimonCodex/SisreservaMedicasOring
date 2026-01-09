@extends('layouts.admin')

@section('title', 'Mis Pagos')

@section('content')
<div class="mb-6">
    <div class="flex items-center justify-between gap-4">
        <div>
            <h2 class="text-3xl font-display font-bold text-gray-900">Mis Pagos</h2>
            <p class="text-gray-500 mt-1">Historial de pagos realizados</p>
        </div>
    </div>
</div>

<!-- Filtros -->
<div class="card p-6 mb-6">
    <form method="GET" action="{{ route('pagos.mis-pagos') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
            <label class="form-label">Fecha Inicio</label>
            <input type="date" name="fecha_inicio" class="input" value="{{ request('fecha_inicio') }}">
        </div>

        <div>
            <label class="form-label">Fecha Fin</label>
            <input type="date" name="fecha_fin" class="input" value="{{ request('fecha_fin') }}">
        </div>

        <div>
            <label class="form-label">Estado</label>
            <select name="estado" class="form-select">
                <option value="">Todos</option>
                <option value="Pendiente" {{ request('estado') == 'Pendiente' ? 'selected' : '' }}>Pendiente</option>
                <option value="Procesado" {{ request('estado') == 'Procesado' ? 'selected' : '' }}>Procesado</option>
                <option value="Verificado" {{ request('estado') == 'Verificado' ? 'selected' : '' }}>Verificado</option>
                <option value="Rechazado" {{ request('estado') == 'Rechazado' ? 'selected' : '' }}>Rechazado</option>
            </select>
        </div>

        <div class="md:col-span-3 flex gap-3">
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-funnel mr-2"></i>
                Filtrar
            </button>
            <a href="{{ route('pagos.mis-pagos') }}" class="btn btn-outline">
                <i class="bi bi-x-lg mr-2"></i>
                Limpiar
            </a>
        </div>
    </form>
</div>

<!-- Estadísticas -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
    <div class="card p-4 border-l-4 border-l-info-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 mb-1">Total Pagado</p>
                <p class="text-2xl font-bold text-gray-900">${{ number_format($pagos->sum('monto_pagado'), 2) }}</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-info-50 flex items-center justify-center">
                <i class="bi bi-cash-stack text-info-600 text-2xl"></i>
            </div>
        </div>
    </div>

    <div class="card p-4 border-l-4 border-l-success-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 mb-1">Verificados</p>
                <p class="text-2xl font-bold text-gray-900">{{ $pagos->where('estado_pago', 'Verificado')->count() }}</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-success-50 flex items-center justify-center">
                <i class="bi bi-check-circle text-success-600 text-2xl"></i>
            </div>
        </div>
    </div>

    <div class="card p-4 border-l-4 border-l-warning-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 mb-1">Pendientes</p>
                <p class="text-2xl font-bold text-gray-900">{{ $pagos->where('estado_pago', 'Pendiente')->count() }}</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-warning-50 flex items-center justify-center">
                <i class="bi bi-clock text-warning-600 text-2xl"></i>
            </div>
        </div>
    </div>

    <div class="card p-4 border-l-4 border-l-medical-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 mb-1">Total Pagos</p>
                <p class="text-2xl font-bold text-gray-900">{{ $pagos->total() }}</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-medical-50 flex items-center justify-center">
                <i class="bi bi-receipt text-medical-600 text-2xl"></i>
            </div>
        </div>
    </div>
</div>

<!-- Tabla de Pagos -->
<div class="card overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gradient-to-r from-medical-600 to-medical-500 text-white">
                <tr>
                    <th class="px-6 py-4 text-left font-semibold">Fecha</th>
                    <th class="px-6 py-4 text-left font-semibold">Factura</th>
                    <th class="px-6 py-4 text-left font-semibold">Cita</th>
                    <th class="px-6 py-4 text-left font-semibold">Método</th>
                    <th class="px-6 py-4 text-right font-semibold">Monto</th>
                    <th class="px-6 py-4 text-center font-semibold">Estado</th>
                    <th class="px-6 py-4 text-center font-semibold">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($pagos as $pago)
                <tr class="hover:bg-medical-50 transition-colors">
                    <td class="px-6 py-4">
                        <p class="font-semibold text-gray-900">{{ $pago->fecha_pago ? \Carbon\Carbon::parse($pago->fecha_pago)->format('d/m/Y') : 'N/A' }}</p>
                        <p class="text-xs text-gray-500">{{ $pago->created_at->format('H:i') }}</p>
                    </td>
                    <td class="px-6 py-4">
                        <span class="font-mono font-semibold text-medical-600">{{ $pago->factura->numero_factura }}</span>
                    </td>
                    <td class="px-6 py-4">
                        <p class="font-semibold text-gray-900">{{ $pago->factura->cita->especialidad->nombre_especialidad ?? 'N/A' }}</p>
                        <p class="text-xs text-gray-500">
                            Dr. {{ $pago->factura->cita->medico->primer_nombre }} {{ $pago->factura->cita->medico->primer_apellido }}
                        </p>
                    </td>
                    <td class="px-6 py-4">
                        <span class="badge badge-info">{{ $pago->metodo_pago }}</span>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <p class="font-bold text-success-600">${{ number_format($pago->monto_pagado, 2) }}</p>
                        @if($pago->numero_referencia)
                        <p class="text-xs text-gray-500">Ref: {{ $pago->numero_referencia }}</p>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-center">
                        <span class="badge badge-{{ $pago->estado_pago == 'Verificado' ? 'success' : ($pago->estado_pago == 'Rechazado' ? 'danger' : ($pago->estado_pago == 'Procesado' ? 'info' : 'warning')) }}">
                            {{ $pago->estado_pago }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center justify-center gap-2">
                            <a href="{{ route('pagos.show', $pago->id) }}" class="btn btn-sm btn-ghost text-medical-600" title="Ver detalle">
                                <i class="bi bi-eye"></i>
                            </a>
                            @if($pago->comprobante_pago)
                            <a href="{{ asset('storage/' . $pago->comprobante_pago) }}" target="_blank" class="btn btn-sm btn-ghost text-info-600" title="Ver comprobante">
                                <i class="bi bi-file-earmark-check"></i>
                            </a>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-12">
                        <div class="flex flex-col items-center justify-center">
                            <i class="bi bi-inbox text-5xl text-gray-300 mb-3"></i>
                            <p class="text-gray-500 font-medium mb-2">No tienes pagos registrados</p>
                            <p class="text-sm text-gray-400">Tus pagos aparecerán aquí cuando realices uno</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Paginación -->
    @if($pagos->hasPages())
    <div class="p-6 border-t border-gray-200">
        {{ $pagos->links() }}
    </div>
    @endif
</div>
@endsection
