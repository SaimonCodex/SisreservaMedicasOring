@extends('layouts.paciente')

@section('title', 'Detalle de Orden - ' . $orden->codigo_orden)

@section('content')
<div class="mb-6">
    <a href="{{ route('paciente.ordenes.index') }}" class="text-medical-600 hover:text-medical-700 inline-flex items-center text-sm font-medium mb-3">
        <i class="bi bi-arrow-left mr-1"></i> Volver a Mis Órdenes
    </a>
    
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 rounded-xl bg-{{ $orden->color_tipo }}-100 flex items-center justify-center">
                <i class="bi {{ $orden->icono_tipo }} text-2xl text-{{ $orden->color_tipo }}-600"></i>
            </div>
            <div>
                <h2 class="text-2xl font-bold text-gray-900">{{ $orden->tipo_orden }}</h2>
                <p class="text-gray-500">{{ $orden->codigo_orden }}</p>
            </div>
        </div>
        
        <div class="flex items-center gap-3">
            <span class="badge badge-{{ $orden->estado_orden == 'Emitida' ? 'info' : ($orden->estado_orden == 'Procesada' ? 'success' : 'warning') }}">
                {{ $orden->estado_orden }}
            </span>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Contenido principal -->
    <div class="lg:col-span-2 space-y-6">
        
        <!-- Diagnóstico e indicaciones -->
        @if($orden->diagnostico_principal || $orden->indicaciones)
            <div class="card p-6">
                <h3 class="font-bold text-gray-900 mb-4">
                    <i class="bi bi-clipboard2-pulse mr-2 text-medical-600"></i>
                    Información General
                </h3>
                
                @if($orden->diagnostico_principal)
                    <div class="mb-4">
                        <p class="text-sm text-gray-500 mb-1">Diagnóstico Principal</p>
                        <p class="text-gray-900">{{ $orden->diagnostico_principal }}</p>
                    </div>
                @endif
                
                @if($orden->indicaciones)
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Indicaciones Generales</p>
                        <p class="text-gray-900">{{ $orden->indicaciones }}</p>
                    </div>
                @endif
            </div>
        @endif

        <!-- Medicamentos (Receta) -->
        @if($orden->medicamentos->count() > 0)
            <div class="card p-6">
                <h3 class="font-bold text-gray-900 mb-4">
                    <i class="bi bi-capsule mr-2 text-green-600"></i>
                    Medicamentos Recetados
                </h3>
                
                <div class="space-y-4">
                    @foreach($orden->medicamentos as $index => $med)
                        <div class="p-4 rounded-xl bg-green-50/50 border border-green-100">
                            <div class="flex items-start justify-between">
                                <div class="flex items-start gap-3">
                                    <span class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center text-sm font-bold text-green-700">
                                        {{ $index + 1 }}
                                    </span>
                                    <div>
                                        <p class="font-bold text-gray-900">{{ $med->medicamento }}</p>
                                        @if($med->presentacion)
                                            <p class="text-sm text-gray-600">{{ $med->presentacion }}</p>
                                        @endif
                                    </div>
                                </div>
                                <span class="badge badge-success">
                                    {{ $med->cantidad }} unidad(es)
                                </span>
                            </div>
                            
                            <div class="mt-3 pt-3 border-t border-green-100 grid grid-cols-2 md:grid-cols-4 gap-3 text-sm">
                                <div>
                                    <p class="text-gray-500">Dosis</p>
                                    <p class="font-medium text-gray-900">{{ $med->dosis ?? 'No especificada' }}</p>
                                </div>
                                <div>
                                    <p class="text-gray-500">Vía</p>
                                    <p class="font-medium text-gray-900">{{ $med->via_administracion }}</p>
                                </div>
                                <div>
                                    <p class="text-gray-500">Duración</p>
                                    <p class="font-medium text-gray-900">{{ $med->duracion_dias ? $med->duracion_dias . ' días' : 'No especificada' }}</p>
                                </div>
                            </div>
                            
                            @if($med->indicaciones)
                                <div class="mt-3 pt-3 border-t border-green-100">
                                    <p class="text-sm text-gray-500">Indicaciones específicas:</p>
                                    <p class="text-sm text-gray-900">{{ $med->indicaciones }}</p>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Exámenes de Laboratorio -->
        @if($orden->examenes->count() > 0)
            <div class="card p-6">
                <h3 class="font-bold text-gray-900 mb-4">
                    <i class="bi bi-droplet mr-2 text-blue-600"></i>
                    Exámenes de Laboratorio
                </h3>
                
                <div class="space-y-3">
                    @foreach($orden->examenes as $exam)
                        <div class="p-4 rounded-xl bg-blue-50/50 border border-blue-100">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="font-bold text-gray-900">{{ $exam->nombre_examen }}</p>
                                    <p class="text-sm text-blue-700">{{ $exam->tipo_examen }}</p>
                                </div>
                                <div class="flex items-center gap-2">
                                    @if($exam->urgente)
                                        <span class="badge badge-danger">Urgente</span>
                                    @endif
                                    <span class="badge badge-{{ $exam->tieneResultado() ? 'success' : 'warning' }}">
                                        {{ $exam->estado }}
                                    </span>
                                </div>
                            </div>
                            
                            @if($exam->indicacion_clinica)
                                <p class="text-sm text-gray-600 mt-2">{{ $exam->indicacion_clinica }}</p>
                            @endif
                            
                            @if($exam->resultado)
                                <div class="mt-3 pt-3 border-t border-blue-100">
                                    <p class="text-sm text-gray-500 mb-1">Resultado:</p>
                                    <p class="text-sm text-gray-900">{{ $exam->resultado }}</p>
                                    @if($exam->fecha_resultado)
                                        <p class="text-xs text-gray-500 mt-1">Fecha: {{ $exam->fecha_resultado->format('d/m/Y') }}</p>
                                    @endif
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Estudios de Imagenología -->
        @if($orden->imagenes->count() > 0)
            <div class="card p-6">
                <h3 class="font-bold text-gray-900 mb-4">
                    <i class="bi bi-x-ray mr-2 text-orange-600"></i>
                    Estudios de Imagenología
                </h3>
                
                <div class="space-y-3">
                    @foreach($orden->imagenes as $img)
                        <div class="p-4 rounded-xl bg-orange-50/50 border border-orange-100">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="font-bold text-gray-900">{{ $img->tipo_estudio }}</p>
                                    <p class="text-sm text-orange-700">{{ $img->region_anatomica }}</p>
                                </div>
                                <div class="flex items-center gap-2">
                                    @if($img->contraste)
                                        <span class="badge badge-info">Con contraste</span>
                                    @endif
                                    @if($img->urgente)
                                        <span class="badge badge-danger">Urgente</span>
                                    @endif
                                    <span class="badge badge-{{ $img->tieneResultado() ? 'success' : 'warning' }}">
                                        {{ $img->estado }}
                                    </span>
                                </div>
                            </div>
                            
                            @if($img->proyecciones)
                                <p class="text-sm text-gray-600 mt-2">Proyecciones: {{ $img->proyecciones }}</p>
                            @endif
                            
                            @if($img->resultado)
                                <div class="mt-3 pt-3 border-t border-orange-100">
                                    <p class="text-sm text-gray-500 mb-1">Informe Radiológico:</p>
                                    <p class="text-sm text-gray-900">{{ $img->resultado }}</p>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Referencias a Especialistas -->
        @if($orden->referencias->count() > 0)
            <div class="card p-6">
                <h3 class="font-bold text-gray-900 mb-4">
                    <i class="bi bi-person-badge mr-2 text-purple-600"></i>
                    Referencias a Especialistas
                </h3>
                
                <div class="space-y-3">
                    @foreach($orden->referencias as $ref)
                        <div class="p-4 rounded-xl bg-purple-50/50 border border-purple-100">
                            <div class="flex items-center justify-between mb-3">
                                <div>
                                    <p class="font-bold text-gray-900">{{ $ref->especialidad_destino }}</p>
                                    @if($ref->medicoReferido)
                                        <p class="text-sm text-purple-700">
                                            Dr. {{ $ref->medicoReferido->primer_nombre }} {{ $ref->medicoReferido->primer_apellido }}
                                        </p>
                                    @endif
                                </div>
                                <span class="badge badge-{{ $ref->color_prioridad }}">
                                    {{ $ref->prioridad }}
                                </span>
                            </div>
                            
                            <div class="space-y-2 text-sm">
                                <div>
                                    <p class="text-gray-500">Motivo de referencia:</p>
                                    <p class="text-gray-900">{{ $ref->motivo_referencia }}</p>
                                </div>
                                <div>
                                    <p class="text-gray-500">Resumen clínico:</p>
                                    <p class="text-gray-900">{{ $ref->resumen_clinico }}</p>
                                </div>
                            </div>
                            
                            @if($ref->respuesta)
                                <div class="mt-3 pt-3 border-t border-purple-100">
                                    <p class="text-sm text-gray-500 mb-1">Respuesta del especialista:</p>
                                    <p class="text-sm text-gray-900">{{ $ref->respuesta }}</p>
                                    @if($ref->fecha_atencion)
                                        <p class="text-xs text-gray-500 mt-1">Atendido el: {{ $ref->fecha_atencion->format('d/m/Y') }}</p>
                                    @endif
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    <!-- Sidebar -->
    <div class="space-y-6">
        <!-- Información del médico -->
        <div class="card p-6">
            <h4 class="font-bold text-gray-900 mb-4">Médico Prescriptor</h4>
            
            <div class="flex items-center gap-4 mb-4">
                <div class="w-12 h-12 rounded-full bg-medical-100 flex items-center justify-center">
                    <i class="bi bi-person-circle text-2xl text-medical-600"></i>
                </div>
                <div>
                    <p class="font-bold text-gray-900">
                        Dr. {{ $orden->medico->primer_nombre }} {{ $orden->medico->primer_apellido }}
                    </p>
                    @if($orden->especialidad)
                        <p class="text-sm text-gray-600">{{ $orden->especialidad->nombre }}</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Fechas -->
        <div class="card p-6">
            <h4 class="font-bold text-gray-900 mb-4">Información de la Orden</h4>
            
            <div class="space-y-3">
                <div class="flex justify-between">
                    <span class="text-gray-500">Fecha de Emisión</span>
                    <span class="font-medium text-gray-900">{{ $orden->fecha_emision->format('d/m/Y') }}</span>
                </div>
                @if($orden->fecha_vigencia)
                    <div class="flex justify-between">
                        <span class="text-gray-500">Vigente hasta</span>
                        <span class="font-medium text-gray-900">{{ $orden->fecha_vigencia->format('d/m/Y') }}</span>
                    </div>
                @endif
                <div class="flex justify-between">
                    <span class="text-gray-500">Estado</span>
                    <span class="badge badge-{{ $orden->estado_orden == 'Emitida' ? 'info' : 'success' }}">
                        {{ $orden->estado_orden }}
                    </span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Total Items</span>
                    <span class="font-bold text-gray-900">{{ $orden->total_items }}</span>
                </div>
            </div>
        </div>

        <!-- Nota de confidencialidad -->
        <div class="card p-4 bg-blue-50 border border-blue-100">
            <div class="flex gap-3">
                <i class="bi bi-shield-check text-blue-600 text-lg"></i>
                <div>
                    <p class="text-sm font-medium text-blue-800">Documento Confidencial</p>
                    <p class="text-xs text-blue-700 mt-1">
                        Esta orden médica está protegida. Solo usted y su médico prescriptor tienen acceso a ella.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
