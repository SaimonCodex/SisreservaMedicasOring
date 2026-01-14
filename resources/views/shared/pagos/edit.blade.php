@extends('layouts.admin')

@section('title', 'Editar Pago')

@section('content')
<div class="mb-6">
    <div class="flex items-center gap-3">
        <a href="{{ route('pagos.index') }}" class="btn btn-ghost">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div>
            <h2 class="text-3xl font-display font-bold text-gray-900">Editar Pago</h2>
            <p class="text-gray-500 mt-1">Modificar información del pago</p>
        </div>
    </div>
</div>

<form action="{{ route('pagos.update', $pago->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Formulario Principal -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Información de la Factura -->
            <div class="card p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                    <i class="bi bi-receipt text-info-600"></i>
                    Información de la Factura
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="md:col-span-2">
                        <label class="form-label required">Factura</label>
                        <select name="id_factura_paciente" class="form-select" required disabled>
                            <option value="">Seleccione una factura</option>
                            @foreach($facturas as $factura)
                            <option value="{{ $factura->id }}" {{ old('id_factura_paciente', $pago->id_factura_paciente) == $factura->id ? 'selected' : '' }}>
                                {{ $factura->numero_factura }} - {{ $factura->cita->paciente->primer_nombre }} {{ $factura->cita->paciente->primer_apellido }} - ${{ number_format($factura->monto_usd, 2) }}
                            </option>
                            @endforeach
                        </select>
                        <input type="hidden" name="id_factura_paciente" value="{{ $pago->id_factura_paciente }}">
                        <p class="text-xs text-gray-500 mt-1">La factura no puede ser modificada</p>
                    </div>

                    <div>
                        <label class="form-label">Monto Factura</label>
                        <input type="text" class="input bg-gray-100" value="${{ number_format($pago->facturaPaciente->monto_usd, 2) }}" readonly>
                    </div>

                    <div>
                        <label class="form-label">Saldo Pendiente</label>
                        <input type="text" class="input bg-gray-100" value="${{ number_format($pago->facturaPaciente->monto_usd - $pago->facturaPaciente->pagos->where('estado', 'Confirmado')->sum('monto_equivalente_usd'), 2) }}" readonly>
                    </div>
                </div>
            </div>

            <!-- Detalles del Pago -->
            <div class="card p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                    <i class="bi bi-credit-card text-success-600"></i>
                    Detalles del Pago
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="form-label required">Método de Pago</label>
                        <select name="id_metodo" class="form-select" required>
                            @foreach($metodosPago as $metodo)
                            <option value="{{ $metodo->id_metodo }}" {{ old('id_metodo', $pago->id_metodo) == $metodo->id_metodo ? 'selected' : '' }}>
                                {{ $metodo->nombre }}
                            </option>
                            @endforeach
                        </select>
                        @error('id_metodo')<span class="text-danger-600 text-sm">{{ $message }}</span>@enderror
                    </div>

                    <div>
                        <label class="form-label required">Monto Pagado (Bs)</label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500">Bs.</span>
                            <input type="number" name="monto_pagado_bs" class="input pl-12" step="0.01" 
                                   value="{{ old('monto_pagado_bs', $pago->monto_pagado_bs) }}" required>
                        </div>
                        @error('monto_pagado_bs')<span class="text-danger-600 text-sm">{{ $message }}</span>@enderror
                    </div>

                    <div>
                        <label class="form-label required">Fecha de Pago</label>
                        <input type="date" name="fecha_pago" class="input" 
                               value="{{ old('fecha_pago', $pago->fecha_pago) }}" required>
                        @error('fecha_pago')<span class="text-danger-600 text-sm">{{ $message }}</span>@enderror
                    </div>

                    <div>
                        <label class="form-label">Número de Referencia</label>
                        <input type="text" name="referencia" class="input" 
                               placeholder="Ej: 123456789" 
                               value="{{ old('referencia', $pago->referencia) }}">
                    </div>

                    <div class="md:col-span-2">
                        <label class="form-label">Tasa Aplicada (Bs/$)</label>
                        <select name="tasa_aplicada_id" class="form-select">
                            <option value="">Seleccione una tasa</option>
                            @foreach($tasas as $tasa)
                            <option value="{{ $tasa->id }}" {{ old('tasa_aplicada_id', $pago->tasa_aplicada_id) == $tasa->id ? 'selected' : '' }}>
                                {{ \Carbon\Carbon::parse($tasa->fecha_tasa)->format('d/m/Y') }} - 1$ = {{ number_format($tasa->valor, 2) }} Bs
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="md:col-span-2">
                        <label class="form-label">Comentarios</label>
                        <textarea name="comentarios" rows="3" class="input" 
                                  placeholder="Observaciones adicionales sobre el pago">{{ old('comentarios', $pago->comentarios) }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Comprobante -->
            <div class="card p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                    <i class="bi bi-file-earmark-check text-warning-600"></i>
                    Comprobante de Pago
                </h3>
                
                @if($pago->comprobante_pago)
                <div class="mb-4 p-4 bg-info-50 border border-info-200 rounded-lg">
                    <p class="text-sm text-info-700 mb-2">
                        <i class="bi bi-info-circle mr-1"></i>
                        Comprobante actual: <a href="{{ asset('storage/' . $pago->comprobante_pago) }}" target="_blank" class="font-semibold underline">Ver archivo</a>
                    </p>
                </div>
                @endif

                <div>
                    <label class="form-label">Subir Nuevo Comprobante</label>
                    <input type="file" name="comprobante" class="input" accept="image/*,application/pdf">
                    <p class="text-xs text-gray-500 mt-1">Formatos: JPG, PNG, PDF. Máximo 5MB.</p>
                    @error('comprobante')<span class="text-danger-600 text-sm">{{ $message }}</span>@enderror
                </div>
            </div>
        </div>

        <!-- Panel Lateral -->
        <div class="space-y-6">
            <!-- Estado -->
            <div class="card p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                    <i class="bi bi-check-circle text-medical-600"></i>
                    Estado del Pago
                </h3>
                
                <div class="space-y-3">
                    <div>
                        <label class="form-label required">Estado</label>
                        <select name="estado" class="form-select" required>
                            <option value="Pendiente" {{ old('estado', $pago->estado) == 'Pendiente' ? 'selected' : '' }}>Pendiente</option>
                            <option value="Confirmado" {{ old('estado', $pago->estado) == 'Confirmado' ? 'selected' : '' }}>Confirmado</option>
                            <option value="Rechazado" {{ old('estado', $pago->estado) == 'Rechazado' ? 'selected' : '' }}>Rechazado</option>
                            <option value="Reembolsado" {{ old('estado', $pago->estado) == 'Reembolsado' ? 'selected' : '' }}>Reembolsado</option>
                        </select>
                    </div>

                    <div>
                        <label class="form-label">Verificado Por</label>
                        <select name="verificado_por" class="form-select">
                            <option value="">Sin verificar</option>
                            @foreach($administradores as $admin)
                            <option value="{{ $admin->id }}" {{ old('verificado_por', $pago->verificado_por) == $admin->id ? 'selected' : '' }}>
                                {{ $admin->primer_nombre }} {{ $admin->primer_apellido }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <!-- Acciones -->
            <div class="card p-6">
                <div class="space-y-3">
                    <button type="submit" class="btn btn-primary w-full">
                        <i class="bi bi-check-lg mr-2"></i>
                        Actualizar Pago
                    </button>
                    <a href="{{ route('pagos.show', $pago->id) }}" class="btn btn-outline w-full">
                        <i class="bi bi-x-lg mr-2"></i>
                        Cancelar
                    </a>
                </div>
            </div>

            <!-- Información del Sistema -->
            <div class="card p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                    <i class="bi bi-info-circle text-gray-600"></i>
                    Información del Sistema
                </h3>
                
                <div class="space-y-3 text-sm">
                    <div>
                        <p class="text-gray-500">ID</p>
                        <p class="font-mono font-semibold text-gray-900">{{ $pago->id }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500">Registrado</p>
                        <p class="font-semibold text-gray-900">{{ $pago->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500">Última Actualización</p>
                        <p class="font-semibold text-gray-900">{{ $pago->updated_at->format('d/m/Y H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection
