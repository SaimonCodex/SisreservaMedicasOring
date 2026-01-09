@extends('layouts.admin')

@section('title', 'Detalle de Cita')

@section('content')
<div class="mb-6">
    <a href="{{ route('citas.index') }}" class="text-medical-600 hover:text-medical-700 inline-flex items-center text-sm font-medium mb-3">
        <i class="bi bi-arrow-left mr-1"></i> Volver a Citas
    </a>
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h2 class="text-3xl font-display font-bold text-gray-900">Detalle de Cita</h2>
            <p class="text-gray-500 mt-1">Información completa de la cita médica</p>
        </div>
        <div class="flex gap-3">
            <button class="btn btn-outline">
                <i class="bi bi-printer mr-2"></i>
                Imprimir
            </button>
            <a href="{{ route('citas.edit', 1) }}" class="btn btn-primary">
                <i class="bi bi-pencil mr-2"></i>
                Editar
            </a>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Columna Principal -->
    <div class="lg:col-span-2 space-y-6">
        
        <!-- Estado de la Cita -->
        <div class="card p-0 overflow-hidden">
            <div class="bg-gradient-to-r from-warning-600 to-warning-500 p-6">
                <div class="flex items-center justify-between">
                    <div class="text-white">
                        <p class="text-white/80 text-sm mb-1">Estado de la Cita</p>
                        <h3 class="text-2xl font-bold">PENDIENTE</h3>
                    </div>
                    <div class="w-16 h-16 rounded-full bg-white/20 backdrop-blur-sm flex items-center justify-center border-4 border-white/30">
                        <i class="bi bi-clock text-white text-3xl"></i>
                    </div>
                </div>
            </div>
            
            <div class="p-6">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-center">
                    <div class="p-3 bg-gray-50 rounded-xl">
                        <i class="bi bi-calendar3 text-2xl text-medical-600 mb-2"></i>
                        <p class="text-xs text-gray-500">Fecha</p>
                        <p class="font-bold text-gray-900">15 Ene 2026</p>
                    </div>
                    <div class="p-3 bg-gray-50 rounded-xl">
                        <i class="bi bi-clock text-2xl text-warning-600 mb-2"></i>
                        <p class="text-xs text-gray-500">Hora</p>
                        <p class="font-bold text-gray-900">08:00 AM</p>
                    </div>
                    <div class="p-3 bg-gray-50 rounded-xl">
                        <i class="bi bi-hourglass-split text-2xl text-info-600 mb-2"></i>
                        <p class="text-xs text-gray-500">Duración</p>
                        <p class="font-bold text-gray-900">30 min</p>
                    </div>
                    <div class="p-3 bg-gray-50 rounded-xl">
                        <i class="bi bi-hash text-2xl text-success-600 mb-2"></i>
                        <p class="text-xs text-gray-500">N° Cita</p>
                        <p class="font-bold text-gray-900">C-0058</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Información del Paciente -->
        <div class="card p-6 border-l-4 border-l-success-500">
            <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                <i class="bi bi-person-circle text-success-600"></i>
                Información del Paciente
            </h3>
            
            <div class="flex items-start gap-4 mb-4">
                <div class="w-16 h-16 rounded-full bg-gradient-to-br from-success-500 to-success-600 flex items-center justify-center text-white text-2xl font-bold flex-shrink-0">
                    AR
                </div>
                <div class="flex-1">
                    <h4 class="text-xl font-bold text-gray-900">Ana María Rodríguez González</h4>
                    <p class="text-gray-600">V-18765432 • HC-2024-001</p>
                    <div class="flex gap-2 mt-2">
                        <span class="badge badge-primary">35 años</span>
                        <span class="badge badge-info">Femenino</span>
                        <span class="badge badge-success">O+</span>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4 pt-4 border-t border-gray-100">
                <div>
                    <p class="text-sm text-gray-500 mb-1">Teléfono</p>
                    <p class="font-semibold text-gray-900">0414-5678901</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 mb-1">Email</p>
                    <p class="font-semibold text-gray-900">ana.r@example.com</p>
                </div>
            </div>

            <div class="mt-4 pt-4 border-t border-gray-100">
                <a href="{{ route('pacientes.show', 1) }}" class="btn btn-sm btn-outline">
                    <i class="bi bi-eye mr-1"></i> Ver Perfil Completo
                </a>
            </div>
        </div>

        <!-- Información del Médico -->
        <div class="card p-6 border-l-4 border-l-medical-500">
            <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                <i class="bi bi-person-badge text-medical-600"></i>
                Médico Asignado
            </h3>
            
            <div class="flex items-start gap-4 mb-4">
                <div class="w-16 h-16 rounded-full bg-gradient-to-br from-medical-500 to-medical-600 flex items-center justify-center text-white text-2xl font-bold flex-shrink-0">
                    JP
                </div>
                <div class="flex-1">
                    <h4 class="text-xl font-bold text-gray-900">Dr. Juan Carlos Pérez</h4>
                    <p class="text-gray-600">MPPS: 98765 • CMG: 54321</p>
                    <div class="flex gap-2 mt-2">
                        <span class="badge badge-primary">Cardiología</span>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4 pt-4 border-t border-gray-100">
                <div>
                    <p class="text-sm text-gray-500 mb-1">Consultorio</p>
                    <p class="font-semibold text-gray-900">205 - Piso 2</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 mb-1">Especialidad</p>
                    <p class="font-semibold text-gray-900">Cardiología</p>
                </div>
            </div>

            <div class="mt-4 pt-4 border-t border-gray-100">
                <a href="{{ route('medicos.show', 1) }}" class="btn btn-sm btn-outline">
                    <i class="bi bi-eye mr-1"></i> Ver Perfil del Médico
                </a>
            </div>
        </div>

        <!-- Detalles de la Cita -->
        <div class="card p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                <i class="bi bi-file-medical text-info-600"></i>
                Detalles de la Consulta
            </h3>
            
            <div class="space-y-4">
                <div>
                    <p class="text-sm text-gray-500 mb-1">Motivo de Consulta</p>
                    <p class="font-semibold text-gray-900">Control Cardiológico</p>
                </div>

                <div>
                    <p class="text-sm text-gray-500 mb-2">Observaciones</p>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-gray-700 text-sm">
                            Paciente con antecedentes de hipertensión arterial. Requiere control de presión arterial y revisión de tratamiento actual.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Historial de la Cita -->
        <div class="card p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                <i class="bi bi-clock-history text-warning-600"></i>
                Historial de Cambios
            </h3>
            
            <div class="space-y-3">
                <div class="flex gap-3 items-start">
                    <div class="w-8 h-8 rounded-full bg-success-100 flex items-center justify-center flex-shrink-0">
                        <i class="bi bi-plus-lg text-success-600 text-sm"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-900">Cita creada</p>
                        <p class="text-xs text-gray-500">08/01/2026 - 10:30 AM por Admin</p>
                    </div>
                </div>

                <div class="flex gap-3 items-start">
                    <div class="w-8 h-8 rounded-full bg-info-100 flex items-center justify-center flex-shrink-0">
                        <i class="bi bi-bell text-info-600 text-sm"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-900">Notificación enviada</p>
                        <p class="text-xs text-gray-500">08/01/2026 - 10:35 AM (SMS y Email)</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="lg:col-span-1 space-y-6">
        <!-- Acciones Rápidas -->
        <div class="card p-6 sticky top-6">
            <h4 class="font-bold text-gray-900 mb-4">Acciones</h4>
            <div class="space-y-2">
                <button class="btn btn-success w-full justify-start">
                    <i class="bi bi-check-circle mr-2"></i>
                    Confirmar Cita
                </button>
                <button class="btn btn-outline w-full justify-start">
                    <i class="bi bi-calendar-x mr-2"></i>
                    Reprogramar
                </button>
                <button class="btn btn-outline w-full justify-start">
                    <i class="bi bi-bell mr-2"></i>
                    Enviar Recordatorio
                </button>
                <button class="btn btn-outline w-full justify-start text-danger-600 border-danger-300 hover:bg-danger-50">
                    <i class="bi bi-x-circle mr-2"></i>
                    Cancelar Cita
                </button>
            </div>
        </div>

        <!-- Estado -->
        <div class="card p-6">
            <h4 class="font-bold text-gray-900 mb-4">Estado Actual</h4>
            <div class="space-y-3 text-sm">
                <div class="flex items-center justify-between">
                    <span class="text-gray-600">Estado</span>
                    <span class="badge badge-warning">Pendiente</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-gray-600">Confirmación</span>
                    <span class="badge badge-gray">No confirmada</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-gray-600">Notificado</span>
                    <span class="badge badge-success">Sí</span>
                </div>
            </div>
        </div>

        <!-- Información Adicional -->
        <div class="card p-6 bg-medical-50 border-medical-200">
            <h4 class="font-bold text-gray-900 mb-4">Información</h4>
            <div class="space-y-2 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-600">Creada por:</span>
                    <span class="font-medium text-gray-900">Admin</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Fecha creación:</span>
                    <span class="text-gray-900">08/01/2026</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Última modificación:</span>
                    <span class="text-gray-900">08/01/2026</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
