@extends('layouts.admin')

@section('title', 'Nueva Notificación')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center gap-4">
        <a href="{{ url('index.php/shared/notificaciones') }}" class="btn btn-outline">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div>
            <h1 class="text-2xl font-display font-bold text-gray-900">Enviar Notificación</h1>
            <p class="text-gray-600 mt-1">Crear y enviar una nueva notificación</p>
        </div>
    </div>

    <form action="{{ url('index.php/shared/notificaciones') }}" method="POST">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Form -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Destinatarios -->
                <div class="card p-6">
                    <h3 class="text-lg font-display font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="bi bi-people text-blue-600"></i>
                        Destinatarios
                    </h3>

                    <div class="space-y-4">
                        <div>
                            <label class="form-label form-label-required">Enviar a</label>
                            <select name="destinatario_tipo" class="form-select" id="destinatarioTipo" required>
                                <option value="">Seleccionar...</option>
                                <option value="todos">Todos los usuarios</option>
                                <option value="rol">Por rol</option>
                                <option value="individual">Usuario específico</option>
                            </select>
                        </div>

                        <div id="rolSelect" class="hidden">
                            <label class="form-label">Rol</label>
                            <select name="rol_id" class="form-select">
                                <option value="">Seleccionar rol...</option>
                                <option value="1">Administradores</option>
                                <option value="2">Médicos</option>
                                <option value="3">Pacientes</option>
                            </select>
                        </div>

                        <div id="usuarioSelect" class="hidden">
                            <label class="form-label">Usuario</label>
                            <select name="usuario_id" class="form-select">
                                <option value="">Seleccionar usuario...</option>
                                @foreach($usuarios ?? [] as $usuario)
                                <option value="{{ $usuario->id }}">{{ $usuario->nombre_completo }} - {{ $usuario->rol->nombre ?? 'N/A' }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Contenido -->
                <div class="card p-6">
                    <h3 class="text-lg font-display font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="bi bi-chat-text text-emerald-600"></i>
                        Contenido de la Notificación
                    </h3>

                    <div class="space-y-4">
                        <div>
                            <label class="form-label form-label-required">Tipo</label>
                            <select name="tipo" class="form-select" required>
                                <option value="">Seleccionar...</option>
                                <option value="info">Información</option>
                                <option value="citas">Citas</option>
                                <option value="pagos">Pagos</option>
                                <option value="sistema">Sistema</option>
                                <option value="urgente">Urgente</option>
                            </select>
                        </div>

                        <div>
                            <label class="form-label form-label-required">Título</label>
                            <input type="text" name="titulo" class="input" placeholder="Título de la notificación" value="{{ old('titulo') }}" required>
                        </div>

                        <div>
                            <label class="form-label form-label-required">Mensaje</label>
                            <textarea name="mensaje" rows="5" class="form-textarea" placeholder="Contenido del mensaje..." required>{{ old('mensaje') }}</textarea>
                        </div>

                        <div>
                            <label class="form-label">URL de Acción (opcional)</label>
                            <input type="url" name="url" class="input" placeholder="https://..." value="{{ old('url') }}">
                            <p class="text-xs text-gray-500 mt-1">URL a la que se redirigirá al hacer clic en la notificación</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Prioridad -->
                <div class="card p-6">
                    <h3 class="text-lg font-display font-bold text-gray-900 mb-4">Prioridad</h3>
                    <div class="space-y-3">
                        <label class="flex items-center gap-3 p-3 border-2 border-gray-200 rounded-xl cursor-pointer hover:bg-gray-50">
                            <input type="radio" name="prioridad" value="baja" class="form-radio" {{ old('prioridad', 'normal') == 'baja' ? 'checked' : '' }}>
                            <div>
                                <p class="font-semibold text-gray-900">Baja</p>
                                <p class="text-sm text-gray-600">Notificación informativa</p>
                            </div>
                        </label>
                        <label class="flex items-center gap-3 p-3 border-2 border-gray-200 rounded-xl cursor-pointer hover:bg-gray-50">
                            <input type="radio" name="prioridad" value="normal" class="form-radio" {{ old('prioridad', 'normal') == 'normal' ? 'checked' : '' }}>
                            <div>
                                <p class="font-semibold text-gray-900">Normal</p>
                                <p class="text-sm text-gray-600">Notificación estándar</p>
                            </div>
                        </label>
                        <label class="flex items-center gap-3 p-3 border-2 border-gray-200 rounded-xl cursor-pointer hover:bg-gray-50">
                            <input type="radio" name="prioridad" value="alta" class="form-radio" {{ old('prioridad') == 'alta' ? 'checked' : '' }}>
                            <div>
                                <p class="font-semibold text-gray-900">Alta</p>
                                <p class="text-sm text-gray-600">Requiere atención</p>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Opciones -->
                <div class="card p-6">
                    <h3 class="text-lg font-display font-bold text-gray-900 mb-4">Opciones</h3>
                    <div class="space-y-3">
                        <label class="flex items-center gap-3">
                            <input type="checkbox" name="enviar_email" value="1" class="form-checkbox" {{ old('enviar_email') ? 'checked' : '' }}>
                            <div>
                                <p class="font-semibold text-gray-900 text-sm">Enviar por Email</p>
                                <p class="text-xs text-gray-600">También enviar notificación por correo electrónico</p>
                            </div>
                        </label>
                        <label class="flex items-center gap-3">
                            <input type="checkbox" name="persistente" value="1" class="form-checkbox" {{ old('persistente') ? 'checked' : '' }}>
                            <div>
                                <p class="font-semibold text-gray-900 text-sm">Persistente</p>
                                <p class="text-xs text-gray-600">No se puede eliminar hasta que sea leída</p>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Actions -->
                <div class="card p-6">
                    <h3 class="text-lg font-display font-bold text-gray-900 mb-4">Acciones</h3>
                    <div class="space-y-3">
                        <button type="submit" class="btn btn-success w-full">
                            <i class="bi bi-send"></i>
                            Enviar Notificación
                        </button>
                        <a href="{{ url('index.php/shared/notificaciones') }}" class="btn btn-outline w-full">
                            <i class="bi bi-x-lg"></i>
                            Cancelar
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const destinatarioTipo = document.getElementById('destinatarioTipo');
    const rolSelect = document.getElementById('rolSelect');
    const usuarioSelect = document.getElementById('usuarioSelect');
    
    destinatarioTipo?.addEventListener('change', function() {
        rolSelect.classList.add('hidden');
        usuarioSelect.classList.add('hidden');
        
        if (this.value === 'rol') {
            rolSelect.classList.remove('hidden');
        } else if (this.value === 'individual') {
            usuarioSelect.classList.remove('hidden');
        }
    });
});
</script>
@endsection
