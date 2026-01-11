@extends('layouts.medico')

@section('title', 'Mis Citas Médicas')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-display font-bold text-gray-900">Mis Citas Médicas</h1>
            <p class="text-gray-600 mt-1">Consulta tu agenda y citas programadas</p>
        </div>
    </div>

    <!-- Filters -->
    <div class="card p-6">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <div>
                <label class="form-label">Fecha</label>
                <input type="date" name="fecha" class="input" value="{{ request('fecha') }}">
            </div>
            <div>
                <label class="form-label">Estado</label>
                <select name="status" class="form-select">
                    <option value="">Todos</option>
                    <option value="confirmada" {{ request('status') == 'confirmada' ? 'selected' : '' }}>Confirmada</option>
                    <option value="pendiente" {{ request('status') == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                    <option value="completada" {{ request('status') == 'completada' ? 'selected' : '' }}>Completada</option>
                    <option value="cancelada" {{ request('status') == 'cancelada' ? 'selected' : '' }}>Cancelada</option>
                </select>
            </div>
            <div>
                <label class="form-label">Paciente</label>
                <input type="text" name="paciente" class="input" placeholder="Buscar paciente..." value="{{ request('paciente') }}">
            </div>
            <div>
                <label class="form-label">Consultorio</label>
                <select name="consultorio" class="form-select">
                    <option value="">Todos</option>
                    @foreach($consultorios ?? [] as $consultorio)
                    <option value="{{ $consultorio->id }}" {{ request('consultorio') == $consultorio->id ? 'selected' : '' }}>
                        {{ $consultorio->nombre }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-end gap-2">
                <button type="submit" class="btn btn-primary flex-1">
                    <i class="bi bi-search"></i>
                    Filtrar
                </button>
                <a href="{{ route('citas.index') }}" class="btn btn-outline">
                    <i class="bi bi-x-lg"></i>
                </a>
            </div>
        </form>
    </div>

    <!-- Stats Summary -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="card p-4 bg-gradient-to-br from-blue-50 to-blue-100 border-blue-200">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-lg bg-blue-600 flex items-center justify-center">
                    <i class="bi bi-calendar-check text-white text-xl"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-blue-900">{{ $stats['confirmadas'] ?? 0 }}</p>
                    <p class="text-sm text-blue-700">Confirmadas</p>
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
        <div class="card p-4 bg-gradient-to-br from-rose-50 to-rose-100 border-rose-200">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-lg bg-rose-600 flex items-center justify-center">
                    <i class="bi bi-x-circle text-white text-xl"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-rose-900">{{ $stats['canceladas'] ?? 0 }}</p>
                    <p class="text-sm text-rose-700">Canceladas</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Citas List -->
    <div class="card">
        <div class="overflow-x-auto">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th class="w-32">Fecha y Hora</th>
                        <th>Paciente</th>
                        <th>Consultorio</th>
                        <th>Motivo</th>
                        <th class="w-32">Estado</th>
                        <th class="w-48">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($citas ?? [] as $cita)
                    <tr>
                        <td>
                            <div class="flex flex-col">
                                <span class="font-semibold text-gray-900">{{ \Carbon\Carbon::parse($cita->fecha_hora)->format('d/m/Y') }}</span>
                                <span class="text-sm text-gray-500">{{ \Carbon\Carbon::parse($cita->fecha_hora)->format('H:i A') }}</span>
                            </div>
                        </td>
                        <td>
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-emerald-500 to-emerald-600 flex items-center justify-center text-white font-bold">
                                    {{ strtoupper(substr($cita->paciente->primer_nombre, 0, 1)) }}{{ strtoupper(substr($cita->paciente->primer_apellido, 0, 1)) }}
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-900">{{ $cita->paciente->primer_nombre }} {{ $cita->paciente->primer_apellido }}</p>
                                    <p class="text-sm text-gray-500">{{ $cita->paciente->cedula }}</p>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="flex items-center gap-2 text-gray-700">
                                <i class="bi bi-building text-gray-400"></i>
                                {{ $cita->consultorio->nombre ?? 'N/A' }}
                            </span>
                        </td>
                        <td>
                            <p class="text-gray-900">{{ $cita->motivo }}</p>
                        </td>
                        <td>
                            @if($cita->status == 'confirmada')
                                <span class="badge badge-success">
                                    <i class="bi bi-check-circle"></i> Confirmada
                                </span>
                            @elseif($cita->status == 'pendiente')
                                <span class="badge badge-warning">
                                    <i class="bi bi-clock"></i> Pendiente
                                </span>
                            @elseif($cita->status == 'completada')
                                <span class="badge badge-primary">
                                    <i class="bi bi-check-all"></i> Completada
                                </span>
                            @else
                                <span class="badge badge-danger">
                                    <i class="bi bi-x-circle"></i> Cancelada
                                </span>
                            @endif
                        </td>
                        <td>
                            <div class="flex items-center gap-2">
                                <a href="{{ route('citas.show', $cita->id) }}" class="btn btn-sm btn-outline" title="Ver Detalles">
                                    <i class="bi bi-eye"></i>
                                </a>
                                @if($cita->status == 'confirmada')
                                <a href="{{ route('historia-clinica.evoluciones.create', ['citaId' => $cita->id]) }}" class="btn btn-sm btn-success" title="Registrar Evolución">
                                    <i class="bi bi-file-earmark-medical"></i>
                                </a>
                                @endif
                                @if(in_array($cita->status, ['pendiente', 'confirmada']))
                                <form action="{{ route('citas.destroy', $cita->id) }}" method="POST" class="inline" onsubmit="return confirm('¿Está seguro de cancelar esta cita?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" title="Cancelar">
                                        <i class="bi bi-x-lg"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-12">
                            <div class="inline-flex flex-col items-center">
                                <div class="w-20 h-20 rounded-full bg-gray-100 flex items-center justify-center mb-4">
                                    <i class="bi bi-calendar-x text-4xl text-gray-300"></i>
                                </div>
                                <p class="text-gray-500 font-medium mb-2">No se encontraron citas</p>
                                <p class="text-sm text-gray-400">Intenta ajustar los filtros de búsqueda</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if(isset($citas) && $citas->hasPages())
        <div class="p-6 border-t border-gray-200">
            {{ $citas->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
