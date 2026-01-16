@extends('layouts.medico')

@section('title', 'Evoluciones Clínicas')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-display font-bold text-gray-900">Evoluciones Clínicas</h1>
            <p class="text-gray-600 mt-1">Registro y seguimiento de consultas médicas</p>
        </div>
        <!-- <a href="{{ route('historia-clinica.evoluciones.create', ['citaId' => 0]) }}" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i>
            <span>Nueva Evolución</span>
        </a> -->
    </div>

    <!-- Filters -->
    <div class="card p-6">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
            <div class="md:col-span-3">
                <label class="form-label">Paciente</label>
                <div class="relative">
                    <i class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    <input type="text" name="paciente" class="input pl-10" placeholder="Buscar paciente..." value="{{ request('paciente') }}">
                </div>
            </div>
            <div class="md:col-span-2">
                <label class="form-label">Fecha Desde</label>
                <input type="date" name="fecha_desde" class="input" value="{{ request('fecha_desde') }}">
            </div>
            <div class="md:col-span-2">
                <label class="form-label">Fecha Hasta</label>
                <input type="date" name="fecha_hasta" class="input" value="{{ request('fecha_hasta') }}">
            </div>
            <div class="md:col-span-3">
                <label class="form-label">Diagnóstico</label>
                <input type="text" name="diagnostico" class="input" placeholder="Buscar..." value="{{ request('diagnostico')}}">
            </div>
            <div class="md:col-span-2 flex gap-2">
                <button type="submit" class="btn btn-primary w-full">
                    <i class="bi bi-funnel"></i>
                    Filtrar
                </button>
                <a href="{{ route('historia-clinica.evoluciones.general') }}" class="btn btn-outline" title="Limpiar">
                    <i class="bi bi-x-lg"></i>
                </a>
            </div>
        </form>
    </div>

    <!-- Evoluciones List -->
    <div class="card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gradient-to-r from-medical-600 to-medical-500 text-white">
                    <tr>
                        <th class="px-6 py-4 text-left font-semibold">Fecha</th>
                        <th class="px-6 py-4 text-left font-semibold">Paciente</th>
                        <th class="px-6 py-4 text-left font-semibold">Diagnóstico</th>
                        <th class="px-6 py-4 text-left font-semibold">Signos Vitales</th>
                        <th class="px-6 py-4 text-left font-semibold">Cita</th>
                        <th class="px-6 py-4 text-center font-semibold">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white">
                    @forelse($evoluciones ?? [] as $evolucion)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex flex-col">
                                <span class="font-semibold text-gray-900">
                                    {{ isset($evolucion->created_at) ? \Carbon\Carbon::parse($evolucion->created_at)->format('d/m/Y') : 'N/A' }}
                                </span>
                                <span class="text-sm text-gray-500">
                                    {{ isset($evolucion->created_at) ? \Carbon\Carbon::parse($evolucion->created_at)->format('H:i A') : '' }}
                                </span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-purple-500 to-purple-600 flex items-center justify-center text-white font-bold text-sm">
                                    {{ strtoupper(substr($evolucion->paciente->primer_nombre ?? 'P', 0, 1)) }}{{ strtoupper(substr($evolucion->paciente->primer_apellido ?? 'A', 0, 1)) }}
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-900">
                                        {{ $evolucion->paciente->primer_nombre ?? 'N/A' }} 
                                        {{ $evolucion->paciente->primer_apellido ?? '' }}
                                    </p>
                                    <p class="text-sm text-gray-500">
                                        {{ $evolucion->paciente->tipo_documento ?? '' }}-{{ $evolucion->paciente->numero_documento ?? 'N/A' }}
                                    </p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-gray-900 line-clamp-2">{{ $evolucion->diagnostico ?? 'N/A' }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-wrap gap-2">
                                @if($evolucion->presion_arterial ?? null)
                                <span class="badge badge-info text-xs">
                                    <i class="bi bi-heart-pulse"></i> {{ $evolucion->presion_arterial }}
                                </span>
                                @endif
                                @if($evolucion->temperatura ?? null)
                                <span class="badge badge-warning text-xs">
                                    <i class="bi bi-thermometer-half"></i> {{ $evolucion->temperatura }}°C
                                </span>
                                @endif
                                @if($evolucion->frecuencia_cardiaca ?? null)
                                <span class="badge badge-danger text-xs">
                                    <i class="bi bi-activity"></i> {{ $evolucion->frecuencia_cardiaca }} bpm
                                </span>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            @if($evolucion->cita_id ?? null)
                            <a href="{{ route('citas.show', $evolucion->cita_id) }}" class="text-blue-600 hover:text-blue-700 text-sm flex items-center gap-1">
                                <i class="bi bi-calendar-check"></i>
                                Ver cita
                            </a>
                            @else
                            <span class="text-gray-400 text-sm">Sin cita</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('historia-clinica.evoluciones.show', ['citaId' => $evolucion->cita_id ?? 0]) }}" class="btn btn-sm btn-ghost text-purple-600 hover:bg-purple-50" title="Ver">
                                    <i class="bi bi-eye"></i>
                                </a>
                                {{-- Si es necesario editar, descomentar y ajustar ruta
                                <a href="{{ route('historia-clinica.evoluciones.edit', ['citaId' => $evolucion->cita_id ?? 0]) }}" class="btn btn-sm btn-ghost text-blue-600 hover:bg-blue-50" title="Editar">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                --}}
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <div class="w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center mb-4">
                                    <i class="bi bi-file-earmark-medical text-3xl text-gray-400"></i>
                                </div>
                                <h3 class="text-lg font-medium text-gray-900">No se encontraron evoluciones</h3>
                                <p class="text-gray-500 mt-1">Registra una nueva evolución desde el detalle de la cita.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if(isset($evoluciones) && $evoluciones->hasPages())
        <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
            {{ $evoluciones->appends(request()->query())->links('vendor.pagination.medical') }}
        </div>
        @endif
    </div>
</div>
@endsection
