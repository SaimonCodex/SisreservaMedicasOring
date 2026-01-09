@extends('layouts.admin')

@section('title', 'Detalles del Representante')

@section('content')
<div class="mb-6">
    <div class="flex items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <a href="{{ route('representantes.index') }}" class="btn btn-ghost">
                <i class="bi bi-arrow-left"></i>
            </a>
            <div>
                <h2 class="text-3xl font-display font-bold text-gray-900">
                    {{ $representante->primer_nombre }} {{ $representante->primer_apellido }}
                </h2>
                <p class="text-gray-500 mt-1">Información del representante</p>
            </div>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('representantes.edit', $representante->id) }}" class="btn btn-warning">
                <i class="bi bi-pencil mr-2"></i>
                Editar
            </a>
            <form action="{{ route('representantes.destroy', $representante->id) }}" method="POST" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger" onclick="return confirm('¿Eliminar representante?')">
                    <i class="bi bi-trash"></i>
                </button>
            </form>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Información Principal -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Datos Personales -->
        <div class="card p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2 border-b pb-3">
                <i class="bi bi-person-badge text-info-600"></i>
                Datos Personales
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <p class="text-sm text-gray-500 mb-1">Nombre Completo</p>
                    <p class="font-semibold text-gray-900">
                        {{ $representante->primer_nombre }} {{ $representante->segundo_nombre }} 
                        {{ $representante->primer_apellido }} {{ $representante->segundo_apellido }}
                    </p>
                </div>

                <div>
                    <p class="text-sm text-gray-500 mb-1">Documento</p>
                    <p class="font-semibold text-gray-900 font-mono">
                        {{ $representante->tipo_documento ?? 'N/A' }}-{{ $representante->numero_documento ?? 'N/A' }}
                    </p>
                </div>

                <div>
                    <p class="text-sm text-gray-500 mb-1">Fecha de Nacimiento</p>
                    <p class="font-semibold text-gray-900">
                        {{ $representante->fecha_nac ? \Carbon\Carbon::parse($representante->fecha_nac)->format('d/m/Y') : 'N/A' }}
                        @if($representante->fecha_nac)
                        <span class="text-sm text-gray-500">({{ \Carbon\Carbon::parse($representante->fecha_nac)->age }} años)</span>
                        @endif
                    </p>
                </div>

                <div>
                    <p class="text-sm text-gray-500 mb-1">Género</p>
                    <p class="font-semibold text-gray-900">{{ $representante->genero ?? 'N/A' }}</p>
                </div>

                <div class="md:col-span-2">
                    <p class="text-sm text-gray-500 mb-1">Parentesco</p>
                    <span class="badge badge-info badge-lg">{{ $representante->parentesco ?? 'N/A' }}</span>
                </div>
            </div>
        </div>

        <!-- Ubicación -->
        <div class="card p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2 border-b pb-3">
                <i class="bi bi-geo-alt text-warning-600"></i>
                Ubicación
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <p class="text-sm text-gray-500 mb-1">Estado</p>
                    <p class="font-semibold text-gray-900">{{ $representante->estado->estado ?? 'N/A' }}</p>
                </div>

                <div>
                    <p class="text-sm text-gray-500 mb-1">Ciudad</p>
                    <p class="font-semibold text-gray-900">{{ $representante->ciudad->ciudad ?? 'N/A' }}</p>
                </div>

                <div>
                    <p class="text-sm text-gray-500 mb-1">Municipio</p>
                    <p class="font-semibold text-gray-900">{{ $representante->municipio->municipio ?? 'N/A' }}</p>
                </div>

                <div>
                    <p class="text-sm text-gray-500 mb-1">Parroquia</p>
                    <p class="font-semibold text-gray-900">{{ $representante->parroquia->parroquia ?? 'N/A' }}</p>
                </div>

                <div class="md:col-span-2">
                    <p class="text-sm text-gray-500 mb-1">Dirección Detallada</p>
                    <p class="font-semibold text-gray-900">{{ $representante->direccion_detallada ?? 'No especificada' }}</p>
                </div>
            </div>
        </div>

        <!-- Historial de Citas -->
        <div class="card p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2 border-b pb-3">
                <i class="bi bi-calendar-check text-success-600"></i>
                Historial de Citas de Pacientes
            </h3>
            
            @if($historialCitas->count() > 0)
            <div class="space-y-3">
                @foreach($historialCitas->take(5) as $cita)
                <div class="flex items-center gap-4 p-3 rounded-lg border border-gray-200 hover:bg-gray-50">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-medical-500 to-medical-600 flex items-center justify-center text-white">
                        <i class="bi bi-calendar-event text-xl"></i>
                    </div>
                    <div class="flex-1">
                        <p class="font-semibold text-gray-900">{{ $cita->paciente->primer_nombre }} {{ $cita->paciente->primer_apellido }}</p>
                        <p class="text-sm text-gray-500">{{ $cita->especialidad->nombre_especialidad ?? 'N/A' }} - Dr. {{ $cita->medico->primer_nombre }} {{ $cita->medico->primer_apellido }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-semibold text-gray-900">{{ \Carbon\Carbon::parse($cita->fecha_cita)->format('d/m/Y') }}</p>
                        <span class="badge badge-{{ $cita->estado_cita == 'Completada' ? 'success' : 'warning' }} badge-sm">
                            {{ $cita->estado_cita }}
                        </span>
                    </div>
                </div>
                @endforeach
                
                @if($historialCitas->count() > 5)
                <p class="text-center text-sm text-gray-500">
                    Y {{ $historialCitas->count() - 5 }} cita(s) más...
                </p>
                @endendif
            </div>
            @else
            <div class="text-center py-8">
                <i class="bi bi-calendar-x text-4xl text-gray-300 mb-2"></i>
                <p class="text-gray-500">No hay historial de citas</p>
            </div>
            @endif
        </div>
    </div>

    <!-- Panel Lateral -->
    <div class="space-y-6">
        <!-- Contacto -->
        <div class="card p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                <i class="bi bi-telephone text-success-600"></i>
                Contacto
            </h3>
            
            <div class="space-y-4">
                <div class="flex items-center gap-3 p-3 rounded-lg bg-gray-50">
                    <i class="bi bi-phone text-success-600 text-xl"></i>
                    <div>
                        <p class="text-xs text-gray-500">Teléfono</p>
                        <p class="font-semibold text-gray-900">
                            {{ $representante->prefijo_tlf ?? '' }} {{ $representante->numero_tlf ?? 'No registrado' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pacientes Asignados -->
        <div class="card p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                <i class="bi bi-people text-medical-600"></i>
                Pacientes Asignados
            </h3>
            
            <div class="space-y-2">
                @forelse($representante->pacientesEspeciales as $pacienteEspecial)
                <div class="flex items-center gap-3 p-3 rounded-lg border border-gray-200">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-medical-500 to-medical-600 flex items-center justify-center text-white font-bold">
                        {{ strtoupper(substr($pacienteEspecial->paciente->primer_nombre, 0, 1) . substr($pacienteEspecial->paciente->primer_apellido, 0, 1)) }}
                    </div>
                    <div class="flex-1">
                        <p class="font-semibold text-gray-900 text-sm">
                            {{ $pacienteEspecial->paciente->primer_nombre }} {{ $pacienteEspecial->paciente->primer_apellido }}
                        </p>
                        <p class="text-xs text-gray-500">{{ $pacienteEspecial->tipo_condicion }}</p>
                    </div>
                </div>
                @empty
                <div class="text-center py-6">
                    <i class="bi bi-person-x text-3xl text-gray-300 mb-2"></i>
                    <p class="text-sm text-gray-500">No hay pacientes asignados</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Información del Sistema -->
        <div class="card p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                <i class="bi bi-info-circle text-gray-600"></i>
                Información del Sistema
            </h3>
            
            <div class="space-y-3 text-sm">
                <div>
                    <p class="text-gray-500">Estado</p>
                    <span class="badge {{ $representante->status ? 'badge-success' : 'badge-danger' }}">
                        {{ $representante->status ? 'Activo' : 'Inactivo' }}
                    </span>
                </div>
                <div>
                    <p class="text-gray-500">Registrado</p>
                    <p class="font-semibold text-gray-900">{{ $representante->created_at->format('d/m/Y H:i') }}</p>
                </div>
                <div>
                    <p class="text-gray-500">Última Actualización</p>
                    <p class="font-semibold text-gray-900">{{ $representante->updated_at->format('d/m/Y H:i') }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
