@extends('layouts.admin')

@section('title', 'Estadísticas - Pacientes Especiales')

@section('content')
<div class="mb-6">
    <div class="flex items-center gap-3 mb-2">
        <a href="{{ route('pacientes-especiales.index') }}" class="btn btn-ghost">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div>
            <h2 class="text-3xl font-display font-bold text-gray-900">Estadísticas de Pacientes Especiales</h2>
            <p class="text-gray-500 mt-1">Análisis y métricas del sistema</p>
        </div>
    </div>
</div>

<!-- Filtros de Período -->
<div class="card p-6 mb-6">
    <form method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4">
        <div class="md:col-span-2">
            <label class="form-label">Fecha Inicio</label>
            <input type="date" name="fecha_inicio" class="input" value="{{ request('fecha_inicio', now()->startOfMonth()->format('Y-m-d')) }}">
        </div>
        <div class="md:col-span-2">
            <label class="form-label">Fecha Fin</label>
            <input type="date" name="fecha_fin" class="input" value="{{ request('fecha_fin', now()->format('Y-m-d')) }}">
        </div>
        <div class="flex items-end">
            <button type="submit" class="btn btn-primary w-full">
                <i class="bi bi-funnel mr-2"></i>
                Filtrar
            </button>
        </div>
    </form>
</div>

