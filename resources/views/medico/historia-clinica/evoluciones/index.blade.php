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
        <a href="{{ url('index.php/historia-clinica/evoluciones/create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i>
            <span>Nueva Evolución</span>
        </a>
    </div>

    <!-- Filters -->
    <div class="card p-6">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <div>
                <label class="form-label">Paciente</label>
                <input type="text" name="paciente" class="input" placeholder="Buscar paciente..." value="{{ request('paciente') }}">
            </div>
            <div>
                <label class="form-label">Fecha Desde</label>
                <input type="date" name="fecha_desde" class="input" value="{{ request('fecha_desde') }}">
            </div>
            <div>
                <label class="form-label">Fecha Hasta</label>
                <input type="date" name="fecha_hasta" class="input" value="{{ request('fecha_hasta') }}">
            </div>
            <div>
                <label class="form-label">Diagnóstico</label>
                <input type="text" name="diagnostico" class="input" placeholder="Buscar..." value="{{ request('diagnostico')}}" </div>
            <div class="flex items-end gap-2">
                <button type="submit" class="btn btn-primary flex-1">
                    <i class="bi bi-search"></i>
                    Buscar
                </button>
                <a href="{{ url('index.php/historia-clinica/evoluciones') }}" class="btn btn-outline">
                    <i class="bi bi-x-lg"></i>
                </a>
            </div>
        </form>
    </div>

    <!-- Evoluciones List -->
    <div class="card">
        <div class="overflow-x-auto">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Paciente</th>
                        <th>Diagnóstico</th>
                        <th>Signos Vitales</th>
                        <th>Cita</th>
                        <th class="w-40">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($evoluciones ?? [] as $evolucion)
                    <tr>
                        <td>
                            <div class="flex flex-col">
                                <span class="font-semibold text-gray-900">
                                    {{ isset($evolucion->created_at) ? \Carbon\Carbon::parse($evolucion->created_at)->format('d/m/Y') : 'N/A' }}
                                </span>
                                <span class="text-sm text-gray-500">
                                    {{ isset($evolucion->created_at) ? \Carbon\Carbon::parse($evolucion->created_at)->format('H:i A') : '' }}
                                </span>
                            </div>
                        </td>
                        <td>
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-purple-500 to-purple-600 flex items-center justify-center text-white font-bold text-sm">
                                    {{ strtoupper(substr($evolucion->historiaClinica->paciente->primer_nombre ?? 'P', 0, 1)) }}{{ strtoupper(substr($evolucion->historiaClinica->paciente->primer_apellido ?? 'A', 0, 1)) }}
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-900">
                                        {{ $evolucion->historiaClinica->paciente->primer_nombre ?? 'N/A' }} 
                                        {{ $evolucion->historiaClinica->paciente->primer_apellido ?? '' }}
                                    </p>
                                    <p class="text-sm text-gray-500">{{ $evolucion->historiaClinica->paciente->cedula ?? 'N/A' }}</p>
                                </div>
                            </div>
                        </td>
                        <td>
                            <p class="text-gray-900 line-clamp-2">{{ $evolucion->diagnostico ?? 'N/A' }}</p>
                        </td>
                        <td>
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
                        <td>
                            @if($evolucion->cita_id ?? null)
                            <a href="{{ route('citas.show', $evolucion->cita_id) }}" class="text-blue-600 hover:text-blue-700 text-sm flex items-center gap-1">
                                <i class="bi bi-calendar-check"></i>
                                Ver cita
                            </a>
                            @else
                            <span class="text-gray-400 text-sm">Sin cita</span>
                            @endif
                        </td>
                        <td>
                            <div class="flex items-center gap-2">
                                <a href="{{ url('index.php/historia-clinica/evoluciones/' . $evolucion->id) }}" class="btn btn-sm btn-outline" title="Ver">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ url('index.php/historia-clinica/evoluciones/' . $evolucion->id . '/edit') }}" class="btn btn-sm btn-primary" title="Editar">
                                    <i class="bi bi-pencil"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-12">
                            <div class="inline-flex flex-col items-center">
                                <div class="w-20 h-20 rounded-full bg-gray-100 flex items-center justify-center mb-4">
                                    <i class="bi bi-file-earmark-medical text-4xl text-gray-300"></i>
                                </div>
                                <p class="text-gray-500 font-medium mb-2">No se encontraron evoluciones</p>
                                <p class="text-sm text-gray-400 mb-4">Registra una nueva evolución clínica</p>
                                <a href="{{ url('index.php/historia-clinica/evoluciones/create') }}" class="btn btn-sm btn-primary">
                                    <i class="bi bi-plus-lg"></i>
                                    Nueva Evolución
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if(isset($evoluciones) && $evoluciones->hasPages())
        <div class="p-6 border-t border-gray-200">
            {{ $evoluciones->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
