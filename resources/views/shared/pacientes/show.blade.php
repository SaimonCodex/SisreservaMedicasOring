@extends('layouts.admin')

@section('title', 'Perfil del Paciente')

@section('content')
<div class="mb-6">
    <a href="{{ route('pacientes.index') }}" class="text-medical-600 hover:text-medical-700 inline-flex items-center text-sm font-medium mb-3">
        <i class="bi bi-arrow-left mr-1"></i> Volver a Pacientes
    </a>
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h2 class="text-3xl font-display font-bold text-gray-900">Perfil del Paciente</h2>
            <p class="text-gray-500 mt-1">Información completa y registro médico</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('citas.create') }}?paciente=1" class="btn btn-outline">
                <i class="bi bi-calendar-plus mr-2"></i>
                Nueva Cita
            </a>
            <a href="{{ route('pacientes.edit', 1) }}" class="btn btn-primary">
                <i class="bi bi-pencil mr-2"></i>
                Editar
            </a>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Columna Principal -->
    <div class="lg:col-span-2 space-y-6">
        
        <!-- Encabezado del Paciente -->
        <div class="card p-0 overflow-hidden">
            <div class="bg-gradient-to-r from-success-600 to-success-500 p-6">
                <div class="flex items-center gap-6">
                    <div class="w-24 h-24 rounded-full bg-white/20 backdrop-blur-sm flex items-center justify-center text-white text-4xl font-bold border-4 border-white/30">
                        AR
                    </div>
                    <div class="text-white flex-1">
                        <h3 class="text-2xl font-bold mb-1">Ana Rodríguez</h3>
                        <p class="text-white/90 mb-2">35 años • Femenino • V-18765432</p>
                        <div class="flex gap-2">
                            <span class="badge bg-white/20 text-white border border-white/30">Activo</span>
                            <span class="badge bg-white/20 text-white border border-white/30">O+</span>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-sm text-white/70">Historia Clínica</p>
                        <p class="text-xl font-bold text-white">HC-2024-001</p>
                    </div>
                </div>
            </div>
            
            <div class="p-6">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div class="text-center p-4 bg-gray-50 rounded-xl">
                        <p class="text-3xl font-bold text-medical-600 mb-1">12</p>
                        <p class="text-sm text-gray-500">Consultas</p>
                    </div>
                    <div class="text-center p-4 bg-gray-50 rounded-xl">
                        <p class="text-3xl font-bold text-success-600 mb-1">8</p>
                        <p class="text-sm text-gray-500">Completadas</p>
                    </div>
                    <div class="text-center p-4 bg-gray-50 rounded-xl">
                        <p class="text-3xl font-bold text-warning-600 mb-1">0</p>
                        <p class="text-sm text-gray-500">Pendientes</p>
                    </div>
                    <div class="text-center p-4 bg-gray-50 rounded-xl">
                        <p class="text-3xl font-bold text-info-600 mb-1">4</p>
                        <p class="text-sm text-gray-500">Canceladas</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Datos Personales -->
        <div class="card p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                <i class="bi bi-person-circle text-medical-600"></i>
                Datos Personales
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-gray-500 mb-1">Nombre Completo</p>
                    <p class="font-semibold text-gray-900">Ana María Rodríguez González</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 mb-1">Documento de Identidad</p>
                    <p class="font-semibold text-gray-900">V-18765432</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 mb-1">Fecha de Nacimiento</p>
                    <p class="font-semibold text-gray-900">22/05/1988 (35 años)</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 mb-1">Género</p>
                    <p class="font-semibold text-gray-900">Femenino</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 mb-1">Estado Civil</p>
                    <p class="font-semibold text-gray-900">Casada</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 mb-1">Grupo Sanguíneo</p>
                    <p class="font-semibold text-gray-900">O+</p>
                </div>
            </div>
        </div>

        <!-- Contacto -->
        <div class="card p-6 border-l-4 border-l-success-500">
            <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                <i class="bi bi-telephone text-success-600"></i>
                Información de Contacto
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-gray-500 mb-1">Teléfono Principal</p>
                    <p class="font-semibold text-gray-900">0414-5678901</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 mb-1">Teléfono Secundario</p>
                    <p class="font-semibold text-gray-900">0212-3456789</p>
                </div>
                <div class="md:col-span-2">
                    <p class="text-sm text-gray-500 mb-1">Correo Electrónico</p>
                    <p class="font-semibold text-gray-900">ana.rodriguez@example.com</p>
                </div>
                <div class="md:col-span-2">
                    <p class="text-sm text-gray-500 mb-1">Dirección</p>
                    <p class="font-semibold text-gray-900">Av. Principal, Urb. Los Rosales, Caracas, Miranda</p>
                </div>
            </div>
        </div>

        <!-- Contacto de Emergencia -->
        <div class="card p-6 border-l-4 border-l-danger-500">
            <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                <i class="bi bi-shield-exclamation text-danger-600"></i>
                Contacto de Emergencia
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-gray-500 mb-1">Nombre</p>
                    <p class="font-semibold text-gray-900">Pedro Rodríguez</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 mb-1">Parentesco</p>
                    <p class="font-semibold text-gray-900">Cónyuge</p>
                </div>
                <div class="md:col-span-2">
                    <p class="text-sm text-gray-500 mb-1">Teléfono</p>
                    <p class="font-semibold text-gray-900">0424-9876543</p>
                </div>
            </div>
        </div>

        <!-- Próximas Citas -->
        <div class="card p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                    <i class="bi bi-calendar-check text-warning-600"></i>
                    Próximas Citas
                </h3>
                <a href="{{ route('citas.create') }}?paciente=1" class="text-sm text-medical-600 hover:underline">
                    <i class="bi bi-plus-lg mr-1"></i>Agendar
                </a>
            </div>
            <div class="space-y-3">
                <div class="flex items-center gap-4 p-4 bg-warning-50 border border-warning-200 rounded-xl">
                    <div class="w-16 text-center">
                        <p class="text-2xl font-bold text-warning-700">15</p>
                        <p class="text-xs text-warning-600">ENE</p>
                    </div>
                    <div class="flex-1">
                        <p class="font-semibold text-gray-900">Control Cardiológico</p>
                        <p class="text-sm text-gray-600">Dr. Juan Pérez • 10:00 AM</p>
                    </div>
                    <span class="badge badge-warning">Pendiente</span>
                </div>

                <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-xl">
                    <div class="w-16 text-center">
                        <p class="text-2xl font-bold text-gray-700">28</p>
                        <p class="text-xs text-gray-600">ENE</p>
                    </div>
                    <div class="flex-1">
                        <p class="font-semibold text-gray-900">Consulta General</p>
                        <p class="text-sm text-gray-600">Dra. María González • 02:30 PM</p>
                    </div>
                    <span class="badge badge-gray">Agendada</span>
                </div>
            </div>
        </div>

        <!-- Historial Reciente -->
        <div class="card p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                    <i class="bi bi-clock-history text-info-600"></i>
                    Historial Reciente
                </h3>
                <a href="#" class="text-sm text-medical-600 hover:underline">Ver todo</a>
            </div>
            <div class="space-y-3">
                <div class="flex gap-3 p-3 hover:bg-gray-50 rounded-lg transition-colors">
                    <div class="w-10 h-10 rounded-full bg-success-100 flex items-center justify-center flex-shrink-0">
                        <i class="bi bi-check-lg text-success-600"></i>
                    </div>
                    <div class="flex-1">
                        <p class="font-medium text-gray-900 text-sm">Consulta Cardiológica</p>
                        <p class="text-xs text-gray-500">05/01/2026 • Dr. Juan Pérez</p>
                    </div>
                </div>

                <div class="flex gap-3 p-3 hover:bg-gray-50 rounded-lg transition-colors">
                    <div class="w-10 h-10 rounded-full bg-info-100 flex items-center justify-center flex-shrink-0">
                        <i class="bi bi-clipboard-pulse text-info-600"></i>
                    </div>
                    <div class="flex-1">
                        <p class="font-medium text-gray-900 text-sm">Exámenes de Laboratorio</p>
                        <p class="text-xs text-gray-500">28/12/2025 • Lab. Central</p>
                    </div>
                </div>

                <div class="flex gap-3 p-3 hover:bg-gray-50 rounded-lg transition-colors">
                    <div class="w-10 h-10 rounded-full bg-success-100 flex items-center justify-center flex-shrink-0">
                        <i class="bi bi-check-lg text-success-600"></i>
                    </div>
                    <div class="flex-1">
                        <p class="font-medium text-gray-900 text-sm">Control General</p>
                        <p class="text-xs text-gray-500">15/12/2025 • Dra. María González</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="lg:col-span-1 space-y-6">
        <!-- Acciones Rápidas -->
        <div class="card p-6 sticky top-6">
            <h4 class="font-bold text-gray-900 mb-4">Acciones Rápidas</h4>
            <div class="space-y-2">
                <a href="{{ route('historia-clinica.base.show', 1) }}" class="btn btn-outline w-full justify-start">
                    <i class="bi bi-file-medical mr-2"></i>
                    Ver Historia Clínica
                </a>
                <button class="btn btn-outline w-full justify-start">
                    <i class="bi bi-calendar-plus mr-2"></i>
                    Agendar Cita
                </button>
                <button class="btn btn-outline w-full justify-start">
                    <i class="bi bi-prescription2 mr-2"></i>
                    Ver Recetas
                </button>
                <button class="btn btn-outline w-full justify-start">
                    <i class="bi bi-cash-coin mr-2"></i>
                    Historial Pagos
                </button>
            </div>
        </div>

        <!-- Estado -->
        <div class="card p-6">
            <h4 class="font-bold text-gray-900 mb-4">Estado</h4>
            <div class="space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Cuenta</span>
                    <span class="badge badge-success">Activa</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Tipo</span>
                    <span class="badge badge-primary">Regular</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Última visita</span>
                    <span class="text-sm font-medium text-gray-900">05/01/2026</span>
                </div>
                <div class="flex items-center justify-between pt-3 border-t border-gray-100">
                    <span class="text-sm text-gray-600">Registro</span>
                    <span class="text-xs text-gray-500">03/02/2024</span>
                </div>
            </div>
        </div>

        <!-- Observaciones -->
        <div class="card p-6 bg-gradient-to-br from-warning-50 to-amber-50 border-warning-200">
            <h4 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
                <i class="bi bi-exclamation-triangle text-warning-600"></i>
                Alertas Médicas
            </h4>
            <div class="space-y-2">
                <div class="bg-white rounded-lg p-3 text-sm">
                    <p class="font-medium text-warning-700">Alergia: Penicilina</p>
                    <p class="text-xs text-gray-600 mt-1">Reacción severa documentada</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
