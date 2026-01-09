@extends('layouts.admin')

@section('title', 'Detalle de Especialidad')

@section('content')
<div class="mb-6">
    <a href="{{ route('especialidades.index') }}" class="text-medical-600 hover:text-medical-700 inline-flex items-center text-sm font-medium mb-3">
        <i class="bi bi-arrow-left mr-1"></i> Volver a Especialidades
    </a>
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h2 class="text-3xl font-display font-bold text-gray-900">Cardiología</h2>
            <p class="text-gray-500 mt-1">Detalle completo de la especialidad médica</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('especialidades.edit', 1) }}" class="btn btn-primary">
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
                <div class="flex items-center gap-6">
                    <div class="w-20 h-20 rounded-2xl bg-white/20 backdrop-blur-sm flex items-center justify-center text-white text-4xl border-4 border-white/30">
                        <i class="bi bi-heart-pulse"></i>
                    </div>
                    <div class="text-white flex-1">
                        <h3 class="text-3xl font-bold mb-2">Cardiología</h3>
                        <p class="text-white/90 text-lg">Enfermedades del sistema cardiovascular</p>
                        <div class="flex gap-2 mt-3">
                            <span class="badge bg-white/20 text-white border border-white/30">Activa</span>
                            <span class="badge bg-white/20 text-white border border-white/30">Código: CARD-01</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="p-6">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div class="text-center p-4 bg-gray-50 rounded-xl">
                        <p class="text-3xl font-bold text-medical-600 mb-1">5</p>
                        <p class="text-sm text-gray-500">Médicos</p>
                    </div>
                    <div class="text-center p-4 bg-gray-50 rounded-xl">
                        <p class="text-3xl font-bold text-success-600 mb-1">143</p>
                        <p class="text-sm text-gray-500">Citas/Mes</p>
                    </div>
                    <div class="text-center p-4 bg-gray-50 rounded-xl">
                        <p class="text-3xl font-bold text-warning-600 mb-1">18</p>
                        <p class="text-sm text-gray-500">Pendientes</p>
                    </div>
                    <div class="text-center p-4 bg-gray-50 rounded-xl">
                        <p class="text-3xl font-bold text-info-600 mb-1">92%</p>
                        <p class="text-sm text-gray-500">Ocupación</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Información General -->
        <div class="card p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                <i class="bi bi-info-circle text-medical-600"></i>
                Información General
            </h3>
            
            <div class="space-y-4">
                <div>
                    <p class="text-sm text-gray-500 mb-2">Descripción</p>
                    <p class="text-gray-700 leading-relaxed">
                        La cardiología es la rama de la medicina que se ocupa de las afecciones del corazón y del aparato circulatorio. Se incluye dentro de las especialidades médicas, es decir que no abarca la cirugía, ocupándose de ese aspecto la cirugía cardiovascular.
                    </p>
                </div>

                <div class="grid grid-cols-2 gap-4 pt-4 border-t border-gray-100">
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Duración de Cita</p>
                        <p class="font-semibold text-gray-900">30 minutos</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Prioridad</p>
                        <p class="font-semibold text-gray-900">Alta</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Médicos de la Especialidad -->
        <div class="card p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                    <i class="bi bi-person-badge text-success-600"></i>
                    Médicos Asignados
                </h3>
                <span class="badge badge-primary">5 médicos</span>
            </div>
            
            <div class="space-y-3">
                <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors">
                    <div class="w-12 h-12 rounded-full bg-gradient-to-br from-medical-500 to-medical-600 flex items-center justify-center text-white font-bold">
                        JP
                    </div>
                    <div class="flex-1">
                        <p class="font-semibold text-gray-900">Dr. Juan Pérez</p>
                        <p class="text-sm text-gray-600">MPPS: 98765 • 12 años exp.</p>
                    </div>
                    <a href="{{ route('medicos.show', 1) }}" class="btn btn-sm btn-outline">
                        <i class="bi bi-eye mr-1"></i> Ver
                    </a>
                </div>

                <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors">
                    <div class="w-12 h-12 rounded-full bg-gradient-to-br from-success-500 to-success-600 flex items-center justify-center text-white font-bold">
                        CL
                    </div>
                    <div class="flex-1">
                        <p class="font-semibold text-gray-900">Dr. Carlos López</p>
                        <p class="text-sm text-gray-600">MPPS: 76543 • 8 años exp.</p>
                    </div>
                    <a href="{{ route('medicos.show', 3) }}" class="btn btn-sm btn-outline">
                        <i class="bi bi-eye mr-1"></i> Ver
                    </a>
                </div>

                <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors">
                    <div class="w-12 h-12 rounded-full bg-gradient-to-br from-warning-500 to-warning-600 flex items-center justify-center text-white font-bold">
                        AR
                    </div>
                    <div class="flex-1">
                        <p class="font-semibold text-gray-900">Dra. Ana Ramírez</p>
                        <p class="text-sm text-gray-600">MPPS: 65432 • 15 años exp.</p>
                    </div>
                    <a href="{{ route('medicos.show', 4) }}" class="btn btn-sm btn-outline">
                        <i class="bi bi-eye mr-1"></i> Ver
                    </a>
                </div>
            </div>
        </div>

        <!-- Requisitos -->
        <div class="card p-6 border-l-4 border-l-warning-500">
            <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                <i class="bi bi-clipboard-check text-warning-600"></i>
                Requisitos para Citas
            </h3>
            <div class="bg-warning-50 rounded-lg p-4">
                <ul class="space-y-2 text-sm text-gray-700">
                    <li class="flex items-start gap-2">
                        <i class="bi bi-check-circle-fill text-warning-600 mt-0.5"></i>
                        <span>Traer electrocardiograma reciente (si aplica)</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <i class="bi bi-check-circle-fill text-warning-600 mt-0.5"></i>
                        <span>Lista de medicamentos actuales</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <i class="bi bi-check-circle-fill text-warning-600 mt-0.5"></i>
                        <span>Resultados de exámenes de laboratorio previos</span>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Estadísticas Mensuales -->
        <div class="card p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                <i class="bi bi-graph-up text-info-600"></i>
                Estadísticas del Mes
            </h3>
            
            <div class="grid grid-cols-2 gap-4">
                <div class="p-4 bg-success-50 rounded-xl">
                    <p class="text-sm text-gray-600 mb-1">Citas Completadas</p>
                    <p class="text-2xl font-bold text-success-600">98</p>
                </div>
                <div class="p-4 bg-warning-50 rounded-xl">
                    <p class="text-sm text-gray-600 mb-1">Citas Pendientes</p>
                    <p class="text-2xl font-bold text-warning-600">18</p>
                </div>
                <div class="p-4 bg-danger-50 rounded-xl">
                    <p class="text-sm text-gray-600 mb-1">Canceladas</p>
                    <p class="text-2xl font-bold text-danger-600">7</p>
                </div>
                <div class="p-4 bg-info-50 rounded-xl">
                    <p class="text-sm text-gray-600 mb-1">No Asistieron</p>
                    <p class="text-2xl font-bold text-info-600">3</p>
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
                <button class="btn btn-outline w-full justify-start">
                    <i class="bi bi-calendar-plus mr-2"></i>
                    Nueva Cita
                </button>
                <button class="btn btn-outline w-full justify-start">
                    <i class="bi bi-person-plus mr-2"></i>
                    Asignar Médico
                </button>
                <button class="btn btn-outline w-full justify-start">
                    <i class="bi bi-graph-up mr-2"></i>
                    Ver Reportes
                </button>
            </div>
        </div>

        <!-- Estado -->
        <div class="card p-6">
            <h4 class="font-bold text-gray-900 mb-4">Configuración</h4>
            <div class="space-y-3 text-sm">
                <div class="flex items-center justify-between">
                    <span class="text-gray-600">Estado</span>
                    <span class="badge badge-success">Activa</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-gray-600">Color</span>
                    <div class="w-6 h-6 rounded-full bg-medical-500"></div>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-gray-600">Prioridad</span>
                    <span class="badge badge-warning">Alta</span>
                </div>
                <div class="flex items-center justify-between pt-3 border-t border-gray-100">
                    <span class="text-gray-600">Creada</span>
                    <span class="text-xs text-gray-500">15/01/2024</span>
                </div>
            </div>
        </div>

        <!-- Disponibilidad Semanal -->
        <div class="card p-6 bg-gradient-to-br from-medical-50 to-info-50">
            <h4 class="font-bold text-gray-900 mb-4">Disponibilidad</h4>
            <div class="space-y-2 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-600">Lunes - Viernes</span>
                    <span class="badge badge-success text-xs">Disponible</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Horario</span>
                    <span class="text-gray-900 font-medium">8AM - 6PM</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Cupos/día</span>
                    <span class="text-gray-900 font-medium">~30 citas</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
