@extends('layouts.admin')

@section('title', 'Detalle del Consultorio')

@section('content')
<div class="mb-6">
    <a href="{{ route('consultorios.index') }}" class="text-medical-600 hover:text-medical-700 inline-flex items-center text-sm font-medium mb-3">
        <i class="bi bi-arrow-left mr-1"></i> Volver a Consultorios
    </a>
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h2 class="text-3xl font-display font-bold text-gray-900">Consultorio 205</h2>
            <p class="text-gray-500 mt-1">Información completa del espacio médico</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('consultorios.horarios', 1) }}" class="btn btn-outline">
                <i class="bi bi-clock mr-2"></i>
                Horarios
            </a>
            <a href="{{ route('consultorios.edit', 1) }}" class="btn btn-primary">
                <i class="bi bi-pencil mr-2"></i>
                Editar
            </a>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Columna Principal -->
    <div class="lg:col-span-2 space-y-6">
        
        <!-- Encabezado -->
        <div class="card p-0 overflow-hidden">
            <div class="bg-gradient-to-br from-medical-500 to-medical-600 p-8">
                <div class="flex items-center justify-between">
                    <div class="text-white">
                        <h3 class="text-5xl font-bold mb-2">205</h3>
                        <p class="text-white/90 text-xl mb-3">Consultorio Cardiología</p>
                        <p class="text-white/80">Piso 2 • 30 m²</p>
                    </div>
                    <span class="badge bg-success-500 text-white text-lg px-4 py-2 border-2 border-white/30">Disponible</span>
                </div>
            </div>
            
            <div class="p-6">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div class="text-center p-4 bg-gray-50 rounded-xl">
                        <p class="text-3xl font-bold text-medical-600 mb-1">143</p>
                        <p class="text-sm text-gray-500">Citas/Mes</p>
                    </div>
                    <div class="text-center p-4 bg-gray-50 rounded-xl">
                        <p class="text-3xl font-bold text-success-600 mb-1">5</p>
                        <p class="text-sm text-gray-500">Hoy</p>
                    </div>
                    <div class="text-center p-4 bg-gray-50 rounded-xl">
                        <p class="text-3xl font-bold text-warning-600 mb-1">92%</p>
                        <p class="text-sm text-gray-500">Ocupación</p>
                    </div>
                    <div class="text-center p-4 bg-gray-50 rounded-xl">
                        <p class="text-3xl font-bold text-info-600 mb-1">4</p>
                        <p class="text-sm text-gray-500">Capacidad</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Médico Asignado -->
        <div class="card p-6 border-l-4 border-l-success-500">
            <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                <i class="bi bi-person-badge text-success-600"></i>
                Médico Asignado
            </h3>
            
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 rounded-full bg-gradient-to-br from-medical-500 to-medical-600 flex items-center justify-center text-white text-2xl font-bold">
                    JP
                </div>
                <div class="flex-1">
                    <h4 class="text-xl font-bold text-gray-900">Dr. Juan Pérez</h4>
                    <p class="text-gray-600">Cardiología • MPPS: 98765</p>
                    <p class="text-sm text-gray-500 mt-1">12 años de experiencia</p>
                </div>
                <a href="{{ route('medicos.show', 1) }}" class="btn btn-sm btn-outline">
                    <i class="bi bi-eye mr-1"></i> Ver Perfil
                </a>
            </div>
        </div>

        <!-- Equipamiento -->
        <div class="card p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                <i class="bi bi-box-seam text-info-600"></i>
                Equipamiento y Servicios
            </h3>
            
            <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                <div class="flex items-center gap-2 p-3 bg-success-50 rounded-lg">
                    <i class="bi bi-check-circle-fill text-success-600"></i>
                    <span class="text-sm text-gray-700">Camilla</span>
                </div>
                <div class="flex items-center gap-2 p-3 bg-success-50 rounded-lg">
                    <i class="bi bi-check-circle-fill text-success-600"></i>
                    <span class="text-sm text-gray-700">Escritorio</span>
                </div>
                <div class="flex items-center gap-2 p-3 bg-success-50 rounded-lg">
                    <i class="bi bi-check-circle-fill text-success-600"></i>
                    <span class="text-sm text-gray-700">Computadora</span>
                </div>
                <div class="flex items-center gap-2 p-3 bg-success-50 rounded-lg">
                    <i class="bi bi-check-circle-fill text-success-600"></i>
                    <span class="text-sm text-gray-700">Sillas (4)</span>
                </div>
                <div class="flex items-center gap-2 p-3 bg-success-50 rounded-lg">
                    <i class="bi bi-check-circle-fill text-success-600"></i>
                    <span class="text-sm text-gray-700">Aire Acondicionado</span>
                </div>
                <div class="flex items-center gap-2 p-3 bg-success-50 rounded-lg">
                    <i class="bi bi-check-circle-fill text-success-600"></i>
                    <span class="text-sm text-gray-700">WiFi</span>
                </div>
                <div class="flex items-center gap-2 p-3 bg-success-50 rounded-lg">
                    <i class="bi bi-check-circle-fill text-success-600"></i>
                    <span class="text-sm text-gray-700">Lavamanos</span>
                </div>
                <div class="flex items-center gap-2 p-3 bg-success-50 rounded-lg">
                    <i class="bi bi-check-circle-fill text-success-600"></i>
                    <span class="text-sm text-gray-700">Botiquín</span>
                </div>
            </div>
        </div>

        <!-- Citas de Hoy -->
        <div class="card p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                <i class="bi bi-calendar-check text-warning-600"></i>
                Citas de Hoy
            </h3>
            
            <div class="space-y-3">
                <div class="flex items-center gap-4 p-4 bg-info-50 border-l-4 border-info-500 rounded-r-xl">
                    <div class="text-center min-w-[60px]">
                        <p class="text-xl font-bold text-info-700">08:00</p>
                        <p class="text-xs text-info-600">AM</p>
                    </div>
                    <div class="flex-1">
                        <p class="font-semibold text-gray-900">Ana Rodríguez</p>
                        <p class="text-sm text-gray-600">Control Cardiológico</p>
                    </div>
                    <span class="badge badge-info">Confirmada</span>
                </div>

                <div class="flex items-center gap-4 p-4 bg-warning-50 border-l-4 border-warning-500 rounded-r-xl">
                    <div class="text-center min-w-[60px]">
                        <p class="text-xl font-bold text-warning-700">10:30</p>
                        <p class="text-xs text-warning-600">AM</p>
                    </div>
                    <div class="flex-1">
                        <p class="font-semibold text-gray-900">Carlos Martínez</p>
                        <p class="text-sm text-gray-600">Primera Consulta</p>
                    </div>
                    <span class="badge badge-warning">Pendiente</span>
                </div>

                <div class="flex items-center gap-4 p-4 bg-success-50 border-l-4 border-success-500 rounded-r-xl opacity-60">
                    <div class="text-center min-w-[60px]">
                        <p class="text-xl font-bold text-success-700">02:00</p>
                        <p class="text-xs text-success-600">PM</p>
                    </div>
                    <div class="flex-1">
                        <p class="font-semibold text-gray-900">Lucía Sánchez</p>
                        <p class="text-sm text-gray-600">Seguimiento</p>
                    </div>
                    <span class="badge badge-success">Completada</span>
                </div>
            </div>
        </div>

        <!-- Observaciones -->
        <div class="card p-6 border-l-4 border-l-warning-500">
            <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                <i class="bi bi-chat-left-text text-warning-600"></i>
                Observaciones
            </h3>
            <div class="bg-warning-50 rounded-lg p-4">
                <p class="text-gray-700 text-sm">
                    Consultorio equipado con electrocardiografo. Temperatura ambiente recomendada: 22-24°C. Última revisión de mantenimiento: 15/12/2025.
                </p>
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="lg:col-span-1 space-y-6">
        <!-- Acciones Rápidas -->
        <div class="card p-6 sticky top-6">
            <h4 class="font-bold text-gray-900 mb-4">Acciones</h4>
            <div class="space-y-2">
                <button class="btn btn-outline w-full justify-start">
                    <i class="bi bi-calendar-plus mr-2"></i>
                    Crear Cita
                </button>
                <button class="btn btn-outline w-full justify-start">
                    <i class="bi bi-clock-history mr-2"></i>
                    Ver Agenda
                </button>
                <button class="btn btn-outline w-full justify-start">
                    <i class="bi bi-graph-up mr-2"></i>
                    Estadísticas
                </button>
                <button class="btn btn-outline w-full justify-start">
                    <i class="bi bi-tools mr-2"></i>
                    Mantenimiento
                </button>
            </div>
        </div>

        <!-- Información -->
        <div class="card p-6">
            <h4 class="font-bold text-gray-900 mb-4">Información</h4>
            <div class="space-y-3 text-sm">
                <div class="flex items-center justify-between">
                    <span class="text-gray-600">Número:</span>
                    <span class="font-medium text-gray-900">205</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-gray-600">Piso:</span>
                    <span class="font-medium text-gray-900">2</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-gray-600">Área:</span>
                    <span class="font-medium text-gray-900">30 m²</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-gray-600">Capacidad:</span>
                    <span class="font-medium text-gray-900">4 personas</span>
                </div>
                <div class="flex items-center justify-between pt-3 border-t border-gray-100">
                    <span class="text-gray-600">Estado:</span>
                    <span class="badge badge-success">Disponible</span>
                </div>
            </div>
        </div>

        <!-- Horario -->
        <div class="card p-6 bg-gradient-to-br from-medical-50 to-info-50">
            <h4 class="font-bold text-gray-900 mb-4">Horario Habitual</h4>
            <div class="space-y-2 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-600">Lun - Vie</span>
                    <span class="font-medium text-gray-900">8AM - 6PM</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Sábados</span>
                    <span class="text-gray-500">Cerrado</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Domingos</span>
                    <span class="text-gray-500">Cerrado</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
