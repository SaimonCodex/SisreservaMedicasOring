@extends('layouts.admin')

@section('title', 'Registrar Pago')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center gap-4">
        <a href="{{ url('index.php/shared/pagos') }}" class="btn btn-outline">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div>
            <h1 class="text-2xl font-display font-bold text-gray-900">Registrar Nuevo Pago</h1>
            <p class="text-gray-600 mt-1">Procesar un pago de factura</p>
        </div>
    </div>

    <form action="{{ url('index.php/shared/pagos') }}" method="POST" class="space-y-6">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Form -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Factura -->
                <div class="card p-6">
                    <h3 class="text-lg font-display font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="bi bi-receipt text-blue-600"></i>
                        Seleccionar Factura
                    </h3>

                    <div class="space-y-4">
                        <div>
                            <label class="form-label form-label-required">Factura</label>
                            <select name="factura_id" class="form-select" required id="facturaSelect">
                                <option value="">Seleccionar factura...</option>
                                @foreach($facturas ?? [] as $factura)
                                <option value="{{ $factura->id }}" data-monto="{{ $factura->total }}" data-paciente="{{ $factura->paciente->nombre_completo ?? 'N/A' }}" {{ request('factura_id') == $factura->id ? 'selected' : '' }}>
                                    #{{ $factura->numero }} - {{ $factura->paciente->nombre_completo ?? 'N/A' }} - ${{ number_format($factura->total, 2) }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div id="facturaInfo" class="hidden p-4 bg-blue-50 rounded-xl border border-blue-200">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <p class="text-sm text-gray-600">Paciente</p>
                                    <p class="font-semibold text-gray-900" id="facturaPaciente">-</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Monto a Pagar</p>
                                    <p class="text-2xl font-bold text-blue-700" id="facturaMonto">$0.00</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Payment Details -->
                <div class="card p-6">
                    <h3 class="text-lg font-display font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="bi bi-cash-stack text-emerald-600"></i>
                        Detalles del Pago
                    </h3>

                    <div class="space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="form-label form-label-required">Método de Pago</label>
                                <select name="metodo" class="form-select" required id="metodoPago">
                                    <option value="">Seleccionar...</option>
                                    <option value="efectivo" {{ old('metodo') == 'efectivo' ? 'selected' : '' }}>Efectivo</option>
                                    <option value="tarjeta" {{ old('metodo') == 'tarjeta' ? 'selected' : '' }}>Tarjeta Débito/Crédito</option>
                                    <option value="transferencia" {{ old('metodo') == 'transferencia' ? 'selected' : '' }}>Transferencia Bancaria</option>
                                    <option value="cheque" {{ old('metodo') == 'cheque' ? 'selected' : '' }}>Cheque</option>
                                    <option value="pago_movil" {{ old('metodo') == 'pago_movil' ? 'selected' : '' }}>Pago Móvil</option>
                                </select>
                            </div>

                            <div>
                                <label class="form-label form-label-required">Monto</label>
                                <div class="relative">
                                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500">$</span>
                                    <input type="number" name="monto" class="input pl-8" placeholder="0.00" value="{{ old('monto') }}" step="0.01" required>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="form-label form-label-required">Fecha de Pago</label>
                            <input type="date" name="fecha" class="input" value="{{ old('fecha', date('Y-m-d')) }}" required>
                        </div>

                        <!-- Payment Method Specific Fields -->
                        <div id="tarjetaFields" class="hidden space-y-4">
                            <div>
                                <label class="form-label">Últimos 4 dígitos de la tarjeta</label>
                                <input type="text" name="tarjeta_ultimos_digitos" class="input" placeholder="XXXX" maxlength="4">
                            </div>
                            <div>
                                <label class="form-label">Tipo de Tarjeta</label>
                                <select name="tarjeta_tipo" class="form-select">
                                    <option value="">Seleccionar...</option>
                                    <option value="credito">Crédito</option>
                                    <option value="debito">Débito</option>
                                </select>
                            </div>
                        </div>

                        <div id="transferenciaFields" class="hidden space-y-4">
                            <div>
                                <label class="form-label">Número de Referencia</label>
                                <input type="text" name="referencia_bancaria" class="input" placeholder="Ej: 1234567890">
                            </div>
                            <div>
                                <label class="form-label">Banco</label>
                                <input type="text" name="banco" class="input" placeholder="Nombre del banco">
                            </div>
                        </div>

                        <div id="chequeFields" class="hidden space-y-4">
                            <div>
                                <label class="form-label">Número de Cheque</label>
                                <input type="text" name="numero_cheque" class="input" placeholder="Ej: 001234">
                            </div>
                            <div>
                                <label class="form-label">Banco Emisor</label>
                                <input type="text" name="banco_emisor" class="input" placeholder="Nombre del banco">
                            </div>
                        </div>

                        <div>
                            <label class="form-label">Notas Adicionales</label>
                            <textarea name="notas" rows="3" class="form-textarea" placeholder="Observaciones sobre el pago...">{{ old('notas') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Actions -->
                <div class="card p-6">
                    <h3 class="text-lg font-display font-bold text-gray-900 mb-4">Acciones</h3>
                    <div class="space-y-3">
                        <button type="submit" class="btn btn-success w-full">
                            <i class="bi bi-check-lg"></i>
                            Registrar Pago
                        </button>
                        <a href="{{ url('index.php/shared/pagos') }}" class="btn btn-outline w-full">
                            <i class="bi bi-x-lg"></i>
                            Cancelar
                        </a>
                    </div>
                </div>

                <!-- Info -->
                <div class="card p-6 bg-emerald-50 border-emerald-200">
                    <div class="flex gap-3">
                        <i class="bi bi-info-circle text-emerald-600 text-xl"></i>
                        <div>
                            <h4 class="font-semibold text-gray-900 mb-1">Importante</h4>
                            <p class="text-sm text-gray-600">El pago se registrará automáticamente y se actualizará el estado de la factura.</p>
                        </div>
                    </div>
                </div>

                <!-- Payment Methods Info -->
                <div class="card p-6">
                    <h3 class="text-lg font-display font-bold text-gray-900 mb-3">Métodos Aceptados</h3>
                    <div class="space-y-2 text-sm">
                        <div class="flex items-center gap-2 text-gray-700">
                            <i class="bi bi-check-circle text-emerald-600"></i>
                            <span>Efectivo</span>
                        </div>
                        <div class="flex items-center gap-2 text-gray-700">
                            <i class="bi bi-check-circle text-emerald-600"></i>
                            <span>Tarjeta Débito/Crédito</span>
                        </div>
                        <div class="flex items-center gap-2 text-gray-700">
                            <i class="bi bi-check-circle text-emerald-600"></i>
                            <span>Transferencia Bancaria</span>
                        </div>
                        <div class="flex items-center gap-2 text-gray-700">
                            <i class="bi bi-check-circle text-emerald-600"></i>
                            <span>Cheque</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const facturaSelect = document.getElementById('facturaSelect');
    const facturaInfo = document.getElementById('facturaInfo');
    const facturaPaciente = document.getElementById('facturaPaciente');
    const facturaMonto = document.getElementById('facturaMonto');
    const montoInput = document.querySelector('input[name="monto"]');
    const metodoPago = document.getElementById('metodoPago');
    
    facturaSelect?.addEventListener('change', function() {
        const selected = this.options[this.selectedIndex];
        if (this.value) {
            const monto = selected.getAttribute('data-monto');
            const paciente = selected.getAttribute('data-paciente');
            facturaPaciente.textContent = paciente;
            facturaMonto.textContent = '$' + parseFloat(monto).toFixed(2);
            montoInput.value = parseFloat(monto).toFixed(2);
            facturaInfo.classList.remove('hidden');
        } else {
            facturaInfo.classList.add('hidden');
        }
    });
    
    metodoPago?.addEventListener('change', function() {
        document.querySelectorAll('#tarjetaFields, #transferenciaFields, #chequeFields').forEach(el => el.classList.add('hidden'));
        
        if (this.value === 'tarjeta') {
            document.getElementById('tarjetaFields').classList.remove('hidden');
        } else if (this.value === 'transferencia' || this.value === 'pago_movil') {
            document.getElementById('transferenciaFields').classList.remove('hidden');
        } else if (this.value === 'cheque') {
            document.getElementById('chequeFields').classList.remove('hidden');
        }
    });
});
</script>
@endsection
