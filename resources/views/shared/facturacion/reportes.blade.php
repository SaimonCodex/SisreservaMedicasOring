@extends('layouts.admin')

@section('title', 'Reportes de Facturación')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div>
        <h1 class="text-2xl font-display font-bold text-gray-900">Reportes de Facturación</h1>
        <p class="text-gray-600 mt-1">Análisis y estadísticas de facturación</p>
    </div>

    <!-- Stats Overview -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="card p-6 bg-gradient-to-br from-emerald-50 to-emerald-100 border-emerald-200">
            <p class="text-sm text-emerald-700 font-semibold mb-1">Total Facturado</p>
            <p class="text-3xl font-bold text-emerald-900">${{ number_format($totales['facturado'] ?? 0, 2) }}</p>
            <p class="text-xs text-emerald-600 mt-2">Este mes</p>
        </div>
        <div class="card p-6 bg-gradient-to-br from-blue-50 to-blue-100 border-blue-200">
            <p class="text-sm text-blue-700 font-semibold mb-1">Total Cobrado</p>
            <p class="text-3xl font-bold text-blue-900">${{ number_format($totales['cobrado'] ?? 0, 2) }}</p>
            <p class="text-xs text-blue-600 mt-2">{{ $totales['tasa_cobro'] ?? 0 }}% del total</p>
        </div>
        <div class="card p-6 bg-gradient-to-br from-amber-50 to-amber-100 border-amber-200">
            <p class="text-sm text-amber-700 font-semibold mb-1">Por Cobrar</p>
            <p class="text-3xl font-bold text-amber-900">${{ number_format($totales['por_cobrar'] ?? 0, 2) }}</p>
            <p class="text-xs text-amber-600 mt-2">{{ $totales['facturas_pendientes'] ?? 0 }} facturas</p>
        </div>
        <div class="card p-6 bg-gradient-to-br from-rose-50 to-rose-100 border-rose-200">
            <p class="text-sm text-rose-700 font-semibold mb-1">Vencidas</p>
            <p class="text-3xl font-bold text-rose-900">${{ number_format($totales['vencidas'] ?? 0, 2) }}</p>
            <p class="text-xs text-rose-600 mt-2">Requieren atención</p>
        </div>
    </div>

    <!-- Charts -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="card p-6">
            <h3 class="text-lg font-display font-bold text-gray-900 mb-4">Facturación Mensual</h3>
            <div class="h-64 flex items-end justify-between gap-2">
                @foreach($chart_data['mensual'] ?? [30, 45, 60, 40, 70, 55] as $valor)
                <div class="w-full bg-gradient-to-t from-emerald-500 to-emerald-300 rounded-t-lg hover:from-emerald-600 hover:to-emerald-400 transition-all" style="height: {{ $valor }}%"></div>
                @endforeach
            </div>
            <div class="flex justify-between mt-3 text-xs text-gray-500">
                <span>Ene</span><span>Feb</span><span>Mar</span><span>Abr</span><span>May</span><span>Jun</span>
            </div>
        </div>

        <div class="card p-6">
            <h3 class="text-lg font-display font-bold text-gray-900 mb-4">Estado de Facturas</h3>
            <div class="space-y-3">
                <div>
                    <div class="flex justify-between text-sm mb-1">
                        <span class="text-gray-700">Pagadas</span>
                        <span class="font-semibold">{{ $distribucion['pagadas'] ?? 0 }}%</span>
                    </div>
                    <div class="h-3 bg-gray-200 rounded-full overflow-hidden">
                        <div class="h-full bg-emerald-500" style="width: {{ $distribucion['pagadas'] ?? 0 }}%"></div>
                    </div>
                </div>
                <div>
                    <div class="flex justify-between text-sm mb-1">
                        <span class="text-gray-700">Pendientes</span>
                        <span class="font-semibold">{{ $distribucion['pendientes'] ?? 0 }}%</span>
                    </div>
                    <div class="h-3 bg-gray-200 rounded-full overflow-hidden">
                        <div class="h-full bg-amber-500" style="width: {{ $distribucion['pendientes'] ?? 0 }}%"></div>
                    </div>
                </div>
                <div>
                    <div class="flex justify-between text-sm mb-1">
                        <span class="text-gray-700">Vencidas</span>
                        <span class="font-semibold">{{ $distribucion['vencidas'] ?? 0 }}%</span>
                    </div>
                    <div class="h-3 bg-gray-200 rounded-full overflow-hidden">
                        <div class="h-full bg-rose-500" style="width: {{ $distribucion['vencidas'] ?? 0 }}%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Export Options -->
    <div class="card p-6">
        <h3 class="text-lg font-display font-bold text-gray-900 mb-4">Exportar Reportes</h3>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
            <button class="btn btn-outline">
                <i class="bi bi-file-pdf"></i> PDF
            </button>
            <button class="btn btn-outline">
                <i class="bi bi-file-excel"></i> Excel
            </button>
            <button class="btn btn-outline">
                <i class="bi bi-printer"></i> Imprimir
            </button>
            <button class="btn btn-outline">
                <i class="bi bi-envelope"></i> Enviar Email
            </button>
        </div>
    </div>
</div>
@endsection
