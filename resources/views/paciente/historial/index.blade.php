@extends('layouts.paciente')

@section('title', 'Mi Historial Médico')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div>
        <h1 class="text-2xl font-display font-bold text-gray-900">Mi Historial Médico</h1>
        <p class="text-gray-600 mt-1">Consulta tu expediente clínico completo</p>
    </div>

    <!-- Filters -->
    <div class="card p-6">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="form-label">Tipo de Registro</label>
                <select name="tipo" class="form-select">
                    <option value="">Todos</option>
                    <option value="evolucion" {{ request('tipo') == 'evolucion' ? 'selected' : '' }}>Evoluciones</option>
                    <option value="receta" {{ request('tipo') == 'receta' ? 'selected' : '' }}>Recetas</option>
                    <option value="laboratorio" {{ request('tipo') == 'laboratorio' ? 'selected' : '' }}>Laboratorios</option>
                    <option value="imagenologia" {{ request('tipo') == 'imagenologia' ? 'selected' : '' }}>Imagenología</option>
                </select>
            </div>
            <div>
                <label class="form-label">Desde</label>
                <input type="date" name="fecha_desde" class="input" value="{{ request('fecha_desde') }}">
            </div>
            <div>
                <label class="form-label">Hasta</label>
                <input type="date" name="fecha_hasta" class="input" value="{{ request('fecha_hasta') }}">
            </div>
            <div class="flex items-end">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-search"></i> Buscar
                </button>
            </div>
        </form>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Timeline -->
        <div class="lg:col-span-2">
            <div class="space-y-6">
                @forelse($historial ?? [] as $registro)
                <div class="card p-6">
                    <div class="flex gap-4">
                        <!-- Icon -->
                        <div class="flex-shrink-0">
                            @if($registro->tipo == 'evolucion')
                            <div class="w-12 h-12 rounded-xl bg-blue-100 flex items-center justify-center">
                                <i class="bi bi-file-medical text-blue-600 text-xl"></i>
                            </div>
                            @elseif($registro->tipo == 'receta')
                            <div class="w-12 h-12 rounded-xl bg-purple-100 flex items-center justify-center">
                                <i class="bi bi-prescription text-purple-600 text-xl"></i>
                            </div>
                            @elseif($registro->tipo == 'laboratorio')
                            <div class="w-12 h-12 rounded-xl bg-emerald-100 flex items-center justify-center">
                                <i class="bi bi-activity text-emerald-600 text-xl"></i>
                            </div>
                            @else
                            <div class="w-12 h-12 rounded-xl bg-amber-100 flex items-center justify-center">
                                <i class="bi bi-x-ray text-amber-600 text-xl"></i>
                            </div>
                            @endif
                        </div>

                        <!-- Content -->
                        <div class="flex-1">
                            <div class="flex items-start justify-between mb-2">
                                <div>
                                    <h3 class="font-bold text-gray-900 text-lg">{{ $registro->titulo ?? 'Registro Médico' }}</h3>
                                    <p class="text-sm text-gray-600 mt-1">
                                        Dr. {{ $registro->medico->primer_nombre ?? 'N/A' }} {{ $registro->medico->primer_apellido ?? '' }}
                                    </p>
                                </div>
                                <span class="text-xs text-gray-500">
                                    {{ isset($registro->created_at) ? \Carbon\Carbon::parse($registro->created_at)->format('d/m/Y') : 'N/A' }}
                                </span>
                            </div>

                            @if($registro->tipo == 'evolucion')
                            <div class="space-y-2 mt-3">
                                <div class="p-3 bg-gray-50 rounded-lg">
                                    <p class="text-xs text-gray-600 mb-1">Diagnóstico</p>
                                    <p class="text-sm text-gray-900">{{ $registro->diagnostico ?? 'N/A' }}</p>
                                </div>
                                @if($registro->tratamiento ?? null)
                                <div class="p-3 bg-emerald-50 rounded-lg">
                                    <p class="text-xs text-gray-600 mb-1">Tratamiento</p>
                                    <p class="text-sm text-gray-900">{{ Str::limit($registro->tratamiento, 150) }}</p>
                                </div>
                                @endif
                            </div>
                            @elseif($registro->tipo == 'receta')
                            <div class="p-3 bg-purple-50 rounded-lg mt-3">
                                <p class="text-sm font-semibold text-gray-900">{{ $registro->medicamento ?? 'Medicamento' }}</p>
                                <p class="text-xs text-gray-600 mt-1">
                                    <strong>Dosis:</strong> {{ $registro->dosis ?? 'N/A' }} | 
                                    <strong>Frecuencia:</strong> {{ $registro->frecuencia ?? 'N/A' }}
                                </p>
                            </div>
                            @elseif($registro->tipo == 'laboratorio')
                            <div class="p-3 bg-emerald-50 rounded-lg mt-3">
                                <p class="text-xs text-gray-600 mb-1">Exámenes Solicitados</p>
                                <div class="flex flex-wrap gap-1 mt-2">
                                    @foreach(json_decode($registro->examenes ?? '[]') ?? [] as $examen)
                                    <span class="badge badge-info text-xs">{{ $examen }}</span>
                                    @endforeach
                                </div>
                            </div>
                            @endif

                            <div class="mt-3 flex gap-2">
                                <a href="#" class="btn btn-sm btn-outline">
                                    <i class="bi bi-eye"></i> Ver Detalle
                                </a>
                                <button onclick="window.print()" class="btn btn-sm btn-outline">
                                    <i class="bi bi-download"></i> Descargar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="card p-12">
                    <div class="text-center">
                        <i class="bi bi-folder2-open text-5xl text-gray-300 mb-3"></i>
                        <p class="text-gray-500 font-medium mb-2">No hay registros en tu historial</p>
                        <p class="text-sm text-gray-400">Tu historial médico aparecerá aquí</p>
                    </div>
                </div>
                @endforelse
            </div>

            @if(isset($historial) && $historial->hasPages())
            <div class="mt-6">
                {{ $historial->links() }}
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Stats -->
            <div class="card p-6">
                <h3 class="text-lg font-display font-bold text-gray-900 mb-4">Resumen</h3>
                <div class="space-y-3">
                    <div class="flex items-center justify-between p-3 bg-blue-50 rounded-lg">
                        <span class="text-sm text-gray-700">Total Consultas</span>
                        <span class="font-bold text-blue-900">{{ $stats['total_consultas'] ?? 0 }}</span>
                    </div>
                    <div class="flex items-center justify-between p-3 bg-purple-50 rounded-lg">
                        <span class="text-sm text-gray-700">Recetas</span>
                        <span class="font-bold text-purple-900">{{ $stats['total_recetas'] ?? 0 }}</span>
                    </div>
                    <div class="flex items-center justify-between p-3 bg-emerald-50 rounded-lg">
                        <span class="text-sm text-gray-700">Laboratorios</span>
                        <span class="font-bold text-emerald-900">{{ $stats['total_labs'] ?? 0 }}</span>
                    </div>
                </div>
            </div>

            <!-- Datos Médicos -->
            <div class="card p-6">
                <h3 class="text-lg font-display font-bold text-gray-900 mb-4">Información Médica</h3>
                <div class="space-y-3 text-sm">
                    <div>
                        <p class="text-gray-500">Tipo de Sangre</p>
                        <p class="font-semibold text-gray-900 text-lg">{{ auth()->user()->paciente->tipo_sangre ?? 'N/A' }}</p>
                    </div>
                    @if(auth()->user()->paciente->alergias ?? null)
                    <div class="p-3 bg-rose-50 rounded-lg border border-rose-200">
                        <p class="text-xs text-rose-700 font-semibold mb-1">
                            <i class="bi bi-exclamation-triangle"></i> Alergias
                        </p>
                        <p class="text-sm text-gray-900">{{ auth()->user()->paciente->alergias }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Export -->
            <div class="card p-6 bg-blue-50 border-blue-200">
                <h3 class="text-lg font-display font-bold text-gray-900 mb-3">Exportar Historial</h3>
                <p class="text-sm text-gray-600 mb-4">Descarga tu historial médico completo en formato PDF</p>
                <button class="btn btn-primary w-full">
                    <i class="bi bi-file-earmark-pdf"></i> Descargar PDF
                </button>
            </div>
        </div>
    </div>
</div>
@endsection
