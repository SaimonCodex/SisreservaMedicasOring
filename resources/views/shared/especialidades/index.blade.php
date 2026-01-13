@extends('layouts.admin')

@section('title', 'Especialidades Médicas')

@section('content')
<div class="mb-6">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h2 class="text-3xl font-display font-bold text-gray-900">Especialidades Médicas</h2>
            <p class="text-gray-500 mt-1">Gestión de especialidades y áreas médicas</p>
        </div>
        <a href="{{ route('especialidades.create') }}" class="btn btn-primary shadow-lg">
            <i class="bi bi-plus-lg mr-2"></i>
            Nueva Especialidad
        </a>
    </div>
</div>

<!-- Búsqueda y Filtros -->
<div class="card p-6 mb-6">
    <form method="GET" action="{{ route('especialidades.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="md:col-span-2">
            <label class="form-label">Buscar Especialidad</label>
            <div class="relative">
                <i class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                <input type="text" name="buscar" class="input pl-10" placeholder="Nombre o descripción..." value="{{ request('buscar') }}">
            </div>
        </div>

        <div>
            <label class="form-label">Estado</label>
            <select name="status" class="form-select">
                <option value="">Todas</option>
                <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Activas</option>
                <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Inactivas</option>
            </select>
        </div>

        <div class="flex gap-2">
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-funnel mr-1"></i> Filtrar
            </button>
            <a href="{{ route('especialidades.index') }}" class="btn btn-outline">
                <i class="bi bi-x-lg"></i>
            </a>
        </div>
    </form>
</div>

<!-- Estadísticas -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
    <div class="card p-4 border-l-4 border-l-medical-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 mb-1">Total Especialidades</p>
                <p class="text-2xl font-bold text-gray-900">{{ $totalEspecialidades }}</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-medical-50 flex items-center justify-center">
                <i class="bi bi-bookmark text-medical-600 text-2xl"></i>
            </div>
        </div>
    </div>

    <div class="card p-4 border-l-4 border-l-success-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 mb-1">Activas</p>
                <p class="text-2xl font-bold text-gray-900">{{ $especialidadesActivas }}</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-success-50 flex items-center justify-center">
                <i class="bi bi-check-circle text-success-600 text-2xl"></i>
            </div>
        </div>
    </div>

    <div class="card p-4 border-l-4 border-l-warning-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 mb-1">Total Médicos</p>
                <p class="text-2xl font-bold text-gray-900">{{ $totalMedicos }}</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-warning-50 flex items-center justify-center">
                <i class="bi bi-person-badge text-warning-600 text-2xl"></i>
            </div>
        </div>
    </div>

    <div class="card p-4 border-l-4 border-l-info-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 mb-1">Citas del Mes</p>
                <p class="text-2xl font-bold text-gray-900">{{ $citasMes }}</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-info-50 flex items-center justify-center">
                <i class="bi bi-calendar-check text-info-600 text-2xl"></i>
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @forelse($especialidades as $especialidad)
    <div class="card p-0 overflow-hidden hover:shadow-xl transition-shadow {{ !$especialidad->status ? 'opacity-60' : '' }}">
        <div class="bg-gradient-to-br from-{{ $especialidad->color }}-500 to-{{ $especialidad->color }}-600 p-6 text-white">
            <div class="flex items-center justify-between mb-3">
                <div class="w-14 h-14 rounded-xl bg-white/20 backdrop-blur-sm flex items-center justify-center border-2 border-white/30">
                    <i class="bi bi-{{ $especialidad->icono ?? 'heart-pulse' }} text-3xl"></i>
                </div>
                <span class="badge bg-white/20 text-white border border-white/30">
                    {{ $especialidad->status ? 'Activa' : 'Inactiva' }}
                </span>
            </div>
            <h3 class="text-2xl font-bold mb-1">{{ $especialidad->nombre }}</h3>
            <p class="text-white/80 text-sm">{{ $especialidad->codigo ?? 'N/A' }}</p>
        </div>
        
        <div class="p-6">
            <div class="min-h-[3rem] mb-4">
                <p class="text-gray-600 text-sm line-clamp-2">{{ $especialidad->descripcion }}</p>
            </div>

            <div class="grid grid-cols-2 gap-4 mb-4">
                <div class="text-center p-3 bg-gray-50 rounded-lg">
                    <p class="text-2xl font-bold text-{{ $especialidad->color }}-600">
                        {{ $especialidad->medicos->count() }}
                    </p>
                    <p class="text-xs text-gray-500">Médicos</p>
                </div>
                <div class="text-center p-3 bg-gray-50 rounded-lg">
                    <p class="text-2xl font-bold text-gray-600">
                        {{ $especialidad->duracion_cita_default }}m
                    </p>
                    <p class="text-xs text-gray-500">Duración</p>
                </div>
            </div>
            
            <div class="flex gap-2">
                <a href="{{ route('especialidades.show', $especialidad->id) }}" class="btn btn-sm btn-outline flex-1">
                    <i class="bi bi-eye mr-1"></i> Ver
                </a>
                <a href="{{ route('especialidades.edit', $especialidad->id) }}" class="btn btn-sm btn-ghost text-warning-600">
                    <i class="bi bi-pencil"></i>
                </a>
            </div>
        </div>
    </div>
    @empty
    <div class="col-span-full">
        <div class="card p-12 text-center text-gray-500">
            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="bi bi-inbox text-3xl"></i>
            </div>
            <h3 class="text-lg font-bold text-gray-900">No hay especialidades registradas</h3>
            <p class="mb-4">Comienza registrando la primera especialidad médica.</p>
            <a href="{{ route('especialidades.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-lg mr-2"></i>
                Nueva Especialidad
            </a>
        </div>
    </div>
    @endforelse
</div>
<div class="mt-6">
    {{-- Pagination links if needed, or if controller paginates --}}
</div>
@endsection
