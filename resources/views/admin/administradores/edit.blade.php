@extends('layouts.admin')

@section('title', 'Editar Administrador')

@section('content')
<div class="mb-6">
    <a href="{{ route('administradores.index') }}" class="text-medical-600 hover:text-medical-700 inline-flex items-center text-sm font-medium mb-3">
        <i class="bi bi-arrow-left mr-1"></i> Volver a la lista
    </a>
    <h2 class="text-2xl font-display font-bold text-gray-900">Editar Administrador</h2>
    <p class="text-gray-500 mt-1">Actualiza los datos de {{ $administrador->primer_nombre }} {{ $administrador->primer_apellido }}</p>
</div>

<form method="POST" action="{{ route('administradores.update', $administrador->id) }}">
    @csrf
    @method('PUT')
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Columna Principal: Datos Personales -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Información Personal -->
            <div class="card p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <i class="bi bi-person-circle text-medical-600"></i>
                    Información Personal
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="form-group">
                        <label for="primer_nombre" class="form-label form-label-required">Primer Nombre</label>
                        <input type="text" name="primer_nombre" id="primer_nombre" 
                               class="input @error('primer_nombre') input-error @enderror" 
                               value="{{ old('primer_nombre', $administrador->primer_nombre) }}" required>
                        @error('primer_nombre')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="segundo_nombre" class="form-label">Segundo Nombre</label>
                        <input type="text" name="segundo_nombre" id="segundo_nombre" 
                               class="input @error('segundo_nombre') input-error @enderror" 
                               value="{{ old('segundo_nombre', $administrador->segundo_nombre) }}">
                        @error('segundo_nombre')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="primer_apellido" class="form-label form-label-required">Primer Apellido</label>
                        <input type="text" name="primer_apellido" id="primer_apellido" 
                               class="input @error('primer_apellido') input-error @enderror" 
                               value="{{ old('primer_apellido', $administrador->primer_apellido) }}" required>
                        @error('primer_apellido')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="segundo_apellido" class="form-label">Segundo Apellido</label>
                        <input type="text" name="segundo_apellido" id="segundo_apellido" 
                               class="input @error('segundo_apellido') input-error @enderror" 
                               value="{{ old('segundo_apellido', $administrador->segundo_apellido) }}">
                        @error('segundo_apellido')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="tipo_documento" class="form-label form-label-required">Tipo Documento</label>
                        <select name="tipo_documento" id="tipo_documento" 
                                class="form-select @error('tipo_documento') input-error @enderror" required>
                            <option value="">Seleccionar...</option>
                            <option value="V" {{ old('tipo_documento', $administrador->tipo_documento) == 'V' ? 'selected' : '' }}>V - Venezolano</option>
                            <option value="E" {{ old('tipo_documento', $administrador->tipo_documento) == 'E' ? 'selected' : '' }}>E - Extranjero</option>
                            <option value="P" {{ old('tipo_documento', $administrador->tipo_documento) == 'P' ? 'selected' : '' }}>P - Pasaporte</option>
                            <option value="J" {{ old('tipo_documento', $administrador->tipo_documento) == 'J' ? 'selected' : '' }}>J - Jurídico</option>
                        </select>
                        @error('tipo_documento')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="numero_documento" class="form-label form-label-required">Número Documento</label>
                        <input type="text" name="numero_documento" id="numero_documento" 
                               class="input @error('numero_documento') input-error @enderror" 
                               value="{{ old('numero_documento', $administrador->numero_documento) }}" required>
                        @error('numero_documento')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="fecha_nac" class="form-label form-label-required">Fecha de Nacimiento</label>
                        <input type="date" name="fecha_nac" id="fecha_nac" 
                               class="input @error('fecha_nac') input-error @enderror" 
                               value="{{ old('fecha_nac', $administrador->fecha_nac) }}" required>
                        @error('fecha_nac')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="genero" class="form-label form-label-required">Género</label>
                        <select name="genero" id="genero" 
                                class="form-select @error('genero') input-error @enderror" required>
                            <option value="">Seleccionar...</option>
                            <option value="Masculino" {{ old('genero', $administrador->genero) == 'Masculino' ? 'selected' : '' }}>Masculino</option>
                            <option value="Femenino" {{ old('genero', $administrador->genero) == 'Femenino' ? 'selected' : '' }}>Femenino</option>
                        </select>
                        @error('genero')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Datos de Contacto -->
            <div class="card p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <i class="bi bi-telephone text-medical-600"></i>
                    Datos de Contacto
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="form-group">
                        <label for="prefijo_tlf" class="form-label form-label-required">Prefijo Telefónico</label>
                        <select name="prefijo_tlf" id="prefijo_tlf" 
                                class="form-select @error('prefijo_tlf') input-error @enderror" required>
                            <option value="+58" {{ old('prefijo_tlf', $administrador->prefijo_tlf) == '+58' ? 'selected' : '' }}>+58 - Venezuela</option>
                            <option value="+57" {{ old('prefijo_tlf', $administrador->prefijo_tlf) == '+57' ? 'selected' : '' }}>+57 - Colombia</option>
                            <option value="+1" {{ old('prefijo_tlf', $administrador->prefijo_tlf) == '+1' ? 'selected' : '' }}>+1 - USA</option>
                        </select>
                        @error('prefijo_tlf')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="numero_tlf" class="form-label form-label-required">Número de Teléfono</label>
                        <input type="text" name="numero_tlf" id="numero_tlf" 
                               class="input @error('numero_tlf') input-error @enderror" 
                               value="{{ old('numero_tlf', $administrador->numero_tlf) }}" required>
                        @error('numero_tlf')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group md:col-span-2">
                        <label for="correo" class="form-label form-label-required">Correo Electrónico</label>
                        <input type="email" name="correo" id="correo" 
                               class="input @error('correo') input-error @enderror" 
                               value="{{ old('correo', $administrador->usuario->correo) }}" required>
                        <p class="form-help">Cambiar el correo afectará el acceso al sistema</p>
                        @error('correo')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Cambiar Contraseña (Opcional) -->
            <div class="card p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <i class="bi bi-shield-lock text-medical-600"></i>
                    Cambiar Contraseña <span class="text-sm text-gray-400 font-normal">(Opcional)</span>
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="form-group">
                        <label for="password" class="form-label">Nueva Contraseña</label>
                        <input type="password" name="password" id="password" 
                               class="input @error('password') input-error @enderror" 
                               placeholder="Dejar en blanco para no cambiar">
                        <p class="form-help">Mínimo 8 caracteres</p>
                        @error('password')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password_confirmation" class="form-label">Confirmar Contraseña</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" 
                               class="input" placeholder="Repetir nueva contraseña">
                    </div>
                </div>
            </div>
        </div>

        <!-- Columna Lateral: Estado y Acciones -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Estado -->
            <div class="card p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Estado</h3>
                <div class="form-group mb-0">
                    <label class="flex items-center cursor-pointer">
                        <input type="checkbox" name="status" value="1" 
                               class="form-checkbox" 
                               {{ old('status', $administrador->status) ? 'checked' : '' }}>
                        <span class="ml-2 text-gray-700">Administrador Activo</span>
                    </label>
                    <p class="form-help mt-2">Si está inactivo, no podrá acceder al sistema</p>
                </div>
            </div>

            <!-- Acciones -->
            <div class="card p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Opciones</h3>
                <div class="space-y-3">
                    <button type="submit" class="btn btn-primary w-full">
                        <i class="bi bi-check-lg mr-2"></i>
                        Guardar Cambios
                    </button>
                    <a href="{{ route('administradores.show', $administrador->id) }}" class="btn btn-outline w-full">
                        <i class="bi bi-eye mr-2"></i>
                        Ver Detalles
                    </a>
                    <a href="{{ route('administradores.index') }}" class="btn btn-outline w-full">
                        <i class="bi bi-x-lg mr-2"></i>
                        Cancelar
                    </a>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection
