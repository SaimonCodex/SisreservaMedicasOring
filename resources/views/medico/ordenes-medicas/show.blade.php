@extends(auth()->user()->rol_id == 1 ? 'layouts.admin' : 'layouts.medico')

@section('title', 'Detalle de Orden Médica')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-4">
            <a href="{{ url('index.php/ordenes-medicas') }}" class="btn btn-outline">
                <i class="bi bi-arrow-left"></i>
            </a>
            <div>
                <h1 class="text-2xl font-display font-bold text-gray-900">Detalle de Orden Médica</h1>
                <p class="text-gray-600 mt-1">
                    {{ isset($orden->created_at) ? \Carbon\Carbon::parse($orden->created_at)->format('d \d\e F, Y - H:i A') : 'N/A' }}
                </p>
            </div>
        </div>
        <div class="flex items-center gap-2">
            @if($orden->status != 'completada')
            <a href="{{ url('index.php/ordenes-medicas/' . ($orden->id ?? 1) . '/edit') }}" class="btn btn-primary">
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
            <!-- Tipo de Orden -->
            <div class="card p-6">
                <div class="flex items-center gap-4">
                    @if($orden->tipo == 'receta')
                    <div class="w-16 h-16 rounded-xl bg-purple-100 flex items-center justify-center">
                        <i class="bi bi-prescription text-purple-600 text-3xl"></i>
                    </div>
                    <div>
                        <h3 class="text-2xl font-bold text-gray-900">Receta Médica</h3>
                        <p class="text-gray-600">Prescripción de medicamentos</p>
                    </div>
                    @elseif($orden->tipo == 'laboratorio')
                    <div class="w-16 h-16 rounded-xl bg-blue-100 flex items-center justify-center">
                        <i class="bi bi-activity text-blue-600 text-3xl"></i>
                    </div>
                    <div>
                        <h3 class="text-2xl font-bold text-gray-900">Orden de Laboratorio</h3>
                        <p class="text-gray-600">Exámenes clínicos</p>
                    </div>
                    @elseif($orden->tipo == 'imagenologia')
                    <div class="w-16 h-16 rounded-xl bg-emerald-100 flex items-center justify-center">
                        <i class="bi bi-x-ray text-emerald-600 text-3xl"></i>
                    </div>
                    <div>
                        <h3 class="text-2xl font-bold text-gray-900">Orden de Imagenología</h3>
                        <p class="text-gray-600">Estudios de imagen</p>
                    </div>
                    @else
                    <div class="w-16 h-16 rounded-xl bg-amber-100 flex items-center justify-center">
                        <i class="bi bi-arrow-right-circle text-amber-600 text-3xl"></i>
                    </div>
                    <div>
                        <h3 class="text-2xl font-bold text-gray-900">Referencia Médica</h3>
                        <p class="text-gray-600">Derivación a especialista</p>
                    </div>
                    @endif
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
                                {{ $orden->paciente->primer_nombre ?? 'N/A' }} 
                                {{ $orden->paciente->segundo_nombre ?? '' }}
                                {{ $orden->paciente->primer_apellido ?? '' }}
                                {{ $orden->paciente->segundo_apellido ?? '' }}
                            </h4>
                            <div class="grid grid-cols-3 gap-4 mt-2 text-sm">
                                <div>
                                    <p class="text-gray-500">Cédula</p>
                                    <p class="font-semibold text-gray-900">{{ $orden->paciente->cedula ?? 'N/A' }}</p>
                                </div>
                                <div>
                                    <p class="text-gray-500">Edad</p>
                                    <p class="font-semibold text-gray-900">
                                        {{ isset($orden->paciente->fecha_nacimiento) ? \Carbon\Carbon::parse($orden->paciente->fecha_nacimiento)->age . ' años' : 'N/A' }}
                                    </p>
                                </div>
                                <div>
                                    <p class="text-gray-500">Teléfono</p>
                                    <p class="font-semibold text-gray-900">{{ $orden->paciente->telefono ?? 'N/A' }}</p>
                                </div>
                            </div>
                        </div>
                        <a href="{{ route('pacientes.show', $orden->paciente->id ?? 1) }}" class="btn btn-sm btn-outline">
                            <i class="bi bi-eye"></i> Ver Perfil
                        </a>
                    </div>
                </div>
            </div>

            <!-- Receta Details -->
            @if($orden->tipo == 'receta' && $orden->receta)
            <div class="card">
                <div class="p-6 border-b border-gray-200 bg-gradient-to-r from-purple-50 to-white">
                    <h3 class="text-lg font-display font-bold text-gray-900 flex items-center gap-2">
                        <i class="bi bi-prescription text-purple-600"></i>
                        Detalles de la Receta
                    </h3>
                </div>
                <div class="p-6 space-y-4">
                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <label class="text-sm font-semibold text-gray-700 block mb-2">Medicamento</label>
                            <p class="text-lg font-bold text-gray-900">{{ $orden->receta->medicamento ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-semibold text-gray-700 block mb-2">Vía de Administración</label>
                            <p class="text-gray-900">{{ ucfirst($orden->receta->via_administracion ?? 'N/A') }}</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-3 gap-4">
                        <div class="p-4 bg-purple-50 rounded-xl">
                            <p class="text-xs text-gray-600 mb-1">Dosis</p>
                            <p class="text-lg font-bold text-gray-900">{{ $orden->receta->dosis ?? 'N/A' }}</p>
                        </div>
                        <div class="p-4 bg-blue-50 rounded-xl">
                            <p class="text-xs text-gray-600 mb-1">Frecuencia</p>
                            <p class="text-lg font-bold text-gray-900">{{ $orden->receta->frecuencia ?? 'N/A' }}</p>
                        </div>
                        <div class="p-4 bg-emerald-50 rounded-xl">
                            <p class="text-xs text-gray-600 mb-1">Duración</p>
                            <p class="text-lg font-bold text-gray-900">{{ $orden->receta->duracion ?? 'N/A' }}</p>
                        </div>
                    </div>

                    @if($orden->receta->instrucciones ?? null)
                    <div>
                        <label class="text-sm font-semibold text-gray-700 block mb-2">Instrucciones Especiales</label>
                        <div class="p-4 bg-amber-50 rounded-xl border border-amber-200">
                            <p class="text-gray-900">{{ $orden->receta->instrucciones }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- Laboratorio Details -->
            @if($orden->tipo == 'laboratorio' && $orden->laboratorio)
            <div class="card">
                <div class="p-6 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-white">
                    <h3 class="text-lg font-display font-bold text-gray-900 flex items-center gap-2">
                        <i class="bi bi-activity text-blue-600"></i>
                        Exámenes de Laboratorio
                    </h3>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <label class="text-sm font-semibold text-gray-700 block mb-2">Exámenes Solicitados</label>
                        <div class="flex flex-wrap gap-2">
                            @foreach(json_decode($orden->laboratorio->examenes ?? '[]') ?? [] as $examen)
                            <span class="badge badge-info">{{ ucfirst(str_replace('_', ' ', $examen)) }}</span>
                            @endforeach
                        </div>
                    </div>

                    @if($orden->laboratorio->otros_examenes ?? null)
                    <div>
                        <label class="text-sm font-semibold text-gray-700 block mb-2">Otros Exámenes</label>
                        <p class="text-gray-900">{{ $orden->laboratorio->otros_examenes }}</p>
                    </div>
                    @endif

                    @if($orden->laboratorio->resultados ?? null)
                    <div>
                        <label class="text-sm font-semibold text-gray-700 block mb-2 flex items-center gap-2">
                            <i class="bi bi-check-circle text-emerald-600"></i>
                            Resultados
                        </label>
                        <div class="p-4 bg-emerald-50 rounded-xl border border-emerald-200">
                            <p class="text-gray-900 whitespace-pre-wrap">{{ $orden->laboratorio->resultados }}</p>
                        </div>
                    </div>
                    @else
                    <div class="p-4 bg-amber-50 rounded-xl border border-amber-200">
                        <p class="text-amber-900 font-semibold">
                            <i class="bi bi-hourglass-split"></i> Pendiente de resultados
                        </p>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- Imagenología Details -->
            @if($orden->tipo == 'imagenologia' && $orden->imagenologia)
            <div class="card">
                <div class="p-6 border-b border-gray-200 bg-gradient-to-r from-emerald-50 to-white">
                    <h3 class="text-lg font-display font-bold text-gray-900 flex items-center gap-2">
                        <i class="bi bi-x-ray text-emerald-600"></i>
                        Estudio de Imagenología
                    </h3>
                </div>
                <div class="p-6 space-y-4">
                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <label class="text-sm font-semibold text-gray-700 block mb-2">Tipo de Estudio</label>
                            <p class="text-lg font-bold text-gray-900">{{ ucfirst($orden->imagenologia->tipo_estudio ?? 'N/A') }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-semibold text-gray-700 block mb-2">Área/Región</label>
                            <p class="text-lg font-bold text-gray-900">{{ $orden->imagenologia->region ?? 'N/A' }}</p>
                        </div>
                    </div>

                    @if($orden->imagenologia->indicaciones_clinicas ?? null)
                    <div>
                        <label class="text-sm font-semibold text-gray-700 block mb-2">Indicaciones Clínicas</label>
                        <div class="p-4 bg-gray-50 rounded-xl">
                            <p class="text-gray-900">{{ $orden->imagenologia->indicaciones_clinicas }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- Referencia Details -->
            @if($orden->tipo == 'referencia' && $orden->referencia)
            <div class="card">
                <div class="p-6 border-b border-gray-200 bg-gradient-to-r from-amber-50 to-white">
                    <h3 class="text-lg font-display font-bold text-gray-900 flex items-center gap-2">
                        <i class="bi bi-arrow-right-circle text-amber-600"></i>
                        Referencia Médica
                    </h3>
                </div>
                <div class="p-6 space-y-4">
                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <label class="text-sm font-semibold text-gray-700 block mb-2">Especialidad</label>
                            <p class="text-lg font-bold text-gray-900">{{ ucfirst(str_replace('_', ' ', $orden->referencia->especialidad_referencia ?? 'N/A')) }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-semibold text-gray-700 block mb-2">Prioridad</label>
                            @if($orden->referencia->prioridad == 'muy_urgente')
                            <span class="badge badge-danger">Muy Urgente</span>
                            @elseif($orden->referencia->prioridad == 'urgente')
                            <span class="badge badge-warning">Urgente</span>
                            @else
                            <span class="badge badge-info">Normal</span>
                            @endif
                        </div>
                    </div>

                    <div>
                        <label class="text-sm font-semibold text-gray-700 block mb-2">Motivo de Referencia</label>
                        <div class="p-4 bg-amber-50 rounded-xl border border-amber-200">
                            <p class="text-gray-900">{{ $orden->referencia->motivo_referencia ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Observaciones -->
            @if($orden->observaciones ?? null)
            <div class="card">
                <div class="p-6 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-white">
                    <h3 class="text-lg font-display font-bold text-gray-900 flex items-center gap-2">
                        <i class="bi bi-sticky text-gray-600"></i>
                        Observaciones
                    </h3>
                </div>
                <div class="p-6">
                    <p class="text-gray-900">{{ $orden->observaciones }}</p>
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Estado -->
            <div class="card p-6">
                <h3 class="text-lg font-display font-bold text-gray-900 mb-4">Estado</h3>
                @if($orden->status == 'completada')
                <div class="p-4 bg-emerald-50 rounded-xl border border-emerald-200">
                    <p class="font-bold text-emerald-900 flex items-center gap-2">
                        <i class="bi bi-check-circle-fill"></i> Completada
                    </p>
                </div>
                @elseif($orden->status == 'en_proceso')
                <div class="p-4 bg-blue-50 rounded-xl border border-blue-200">
                    <p class="font-bold text-blue-900 flex items-center gap-2">
                        <i class="bi bi-hourglass-split"></i> En Proceso
                    </p>
                </div>
                @elseif($orden->status == 'pendiente')
                <div class="p-4 bg-amber-50 rounded-xl border border-amber-200">
                    <p class="font-bold text-amber-900 flex items-center gap-2">
                        <i class="bi bi-clock"></i> Pendiente
                    </p>
                </div>
                @else
                <div class="p-4 bg-rose-50 rounded-xl border border-rose-200">
                    <p class="font-bold text-rose-900 flex items-center gap-2">
                        <i class="bi bi-x-circle"></i> Cancelada
                    </p>
                </div>
                @endif
            </div>

            <!-- Cita Asociada -->
            @if($orden->cita_id ?? null)
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

            <!-- Doctor Info -->
            <div class="card p-6">
                <h3 class="text-lg font-display font-bold text-gray-900 mb-4">Médico Responsable</h3>
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-full bg-gradient-to-br from-emerald-500 to-emerald-600 flex items-center justify-center text-white font-bold">
                        {{ strtoupper(substr($orden->medico->usuario->nombre ?? 'D', 0, 1)) }}
                    </div>
                    <div>
                        <p class="font-semibold text-gray-900">{{ $orden->medico->usuario->nombre ?? 'Dr. Nombre' }}</p>
                        <p class="text-sm text-gray-500">Médico</p>
                    </div>
                </div>
            </div>

            <!-- Metadata -->
            <div class="card p-6">
                <h3 class="text-lg font-display font-bold text-gray-900 mb-4">Información del Registro</h3>
                <div class="space-y-3 text-sm">
                    <div>
                        <p class="text-gray-500">Fecha de Creación</p>
                        <p class="font-semibold text-gray-900">
                            {{ isset($orden->created_at) ? \Carbon\Carbon::parse($orden->created_at)->format('d/m/Y H:i A') : 'N/A' }}
                        </p>
                    </div>
                    <div>
                        <p class="text-gray-500">Última Actualización</p>
                        <p class="font-semibold text-gray-900">
                            {{ isset($orden->updated_at) ? \Carbon\Carbon::parse($orden->updated_at)->format('d/m/Y H:i A') : 'N/A' }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            @if($orden->status != 'completada')
            <div class="card p-6">
                <h3 class="text-lg font-display font-bold text-gray-900 mb-4">Acciones</h3>
                <div class="space-y-2">
                    @if($orden->tipo == 'laboratorio' && !($orden->laboratorio->resultados ?? null))
                    <a href="{{ url('index.php/ordenes-medicas/registrar-resultados?laboratorio=' . $orden->laboratorio->id) }}" class="btn btn-success w-full justify-start">
                        <i class="bi bi-clipboard-check"></i>
                        Registrar Resultados
                    </a>
                    @endif
                    <button onclick="if(confirm('¿Marcar como completada?')) { /* submit form */ }" class="btn btn-outline w-full justify-start">
                        <i class="bi bi-check-circle"></i>
                        Marcar Completada
                    </button>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
