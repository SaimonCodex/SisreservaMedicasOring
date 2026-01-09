@extends('layouts.admin')

@section('title', 'Configuración de Correo')

@section('content')
<div class="mb-6">
    <a href="{{ route('configuracion.index') }}" class="text-medical-600 hover:text-medical-700 inline-flex items-center text-sm font-medium mb-3">
        <i class="bi bi-arrow-left mr-1"></i> Volver a Configuración
    </a>
    <h2 class="text-3xl font-display font-bold text-gray-900">Configuración de Correo Electrónico</h2>
    <p class="text-gray-500 mt-1">Servidor SMTP y plantillas de notificaciones</p>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Formulario Principal -->
    <div class="lg:col-span-2 space-y-6">
        
        <!-- Configuración SMTP -->
        <div class="card p-6 border-l-4 border-l-premium-500">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-xl bg-premium-50 flex items-center justify-center">
                        <i class="bi bi-envelope-at-fill text-premium-600 text-2xl"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-900">Configuración SMTP</h3>
                        <p class="text-sm text-gray-500">Servidor de correo saliente</p>
                    </div>
                </div>
                <span class="badge badge-success">Conectado</span>
            </div>

            <form method="POST" action="{{ route('configuracion.correo.actualizar') }}" class="space-y-4">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="form-group md:col-span-2">
                        <label for="mail_host" class="form-label">Servidor SMTP</label>
                        <input type="text" name="mail_host" id="mail_host" 
                               class="input" value="smtp.gmail.com" 
                               placeholder="smtp.example.com">
                    </div>

                    <div class="form-group">
                        <label for="mail_port" class="form-label">Puerto</label>
                        <select name="mail_port" id="mail_port" class="form-select">
                            <option value="587">587 (TLS)</option>
                            <option value="465">465 (SSL)</option>
                            <option value="25">25 (Sin cifrado)</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="mail_encryption" class="form-label">Cifrado</label>
                        <select name="mail_encryption" id="mail_encryption" class="form-select">
                            <option value="tls">TLS (Recomendado)</option>
                            <option value="ssl">SSL</option>
                            <option value="null">Ninguno</option>
                        </select>
                    </div>

                    <div class="form-group md:col-span-2">
                        <label for="mail_username" class="form-label">Usuario / Email</label>
                        <input type="email" name="mail_username" id="mail_username" 
                               class="input" value="notificaciones@clinica.com" 
                               placeholder="usuario@example.com">
                    </div>

                    <div class="form-group md:col-span-2">
                        <label for="mail_password" class="form-label">Contraseña</label>
                        <input type="password" name="mail_password" id="mail_password" 
                               class="input" placeholder="••••••••••">
                        <p class="form-help">Deja en blanco para mantener la contraseña actual</p>
                    </div>

                    <div class="form-group">
                        <label for="mail_from_address" class="form-label">Email Remitente</label>
                        <input type="email" name="mail_from_address" id="mail_from_address" 
                               class="input" value="no-reply@clinica.com" 
                               placeholder="no-reply@example.com">
                    </div>

                    <div class="form-group">
                        <label for="mail_from_name" class="form-label">Nombre Remitente</label>
                        <input type="text" name="mail_from_name" id="mail_from_name" 
                               class="input" value="Clínica Médica" 
                               placeholder="Mi Clínica">
                    </div>
                </div>

                <div class="flex gap-3 pt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save mr-2"></i>
                        Guardar Configuración
                    </button>
                    <button type="button" class="btn btn-outline" onclick="testEmail()">
                        <i class="bi bi-send mr-2"></i>
                        Enviar Correo de Prueba
                    </button>
                </div>
            </form>
        </div>

        <!-- Plantillas de Correo -->
        <div class="card p-6 border-l-4 border-l-info-500">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 rounded-xl bg-info-50 flex items-center justify-center">
                    <i class="bi bi-file-earmark-richtext text-info-600 text-xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900">Plantillas Activas</h3>
            </div>

            <div class="space-y-3">
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors">
                    <div class="flex items-center gap-3">
                        <i class="bi bi-calendar-check text-medical-600 text-xl"></i>
                        <div>
                            <p class="font-semibold text-gray-900">Confirmación de Cita</p>
                            <p class="text-xs text-gray-500">Se envía al agendar una nueva cita</p>
                        </div>
                    </div>
                    <div class="flex gap-2">
                        <button class="btn btn-sm btn-ghost"><i class="bi bi-eye"></i></button>
                        <button class="btn btn-sm btn-ghost"><i class="bi bi-pencil"></i></button>
                    </div>
                </div>

                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors">
                    <div class="flex items-center gap-3">
                        <i class="bi bi-bell text-warning-600 text-xl"></i>
                        <div>
                            <p class="font-semibold text-gray-900">Recordatorio de Cita</p>
                            <p class="text-xs text-gray-500">24 horas antes de la cita</p>
                        </div>
                    </div>
                    <div class="flex gap-2">
                        <button class="btn btn-sm btn-ghost"><i class="bi bi-eye"></i></button>
                        <button class="btn btn-sm btn-ghost"><i class="bi bi-pencil"></i></button>
                    </div>
                </div>

                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors">
                    <div class="flex items-center gap-3">
                        <i class="bi bi-file-medical text-premium-600 text-xl"></i>
                        <div>
                            <p class="font-semibold text-gray-900">Resultados Disponibles</p>
                            <p class="text-xs text-gray-500">Al completar un estudio de laboratorio</p>
                        </div>
                    </div>
                    <div class="flex gap-2">
                        <button class="btn btn-sm btn-ghost"><i class="bi bi-eye"></i></button>
                        <button class="btn btn-sm btn-ghost"><i class="bi bi-pencil"></i></button>
                    </div>
                </div>
            </div>

            <button class="btn btn-outline w-full mt-4">
                <i class="bi bi-plus-lg mr-2"></i>
                Crear Nueva Plantilla
            </button>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="lg:col-span-1 space-y-6">
        <!-- Estado del Servicio -->
        <div class="card p-6 sticky top-6">
            <h4 class="font-bold text-gray-900 mb-4">Estado del Servicio</h4>
            <div class="text-center mb-6">
                <div class="w-20 h-20 mx-auto rounded-full bg-success-100 flex items-center justify-center mb-3">
                    <i class="bi bi-check-circle-fill text-4xl text-success-600"></i>
                </div>
                <p class="font-bold text-success-700 text-lg">Operativo</p>
                <p class="text-xs text-gray-500 mt-1">La conexión SMTP está funcionando correctamente</p>
            </div>

            <div class="space-y-3 pt-4 border-t border-gray-200">
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500">Emails Enviados (Hoy)</span>
                    <span class="font-bold">47</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500">Cola Pendiente</span>
                    <span class="font-bold">2</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500">Errores (Semana)</span>
                    <span class="font-bold text-danger-600">0</span>
                </div>
            </div>
        </div>

        <!-- Configuraciones Rápidas -->
        <div class="card p-6">
            <h4 class="font-bold text-gray-900 mb-4">Opciones</h4>
            <div class="space-y-3">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" class="form-checkbox text-medical-600" checked>
                    <span class="text-sm text-gray-700">Activar recordatorios automáticos</span>
                </label>
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" class="form-checkbox text-medical-600" checked>
                    <span class="text-sm text-gray-700">Confirmar citas por email</span>
                </label>
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" class="form-checkbox text-medical-600">
                    <span class="text-sm text-gray-700">Copias a administradores</span>
                </label>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function testEmail() {
    if(confirm('¿Enviar un correo de prueba a tu dirección?')) {
        // Lógica AJAX para enviar correo de prueba
        alert('Correo de prueba enviado. Revisa tu bandeja de entrada.');
    }
}
</script>
@endpush
@endsection
