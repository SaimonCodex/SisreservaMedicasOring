@extends('layouts.medico')

@section('title', 'Recetas Médicas')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-4">
            <a href="{{ url('index.php/ordenes-medicas') }}" class="btn btn-outline">
                <i class="bi bi-arrow-left"></i>
            </a>
            <div>
                <h1 class="text-2xl font-display font-bold text-gray-900">Recetas Médicas</h1>
                <p class="text-gray-600 mt-1">Gestión de prescripciones y medicamentos</p>
            </div>
        </div>
        <a href="{{ url('index.php/ordenes-medicas/create?tipo=receta') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i>
            <span>Nueva Receta</span>
        </a>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="card p-4 bg-gradient-to-br from-purple-50 to-purple-100 border-purple-200">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-lg bg-purple-600 flex items-center justify-center">
                    <i class="bi bi-prescription text-white text-xl"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-purple-900">{{ $stats['total'] ?? 0 }}</p>
                    <p class="text-sm text-purple-700">Total Recetas</p>
                </div>
            </div>
        </div>
        <div class="card p-4 bg-gradient-to-br from-blue-50 to-blue-100 border-blue-200">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-lg bg-blue-600 flex items-center justify-center">
                    <i class="bi bi-clock text-white text-xl"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-blue-900">{{ $stats['activas'] ?? 0}}</p>
                    <p class="text-sm text-blue-700">Activas</p>
                </div>
            </div>
        </div>
        <div class="card p-4 bg-gradient-to-br from-emerald-50 to-emerald-100 border-emerald-200">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-lg bg-emerald-600 flex items-center justify-center">
                    <i class="bi bi-calendar-week text-white text-xl"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-emerald-900">{{ $stats['esta_semana'] ?? 0 }}</p>
                    <p class="text-sm text-emerald-700">Esta Semana</p>
                </div>
            </div>
        </div>
        <div class="card p-4 bg-gradient-to-br from-amber-50 to-amber-100 border-amber-200">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-lg bg-amber-600 flex items-center justify-center">
                    <i class="bi bi-exclamation-triangle text-white text-xl"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-amber-900">{{ $stats['vencidas'] ?? 0 }}</p>
                    <p class="text-sm text-amber-700">Por Vencer</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card p-6">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <div>
                <label class="form-label">Paciente</label>
                <input type="text" name="paciente" class="input" placeholder="Buscar..." value="{{ request('paciente') }}">
            </div>
            <div>
                <label class="form-label">Medicamento</label>
                <input type="text" name="medicamento" class="input" placeholder="Buscar..." value="{{ request('medicamento') }}">
            </div>
            <div>
                <label class="form-label">Fecha Desde</label>
                <input type="date" name="fecha_desde" class="input" value="{{ request('fecha_desde') }}">
            </div>
            <div>
                <label class="form-label">Fecha Hasta</label>
                <input type="date" name="fecha_hasta" class="input" value="{{ request('fecha_hasta') }}">
            </div>
            <div class="flex items-end gap-2">
                <button type="submit" class="btn btn-primary flex-1">
                    <i class="bi bi-search"></i>
                    Buscar
                </button>
                <a href="{{ url('index.php/ordenes-medicas/recetas') }}" class="btn btn-outline">
                    <i class="bi bi-x-lg"></i>
                </a>
            </div>
        </form>
    </div>

    <!-- Recetas List -->
    <div class="card">
        <div class="overflow-x-auto">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Paciente</th>
                        <th>Medicamento</th>
                        <th>Dosis y Frecuencia</th>
                        <th>Duración</th>
                        <th class="w-32">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recetas ?? [] as $receta)
                    <tr>
                        <td>
                            <div class="flex flex-col">
                                <span class="text-sm font-semibold text-gray-900">
                                    {{ isset($receta->created_at) ? \Carbon\Carbon::parse($receta->created_at)->format('d/m/Y') : 'N/A' }}
                                </span>
                                <span class="text-xs text-gray-500">
                                    {{ isset($receta->created_at) ? \Carbon\Carbon::parse($receta->created_at)->format('H:i A') : '' }}
                                </span>
                            </div>
                        </td>
                        <td>
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 rounded-full bg-purple-100 flex items-center justify-center text-purple-700 text-xs font-bold">
                                    {{ strtoupper(substr($receta->orden->paciente->primer_nombre ?? 'P', 0, 1)) }}{{ strtoupper(substr($receta->orden->paciente->primer_apellido ?? 'A', 0, 1)) }}
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-gray-900">
                                        {{ $receta->orden->paciente->primer_nombre ?? 'N/A' }} 
                                        {{ $receta->orden->paciente->primer_apellido ?? '' }}
                                    </p>
                                </div>
                            </div>
                        </td>
                        <td>
                            <p class="font-semibold text-gray-900">{{ $receta->medicamento ?? 'N/A' }}</p>
                            @if($receta->via_administracion ?? null)
                            <p class="text-xs text-gray-500">Vía: {{ ucfirst($receta->via_administracion) }}</p>
                            @endif
                        </td>
                        <td>
                            <div class="flex flex-col gap-1">
                                <span class="text-sm text-gray-900"><strong>Dosis:</strong> {{ $receta->dosis ?? 'N/A' }}</span>
                                <span class="text-sm text-gray-600">{{ $receta->frecuencia ?? 'N/A' }}</span>
                            </div>
                        </td>
                        <td>
                            <span class="badge badge-info">{{ $receta->duracion ?? 'N/A' }}</span>
                        </td>
                        <td>
                            <div class="flex items-center gap-2">
                                <a href="{{ url('index.php/ordenes-medicas/' . $receta->orden_id) }}" class="btn btn-sm btn-outline" title="Ver">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <button onclick="window.print()" class="btn btn-sm btn-primary" title="Imprimir">
                                    <i class="bi bi-printer"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-12">
                            <div class="inline-flex flex-col items-center">
                                <div class="w-20 h-20 rounded-full bg-gray-100 flex items-center justify-center mb-4">
                                    <i class="bi bi-prescription text-4xl text-gray-300"></i>
                                </div>
                                <p class="text-gray-500 font-medium mb-2">No se encontraron recetas</p>
                                <p class="text-sm text-gray-400 mb-4">Crea una nueva receta médica</p>
                                <a href="{{ url('index.php/ordenes-medicas/create?tipo=receta') }}" class="btn btn-sm btn-primary">
                                    <i class="bi bi-plus-lg"></i>
                                    Nueva Receta
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if(isset($recetas) && $recetas->hasPages())
        <div class="p-6 border-t border-gray-200">
            {{ $recetas->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
