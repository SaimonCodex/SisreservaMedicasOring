@extends('layouts.admin')

@section('title', 'Detalles Administrador')

@section('content')
<div class="mb-6">
    <a href="{{ route('administradores.index') }}" class="text-medical-600 hover:text-medical-700 inline-flex items-center text-sm font-medium mb-3">
        <i class="bi bi-arrow-left mr-1"></i> Volver a la lista
    </a>
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h2 class="text-2xl font-display font-bold text-gray-900">
                {{ $administrador->primer_nombre }} {{ $administrador->primer_apellido }}
            </h2>
            <p class="text-gray-500 mt-1">{{ $administrador->usuario->correo }}</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('administradores.edit', $administrador->id) }}" class="btn btn-warning">
                <i class="bi bi-pencil mr-2"></i>
                Editar
            </a>
            <form action="{{ route('administradores.destroy', $administrador->id) }}" method="POST" 
                  onsubmit="return confirm('¿Estás seguro de eliminar este administrador?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">
                    <i class="bi bi-trash mr-2"></i>
                    Eliminar
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
            <h3 class="text-lg font-bold text-gray-900 mb-5 flex items-center gap-2">
                <i class="bi bi-person-circle text-medical-600"></i>
                Información Personal
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="text-xs text-gray-500 font-medium uppercase tracking-wider">Nombre Completo</label>
                    <p class="text-gray-900 mt-1 font-medium">
                        {{ $administrador->primer_nombre }} {{ $administrador->segundo_nombre }}
                        {{ $administrador->primer_apellido }} {{ $administrador->segundo_apellido }}
                    </p>
                </div>

                <div>
                    <label class="text-xs text-gray-500 font-medium uppercase tracking-wider">Documento</label>
                    <p class="text-gray-900 mt-1 font-medium">
                        {{ $administrador->tipo_documento }}-{{ $administrador->numero_documento }}
                    </p>
                </div>

                <div>
                    <label class="text-xs text-gray-500 font-medium uppercase tracking-wider">Fecha de Nacimiento</label>
                    <p class="text-gray-900 mt-1 font-medium">
                        {{ \Carbon\Carbon::parse($administrador->fecha_nac)->format('d/m/Y') }}
                        <span class="text-gray-500 text-sm ml-2">
                            ({{ \Carbon\Carbon::parse($administrador->fecha_nac)->age }} años)
                        </span>
                    </p>
                </div>

                <div>
                    <label class="text-xs text-gray-500 font-medium uppercase tracking-wider">Género</label>
                    <p class="text-gray-900 mt-1 font-medium">{{ $administrador->genero }}</p>
                </div>
            </div>
        </div>

        <!-- Datos de Contacto -->
        <div class="card p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-5 flex items-center gap-2">
                <i class="bi bi-telephone text-medical-600"></i>
                Datos de Contacto
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="text-xs text-gray-500 font-medium uppercase tracking-wider">Correo Electrónico</label>
                    <p class="text-gray-900 mt-1 font-medium flex items-center gap-2">
                        <i class="bi bi-envelope text-medical-400"></i>
                        {{ $administrador->usuario->correo }}
                    </p>
                </div>

                <div>
                    <label class="text-xs text-gray-500 font-medium uppercase tracking-wider">Teléfono</label>
                    <p class="text-gray-900 mt-1 font-medium flex items-center gap-2">
                        <i class="bi bi-phone text-medical-400"></i>
                        {{ $administrador->prefijo_tlf }} {{ $administrador->numero_tlf }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Información del Sistema -->
        <div class="card p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-5 flex items-center gap-2">
                <i class="bi bi-gear text-medical-600"></i>
                Información del Sistema
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="text-xs text-gray-500 font-medium uppercase tracking-wider">Fecha de Registro</label>
                    <p class="text-gray-900 mt-1 font-medium">
                        {{ $administrador->created_at->format('d/m/Y H:i') }}
                    </p>
                </div>

                <div>
                    <label class="text-xs text-gray-500 font-medium uppercase tracking-wider">Última Actualización</label>
                    <p class="text-gray-900 mt-1 font-medium">
                        {{ $administrador->updated_at->format('d/m/Y H:i') }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="lg:col-span-1 space-y-6">
        <!-- Estado -->
        <div class="card p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Estado</h3>
            <div class="text-center">
                @if($administrador->status)
                    <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-success-100 mb-3">
                        <i class="bi bi-check-circle-fill text-3xl text-success-600"></i>
                    </div>
                    <p class="font-semibold text-success-700">Activo</p>
                    <p class="text-sm text-gray-500 mt-1">El administrador puede acceder al sistema</p>
                @else
                    <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-danger-100 mb-3">
                        <i class="bi bi-x-circle-fill text-3xl text-danger-600"></i>
                    </div>
                    <p class="font-semibold text-danger-700">Inactivo</p>
                    <p class="text-sm text-gray-500 mt-1">El administrador no puede acceder</p>
                @endif
            </div>
        </div>

        <!-- Acciones Rápidas -->
        <div class="card p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Acciones</h3>
            <div class="space-y-2">
                <a href="{{ route('administradores.edit', $administrador->id) }}" 
                   class="btn btn-outline w-full justify-center">
                    <i class="bi bi-pencil mr-2"></i>
                    Editar Datos
                </a>
                <button type="button" class="btn btn-outline w-full justify-center">
                    <i class="bi bi-key mr-2"></i>
                    Restablecer Contraseña
                </button>
            </div>
        </div>

        <!-- Estadísticas (Opcional) -->
        <div class="card p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Actividad</h3>
            <div class="space-y-3">
                <div class="flex justify-between items-center">
                    <span class="text-gray-600 text-sm">Último acceso</span>
                    <span class="font-semibold text-gray-900">Hoy</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-600 text-sm">Total sesiones</span>
                    <span class="font-semibold text-gray-900">487</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
