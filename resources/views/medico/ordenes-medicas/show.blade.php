@extends('layouts.medico')

@section('title', 'Detalle de Orden Médica - ' . ($orden->codigo_orden ?? 'N/A'))

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-4">
            <a href="{{ route('ordenes-medicas.index') }}" class="btn btn-outline">
                <i class="bi bi-arrow-left"></i>
            </a>
            <div>
                <h1 class="text-2xl font-display font-bold text-gray-900">{{ $orden->tipo_orden }}</h1>
                <p class="text-gray-600 mt-1">
                    <span class="font-medium">{{ $orden->codigo_orden }}</span> • 
                    {{ $orden->fecha_emision ? $orden->fecha_emision->format('d/m/Y') : 'N/A' }}
                </p>
            </div>
        </div>
        <div class="flex items-center gap-2">
            @if(isset($esPropietario) && $esPropietario)
                <a href="{{ route('ordenes-medicas.edit', $orden->id) }}" class="btn btn-primary">
                    <i class="bi bi-pencil"></i>
                    <span>Editar</span>
                </a>
            @endif
            <button onclick="window.print()" class="btn btn-outline">
                <i class="bi bi-printer"></i>
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Tipo de Orden Header -->
            <div class="card p-6">
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 rounded-xl bg-{{ $orden->color_tipo }}-100 flex items-center justify-center">
                        <i class="bi {{ $orden->icono_tipo }} text-{{ $orden->color_tipo }}-600 text-3xl"></i>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-2xl font-bold text-gray-900">{{ $orden->tipo_orden }}</h3>
                        <p class="text-gray-600">{{ $orden->total_items }} item(s) en esta orden</p>
                    </div>
                    <span class="badge badge-{{ $orden->estado_orden == 'Emitida' ? 'info' : ($orden->estado_orden == 'Procesada' ? 'success' : 'warning') }} text-sm px-3 py-1">
                        {{ $orden->estado_orden }}
                    </span>
                </div>
            </div>

            <!-- Patient Info -->
            <div class="card">
                <div class="p-6 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-white">
                    <h3 class="text-lg font-display font-bold text-gray-900 flex items-center gap-2">
                        <i class="bi bi-person-circle text-blue-600"></i>
                        Información del Paciente
                    </h3>
                </div>
                <div class="p-6">
                    <div class="flex items-center gap-4">
                        <div class="w-16 h-16 rounded-xl bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center text-white text-2xl font-bold">
                            {{ strtoupper(substr($orden->paciente->primer_nombre ?? 'P', 0, 1)) }}{{ strtoupper(substr($orden->paciente->primer_apellido ?? 'A', 0, 1)) }}
                        </div>
                        <div class="flex-1">
                            <h4 class="text-xl font-bold text-gray-900">
                                {{ $orden->nombre_paciente }}
                            </h4>
                            <h4 class="text-xl font-bold text-gray-900">
                                {{ $orden->nombre_paciente }}
                            </h4>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-2 text-sm">
                                <div>
                                    <p class="text-gray-500">Documento</p>
                                    <p class="font-semibold text-gray-900">
                                        {{ $orden->paciente->tipo_documento ?? '' }}-{{ $orden->paciente->numero_documento ?? 'N/A' }}
                                    </p>
                                </div>
                                <div>
                                    <p class="text-gray-500">Edad</p>
                                    <p class="font-semibold text-gray-900">
                                        {{ isset($orden->paciente->fecha_nac) ? \Carbon\Carbon::parse($orden->paciente->fecha_nac)->age . ' años' : 'N/A' }}
                                    </p>
                                </div>
                                <div>
                                    <p class="text-gray-500">Género</p>
                                    <p class="font-semibold text-gray-900 capitalize">{{ $orden->paciente->genero ?? 'N/A' }}</p>
                                </div>
                                <div>
                                    <p class="text-gray-500">Teléfono</p>
                                    <p class="font-semibold text-gray-900">
                                        {{ $orden->paciente->prefijo_tlf ?? '' }}-{{ $orden->paciente->numero_tlf ?? 'N/A' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                        <a href="{{ route('pacientes.show', $orden->paciente->id ?? 1) }}" class="btn btn-sm btn-outline">
                            <i class="bi bi-eye"></i> Ver Perfil
                        </a>
                    </div>
                </div>
            </div>

            <!-- Diagnóstico Principal -->
            @if($orden->diagnostico_principal)
            <div class="card p-6">
                <h3 class="text-lg font-display font-bold text-gray-900 mb-3 flex items-center gap-2">
                    <i class="bi bi-clipboard2-pulse text-medical-600"></i>
                    Diagnóstico Principal
                </h3>
                <div class="p-4 bg-gray-50 rounded-xl">
                    <p class="text-gray-900">{{ $orden->diagnostico_principal }}</p>
                </div>
            </div>
            @endif

            <!-- Medicamentos (Receta) -->
            @if($orden->medicamentos->count() > 0)
            <div class="card">
                <div class="p-6 border-b border-gray-200 bg-gradient-to-r from-green-50 to-white">
                    <h3 class="text-lg font-display font-bold text-gray-900 flex items-center gap-2">
                        <i class="bi bi-capsule text-green-600"></i>
                        Medicamentos Recetados
                        <span class="badge badge-success ml-2">{{ $orden->medicamentos->count() }}</span>
                    </h3>
                </div>
                <div class="p-6 space-y-4">
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
                                <span class="badge badge-success">{{ $med->cantidad }} unidad(es)</span>
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
                                    <p class="text-sm text-gray-500">Indicaciones:</p>
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
            <div class="card">
                <div class="p-6 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-white">
                    <h3 class="text-lg font-display font-bold text-gray-900 flex items-center gap-2">
                        <i class="bi bi-droplet text-blue-600"></i>
                        Exámenes de Laboratorio
                        <span class="badge badge-info ml-2">{{ $orden->examenes->count() }}</span>
                    </h3>
                </div>
                <div class="p-6 space-y-3">
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
                                    <div class="p-3 bg-white rounded-lg">
                                        <p class="text-sm text-gray-900">{{ $exam->resultado }}</p>
                                    </div>
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
            <div class="card">
                <div class="p-6 border-b border-gray-200 bg-gradient-to-r from-orange-50 to-white">
                    <h3 class="text-lg font-display font-bold text-gray-900 flex items-center gap-2">
                        <i class="bi bi-x-ray text-orange-600"></i>
                        Estudios de Imagenología
                        <span class="badge badge-warning ml-2">{{ $orden->imagenes->count() }}</span>
                    </h3>
                </div>
                <div class="p-6 space-y-3">
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
                                    <div class="p-3 bg-white rounded-lg">
                                        <p class="text-sm text-gray-900">{{ $img->resultado }}</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Referencias a Especialistas -->
            @if($orden->referencias->count() > 0)
            <div class="card">
                <div class="p-6 border-b border-gray-200 bg-gradient-to-r from-purple-50 to-white">
                    <h3 class="text-lg font-display font-bold text-gray-900 flex items-center gap-2">
                        <i class="bi bi-person-badge text-purple-600"></i>
                        Referencias a Especialistas
                        <span class="badge badge-purple ml-2">{{ $orden->referencias->count() }}</span>
                    </h3>
                </div>
                <div class="p-6 space-y-3">
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
                                <span class="badge badge-{{ $ref->color_prioridad }}">{{ $ref->prioridad }}</span>
                            </div>
                            
                            <div class="space-y-2 text-sm">
                                <div>
                                    <p class="text-gray-500">Motivo:</p>
                                    <p class="text-gray-900">{{ $ref->motivo_referencia }}</p>
                                </div>
                                <div>
                                    <p class="text-gray-500">Resumen Clínico:</p>
                                    <p class="text-gray-900">{{ $ref->resumen_clinico }}</p>
                                </div>
                            </div>
                            
                            @if($ref->respuesta)
                                <div class="mt-3 pt-3 border-t border-purple-100">
                                    <p class="text-sm text-gray-500 mb-1">Respuesta del Especialista:</p>
                                    <div class="p-3 bg-white rounded-lg">
                                        <p class="text-sm text-gray-900">{{ $ref->respuesta }}</p>
                                    </div>
                                    @if($ref->fecha_atencion)
                                        <p class="text-xs text-gray-500 mt-1">Atendido: {{ $ref->fecha_atencion->format('d/m/Y') }}</p>
                                    @endif
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Indicaciones Generales -->
            @if($orden->indicaciones)
            <div class="card p-6">
                <h3 class="text-lg font-display font-bold text-gray-900 mb-3 flex items-center gap-2">
                    <i class="bi bi-info-circle text-blue-600"></i>
                    Indicaciones Generales
                </h3>
                <div class="p-4 bg-blue-50 rounded-xl border border-blue-100">
                    <p class="text-gray-900">{{ $orden->indicaciones }}</p>
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Estado -->
            <div class="card p-6">
                <h3 class="text-lg font-display font-bold text-gray-900 mb-4">Estado de la Orden</h3>
                <div class="p-4 rounded-xl 
                    @if($orden->estado_orden == 'Emitida') bg-blue-50 border border-blue-200
                    @elseif($orden->estado_orden == 'Procesada') bg-green-50 border border-green-200
                    @elseif($orden->estado_orden == 'Parcialmente Procesada') bg-yellow-50 border border-yellow-200
                    @else bg-red-50 border border-red-200 @endif">
                    <p class="font-bold flex items-center gap-2
                        @if($orden->estado_orden == 'Emitida') text-blue-900
                        @elseif($orden->estado_orden == 'Procesada') text-green-900
                        @elseif($orden->estado_orden == 'Parcialmente Procesada') text-yellow-900
                        @else text-red-900 @endif">
                        <i class="bi 
                            @if($orden->estado_orden == 'Emitida') bi-clock
                            @elseif($orden->estado_orden == 'Procesada') bi-check-circle-fill
                            @elseif($orden->estado_orden == 'Parcialmente Procesada') bi-hourglass-split
                            @else bi-x-circle @endif"></i>
                        {{ $orden->estado_orden }}
                    </p>
                </div>
            </div>

            <!-- Médico Prescriptor -->
            <div class="card p-6">
                <h3 class="text-lg font-display font-bold text-gray-900 mb-4">Médico Prescriptor</h3>
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-full bg-gradient-to-br from-medical-500 to-medical-600 flex items-center justify-center text-white font-bold">
                        {{ strtoupper(substr($orden->medico->primer_nombre ?? 'D', 0, 1)) }}
                    </div>
                    <div>
                        <p class="font-semibold text-gray-900">
                            Dr. {{ $orden->medico->primer_nombre ?? 'N/A' }} {{ $orden->medico->primer_apellido ?? '' }}
                        </p>
                        @if($orden->especialidad)
                            <p class="text-sm text-gray-500">{{ $orden->especialidad->nombre }}</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Fechas -->
            <div class="card p-6">
                <h3 class="text-lg font-display font-bold text-gray-900 mb-4">Información</h3>
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-500">Código</span>
                        <span class="font-medium text-gray-900">{{ $orden->codigo_orden }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Fecha Emisión</span>
                        <span class="font-medium text-gray-900">{{ $orden->fecha_emision ? $orden->fecha_emision->format('d/m/Y') : 'N/A' }}</span>
                    </div>
                    @if($orden->fecha_vigencia)
                    <div class="flex justify-between">
                        <span class="text-gray-500">Vigencia</span>
                        <span class="font-medium text-gray-900">{{ $orden->fecha_vigencia->format('d/m/Y') }}</span>
                    </div>
                    @endif
                    <div class="flex justify-between">
                        <span class="text-gray-500">Total Items</span>
                        <span class="font-bold text-gray-900">{{ $orden->total_items }}</span>
                    </div>
                </div>
            </div>

            <!-- Cita Asociada -->
            @if($orden->cita_id)
            <div class="card p-6">
                <h3 class="text-lg font-display font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <i class="bi bi-calendar-check text-blue-600"></i>
                    Cita Asociada
                </h3>
                <div class="p-4 bg-blue-50 rounded-xl border border-blue-200">
                    <p class="text-sm text-gray-700 mb-2">Esta orden está asociada a una cita médica</p>
                    <a href="{{ route('citas.show', $orden->cita_id) }}" class="btn btn-sm btn-primary w-full mt-2">
                        <i class="bi bi-eye"></i> Ver Cita
                    </a>
                </div>
            </div>
            @endif

            <!-- Acciones -->
            @if(isset($esPropietario) && $esPropietario)
            <div class="card p-6">
                <h3 class="text-lg font-display font-bold text-gray-900 mb-4">Acciones</h3>
                <div class="space-y-2">
                    <a href="{{ route('ordenes-medicas.edit', $orden->id) }}" class="btn btn-outline w-full justify-start">
                        <i class="bi bi-pencil"></i> Editar Orden
                    </a>
                    <button onclick="window.print()" class="btn btn-outline w-full justify-start">
                        <i class="bi bi-printer"></i> Imprimir
                    </button>
                </div>
            </div>
            @endif

            <!-- Nota de Confidencialidad -->
            <div class="card p-4 bg-gray-50 border border-gray-200">
                <div class="flex gap-3">
                    <i class="bi bi-shield-check text-gray-600 text-lg"></i>
                    <div>
                        <p class="text-sm font-medium text-gray-700">Documento Confidencial</p>
                        <p class="text-xs text-gray-500 mt-1">
                            Protegido por políticas de confidencialidad médico-paciente.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
