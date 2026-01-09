@extends('layouts.admin')

@section('title', 'Editar Cita')

@section('content')
<div class="mb-6">
    <a href="{{ route('citas.show', 1) }}" class="text-medical-600 hover:text-medical-700 inline-flex items-center text-sm font-medium mb-3">
        <i class="bi bi-arrow-left mr-1"></i> Volver al Detalle
    </a>
    <h2 class="text-3xl font-display font-bold text-gray-900">Editar Cita</h2>
    <p class="text-gray-500 mt-1">Reprogramar o modificar la cita médica</p>
</div>

<form method="POST" action="{{ route('citas.update', 1) }}">
    @csrf
    @method('PUT')
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Formulario Principal -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Información del Paciente (No editable) -->
            <div class="card p-6 border-l-4 border-l-success-500">
                <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <i class="bi bi-person text-success-600"></i>
                    Paciente
                </h3>
                
                <div class="bg-success-50 border border-success-200 rounded-xl p-4">
                    <div class="flex items-center gap-4">
                        <div class="w-16 h-16 rounded-full bg-gradient-to-br from-success-500 to-success-600 flex items-center justify-center text-white text-xl font-bold">
                            AR
                        </div>
                        <div class="flex-1">
                            <h4 class="font-bold text-gray-900">Ana Rodríguez</h4>
                            <p class="text-sm text-gray-600">V-18765432 • HC-2024-001</p>
                            <p class="text-xs text-gray-500 mt-1">35 años • Femenino • O+</p>
                        </div>
                    </div>
                </div>
                <p class="text-xs text-gray-500 mt-2">
                    <i class="bi bi-info-circle mr-1"></i>
                    El paciente no puede ser modificado. Para cambiar de paciente, cree una nueva cita.
                </p>
            </div>

            <!-- Médico y Especialidad -->
            <div class="card p-6 border-l-4 border-l-medical-500">
                <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                    <i class="bi bi-person-badge text-medical-600"></i>
                    Médico y Especialidad
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="form-group">
                        <label for="especialidad_id" class="form-label form-label-required">Especialidad</label>
                        <select id="especialidad_id" name="especialidad_id" class="form-select" required>
                            <option value="1" selected>Cardiología</option>
                            <option value="2">Pediatría</option>
                            <option value="3">Traumatología</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="medico_id" class="form-label form-label-required">Médico</label>
                        <select id="medico_id" name="medico_id" class="form-select" required>
                            <option value="1" selected>Dr. Juan Pérez</option>
                            <option value="2">Dr. Carlos López</option>
                        </select>
                    </div>

                    <div class="form-group md:col-span-2">
                        <label for="consultorio_id" class="form-label">Consultorio</label>
                        <select id="consultorio_id" name="consultorio_id" class="form-select">
                            <option value="1">Consultorio 101 - Piso 1</option>
                            <option value="2" selected>Consultorio 205 - Piso 2</option>
                            <option value="3">Consultorio 310 - Piso 3</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Fecha y Hora -->
            <div class="card p-6 border-l-4 border-l-warning-500">
                <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                    <i class="bi bi-calendar-event text-warning-600"></i>
                    Fecha y Hora
                </h3>
                
                <div class="bg-warning-50 border border-warning-200 rounded-xl p-4 mb-4">
                    <p class="text-sm text-warning-800 flex items-center gap-2">
                        <i class="bi bi-exclamation-triangle-fill"></i>
                        <strong>Fecha actual:</strong> 15/01/2026 - 08:00 AM
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="form-group">
                        <label for="fecha" class="form-label form-label-required">Nueva Fecha</label>
                        <input type="date" id="fecha" name="fecha" class="input" value="2026-01-15" required min="{{ date('Y-m-d') }}">
                    </div>

                    <div class="form-group">
                        <label for="hora" class="form-label form-label-required">Nueva Hora</label>
                        <select id="hora" name="hora" class="form-select" required>
                            <option value="08:00" selected>08:00 AM</option>
                            <option value="08:30">08:30 AM</option>
                            <option value="09:00">09:00 AM</option>
                            <option value="09:30">09:30 AM</option>
                            <option value="10:00">10:00 AM</option>
                        </select>
                    </div>

                    <div class="form-group md:col-span-2">
                        <label for="duracion" class="form-label">Duración Estimada</label>
                        <select id="duracion" name="duracion" class="form-select">
                            <option value="30" selected>30 minutos</option>
                            <option value="45">45 minutos</option>
                            <option value="60">1 hora</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Estado de la Cita -->
            <div class="card p-6 border-l-4 border-l-info-500">
                <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                    <i class="bi bi-flag text-info-600"></i>
                    Estado de la Cita
                </h3>
                
                <div class="form-group">
                    <label for="estado" class="form-label form-label-required">Estado</label>
                    <select id="estado" name="estado" class="form-select" required>
                        <option value="pendiente" selected>Pendiente</option>
                        <option value="confirmada">Confirmada</option>
                        <option value="completada">Completada</option>
                        <option value="cancelada">Cancelada</option>
                        <option value="no_asistio">No asistió</option>
                    </select>
                </div>

                <div class="form-group" id="motivo_cancelacion_container" style="display: none;">
                    <label for="motivo_cancelacion" class="form-label">Motivo de Cancelación</label>
                    <textarea id="motivo_cancelacion" name="motivo_cancelacion" rows="3" class="form-textarea" placeholder="Indique el motivo..."></textarea>
                </div>
            </div>

            <!-- Motivo y Observaciones -->
            <div class="card p-6 border-l-4 border-l-success-500">
                <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                    <i class="bi bi-chat-left-text text-success-600"></i>
                    Detalles de la Cita
                </h3>
                
                <div class="grid grid-cols-1 gap-4">
                    <div class="form-group">
                        <label for="motivo" class="form-label form-label-required">Motivo de la Consulta</label>
                        <select id="motivo" name="motivo" class="form-select" required>
                            <option value="primera_vez">Primera Vez</option>
                            <option value="control" selected>Control</option>
                            <option value="seguimiento">Seguimiento</option>
                            <option value="emergencia">Emergencia</option>
                            <option value="resultados">Revisión de Resultados</option>
                            <option value="otro">Otro</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="observaciones" class="form-label">Observaciones</label>
                        <textarea id="observaciones" name="observaciones" rows="4" class="form-textarea">Paciente con antecedentes de hipertensión arterial. Requiere control de presión arterial y revisión de tratamiento actual.</textarea>
                    </div>
                </div>
            </div>

            <!-- Opciones de Notificación -->
            <div class="card p-6 bg-medical-50 border border-medical-200">
                <h4 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <i class="bi bi-bell text-medical-600"></i>
                    Notificar Cambios
                </h4>
                
                <div class="space-y-3">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="notificar_sms" value="1" class="form-checkbox">
                        <span class="text-sm text-gray-700">Enviar SMS al paciente sobre los cambios</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="notificar_email" value="1" class="form-checkbox">
                        <span class="text-sm text-gray-700">Enviar email de notificación</span>
                    </label>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Resumen de Cambios -->
            <div class="card p-6 sticky top-6">
                <h4 class="font-bold text-gray-900 mb-4">Información de la Cita</h4>
                
                <div class="space-y-3 text-sm">
                    <div class="p-3 bg-gray-50 rounded-lg">
                        <p class="text-xs text-gray-500 mb-1">N° de Cita</p>
                        <p class="font-semibold text-gray-900">C-0058</p>
                    </div>

                    <div class="p-3 bg-gray-50 rounded-lg">
                        <p class="text-xs text-gray-500 mb-1">Fecha Actual</p>
                        <p class="font-semibold text-gray-900">15 Ene 2026 - 08:00 AM</p>
                    </div>

                    <div class="p-3 bg-gray-50 rounded-lg">
                        <p class="text-xs text-gray-500 mb-1">Estado Actual</p>
                        <span class="badge badge-warning">Pendiente</span>
                    </div>

                    <div class="p-3 bg-gray-50 rounded-lg">
                        <p class="text-xs text-gray-500 mb-1">Creada</p>
                        <p class="text-xs text-gray-600">08/01/2026 por Admin</p>
                    </div>
                </div>
            </div>

            <!-- Acciones -->
            <div class="card p-6">
                <button type="submit" class="btn btn-primary w-full shadow-lg mb-3">
                    <i class="bi bi-save mr-2"></i>
                    Guardar Cambios
                </button>
                <a href="{{ route('citas.show', 1) }}" class="btn btn-outline w-full mb-3">
                    <i class="bi bi-x-lg mr-2"></i>
                    Cancelar
                </a>
                
                <div class="border-t border-gray-200 pt-4 mt-4">
                    <button type="button" class="btn btn-sm text-danger-600 hover:bg-danger-50 w-full" onclick="return confirm('¿Está seguro de eliminar esta cita?')">
                        <i class="bi bi-trash mr-2"></i>
                        Eliminar Cita
                    </button>
                </div>
            </div>

            <!-- Ayuda -->
            <div class="card p-6 bg-info-50 border-info-200">
                <h4 class="font-bold text-info-900 mb-2 flex items-center gap-2">
                    <i class="bi bi-lightbulb"></i>
                    Consejo
                </h4>
                <p class="text-sm text-info-700">
                    Si reprograma la cita, active las notificaciones para que el paciente sea informado del cambio.
                </p>
            </div>
        </div>
    </div>
</form>

<script>
    // Mostrar campo de motivo si se selecciona "cancelada"
    document.getElementById('estado').addEventListener('change', function() {
        const motivoCancelacion = document.getElementById('motivo_cancelacion_container');
        if (this.value === 'cancelada') {
            motivoCancelacion.style.display = 'block';
        } else {
            motivoCancelacion.style.display = 'none';
        }
    });
</script>
@endsection
