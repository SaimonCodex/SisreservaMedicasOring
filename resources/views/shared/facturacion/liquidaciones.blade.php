@extends('layouts.admin')

@section('title', 'Liquidaciones')

@section('content')
<div class="mb-6">
    <div class="flex items-center justify-between gap-4">
        <div>
            <h2 class="text-3xl font-display font-bold text-gray-900">Liquidaciones</h2>
            <p class="text-gray-500 mt-1">Pagos a médicos y liquidación de honorarios</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('facturacion.exportar-liquidaciones') }}" class="btn btn-success">
                <i class="bi bi-file-excel mr-2"></i>
                Exportar Excel
            </a>
            <a href="{{ route('facturacion.index') }}" class="btn btn-outline">
                <i class="bi bi-arrow-left mr-2"></i>
                Volver
            </a>
        </div>
    </div>
</div>

<!-- Filtros -->
<div class="card p-6 mb-6">
    <form method="GET" action="{{ route('facturacion.liquidaciones') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div>
            <label class="form-label">Fecha Inicio</label>
            <input type="date" name="fecha_inicio" class="input" value="{{ request('fecha_inicio') }}">
        </div>

        <div>
            <label class="form-label">Fecha Fin</label>
            <input type="date" name="fecha_fin" class="input" value="{{ request('fecha_fin') }}">
        </div>

        <div>
            <label class="form-label">Médico</label>
            <select name="medico_id" class="form-select">
                <option value="">Todos los médicos</option>
                @foreach($medicos ?? [] as $medico)
                <option value="{{ $medico->id }}" {{ request('medico_id') == $medico->id ? 'selected' : '' }}>
                    Dr. {{ $medico->primer_nombre }} {{ $medico->primer_apellido }}
                </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="form-label">Estado</label>
            <select name="estado" class="form-select">
                <option value="">Todos</option>
                <option value="Pendiente" {{ request('estado') == 'Pendiente' ? 'selected' : '' }}>Pendiente</option>
                <option value="Procesada" {{ request('estado') == 'Procesada' ? 'selected' : '' }}>Procesada</option>
                <option value="Pagada" {{ request('estado') == 'Pagada' ? 'selected' : '' }}>Pagada</option>
            </select>
        </div>

        <div class="md:col-span-4 flex gap-3">
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-funnel mr-2"></i>
                Filtrar
            </button>
            <a href="{{ route('facturacion.liquidaciones') }}" class="btn btn-outline">
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
                <p class="text-sm text-gray-500 mb-1">Total Liquidaciones</p>
                <p class="text-2xl font-bold text-gray-900">{{ $liquidaciones->count() }}</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-info-50 flex items-center justify-center">
                <i class="bi bi-file-text text-info-600 text-2xl"></i>
            </div>
        </div>
    </div>

    <div class="card p-4 border-l-4 border-l-success-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 mb-1">Total a Pagar</p>
                <p class="text-2xl font-bold text-gray-900">${{ number_format($liquidaciones->sum('monto_medico'), 2) }}</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-success-50 flex items-center justify-center">
                <i class="bi bi-cash-stack text-success-600 text-2xl"></i>
            </div>
        </div>
    </div>

    <div class="card p-4 border-l-4 border-l-warning-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 mb-1">Pendientes</p>
                <p class="text-2xl font-bold text-gray-900">{{ $liquidaciones->where('estado_pago', 'Pendiente')->count() }}</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-warning-50 flex items-center justify-center">
                <i class="bi bi-clock text-warning-600 text-2xl"></i>
            </div>
        </div>
    </div>

    <div class="card p-4 border-l-4 border-l-medical-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 mb-1">Pagadas</p>
                <p class="text-2xl font-bold text-gray-900">{{ $liquidaciones->where('estado_pago', 'Pagada')->count() }}</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-medical-50 flex items-center justify-center">
                <i class="bi bi-check-circle text-medical-600 text-2xl"></i>
            </div>
        </div>
    </div>
