@extends('layouts.medico')

@section('title', 'Perfil del Paciente')

@section('content')
<div class="mb-6">
    <a href="{{ route('pacientes.index') }}" class="text-medical-600 hover:text-medical-700 inline-flex items-center text-sm font-medium mb-3">
        <i class="bi bi-arrow-left mr-1"></i> Volver a Pacientes
    </a>
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h2 class="text-3xl font-display font-bold text-gray-900">Perfil del Paciente</h2>
            <p class="text-gray-500 mt-1">Información completa y registro médico</p>
        </div>
        <div class="flex gap-3">
            {{-- <a href="{{ route('citas.create') }}?paciente={{ $paciente->id }}" class="btn btn-outline">
                <i class="bi bi-calendar-plus mr-2"></i>
                Nueva Cita
            </a> --}}
            {{-- <a href="{{ route('pacientes.edit', $paciente->id) }}" class="btn btn-primary">
                <i class="bi bi-pencil mr-2"></i>
                Editar
            </a> --}}
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Columna Principal -->
    <div class="lg:col-span-2 space-y-6">
        
        <!-- Encabezado del Paciente -->
        <div class="card p-0 overflow-hidden">
            <div class="bg-gradient-to-r from-success-600 to-success-500 p-6">
                <div class="flex items-center gap-6">
                    <div class="w-24 h-24 rounded-full bg-white/20 backdrop-blur-sm flex items-center justify-center text-white text-4xl font-bold border-4 border-white/30">
                        {{ substr($paciente->primer_nombre, 0, 1) }}{{ substr($paciente->primer_apellido, 0, 1) }}
                    </div>
                    <div class="text-white flex-1">
                        <h3 class="text-2xl font-bold mb-1">{{ $paciente->primer_nombre }} {{ $paciente->primer_apellido }}</h3>
                        <p class="text-white/90 mb-2">
                            @if($paciente->fecha_nac)
                                {{ \Carbon\Carbon::parse($paciente->fecha_nac)->age }} años • 
                            @endif
                            {{ $paciente->genero }} • {{ $paciente->tipo_documento }}-{{ $paciente->numero_documento }}
                        </p>
                        <div class="flex gap-2">
                            <span class="badge bg-white/20 text-white border border-white/30">{{ $paciente->status ? 'Activo' : 'Inactivo' }}</span>
                            @if($paciente->historiaClinicaBase && $paciente->historiaClinicaBase->tipo_sangre)
                                <span class="badge bg-white/20 text-white border border-white/30">{{ $paciente->historiaClinicaBase->tipo_sangre }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-sm text-white/70">Historia Clínica</p>
                        <p class="text-xl font-bold text-white">
                            {{ $paciente->historiaClinicaBase ? 'HC-' . str_pad($paciente->historiaClinicaBase->id, 6, '0', STR_PAD_LEFT) : 'Sin Historia' }}
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="p-6">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div class="text-center p-4 bg-gray-50 rounded-xl">
                        <p class="text-3xl font-bold text-medical-600 mb-1">
                            {{ \App\Models\Cita::where('paciente_id', $paciente->id)->count() }}
                        </p>
                        <p class="text-sm text-gray-500">Consultas</p>
                    </div>
                    <div class="text-center p-4 bg-gray-50 rounded-xl">
                        <p class="text-3xl font-bold text-success-600 mb-1">
                            {{ \App\Models\Cita::where('paciente_id', $paciente->id)->where('estado_cita', 'Completada')->count() }}
                        </p>
                        <p class="text-sm text-gray-500">Completadas</p>
                    </div>
                    <div class="text-center p-4 bg-gray-50 rounded-xl">
                        <p class="text-3xl font-bold text-warning-600 mb-1">
                            {{ \App\Models\Cita::where('paciente_id', $paciente->id)->where('estado_cita', 'Pendiente')->count() }}
                        </p>
                        <p class="text-sm text-gray-500">Pendientes</p>
                    </div>
                    <div class="text-center p-4 bg-gray-50 rounded-xl">
                        <p class="text-3xl font-bold text-info-600 mb-1">
                            {{ \App\Models\Cita::where('paciente_id', $paciente->id)->where('estado_cita', 'Cancelada')->count() }}
                        </p>
                        <p class="text-sm text-gray-500">Canceladas</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Datos Personales -->
        <div class="card p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                <i class="bi bi-person-circle text-medical-600"></i>
                Datos Personales
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-gray-500 mb-1">Nombre Completo</p>
                    <p class="font-semibold text-gray-900">{{ $paciente->primer_nombre }} {{ $paciente->segundo_nombre }} {{ $paciente->primer_apellido }} {{ $paciente->segundo_apellido }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 mb-1">Documento de Identidad</p>
                    <p class="font-semibold text-gray-900">{{ $paciente->tipo_documento }}-{{ $paciente->numero_documento }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 mb-1">Fecha de Nacimiento</p>
                    <p class="font-semibold text-gray-900">
                        @if($paciente->fecha_nac)
                            {{ \Carbon\Carbon::parse($paciente->fecha_nac)->format('d/m/Y') }} ({{ \Carbon\Carbon::parse($paciente->fecha_nac)->age }} años)
                        @else
                            No registrada
                        @endif
                    </p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 mb-1">Género</p>
                    <p class="font-semibold text-gray-900">{{ $paciente->genero }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 mb-1">Estado Civil</p>
                    <p class="font-semibold text-gray-900">{{ $paciente->estado_civil ?: 'No registrado' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 mb-1">Grupo Sanguíneo</p>
                    <p class="font-semibold text-gray-900">{{ $paciente->historiaClinicaBase->tipo_sangre ?? 'No registrado' }}</p>
                </div>
            </div>
        </div>

        <!-- Contacto -->
        <div class="card p-6 border-l-4 border-l-success-500">
            <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                <i class="bi bi-telephone text-success-600"></i>
                Información de Contacto
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-gray-500 mb-1">Teléfono Principal</p>
                    <p class="font-semibold text-gray-900">{{ $paciente->prefijo_tlf }} {{ $paciente->numero_tlf }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 mb-1">Correo Electrónico</p>
                    <p class="font-semibold text-gray-900">{{ $paciente->usuario->correo ?? 'No registrado' }}</p>
                </div>
                <div class="md:col-span-2">
                    <p class="text-sm text-gray-500 mb-1">Dirección</p>
                    <p class="font-semibold text-gray-900">
                        {{ $paciente->direccion ?? 'No registrada' }}
                        @if($paciente->ciudad || $paciente->municipio || $paciente->estado)
                            <br>
                            <span class="text-gray-500 text-xs">
                                {{ $paciente->parroquia->parroquia ?? '' }}, {{ $paciente->municipio->municipio ?? '' }}, {{ $paciente->ciudad->ciudad ?? '' }}, {{ $paciente->estado->estado ?? '' }}
                            </span>
                        @endif
                    </p>
                </div>
            </div>
        </div>

        <!-- Contacto de Emergencia (Si existe en el modelo, agregarlo aquí) -->
        <!-- Por brevedad, omito este bloque si no tengo los campos exactos, pero dejo la estructura básica -->

        <!-- Próximas Citas -->
        <div class="card p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                    <i class="bi bi-calendar-check text-warning-600"></i>
                    Próximas Citas
                </h3>
            <!--  <a href="{{ route('citas.create') }}?paciente={{ $paciente->id }}" class="text-sm text-medical-600 hover:underline">
             //       <i class="bi bi-plus-lg mr-1"></i>Agendar
              </a>-->

            </div>
            
            @php
                $proximasCitas = \App\Models\Cita::where('paciente_id', $paciente->id)
                                               ->where('fecha_cita', '>=', now())
                                               ->where('status', true)
                                               ->orderBy('fecha_cita')
                                               ->limit(3)
                                               ->get();
            @endphp

            @if($proximasCitas->count() > 0)
                <div class="space-y-3">
                    @foreach($proximasCitas as $cita)
                        <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-xl border border-gray-100">
                            <div class="w-16 text-center">
                                <p class="text-2xl font-bold text-gray-700">{{ \Carbon\Carbon::parse($cita->fecha_cita)->format('d') }}</p>
                                <p class="text-xs text-gray-600">{{ strtoupper(\Carbon\Carbon::parse($cita->fecha_cita)->format('M')) }}</p>
                            </div>
                            <div class="flex-1">
                                <p class="font-semibold text-gray-900">{{ $cita->especialidad->nombre ?? 'Consulta' }}</p>
                                <p class="text-sm text-gray-600">Dr. {{ $cita->medico->primer_nombre }} {{ $cita->medico->primer_apellido }} • {{ \Carbon\Carbon::parse($cita->hora_cita)->format('h:i A') }}</p>
                            </div>
                            <span class="badge badge-{{ $cita->estado_cita == 'Pendiente' ? 'warning' : ($cita->estado_cita == 'Confirmada' ? 'info' : 'gray') }}">
                                {{ $cita->estado_cita }}
                            </span>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500 text-center py-4">No hay próximas citas agendadas.</p>
            @endif
        </div>

    </div>

    <!-- Sidebar -->
    <div class="lg:col-span-1 space-y-6">


        <!-- Estado -->
        <div class="card p-6">
            <h4 class="font-bold text-gray-900 mb-4">Estado</h4>
            <div class="space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Cuenta</span>
                    <span class="badge badge-{{ $paciente->status ? 'success' : 'danger' }}">
                        {{ $paciente->status ? 'Activa' : 'Inactiva' }}
                    </span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Registro</span>
                    <span class="text-xs text-gray-500">{{ $paciente->created_at->format('d/m/Y') }}</span>
                </div>
            </div>
        </div>

        <!-- Observaciones / Alertas -->
        @if($paciente->historiaClinicaBase && ($paciente->historiaClinicaBase->alergias || $paciente->historiaClinicaBase->enfermedades_cronicas))
            <div class="card p-6 bg-gradient-to-br from-warning-50 to-amber-50 border-warning-200">
                <h4 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <i class="bi bi-exclamation-triangle text-warning-600"></i>
                    Alertas Médicas
                </h4>
                <div class="space-y-2">
                    @if($paciente->historiaClinicaBase->alergias)
                        <div class="bg-white rounded-lg p-3 text-sm">
                            <p class="font-medium text-warning-700">Alergias</p>
                            <p class="text-xs text-gray-600 mt-1">{{ $paciente->historiaClinicaBase->alergias }}</p>
                        </div>
                    @endif
                    @if($paciente->historiaClinicaBase->enfermedades_cronicas)
                        <div class="bg-white rounded-lg p-3 text-sm">
                            <p class="font-medium text-warning-700">Enfermedades Crónicas</p>
                            <p class="text-xs text-gray-600 mt-1">{{ $paciente->historiaClinicaBase->enfermedades_cronicas }}</p>
                        </div>
                    @endif
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
