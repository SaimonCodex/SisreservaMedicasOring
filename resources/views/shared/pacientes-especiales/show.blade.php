@extends('layouts.admin')

@section('title', 'Detalle del Paciente Especial')

@section('content')
<div class="mb-6">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div class="flex items-center gap-3">
            <a href="{{ route('pacientes-especiales.index') }}" class="btn btn-ghost">
                <i class="bi bi-arrow-left"></i>
            </a>
            <div>
                <h2 class="text-3xl font-display font-bold text-gray-900">
                    {{ $pacienteEspecial->primer_nombre }} {{ $pacienteEspecial->primer_apellido }}
                </h2>
                <p class="text-gray-500 mt-1">Paciente Especial - Historia: {{ $pacienteEspecial->numero_historia ?? 'N/A' }}</p>
            </div>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('pacientes-especiales.edit', $pacienteEspecial->id) }}" class="btn btn-outline">
                <i class="bi bi-pencil mr-2"></i>
                Editar
            </a>
            <a href="{{ route('historia-clinica.base.index', $pacienteEspecial->id) }}" class="btn btn-primary">
                <i class="bi bi-file-medical mr-2"></i>
                Historia Clínica
            </a>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    
    <!-- Información Principal -->
    <div class="lg:col-span-2 space-y-6">
        
        <!-- Datos Personales -->
        <div class="card p-6">
            <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
                <i class="bi bi-person-fill text-warning-600"></i>
                Datos Personales
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <p class="text-sm text-gray-500 mb-1">Nombre Completo</p>
                    <p class="font-semibold text-gray-900">
                        {{ $pacienteEspecial->primer_nombre }} {{ $pacienteEspecial->segundo_nombre }} 
                        {{ $pacienteEspecial->primer_apellido }} {{ $pacienteEspecial->segundo_apellido }}
                    </p>
                </div>
                
                <div>
                    <p class="text-sm text-gray-500 mb-1">Documento de Identidad</p>
                    <p class="font-semibold text-gray-900">
                        {{ $pacienteEspecial->tipo_documento }}-{{ $pacienteEspecial->numero_documento }}
                    </p>
                </div>
                
                <div>
                    <p class="text-sm text-gray-500 mb-1">Fecha de Nacimiento</p>
                    <p class="font-semibold text-gray-900">
                        {{ \Carbon\Carbon::parse($pacienteEspecial->fecha_nacimiento)->format('d/m/Y') }}
                    </p>
                </div>
                
                <div>
                    <p class="text-sm text-gray-500 mb-1">Edad</p>
                    <p class="font-semibold text-gray-900">
                        {{ \Carbon\Carbon::parse($pacienteEspecial->fecha_nacimiento)->age }} años
                    </p>
                </div>
                
                <div>
                    <p class="text-sm text-gray-500 mb-1">Género</p>
                    <p class="font-semibold text-gray-900">{{ $pacienteEspecial->genero }}</p>
                </div>
                
                <div>
                    <p class="text-sm text-gray-500 mb-1">Estado</p>
                    @if($pacienteEspecial->status)
                        <span class="badge badge-success">Activo</span>
                    @else
                        <span class="badge badge-danger">Inactivo</span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Condición Especial -->
        <div class="card p-6">
            <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
                <i class="bi bi-heart-pulse text-warning-600"></i>
                Condición Especial
            </h3>
            
            <div class="space-y-4">
                <div>
                    <p class="text-sm text-gray-500 mb-1">Tipo de Condición</p>
                    <span class="badge badge-warning text-base">
                        {{ ucfirst(str_replace('_', ' ', $pacienteEspecial->tipo_condicion)) }}
                    </span>
                </div>
                
                @if($pacienteEspecial->observaciones_medicas)
                <div>
                    <p class="text-sm text-gray-500 mb-1">Observaciones Médicas</p>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-gray-900">{{ $pacienteEspecial->observaciones_medicas }}</p>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Representante Legal -->
        <div class="card p-6">
            <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
                <i class="bi bi-shield-check text-warning-600"></i>
                Representante Legal
            </h3>
            
            @if($pacienteEspecial->representante)
            <div class="bg-info-50 border border-info-200 rounded-lg p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Nombre Completo</p>
                        <p class="font-semibold text-gray-900">{{ $pacienteEspecial->representante->nombre_completo }}</p>
                    </div>
                    
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Documento</p>
                        <p class="font-semibold text-gray-900">{{ $pacienteEspecial->representante->numero_documento }}</p>
                    </div>
                    
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Parentesco</p>
                        <p class="font-semibold text-gray-900">{{ $pacienteEspecial->representante->parentesco }}</p>
                    </div>
                    
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Teléfono</p>
                        <p class="font-semibold text-gray-900">{{ $pacienteEspecial->representante->telefono ?? 'N/A' }}</p>
                    </div>
                    
                    @if($pacienteEspecial->representante->email)
                    <div class="md:col-span-2">
                        <p class="text-sm text-gray-600 mb-1">Email</p>
                        <p class="font-semibold text-gray-900">{{ $pacienteEspecial->representante->email }}</p>
                    </div>
                    @endif
                </div>
                
                <div class="mt-4 pt-4 border-t border-info-300">
                    <a href="{{ route('representantes.show', $pacienteEspecial->representante->id) }}" class="btn btn-sm btn-outline">
                        <i class="bi bi-eye mr-2"></i>
                        Ver Perfil Completo
                    </a>
                </div>
            </div>
            @else
            <div class="bg-gray-50 rounded-lg p-6 text-center">
                <i class="bi bi-exclamation-circle text-4xl text-gray-400 mb-2"></i>
                <p class="text-gray-600">No hay representante asignado</p>
            </div>
            @endif
        </div>

        <!-- Historial de Citas -->
        <div class="card p-6">
            <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
                <i class="bi bi-calendar-event text-warning-600"></i>
                Historial de Citas
            </h3>
            
            <div class="space-y-3">
                @forelse($pacienteEspecial->citas ?? [] as $cita)
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-lg bg-medical-100 flex items-center justify-center">
                            <i class="bi bi-calendar-check text-medical-600 text-xl"></i>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-900">{{ $cita->especialidad ?? 'Consulta General' }}</p>
                            <p class="text-sm text-gray-600">Dr. {{ $cita->medico->nombre ?? 'N/A' }}</p>
                            <p class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($cita->fecha)->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                    <span class="badge badge-{{ $cita->status == 'completada' ? 'success' : 'warning' }}">
                        {{ ucfirst($cita->status) }}
                    </span>
                </div>
                @empty
                <div class="text-center py-8">
                    <i class="bi bi-inbox text-3xl text-gray-300 mb-2"></i>
                    <p class="text-gray-500">No hay citas registradas</p>
                </div>
                @endforelse
            </div>
            
            @if(count($pacienteEspecial->citas ?? []) > 0)
            <div class="mt-4 pt-4 border-t border-gray-200">
                <a href="{{ route('citas.index', ['paciente_id' => $pacienteEspecial->id]) }}" class="btn btn-sm btn-outline">
                    <i class="bi bi-list mr-2"></i>
                    Ver Todas las Citas
                </a>
            </div>
            @endif
        </div>

    </div>

    <!-- Panel Lateral -->
    <div class="space-y-6">
        
        <!-- Avatar y Acciones Rápidas -->
        <div class="card p-6">
            <div class="flex flex-col items-center text-center mb-6">
                <div class="w-24 h-24 rounded-full bg-gradient-to-br from-warning-500 to-warning-600 flex items-center justify-center text-white text-3xl font-bold mb-3">
                    {{ strtoupper(substr($pacienteEspecial->primer_nombre, 0, 1) . substr($pacienteEspecial->primer_apellido, 0, 1)) }}
                </div>
                <h3 class="font-bold text-gray-900 text-lg">
                    {{ $pacienteEspecial->primer_nombre }} {{ $pacienteEspecial->primer_apellido }}
                </h3>
                <p class="text-sm text-gray-500">Paciente Especial</p>
            </div>
            
            <div class="space-y-2">
                <a href="{{ route('citas.create', ['paciente_id' => $pacienteEspecial->id]) }}" class="btn btn-primary w-full">
                    <i class="bi bi-calendar-plus mr-2"></i>
                    Nueva Cita
                </a>
                <a href="{{ route('historia-clinica.base.index', $pacienteEspecial->id) }}" class="btn btn-outline w-full">
                    <i class="bi bi-file-medical mr-2"></i>
                    Historia Clínica
                </a>
                <a href="{{ route('pacientes-especiales.edit', $pacienteEspecial->id) }}" class="btn btn-outline w-full">
                    <i class="bi bi-pencil mr-2"></i>
                    Editar Datos
                </a>
            </div>
        </div>

        <!-- Estadísticas Rápidas -->
        <div class="card p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4">
                <i class="bi bi-graph-up text-info-600 mr-2"></i>
                Estadísticas
            </h3>
            
            <div class="space-y-3">
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Total de Citas</span>
                    <span class="font-bold text-gray-900">{{ count($pacienteEspecial->citas ?? []) }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Citas Completadas</span>
                    <span class="font-bold text-success-600">0</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Citas Pendientes</span>
                    <span class="font-bold text-warning-600">0</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Última Visita</span>
                    <span class="font-bold text-gray-900">N/A</span>
                </div>
            </div>
        </div>

        <!-- Información del Registro -->
        <div class="card p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4">
                <i class="bi bi-info-circle text-info-600 mr-2"></i>
                Información del Registro
            </h3>
            
            <div class="space-y-3 text-sm">
                <div>
                    <p class="text-gray-500 mb-1">Fecha de Registro</p>
                    <p class="font-semibold text-gray-900">
                        {{ $pacienteEspecial->created_at?->format('d/m/Y H:i') ?? 'N/A' }}
                    </p>
                </div>
                <div>
                    <p class="text-gray-500 mb-1">Última Modificación</p>
                    <p class="font-semibold text-gray-900">
                        {{ $pacienteEspecial->updated_at?->format('d/m/Y H:i') ?? 'N/A' }}
                    </p>
                </div>
                <div>
                    <p class="text-gray-500 mb-1">Registrado Por</p>
                    <p class="font-semibold text-gray-900">
                        {{ $pacienteEspecial->registrado_por ?? 'Sistema' }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Documentos -->
        <div class="card p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4">
                <i class="bi bi-file-earmark-text text-warning-600 mr-2"></i>
                Documentos
            </h3>
            
            <div class="space-y-2">
                @if($pacienteEspecial->documento_identidad)
                <a href="{{ asset('storage/' . $pacienteEspecial->documento_identidad) }}" target="_blank" class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                    <i class="bi bi-file-pdf text-danger-600 text-xl"></i>
                    <div class="flex-1">
                        <p class="text-sm font-semibold text-gray-900">Documento de Identidad</p>
                        <p class="text-xs text-gray-500">PDF</p>
                    </div>
                    <i class="bi bi-download text-gray-400"></i>
                </a>
                @endif
                
                @if($pacienteEspecial->documento_representacion)
                <a href="{{ asset('storage/' . $pacienteEspecial->documento_representacion) }}" target="_blank" class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                    <i class="bi bi-file-pdf text-danger-600 text-xl"></i>
                    <div class="flex-1">
                        <p class="text-sm font-semibold text-gray-900">Doc. Representación</p>
                        <p class="text-xs text-gray-500">PDF</p>
                    </div>
                    <i class="bi bi-download text-gray-400"></i>
                </a>
                @endif
                
                @if(!$pacienteEspecial->documento_identidad && !$pacienteEspecial->documento_representacion)
                <div class="text-center py-4">
                    <i class="bi bi-inbox text-2xl text-gray-300 mb-1"></i>
                    <p class="text-xs text-gray-500">Sin documentos adjuntos</p>
                </div>
                @endif
            </div>
        </div>

    </div>
    
</div>
@endsection
