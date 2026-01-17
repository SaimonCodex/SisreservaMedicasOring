@extends('layouts.paciente')

@section('title', 'Solicitudes de Acceso - Órdenes Médicas')

@section('content')
<div class="mb-6">
    <a href="{{ route('paciente.ordenes.index') }}" class="text-medical-600 hover:text-medical-700 inline-flex items-center text-sm font-medium mb-3">
        <i class="bi bi-arrow-left mr-1"></i> Volver a Mis Órdenes
    </a>
    
    <h2 class="text-3xl font-display font-bold text-gray-900">Solicitudes de Acceso</h2>
    <p class="text-gray-500 mt-1">Médicos que solicitan ver sus órdenes médicas</p>
</div>

<!-- Información -->
<div class="card p-4 mb-6 bg-blue-50 border border-blue-100">
    <div class="flex gap-3">
        <i class="bi bi-info-circle text-blue-600 text-lg"></i>
        <div>
            <p class="text-sm text-blue-800 font-medium">¿Qué significa esto?</p>
            <p class="text-sm text-blue-700 mt-1">
                Cuando otro médico necesita ver una orden médica que no fue emitida por él (por ejemplo, para una interconsulta o segunda opinión), 
                debe solicitar su autorización. Usted puede aprobar o rechazar cada solicitud.
            </p>
        </div>
    </div>
</div>

@if($solicitudes->count() > 0)
    <div class="space-y-4">
        @foreach($solicitudes as $solicitud)
            <div class="card p-6">
                <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">
                    <!-- Información de la solicitud -->
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-3">
                            <div class="w-12 h-12 rounded-full bg-medical-100 flex items-center justify-center">
                                <i class="bi bi-person-circle text-2xl text-medical-600"></i>
                            </div>
                            <div>
                                <p class="font-bold text-gray-900">
                                    Dr. {{ $solicitud->medicoSolicitante->primer_nombre }} {{ $solicitud->medicoSolicitante->primer_apellido }}
                                </p>
                                <p class="text-sm text-gray-600">Solicita acceso a una orden médica</p>
                            </div>
                        </div>
                        
                        <!-- Detalles -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div class="p-3 rounded-lg bg-gray-50">
                                <p class="text-xs text-gray-500">Orden Solicitada</p>
                                <p class="font-medium text-gray-900">
                                    {{ $solicitud->orden->tipo_orden }} - {{ $solicitud->orden->codigo_orden }}
                                </p>
                                <p class="text-xs text-gray-500 mt-1">
                                    Emitida por Dr. {{ $solicitud->medicoPropietario->primer_nombre }} {{ $solicitud->medicoPropietario->primer_apellido }}
                                </p>
                            </div>
                            <div class="p-3 rounded-lg bg-gray-50">
                                <p class="text-xs text-gray-500">Motivo de Solicitud</p>
                                <p class="font-medium text-gray-900">{{ $solicitud->motivo_solicitud }}</p>
                            </div>
                        </div>
                        
                        @if($solicitud->observaciones)
                            <div class="p-3 rounded-lg bg-yellow-50 border border-yellow-100 mb-4">
                                <p class="text-xs text-yellow-700 mb-1">Observaciones del médico:</p>
                                <p class="text-sm text-gray-900">{{ $solicitud->observaciones }}</p>
                            </div>
                        @endif
                        
                        <p class="text-xs text-gray-500">
                            <i class="bi bi-clock mr-1"></i>
                            Solicitud enviada: {{ $solicitud->created_at->diffForHumans() }}
                            <span class="ml-2">
                                <i class="bi bi-hourglass-split mr-1"></i>
                                Expira: {{ $solicitud->token_expira_at->diffForHumans() }}
                            </span>
                        </p>
                    </div>
                    
                    <!-- Acciones -->
                    <div class="flex flex-col gap-2 min-w-[200px]">
                        <form action="{{ route('paciente.ordenes.solicitudes.aprobar', $solicitud->id) }}" method="POST" class="w-full">
                            @csrf
                            <div class="mb-3">
                                <label class="text-xs text-gray-600 block mb-1">Duración del acceso</label>
                                <select name="duracion_dias" class="form-select text-sm">
                                    <option value="1">1 día</option>
                                    <option value="3">3 días</option>
                                    <option value="7" selected>7 días</option>
                                    <option value="15">15 días</option>
                                    <option value="30">30 días</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-success w-full">
                                <i class="bi bi-check-lg mr-2"></i> Aprobar
                            </button>
                        </form>
                        
                        <form action="{{ route('paciente.ordenes.solicitudes.rechazar', $solicitud->id) }}" method="POST" class="w-full">
                            @csrf
                            <button type="submit" class="btn btn-outline text-red-600 border-red-300 hover:bg-red-50 w-full">
                                <i class="bi bi-x-lg mr-2"></i> Rechazar
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@else
    <div class="card p-12 text-center">
        <div class="w-20 h-20 mx-auto rounded-full bg-green-100 flex items-center justify-center mb-4">
            <i class="bi bi-check-circle text-4xl text-green-500"></i>
        </div>
        <h3 class="text-lg font-bold text-gray-900 mb-2">Sin solicitudes pendientes</h3>
        <p class="text-gray-500">No tiene solicitudes de acceso a sus órdenes médicas en este momento.</p>
        <a href="{{ route('paciente.ordenes.index') }}" class="btn btn-outline mt-4">
            <i class="bi bi-arrow-left mr-2"></i> Volver a Mis Órdenes
        </a>
    </div>
@endif
@endsection
