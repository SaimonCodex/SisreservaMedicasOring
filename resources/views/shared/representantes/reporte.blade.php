@extends('layouts.admin')

@section('title', 'Reporte de Representantes')

@section('content')
<div class="mb-6">
    <div class="flex items-center justify-between gap-4">
        <div>
            <h2 class="text-3xl font-display font-bold text-gray-900">Reporte de Representantes</h2>
            <p class="text-gray-500 mt-1">Análisis y resumen de representantes registrados</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('representantes.exportar') }}" class="btn btn-success">
                <i class="bi bi-file-pdf mr-2"></i>
                Exportar PDF
            </a>
            <a href="{{ route('representantes.index') }}" class="btn btn-outline">
                <i class="bi bi-arrow-left mr-2"></i>
                Volver
            </a>
        </div>
    </div>
</div>

<!-- Estadísticas Generales -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
    <div class=" card p-4 border-l-4 border-l-info-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 mb-1">Total Representantes</p>
                <p class="text-2xl font-bold text-gray-900">{{ $estadisticas['total'] }}</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-info-50 flex items-center justify-center">
                <i class="bi bi-people text-info-600 text-2xl"></i>
            </div>
        </div>
    </div>

    <div class="card p-4 border-l-4 border-l-warning-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 mb-1">Con Múltiples Pacientes</p>
                <p class="text-2xl font-bold text-gray-900">{{ $estadisticas['con_multipacientes'] }}</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-warning-50 flex items-center justify-center">
                <i class="bi bi-diagram-3 text-warning-600 text-2xl"></i>
            </div>
        </div>
    </div>

    <div class="card p-4 border-l-4 border-l-success-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 mb-1">Por Parentesco</p>
                <p class="text-2xl font-bold text-gray-900">{{ $estadisticas['por_parentesco']->count() }}</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-success-50 flex items-center justify-center">
                <i class="bi bi-tag text-success-600 text-2xl"></i>
            </div>
        </div>
    </div>

    <div class="card p-4 border-l-4 border-l-medical-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 mb-1">Por Estado</p>
                <p class="text-2xl font-bold text-gray-900">{{ $estadisticas['por_estado']->count() }}</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-medical-50 flex items-center justify-center">
                <i class="bi bi-geo-alt text-medical-600 text-2xl"></i>
            </div>
        </div>
    </div>
</div>

<!-- Distribución por Parentesco -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
    <div class="card p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
            <i class="bi bi-pie-chart text-info-600"></i>
            Distribución por Parentesco
        </h3>
        
        <div class="space-y-3">
            @foreach($estadisticas['por_parentesco'] as $parentesco => $count)
            <div class="flex items-center justify-between p-3 rounded-lg bg-gray-50">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-info-100 flex items-center justify-center">
                        <i class="bi bi-person text-info-600"></i>
                    </div>
                    <span class="font-semibold text-gray-900">{{ $parentesco ?: 'Sin especificar' }}</span>
                </div>
                <span class="badge badge-info">{{ $count }}</span>
            </div>
            @endforeach
        </div>
    </div>

    <div class="card p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
            <i class="bi bi-map text-warning-600"></i>
            Distribución por Estado
        </h3>
        
        <div class="space-y-3">
            @foreach($estadisticas['por_estado']->take(10) as $estado => $count)
            <div class="flex items-center justify-between p-3 rounded-lg bg-gray-50">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-warning-100 flex items-center justify-center">
                        <i class="bi bi-geo-alt text-warning-600"></i>
                    </div>
                    <span class="font-semibold text-gray-900">{{ $estado ?: 'Sin especificar' }}</span>
                </div>
                <span class="badge badge-warning">{{ $count }}</span>
            </div>
            @endforeach
        </div>
    </div>
</div>

<!-- Lista de Representantes -->
<div class="card overflow-hidden">
    <div class="px-6 py-4 bg-gradient-to-r from-info-600 to-info-500">
        <h3 class="text-lg font-semibold text-white">Lista Completa de Representantes</h3>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-3 text-left font-semibold text-gray-900">#</th>
                    <th class="px-6 py-3 text-left font-semibold text-gray-900">Representante</th>
                    <th class="px-6 py-3 text-left font-semibold text-gray-900">Documento</th>
                    <th class="px-6 py-3 text-left font-semibold text-gray-900">Parentesco</th>
                    <th class="px-6 py-3 text-left font-semibold text-gray-900">Pacientes</th>
                    <th class="px-6 py-3 text-left font-semibold text-gray-900">Estado</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($representantes as $index => $representante)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 text-gray-900">{{ $index + 1 }}</td>
                    <td class="px-6 py-4">
                        <p class="font-semibold text-gray-900">
                            {{ $representante->primer_nombre }} {{ $representante->primer_apellido }}
                        </p>
                    </td>
                    <td class="px-6 py-4 font-mono text-info-600">
                        {{ $representante->tipo_documento }}-{{ $representante->numero_documento }}
                    </td>
                    <td class="px-6 py-4">
                        <span class="badge badge-info">{{ $representante->parentesco }}</span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="font-semibold text-gray-900">{{ $representante->pacientesEspeciales->count() }}</span>
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
