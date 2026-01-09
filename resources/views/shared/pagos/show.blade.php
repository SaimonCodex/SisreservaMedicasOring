@extends('layouts.admin')

@section('title', 'Detalle de Pago')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-4">
            <a href="{{ url('index.php/shared/pagos') }}" class="btn btn-outline">
                <i class="bi bi-arrow-left"></i>
            </a>
            <div>
                <h1 class="text-2xl font-display font-bold text-gray-900">Pago {{ $pago->referencia ?? 'N/A' }}</h1>
                <p class="text-gray-600 mt-1">Detalle completo del pago</p>
            </div>
        </div>
        <div class="flex gap-2">
            <a href="{{ url('index.php/shared/pagos/' . $pago->id . '/recibo') }}" class="btn btn-primary">
                <i class="bi bi-file-earmark-text"></i> Generar Recibo
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Payment Receipt -->
            <div class="card p-8">
                <div class="text-center mb-8 pb-6 border-b-2 border-gray-200">
                    <div class="w-20 h-20 mx-auto rounded-full bg-emerald-100 flex items-center justify-center mb-4">
                        <i class="bi bi-check-circle text-emerald-600 text-4xl"></i>
                    </div>
                    <h2 class="text-2xl font-display font-bold text-gray-900 mb-2">Pago Recibido</h2>
                    <p class="text-gray-600">Referencia: <span class="font-mono font-semibold">{{ $pago->referencia ?? 'N/A' }}</span></p>
                </div>

                <!-- Payment Details Grid -->
                <div class="grid grid-cols-2 gap-6 mb-8">
                    <div>
                        <h3 class="text-sm font-semibold text-gray-500 uppercase mb-3">Paciente</h3>
                        <div class="p-4 bg-gray-50 rounded-lg">
                            <p class="font-bold text-gray-900">{{ $pago->paciente->nombre_completo ?? 'N/A' }}</p>
                            <p class="text-gray-600 text-sm mt-1">{{ $pago->paciente->cedula ?? 'N/A' }}</p>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-sm font-semibold text-gray-500 uppercase mb-3">Factura</h3>
                        <div class="p-4 bg-gray-50 rounded-lg">
                            <p class="font-bold text-gray-900">#{{ $pago->factura->numero ?? 'N/A' }}</p>
                            <p class="text-gray-600 text-sm mt-1">{{ $pago->factura->concepto ?? 'Consulta' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Payment Info -->
                <div class="space-y-4">
                    <div class="flex justify-between items-center py-3 border-b border-gray-200">
                        <span class="text-gray-600">Método de Pago:</span>
                        <span class="font-semibold text-gray-900">{{ ucfirst($pago->metodo ?? 'N/A') }}</span>
                    </div>

                    <div class="flex justify-between items-center py-3 border-b border-gray-200">
                        <span class="text-gray-600">Fecha de Pago:</span>
                        <span class="font-semibold text-gray-900">{{ isset($pago->fecha) ? \Carbon\Carbon::parse($pago->fecha)->format('d/m/Y') : 'N/A' }}</span>
                    </div>

                    @if($pago->referencia_bancaria)
                    <div class="flex justify-between items-center py-3 border-b border-gray-200">
                        <span class="text-gray-600">Referencia Bancaria:</span>
                        <span class="font-mono font-semibold text-gray-900">{{ $pago->referencia_bancaria }}</span>
                    </div>
                    @endif

                    @if($pago->banco)
                    <div class="flex justify-between items-center py-3 border-b border-gray-200">
                        <span class="text-gray-600">Banco:</span>
                        <span class="font-semibold text-gray-900">{{ $pago->banco }}</span>
                    </div>
                    @endif

                    <div class="flex justify-between items-center py-6 border-t-2 border-gray-300 mt-4">
                        <span class="text-xl font-bold text-gray-900">Monto Pagado:</span>
                        <span class="text-4xl font-bold text-emerald-700">${{ number_format($pago->monto ?? 0, 2) }}</span>
                    </div>
                </div>

                @if($pago->notas)
                <div class="mt-6 p-4 bg-blue-50 rounded-lg">
                    <p class="text-sm"><strong class="text-gray-900">Notas:</strong></p>
                    <p class="text-gray-700 mt-1">{{ $pago->notas }}</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Status -->
            <div class="card p-6">
                <h3 class="text-lg font-display font-bold text-gray-900 mb-4">Estado</h3>
                <div class="text-center">
                    @if($pago->estado == 'completado')
                    <div class="w-20 h-20 mx-auto rounded-full bg-emerald-100 flex items-center justify-center mb-3">
                        <i class="bi bi-check-circle text-emerald-600 text-4xl"></i>
                    </div>
                    <p class="text-xl font-bold text-emerald-700">Completado</p>
                    @elseif($pago->estado == 'pendiente')
                    <div class="w-20 h-20 mx-auto rounded-full bg-amber-100 flex items-center justify-center mb-3">
                        <i class="bi bi-clock-history text-amber-600 text-4xl"></i>
                    </div>
                    <p class="text-xl font-bold text-amber-700">Pendiente</p>
                    @else
                    <div class="w-20 h-20 mx-auto rounded-full bg-gray-100 flex items-center justify-center mb-3">
                        <i class="bi bi-arrow-counterclockwise text-gray-600 text-4xl"></i>
                    </div>
                    <p class="text-xl font-bold text-gray-700">Reembolsado</p>
                    @endif
                </div>
            </div>

            <!-- Timeline -->
            <div class="card p-6">
                <h3 class="text-lg font-display font-bold text-gray-900 mb-4">Línea de Tiempo</h3>
                <div class="space-y-4">
                    <div class="flex gap-3">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-emerald-100 flex items-center justify-center">
                            <i class="bi bi-check text-emerald-600 text-sm"></i>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-900 text-sm">Pago Registrado</p>
                            <p class="text-xs text-gray-500 mt-1">
                                {{ isset($pago->created_at) ? \Carbon\Carbon::parse($pago->created_at)->format('d/m/Y H:i') : 'N/A' }}
                            </p>
                        </div>
                    </div>

                    @if($pago->estado == 'completado')
                    <div class="flex gap-3">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-emerald-100 flex items-center justify-center">
                            <i class="bi bi-check-circle text-emerald-600 text-sm"></i>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-900 text-sm">Pago Confirmado</p>
                            <p class="text-xs text-gray-500 mt-1">
                                {{ isset($pago->updated_at) ? \Carbon\Carbon::parse($pago->updated_at)->format('d/m/Y H:i') : 'N/A' }}
                            </p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Actions -->
            <div class="card p-6">
                <h3 class="text-lg font-display font-bold text-gray-900 mb-4">Acciones</h3>
                <div class="space-y-2">
                    <a href="{{ url('index.php/shared/pagos/' . $pago->id . '/recibo') }}" class="btn btn-outline w-full justify-start">
                        <i class="bi bi-file-earmark-text"></i> Generar Recibo
                    </a>
                    <a href="{{ url('index.php/shared/pagos/' . $pago->id . '/email') }}" class="btn btn-outline w-full justify-start">
                        <i class="bi bi-envelope"></i> Enviar por Email
                    </a>
                    <button onclick="window.print()" class="btn btn-outline w-full justify-start">
                        <i class="bi bi-printer"></i> Imprimir
                    </button>
                    @if($pago->estado == 'completado')
                    <button class="btn btn-outline w-full justify-start text-rose-600 hover:bg-rose-50">
                        <i class="bi bi-arrow-counterclockwise"></i> Solicitar Reembolso
                    </button>
                    @endif
                </div>
            </div>

            <!-- Related Invoice -->
            <div class="card p-6">
                <h3 class="text-lg font-display font-bold text-gray-900 mb-3">Factura Relacionada</h3>
                <a href="{{ url('index.php/shared/facturacion/' . $pago->factura_id) }}" class="block p-4 bg-blue-50 hover:bg-blue-100 rounded-lg transition-colors">
                    <p class="font-semibold text-blue-900">#{{ $pago->factura->numero ?? 'N/A' }}</p>
                    <p class="text-sm text-gray-600 mt-1">{{ $pago->factura->concepto ?? 'Ver factura' }}</p>
                    <p class="text-sm text-blue-600 mt-2 flex items-center gap-1">
                        Ver detalles <i class="bi bi-arrow-right"></i>
                    </p>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
