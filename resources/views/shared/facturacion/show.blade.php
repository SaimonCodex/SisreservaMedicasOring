@extends('layouts.admin')

@section('title', 'Detalle de Factura')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-4">
            <a href="{{ url('index.php/shared/facturacion') }}" class="btn btn-outline">
                <i class="bi bi-arrow-left"></i>
            </a>
            <div>
                <h1 class="text-2xl font-display font-bold text-gray-900">Factura #{{ $factura->numero ?? 'N/A' }}</h1>
                <p class="text-gray-600 mt-1">Detalle completo de la factura</p>
            </div>
        </div>
        <div class="flex gap-2">
            <a href="{{ url('index.php/shared/facturacion/' . $factura->id . '/pdf') }}" class="btn btn-primary">
                <i class="bi bi-file-pdf"></i> Descargar PDF
            </a>
            @if($factura->status != 'pagada')
            <a href="{{ url('index.php/shared/facturacion/' . $factura->id . '/edit') }}" class="btn btn-outline">
                <i class="bi bi-pencil"></i> Editar
            </a>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Invoice Document -->
            <div class="card p-8">
                <!-- Header -->
                <div class="flex justify-between items-start mb-8 pb-8 border-b-2 border-gray-200">
                    <div>
                        <h2 class="text-3xl font-display font-bold text-gray-900 mb-2">FACTURA</h2>
                        <p class="text-gray-600">Sistema de Reservas Médicas</p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm text-gray-500">Número de Factura</p>
                        <p class="text-2xl font-bold text-gray-900">#{{ $factura->numero ?? 'N/A' }}</p>
                        <p class="text-sm text-gray-500 mt-2">Fecha: {{ isset($factura->fecha) ? \Carbon\Carbon::parse($factura->fecha)->format('d/m/Y') : 'N/A' }}</p>
                    </div>
                </div>

                <!-- Patient Info -->
                <div class="mb-8">
                    <h3 class="text-sm font-semibold text-gray-500 uppercase mb-3">Paciente</h3>
                    <div class="p-4 bg-gray-50 rounded-lg">
                        <p class="font-bold text-gray-900 text-lg">{{ $factura->paciente->nombre_completo ?? 'N/A' }}</p>
                        <p class="text-gray-600 mt-1">Cédula: {{ $factura->paciente->cedula ?? 'N/A' }}</p>
                        <p class="text-gray-600">Teléfono: {{ $factura->paciente->telefono ?? 'N/A' }}</p>
                        <p class="text-gray-600">Email: {{ $factura->paciente->email ?? 'N/A' }}</p>
                    </div>
                </div>

                <!-- Invoice Details -->
                <div class="mb-8">
                    <h3 class="text-sm font-semibold text-gray-500 uppercase mb-3">Detalle</h3>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="border-b-2 border-gray-300">
                                    <th class="text-left py-3 text-gray-700 font-semibold">Concepto</th>
                                    <th class="text-right py-3 text-gray-700 font-semibold">Cantidad</th>
                                    <th class="text-right py-3 text-gray-700 font-semibold">Precio</th>
                                    <th class="text-right py-3 text-gray-700 font-semibold">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="border-b border-gray-200">
                                    <td class="py-4">
                                        <p class="font-semibold text-gray-900">{{ $factura->concepto ?? 'Consulta Médica' }}</p>
                                        @if($factura->descripcion)
                                        <p class="text-sm text-gray-500">{{ $factura->descripcion }}</p>
                                        @endif
                                    </td>
                                    <td class="text-right text-gray-700">1</td>
                                    <td class="text-right text-gray-700">${{ number_format($factura->subtotal ?? 0, 2) }}</td>
                                    <td class="text-right font-bold text-gray-900">${{ number_format($factura->subtotal ?? 0, 2) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Totals -->
                <div class="flex justify-end">
                    <div class="w-64 space-y-2">
                        <div class="flex justify-between text-gray-700">
                            <span>Subtotal:</span>
                            <span>${{ number_format($factura->subtotal ?? 0, 2) }}</span>
                        </div>
                        @if($factura->descuento > 0)
                        <div class="flex justify-between text-gray-700">
                            <span>Descuento ({{ $factura->descuento }}%):</span>
                            <span class="text-rose-600">-${{ number_format(($factura->subtotal * $factura->descuento / 100) ?? 0, 2) }}</span>
                        </div>
                        @endif
                        @if($factura->iva > 0)
                        <div class="flex justify-between text-gray-700">
                            <span>IVA ({{ $factura->iva }}%):</span>
                            <span>${{ number_format((($factura->subtotal - ($factura->subtotal * $factura->descuento / 100)) * $factura->iva / 100) ?? 0, 2) }}</span>
                        </div>
                        @endif
                        <div class="flex justify-between pt-3 border-t-2 border-gray-300">
                            <span class="text-lg font-bold text-gray-900">Total:</span>
                            <span class="text-2xl font-bold text-emerald-700">${{ number_format($factura->total ?? 0, 2) }}</span>
                        </div>
                    </div>
                </div>

                <!-- Footer Note -->
                @if($factura->notas)
                <div class="mt-8 pt-6 border-t border-gray-200">
                    <p class="text-sm text-gray-600"><strong>Notas:</strong> {{ $factura->notas }}</p>
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
                    @if($factura->status == 'pagada')
                    <div class="w-20 h-20 mx-auto rounded-full bg-emerald-100 flex items-center justify-center mb-3">
                        <i class="bi bi-check-circle text-emerald-600 text-4xl"></i>
                    </div>
                    <p class="text-xl font-bold text-emerald-700">Pagada</p>
                    <p class="text-sm text-gray-600 mt-1">{{ isset($factura->fecha_pago) ? \Carbon\Carbon::parse($factura->fecha_pago)->format('d/m/Y') : '' }}</p>
                    @elseif($factura->status == 'pendiente')
                    <div class="w-20 h-20 mx-auto rounded-full bg-amber-100 flex items-center justify-center mb-3">
                        <i class="bi bi-clock-history text-amber-600 text-4xl"></i>
                    </div>
                    <p class="text-xl font-bold text-amber-700">Pendiente</p>
                    @elseif($factura->status == 'vencida')
                    <div class="w-20 h-20 mx-auto rounded-full bg-rose-100 flex items-center justify-center mb-3">
                        <i class="bi bi-exclamation-triangle text-rose-600 text-4xl"></i>
                    </div>
                    <p class="text-xl font-bold text-rose-700">Vencida</p>
                    @else
                    <div class="w-20 h-20 mx-auto rounded-full bg-gray-100 flex items-center justify-center mb-3">
                        <i class="bi bi-x-circle text-gray-600 text-4xl"></i>
                    </div>
                    <p class="text-xl font-bold text-gray-700">Cancelada</p>
                    @endif
                </div>

                @if($factura->status != 'pagada' && $factura->status != 'cancelada')
                <div class="mt-6">
                    <a href="{{ url('index.php/shared/pagos/create?factura_id=' . $factura->id) }}" class="btn btn-success w-full">
                        <i class="bi bi-cash"></i> Registrar Pago
                    </a>
                </div>
                @endif
            </div>

            <!-- Info -->
            <div class="card p-6">
                <h3 class="text-lg font-display font-bold text-gray-900 mb-4">Información</h3>
                <div class="space-y-3 text-sm">
                    <div class="flex items-center justify-between p-2 bg-gray-50 rounded-lg">
                        <span class="text-gray-600">Fecha Emisión</span>
                        <span class="font-semibold text-gray-900">{{ isset($factura->fecha) ? \Carbon\Carbon::parse($factura->fecha)->format('d/m/Y') : 'N/A' }}</span>
                    </div>
                    @if($factura->fecha_vencimiento)
                    <div class="flex items-center justify-between p-2 bg-gray-50 rounded-lg">
                        <span class="text-gray-600">Vencimiento</span>
                        <span class="font-semibold text-gray-900">{{ \Carbon\Carbon::parse($factura->fecha_vencimiento)->format('d/m/Y') }}</span>
                    </div>
                    @endif
                    <div class="flex items-center justify-between p-2 bg-gray-50 rounded-lg">
                        <span class="text-gray-600">Método de Pago</span>
                        <span class="font-semibold text-gray-900">{{ $factura->metodo_pago ?? 'N/A' }}</span>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="card p-6">
                <h3 class="text-lg font-display font-bold text-gray-900 mb-4">Acciones</h3>
                <div class="space-y-2">
                    <a href="{{ url('index.php/shared/facturacion/' . $factura->id . '/pdf') }}" class="btn btn-outline w-full justify-start">
                        <i class="bi bi-file-pdf"></i> Descargar PDF
                    </a>
                    <a href="{{ url('index.php/shared/facturacion/' . $factura->id . '/email') }}" class="btn btn-outline w-full justify-start">
                        <i class="bi bi-envelope"></i> Enviar por Email
                    </a>
                    <button onclick="window.print()" class="btn btn-outline w-full justify-start">
                        <i class="bi bi-printer"></i> Imprimir
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
