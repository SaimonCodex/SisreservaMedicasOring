@extends('layouts.admin')

@section('title', 'Estadísticas del Sistema')

@section('content')
<div class="mb-6">
    <a href="{{ route('configuracion.index') }}" class="text-medical-600 hover:text-medical-700 inline-flex items-center text-sm font-medium mb-3">
        <i class="bi bi-arrow-left mr-1"></i> Volver a Configuración
    </a>
    <h2 class="text-3xl font-display font-bold text-gray-900">Estadísticas del Sistema</h2>
    <p class="text-gray-500 mt-1">Métricas y análisis de rendimiento</p>
</div>

<!-- Período de Análisis -->
<div class="card p-6 mb-6">
    <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div>
            <label class="form-label">Fecha Inicio</label>
            <input type="date" name="fecha_inicio" class="input" value="{{ date('Y-m-01') }}">
        </div>
        <div>
            <label class="form-label">Fecha Fin</label>
            <input type="date" name="fecha_fin" class="input" value="{{ date('Y-m-d') }}">
        </div>
        <div>
            <label class="form-label">Tipo de Reporte</label>
            <select name="tipo" class="form-select">
                <option value="general" selected>General</option>
                <option value="medicos">Médicos</option>
                <option value="pacientes">Pacientes</option>
                <option value="citas">Citas</option>
                <option value="financiero">Financiero</option>
            </select>
        </div>
        <div class="flex items-end gap-2">
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-search mr-1"></i> Generar
            </button>
            <button type="button" class="btn btn-outline">
                <i class="bi bi-download"></i>
            </button>
        </div>
    </form>
</div>

