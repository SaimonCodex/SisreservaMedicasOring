@extends('layouts.admin')

@section('title', 'Editar Notificación')

@section('content')
<div class="mb-6">
    <div class="flex items-center gap-3">
        <a href="{{ route('notificaciones.index') }}" class="btn btn-ghost">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div>
            <h2 class="text-3xl font-display font-bold text-gray-900">Editar Notificación</h2>
            <p class="text-gray-500 mt-1">Modifique los detalles de la notificación</p>
        </div>
    </div>
</div>

<form action="{{ route('notificaciones.update', $notificacion->id) }}" method="POST">
    @csrf
    @method('PUT')
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Formulario Principal -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Información Básica -->
            <div class="card p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                    <i class="bi bi-info-circle text-info-600"></i>
                    Información Básica
                </h3>
                
                <div class="space-y-4">
                    <div>
                        <label class="form-label required">Tipo de Notificación</label>
                        <select name="tipo" class="form-select" required>
                            @foreach($tipos as $key => $value)
                            <option value="{{ $key }}" {{ old('tipo', $notificacion->tipo) == $key ? 'selected' : '' }}>
                                {{ $value }}
                            </option>
                            @endforeach
                        </select>
                        @error('tipo')<span class="text-danger-600 text-sm">{{ $message }}</span>@enderror
                    </div>

                    <div>
                        <label class="form-label required">Título</label>
                        <input type="text" name="titulo" class="input" 
                               value="{{ old('titulo', $notificacion->titulo) }}" 
                               placeholder="Título de la notificación" required>
                        @error('titulo')<span class="text-danger-600 text-sm">{{ $message }}</span>@enderror
                    </div>

                    <div>
                        <label class="form-label required">Mensaje</label>
                        <textarea name="mensaje" rows="6" class="input" 
                                  placeholder="Contenido del mensaje" required>{{ old('mensaje', $notificacion->mensaje) }}</textarea>
                        @error('mensaje')<span class="text-danger-600 text-sm">{{ $message }}</span>@enderror
                    </div>
                </div>
            </div>

            <!-- Configuración de Envío -->
            <div class="card p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                    <i class="bi bi-send text-success-600"></i>
                    Configuración de Envío
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="form-label required">Vía de Envío</label>
                        <select name="via" class="form-select" required>
                            @foreach($vias as $via)
                            <option value="{{ $via }}" {{ old('via', $notificacion->via) == $via ? 'selected' : '' }}>
                                {{ $via }}
                            </option>
                            @endforeach
                        </select>
                        @error('via')<span class="text-danger-600 text-sm">{{ $message }}</span>@enderror
                    </div>

                    <div>
                        <label class="form-label required">Estado de Envío</label>
                        <select name="estado_envio" class="form-select" required>
                            <option value="Pendiente" {{ old('estado_envio', $notificacion->estado_envio) == 'Pendiente' ? 'selected' : '' }}>Pendiente</option>
                            <option value="Enviado" {{ old('estado_envio', $notificacion->estado_envio) == 'Enviado' ? 'selected' : '' }}>Enviado</option>
                            <option value="Fallido" {{ old('estado_envio', $notificacion->estado_envio) == 'Fallido' ? 'selected' : '' }}>Fallido</option>
                            <option value="Leido" {{ old('estado_envio', $notificacion->estado_envio) == 'Leido' ? 'selected' : '' }}>Leído</option>
                        </select>
                        @error('estado_envio')<span class="text-danger-600 text-sm">{{ $message }}</span>@enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Panel Lateral -->
        <div class="space-y-6">
            <!-- Información del Receptor -->
            <div class="card p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                    <i class="bi bi-person text-medical-600"></i>
                    Receptor
                </h3>
                
                <div class="space-y-4">
                    <div class="p-4 bg-gray-50 rounded-lg">
                        <p class="text-sm text-gray-500 mb-1">Rol</p>
                        <p class="font-semibold text-gray-900">{{ $notificacion->receptor_rol }}</p>
                    </div>

                    <div class="p-4 bg-gray-50 rounded-lg">
                        <p class="text-sm text-gray-500 mb-1">ID Receptor</p>
                        <p class="font-mono font-semibold text-gray-900">{{ $notificacion->receptor_id ?? 'N/A' }}</p>
                    </div>

                    <div class="p-3 bg-info-50 border border-info-200 rounded-lg">
                        <p class="text-xs text-info-700">
                            <i class="bi bi-info-circle mr-1"></i>
                            El receptor no puede ser modificado después de crear la notificación
                        </p>
                    </div>
                </div>
            </div>

            <!-- Acciones -->
            <div class="card p-6">
                <div class="space-y-3">
                    <button type="submit" class="btn btn-primary w-full">
                        <i class="bi bi-check-lg mr-2"></i>
                        Actualizar Notificación
                    </button>
                    <a href="{{ route('notificaciones.show', $notificacion->id) }}" class="btn btn-outline w-full">
                        <i class="bi bi-x-lg mr-2"></i>
                        Cancelar
                    </a>
                </div>
            </div>

            <!-- Información del Sistema -->
            <div class="card p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                    <i class="bi bi-clock-history text-gray-600"></i>
                    Historial
                </h3>
                
                <div class="space-y-3 text-sm">
                    <div>
                        <p class="text-gray-500">Creada</p>
                        <p class="font-semibold text-gray-900">{{ $notificacion->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500">Última Actualización</p>
                        <p class="font-semibold text-gray-900">{{ $notificacion->updated_at->format('d/m/Y H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection
