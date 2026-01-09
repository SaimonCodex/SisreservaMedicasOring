@extends('layouts.admin')

@section('title', 'Evoluciones Clínicas')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-4">
            <a href="{{ url('index.php/shared/historia-clinica/' . $paciente->id) }}" class="btn btn-outline">
                <i class="bi bi-arrow-left"></i>
            </a>
            <div>
                <h1 class="text-2xl font-display font-bold text-gray-900">Evoluciones Clínicas</h1>
                <p class="text-gray-600 mt-1">{{ $paciente->nombre_completo }} - {{ $paciente->cedula }}</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Filters -->
        <div class="lg:col-span-3">
            <div class="card p-6">
                <form action="{{ route('historia-clinica.evoluciones.buscar.fecha', $paciente->id) }}" method="POST" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    @csrf
                    <div class="md:col-span-2">
                        <label class="form-label">Buscar por Fecha</label>
                        <div class="flex gap-2">
                            <input type="date" name="fecha_inicio" class="input" value="{{ $filtros['fecha_inicio'] ?? '' }}" required>
                            <input type="date" name="fecha_fin" class="input" value="{{ $filtros['fecha_fin'] ?? '' }}" required>
                        </div>
                    </div>
                    <div>
                        <label class="form-label">Buscar Diagnóstico</label>
                        <div class="flex gap-2">
                            <input type="text" name="termino" class="input" placeholder="Diagnóstico..." value="{{ $termino ?? '' }}">
                        </div>
                    </div>
                    <div class="flex items-end gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-search"></i> Filtrar
                        </button>
                        <a href="{{ url('index.php/shared/historia-clinica/evoluciones/' . $paciente->id) }}" class="btn btn-outline">
                            <i class="bi bi-x-lg"></i>
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Evoluciones List -->
        <div class="lg:col-span-2 space-y-6">
            @forelse($evoluciones ?? [] as $evolucion)
            <div class="card p-6 border-l-4 border-blue-500 hover:shadow-md transition-shadow">
                <div class="flex justify-between items-start mb-4">
                    <div class="flex gap-4">
                        <div class="w-12 h-12 rounded-full bg-blue-50 flex items-center justify-center text-blue-600 font-bold text-lg">
                            {{ $loop->iteration }}
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-900">{{ $evolucion->motivo_consulta }}</h3>
                            <p class="text-sm text-gray-600">
                                {{ $evolucion->created_at->format('d/m/Y h:i A') }} • Dr. {{ $evolucion->medico->nombre_completo ?? 'N/A' }}
                            </p>
                        </div>
                    </div>
                    <a href="{{ url('index.php/shared/historia-clinica/evoluciones/show/' . $evolucion->cita_id) }}" class="btn btn-sm btn-outline">
                        Ver Detalle <i class="bi bi-arrow-right ml-1"></i>
                    </a>
                </div>

                <div class="space-y-4 pl-16">
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div class="bg-gray-50 p-3 rounded-lg">
                            <span class="font-semibold text-gray-700">Diagnóstico:</span>
                            <p class="text-gray-900 mt-1">{{ Str::limit($evolucion->diagnostico, 100) }}</p>
                        </div>
                        <div class="bg-gray-50 p-3 rounded-lg">
                            <span class="font-semibold text-gray-700">Tratamiento:</span>
                            <p class="text-gray-900 mt-1">{{ Str::limit($evolucion->tratamiento, 100) }}</p>
                        </div>
                    </div>

                    <div class="flex gap-4 text-sm text-gray-500 border-t pt-3">
                        <span class="flex items-center gap-1">
                            <i class="bi bi-activity"></i> T: {{ $evolucion->temperatura_c ?? '--' }}°C
                        </span>
                        <span class="flex items-center gap-1">
                            <i class="bi bi-heart-pulse"></i> FC: {{ $evolucion->frecuencia_cardiaca ?? '--' }} bpm
                        </span>
                        <span class="flex items-center gap-1">
                            <i class="bi bi-speedometer2"></i> T/A: {{ $evolucion->tension_sistolica }}/{{ $evolucion->tension_diastolica }}
                        </span>
                    </div>
                </div>
            </div>
            @empty
            <div class="card p-12 text-center">
                <i class="bi bi-inbox text-5xl text-gray-300 mb-3"></i>
                <p class="text-gray-500">No hay evoluciones registradas para este paciente</p>
            </div>
            @endforelse
        </div>

        <!-- Sidebar Stats -->
        <div class="space-y-6">
            <div class="card p-6 bg-gradient-to-br from-blue-500 to-blue-600 text-white">
                <h3 class="font-bold text-lg mb-4">Resumenr</h3>
                <div class="space-y-4">
                    <div class="flex justify-between items-center bg-white/10 p-3 rounded-lg">
                        <span>Total Consultas</span>
                        <span class="font-bold text-2xl">{{ $evoluciones->count() }}</span>
                    </div>
                    <div class="flex justify-between items-center bg-white/10 p-3 rounded-lg">
                        <span>Primera Consulta</span>
                        <span class="font-bold text-sm">
                            {{ $evoluciones->last()->created_at->format('d/m/Y') ?? 'N/A' }}
                        </span>
                    </div>
                    <div class="flex justify-between items-center bg-white/10 p-3 rounded-lg">
                        <span>Última Consulta</span>
                        <span class="font-bold text-sm">
                            {{ $evoluciones->first()->created_at->format('d/m/Y') ?? 'N/A' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
