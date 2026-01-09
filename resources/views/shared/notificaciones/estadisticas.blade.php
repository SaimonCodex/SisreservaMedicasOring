@extends('layouts.admin')

@section('title', 'Estadísticas de Notificaciones')

@section('content')
<div class="mb-6">
    <div class="flex items-center justify-between gap-4">
        <div>
            <h2 class="text-3xl font-display font-bold text-gray-900">Estadísticas de Notificaciones</h2>
            <p class="text-gray-500 mt-1">Análisis detallado del sistema de notificaciones</p>
        </div>
        <a href="{{ route('notificaciones.index') }}" class="btn btn-outline">
            <i class="bi bi-arrow-left mr-2"></i>
            Volver
        </a>
    </div>
</div>

<!-- Métricas Principales -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
    <div class="card p-6 border-l-4 border-l-info-500">
        <div class="flex items-center justify-between mb-3">
            <i class="bi bi-bell-fill text-3xl text-info-600"></i>
            <span class="text-xs text-gray-500">Total</span>
        </div>
        <p class="text-3xl font-bold text-gray-900 mb-1">{{ $totalNotificaciones }}</p>
        <p class="text-sm text-gray-500">Notificaciones Registradas</p>
    </div>

    <div class="card p-6 border-l-4 border-l-success-500">
        <div class="flex items-center justify-between mb-3">
            <i class="bi bi-check-circle-fill text-3xl text-success-600"></i>
            <span class="text-xs text-gray-500">Enviadas</span>
        </div>
        <p class="text-3xl font-bold text-gray-900 mb-1">{{ $porEstado->where('estado_envio', 'Enviado')->first()->total ?? 0 }}</p>
        <p class="text-sm text-gray-500">Exitosamente Enviadas</p>
    </div>

    <div class="card p-6 border-l-4 border-l-danger-500">
        <div class="flex items-center justify-between mb-3">
            <i class="bi bi-x-circle-fill text-3xl text-danger-600"></i>
            <span class="text-xs text-gray-500">Fallidas</span>
        </div>
        <p class="text-3xl font-bold text-gray-900 mb-1">{{ $porEstado->where('estado_envio', 'Fallido')->first()->total ?? 0 }}</p>
        <p class="text-sm text-gray-500">Con Errores</p>
    </div>

    <div class="card p-6 border-l-4 border-l-medical-500">
        <div class="flex items-center justify-between mb-3">
            <i class="bi bi-eye-fill text-3xl text-medical-600"></i>
            <span class="text-xs text-gray-500">Leídas</span>
        </div>
        <p class="text-3xl font-bold text-gray-900 mb-1">{{ $porEstado->where('estado_envio', 'Leido')->first()->total ?? 0 }}</p>
        <p class="text-sm text-gray-500">Vistas por Usuarios</p>
    </div>
</div>

<!-- Gráficos -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    <!-- Por Tipo -->
    <div class="card p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
            <i class="bi bi-pie-chart-fill text-info-600"></i>
            Distribución por Tipo
        </h3>
        
        <div class="space-y-3">
            @foreach($porTipo as $item)
            @php
                $percentage = $totalNotificaciones > 0 ? ($item->total / $totalNotificaciones) * 100 : 0;
                $tipoLabel = str_replace('_', ' ', $item->tipo);
            @endphp
            <div>
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm font-medium text-gray-900">{{ $tipoLabel }}</span>
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

    <!-- Por Vía -->
    <div class="card p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
            <i class="bi bi-send-fill text-success-600"></i>
            Distribución por Vía de Envío
        </h3>
        
        <div class="space-y-3">
            @foreach($porVia as $item)
            @php
                $percentage = $totalNotificaciones > 0 ? ($item->total / $totalNotificaciones) * 100 : 0;
            @endphp
            <div>
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm font-medium text-gray-900">{{ $item->via }}</span>
                    <span class="text-sm font-semibold text-success-600">{{ $item->total }} ({{ number_format($percentage, 1) }}%)</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2.5">
                    <div class="bg-gradient-to-r from-success-500 to-success-600 h-2.5 rounded-full transition-all duration-500" 
                         style="width: {{ $percentage }}%"></div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

<!-- Por Estado -->
<div class="card p-6 mb-6">
    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
        <i class="bi bi-bar-chart-fill text-warning-600"></i>
        Estado de Envío
    </h3>
    
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        @foreach($porEstado as $item)
        @php
            $percentage = $totalNotificaciones > 0 ? ($item->total / $totalNotificaciones) * 100 : 0;
            $colorClass = match($item->estado_envio) {
                'Enviado' => 'success',
                'Fallido' => 'danger',
                'Leido' => 'info',
                default => 'warning'
            };
        @endphp
        <div class="p-4 rounded-lg border-2 border-{{ $colorClass }}-200 bg-{{ $colorClass }}-50">
            <div class="flex items-center justify-between mb-3">
                <span class="text-sm font-medium text-gray-900">{{ $item->estado_envio }}</span>
                <span class="badge badge-{{ $colorClass }}">{{ number_format($percentage, 1) }}%</span>
            </div>
            <p class="text-2xl font-bold text-{{ $colorClass }}-700">{{ $item->total }}</p>
            <div class="w-full bg-gray-200 rounded-full h-1.5 mt-3">
                <div class="bg-{{ $colorClass }}-600 h-1.5 rounded-full" style="width: {{ $percentage }}%"></div>
            </div>
        </div>
        @endforeach
    </div>
</div>

<!-- Tendencia Mensual -->
<div class="card p-6">
    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
        <i class="bi bi-graph-up text-medical-600"></i>
        Tendencia Mensual (Último Año)
    </h3>
    
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-3 text-left font-semibold text-gray-900">Mes</th>
                    <th class="px-6 py-3 text-center font-semibold text-gray-900">Cantidad</th>
                    <th class="px-6 py-3 text-left font-semibold text-gray-900">Tendencia</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @php
                    $meses = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
                    $maxTotal = $porMes->max('total') ?: 1;
                @endphp
                @foreach($porMes as $item)
                @php
                    $mesNombre = $meses[$item->mes - 1] ?? 'Desconocido';
                    $barWidth = ($item->total / $maxTotal) * 100;
                @endphp
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4">
                        <p class="font-semibold text-gray-900">{{ $mesNombre }} {{ $item->año }}</p>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <span class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-medical-100 text-medical-700 font-bold">
                            {{ $item->total }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="flex-1 bg-gray-200 rounded-full h-2">
                                <div class="bg-gradient-to-r from-medical-500 to-medical-600 h-2 rounded-full transition-all duration-500" 
                                     style="width: {{ $barWidth }}%"></div>
                            </div>
                            <span class="text-sm font-semibold text-medical-600 w-12">{{ number_format(($item->total / $totalNotificaciones) * 100, 1) }}%</span>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
