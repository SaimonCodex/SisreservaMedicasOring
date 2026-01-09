@extends('layouts.admin')

@section('title', 'Facturación')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-display font-bold text-gray-900">Gestión de Facturación</h1>
            <p class="text-gray-600 mt-1">Administra facturas y cobros</p>
        </div>
        <a href="{{ url('index.php/shared/facturacion/create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i>
            <span>Nueva Factura</span>
        </a>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="card p-6 bg-gradient-to-br from-emerald-50 to-emerald-100 border-emerald-200">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-emerald-600 rounded-xl flex items-center justify-center">
                    <i class="bi bi-check-circle text-white text-xl"></i>
                </div>
                <div>
                    <p class="text-sm text-emerald-700">Cobradas</p>
                    <p class="text-2xl font-bold text-emerald-900">${{ number_format($stats['cobradas'] ?? 0, 2) }}</p>
                </div>
            </div>
        </div>

        <div class="card p-6 bg-gradient-to-br from-amber-50 to-amber-100 border-amber-200">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-amber-600 rounded-xl flex items-center justify-center">
                    <i class="bi bi-clock-history text-white text-xl"></i>
                </div>
                <div>
                    <p class="text-sm text-amber-700">Pendientes</p>
                    <p class="text-2xl font-bold text-amber-900">${{ number_format($stats['pendientes'] ?? 0, 2) }}</p>
                </div>
            </div>
        </div>

        <div class="card p-6 bg-gradient-to-br from-rose-50 to-rose-100 border-rose-200">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-rose-600 rounded-xl flex items-center justify-center">
                    <i class="bi bi-exclamation-triangle text-white text-xl"></i>
                </div>
                <div>
                    <p class="text-sm text-rose-700">Vencidas</p>
                    <p class="text-2xl font-bold text-rose-900">${{ number_format($stats['vencidas'] ?? 0, 2) }}</p>
                </div>
            </div>
        </div>

        <div class="card p-6 bg-gradient-to-br from-blue-50 to-blue-100 border-blue-200">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-blue-600 rounded-xl flex items-center justify-center">
                    <i class="bi bi-receipt text-white text-xl"></i>
                </div>
                <div>
                    <p class="text-sm text-blue-700">Total Facturas</p>
                    <p class="text-2xl font-bold text-blue-900">{{ $stats['total'] ?? 0 }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card p-6">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <div>
                <label class="form-label">Buscar</label>
                <input type="text" name="search" class="input" placeholder="Número o paciente..." value="{{ request('search') }}">
            </div>
            <div>
                <label class="form-label">Estado</label>
                <select name="status" class="form-select">
                    <option value="">Todos</option>
                    <option value="pendiente" {{ request('status') == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                    <option value="pagada" {{ request('status') == 'pagada' ? 'selected' : '' }}>Pagada</option>
                    <option value="vencida" {{ request('status') == 'vencida' ? 'selected' : '' }}>Vencida</option>
                    <option value="cancelada" {{ request('status') == 'cancelada' ? 'selected' : '' }}>Cancelada</option>
                </select>
            </div>
            <div>
                <label class="form-label">Desde</label>
                <input type="date" name="fecha_desde" class="input" value="{{ request('fecha_desde') }}">
            </div>
            <div>
                <label class="form-label">Hasta</label>
                <input type="date" name="fecha_hasta" class="input" value="{{ request('fecha_hasta') }}">
            </div>
            <div class="flex items-end gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-search"></i> Buscar
                </button>
                <a href="{{ url('index.php/shared/facturacion') }}" class="btn btn-outline">
                    <i class="bi bi-x-lg"></i>
                </a>
            </div>
        </form>
    </div>

    <!-- Table -->
    <div class="card">
        <div class="overflow-x-auto">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th class="w-32">Número</th>
                        <th>Paciente</th>
                        <th>Concepto</th>
                        <th class="w-32">Fecha</th>
                        <th class="w-32">Monto</th>
                        <th class="w-24">Estado</th>
                        <th class="w-40">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($facturas ?? [] as $factura)
                    <tr>
                        <td>
                            <span class="font-mono text-sm font-semibold text-gray-900">{{ $factura->numero ?? 'N/A' }}</span>
                        </td>
                        <td>
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                    <i class="bi bi-person text-blue-600"></i>
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-900">{{ $factura->paciente->nombre_completo ?? 'N/A' }}</p>
                                    <p class="text-sm text-gray-500">{{ $factura->paciente->cedula ?? 'N/A' }}</p>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="text-gray-700">{{ $factura->concepto ?? 'Consulta médica' }}</span>
                        </td>
                        <td>
                            <span class="text-gray-600">{{ isset($factura->fecha) ? \Carbon\Carbon::parse($factura->fecha)->format('d/m/Y') : 'N/A' }}</span>
                        </td>
                        <td>
                            <span class="font-bold text-gray-900">${{ number_format($factura->monto ?? 0, 2) }}</span>
                        </td>
                        <td>
                            @if($factura->status == 'pagada')
                            <span class="badge badge-success">Pagada</span>
                            @elseif($factura->status == 'pendiente')
                            <span class="badge badge-warning">Pendiente</span>
                            @elseif($factura->status == 'vencida')
                            <span class="badge badge-danger">Vencida</span>
                            @else
                            <span class="badge badge-secondary">Cancelada</span>
                            @endif
                        </td>
                        <td>
                            <div class="flex gap-2">
                                <a href="{{ url('index.php/shared/facturacion/' . $factura->id) }}" class="btn btn-sm btn-outline" title="Ver">
                                    <i class="bi bi-eye"></i>
                                </a>
                                @if($factura->status != 'pagada')
                                <a href="{{ url('index.php/shared/facturacion/' . $factura->id . '/edit') }}" class="btn btn-sm btn-outline" title="Editar">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                @endif
                                <a href="{{ url('index.php/shared/facturacion/' . $factura->id . '/pdf') }}" class="btn btn-sm btn-outline text-rose-600" title="PDF">
                                    <i class="bi bi-file-pdf"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-12">
                            <i class="bi bi-inbox text-5xl text-gray-300 mb-3"></i>
                            <p class="text-gray-500">No se encontraron facturas</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if(isset($facturas) && $facturas->hasPages())
        <div class="p-6 border-t border-gray-200">
            {{ $facturas->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
