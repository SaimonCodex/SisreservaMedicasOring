@extends('layouts.medico')

@section('title', 'Órdenes Médicas')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-display font-bold text-gray-900">Órdenes Médicas</h1>
            <p class="text-gray-600 mt-1">Gestión de recetas, exámenes y referencias médicas</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('ordenes-medicas.recetas') }}" class="btn btn-outline">
                <i class="bi bi-prescription"></i>
                <span>Recetas</span>
            </a>
            @if(auth()->user()->rol_id == 2)
            <a href="{{ route('ordenes-medicas.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-lg"></i>
                <span>Nueva Orden</span>
            </a>
            @endif
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="card p-4 bg-gradient-to-br from-purple-50 to-purple-100 border-purple-200">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-lg bg-purple-600 flex items-center justify-center">
                    <i class="bi bi-prescription text-white text-xl"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-purple-900">{{ $stats['recetas'] ?? 0 }}</p>
                    <p class="text-sm text-purple-700">Recetas</p>
                </div>
            </div>
        </div>
        <div class="card p-4 bg-gradient-to-br from-blue-50 to-blue-100 border-blue-200">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-lg bg-blue-600 flex items-center justify-center">
                    <i class="bi bi-activity text-white text-xl"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-blue-900">{{ $stats['laboratorios'] ?? 0 }}</p>
                    <p class="text-sm text-blue-700">Laboratorios</p>
                </div>
            </div>
        </div>
        <div class="card p-4 bg-gradient-to-br from-emerald-50 to-emerald-100 border-emerald-200">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-lg bg-emerald-600 flex items-center justify-center">
                    <i class="bi bi-x-ray text-white text-xl"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-emerald-900">{{ $stats['imagenologia'] ?? 0 }}</p>
                    <p class="text-sm text-emerald-700">Imagenología</p>
                </div>
            </div>
        </div>
        <div class="card p-4 bg-gradient-to-br from-amber-50 to-amber-100 border-amber-200">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-lg bg-amber-600 flex items-center justify-center">
                    <i class="bi bi-arrow-right-circle text-white text-xl"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-amber-900">{{ $stats['referencias'] ?? 0 }}</p>
                    <p class="text-sm text-amber-700">Referencias</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card p-6">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <div>
                <label class="form-label">Tipo de Orden</label>
                <select name="tipo" class="form-select">
                    <option value="">Todas</option>
                    <option value="receta" {{ request('tipo') == 'receta' ? 'selected' : '' }}>Receta Médica</option>
                    <option value="laboratorio" {{ request('tipo') == 'laboratorio' ? 'selected' : '' }}>Examen de Laboratorio</option>
                    <option value="imagenologia" {{ request('tipo') == 'imagenologia' ? 'selected' : '' }}>Imagenología</option>
                    <option value="referencia" {{ request('tipo') == 'referencia' ? 'selected' : '' }}>Referencia</option>
                </select>
            </div>
            <div>
                <label class="form-label">Paciente</label>
                <input type="text" name="paciente" class="input" placeholder="Buscar..." value="{{ request('paciente') }}">
            </div>
            <div>
                <label class="form-label">Estado</label>
                <select name="status" class="form-select">
                    <option value="">Todos</option>
                    <option value="pendiente" {{ request('status') == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                    <option value="en_proceso" {{ request('status') == 'en_proceso' ? 'selected' : '' }}>En Proceso</option>
                    <option value="completada" {{ request('status') == 'completada' ? 'selected' : '' }}>Completada</option>
                    <option value="cancelada" {{ request('status') == 'cancelada' ? 'selected' : '' }}>Cancelada</option>
                </select>
            </div>
            <div>
                <label class="form-label">Fecha</label>
                <input type="date" name="fecha" class="input" value="{{ request('fecha') }}">
            </div>
            <div class="flex items-end gap-2">
                <button type="submit" class="btn btn-primary flex-1">
                    <i class="bi bi-search"></i>
                    Filtrar
                </button>
                <a href="{{ route('ordenes-medicas.index') }}" class="btn btn-outline">
                    <i class="bi bi-x-lg"></i>
                </a>
            </div>
        </form>
    </div>

    <!-- Ordenes List -->
    <div class="card">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gradient-to-r from-medical-600 to-medical-500 text-white">
                    <tr>
                        <th class="px-6 py-4 text-left font-semibold">Fecha</th>
                        <th class="px-6 py-4 text-left font-semibold">Tipo</th>
                        <th class="px-6 py-4 text-left font-semibold">Paciente</th>
                        <th class="px-6 py-4 text-left font-semibold">Descripción</th>
                        <th class="px-6 py-4 text-left font-semibold">Estado</th>
                        <th class="px-6 py-4 text-center font-semibold">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($ordenes ?? [] as $orden)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex flex-col">
                                <span class="font-semibold text-gray-900">
                                    {{ isset($orden->created_at) ? \Carbon\Carbon::parse($orden->created_at)->format('d/m/Y') : 'N/A' }}
                                </span>
                                <span class="text-sm text-gray-500">
                                    {{ isset($orden->created_at) ? \Carbon\Carbon::parse($orden->created_at)->format('H:i A') : '' }}
                                </span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            @if($orden->tipo == 'receta')
                            <span class="badge badge-purple">
                                <i class="bi bi-prescription"></i> Receta
                            </span>
                            @elseif($orden->tipo == 'laboratorio')
                            <span class="badge badge-info">
                                <i class="bi bi-activity"></i> Laboratorio
                            </span>
                            @elseif($orden->tipo == 'imagenologia')
                            <span class="badge badge-success">
                                <i class="bi bi-x-ray"></i> Imagenología
                            </span>
                            @else
                            <span class="badge badge-warning">
                                <i class="bi bi-arrow-right-circle"></i> Referencia
                            </span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-purple-500 to-purple-600 flex items-center justify-center text-white font-bold text-sm">
                                    {{ strtoupper(substr($orden->paciente->primer_nombre ?? 'P', 0, 1)) }}{{ strtoupper(substr($orden->paciente->primer_apellido ?? 'A', 0, 1)) }}
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-900">
                                        {{ $orden->paciente->primer_nombre ?? 'N/A' }} 
                                        {{ $orden->paciente->primer_apellido ?? '' }}
                                    </p>
                                    <p class="text-sm text-gray-500">
                                        {{ $orden->paciente->tipo_documento ?? '' }}-{{ $orden->paciente->numero_documento ?? 'N/A' }}
                                    </p>
                                    <p class="text-xs text-gray-400 capitalize">{{ $orden->paciente->genero ?? '' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-gray-900 line-clamp-2">{{ $orden->descripcion ?? $orden->observaciones ?? 'Sin descripción' }}</p>
                        </td>
                        <td class="px-6 py-4">
                            @if($orden->status == 'completada')
                            <span class="badge badge-success">
                                <i class="bi bi-check-circle"></i> Completada
                            </span>
                            @elseif($orden->status == 'en_proceso')
                            <span class="badge badge-info">
                                <i class="bi bi-hourglass-split"></i> En Proceso
                            </span>
                            @elseif($orden->status == 'pendiente')
                            <span class="badge badge-warning">
                                <i class="bi bi-clock"></i> Pendiente
                            </span>
                            @else
                            <span class="badge badge-danger">
                                <i class="bi bi-x-circle"></i> Cancelada
                            </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('ordenes-medicas.show', $orden->id) }}" class="btn btn-sm btn-ghost text-purple-600 hover:bg-purple-50" title="Ver">
                                    <i class="bi bi-eye"></i>
                                </a>
                                @if($orden->status != 'completada' && auth()->user()->rol_id == 2)
                                <a href="{{ route('ordenes-medicas.edit', $orden->id) }}" class="btn btn-sm btn-ghost text-blue-600 hover:bg-blue-50" title="Editar">
                                    <i class="bi bi-pencil"></i>
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
                                    <i class="bi bi-clipboard-pulse text-4xl text-gray-300"></i>
                                </div>
                                <p class="text-gray-500 font-medium mb-2">No se encontraron órdenes médicas</p>
                                <p class="text-sm text-gray-400 mb-4">Crea una nueva orden para comenzar</p>
                                @if(auth()->user()->rol_id == 2)
                                <a href="{{ route('ordenes-medicas.create') }}" class="btn btn-sm btn-primary">
                                    <i class="bi bi-plus-lg"></i>
                                    Nueva Orden
                                </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if(isset($ordenes) && $ordenes->hasPages())
        <div class="p-6 border-t border-gray-200">
            {{ $ordenes->appends(request()->query())->links('vendor.pagination.medical') }}
        </div>
        @endif
    </div>

    <!-- Quick Links -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <a href="{{ route('ordenes-medicas.recetas') }}" class="card card-hover p-6 bg-gradient-to-br from-purple-50 to-white border-purple-200">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 rounded-xl bg-purple-600 flex items-center justify-center">
                    <i class="bi bi-prescription text-white text-2xl"></i>
                </div>
                <div>
                    <h3 class="font-bold text-gray-900">Recetas Médicas</h3>
                    <p class="text-sm text-gray-600 mt-1">Ver todas las recetas</p>
                </div>
            </div>
        </a>

        <a href="{{ route('ordenes-medicas.laboratorios') }}" class="card card-hover p-6 bg-gradient-to-br from-blue-50 to-white border-blue-200">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 rounded-xl bg-blue-600 flex items-center justify-center">
                    <i class="bi bi-activity text-white text-2xl"></i>
                </div>
                <div>
                    <h3 class="font-bold text-gray-900">Laboratorios</h3>
                    <p class="text-sm text-gray-600 mt-1">Órdenes de laboratorio</p>
                </div>
            </div>
        </a>

        <a href="{{ route('ordenes-medicas.imagenologias') }}" class="card card-hover p-6 bg-gradient-to-br from-emerald-50 to-white border-emerald-200">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 rounded-xl bg-emerald-600 flex items-center justify-center">
                    <i class="bi bi-x-ray text-white text-2xl"></i>
                </div>
                <div>
                    <h3 class="font-bold text-gray-900">Imagenología</h3>
                    <p class="text-sm text-gray-600 mt-1">Estudios de imagen</p>
                </div>
            </div>
        </a>

        <a href="{{ route('ordenes-medicas.referencias') }}" class="card card-hover p-6 bg-gradient-to-br from-amber-50 to-white border-amber-200">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 rounded-xl bg-amber-600 flex items-center justify-center">
                    <i class="bi bi-arrow-right-circle text-white text-2xl"></i>
                </div>
                <div>
                    <h3 class="font-bold text-gray-900">Referencias</h3>
                    <p class="text-sm text-gray-600 mt-1">Referencias médicas</p>
                </div>
            </div>
        </a>
    </div>
</div>
@endsection
