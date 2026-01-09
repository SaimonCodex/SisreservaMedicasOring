@extends('layouts.admin')

@section('title', 'Detalle de Notificación')

@section('content')
<div class="mb-6">
    <div class="flex items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <a href="{{ route('notificaciones.index') }}" class="btn btn-ghost">
                <i class="bi bi-arrow-left"></i>
            </a>
            <div>
                <h2 class="text-3xl font-display font-bold text-gray-900">{{ $notificacion->titulo }}</h2>
                <p class="text-gray-500 mt-1">Detalle de la notificación</p>
            </div>
        </div>
        <div class="flex gap-2">
            @if($notificacion->estado_envio == 'Fallido')
            <form action="{{ route('notificaciones.reenviar', $notificacion->id) }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="btn btn-warning">
                    <i class="bi bi-arrow-clockwise mr-2"></i>
                    Reenviar
                </button>
            </form>
            @endif
            <a href="{{ route('notificaciones.edit', $notificacion->id) }}" class="btn btn-primary">
                <i class="bi bi-pencil mr-2"></i>
                Editar
            </a>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Contenido Principal -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Mensaje -->
        <div class="card p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2 border-b pb-3">
                <i class="bi bi-envelope-fill text-info-600"></i>
                Mensaje
            </h3>
            
            <div class="prose max-w-none">
                <p class="text-gray-700 whitespace-pre-wrap">{{ $notificacion->mensaje }}</p>
            </div>
        </div>

        <!-- Detalles del Envío -->
        <div class="card p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2 border-b pb-3">
                <i class="bi bi-info-circle text-warning-600"></i>
                Detalles del Envío
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <p class="text-sm text-gray-500 mb-1">Receptor</p>
                    <p class="font-semibold text-gray-900">{{ $notificacion->receptor_rol }}</p>
                    @if($notificacion->receptor_id)
                    <p class="text-sm text-gray-500">ID: {{ $notificacion->receptor_id }}</p>
                    @endif
                </div>

                <div>
                    <p class="text-sm text-gray-500 mb-1">Vía de Envío</p>
                    <span class="badge badge-info badge-lg">{{ $notificacion->via }}</span>
                </div>

                <div>
                    <p class="text-sm text-gray-500 mb-1">Estado</p>
                    <span class="badge badge-{{ $notificacion->estado_envio == 'Enviado' ? 'success' : ($notificacion->estado_envio == 'Fallido' ? 'danger' : ($notificacion->estado_envio == 'Leido' ? 'info' : 'warning')) }} badge-lg">
                        {{ $notificacion->estado_envio }}
                    </span>
                </div>

                <div>
                    <p class="text-sm text-gray-500 mb-1">Tipo</p>
                    <span class="badge badge-medical badge-lg">{{ str_replace('_', ' ', $notificacion->tipo) }}</span>
                </div>

                <div class="md:col-span-2">
                    <p class="text-sm text-gray-500 mb-1">Fecha de Creación</p>
                    <p class="font-semibold text-gray-900">
                        {{ $notificacion->created_at->format('d/m/Y H:i:s') }}
                        <span class="text-sm text-gray-500">({{ $notificacion->created_at->diffForHumans() }})</span>
                    </p>
                </div>

                @if($notificacion->error_detalle)
                <div class="md:col-span-2">
                    <p class="text-sm text-gray-500 mb-1">Error</p>
                    <div class="p-3 bg-danger-50 border border-danger-200 rounded-lg">
                        <p class="text-sm text-danger-700">{{ $notificacion->error_detalle }}</p>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Panel Lateral -->
    <div class="space-y-6">
        <!-- Estado de la Notificación -->
        <div class="card p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                <i class="bi bi-check-circle text-success-600"></i>
                Estado
            </h3>
            
            <div class="space-y-4">
                <div class="flex items-center justify-between p-3 rounded-lg {{ $notificacion->estado_envio == 'Pendiente' ? 'bg-warning-50' : 'bg-gray-50' }}">
                    <div class="flex items-center gap-3">
                        <i class="bi bi-clock {{ $notificacion->estado_envio == 'Pendiente' ? 'text-warning-600' : 'text-gray-400' }} text-xl"></i>
                        <span class="text-sm font-medium text-gray-900">Pendiente</span>
                    </div>
                    @if($notificacion->estado_envio == 'Pendiente')
                    <i class="bi bi-check-lg text-warning-600"></i>
                    @endif
                </div>

                <div class="flex items-center justify-between p-3 rounded-lg {{ $notificacion->estado_envio == 'Enviado' ? 'bg-success-50' : 'bg-gray-50' }}">
                    <div class="flex items-center gap-3">
                        <i class="bi bi-send {{ $notificacion->estado_envio == 'Enviado' ? 'text-success-600' : 'text-gray-400' }} text-xl"></i>
                        <span class="text-sm font-medium text-gray-900">Enviado</span>
                    </div>
                    @if($notificacion->estado_envio == 'Enviado')
                    <i class="bi bi-check-lg text-success-600"></i>
                    @endif
                </div>

                <div class="flex items-center justify-between p-3 rounded-lg {{ $notificacion->estado_envio == 'Leido' ? 'bg-info-50' : 'bg-gray-50' }}">
                    <div class="flex items-center gap-3">
                        <i class="bi bi-eye {{ $notificacion->estado_envio == 'Leido' ? 'text-info-600' : 'text-gray-400' }} text-xl"></i>
                        <span class="text-sm font-medium text-gray-900">Leído</span>
                    </div>
                    @if($notificacion->estado_envio == 'Leido')
                    <i class="bi bi-check-lg text-info-600"></i>
                    @endif
                </div>

                <div class="flex items-center justify-between p-3 rounded-lg {{ $notificacion->estado_envio == 'Fallido' ? 'bg-danger-50' : 'bg-gray-50' }}">
                    <div class="flex items-center gap-3">
                        <i class="bi bi-x-circle {{ $notificacion->estado_envio == 'Fallido' ? 'text-danger-600' : 'text-gray-400' }} text-xl"></i>
                        <span class="text-sm font-medium text-gray-900">Fallido</span>
                    </div>
                    @if($notificacion->estado_envio == 'Fallido')
                    <i class="bi bi-check-lg text-danger-600"></i>
                    @endif
                </div>
            </div>
        </div>

        <!-- Acciones -->
        <div class="card p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                <i class="bi bi-gear text-gray-600"></i>
                Acciones
            </h3>
            
            <div class="space-y-3">
                @if($notificacion->estado_envio == 'Fallido')
                <form action="{{ route('notificaciones.reenviar', $notificacion->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-warning w-full">
                        <i class="bi bi-arrow-clockwise mr-2"></i>
                        Reenviar Notificación
                    </button>
                </form>
                @endif
                
                <a href="{{ route('notificaciones.edit', $notificacion->id) }}" class="btn btn-primary w-full">
                    <i class="bi bi-pencil mr-2"></i>
                    Editar
                </a>
                
                <form action="{{ route('notificaciones.destroy', $notificacion->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger w-full" onclick="return confirm('¿Eliminar notificación?')">
                        <i class="bi bi-trash mr-2"></i>
                        Eliminar
                    </button>
                </form>
            </div>
        </div>

        <!-- Información del Sistema -->
        <div class="card p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                <i class="bi bi-database text-gray-600"></i>
                Sistema
            </h3>
            
            <div class="space-y-3 text-sm">
                <div>
                    <p class="text-gray-500">ID</p>
                    <p class="font-mono font-semibold text-gray-900">{{ $notificacion->id }}</p>
                </div>
                <div>
                    <p class="text-gray-500">Creada</p>
                    <p class="font-semibold text-gray-900">{{ $notificacion->created_at->format('d/m/Y H:i') }}</p>
                </div>
                <div>
                    <p class="text-gray-500">Actualizada</p>
                    <p class="font-semibold text-gray-900">{{ $notificacion->updated_at->format('d/m/Y H:i') }}</p>
                </div>
                <div>
                    <p class="text-gray-500">Estado</p>
                    <span class="badge {{ $notificacion->status ? 'badge-success' : 'badge-danger' }}">
                        {{ $notificacion->status ? 'Activa' : 'Inactiva' }}
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