<!-- KPIs Principales -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
    <div class="card p-6 border-l-4 border-l-medical-500">
        <div class="flex items-center justify-between mb-3">
            <div class="w-12 h-12 rounded-xl bg-medical-50 flex items-center justify-center">
                <i class="bi bi-calendar-check text-medical-600 text-2xl"></i>
            </div>
            <span class="text-xs text-success-600 font-medium flex items-center gap-1">
                <i class="bi bi-arrow-up"></i> 12%
            </span>
        </div>
        <p class="text-sm text-gray-500 mb-1">Total Citas</p>
        <p class="text-3xl font-bold text-gray-900">1,847</p>
        <p class="text-xs text-gray-500 mt-1">Este mes</p>
    </div>

    <div class="card p-6 border-l-4 border-l-success-500">
        <div class="flex items-center justify-between mb-3">
            <div class="w-12 h-12 rounded-xl bg-success-50 flex items-center justify-center">
                <i class="bi bi-people text-success-600 text-2xl"></i>
            </div>
            <span class="text-xs text-success-600 font-medium flex items-center gap-1">
                <i class="bi bi-arrow-up"></i> 8%
            </span>
        </div>
        <p class="text-sm text-gray-500 mb-1">Pacientes Nuevos</p>
        <p class="text-3xl font-bold text-gray-900">287</p>
        <p class="text-xs text-gray-500 mt-1">Este mes</p>
    </div>

    <div class="card p-6 border-l-4 border-l-warning-500">
        <div class="flex items-center justify-between mb-3">
            <div class="w-12 h-12 rounded-xl bg-warning-50 flex items-center justify-center">
                <i class="bi bi-cash-stack text-warning-600 text-2xl"></i>
            </div>
            <span class="text-xs text-success-600 font-medium flex items-center gap-1">
                <i class="bi bi-arrow-up"></i> 15%
            </span>
        </div>
        <p class="text-sm text-gray-500 mb-1">Ingresos</p>
        <p class="text-3xl font-bold text-gray-900">$45.8K</p>
        <p class="text-xs text-gray-500 mt-1">Este mes</p>
    </div>

    <div class="card p-6 border-l-4 border-l-info-500">
        <div class="flex items-center justify-between mb-3">
            <div class="w-12 h-12 rounded-xl bg-info-50 flex items-center justify-center">
                <i class="bi bi-star text-info-600 text-2xl"></i>
            </div>
            <span class="text-xs text-success-600 font-medium flex items-center gap-1">
                <i class="bi bi-arrow-up"></i> 3%
            </span>
        </div>
        <p class="text-sm text-gray-500 mb-1">Satisfacción</p>
        <p class="text-3xl font-bold text-gray-900">4.8</p>
        <p class="text-xs text-gray-500 mt-1">Promedio</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    <!-- Gráfico de Citas -->
    <div class="card p-6">
        <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
            <i class="bi bi-graph-up text-medical-600"></i>
            Tendencia de Citas
        </h3>
        <div class="h-64 flex items-center justify-center bg-gradient-to-br from-medical-50 to-info-50 rounded-xl">
            <div class="text-center">
                <i class="bi bi-bar-chart text-6xl text-medical-300 mb-3"></i>
                <p class="text-sm text-gray-500">Gráfico de líneas</p>
                <p class="text-xs text-gray-400">Últimos 30 días</p>
            </div>
        </div>
    </div>

    <!-- Distribución por Especialidad -->
    <div class="card p-6">
        <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
            <i class="bi bi-pie-chart text-success-600"></i>
            Citas por Especialidad
        </h3>
        <div class="space-y-3">
            <div>
                <div class="flex items-center justify-between mb-2 text-sm">
                    <span class="text-gray-700">Cardiología</span>
                    <span class="font-medium text-gray-900">385 (32%)</span>
                </div>
                <div class="w-full bg-gray-100 rounded-full h-2">
                    <div class="bg-medical-500 h-2 rounded-full" style="width: 32%"></div>
                </div>
            </div>
            <div>
                <div class="flex items-center justify-between mb-2 text-sm">
                    <span class="text-gray-700">Pediatría</span>
                    <span class="font-medium text-gray-900">298 (25%)</span>
                </div>
                <div class="w-full bg-gray-100 rounded-full h-2">
                    <div class="bg-success-500 h-2 rounded-full" style="width: 25%"></div>
                </div>
            </div>
            <div>
                <div class="flex items-center justify-between mb-2 text-sm">
                    <span class="text-gray-700">Traumatología</span>
                    <span class="font-medium text-gray-900">245 (20%)</span>
                </div>
                <div class="w-full bg-gray-100 rounded-full h-2">
                    <div class="bg-warning-500 h-2 rounded-full" style="width: 20%"></div>
                </div>
            </div>
            <div>
                <div class="flex items-center justify-between mb-2 text-sm">
                    <span class="text-gray-700">Medicina General</span>
                    <span class="font-medium text-gray-900">198 (17%)</span>
                </div>
                <div class="w-full bg-gray-100 rounded-full h-2">
                    <div class="bg-info-500 h-2 rounded-full" style="width: 17%"></div>
                </div>
            </div>
            <div>
                <div class="flex items-center justify-between mb-2 text-sm">
                    <span class="text-gray-700">Otras</span>
                    <span class="font-medium text-gray-900">72 (6%)</span>
                </div>
                <div class="w-full bg-gray-100 rounded-full h-2">
                    <div class="bg-gray-400 h-2 rounded-full" style="width: 6%"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Tablas de Detalle -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Top Médicos -->
    <div class="card p-6">
        <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
            <i class="bi bi-trophy text-warning-600"></i>
            Médicos con Más Consultas
        </h3>
        <div class="space-y-3">
            <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg">
                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-medical-500 to-medical-600 flex items-center justify-center text-white font-bold">
                    1
                </div>
                <div class="flex-1">
                    <p class="font-semibold text-gray-900">Dr. Juan Pérez</p>
                    <p class="text-xs text-gray-500">Cardiología</p>
                </div>
                <span class="badge badge-success">143</span>
            </div>
            <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg">
                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-success-500 to-success-600 flex items-center justify-center text-white font-bold">
                    2
                </div>
                <div class="flex-1">
                    <p class="font-semibold text-gray-900">Dra. María González</p>
                    <p class="text-xs text-gray-500">Pediatría</p>
                </div>
                <span class="badge badge-success">128</span>
            </div>
            <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg">
                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-warning-500 to-warning-600 flex items-center justify-center text-white font-bold">
                    3
                </div>
                <div class="flex-1">
                    <p class="font-semibold text-gray-900">Dr. Carlos López</p>
                    <p class="text-xs text-gray-500">Traumatología</p>
                </div>
                <span class="badge badge-success">115</span>
            </div>
        </div>
    </div>

    <!-- Horarios Pico -->
    <div class="card p-6">
        <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
            <i class="bi bi-clock text-info-600"></i>
            Horarios con Mayor Demanda
        </h3>
        <div class="space-y-3">
            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                <div>
                    <p class="font-semibold text-gray-900">08:00 - 10:00 AM</p>
                    <p class="text-xs text-gray-500">Horario matutino</p>
                </div>
                <div class="text-right">
                    <p class="text-2xl font-bold text-medical-600">245</p>
                    <p class="text-xs text-gray-500">citas</p>
                </div>
            </div>
            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                <div>
                    <p class="font-semibold text-gray-900">02:00 - 04:00 PM</p>
                    <p class="text-xs text-gray-500">Horario vespertino</p>
                </div>
                <div class="text-right">
                    <p class="text-2xl font-bold text-success-600">198</p>
                    <p class="text-xs text-gray-500">citas</p>
                </div>
            </div>
            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                <div>
                    <p class="font-semibold text-gray-900">10:00 - 12:00 PM</p>
                    <p class="text-xs text-gray-500">Media mañana</p>
                </div>
                <div class="text-right">
                    <p class="text-2xl font-bold text-warning-600">176</p>
                    <p class="text-xs text-gray-500">citas</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
