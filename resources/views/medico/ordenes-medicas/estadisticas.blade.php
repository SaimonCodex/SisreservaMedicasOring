@extends('layouts.medico')

@section('title', 'Estadísticas - Órdenes Médicas')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-4">
            <a href="{{ route('ordenes-medicas.index') }}" class="btn btn-outline">
                <i class="bi bi-arrow-left"></i>
            </a>
            <div>
                <h1 class="text-2xl font-display font-bold text-gray-900">Estadísticas de Órdenes Médicas</h1>
                <p class="text-gray-600 mt-1">Análisis y métricas del sistema</p>
            </div>
        </div>
    </div>

    <!-- Summary Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="card p-6">
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 rounded-xl bg-blue-100 flex items-center justify-center">
                    <i class="bi bi-file-medical text-blue-600 text-3xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Total Órdenes</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $totalOrdenes }}</p>
                </div>
            </div>
        </div>

        @foreach($porTipo as $tipo)
        <div class="card p-6">
            <div class="flex items-center gap-4">
                @if($tipo->tipo_orden == 'Receta')
                <div class="w-16 h-16 rounded-xl bg-purple-100 flex items-center justify-center">
                    <i class="bi bi-prescription text-purple-600 text-3xl"></i>
                </div>
                @elseif($tipo->tipo_orden == 'Laboratorio')
                <div class="w-16 h-16 rounded-xl bg-emerald-100 flex items-center justify-center">
                    <i class="bi bi-activity text-emerald-600 text-3xl"></i>
                </div>
                @elseif($tipo->tipo_orden == 'Imagenologia')
                <div class="w-16 h-16 rounded-xl bg-amber-100 flex items-center justify-center">
                    <i class="bi bi-x-ray text-amber-600 text-3xl"></i>
                </div>
                @else
                <div class="w-16 h-16 rounded-xl bg-rose-100 flex items-center justify-center">
                    <i class="bi bi-arrow-right-circle text-rose-600 text-3xl"></i>
                </div>
                @endif
                <div>
                    <p class="text-sm text-gray-600">{{ $tipo->tipo_orden }}</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $tipo->total }}</p>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Orders by Month -->
        <div class="card p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-6 flex items-center gap-2">
                <i class="bi bi-calendar3 text-blue-600"></i>
                Órdenes por Mes (Último Año)
            </h3>
            
            <div class="space-y-3">
                @forelse($porMes as $mes)
                <div class="flex items-center gap-4">
                    <div class="flex-shrink-0 w-24">
                        <span class="text-gray-600">
                            {{ \Carbon\Carbon::create($mes->año, $mes->mes, 1)->format('M Y') }}
                        </span>
                    </div>
                    <div class="flex-1">
                        <div class="h-8 bg-gray-100 rounded-lg overflow-hidden">
                            <div class="h-full bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg flex items-center justify-end px-3" 
                                 style="width: {{ ($mes->total / $totalOrdenes) * 100 }}%;">
                                <span class="text-white font-semibold text-sm">{{ $mes->total }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <p class="text-gray-500 text-center py-8">No hay datos disponibles</p>
                @endforelse
            </div>
        </div>

        <!-- Top Active Doctors -->
        <div class="card p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-6 flex items-center gap-2">
                <i class="bi bi-trophy text-amber-600"></i>
                Médicos Más Activos (Último Mes)
            </h3>
            
            <div class="space-y-4">
                @forelse($medicosMasActivos as $index => $medicoStat)
                <div class="flex items-center gap-4">
                    <div class="flex-shrink-0 w-12 h-12 rounded-full 
                        @if($index == 0) bg-gradient-to-br from-amber-400 to-amber-600
                        @elseif($index == 1) bg-gradient-to-br from-gray-300 to-gray-500
                        @elseif($index == 2) bg-gradient-to-br from-orange-400 to-orange-600
                        @else bg-gradient-to-br from-blue-400 to-blue-600
                        @endif
                        flex items-center justify-center text-white font-bold">
                        {{ $index + 1 }}
                    </div>
                    <div class="flex-1">
                        <p class="font-semibold text-gray-900">
                            Dr. {{ $medicoStat->medico->nombre_completo ?? 'N/A' }}
                        </p>
                        <p class="text-sm text-gray-600">
                            {{ $medicoStat->total_ordenes }} {{ Str::plural('orden', $medicoStat->total_ordenes) }}
                        </p>
                    </div>
                    <div class="flex-shrink-0">
                        <span class="badge badge-info">{{ $medicoStat->total_ordenes }}</span>
                    </div>
                </div>
                @empty
                <p class="text-gray-500 text-center py-8">No hay datos disponibles</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Distribution by Type Chart -->
    <div class="card p-6">
        <h3 class="text-lg font-bold text-gray-900 mb-6 flex items-center gap-2">
            <i class="bi bi-pie-chart text-purple-600"></i>
            Distribución por Tipo de Orden
        </h3>
        
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
            @foreach($porTipo as $tipo)
            <div class="text-center p-4 border border-gray-200 rounded-lg hover:shadow-md transition-shadow">
                <div class="text-3xl font-bold 
                    @if($tipo->tipo_orden == 'Receta') text-purple-600
                    @elseif($tipo->tipo_orden == 'Laboratorio') text-emerald-600
                    @elseif($tipo->tipo_orden == 'Imagenologia') text-amber-600
                    @else text-rose-600
                    @endif
                ">
                    {{ number_format(($tipo->total / $totalOrdenes) * 100, 1) }}%
                </div>
                <p class="text-sm text-gray-600 mt-2">{{ $tipo->tipo_orden }}</p>
                <p class="text-xs text-gray-500">{{ $tipo->total }} órdenes</p>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
