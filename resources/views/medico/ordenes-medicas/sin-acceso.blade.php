@extends('layouts.medico')

@section('title', 'Acceso Restringido - Orden Médica')

@section('content')
<div class="min-h-[60vh] flex items-center justify-center">
    <div class="max-w-lg w-full">
        <div class="card p-8 text-center">
            <!-- Icono de bloqueo -->
            <div class="mb-6">
                <div class="w-20 h-20 mx-auto rounded-full bg-red-100 flex items-center justify-center">
                    <i class="bi bi-shield-lock text-4xl text-red-500"></i>
                </div>
            </div>
            
            <!-- Título -->
            <h2 class="text-2xl font-bold text-gray-900 mb-2">Acceso Restringido</h2>
            <p class="text-gray-600 mb-6">
                Esta orden médica fue emitida por otro profesional y está protegida por políticas de confidencialidad médico-paciente.
            </p>

            <!-- Información de la orden -->
            <div class="bg-gray-50 rounded-xl p-4 mb-6 text-left">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 rounded-full bg-{{ $orden->color_tipo }}-100 flex items-center justify-center">
                        <i class="bi {{ $orden->icono_tipo }} text-{{ $orden->color_tipo }}-600"></i>
                    </div>
                    <div>
                        <p class="font-bold text-gray-900">{{ $orden->tipo_orden }}</p>
                        <p class="text-sm text-gray-500">{{ $orden->codigo_orden }}</p>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <p class="text-gray-500">Médico prescriptor</p>
                        <p class="font-medium text-gray-900">Dr. {{ $orden->medico->primer_nombre }} {{ $orden->medico->primer_apellido }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500">Fecha de emisión</p>
                        <p class="font-medium text-gray-900">{{ $orden->fecha_emision->format('d/m/Y') }}</p>
                    </div>
                </div>
            </div>

            <!-- Alerta informativa -->
            <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 mb-6 text-left">
                <div class="flex gap-3">
                    <i class="bi bi-info-circle text-blue-600 text-lg"></i>
                    <div>
                        <p class="text-sm text-blue-800 font-medium mb-1">¿Necesita acceso a esta orden?</p>
                        <p class="text-sm text-blue-700">
                            Puede solicitar acceso al paciente. La solicitud será enviada para su aprobación.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Formulario de solicitud -->
            <form action="{{ route('ordenes-medicas.solicitar-acceso', $orden->id) }}" method="POST">
                @csrf
                
                <div class="mb-4">
                    <label for="motivo_solicitud" class="block text-sm font-medium text-gray-700 text-left mb-2">
                        Motivo de la solicitud <span class="text-red-500">*</span>
                    </label>
                    <select name="motivo_solicitud" id="motivo_solicitud" class="form-select" required>
                        <option value="">Seleccione un motivo...</option>
                        <option value="Interconsulta">Interconsulta</option>
                        <option value="Emergencia">Emergencia Médica</option>
                        <option value="Segunda Opinion">Segunda Opinión</option>
                        <option value="Referencia">Referencia Recibida</option>
                        <option value="Continuidad Tratamiento">Continuidad de Tratamiento</option>
                    </select>
                </div>
                
                <div class="mb-6">
                    <label for="observaciones" class="block text-sm font-medium text-gray-700 text-left mb-2">
                        Observaciones (opcional)
                    </label>
                    <textarea name="observaciones" id="observaciones" rows="3" class="form-control" 
                              placeholder="Explique brevemente por qué necesita acceso a esta orden..."></textarea>
                </div>

                <div class="flex gap-3">
                    <a href="{{ route('ordenes-medicas.index') }}" class="btn btn-outline flex-1">
                        <i class="bi bi-arrow-left mr-2"></i> Volver
                    </a>
                    <button type="submit" class="btn btn-primary flex-1">
                        <i class="bi bi-send mr-2"></i> Solicitar Acceso
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
