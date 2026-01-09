@extends('layouts.admin')

@section('title', 'Nueva Factura')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center gap-4">
        <a href="{{ url('index.php/shared/facturacion') }}" class="btn btn-outline">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div>
            <h1 class="text-2xl font-display font-bold text-gray-900">Nueva Factura</h1>
            <p class="text-gray-600 mt-1">Generar una nueva factura</p>
        </div>
    </div>

    <form action="{{ url('index.php/shared/facturacion') }}" method="POST" class="space-y-6">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Form -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Información del Paciente -->
                <div class="card p-6">
                    <h3 class="text-lg font-display font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="bi bi-person text-blue-600"></i>
                        Información del Paciente
                    </h3>

                    <div class="space-y-4">
                        <div>
                            <label class="form-label form-label-required">Paciente</label>
                            <select name="paciente_id" class="form-select" required>
                                <option value="">Seleccionar paciente...</option>
                                @foreach($pacientes ?? [] as $paciente)
                                <option value="{{ $paciente->id }}" {{ old('paciente_id') == $paciente->id ? 'selected' : '' }}>
                                    {{ $paciente->nombre_completo }} - {{ $paciente->cedula }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="form-label form-label-required">Fecha</label>
                                <input type="date" name="fecha" class="input" value="{{ old('fecha', date('Y-m-d')) }}" required>
                            </div>
                            <div>
                                <label class="form-label">Fecha de Vencimiento</label>
                                <input type="date" name="fecha_vencimiento" class="input" value="{{ old('fecha_vencimiento') }}">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Detalle de la Factura -->
                <div class="card p-6">
                    <h3 class="text-lg font-display font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="bi bi-receipt text-emerald-600"></i>
                        Detalle de la Factura
                    </h3>

                    <div class="space-y-4">
                        <div>
                            <label class="form-label form-label-required">Concepto</label>
                            <input type="text" name="concepto" class="input" placeholder="Ej: Consulta General" value="{{ old('concepto') }}" required>
                        </div>

                        <div>
                            <label class="form-label">Descripción</label>
                            <textarea name="descripcion" rows="3" class="form-textarea" placeholder="Detalles adicionales...">{{ old('descripcion') }}</textarea>
                        </div>

                        <div class="grid grid-cols-3 gap-4">
                            <div>
                                <label class="form-label form-label-required">Subtotal</label>
                                <div class="relative">
                                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500">$</span>
                                    <input type="number" name="subtotal" class="input pl-8" placeholder="0.00" value="{{ old('subtotal') }}" step="0.01" required>
                                </div>
                            </div>
                            <div>
                                <label class="form-label">Descuento (%)</label>
                                <input type="number" name="descuento" class="input" placeholder="0" value="{{ old('descuento', 0) }}" min="0" max="100">
                            </div>
                            <div>
                                <label class="form-label">IVA (%)</label>
                                <input type="number" name="iva" class="input" placeholder="0" value="{{ old('iva', 0) }}" min="0" max="100">
                            </div>
                        </div>

                        <div class="p-4 bg-emerald-50 rounded-xl border border-emerald-200">
                            <div class="flex items-center justify-between">
                                <span class="text-lg font-semibold text-gray-900">Total a Pagar:</span>
                                <span class="text-3xl font-bold text-emerald-700">$0.00</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Estado -->
                <div class="card p-6">
                    <h3 class="text-lg font-display font-bold text-gray-900 mb-4">Estado de Pago</h3>
                    <div class="space-y-3">
                        <label class="flex items-center gap-3 p-3 border-2 border-gray-200 rounded-xl cursor-pointer hover:bg-gray-50">
                            <input type="radio" name="status" value="pendiente" class="form-radio" {{ old('status', 'pendiente') == 'pendiente' ? 'checked' : '' }}>
                            <div>
                                <p class="font-semibold text-gray-900">Pendiente</p>
                                <p class="text-sm text-gray-600">Por cobrar</p>
                            </div>
                        </label>
                        <label class="flex items-center gap-3 p-3 border-2 border-gray-200 rounded-xl cursor-pointer hover:bg-gray-50">
                            <input type="radio" name="status" value="pagada" class="form-radio" {{ old('status') == 'pagada' ? 'checked' : '' }}>
                            <div>
                                <p class="font-semibold text-gray-900">Pagada</p>
                                <p class="text-sm text-gray-600">Ya cobrada</p>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Actions -->
                <div class="card p-6">
                    <h3 class="text-lg font-display font-bold text-gray-900 mb-4">Acciones</h3>
                    <div class="space-y-3">
                        <button type="submit" class="btn btn-success w-full">
                            <i class="bi bi-check-lg"></i>
                            Generar Factura
                        </button>
                        <a href="{{ url('index.php/shared/facturacion') }}" class="btn btn-outline w-full">
                            <i class="bi bi-x-lg"></i>
                            Cancelar
                        </a>
                    </div>
                </div>

                <!-- Info -->
                <div class="card p-6 bg-blue-50 border-blue-200">
                    <div class="flex gap-3">
                        <i class="bi bi-info-circle text-blue-600 text-xl"></i>
                        <div>
                            <h4 class="font-semibold text-gray-900 mb-1">Información</h4>
                            <p class="text-sm text-gray-600">La factura se generará automáticamente con un número único secuencial.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
// Calculate total dynamically
document.addEventListener('DOMContentLoaded', function() {
    const subtotalInput = document.querySelector('input[name="subtotal"]');
    const descuentoInput = document.querySelector('input[name="descuento"]');
    const ivaInput = document.querySelector('input[name="iva"]');
    
    function calculateTotal() {
        const subtotal = parseFloat(subtotalInput.value) || 0;
        const descuento = parseFloat(descuentoInput.value) || 0;
        const iva = parseFloat(ivaInput.value) || 0;
        
        const afterDiscount = subtotal * (1 - descuento / 100);
        const total = afterDiscount * (1 + iva / 100);
        
        document.querySelector('.text-emerald-700').textContent = '$' + total.toFixed(2);
    }
    
    subtotalInput?.addEventListener('input', calculateTotal);
    descuentoInput?.addEventListener('input', calculateTotal);
    ivaInput?.addEventListener('input', calculateTotal);
});
</script>
@endsection
