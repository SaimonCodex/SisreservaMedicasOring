@extends('layouts.medico')

@section('title', 'Órdenes de Laboratorio')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-4">
            <a href="{{ route('ordenes-medicas.index') }}" class="btn btn-outline">
                <i class="bi bi-arrow-left"></i>
            </a>
            <div>
                <h1 class="text-2xl font-display font-bold text-gray-900">Órdenes de Laboratorio</h1>
                <p class="text-gray-600 mt-1">Gestión de exámenes y análisis clínicos</p>
            </div>
        </div>
        <a href="{{ route('ordenes-medicas.create', ['tipo' => 'laboratorio']) }}" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i>
            <span>Nueva Orden</span>
        </a>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="card p-4 bg-gradient-to-br from-blue-50 to-blue-100 border-blue-200">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-lg bg-blue-600 flex items-center justify-center">
                    <i class="bi bi-activity text-white text-xl"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-blue-900">{{ $stats['total'] ?? 0 }}</p>
                    <p class="text-sm text-blue-700">Total Órdenes</p>
                </div>
            </div>
        </div>
        <div class="card p-4 bg-gradient-to-br from-amber-50 to-amber-100 border-amber-200">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-lg bg-amber-600 flex items-center justify-center">
                    <i class="bi bi-clock text-white text-xl"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-amber-900">{{ $stats['pendientes'] ?? 0 }}</p>
                    <p class="text-sm text-amber-700">Pendientes</p>
                </div>
            </div>
        </div>
        <div class="card p-4 bg-gradient-to-br from-purple-50 to-purple-100 border-purple-200">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-lg bg-purple-600 flex items-center justify-center">
                    <i class="bi bi-hourglass-split text-white text-xl"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-purple-900">{{ $stats['en_proceso'] ?? 0 }}</p>
                    <p class="text-sm text-purple-700">En Proceso</p>
                </div>
            </div>
        </div>
        <div class="card p-4 bg-gradient-to-br from-emerald-50 to-emerald-100 border-emerald-200">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-lg bg-emerald-600 flex items-center justify-center">
                    <i class="bi bi-check-circle text-white text-xl"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-emerald-900">{{ $stats['completadas'] ?? 0 }}</p>
                    <p class="text-sm text-emerald-700">Completadas</p>
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
                <label class="form-label">Examen</label>
                <input type="text" name="examen" class="input" placeholder="Buscar..." value="{{ request('examen') }}">
            </div>
            <div>
                <label class="form-label">Estado</label>
                <select name="status" class="form-select">
                    <option value="">Todos</option>
                    <option value="pendiente" {{ request('status') == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                    <option value="en_proceso" {{ request('status') == 'en_proceso' ? 'selected' : '' }}>En Proceso</option>
                    <option value="completada" {{ request('status') == 'completada' ? 'selected' : '' }}>Completada</option>
                </select>
            </div>
            <div>
                <label class="form-label">Fecha</label>
                <input type="date" name="fecha" class="input" value="{{ request('fecha') }}">
            </div>
            <div class="flex items-end gap-2">
                <button type="submit" class="btn btn-primary flex-1">
                    <i class="bi bi-search"></i>
                    Buscar
                </button>
                <a href="{{ route('ordenes-medicas.laboratorios') }}" class="btn btn-outline">
                    <i class="bi bi-x-lg"></i>
                </a>
            </div>
        </form>
    </div>

    <!-- Laboratorios List -->
    <div class="card">
        <div class="overflow-x-auto">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Paciente</th>
                        <th>Exámenes Solicitados</th>
                        <th>Estado</th>
                        <th>Resultados</th>
                        <th class="w-40">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($laboratorios ?? [] as $lab)
                    <tr>
                        <td>
                            <div class="flex flex-col">
                                <span class="text-sm font-semibold text-gray-900">
                                    {{ isset($lab->created_at) ? \Carbon\Carbon::parse($lab->created_at)->format('d/m/Y') : 'N/A' }}
                                </span>
                                <span class="text-xs text-gray-500">
                                    {{ isset($lab->created_at) ? \Carbon\Carbon::parse($lab->created_at)->format('H:i A') : '' }}
                                </span>
                            </div>
                        </td>
                        <td>
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-700 text-xs font-bold">
                                    {{ strtoupper(substr($lab->orden->paciente->primer_nombre ?? 'P', 0, 1)) }}{{ strtoupper(substr($lab->orden->paciente->primer_apellido ?? 'A', 0, 1)) }}
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-gray-900">
                                        {{ $lab->orden->paciente->primer_nombre ?? 'N/A' }} 
                                        {{ $lab->orden->paciente->primer_apellido ?? '' }}
                                    </p>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="flex flex-wrap gap-1">
                                @if($lab->examenes ?? null)
                                @foreach(json_decode($lab->examenes) ?? [] as $examen)
                                <span class="badge badge-info text-xs">{{ ucfirst(str_replace('_', ' ', $examen)) }}</span>
                                @endforeach
                                @endif
                            </div>
                            @if($lab->otros_examenes ?? null)
                            <p class="text-xs text-gray-600 mt-1">+ {{ $lab->otros_examenes }}</p>
                            @endif
                        </td>
                        <td>
                            @if($lab->orden->status == 'completada')
                            <span class="badge badge-success">
                                <i class="bi bi-check-circle"></i> Completada
                            </span>
                            @elseif($lab->orden->status == 'en_proceso')
                            <span class="badge badge-info">
                                <i class="bi bi-hourglass-split"></i> En Proceso
                            </span>
                            @else
                            <span class="badge badge-warning">
                                <i class="bi bi-clock"></i> Pendiente
                            </span>
                            @endif
                        </td>
                        <td>
                            @if($lab->resultados ?? null)
                            <span class="text-emerald-600 font-semibold text-sm">
                                <i class="bi bi-file-earmark-check"></i> Disponibles
                            </span>
                            @else
                            <span class="text-gray-400 text-sm">Sin resultados</span>
                            @endif
                        </td>
                        <td>
                            <div class="flex items-center gap-2">
                                <a href="{{ route('ordenes-medicas.show', $lab->orden_id) }}" class="btn btn-sm btn-outline" title="Ver">
                                    <i class="bi bi-eye"></i>
                                </a>
                                @if(!$lab->resultados)
                                <a href="{{ route('ordenes-medicas.registrar-resultados', ['id' => $lab->orden_id, 'laboratorio' => $lab->id]) }}" class="btn btn-sm btn-success" title="Registrar Resultados">
                                    <i class="bi bi-clipboard-check"></i>
                                </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-12">
                            <div class="inline-flex flex-col items-center">
                                <div class="w-20 h-20 rounded-full bg-gray-100 flex items-center justify-center mb-4">
                                    <i class="bi bi-activity text-4xl text-gray-300"></i>
                                </div>
                                <p class="text-gray-500 font-medium mb-2">No se encontraron órdenes de laboratorio</p>
                                <p class="text-sm text-gray-400 mb-4">Crea una nueva orden</p>
                                <a href="{{ route('ordenes-medicas.create', ['tipo' => 'laboratorio']) }}" class="btn btn-sm btn-primary">
                                    <i class="bi bi-plus-lg"></i>
                                    Nueva Orden
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if(isset($laboratorios) && $laboratorios->hasPages())
        <div class="p-6 border-t border-gray-200">
            {{ $laboratorios->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
