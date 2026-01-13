@extends('layouts.medico')

@section('title', 'Detalle de Cita')

@section('content')
<div class="space-y-6">
    <!-- Header with Actions -->
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-4">
            <a href="{{ route('citas.index') }}" class="btn btn-outline">
                <i class="bi bi-arrow-left"></i>
            </a>
            <div>
                <h1 class="text-2xl font-display font-bold text-gray-900">Detalle de Cita</h1>
                <p class="text-gray-600 mt-1">
                    {{ \Carbon\Carbon::parse($cita->fecha_cita)->format('d \d\e F \d\e Y') }} - 
                    {{ \Carbon\Carbon::parse($cita->hora_inicio)->format('h:i A') }}
                </p>
            </div>
        </div>
        <div class="flex items-center gap-2">
            @if(in_array($cita->estado_cita, ['Confirmada', 'En Progreso']))
                <a href="{{ route('historia-clinica.evoluciones.create', ['citaId' => $cita->id]) }}" class="btn btn-success">
                    <i class="bi bi-file-earmark-medical"></i>
                    <span>Registrar Evolución</span>
                </a>
            @endif
            @if(in_array($cita->estado_cita, ['Programada', 'Confirmada']))
                <a href="{{ route('citas.edit', $cita->id) }}" class="btn btn-primary">
                    <i class="bi bi-pencil"></i>
                    <span>Editar</span>
                </a>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Info -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Patient Info -->
            <div class="card">
                <div class="p-6 border-b border-gray-200 bg-gradient-to-r from-emerald-50 to-white">
                    <h3 class="text-lg font-display font-bold text-gray-900 flex items-center gap-2">
                        <i class="bi bi-person-circle text-emerald-600"></i>
                        Información del Paciente
                    </h3>
                </div>
                <div class="p-6">
                    <div class="flex items-start gap-6">
                        <div class="w-24 h-24 rounded-2xl bg-gradient-to-br from-emerald-500 to-emerald-600 flex items-center justify-center text-white text-3xl font-bold shadow-lg">
                            {{ strtoupper(substr($cita->paciente->primer_nombre ?? 'P', 0, 1)) }}{{ strtoupper(substr($cita->paciente->primer_apellido ?? 'A', 0, 1)) }}
                        </div>
                        <div class="flex-1">
                            <h4 class="text-2xl font-bold text-gray-900">
                                {{ $cita->paciente->primer_nombre ?? '' }} {{ $cita->paciente->segundo_nombre ?? '' }} 
                                {{ $cita->paciente->primer_apellido ?? '' }} {{ $cita->paciente->segundo_apellido ?? '' }}
                            </h4>
                            <div class="grid grid-cols-2 gap-4 mt-4">
                                <div>
                                    <p class="text-sm text-gray-500">Cédula</p>
                                    <p class="font-semibold text-gray-900">
                                        {{ $cita->paciente->tipo_documento ?? '' }}-{{ $cita->paciente->numero_documento ?? 'N/A' }}
                                    </p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Teléfono</p>
                                    <p class="font-semibold text-gray-900">
                                        {{ $cita->paciente->prefijo_tlf ?? '' }} {{ $cita->paciente->numero_tlf ?? 'N/A' }}
                                    </p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Correo</p>
                                    <p class="font-semibold text-gray-900">{{ $cita->paciente->usuario->correo ?? 'N/A' }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Edad</p>
                                    <p class="font-semibold text-gray-900">
                                        @if($cita->paciente->fecha_nac)
                                            {{ \Carbon\Carbon::parse($cita->paciente->fecha_nac)->age }} años
                                            ({{ $cita->paciente->genero == 'M' ? 'Masculino' : 'Femenino' }})
                                        @else
                                            N/A
                                        @endif
                                    </p>
                                </div>
                            </div>
                            <div class="mt-4 flex gap-2">
                                <a href="{{ route('pacientes.show', $cita->paciente->id) }}" class="btn btn-sm btn-outline">
                                    <i class="bi bi-eye"></i> Ver Perfil Completo
                                </a>
                                @if($cita->paciente->historiaClinicaBase)
                                    <a href="{{ route('historia-clinica.base.show', $cita->paciente->id) }}" class="btn btn-sm btn-info">
                                        <i class="bi bi-file-medical"></i> Historia Clínica Base
                                    </a>
                                @else
                                    <a href="{{ route('historia-clinica.base.create', $cita->paciente->id) }}" class="btn btn-sm btn-success">
                                        <i class="bi bi-plus-circle"></i> Crear Historia Clínica Base
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Representative Info (if special patient) -->
            @if($cita->pacienteEspecial && $cita->representante)
            <div class="card">
                <div class="p-6 border-b border-gray-200 bg-gradient-to-r from-purple-50 to-white">
                    <h3 class="text-lg font-display font-bold text-gray-900 flex items-center gap-2">
                        <i class="bi bi-person-badge text-purple-600"></i>
                        Datos del Representante
                    </h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-500">Nombre Completo</p>
                            <p class="font-semibold text-gray-900">
                                {{ $cita->representante->primer_nombre ?? '' }} {{ $cita->representante->segundo_nombre ?? '' }}
                                {{ $cita->representante->primer_apellido ?? '' }} {{ $cita->representante->segundo_apellido ?? '' }}
                            </p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Cédula</p>
                            <p class="font-semibold text-gray-900">
                                {{ $cita->representante->tipo_documento ?? '' }}-{{ $cita->representante->numero_documento ?? 'N/A' }}
                            </p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Teléfono</p>
                            <p class="font-semibold text-gray-900">
                                {{ $cita->representante->prefijo_tlf ?? '' }} {{ $cita->representante->numero_tlf ?? 'N/A' }}
                            </p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Relación con Paciente</p>
                            <p class="font-semibold text-gray-900">
                                @if($cita->pacienteEspecial)
                                    {{ $cita->pacienteEspecial->parentesco ?? 'No especificado' }}
                                @else
                                    N/A
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Appointment Details -->
            <div class="card">
                <div class="p-6 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-white">
                    <h3 class="text-lg font-display font-bold text-gray-900 flex items-center gap-2">
                        <i class="bi bi-calendar-event text-blue-600"></i>
                        Detalles de la Cita
                    </h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <label class="text-sm font-semibold text-gray-700 block mb-2">Fecha y Hora</label>
                            <div class="flex items-center gap-3 p-4 bg-blue-50 rounded-xl border border-blue-100">
                                <i class="bi bi-calendar-check text-blue-600 text-2xl"></i>
                                <div>
                                    <p class="font-bold text-gray-900">{{ \Carbon\Carbon::parse($cita->fecha_cita)->format('d \d\e F, Y') }}</p>
                                    <p class="text-sm text-gray-600">
                                        {{ \Carbon\Carbon::parse($cita->hora_inicio)->format('h:i A') }} - 
                                        {{ \Carbon\Carbon::parse($cita->hora_fin)->format('h:i A') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div>
                            <label class="text-sm font-semibold text-gray-700 block mb-2">Especialidad</label>
                            <div class="flex items-center gap-3 p-4 bg-indigo-50 rounded-xl border border-indigo-100">
                                <i class="bi bi-heart-pulse text-indigo-600 text-2xl"></i>
                                <div>
                                    <p class="font-bold text-gray-900">{{ $cita->especialidad->nombre ?? 'N/A' }}</p>
                                </div>
                            </div>
                        </div>
                        <div>
                            <label class="text-sm font-semibold text-gray-700 block mb-2">Consultorio</label>
                            <div class="flex items-center gap-3 p-4 bg-emerald-50 rounded-xl border border-emerald-100">
                                <i class="bi bi-building text-emerald-600 text-2xl"></i>
                                <div>
                                    <p class="font-bold text-gray-900">{{ $cita->consultorio->nombre ?? 'N/A' }}</p>
                                    <p class="text-sm text-gray-600">{{ $cita->consultorio->direccion ?? '' }}</p>
                                </div>
                            </div>
                        </div>
                        <div>
                            <label class="text-sm font-semibold text-gray-700 block mb-2">Tipo de Consulta y Tarifa</label>
                            <div class="flex items-center gap-3 p-4 bg-amber-50 rounded-xl border border-amber-100">
                                @if($cita->tipo_consulta == 'Domicilio')
                                    <i class="bi bi-house-door text-amber-600 text-2xl"></i>
                                @elseif($cita->tipo_consulta == 'Online')
                                    <i class="bi bi-camera-video text-amber-600 text-2xl"></i>
                                @else
                                    <i class="bi bi-building text-amber-600 text-2xl"></i>
                                @endif
                                <div>
                                    <p class="font-bold text-gray-900">{{ $cita->tipo_consulta ?? 'Presencial' }}</p>
                                    <p class="text-sm text-emerald-600 font-semibold">${{ number_format($cita->tarifa_total ?? $cita->tarifa ?? 0, 2) }}</p>
                                </div>
                            </div>
                        </div>
                        
                        @if($cita->tipo_consulta == 'Domicilio')
                        <div class="col-span-2">
                            <label class="text-sm font-semibold text-gray-700 block mb-2">Dirección de Domicilio</label>
                            <div class="p-4 bg-orange-50 rounded-xl border border-orange-200">
                                <p class="text-gray-900">
                                    <i class="bi bi-geo-alt text-orange-600 mr-2"></i>
                                    {{ $cita->direccion_domicilio ?? $cita->paciente->direccion_detallada ?? 'No especificada' }}
                                </p>
                            </div>
                        </div>
                        @endif

                        @if($cita->motivo)
                        <div class="col-span-2">
                            <label class="text-sm font-semibold text-gray-700 block mb-2">Motivo de Consulta</label>
                            <div class="p-4 bg-gray-50 rounded-xl border border-gray-200">
                                <p class="text-gray-900">{{ $cita->motivo }}</p>
                            </div>
                        </div>
                        @endif
                        
                        @if($cita->observaciones)
                        <div class="col-span-2">
                            <label class="text-sm font-semibold text-gray-700 block mb-2">Observaciones</label>
                            <div class="p-4 bg-amber-50 rounded-xl border border-amber-200">
                                <p class="text-gray-900">{{ $cita->observaciones }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Historia Clínica Base (Quick View) -->
            @if($cita->paciente->historiaClinicaBase)
            <div class="card">
                <div class="p-6 border-b border-gray-200 bg-gradient-to-r from-teal-50 to-white">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-display font-bold text-gray-900 flex items-center gap-2">
                            <i class="bi bi-file-medical text-teal-600"></i>
                            Historia Clínica Base
                        </h3>
                        <a href="{{ route('historia-clinica.base.show', $cita->paciente->id) }}" class="btn btn-sm btn-outline">
                            <i class="bi bi-eye"></i> Ver Completa
                        </a>
                    </div>
                </div>
                <div class="p-6">
                    @php $hcb = $cita->paciente->historiaClinicaBase; @endphp
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div class="p-3 bg-red-50 rounded-lg border border-red-100">
                            <p class="text-xs text-gray-500">Tipo de Sangre</p>
                            <p class="font-bold text-red-700">{{ $hcb->tipo_sangre ?? 'N/A' }}</p>
                        </div>
                        <div class="p-3 bg-amber-50 rounded-lg border border-amber-100">
                            <p class="text-xs text-gray-500">Alergias</p>
                            <p class="font-bold text-amber-700">{{ $hcb->alergias ?? 'Ninguna conocida' }}</p>
                        </div>
                        <div class="p-3 bg-purple-50 rounded-lg border border-purple-100">
                            <p class="text-xs text-gray-500">Enf. Crónicas</p>
                            <p class="font-bold text-purple-700">{{ $hcb->enfermedades_cronicas ?? 'Ninguna' }}</p>
                        </div>
                        <div class="p-3 bg-blue-50 rounded-lg border border-blue-100">
                            <p class="text-xs text-gray-500">Medicamentos</p>
                            <p class="font-bold text-blue-700">{{ $hcb->medicamentos_actuales ?? 'Ninguno' }}</p>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Evoluciones Previas con este Médico -->
            @if(isset($evolucionesPrevias) && $evolucionesPrevias->count() > 0)
            <div class="card">
                <div class="p-6 border-b border-gray-200 bg-gradient-to-r from-violet-50 to-white">
                    <h3 class="text-lg font-display font-bold text-gray-900 flex items-center gap-2">
                        <i class="bi bi-journal-medical text-violet-600"></i>
                        Historial de Consultas Anteriores ({{ $evolucionesPrevias->count() }})
                    </h3>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        @foreach($evolucionesPrevias as $evolucion)
                        <div class="flex items-start gap-4 p-4 bg-gray-50 rounded-xl border border-gray-100 hover:bg-gray-100 transition-colors">
                            <div class="w-12 h-12 rounded-lg bg-violet-100 flex items-center justify-center flex-shrink-0">
                                <i class="bi bi-file-medical text-violet-600 text-xl"></i>
                            </div>
                            <div class="flex-1">
                                <div class="flex items-start justify-between">
                                    <div>
                                        <p class="font-semibold text-gray-900">
                                            {{ \Carbon\Carbon::parse($evolucion->fecha)->format('d/m/Y') }}
                                        </p>
                                        <p class="text-sm text-gray-500">
                                            {{ $evolucion->cita->especialidad->nombre ?? 'Consulta General' }}
                                        </p>
                                    </div>
                                    <a href="{{ route('historia-clinica.evoluciones.show', $evolucion->id) }}" class="btn btn-sm btn-outline">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </div>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-700">
                                        <strong>Diagnóstico:</strong> {{ Str::limit($evolucion->diagnostico ?? 'No registrado', 100) }}
                                    </p>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            <!-- Evolution if exists for THIS appointment -->
            @if($cita->evolucionClinica)
            <div class="card">
                <div class="p-6 border-b border-gray-200 bg-gradient-to-r from-purple-50 to-white">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-display font-bold text-gray-900 flex items-center gap-2">
                            <i class="bi bi-file-earmark-medical text-purple-600"></i>
                            Evolución Clínica de Esta Cita
                        </h3>
                        <span class="badge badge-success">Registrada</span>
                    </div>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <div>
                            <label class="text-sm font-semibold text-gray-700">Diagnóstico</label>
                            <p class="mt-1 text-gray-900">{{ $cita->evolucionClinica->diagnostico ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-semibold text-gray-700">Tratamiento</label>
                            <p class="mt-1 text-gray-900">{{ $cita->evolucionClinica->tratamiento ?? 'N/A' }}</p>
                        </div>
                        <div class="pt-4 border-t border-gray-200">
                            <a href="{{ route('historia-clinica.evoluciones.show', $cita->evolucionClinica->id) }}" class="btn btn-sm btn-outline">
                                <i class="bi bi-eye"></i> Ver Evolución Completa
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Status Card -->
            <div class="card">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-display font-bold text-gray-900">Estado de la Cita</h3>
                </div>
                <div class="p-6">
                    <div class="text-center">
                        @php
                            $estadoConfig = [
                                'Programada' => ['icon' => 'bi-clock-fill', 'color' => 'amber', 'label' => 'Programada'],
                                'Confirmada' => ['icon' => 'bi-check-circle-fill', 'color' => 'emerald', 'label' => 'Confirmada'],
                                'En Progreso' => ['icon' => 'bi-play-circle-fill', 'color' => 'blue', 'label' => 'En Progreso'],
                                'Completada' => ['icon' => 'bi-check-all', 'color' => 'indigo', 'label' => 'Completada'],
                                'Cancelada' => ['icon' => 'bi-x-circle-fill', 'color' => 'rose', 'label' => 'Cancelada'],
                                'No Asistió' => ['icon' => 'bi-person-x-fill', 'color' => 'rose', 'label' => 'No Asistió']
                            ];
                            $config = $estadoConfig[$cita->estado_cita] ?? ['icon' => 'bi-question-circle', 'color' => 'gray', 'label' => $cita->estado_cita];
                        @endphp
                        <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-{{ $config['color'] }}-100 mb-4">
                            <i class="bi {{ $config['icon'] }} text-4xl text-{{ $config['color'] }}-600"></i>
                        </div>
                        <p class="font-bold text-{{ $config['color'] }}-900 text-lg">{{ $config['label'] }}</p>

                        {{-- Botón para marcar como completada (solo para citas Confirmadas) --}}
                        @if($cita->estado_cita == 'Confirmada')
                        <form action="{{ route('citas.cambiar-estado', $cita->id) }}" method="POST" class="mt-4">
                            @csrf
                            <input type="hidden" name="estado_cita" value="Completada">
                            <button type="submit" class="btn btn-primary w-full" onclick="return confirm('¿Está seguro de marcar esta cita como COMPLETADA? Esta acción indica que la consulta ha finalizado.')">
                                <i class="bi bi-check-all"></i>
                                Marcar como Completada
                            </button>
                        </form>
                        @endif

                        @if(in_array($cita->estado_cita, ['Programada', 'Confirmada']))
                        <div class="mt-4 pt-4 border-t border-gray-200">
                            <form action="{{ route('citas.destroy', $cita->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger w-full" onclick="return confirm('¿Está seguro de CANCELAR esta cita? Esta acción no se puede deshacer.')">
                                    <i class="bi bi-x-lg"></i>
                                    Cancelar Cita
                                </button>
                            </form>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card p-6">
                <h3 class="text-lg font-display font-bold text-gray-900 mb-4">Acciones Rápidas</h3>
                <div class="space-y-2">
                    <a href="{{ route('ordenes-medicas.create') }}?paciente={{ $cita->paciente->id }}" class="btn btn-outline w-full justify-start">
                        <i class="bi bi-clipboard-plus"></i>
                        Nueva Orden Médica
                    </a>
                    @if($cita->paciente->historiaClinicaBase)
                        <a href="{{ route('historia-clinica.base.show', $cita->paciente->id) }}" class="btn btn-outline w-full justify-start">
                            <i class="bi bi-folder2-open"></i>
                            Ver Expediente Completo
                        </a>
                    @endif
                    <a href="{{ route('historia-clinica.evoluciones.index', $cita->paciente->id) }}" class="btn btn-outline w-full justify-start">
                        <i class="bi bi-journal-medical"></i>
                        Ver Todas las Evoluciones
                    </a>
                </div>
            </div>

            <!-- Timeline -->
            <div class="card">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-display font-bold text-gray-900">Línea de Tiempo</h3>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <div class="flex gap-3">
                            <div class="flex flex-col items-center">
                                <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center">
                                    <i class="bi bi-plus-circle text-blue-600"></i>
                                </div>
                                <div class="w-0.5 h-full bg-blue-200"></div>
                            </div>
                            <div class="flex-1 pb-4">
                                <p class="font-semibold text-gray-900">Cita Creada</p>
                                <p class="text-sm text-gray-500">{{ $cita->created_at->format('d/m/Y H:i A') }}</p>
                            </div>
                        </div>

                        @if($cita->estado_cita != 'Programada')
                        <div class="flex gap-3">
                            <div class="flex flex-col items-center">
                                <div class="w-8 h-8 rounded-full bg-emerald-100 flex items-center justify-center">
                                    <i class="bi bi-check-circle text-emerald-600"></i>
                                </div>
                                @if(!in_array($cita->estado_cita, ['Confirmada', 'Cancelada', 'No Asistió']))
                                <div class="w-0.5 h-full bg-emerald-200"></div>
                                @endif
                            </div>
                            <div class="flex-1 pb-4">
                                <p class="font-semibold text-gray-900">{{ $cita->estado_cita }}</p>
                                <p class="text-sm text-gray-500">{{ $cita->updated_at->format('d/m/Y H:i A') }}</p>
                            </div>
                        </div>
                        @endif

                        @if($cita->evolucionClinica)
                        <div class="flex gap-3">
                            <div class="flex flex-col items-center">
                                <div class="w-8 h-8 rounded-full bg-purple-100 flex items-center justify-center">
                                    <i class="bi bi-file-medical text-purple-600"></i>
                                </div>
                            </div>
                            <div class="flex-1">
                                <p class="font-semibold text-gray-900">Evolución Registrada</p>
                                <p class="text-sm text-gray-500">{{ $cita->evolucionClinica->created_at->format('d/m/Y H:i A') }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
