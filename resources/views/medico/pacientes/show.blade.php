@extends('layouts.medico')

@section('title', 'Perfil del Paciente')

@section('content')
<div class="mb-6">
    <a href="{{ route('pacientes.index') }}" class="text-medical-600 hover:text-medical-700 inline-flex items-center text-sm font-medium mb-3">
        <i class="bi bi-arrow-left mr-1"></i> Volver a Mis Pacientes
    </a>
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h2 class="text-3xl font-display font-bold text-gray-900">Perfil del Paciente</h2>
            <p class="text-gray-500 mt-1">Información completa y registro médico</p>
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
                        {{ strtoupper(substr($paciente->primer_nombre ?? 'P', 0, 1)) }}{{ strtoupper(substr($paciente->primer_apellido ?? 'A', 0, 1)) }}
                    </div>
                    <div class="text-white flex-1">
                        <h3 class="text-2xl font-bold mb-1">{{ $paciente->primer_nombre }} {{ $paciente->segundo_nombre }} {{ $paciente->primer_apellido }} {{ $paciente->segundo_apellido }}</h3>
                        <p class="text-white/90 mb-2">
                            {{ $paciente->fecha_nacimiento ? \Carbon\Carbon::parse($paciente->fecha_nacimiento)->age . ' años' : 'N/A' }} • 
                            {{ $paciente->genero ?? 'N/A' }} • 
                            {{ $paciente->tipo_documento }}-{{ $paciente->numero_documento }}
                        </p>
                        <div class="flex gap-2">
                            <span class="badge bg-white/20 text-white border border-white/30">{{ $paciente->status ? 'Activo' : 'Inactivo' }}</span>
                            @if($paciente->grupo_sanguineo)
                            <span class="badge bg-white/20 text-white border border-white/30">{{ $paciente->grupo_sanguineo }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-sm text-white/70">Historia Clínica</p>
                        <p class="text-xl font-bold text-white">{{ $paciente->historiaClinicaBase->numero_historia ?? 'N/A' }}</p>
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
                        {{ $paciente->fecha_nacimiento ? \Carbon\Carbon::parse($paciente->fecha_nacimiento)->format('d/m/Y') . ' (' . \Carbon\Carbon::parse($paciente->fecha_nacimiento)->age . ' años)' : 'N/A' }}
                    </p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 mb-1">Género</p>
                    <p class="font-semibold text-gray-900">{{ $paciente->genero ?? 'N/A' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 mb-1">Estado Civil</p>
                    <p class="font-semibold text-gray-900">{{ $paciente->estado_civil ?? 'N/A' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 mb-1">Grupo Sanguíneo</p>
                    <p class="font-semibold text-gray-900">{{ $paciente->grupo_sanguineo ?? 'N/A' }}</p>
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
                    <p class="font-semibold text-gray-900">{{ $paciente->telefono ?? 'N/A' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 mb-1">Teléfono Secundario</p>
                    <p class="font-semibold text-gray-900">{{ $paciente->telefono_secundario ?? 'N/A' }}</p>
                </div>
                <div class="md:col-span-2">
                    <p class="text-sm text-gray-500 mb-1">Correo Electrónico</p>
                    <p class="font-semibold text-gray-900">{{ $paciente->usuario->email ?? 'N/A' }}</p>
                </div>
                <div class="md:col-span-2">
                    <p class="text-sm text-gray-500 mb-1">Dirección</p>
                    <p class="font-semibold text-gray-900">{{ $paciente->direccion ?? 'N/A' }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="lg:col-span-1 space-y-6">
        <!-- Acciones Rápidas -->
        <div class="card p-6 sticky top-6">
            <h4 class="font-bold text-gray-900 mb-4">Acciones Rápidas</h4>
            <div class="space-y-2">
                <a href="{{ route('historia-clinica.base.show', $paciente->id) }}" class="btn btn-outline w-full justify-start">
                    <i class="bi bi-file-medical mr-2"></i>
                    Ver Historia Clínica
                </a>
                <a href="{{ route('historia-clinica.evoluciones.create', ['pacienteId' => $paciente->id]) }}" class="btn btn-outline w-full justify-start">
                    <i class="bi bi-clipboard-pulse mr-2"></i>
                    Nueva Evolución
                </a>
                <a href="{{ route('ordenes-medicas.create', ['paciente' => $paciente->id]) }}" class="btn btn-outline w-full justify-start">
                    <i class="bi bi-prescription2 mr-2"></i>
                    Nueva Orden Médica
                </a>
            </div>
        </div>

        <!-- Estado -->
        <div class="card p-6">
            <h4 class="font-bold text-gray-900 mb-4">Estado</h4>
            <div class="space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Cuenta</span>
                    <span class="badge {{ $paciente->status ? 'badge-success' : 'badge-danger' }}">{{ $paciente->status ? 'Activa' : 'Inactiva' }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Registro</span>
                    <span class="text-xs text-gray-500">{{ $paciente->created_at ? $paciente->created_at->format('d/m/Y') : 'N/A' }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
