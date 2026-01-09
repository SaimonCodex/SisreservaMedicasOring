@extends('layouts.admin')

@section('title', 'Pagos')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-display font-bold text-gray-900">Gestión de Pagos</h1>
            <p class="text-gray-600 mt-1">Registro y seguimiento de pagos</p>
        </div>
        <a href="{{ url('index.php/shared/pagos/create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i>
            <span>Registrar Pago</span>
        </a>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="card p-6 bg-gradient-to-br from-emerald-50 to-emerald-100 border-emerald-200">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-emerald-600 rounded-xl flex items-center justify-center">
                    <i class="bi bi-cash-stack text-white text-xl"></i>
                </div>
                <div>
                    <p class="text-sm text-emerald-700">Hoy</p>
                    <p class="text-2xl font-bold text-emerald-900">${{ number_format($stats['hoy'] ?? 0, 2) }}</p>
                </div>
            </div>
        </div>

        <div class="card p-6 bg-gradient-to-br from-blue-50 to-blue-100 border-blue-200">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-blue-600 rounded-xl flex items-center justify-center">
                    <i class="bi bi-calendar-month text-white text-xl"></i>
                </div>
                <div>
                    <p class="text-sm text-blue-700">Este Mes</p>
                    <p class="text-2xl font-bold text-blue-900">${{ number_format($stats['mes'] ?? 0, 2) }}</p>
                </div>
            </div>
        </div>

        <div class="card p-6 bg-gradient-to-br from-purple-50 to-purple-100 border-purple-200">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-purple-600 rounded-xl flex items-center justify-center">
                    <i class="bi bi-receipt text-white text-xl"></i>
                </div>
                <div>
                    <p class="text-sm text-purple-700">Total Pagos</p>
                    <p class="text-2xl font-bold text-purple-900">{{ $stats['total'] ?? 0 }}</p>
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
                    <p class="text-2xl font-bold text-amber-900">{{ $stats['pendientes'] ?? 0 }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card p-6">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-6 gap-4">
            <div>
                <label class="form-label">Buscar</label>
                <input type="text" name="search" class="input" placeholder="Referencia o paciente..." value="{{ request('search') }}">
            </div>
            <div>
                <label class="form-label">Método</label>
                <select name="metodo" class="form-select">
                    <option value="">Todos</option>
                    <option value="efectivo" {{ request('metodo') == 'efectivo' ? 'selected' : '' }}>Efectivo</option>
                    <option value="tarjeta" {{ request('metodo') == 'tarjeta' ? 'selected' : '' }}>Tarjeta</option>
                    <option value="transferencia" {{ request('metodo') == 'transferencia' ? 'selected' : '' }}>Transferencia</option>
                    <option value="cheque" {{ request('metodo') == 'cheque' ? 'selected' : '' }}>Cheque</option>
                </select>
            </div>
            <div>
                <label class="form-label">Estado</label>
                <select name="estado" class="form-select">
                    <option value="">Todos</option>
                    <option value="completado" {{ request('estado') == 'completado' ? 'selected' : '' }}>Completado</option>
                    <option value="pendiente" {{ request('estado') == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                    <option value="reembolsado" {{ request('estado') == 'reembolsado' ? 'selected' : '' }}>Reembolsado</option>
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
                    <i class="bi bi-search"></i>
                </button>
                <a href="{{ url('index.php/shared/pagos') }}" class="btn btn-outline">
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
                        <th class="w-32">Referencia</th>
                        <th>Paciente</th>
                        <th>Factura</th>
                        <th class="w-28">Método</th>
                        <th class="w-32">Fecha</th>
                        <th class="w-32">Monto</th>
                        <th class="w-24">Estado</th>
                        <th class="w-32">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pagos ?? [] as $pago)
                    <tr>
                        <td>
                            <span class="font-mono text-sm font-semibold text-gray-900">{{ $pago->referencia ?? 'N/A' }}</span>
                        </td>
                        <td>
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                                    <i class="bi bi-person text-purple-600"></i>
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-900">{{ $pago->paciente->nombre_completo ?? 'N/A' }}</p>
                                    <p class="text-sm text-gray-500">{{ $pago->paciente->cedula ?? 'N/A' }}</p>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="text-gray-700">{{ $pago->factura->numero ?? 'N/A' }}</span>
                        </td>
                        <td>
                            <span class="badge badge-info">{{ ucfirst($pago->metodo ?? 'N/A') }}</span>
                        </td>
                        <td>
                            <span class="text-gray-600">{{ isset($pago->fecha) ? \Carbon\Carbon::parse($pago->fecha)->format('d/m/Y') : 'N/A' }}</span>
                        </td>
                        <td>
                            <span class="font-bold text-gray-900">${{ number_format($pago->monto ?? 0, 2) }}</span>
                        </td>
                        <td>
                            @if($pago->estado == 'completado')
                            <span class="badge badge-success">Completado</span>
                            @elseif($pago->estado == 'pendiente')
                            <span class="badge badge-warning">Pendiente</span>
                            @else
                            <span class="badge badge-secondary">Reembolsado</span>
                            @endif
                        </td>
                        <td>
                            <div class="flex gap-2">
                                <a href="{{ url('index.php/shared/pagos/' . $pago->id) }}" class="btn btn-sm btn-outline" title="Ver">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ url('index.php/shared/pagos/' . $pago->id . '/recibo') }}" class="btn btn-sm btn-outline text-blue-600" title="Recibo">
                                    <i class="bi bi-file-earmark-text"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-12">
                            <i class="bi bi-inbox text-5xl text-gray-300 mb-3"></i>
                            <p class="text-gray-500">No se encontraron pagos</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if(isset($pagos) && $pagos->hasPages())
        <div class="p-6 border-t border-gray-200">
            {{ $pagos->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