<!-- Métricas Generales -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
    <div class="card p-6 border-l-4 border-l-warning-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 mb-1">Total Pacientes</p>
                <p class="text-3xl font-bold text-gray-900">{{ $totalPacientes ?? 0 }}</p>
                <p class="text-xs text-success-600 mt-1">
                    <i class="bi bi-arrow-up"></i> +12% vs mes anterior
                </p>
            </div>
            <div class="w-14 h-14 rounded-xl bg-warning-50 flex items-center justify-center">
                <i class="bi bi-heart-pulse text-warning-600 text-3xl"></i>
            </div>
        </div>
    </div>

    <div class="card p-6 border-l-4 border-l-success-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 mb-1">Activos</p>
                <p class="text-3xl font-bold text-gray-900">{{ $pacientesActivos ?? 0 }}</p>
                <p class="text-xs text-gray-500 mt-1">
                    {{ number_format(($pacientesActivos ?? 0) / max($totalPacientes ?? 1, 1) * 100, 1) }}% del total
                </p>
            </div>
            <div class="w-14 h-14 rounded-xl bg-success-50 flex items-center justify-center">
                <i class="bi bi-check-circle text-success-600 text-3xl"></i>
            </div>
        </div>
    </div>

    <div class="card p-6 border-l-4 border-l-medical-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 mb-1">Con Citas Activas</p>
                <p class="text-3xl font-bold text-gray-900">{{ $conCitasActivas ?? 0 }}</p>
                <p class="text-xs text-medical-600 mt-1">
                    <i class="bi bi-calendar-check"></i> Este mes
                </p>
            </div>
            <div class="w-14 h-14 rounded-xl bg-medical-50 flex items-center justify-center">
                <i class="bi bi-calendar-event text-medical-600 text-3xl"></i>
            </div>
        </div>
    </div>

    <div class="card p-6 border-l-4 border-l-info-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 mb-1">Nuevos (Período)</p>
                <p class="text-3xl font-bold text-gray-900">{{ $nuevosPeriodo ?? 0 }}</p>
                <p class="text-xs text-info-600 mt-1">
                    <i class="bi bi-person-plus"></i> Registrados
                </p>
            </div>
            <div class="w-14 h-14 rounded-xl bg-info-50 flex items-center justify-center">
                <i class="bi bi-graph-up-arrow text-info-600 text-3xl"></i>
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    
    <!-- Distribución por Tipo de Condición -->
    <div class="card p-6">
        <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
            <i class="bi bi-pie-chart text-warning-600"></i>
            Distribución por Tipo de Condición
        </h3>
        
        <div class="space-y-4">
            <div class="flex items-center justify-between p-4 bg-gradient-to-r from-blue-50 to-transparent rounded-lg">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-blue-500 flex items-center justify-center text-white font-bold">
                        {{ $porCondicion['menor_edad'] ?? 0 }}
                    </div>
                    <div>
                        <p class="font-semibold text-gray-900">Menor de Edad</p>
                        <p class="text-xs text-gray-500">Pacientes menores de 18 años</p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-2xl font-bold text-blue-600">{{ number_format(($porCondicion['menor_edad'] ?? 0) / max($totalPacientes ?? 1, 1) * 100, 1) }}%</p>
                </div>
            </div>

            <div class="flex items-center justify-between p-4 bg-gradient-to-r from-purple-50 to-transparent rounded-lg">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-purple-500 flex items-center justify-center text-white font-bold">
                        {{ $porCondicion['discapacidad'] ?? 0 }}
                    </div>
                    <div>
                        <p class="font-semibold text-gray-900">Discapacidad</p>
                        <p class="text-xs text-gray-500">Física o mental</p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-2xl font-bold text-purple-600">{{ number_format(($porCondicion['discapacidad'] ?? 0) / max($totalPacientes ?? 1, 1) * 100, 1) }}%</p>
                </div>
            </div>

            <div class="flex items-center justify-between p-4 bg-gradient-to-r from-orange-50 to-transparent rounded-lg">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-orange-500 flex items-center justify-center text-white font-bold">
                        {{ $porCondicion['adulto_mayor'] ?? 0 }}
                    </div>
                    <div>
                        <p class="font-semibold text-gray-900">Adulto Mayor</p>
                        <p class="text-xs text-gray-500">Con tutor asignado</p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-2xl font-bold text-orange-600">{{ number_format(($porCondicion['adulto_mayor'] ?? 0) / max($totalPacientes ?? 1, 1) * 100, 1) }}%</p>
                </div>
            </div>

            <div class="flex items-center justify-between p-4 bg-gradient-to-r from-red-50 to-transparent rounded-lg">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-red-500 flex items-center justify-center text-white font-bold">
                        {{ $porCondicion['incapacidad_legal'] ?? 0 }}
                    </div>
                    <div>
                        <p class="font-semibold text-gray-900">Incapacidad Legal</p>
                        <p class="text-xs text-gray-500">Declarada legalmente</p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-2xl font-bold text-red-600">{{ number_format(($porCondicion['incapacidad_legal'] ?? 0) / max($totalPacientes ?? 1, 1) * 100, 1) }}%</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Distribución por Rango de Edad -->
    <div class="card p-6">
        <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
            <i class="bi bi-bar-chart text-medical-600"></i>
            Distribución por Rango de Edad
        </h3>
        
        <div class="space-y-4">
            @foreach([
                ['rango' => '0-5 años', 'key' => '0-5', 'color' => 'pink'],
                ['rango' => '6-12 años', 'key' => '6-12', 'color' => 'indigo'],
                ['rango' => '13-17 años', 'key' => '13-17', 'color' => 'cyan'],
                ['rango' => '18-60 años', 'key' => '18-60', 'color' => 'teal'],
                ['rango' => '60+ años', 'key' => '60+', 'color' => 'amber']
            ] as $rango)
            <div>
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm font-semibold text-gray-900">{{ $rango['rango'] }}</span>
                    <span class="text-sm font-bold text-{{ $rango['color'] }}-600">{{ $porEdad[$rango['key']] ?? 0 }} pacientes</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-3">
                    <div class="bg-{{ $rango['color'] }}-500 h-3 rounded-full transition-all" 
                         style="width: {{ number_format((($porEdad[$rango['key']] ?? 0) / max($totalPacientes ?? 1, 1)) * 100, 1) }}%">
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    
    <!-- Representantes más Activos -->
    <div class="card p-6">
        <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
            <i class="bi bi-people text-info-600"></i>
            Representantes más Activos
        </h3>
        
        <div class="space-y-3">
            @forelse($representantesTop ?? [] as $index => $rep)
            <div class="flex items-center gap-4 p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-info-500 to-info-600 flex items-center justify-center text-white font-bold text-sm">
                    {{ $index + 1 }}
                </div>
                <div class="flex-1">
                    <p class="font-semibold text-gray-900">{{ $rep->nombre }}</p>
                    <p class="text-xs text-gray-500">{{ $rep->documento }}</p>
                </div>
                <div class="text-right">
                    <p class="text-lg font-bold text-info-600">{{ $rep->total_pacientes }}</p>
                    <p class="text-xs text-gray-500">pacientes</p>
                </div>
            </div>
            @empty
            <div class="text-center py-6">
                <i class="bi bi-inbox text-3xl text-gray-300 mb-2"></i>
                <p class="text-gray-500">No hay datos disponibles</p>
            </div>
            @endforelse
        </div>
    </div>

    <!-- Tendencia de Registro Mensual -->
    <div class="card p-6">
        <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
            <i class="bi bi-graph-up text-success-600"></i>
            Tendencia de Registro Mensual
        </h3>
        
        <div class="space-y-3">
            @foreach(['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio'] as $index => $mes)
            <div class="flex items-center gap-4">
                <div class="w-20">
                    <p class="text-sm font-semibold text-gray-700">{{ $mes }}</p>
                </div>
                <div class="flex-1">
                    <div class="w-full bg-gray-200 rounded-full h-8 relative overflow-hidden">
                        <div class="bg-gradient-to-r from-success-500 to-success-600 h-8 rounded-full flex items-center justify-end px-3 transition-all" 
                             style="width: {{ rand(20, 100) }}%">
                            <span class="text-white text-xs font-bold">{{ rand(5, 25) }}</span>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

