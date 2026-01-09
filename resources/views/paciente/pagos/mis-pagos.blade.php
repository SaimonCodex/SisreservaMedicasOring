@extends('layouts.paciente')

@section('title', 'Mis Pagos')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div>
        <h1 class="text-2xl font-display font-bold text-gray-900">Mis Pagos</h1>
        <p class="text-gray-600 mt-1">Gestiona tus pagos y facturas</p>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="card p-6 bg-gradient-to-br from-blue-50 to-blue-100 border-blue-200">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 rounded-xl bg-blue-600 flex items-center justify-center">
                    <i class="bi bi-cash-stack text-white text-2xl"></i>
                </div>
                <div>
                    <p class="text-sm text-blue-700">Total Pagado</p>
                    <p class="text-2xl font-bold text-blue-900">${{ number_format($stats['total_pagado'] ?? 0, 2) }}</p>
                </div>
            </div>
        </div>

        <div class="card p-6 bg-gradient-to-br from-amber-50 to-amber-100 border-amber-200">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 rounded-xl bg-amber-600 flex items-center justify-center">
                    <i class="bi bi-clock-history text-white text-2xl"></i>
                </div>
                <div>
                    <p class="text-sm text-amber-700">Pendiente</p>
                    <p class="text-2xl font-bold text-amber-900">${{ number_format($stats['pendiente'] ?? 0, 2) }}</p>
                </div>
            </div>
        </div>

        <div class="card p-6 bg-gradient-to-br from-emerald-50 to-emerald-100 border-emerald-200">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 rounded-xl bg-emerald-600 flex items-center justify-center">
                    <i class="bi bi-file-earmark-text text-white text-2xl"></i>
                </div>
                <div>
                    <p class="text-sm text-emerald-700">Facturas</p>
                    <p class="text-2xl font-bold text-emerald-900">{{ $stats['total_facturas'] ?? 0 }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabs -->
    <div class="card p-6">
        <div class="flex flex-wrap gap-2 border-b border-gray-200 pb-4 mb-6">
            <button class="tab-button active" data-tab="pendientes">
                <i class="bi bi-clock"></i> Pendientes
            </button>
            <button class="tab-button" data-tab="pagados">
                <i class="bi bi-check-circle"></i> Pagados
            </button>
            <button class="tab-button" data-tab="facturas">
                <i class="bi bi-file-earmark-text"></i> Facturas
            </button>
        </div>

        <!-- Pendientes Tab -->
        <div id="tab-pendientes" class="tab-content">
            <div class="space-y-4">
                @forelse($pagosPendientes ?? [] as $pago)
                <div class="card p-6 bg-amber-50 border-amber-200">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <h3 class="font-bold text-gray-900">{{ $pago->concepto ?? 'Pago de Consulta' }}</h3>
                            <p class="text-sm text-gray-600 mt-1">
                                {{ isset($pago->created_at) ? \Carbon\Carbon::parse($pago->created_at)->format('d/m/Y') : 'N/A' }}
                            </p>
                            <p class="text-xs text-gray-500 mt-2">
                                Vence: {{ isset($pago->fecha_vencimiento) ? \Carbon\Carbon::parse($pago->fecha_vencimiento)->format('d/m/Y') : 'N/A' }}
                            </p>
                        </div>
                        <div class="text-right">
                            <p class="text-2xl font-bold text-amber-900">${{ number_format($pago->monto ?? 0, 2) }}</p>
                            <button class="btn btn-sm btn-success mt-2">
                                <i class="bi bi-credit-card"></i> Pagar Ahora
                            </button>
                        </div>
                    </div>
                </div>
                @empty
                <div class="text-center py-12">
                    <i class="bi bi-check-circle text-5xl text-emerald-300 mb-3"></i>
                    <p class="text-gray-500 font-medium">No tienes pagos pendientes</p>
                    <p class="text-sm text-gray-400">¡Estás al día con tus pagos!</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Pagados Tab -->
        <div id="tab-pagados" class="tab-content hidden">
            <div class="overflow-x-auto">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Concepto</th>
                            <th>Método</th>
                            <th>Monto</th>
                            <th class="w-32">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pagosPagados ?? [] as $pago)
                        <tr>
                            <td>
                                <span class="text-sm">
                                    {{ isset($pago->fecha_pago) ? \Carbon\Carbon::parse($pago->fecha_pago)->format('d/m/Y') : 'N/A' }}
                                </span>
                            </td>
                            <td>
                                <p class="font-semibold text-gray-900">{{ $pago->concepto ?? 'N/A' }}</p>
                            </td>
                            <td>
                                <span class="badge badge-info">{{ ucfirst($pago->metodo_pago ?? 'N/A') }}</span>
                            </td>
                            <td>
                                <span class="font-bold text-emerald-600">${{ number_format($pago->monto ?? 0, 2) }}</span>
                            </td>
                            <td>
                                <div class="flex gap-2">
                                    <button class="btn btn-sm btn-outline" title="Ver Recibo">
                                        <i class="bi bi-file-earmark-pdf"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline" title="Descargar">
                                        <i class="bi bi-download"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-12">
                                <i class="bi bi-inbox text-4xl text-gray-300 mb-3"></i>
                                <p class="text-gray-500">No hay pagos registrados</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Facturas Tab -->
        <div id="tab-facturas" class="tab-content hidden">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @forelse($facturas ?? [] as $factura)
                <div class="card p-6 hover:shadow-md transition-shadow cursor-pointer">
                    <div class="flex items-start gap-4">
                        <div class="w-14 h-14 rounded-xl bg-blue-100 flex items-center justify-center flex-shrink-0">
                            <i class="bi bi-file-earmark-text text-blue-600 text-2xl"></i>
                        </div>
                        <div class="flex-1">
                            <h4 class="font-bold text-gray-900">Factura #{{ $factura->numero ?? '0000' }}</h4>
                            <p class="text-sm text-gray-600 mt-1">
                                {{ isset($factura->fecha) ? \Carbon\Carbon::parse($factura->fecha)->format('d/m/Y') : 'N/A' }}
                            </p>
                            <p class="text-lg font-bold text-blue-600 mt-2">
                                ${{ number_format($factura->total ?? 0, 2) }}
                            </p>
                            <div class="flex gap-2 mt-3">
                                <button class="btn btn-sm btn-outline">
                                    <i class="bi bi-eye"></i> Ver
                                </button>
                                <button class="btn btn-sm btn-primary">
                                    <i class="bi bi-download"></i> Descargar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-span-2 text-center py-12">
                    <i class="bi bi-file-earmark-x text-5xl text-gray-300 mb-3"></i>
                    <p class="text-gray-500 font-medium">No hay facturas disponibles</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Payment Methods -->
    <div class="card p-6">
        <h3 class="text-lg font-display font-bold text-gray-900 mb-4 flex items-center gap-2">
            <i class="bi bi-credit-card text-blue-600"></i>
            Métodos de Pago
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="p-4 border-2 border-gray-200 rounded-xl hover:border-blue-500 transition-colors cursor-pointer">
                <i class="bi bi-credit-card-2-front text-3xl text-blue-600 mb-2"></i>
                <h4 class="font-semibold text-gray-900">Tarjeta de Crédito/Débito</h4>
                <p class="text-xs text-gray-500 mt-1">Visa, Mastercard, American Express</p>
            </div>
            <div class="p-4 border-2 border-gray-200 rounded-xl hover:border-emerald-500 transition-colors cursor-pointer">
                <i class="bi bi-bank text-3xl text-emerald-600 mb-2"></i>
                <h4 class="font-semibold text-gray-900">Transferencia Bancaria</h4>
                <p class="text-xs text-gray-500 mt-1">Pago directo desde tu banco</p>
            </div>
            <div class="p-4 border-2 border-gray-200 rounded-xl hover:border-purple-500 transition-colors cursor-pointer">
                <i class="bi bi-phone text-3xl text-purple-600 mb-2"></i>
                <h4 class="font-semibold text-gray-900">Pago Móvil</h4>
                <p class="text-xs text-gray-500 mt-1">PayPal, Zelle, otros</p>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const tabButtons = document.querySelectorAll('.tab-button');
        const tabContents = document.querySelectorAll('.tab-content');

        tabButtons.forEach(button => {
            button.addEventListener('click', function() {
                const tabName = this.dataset.tab;

                // Remove active from all buttons
                tabButtons.forEach(btn => btn.classList.remove('active'));
                // Hide all tabs
                tabContents.forEach(content => content.classList.add('hidden'));

                // Activate clicked tab
                this.classList.add('active');
                document.getElementById('tab-' + tabName).classList.remove('hidden');
            });
        });
    });
</script>

<style>
    .tab-button {
        @apply px-4 py-2 rounded-lg font-semibold text-gray-600 hover:bg-gray-100 transition-colors;
    }
    .tab-button.active {
        @apply bg-blue-600 text-white hover:bg-blue-700;
    }
</style>
@endpush
@endsection
