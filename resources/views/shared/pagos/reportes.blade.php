@extends('layouts.admin')

@section('title', 'Reportes de Pagos')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div>
        <h1 class="text-2xl font-display font-bold text-gray-900">Reportes de Pagos</h1>
        <p class="text-gray-600 mt-1">Análisis y estadísticas de pagos recibidos</p>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
        <div class="card p-6 bg-gradient-to-br from-emerald-50 to-emerald-100 border-emerald-200">
            <p class="text-sm text-emerald-700 font-semibold mb-1">Efectivo</p>
            <p class="text-3xl font-bold text-emerald-900">${{ number_format($metodos['efectivo'] ?? 0, 2) }}</p>
        </div>
        <div class="card p-6 bg-gradient-to-br from-blue-50 to-blue-100 border-blue-200">
            <p class="text-sm text-blue-700 font-semibold mb-1">Tarjeta</p>
            <p class="text-3xl font-bold text-blue-900">${{ number_format($metodos['tarjeta'] ?? 0, 2) }}</p>
        </div>
        <div class="card p-6 bg-gradient-to-br from-purple-50 to-purple-100 border-purple-200">
            <p class="text-sm text-purple-700 font-semibold mb-1">Transferencia</p>
            <p class="text-3xl font-bold text-purple-900">${{ number_format($metodos['transferencia'] ?? 0, 2) }}</p>
        </div>
        <div class="card p-6 bg-gradient-to-br from-amber-50 to-amber-100 border-amber-200">
            <p class="text-sm text-amber-700 font-semibold mb-1">Otros</p>
            <p class="text-3xl font-bold text-amber-900">${{ number_format($metodos['otros'] ?? 0, 2) }}</p>
        </div>
        <div class="card p-6 bg-gradient-to-br from-indigo-50 to-indigo-100 border-indigo-200">
            <p class="text-sm text-indigo-700 font-semibold mb-1">Total</p>
            <p class="text-3xl font-bold text-indigo-900">${{ number_format($totales['total'] ?? 0, 2) }}</p>
        </div>
    </div>

    <!-- Charts -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="card p-6">
            <h3 class="text-lg font-display font-bold text-gray-900 mb-4">Pagos por Día (Últimos 7 días)</h3>
            <div class="h-64 flex items-end justify-between gap-2">
                @foreach($chart_data['diario'] ?? [50, 70, 45, 85, 60, 75, 90] as $valor)
                <div class="w-full bg-gradient-to-t from-blue-500 to-blue-300 rounded-t-lg hover:from-blue-600 hover:to-blue-400 transition-all" style="height: {{ $valor }}%"></div>
                @endforeach
            </div>
            <div class="flex justify-between mt-3 text-xs text-gray-500">
                <span>Lun</span><span>Mar</span><span>Mié</span><span>Jue</span><span>Vie</span><span>Sáb</span><span>Dom</span>
            </div>
        </div>

        <div class="card p-6">
            <h3 class="text-lg font-display font-bold text-gray-900 mb-4">Distribución por Método</h3>
            <div class="space-y-3">
                <div>
                    <div class="flex justify-between text-sm mb-1">
                        <span>Efectivo</span>
                        <span class="font-semibold">{{ $distribucion['efectivo'] ?? 0 }}%</span>
                    </div>
                    <div class="h-3 bg-gray-200 rounded-full">
                        <div class="h-full bg-emerald-500 rounded-full" style="width: {{ $distribucion['efectivo'] ?? 0 }}%"></div>
                    </div>
                </div>
                <div>
                    <div class="flex justify-between text-sm mb-1">
                        <span>Tarjeta</span>
                        <span class="font-semibold">{{ $distribucion['tarjeta'] ?? 0 }}%</span>
                    </div>
                    <div class="h-3 bg-gray-200 rounded-full">
                        <div class="h-full bg-blue-500 rounded-full" style="width: {{ $distribucion['tarjeta'] ?? 0 }}%"></div>
                    </div>
                </div>
                <div>
                    <div class="flex justify-between text-sm mb-1">
                        <span>Transferencia</span>
                        <span class="font-semibold">{{ $distribucion['transferencia'] ?? 0 }}%</span>
                    </div>
                    <div class="h-3 bg-gray-200 rounded-full">
                        <div class="h-full bg-purple-500 rounded-full" style="width: {{ $distribucion['transferencia'] ?? 0 }}%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Top Patients -->
    <div class="card">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-display font-bold text-gray-900">Pacientes con Más Pagos</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="table">
                <thead>
                    <tr>
                        <th>Paciente</th>
                        <th class="w-32">Total Pagos</th>
                        <th class="w-32">Monto Total</th>
                        <th class="w-32">Último Pago</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($top_pacientes ?? [] as $paciente)
                    <tr>
                        <td>
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                                    <i class="bi bi-person text-purple-600"></i>
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-900">{{ $paciente->nombre ?? 'N/A' }}</p>
                                    <p class="text-sm text-gray-500">{{ $paciente->cedula ?? 'N/A' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="text-center">
                            <span class="font-bold text-gray-900">{{ $paciente->total_pagos ?? 0 }}</span>
                        </td>
                        <td>
                            <span class="font-bold text-emerald-700">${{ number_format($paciente->monto_total ?? 0, 2) }}</span>
                        </td>
                        <td>
                            <span class="text-gray-600">{{ isset($paciente->ultimo_pago) ? \Carbon\Carbon::parse($paciente->ultimo_pago)->format('d/m/Y') : 'N/A' }}</span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center py-8 text-gray-500">No hay datos disponibles</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Export -->
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
                <i class="bi bi-envelope"></i> Email
            </button>
        </div>
    </div>
</div>
@endsection