</div>

<!-- Tabla de Liquidaciones -->
<div class="card overflow-hidden">
    <div class="px-6 py-4 bg-gradient-to-r from-medical-600 to-medical-500">
        <h3 class="text-lg font-semibold text-white">Resumen de Liquidaciones</h3>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-3 text-left font-semibold text-gray-900">Factura</th>
                    <th class="px-6 py-3 text-left font-semibold text-gray-900">Fecha</th>
                    <th class="px-6 py-3 text-left font-semibold text-gray-900">Médico</th>
                    <th class="px-6 py-3 text-left font-semibold text-gray-900">Paciente</th>
                    <th class="px-6 py-3 text-right font-semibold text-gray-900">Total Factura</th>
                    <th class="px-6 py-3 text-right font-semibold text-gray-900">Honorarios</th>
                    <th class="px-6 py-3 text-center font-semibold text-gray-900">Estado</th>
                    <th class="px-6 py-3 text-center font-semibold text-gray-900">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($liquidaciones as $liquidacion)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4">
                        <span class="font-mono font-semibold text-medical-600">{{ $liquidacion->numero_factura }}</span>
                    </td>
                    <td class="px-6 py-4">
                        <p class="font-semibold text-gray-900">{{ $liquidacion->fecha_factura->format('d/m/Y') }}</p>
                        <p class="text-xs text-gray-500">{{ $liquidacion->fecha_factura->format('H:i') }}</p>
                    </td>
                    <td class="px-6 py-4">
                        <p class="font-semibold text-gray-900">
                            Dr. {{ $liquidacion->cita->medico->primer_nombre }} {{ $liquidacion->cita->medico->primer_apellido }}
                        </p>
                        <p class="text-xs text-gray-500">{{ $liquidacion->cita->especialidad->nombre_especialidad ?? 'N/A' }}</p>
                    </td>
                    <td class="px-6 py-4">
                        <p class="font-semibold text-gray-900">
                            {{ $liquidacion->cita->paciente->primer_nombre }} {{ $liquidacion->cita->paciente->primer_apellido }}
                        </p>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <p class="font-semibold text-gray-900">${{ number_format($liquidacion->monto_total, 2) }}</p>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <p class="font-bold text-success-600">${{ number_format($liquidacion->monto_medico, 2) }}</p>
                        <p class="text-xs text-gray-500">{{ $liquidacion->porcentaje_medico }}%</p>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <span class="badge badge-{{ $liquidacion->estado_pago == 'Pagada' ? 'success' : ($liquidacion->estado_pago == 'Procesada' ? 'info' : 'warning') }}">
                            {{ $liquidacion->estado_pago }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center justify-center gap-2">
                            <a href="{{ route('facturacion.show', $liquidacion->id) }}" class="btn btn-sm btn-ghost text-info-600" title="Ver detalle">
                                <i class="bi bi-eye"></i>
                            </a>
                            @if($liquidacion->estado_pago != 'Pagada')
                            <form action="{{ route('facturacion.marcar-pagada', $liquidacion->id) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-ghost text-success-600" title="Marcar como pagada">
                                    <i class="bi bi-check-circle"></i>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center py-12">
                        <i class="bi bi-inbox text-5xl text-gray-300 mb-3"></i>
                        <p class="text-gray-500">No se encontraron liquidaciones</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
            <tfoot class="bg-gray-50 border-t-2 border-gray-300">
                <tr>
                    <td colspan="4" class="px-6 py-4 text-right font-bold text-gray-900">TOTALES:</td>
                    <td class="px-6 py-4 text-right font-bold text-gray-900">${{ number_format($liquidaciones->sum('monto_total'), 2) }}</td>
                    <td class="px-6 py-4 text-right font-bold text-success-600">${{ number_format($liquidaciones->sum('monto_medico'), 2) }}</td>
                    <td colspan="2"></td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
@endsection
