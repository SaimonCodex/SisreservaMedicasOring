@extends('layouts.admin')

@section('title', 'Editar Usuario')

@section('content')
<div class="mb-6">
    <div class="flex items-center gap-3">
        <a href="{{ route('usuarios.index') }}" class="btn btn-ghost">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div>
            <h2 class="text-3xl font-display font-bold text-gray-900">Editar Usuario</h2>
            <p class="text-gray-500 mt-1">Actualizar información del usuario</p>
        </div>
    </div>
</div>

<form action="{{ route('usuarios.update', $usuario->id) }}" method="POST">
    @csrf
    @method('PUT')
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Formulario Principal -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Datos Básicos -->
            <div class="card p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                    <i class="bi bi-person-badge text-info-600"></i>
                    Datos Básicos
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="form-label required">Primer Nombre</label>
                        <input type="text" name="primer_nombre" class="input" 
                               value="{{ old('primer_nombre', $usuario->primer_nombre) }}" required>
                        @error('primer_nombre')<span class="text-danger-600 text-sm">{{ $message }}</span>@enderror
                    </div>

                    <div>
                        <label class="form-label">Segundo Nombre</label>
                        <input type="text" name="segundo_nombre" class="input" 
                               value="{{ old('segundo_nombre', $usuario->segundo_nombre) }}">
                    </div>

                    <div>
                        <label class="form-label required">Primer Apellido</label>
                        <input type="text" name="primer_apellido" class="input" 
                               value="{{ old('primer_apellido', $usuario->primer_apellido) }}" required>
                        @error('primer_apellido')<span class="text-danger-600 text-sm">{{ $message }}</span>@enderror
                    </div>

                    <div>
                        <label class="form-label">Segundo Apellido</label>
                        <input type="text" name="segundo_apellido" class="input" 
                               value="{{ old('segundo_apellido', $usuario->segundo_apellido) }}">
                    </div>

                    <div>
                        <label class="form-label required">Email</label>
                        <input type="email" name="email" class="input" 
                               value="{{ old('email', $usuario->email) }}" required>
                        @error('email')<span class="text-danger-600 text-sm">{{ $message }}</span>@enderror
                    </div>

                    <div>
                        <label class="form-label">Teléfono</label>
                        <input type="text" name="telefono" class="input" 
                               placeholder="0414-1234567"
                               value="{{ old('telefono', $usuario->telefono) }}">
                    </div>
                </div>
            </div>

            <!-- Cambiar Contraseña -->
            <div class="card p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                    <i class="bi bi-key text-warning-600"></i>
                    Cambiar Contraseña
                </h3>
                
                <div class="mb-4 p-3 bg-info-50 border border-info-200 rounded-lg">
                    <p class="text-sm text-info-700">
                        <i class="bi bi-info-circle mr-1"></i>
                        Deja estos campos vacíos si no deseas cambiar la contraseña
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="form-label">Nueva Contraseña</label>
                        <input type="password" name="password" class="input" 
                               placeholder="Mínimo 8 caracteres">
                        @error('password')<span class="text-danger-600 text-sm">{{ $message }}</span>@enderror
                    </div>

                    <div>
                        <label class="form-label">Confirmar Contraseña</label>
                        <input type="password" name="password_confirmation" class="input" 
                               placeholder="Repite la contraseña">
                    </div>
                </div>
            </div>
        </div>

        <!-- Panel Lateral -->
        <div class="space-y-6">
            <!-- Rol y Estado -->
            <div class="card p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                    <i class="bi bi-shield-check text-medical-600"></i>
                    Rol y Estado
                </h3>
                
                <div class="space-y-4">
                    <div>
                        <label class="form-label required">Rol</label>
                        <select name="rol_id" class="form-select" required>
                            @foreach($roles as $rol)
                            <option value="{{ $rol->id }}" {{ old('rol_id', $usuario->rol_id) == $rol->id ? 'selected' : '' }}>
                                {{ $rol->nombre_rol }}
                            </option>
                            @endforeach
                        </select>
                        @error('rol_id')<span class="text-danger-600 text-sm">{{ $message }}</span>@enderror
                    </div>

                    <div>
                        <label class="form-label required">Estado</label>
                        <select name="status" class="form-select" required>
                            <option value="1" {{ old('status', $usuario->status) == 1 ? 'selected' : '' }}>Activo</option>
                            <option value="0" {{ old('status', $usuario->status) == 0 ? 'selected' : '' }}>Inactivo</option>
                        </select>
                        @error('status')<span class="text-danger-600 text-sm">{{ $message }}</span>@enderror
                    </div>

                    <div class="pt-3 border-t">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="email_verified" value="1" 
                                   class="form-checkbox"
                                   {{ $usuario->email_verified_at ? 'checked' : '' }}>
                            <span class="text-sm text-gray-700">Email Verificado</span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Acciones -->
            <div class="card p-6">
                <div class="space-y-3">
                    <button type="submit" class="btn btn-primary w-full">
                        <i class="bi bi-check-lg mr-2"></i>
                        Actualizar Usuario
                    </button>
                    <a href="{{ route('usuarios.show', $usuario->id) }}" class="btn btn-outline w-full">
                        <i class="bi bi-x-lg mr-2"></i>
                        Cancelar
                    </a>
                </div>
            </div>

            <!-- Información del Sistema -->
            <div class="card p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                    <i class="bi bi-info-circle text-gray-600"></i>
                    Información
                </h3>
                
                <div class="space-y-3 text-sm">
                    <div>
                        <p class="text-gray-500">ID</p>
                        <p class="font-mono font-semibold text-gray-900">{{ $usuario->id }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500">Registrado</p>
                        <p class="font-semibold text-gray-900">{{ $usuario->created_at->format('d/m/Y') }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500">Última Actualización</p>
                        <p class="font-semibold text-gray-900">{{ $usuario->updated_at->diffForHumans() }}</p>
                    </div>
                </div>
            </div>

            <!-- Advertencia -->
            <div class="card p-6 bg-gradient-to-br from-warning-50 to-warning-100 border border-warning-200">
                <h3 class="text-lg font-semibold text-gray-900 mb-3 flex items-center gap-2">
                    <i class="bi bi-exclamation-triangle text-warning-600"></i>
                    Importante
                </h3>
                <ul class="space-y-2 text-sm text-gray-700">
                    <li class="flex gap-2">
                        <i class="bi bi-dot text-warning-600 text-xl"></i>
                        <span>Cambiar el rol puede afectar los permisos del usuario</span>
                    </li>
                    <li class="flex gap-2">
                        <i class="bi bi-dot text-warning-600 text-xl"></i>
                        <span>Desactivar el usuario bloqueará su acceso</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</form>
@endsection
