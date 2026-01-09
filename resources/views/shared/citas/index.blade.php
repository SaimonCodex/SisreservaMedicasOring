@extends('layouts.admin')

@section('title', 'Citas Médicas')

@section('content')
<div class="mb-6">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h2 class="text-3xl font-display font-bold text-gray-900">Citas Médicas</h2>
            <p class="text-gray-500 mt-1">Gestión y calendario de citas</p>
        </div>
        <a href="{{ route('citas.create') }}" class="btn btn-primary shadow-lg">
            <i class="bi bi-calendar-plus mr-2"></i>
            Agendar Cita
        </a>
    </div>
</div>

<!-- Filtros -->
<div class="card p-6 mb-6">
    <form method="GET" action="{{ route('citas.index') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4">
        <!-- Búsqueda -->
        <div>
            <label class="form-label">Buscar</label>
            <input type="text" name="buscar" class="input" placeholder="Paciente, médico..." value="{{ request('buscar') }}">
        </div>

        <!-- Fecha -->
        <div>
            <label class="form-label">Fecha</label>
            <input type="date" name="fecha" class="input" value="{{ request('fecha', date('Y-m-d')) }}">
        </div>

        <!-- Médico -->
        <div>
            <label class="form-label">Médico</label>
            <select name="medico_id" class="form-select">
                <option value="">Todos</option>
                <option value="1">Dr. Juan Pérez</option>
                <option value="2">Dra. María González</option>
            </select>
        </div>

        <!-- Estado -->
        <div>
            <label class="form-label">Estado</label>
            <select name="estado" class="form-select">
                <option value="">Todos</option>
                <option value="pendiente" {{ request('estado') == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                <option value="confirmada" {{ request('estado') == 'confirmada' ? 'selected' : '' }}>Confirmada</option>
                <option value="completada" {{ request('estado') == 'completada' ? 'selected' : '' }}>Completada</option>
                <option value="cancelada" {{ request('estado') == 'cancelada' ? 'selected' : '' }}>Cancelada</option>
            </select>
        </div>

        <!-- Botones -->
        <div class="flex gap-2">
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-funnel"></i>
            </button>
            <a href="{{ route('citas.index') }}" class="btn btn-outline">
                <i class="bi bi-x-lg"></i>
            </a>
        </div>
    </form>
</div>

<!-- Estadísticas del Día -->
<div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-6">
    <div class="card p-4 border-l-4 border-l-medical-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 mb-1">Total Hoy</p>
                <p class="text-2xl font-bold text-gray-900">58</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-medical-50 flex items-center justify-center">
                <i class="bi bi-calendar-check text-medical-600 text-2xl"></i>
            </div>
        </div>
    </div>

    <div class="card p-4 border-l-4 border-l-warning-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 mb-1">Pendientes</p>
                <p class="text-2xl font-bold text-gray-900">23</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-warning-50 flex items-center justify-center">
                <i class="bi bi-clock text-warning-600 text-2xl"></i>
            </div>
        </div>
    </div>

    <div class="card p-4 border-l-4 border-l-info-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 mb-1">Confirmadas</p>
                <p class="text-2xl font-bold text-gray-900">18</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-info-50 flex items-center justify-center">
                <i class="bi bi-check-circle text-info-600 text-2xl"></i>
            </div>
        </div>
    </div>

    <div class="card p-4 border-l-4 border-l-success-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 mb-1">Completadas</p>
                <p class="text-2xl font-bold text-gray-900">15</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-success-50 flex items-center justify-center">
                <i class="bi bi-check-all text-success-600 text-2xl"></i>
            </div>
        </div>
    </div>

    <div class="card p-4 border-l-4 border-l-danger-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 mb-1">Canceladas</p>
                <p class="text-2xl font-bold text-gray-900">2</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-danger-50 flex items-center justify-center">
                <i class="bi bi-x-circle text-danger-600 text-2xl"></i>
            </div>
        </div>
    </div>
</div>

<!-- Vista de Calendario/Lista -->
<div class="card overflow-hidden">
    <div class="border-b border-gray-200 bg-gray-50 px-6 py-3">
        <div class="flex items-center justify-between">
            <div class="flex gap-2">
                <button class="btn btn-sm bg-medical-600 text-white">
                    <i class="bi bi-list-ul mr-1"></i> Lista
                </button>
                <button class="btn btn-sm btn-outline">
                    <i class="bi bi-calendar3 mr-1"></i> Calendario
                </button>
            </div>
            <p class="text-sm font-medium text-gray-700">Citas de Hoy - {{ date('d/m/Y') }}</p>
        </div>
    </div>

    <!-- Timeline de Citas -->
    <div class="p-6">
        <div class="space-y-4">
            <!-- Cita 1 - Pendiente -->
            <div class="flex gap-4 p-4 bg-warning-50 border-l-4 border-warning-500 rounded-r-xl hover:shadow-md transition-shadow">
                <div class="text-center min-w-[80px]">
                    <p class="text-2xl font-bold text-warning-700">08:00</p>
                    <p class="text-xs text-warning-600">AM</p>
                </div>
                <div class="flex-1">
                    <div class="flex items-start justify-between mb-2">
                        <div>
                            <h4 class="font-bold text-gray-900">Ana Rodríguez</h4>
                            <p class="text-sm text-gray-600">V-18765432 • HC-2024-001</p>
                        </div>
                        <span class="badge badge-warning">Pendiente</span>
                    </div>
                    <div class="grid grid-cols-2 gap-3 text-sm">
                        <div class="flex items-center gap-2 text-gray-600">
                            <i class="bi bi-person-badge text-medical-600"></i>
                            <span>Dr. Juan Pérez - Cardiología</span>
                        </div>
                        <div class="flex items-center gap-2 text-gray-600">
                            <i class="bi bi-building text-medical-600"></i>
                            <span>Consultorio 205</span>
                        </div>
                    </div>
                    <div class="mt-3 flex gap-2">
                        <a href="{{ route('citas.show', 1) }}" class="btn btn-sm btn-outline">
                            <i class="bi bi-eye mr-1"></i> Ver
                        </a>
                        <button class="btn btn-sm btn-success">
                            <i class="bi bi-check-lg mr-1"></i> Confirmar
                        </button>
                        <a href="{{ route('citas.edit', 1) }}" class="btn btn-sm btn-ghost text-warning-600">
                            <i class="bi bi-pencil"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Cita 2 - Confirmada -->
            <div class="flex gap-4 p-4 bg-info-50 border-l-4 border-info-500 rounded-r-xl hover:shadow-md transition-shadow">
                <div class="text-center min-w-[80px]">
                    <p class="text-2xl font-bold text-info-700">09:30</p>
                    <p class="text-xs text-info-600">AM</p>
                </div>
                <div class="flex-1">
                    <div class="flex items-start justify-between mb-2">
                        <div>
                            <h4 class="font-bold text-gray-900">Carlos Martínez</h4>
                            <p class="text-sm text-gray-600">V-21234567 • HC-2023-845</p>
                        </div>
                        <span class="badge badge-info">Confirmada</span>
                    </div>
                    <div class="grid grid-cols-2 gap-3 text-sm">
                        <div class="flex items-center gap-2 text-gray-600">
                            <i class="bi bi-person-badge text-medical-600"></i>
                            <span>Dra. María González - Pediatría</span>
                        </div>
                        <div class="flex items-center gap-2 text-gray-600">
                            <i class="bi bi-building text-medical-600"></i>
                            <span>Consultorio 101</span>
                        </div>
                    </div>
                    <div class="mt-3 flex gap-2">
                        <a href="{{ route('citas.show', 2) }}" class="btn btn-sm btn-outline">
                            <i class="bi bi-eye mr-1"></i> Ver
                        </a>
                        <button class="btn btn-sm btn-success">
                            <i class="bi bi-play-fill mr-1"></i> Iniciar Consulta
                        </button>
                        <a href="{{ route('citas.edit', 2) }}" class="btn btn-sm btn-ghost text-warning-600">
                            <i class="bi bi-pencil"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Cita 3 - Completada -->
            <div class="flex gap-4 p-4 bg-success-50 border-l-4 border-success-500 rounded-r-xl hover:shadow-md transition-shadow opacity-75">
                <div class="text-center min-w-[80px]">
                    <p class="text-2xl font-bold text-success-700">11:00</p>
                    <p class="text-xs text-success-600">AM</p>
                </div>
                <div class="flex-1">
                    <div class="flex items-start justify-between mb-2">
                        <div>
                            <h4 class="font-bold text-gray-900">Lucía Sánchez</h4>
                            <p class="text-sm text-gray-600">V-15987654 • HC-2024-112</p>
                        </div>
                        <span class="badge badge-success">Completada</span>
                    </div>
                    <div class="grid grid-cols-2 gap-3 text-sm">
                        <div class="flex items-center gap-2 text-gray-600">
                            <i class="bi bi-person-badge text-medical-600"></i>
                            <span>Dra. María González - Pediatría</span>
                        </div>
                        <div class="flex items-center gap-2 text-gray-600">
                            <i class="bi bi-building text-medical-600"></i>
                            <span>Consultorio 101</span>
                        </div>
                    </div>
                    <div class="mt-3 flex gap-2">
                        <a href="{{ route('citas.show', 3) }}" class="btn btn-sm btn-outline">
                            <i class="bi bi-eye mr-1"></i> Ver Detalle
                        </a>
                        <button class="btn btn-sm btn-ghost text-medical-600">
                            <i class="bi bi-file-medical mr-1"></i> Historia Clínica
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Paginación -->
    <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
        <div class="flex items-center justify-between">
            <p class="text-sm text-gray-600">
                Mostrando <span class="font-semibold">1</span> a <span class="font-semibold">3</span> de <span class="font-semibold">58</span> citas
            </p>
            <div class="flex gap-2">
                <button class="btn btn-sm btn-outline" disabled>
                    <i class="bi bi-chevron-left"></i>
                </button>
                <button class="btn btn-sm bg-medical-600 text-white">1</button>
                <button class="btn btn-sm btn-outline">2</button>
                <button class="btn btn-sm btn-outline">3</button>
                <button class="btn btn-sm btn-outline">
                    <i class="bi bi-chevron-right"></i>
                </button>
            </div>
        </div>
    </div>
</div>
@endsection
