@extends('layouts.admin')

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
            <a href="{{ route('citas.create') }}?paciente={{ $paciente->id }}" class="btn btn-outline">
                <i class="bi bi-calendar-plus mr-2"></i>
                Nueva Cita
            </a>
            <a href="{{ route('pacientes.edit', $paciente->id) }}" class="btn btn-primary">
                <i class="bi bi-pencil mr-2"></i>
                Editar
            </a>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Columna Principal -->
    <div class="lg:col-span-2 space-y-6">
        
        <!-- Encabezado del Paciente -->
        <div class="card p-0 overflow-hidden">
            <div class="bg-gradient-to-r from-medical-600 to-medical-500 p-6">
                <div class="flex items-center gap-6">
                    @if($paciente->foto_perfil)
                        <img src="{{ asset('storage/' . $paciente->foto_perfil) }}" alt="Foto" class="w-24 h-24 rounded-full object-cover border-4 border-white/30">
                    @else
                        <div class="w-24 h-24 rounded-full bg-white/20 backdrop-blur-sm flex items-center justify-center text-white text-4xl font-bold border-4 border-white/30">
                            {{ strtoupper(substr($paciente->primer_nombre, 0, 1) . substr($paciente->primer_apellido, 0, 1)) }}
                        </div>
                    @endif
                    <div class="text-white flex-1">
                        <h3 class="text-2xl font-bold mb-1">{{ $paciente->primer_nombre }} {{ $paciente->primer_apellido }}</h3>
                        <p class="text-white/90 mb-2">
                            @if($paciente->fecha_nac)
                                {{ \Carbon\Carbon::parse($paciente->fecha_nac)->age }} años • 
                            @endif
                            {{ $paciente->genero == 'M' ? 'Masculino' : 'Femenino' }} • {{ $paciente->tipo_documento }}-{{ $paciente->numero_documento }}
                        </p>
                        <div class="flex gap-2">
                            <span class="badge bg-white/20 text-white border border-white/30">{{ $paciente->status ? 'Activo' : 'Inactivo' }}</span>
                        </div>
                    </div>
                    <div class="text-right hidden md:block">
                        <p class="text-sm text-white/70">Historia Clínica</p>
                        <p class="text-xl font-bold text-white">HC-{{ \Carbon\Carbon::parse($paciente->created_at)->format('Y') }}-{{ str_pad($paciente->id, 3, '0', STR_PAD_LEFT) }}</p>
                    </div>
                </div>
            </div>
            
            <div class="p-6">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div class="text-center p-4 bg-gray-50 rounded-xl">
                        <p class="text-3xl font-bold text-medical-600 mb-1">{{ $paciente->citas->count() }}</p>
                        <p class="text-sm text-gray-500">Consultas</p>
                    </div>
                    <div class="text-center p-4 bg-gray-50 rounded-xl">
                        <p class="text-3xl font-bold text-success-600 mb-1">{{ $paciente->citas->where('estado_cita', 'Completada')->count() }}</p>
                        <p class="text-sm text-gray-500">Completadas</p>
                    </div>
                    <div class="text-center p-4 bg-gray-50 rounded-xl">
                        <p class="text-3xl font-bold text-warning-600 mb-1">{{ $paciente->citas->where('estado_cita', 'Pendiente')->count() }}</p>
                        <p class="text-sm text-gray-500">Pendientes</p>
                    </div>
                    <div class="text-center p-4 bg-gray-50 rounded-xl">
                        <p class="text-3xl font-bold text-info-600 mb-1">{{ $paciente->citas->where('estado_cita', 'Cancelada')->count() }}</p>
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
                        {{ $paciente->fecha_nac ? \Carbon\Carbon::parse($paciente->fecha_nac)->format('d/m/Y') : 'N/A' }} 
                        @if($paciente->fecha_nac) ({{ \Carbon\Carbon::parse($paciente->fecha_nac)->age }} años) @endif
                    </p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 mb-1">Género</p>
                    <p class="font-semibold text-gray-900">{{ $paciente->genero == 'M' ? 'Masculino' : 'Femenino' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 mb-1">Estado Civil</p>
                    <p class="font-semibold text-gray-900">{{ ucfirst($paciente->estado_civil ?? 'No registrado') }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 mb-1">Ocupación</p>
                    <p class="font-semibold text-gray-900">{{ $paciente->ocupacion ?? 'No registrada' }}</p>
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
                    <p class="text-sm text-gray-500 mb-1">Teléfono</p>
                    <p class="font-semibold text-gray-900">{{ $paciente->prefijo_tlf }} {{ $paciente->numero_tlf }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 mb-1">Correo Electrónico</p>
                    <p class="font-semibold text-gray-900">{{ optional($paciente->usuario)->correo ?? 'Sin correo' }}</p>
                </div>
                <div class="md:col-span-2">
                    <p class="text-sm text-gray-500 mb-1">Ubicación</p>
                    <p class="font-semibold text-gray-900">
                        {{ optional($paciente->estado)->estado }}, {{ optional($paciente->municipio)->municipio }}, {{ optional($paciente->ciudad)->ciudad }}
                    </p>
                </div>
                <div class="md:col-span-2">
                    <p class="text-sm text-gray-500 mb-1">Dirección Detallada</p>
                    <p class="font-semibold text-gray-900">{{ $paciente->direccion_detallada }}</p>
                </div>
            </div>
        </div>

        <!-- Próximas Citas -->
        <div class="card p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                    <i class="bi bi-calendar-check text-warning-600"></i>
                    Próximas Citas
                </h3>
                <a href="{{ route('citas.create') }}?paciente={{ $paciente->id }}" class="text-sm text-medical-600 hover:underline">
                    <i class="bi bi-plus-lg mr-1"></i>Agendar
                </a>
            </div>
            <div class="space-y-3">
                @php
                    $proximasCitas = $paciente->citas()->where('fecha_cita', '>=', now()->format('Y-m-d'))->orderBy('fecha_cita', 'asc')->take(3)->get();
                @endphp
                @forelse($proximasCitas as $cita)
                <div class="flex items-center gap-4 p-4 bg-medical-50 border border-medical-100 rounded-xl">
                    <div class="w-16 text-center">
                        <p class="text-2xl font-bold text-medical-700">{{ \Carbon\Carbon::parse($cita->fecha_cita)->format('d') }}</p>
                        <p class="text-xs text-medical-600 upper">{{ \Carbon\Carbon::parse($cita->fecha_cita)->translatedFormat('M') }}</p>
                    </div>
                    <div class="flex-1">
                        <p class="font-semibold text-gray-900">{{ $cita->motivo ?? 'Consulta Médica' }}</p>
                        <p class="text-sm text-gray-600">{{ optional($cita->medico)->primer_nombre }} {{ optional($cita->medico)->primer_apellido }} • {{ \Carbon\Carbon::parse($cita->hora_inicio)->format('h:i A') }}</p>
                    </div>
                    <span class="badge {{ $cita->estado_cita == 'Pendiente' ? 'badge-warning' : 'badge-success' }}">{{ $cita->estado_cita }}</span>
                </div>
                @empty
                <p class="text-sm text-gray-500 text-center py-4 italic">No hay citas programadas próximamente.</p>
                @endforelse
            </div>
        </div>

    </div>

    <!-- Sidebar -->
    <div class="lg:col-span-1 space-y-6">
        <!-- Acciones Rápidas -->
        <div class="card p-6 sticky top-6">
            <h4 class="font-bold text-gray-900 mb-4">Acciones Rápidas</h4>
            <div class="space-y-2">
                <a href="{{ route('pacientes.historia-clinica', $paciente->id) }}" class="btn btn-outline w-full justify-start">
                    <i class="bi bi-file-medical mr-2"></i>
                    Ver Historia Clínica
                </a>
                <a href="{{ route('citas.create') }}?paciente={{ $paciente->id }}" class="btn btn-outline w-full justify-start">
                    <i class="bi bi-calendar-plus mr-2"></i>
                    Agendar Cita
                </a>
            </div>
        </div>

        <!-- Estado -->
        <div class="card p-6">
            <h4 class="font-bold text-gray-900 mb-4">Estado del Registro</h4>
            <div class="space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Cuenta</span>
                    <span class="badge {{ $paciente->status ? 'badge-success' : 'badge-danger' }}">{{ $paciente->status ? 'Activa' : 'Inactiva' }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Última visita</span>
                    <span class="text-sm font-medium text-gray-900">
                        @php $ultima = $paciente->citas()->where('estado_cita', 'Completada')->orderBy('fecha_cita', 'desc')->first(); @endphp
                        {{ $ultima ? \Carbon\Carbon::parse($ultima->fecha_cita)->format('d/m/Y') : 'N/A' }}
                    </span>
                </div>
                <div class="flex items-center justify-between pt-3 border-t border-gray-100">
                    <span class="text-sm text-gray-600">Registro</span>
                    <span class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($paciente->created_at)->format('d/m/Y') }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
