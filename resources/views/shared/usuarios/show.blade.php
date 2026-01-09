@extends('layouts.admin')

@section('title', 'Detalle de Usuario')

@section('content')
<div class="mb-6">
    <div class="flex items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <a href="{{ route('usuarios.index') }}" class="btn btn-ghost">
                <i class="bi bi-arrow-left"></i>
            </a>
            <div>
                <h2 class="text-3xl font-display font-bold text-gray-900">
                    {{ $usuario->primer_nombre }} {{ $usuario->primer_apellido }}
                </h2>
                <p class="text-gray-500 mt-1">Información del usuario</p>
            </div>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('usuarios.edit', $usuario->id) }}" class="btn btn-warning">
                <i class="bi bi-pencil mr-2"></i>
                Editar
            </a>
            <form action="{{ route('usuarios.destroy', $usuario->id) }}" method="POST" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger" onclick="return confirm('¿Eliminar usuario?')">
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
                        {{ $usuario->primer_nombre }} {{ $usuario->segundo_nombre }} 
                        {{ $usuario->primer_apellido }} {{ $usuario->segundo_apellido }}
                    </p>
                </div>

                <div>
                    <p class="text-sm text-gray-500 mb-1">Email</p>
                    <p class="font-semibold text-gray-900">{{ $usuario->email }}</p>
                </div>

                <div>
                    <p class="text-sm text-gray-500 mb-1">Teléfono</p>
                    <p class="font-semibold text-gray-900">{{ $usuario->telefono ?? 'No registrado' }}</p>
                </div>

                <div>
                    <p class="text-sm text-gray-500 mb-1">Fecha de Registro</p>
                    <p class="font-semibold text-gray-900">
                        {{ $usuario->created_at->format('d/m/Y') }}
                        <span class="text-sm text-gray-500">({{ $usuario->created_at->diffForHumans() }})</span>
                    </p>
                </div>
            </div>
        </div>

        <!-- Información del Perfil -->
        <div class="card p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2 border-b pb-3">
                <i class="bi bi-briefcase text-medical-600"></i>
                Información del Perfil
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @if($usuario->rol_id == 1)
                <!-- Administrador -->
                <div class="md:col-span-2">
                    <p class="text-sm text-gray-500 mb-1">Cargo</p>
                    <p class="font-semibold text-gray-900">{{ $usuario->administrador->cargo ?? 'N/A' }}</p>
                </div>
                
                @elseif($usuario->rol_id == 2)
                <!-- Médico -->
                <div>
                    <p class="text-sm text-gray-500 mb-1">Especialidad</p>
                    <p class="font-semibold text-gray-900">{{ $usuario->medico->especialidad->nombre_especialidad ?? 'N/A' }}</p>
                </div>

                <div>
                    <p class="text-sm text-gray-500 mb-1">MPPS</p>
                    <p class="font-mono font-semibold text-gray-900">{{ $usuario->medico->numero_mpps ?? 'N/A' }}</p>
                </div>

                <div>
                    <p class="text-sm text-gray-500 mb-1">CMP</p>
                    <p class="font-mono font-semibold text-gray-900">{{ $usuario->medico->numero_cmp ?? 'N/A' }}</p>
                </div>

                <div>
                    <p class="text-sm text-gray-500 mb-1">Consultorio</p>
                    <p class="font-semibold text-gray-900">{{ $usuario->medico->consultorio->nombre ?? 'No asignado' }}</p>
                </div>

                @elseif($usuario->rol_id == 3)
                <!-- Paciente -->
                <div>
                    <p class="text-sm text-gray-500 mb-1">Historia Clínica</p>
                    <p class="font-mono font-semibold text-medical-600">HC-{{ str_pad($usuario->paciente->id ?? 0, 6, '0', STR_PAD_LEFT) }}</p>
                </div>

                <div>
                    <p class="text-sm text-gray-500 mb-1">Tipo de Paciente</p>
                    <p class="font-semibold text-gray-900">{{ $usuario->paciente->tipo ?? 'Regular' }}</p>
                </div>

                <div>
                    <p class="text-sm text-gray-500 mb-1">Género</p>
                    <p class="font-semibold text-gray-900">{{ $usuario->paciente->genero ?? 'N/A' }}</p>
                </div>

                <div>
                    <p class="text-sm text-gray-500 mb-1">Edad</p>
                    <p class="font-semibold text-gray-900">
                        @if($usuario->paciente && $usuario->paciente->fecha_nac)
                        {{ \Carbon\Carbon::parse($usuario->paciente->fecha_nac)->age }} años
                        @else
                        N/A
                        @endif
                    </p>
                </div>
                @endif
            </div>
        </div>

        <!-- Actividad Reciente -->
        <div class="card p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2 border-b pb-3">
                <i class="bi bi-clock-history text-success-600"></i>
                Actividad Reciente
            </h3>
            
            <div class="space-y-3">
                <div class="flex items-center gap-3 p-3 rounded-lg bg-gray-50">
                    <i class="bi bi-calendar-event text-medical-600 text-xl"></i>
                    <div class="flex-1">
                        <p class="font-semibold text-gray-900">Última Actividad</p>
                        <p class="text-sm text-gray-500">{{ $usuario->updated_at->diffForHumans() }}</p>
                    </div>
                </div>

                @if($usuario->email_verified_at)
                <div class="flex items-center gap-3 p-3 rounded-lg bg-success-50">
                    <i class="bi bi-check-circle text-success-600 text-xl"></i>
                    <div class="flex-1">
                        <p class="font-semibold text-gray-900">Email Verificado</p>
                        <p class="text-sm text-gray-500">{{ \Carbon\Carbon::parse($usuario->email_verified_at)->format('d/m/Y H:i') }}</p>
                    </div>
                </div>
                @else
                <div class="flex items-center gap-3 p-3 rounded-lg bg-warning-50">
                    <i class="bi bi-exclamation-circle text-warning-600 text-xl"></i>
                    <div class="flex-1">
                        <p class="font-semibold text-gray-900">Email Sin Verificar</p>
                        <p class="text-sm text-gray-500">El usuario no ha verificado su correo electrónico</p>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Panel Lateral -->
    <div class="space-y-6">
        <!-- Rol y Estado -->
        <div class="card p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                <i class="bi bi-shield-check text-info-600"></i>
                Rol y Estado
            </h3>
            
            <div class="space-y-4">
                <div>
                    <p class="text-sm text-gray-500 mb-2">Rol</p>
                    <span class="badge badge-{{ $usuario->rol_id == 1 ? 'danger' : ($usuario->rol_id == 2 ? 'info' : 'success') }} badge-lg">
                        {{ $usuario->rol->nombre_rol }}
                    </span>
                </div>

                <div>
                    <p class="text-sm text-gray-500 mb-2">Estado</p>
                    <span class="badge {{ $usuario->status ? 'badge-success' : 'badge-danger' }} badge-lg">
                        {{ $usuario->status ? 'Activo' : 'Inactivo' }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Estadísticas Rápidas -->
        @if($usuario->rol_id == 2 && $usuario->medico)
        <!-- Estadísticas de Médico -->
        <div class="card p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                <i class="bi bi-bar-chart text-medical-600"></i>
                Estadísticas
            </h3>
            
            <div class="space-y-3">
                <div class="flex items-center justify-between p-3 rounded-lg bg-gray-50">
                    <span class="text-sm text-gray-600">Citas Totales</span>
                    <span class="font-bold text-medical-600">{{ $usuario->medico->citas->count() }}</span>
                </div>
                <div class="flex items-center justify-between p-3 rounded-lg bg-gray-50">
                    <span class="text-sm text-gray-600">Este Mes</span>
                    <span class="font-bold text-info-600">
                        {{ $usuario->medico->citas->where('fecha_cita', '>=', now()->startOfMonth())->count() }}
                    </span>
                </div>
            </div>
        </div>
        
        @elseif($usuario->rol_id == 3 && $usuario->paciente)
        <!-- Estadísticas de Paciente -->
        <div class="card p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                <i class="bi bi-bar-chart text-medical-600"></i>
                Estadísticas
            </h3>
            
            <div class="space-y-3">
                <div class="flex items-center justify-between p-3 rounded-lg bg-gray-50">
                    <span class="text-sm text-gray-600">Citas Totales</span>
                    <span class="font-bold text-medical-600">{{ $usuario->paciente->citas->count() }}</span>
                </div>
                <div class="flex items-center justify-between p-3 rounded-lg bg-gray-50">
                    <span class="text-sm text-gray-600">Facturas</span>
                    <span class="font-bold text-info-600">
                        {{ $usuario->paciente->citas->flatMap->facturas->count() }}
                    </span>
                </div>
            </div>
        </div>
        @endif

        <!-- Información del Sistema -->
        <div class="card p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                <i class="bi bi-info-circle text-gray-600"></i>
                Sistema
            </h3>
            
            <div class="space-y-3 text-sm">
                <div>
                    <p class="text-gray-500">ID</p>
                    <p class="font-mono font-semibold text-gray-900">{{ $usuario->id }}</p>
                </div>
                <div>
                    <p class="text-gray-500">Creado</p>
                    <p class="font-semibold text-gray-900">{{ $usuario->created_at->format('d/m/Y H:i') }}</p>
                </div>
                <div>
                    <p class="text-gray-500">Actualizado</p>
                    <p class="font-semibold text-gray-900">{{ $usuario->updated_at->format('d/m/Y H:i') }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