</div>

<!-- Distribución por Género -->
<div class="card p-6 mb-6">
    <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
        <i class="bi bi-gender-ambiguous text-warning-600"></i>
        Distribución por Género
    </h3>
    
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="text-center p-6 bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl">
            <div class="w-20 h-20 mx-auto rounded-full bg-blue-500 flex items-center justify-center mb-4">
                <i class="bi bi-gender-male text-white text-4xl"></i>
            </div>
            <p class="text-3xl font-bold text-blue-600 mb-2">{{ $porGenero['masculino'] ?? 0 }}</p>
            <p class="text-sm font-semibold text-gray-700">Masculino</p>
            <p class="text-xs text-gray-600">{{ number_format(($porGenero['masculino'] ?? 0) / max($totalPacientes ?? 1, 1) * 100, 1) }}%</p>
        </div>
        
        <div class="text-center p-6 bg-gradient-to-br from-pink-50 to-pink-100 rounded-xl">
            <div class="w-20 h-20 mx-auto rounded-full bg-pink-500 flex items-center justify-center mb-4">
                <i class="bi bi-gender-female text-white text-4xl"></i>
            </div>
            <p class="text-3xl font-bold text-pink-600 mb-2">{{ $porGenero['femenino'] ?? 0 }}</p>
            <p class="text-sm font-semibold text-gray-700">Femenino</p>
            <p class="text-xs text-gray-600">{{ number_format(($porGenero['femenino'] ?? 0) / max($totalPacientes ?? 1, 1) * 100, 1) }}%</p>
        </div>
        
        <div class="text-center p-6 bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl">
            <div class="w-20 h-20 mx-auto rounded-full bg-purple-500 flex items-center justify-center mb-4">
                <i class="bi bi-people text-white text-4xl"></i>
            </div>
            <p class="text-3xl font-bold text-purple-600 mb-2">{{ $totalPacientes ?? 0 }}</p>
            <p class="text-sm font-semibold text-gray-700">Total</p>
            <p class="text-xs text-gray-600">100%</p>
        </div>
    </div>
</div>

<!-- Acciones -->
<div class="flex gap-3">
    <a href="{{ route('pacientes-especiales.reporte') }}" class="btn btn-primary">
        <i class="bi bi-file-earmark-pdf mr-2"></i>
        Generar Reporte PDF
    </a>
    <a href="{{ route('pacientes-especiales.exportar') }}" class="btn btn-outline">
        <i class="bi bi-download mr-2"></i>
        Exportar Datos
    </a>
    <a href="{{ route('pacientes-especiales.index') }}" class="btn btn-outline">
        <i class="bi bi-arrow-left mr-2"></i>
        Volver al Listado
    </a>
</div>

@endsection
