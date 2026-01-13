@extends('layouts.medico')

@section('title', 'Referencias Médicas')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-4">
            <a href="{{ route('ordenes-medicas.index') }}" class="btn btn-outline">
                <i class="bi bi-arrow-left"></i>
            </a>
            <div>
                <h1 class="text-2xl font-display font-bold text-gray-900">Referencias Médicas</h1>
                <p class="text-gray-600 mt-1">Gestión de derivaciones a especialistas</p>
            </div>
        </div>
        <a href="{{ route('ordenes-medicas.create', ['tipo' => 'referencia']) }}" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i>
            <span>Nueva Referencia</span>
        </a>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="card p-4 bg-gradient-to-br from-amber-50 to-amber-100 border-amber-200">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-lg bg-amber-600 flex items-center justify-center">
                    <i class="bi bi-arrow-right-circle text-white text-xl"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-amber-900">{{ $stats['total'] ?? 0 }}</p>
                    <p class="text-sm text-amber-700">Total Referencias</p>
                </div>
            </div>
        </div>
        <div class="card p-4 bg-gradient-to-br from-rose-50 to-rose-100 border-rose-200">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-lg bg-rose-600 flex items-center justify-center">
                    <i class="bi bi-exclamation-triangle text-white text-xl"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-rose-900">{{ $stats['urgentes'] ?? 0 }}</p>
                    <p class="text-sm text-rose-700">Urgentes</p>
                </div>
            </div>
        </div>
        <div class="card p-4 bg-gradient-to-br from-blue-50 to-blue-100 border-blue-200">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-lg bg-blue-600 flex items-center justify-center">
                    <i class="bi bi-hourglass-split text-white text-xl"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-blue-900">{{ $stats['pendientes'] ?? 0 }}</p>
                    <p class="text-sm text-blue-700">Pendientes</p>
                </div>
            </div>
        </div>
        <div class="card p-4 bg-gradient-to-br from-emerald-50 to-emerald-100 border-emerald-200">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-lg bg-emerald-600 flex items-center justify-center">
                    <i class="bi bi-check-circle text-white text-xl"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-emerald-900">{{ $stats['atendidas'] ?? 0 }}</p>
                    <p class="text-sm text-emerald-700">Atendidas</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Referencias List -->
    <div class="card">
        <div class="overflow-x-auto">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Paciente</th>
                        <th>Especialidad</th>
                        <th>Motivo</th>
                        <th>Prioridad</th>
                        <th>Estado</th>
                        <th class="w-32">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($referencias ?? [] as $referencia)
                    <tr>
                        <td>
                            <div class="flex flex-col">
                                <span class="text-sm font-semibold text-gray-900">
                                    {{ isset($referencia->created_at) ? \Carbon\Carbon::parse($referencia->created_at)->format('d/m/Y') : 'N/A' }}
                                </span>
                                <span class="text-xs text-gray-500">
                                    {{ isset($referencia->created_at) ? \Carbon\Carbon::parse($referencia->created_at)->format('H:i A') : '' }}
                                </span>
                            </div>
                        </td>
                        <td>
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 rounded-full bg-amber-100 flex items-center justify-center text-amber-700 text-xs font-bold">
                                    {{ strtoupper(substr($referencia->orden->paciente->primer_nombre ?? 'P', 0, 1)) }}{{ strtoupper(substr($referencia->orden->paciente->primer_apellido ?? 'A', 0, 1)) }}
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-gray-900">
                                        {{ $referencia->orden->paciente->primer_nombre ?? 'N/A' }} 
                                        {{ $referencia->orden->paciente->primer_apellido ?? '' }}
                                    </p>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="font-semibold text-gray-900">{{ ucfirst(str_replace('_', ' ', $referencia->especialidad_referencia ?? 'N/A')) }}</span>
                        </td>
                        <td>
                            <p class="text-gray-900 line-clamp-2">{{ $referencia->motivo_referencia ?? 'N/A' }}</p>
                        </td>
                        <td>
                            @if($referencia->prioridad == 'muy_urgente')
                            <span class="badge badge-danger">
                                <i class="bi bi-exclamation-triangle-fill"></i> Muy Urgente
                            </span>
                            @elseif($referencia->prioridad == 'urgente')
                            <span class="badge badge-warning">
                                <i class="bi bi-exclamation-triangle"></i> Urgente
                            </span>
                            @else
                            <span class="badge badge-info">Normal</span>
                            @endif
                        </td>
                        <td>
                            @if($referencia->orden->status == 'completada')
                            <span class="badge badge-success">
                                <i class="bi bi-check-circle"></i> Atendida
                            </span>
                            @else
                            <span class="badge badge-warning">
                                <i class="bi bi-clock"></i> Pendiente
                            </span>
                            @endif
                        </td>
                        <td>
                            <div class="flex items-center gap-2">
                                <a href="{{ route('ordenes-medicas.show', $referencia->orden_id) }}" class="btn btn-sm btn-outline" title="Ver">
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
                        <td colspan="7" class="text-center py-12">
                            <div class="inline-flex flex-col items-center">
                                <div class="w-20 h-20 rounded-full bg-gray-100 flex items-center justify-center mb-4">
                                    <i class="bi bi-arrow-right-circle text-4xl text-gray-300"></i>
                                </div>
                                <p class="text-gray-500 font-medium mb-2">No hay referencias médicas</p>
                                <p class="text-sm text-gray-400 mb-4">Crea una nueva referencia</p>
                                <a href="{{ route('ordenes-medicas.create', ['tipo' => 'referencia']) }}" class="btn btn-sm btn-primary">
                                    <i class="bi bi-plus-lg"></i>
                                    Nueva Referencia
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if(isset($referencias) && $referencias->hasPages())
        <div class="p-6 border-t border-gray-200">
            {{ $referencias->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
