@extends('layouts.admin')

@section('title', 'Agendar Cita')

@section('content')
<div class="mb-6">
    <a href="{{ route('citas.index') }}" class="text-medical-600 hover:text-medical-700 inline-flex items-center text-sm font-medium mb-3">
        <i class="bi bi-arrow-left mr-1"></i> Volver a Citas
    </a>
    <h2 class="text-3xl font-display font-bold text-gray-900">Agendar Nueva Cita</h2>
    <p class="text-gray-500 mt-1">Complete la información para programar la cita médica</p>
</div>

<form method="POST" action="{{ route('citas.store') }}">
    @csrf
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Formulario Principal -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Selección de Paciente -->
            <div class="card p-6 border-l-4 border-l-success-500">
                <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                    <i class="bi bi-person text-success-600"></i>
                    Datos del Paciente
                </h3>
                
                <div class="form-group mb-4">
                    <label for="paciente_buscar" class="form-label form-label-required">Buscar Paciente</label>
                    <div class="relative">
                        <i class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                        <input type="text" id="paciente_buscar" class="input pl-10" placeholder="Buscar por nombre, cédula o historia...">
                    </div>
                    <p class="form-help">Escriba para buscar en la base de datos</p>
                </div>

                <div id="paciente_seleccionado" class="hidden">
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
                            <button type="button" class="text-danger-600 hover:text-danger-700">
                                <i class="bi bi-x-lg"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <input type="hidden" name="paciente_id" id="paciente_id" value="1">
            </div>

            <!-- Selección de Médico y Especialidad -->
            <div class="card p-6 border-l-4 border-l-medical-500">
                <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                    <i class="bi bi-person-badge text-medical-600"></i>
                    Médico y Especialidad
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="form-group">
                        <label for="especialidad_id" class="form-label form-label-required">Especialidad</label>
                        <select id="especialidad_id" name="especialidad_id" class="form-select" required>
                            <option value="">Seleccione...</option>
                            <option value="1">Cardiología</option>
                            <option value="2">Pediatría</option>
                            <option value="3">Traumatología</option>
                            <option value="4">Medicina General</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="medico_id" class="form-label form-label-required">Médico</label>
                        <select id="medico_id" name="medico_id" class="form-select" required>
                            <option value="">Seleccione especialidad primero...</option>
                        </select>
                    </div>

                    <div class="form-group md:col-span-2">
                        <label for="consultorio_id" class="form-label">Consultorio</label>
                        <select id="consultorio_id" name="consultorio_id" class="form-select">
                            <option value="">Se asignará automáticamente</option>
                            <option value="1">Consultorio 101 - Piso 1</option>
                            <option value="2">Consultorio 205 - Piso 2</option>
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
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="form-group">
                        <label for="fecha" class="form-label form-label-required">Fecha de la Cita</label>
                        <input type="date" id="fecha" name="fecha" class="input" required min="{{ date('Y-m-d') }}">
                    </div>

                    <div class="form-group">
                        <label for="hora" class="form-label form-label-required">Hora</label>
                        <select id="hora" name="hora" class="form-select" required>
                            <option value="">Seleccione médico y fecha...</option>
                        </select>
                        <p class="form-help">Horarios disponibles según el médico seleccionado</p>
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

            <!-- Motivo y Observaciones -->
            <div class="card p-6 border-l-4 border-l-info-500">
                <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                    <i class="bi bi-chat-left-text text-info-600"></i>
                    Detalles de la Cita
                </h3>
                
                <div class="grid grid-cols-1 gap-4">
                    <div class="form-group">
                        <label for="motivo" class="form-label form-label-required">Motivo de la Consulta</label>
                        <select id="motivo" name="motivo" class="form-select" required>
                            <option value="">Seleccione...</option>
                            <option value="primera_vez">Primera Vez</option>
                            <option value="control">Control</option>
                            <option value="seguimiento">Seguimiento</option>
                            <option value="emergencia">Emergencia</option>
                            <option value="resultados">Revisión de Resultados</option>
                            <option value="otro">Otro</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="observaciones" class="form-label">Observaciones</label>
                        <textarea id="observaciones" name="observaciones" rows="4" class="form-textarea" placeholder="Información adicional relevante para la cita..."></textarea>
                    </div>
                </div>
            </div>

            <!-- Opciones de Notificación -->
            <div class="card p-6 bg-medical-50 border border-medical-200">
                <h4 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <i class="bi bi-bell text-medical-600"></i>
                    Notificaciones
                </h4>
                
                <div class="space-y-3">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="enviar_sms" value="1" class="form-checkbox" checked>
                        <span class="text-sm text-gray-700">Enviar SMS al paciente</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="enviar_email" value="1" class="form-checkbox" checked>
                        <span class="text-sm text-gray-700">Enviar email de confirmación</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="recordatorio" value="1" class="form-checkbox" checked>
                        <span class="text-sm text-gray-700">Enviar recordatorio 24h antes</span>
                    </label>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Resumen de la Cita -->
            <div class="card p-6 sticky top-6">
                <h4 class="font-bold text-gray-900 mb-4">Resumen de la Cita</h4>
                
                <div class="space-y-3 text-sm">
                    <div class="p-3 bg-gray-50 rounded-lg">
                        <p class="text-xs text-gray-500 mb-1">Paciente</p>
                        <p class="font-semibold text-gray-900">Ana Rodríguez</p>
                        <p class="text-xs text-gray-600">HC-2024-001</p>
                    </div>

                    <div class="p-3 bg-gray-50 rounded-lg">
                        <p class="text-xs text-gray-500 mb-1">Médico</p>
                        <p class="font-semibold text-gray-900">-</p>
                        <p class="text-xs text-gray-600">Seleccione un médico</p>
                    </div>

                    <div class="p-3 bg-gray-50 rounded-lg">
                        <p class="text-xs text-gray-500 mb-1">Fecha y Hora</p>
                        <p class="font-semibold text-gray-900">-</p>
                        <p class="text-xs text-gray-600">Seleccione fecha y hora</p>
                    </div>

                    <div class="p-3 bg-gray-50 rounded-lg">
                        <p class="text-xs text-gray-500 mb-1">Estado Inicial</p>
                        <span class="badge badge-warning">Pendiente</span>
                    </div>
                </div>
            </div>

            <!-- Acciones -->
            <div class="card p-6">
                <button type="submit" class="btn btn-primary w-full shadow-lg mb-3">
                    <i class="bi bi-calendar-check mr-2"></i>
                    Agendar Cita
                </button>
                <a href="{{ route('citas.index') }}" class="btn btn-outline w-full">
                    <i class="bi bi-x-lg mr-2"></i>
                    Cancelar
                </a>
            </div>

            <!-- Disponibilidad -->
            <div class="card p-6 bg-gradient-to-br from-info-50 to-medical-50">
                <h4 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <i class="bi bi-clock-history text-info-600"></i>
                    Disponibilidad
                </h4>
                <p class="text-sm text-gray-600 mb-3">
                    Seleccione un médico y fecha para ver los horarios disponibles
                </p>
                <div class="text-center p-4 bg-white rounded-lg">
                    <i class="bi bi-calendar3 text-4xl text-gray-300 mb-2"></i>
                    <p class="text-xs text-gray-500">Sin médico seleccionado</p>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection
