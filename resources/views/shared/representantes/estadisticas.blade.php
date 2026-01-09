@extends('layouts.admin')

@section('title', 'Estadísticas de Representantes')

@section('content')
<div class="mb-6">
    <div class="flex items-center justify-between gap-4">
        <div>
            <h2 class="text-3xl font-display font-bold text-gray-900">Estadísticas de Representantes</h2>
            <p class="text-gray-500 mt-1">Análisis detallado y métricas</p>
        </div>
        <a href="{{ route('representantes.index') }}" class="btn btn-outline">
            <i class="bi bi-arrow-left mr-2"></i>
            Volver
        </a>
    </div>
</div>

<!-- Métricas Principales -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
    <div class="card p-6 border-l-4 border-l-info-500">
        <div class="flex items-center justify-between mb-3">
            <i class="bi bi-people text-3xl text-info-600"></i>
            <span class="text-xs text-gray-500">Total</span>
        </div>
        <p class="text-3xl font-bold text-gray-900 mb-1">{{ $totalRepresentantes }}</p>
        <p class="text-sm text-gray-500">Representantes Registrados</p>
    </div>

    <div class="card p-6 border-l-4 border-l-success-500">
        <div class="flex items-center justify-between mb-3">
            <i class="bi bi-tags text-3xl text-success-600"></i>
            <span class="text-xs text-gray-500">Categorías</span>
        </div>
       <p class="text-3xl font-bold text-gray-900 mb-1">{{ $porParentesco->count() }}</p>
        <p class="text-sm text-gray-500">Tipos de Parentesco</p>
    </div>

    <div class="card p-6 border-l-4 border-l-warning-500">
        <div class="flex items-center justify-between mb-3">
            <i class="bi bi-geo-alt text-3xl text-warning-600"></i>
            <span class="text-xs text-gray-500">Ubicaciones</span>
        </div>
        <p class="text-3xl font-bold text-gray-900 mb-1">{{ $porEstado->count() }}</p>
        <p class="text-sm text-gray-500">Estados Diferentes</p>
    </div>

    <div class="card p-6 border-l-4 border-l-medical-500">
        <div class="flex items-center justify-between mb-3">
            <i class="bi bi-diagram-3 text-3xl text-medical-600"></i>
            <span class="text-xs text-gray-500">Promedio</span>
        </div>
        <p class="text-3xl font-bold text-gray-900 mb-1">{{ number_format($pacientesPorRepresentante->avg('pacientes_especiales_count'), 1) }}</p>
        <p class="text-sm text-gray-500">Pacientes por Representante</p>
    </div>
</div>

<!-- Gráficos -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    <!-- Por Parentesco -->
    <div class="card p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
            <i class="bi bi-bar-chart text-info-600"></i>
            Distribución por Parentesco
        </h3>
        
        <div class="space-y-3">
            @foreach($porParentesco as $item)
            @php
                $percentage = $totalRepresentantes > 0 ? ($item->total / $totalRepresentantes) * 100 : 0;
            @endphp
            <div>
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm font-medium text-gray-900">{{ $item->parentesco ?: 'Sin especificar' }}</span>
                    <span class="text-sm font-semibold text-info-600">{{ $item->total }} ({{ number_format($percentage, 1) }}%)</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2.5">
                    <div class="bg-gradient-to-r from-info-500 to-info-600 h-2.5 rounded-full transition-all duration-500" 
                         style="width: {{ $percentage }}%"></div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Por Estado -->
    <div class="card p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
            <i class="bi bi-map text-warning-600"></i>
            Top 10 Estados
        </h3>
        
        <div class="space-y-3">
            @foreach($porEstado->take(10) as $item)
            @php
                $percentage = $totalRepresentantes > 0 ? ($item->total / $totalRepresentantes) * 100 : 0;
            @endphp
            <div>
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm font-medium text-gray-900">{{ $item->estado->estado ?? 'N/A' }}</span>
                    <span class="text-sm font-semibold text-warning-600">{{ $item->total }} ({{ number_format($percentage, 1) }}%)</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2.5">
                    <div class="bg-gradient-to-r from-warning-500 to-warning-600 h-2.5 rounded-full transition-all duration-500" 
                         style="width: {{ $percentage }}%"></div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

<!-- Tabla de Representantes con Más Pacientes -->
<div class="card overflow-hidden">
    <div class="px-6 py-4 bg-gradient-to-r from-medical-600 to-medical-500">
        <h3 class="text-lg font-semibold text-white">Representantes con Más Pacientes Asignados</h3>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-3 text-left font-semibold text-gray-900">Representante</th>
                    <th class="px-6 py-3 text-left font-semibold text-gray-900">Parentesco</th>
                    <th class="px-6 py-3 text-center font-semibold text-gray-900">Pacientes</th>
                    <th class="px-6 py-3 text-left font-semibold text-gray-900">Estado</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($pacientesPorRepresentante->sortByDesc('pacientes_especiales_count')->take(10) as $representante)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-medical-500 to-medical-600 flex items-center justify-center text-white font-bold">
                                {{ strtoupper(substr($representante->primer_nombre, 0, 1) . substr($representante->primer_apellido, 0, 1)) }}
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900">
                                    {{ $representante->primer_nombre }} {{ $representante->primer_apellido }}
                                </p>
                                <p class="text-xs text-gray-500">
                                    {{ $representante->tipo_documento }}-{{ $representante->numero_documento }}
                                </p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="badge badge-info">{{ $representante->parentesco ?? 'N/A' }}</span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <span class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-medical-100 text-medical-700 font-bold">
                            {{ $representante->pacientes_especiales_count }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        {{ $representante->estado->estado ?? 'N/A' }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
