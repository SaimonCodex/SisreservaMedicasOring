@extends('layouts.medico')

@section('title', 'Órdenes de Imagenología')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-4">
            <a href="{{ url('index.php/ordenes-medicas') }}" class="btn btn-outline">
                <i class="bi bi-arrow-left"></i>
            </a>
            <div>
                <h1 class="text-2xl font-display font-bold text-gray-900">Órdenes de Imagenología</h1>
                <p class="text-gray-600 mt-1">Gestión de estudios de imagen y radiología</p>
            </div>
        </div>
        <a href="{{ url('index.php/ordenes-medicas/create?tipo=imagenologia') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i>
            <span>Nueva Orden</span>
        </a>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="card p-4 bg-gradient-to-br from-emerald-50 to-emerald-100 border-emerald-200">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-lg bg-emerald-600 flex items-center justify-center">
                    <i class="bi bi-x-ray text-white text-xl"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-emerald-900">{{ $stats['total'] ?? 0 }}</p>
                    <p class="text-sm text-emerald-700">Total Estudios</p>
                </div>
            </div>
        </div>
        <div class="card p-4 bg-gradient-to-br from-blue-50 to-blue-100 border-blue-200">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-lg bg-blue-600 flex items-center justify-center">
                    <i class="bi bi-camera text-white text-xl"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-blue-900">{{ $stats['radiografias'] ?? 0 }}</p>
                    <p class="text-sm text-blue-700">Radiografías</p>
                </div>
            </div>
        </div>
        <div class="card p-4 bg-gradient-to-br from-purple-50 to-purple-100 border-purple-200">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-lg bg-purple-600 flex items-center justify-center">
                    <i class="bi bi-hurricane text-white text-xl"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-purple-900">{{ $stats['tomografias'] ?? 0 }}</p>
                    <p class="text-sm text-purple-700">Tomografías/RM</p>
                </div>
            </div>
        </div>
        <div class="card p-4 bg-gradient-to-br from-cyan-50 to-cyan-100 border-cyan-200">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-lg bg-cyan-600 flex items-center justify-center">
                    <i class="bi bi-soundwave text-white text-xl"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-cyan-900">{{ $stats['ecografias'] ?? 0 }}</p>
                    <p class="text-sm text-cyan-700">Ecografías</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Imagenología List -->
    <div class="card">
        <div class="overflow-x-auto">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Paciente</th>
                        <th>Tipo de Estudio</th>
                        <th>Área/Región</th>
                        <th>Estado</th>
                        <th class="w-32">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($imagenologias ?? [] as $imagen)
                    <tr>
                        <td>
                            <div class="flex flex-col">
                                <span class="text-sm font-semibold text-gray-900">
                                    {{ isset($imagen->created_at) ? \Carbon\Carbon::parse($imagen->created_at)->format('d/m/Y') : 'N/A' }}
                                </span>
                                <span class="text-xs text-gray-500">
                                    {{ isset($imagen->created_at) ? \Carbon\Carbon::parse($imagen->created_at)->format('H:i A') : '' }}
                                </span>
                            </div>
                        </td>
                        <td>
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-700 text-xs font-bold">
                                    {{ strtoupper(substr($imagen->orden->paciente->primer_nombre ?? 'P', 0, 1)) }}{{ strtoupper(substr($imagen->orden->paciente->primer_apellido ?? 'A', 0, 1)) }}
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-gray-900">
                                        {{ $imagen->orden->paciente->primer_nombre ?? 'N/A' }} 
                                        {{ $imagen->orden->paciente->primer_apellido ?? '' }}
                                    </p>
                                </div>
                            </div>
                        </td>
                        <td>
                            @if($imagen->tipo_estudio == 'radiografia')
                            <span class="badge badge-info">
                                <i class="bi bi-camera"></i> Radiografía
                            </span>
                            @elseif($imagen->tipo_estudio == 'tomografia')
                            <span class="badge badge-purple">
                                <i class="bi bi-hurricane"></i> Tomografía
                            </span>
                            @elseif($imagen->tipo_estudio == 'resonancia')
                            <span class="badge badge-danger">
                                <i class="bi bi-magnet"></i> Resonancia
                            </span>
                            @elseif($imagen->tipo_estudio == 'ecografia')
                            <span class="badge badge-success">
                                <i class="bi bi-soundwave"></i> Ecografía
                            </span>
                            @else
                            <span class="badge badge-secondary">{{ ucfirst($imagen->tipo_estudio ?? 'N/A') }}</span>
                            @endif
                        </td>
                        <td>
                            <p class="font-semibold text-gray-900">{{ $imagen->region ?? 'N/A' }}</p>
                        </td>
                        <td>
                            @if($imagen->orden->status == 'completada')
                            <span class="badge badge-success">
                                <i class="bi bi-check-circle"></i> Completada
                            </span>
                            @elseif($imagen->orden->status == 'en_proceso')
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
                            <div class="flex items-center gap-2">
                                <a href="{{ url('index.php/ordenes-medicas/' . $imagen->orden_id) }}" class="btn btn-sm btn-outline" title="Ver">
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
                                    <i class="bi bi-x-ray text-4xl text-gray-300"></i>
                                </div>
                                <p class="text-gray-500 font-medium mb-2">No hay órdenes de imagenología</p>
                                <p class="text-sm text-gray-400 mb-4">Crea una nueva orden de estudio</p>
                                <a href="{{ url('index.php/ordenes-medicas/create?tipo=imagenologia') }}" class="btn btn-sm btn-primary">
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

        @if(isset($imagenologias) && $imagenologias->hasPages())
        <div class="p-6 border-t border-gray-200">
            {{ $imagenologias->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
