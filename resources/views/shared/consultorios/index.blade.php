@extends('layouts.admin')

@section('title', 'Consultorios')

@section('content')
<div class="mb-6">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h2 class="text-3xl font-display font-bold text-gray-900">Consultorios</h2>
            <p class="text-gray-500 mt-1">Gestión de consultorios y espacios médicos</p>
        </div>
        @if(auth()->user()->administrador && auth()->user()->administrador->tipo_admin === 'Root')
        <a href="{{ route('consultorios.create') }}" class="btn btn-primary shadow-lg">
            <i class="bi bi-plus-lg mr-2"></i>
            Nuevo Consultorio
        </a>
        @endif
    </div>
</div>

<!-- Filtros -->
<div class="card p-6 mb-6">
    <form method="GET" action="{{ route('consultorios.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="md:col-span-2">
            <label class="form-label">Buscar</label>
            <input type="text" name="buscar" class="input" placeholder="Nombre, ubicación, descripción..." value="{{ request('buscar') }}">
        </div>

        <div>
            <label class="form-label">Estado</label>
            <select name="status" class="form-select">
                <option value="">Todos</option>
                <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Activos</option>
                <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Inactivos</option>
            </select>
        </div>

        <div class="flex gap-2 items-end">
            <button type="submit" class="btn btn-primary w-full md:w-auto">
                <i class="bi bi-funnel mr-1"></i> Filtrar
            </button>
            <a href="{{ route('consultorios.index') }}" class="btn btn-outline w-full md:w-auto text-center" title="Limpiar filtros">
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
                <p class="text-sm text-gray-500 mb-1">Total Consultorios</p>
                <p class="text-2xl font-bold text-gray-900">{{ $totalConsultorios }}</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-medical-50 flex items-center justify-center">
                <i class="bi bi-building text-medical-600 text-2xl"></i>
            </div>
        </div>
    </div>

    <div class="card p-4 border-l-4 border-l-success-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 mb-1">Activos</p>
                <p class="text-2xl font-bold text-gray-900">{{ $consultoriosActivos }}</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-success-50 flex items-center justify-center">
                <i class="bi bi-check-circle text-success-600 text-2xl"></i>
            </div>
        </div>
    </div>

    <div class="card p-4 border-l-4 border-l-warning-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 mb-1">Ciudades</p>
                <p class="text-2xl font-bold text-gray-900">{{ $totalCiudades }}</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-warning-50 flex items-center justify-center">
                <i class="bi bi-geo-alt text-warning-600 text-2xl"></i>
            </div>
        </div>
    </div>

    <div class="card p-4 border-l-4 border-l-info-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 mb-1">Médicos Asignados</p>
                <p class="text-2xl font-bold text-gray-900">{{ $totalMedicosAsignados }}</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-info-50 flex items-center justify-center">
                <i class="bi bi-people text-info-600 text-2xl"></i>
            </div>
        </div>
    </div>
</div>

<!-- Grid de Consultorios -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @php
        $styles = [
            ['gradient' => 'from-blue-500 to-blue-600', 'text' => 'text-blue-600', 'bg_badge' => 'bg-blue-100', 'text_badge' => 'text-blue-700'],
            ['gradient' => 'from-emerald-500 to-emerald-600', 'text' => 'text-emerald-600', 'bg_badge' => 'bg-emerald-100', 'text_badge' => 'text-emerald-700'],
            ['gradient' => 'from-violet-500 to-violet-600', 'text' => 'text-violet-600', 'bg_badge' => 'bg-violet-100', 'text_badge' => 'text-violet-700'],
            ['gradient' => 'from-rose-500 to-rose-600', 'text' => 'text-rose-600', 'bg_badge' => 'bg-rose-100', 'text_badge' => 'text-rose-700'],
            ['gradient' => 'from-amber-500 to-amber-600', 'text' => 'text-amber-600', 'bg_badge' => 'bg-amber-100', 'text_badge' => 'text-amber-700'],
            ['gradient' => 'from-cyan-500 to-cyan-600', 'text' => 'text-cyan-600', 'bg_badge' => 'bg-cyan-100', 'text_badge' => 'text-cyan-700'],
            ['gradient' => 'from-medical-500 to-medical-600', 'text' => 'text-medical-600', 'bg_badge' => 'bg-medical-100', 'text_badge' => 'text-medical-700'],
        ];
    @endphp

    @forelse ($consultorios as $consultorio)
    @php
        $style = $styles[$consultorio->id % count($styles)];
    @endphp
    
    <div class="card p-0 overflow-hidden hover:shadow-xl transition-shadow {{ !$consultorio->status ? 'opacity-70' : '' }}">
        <div class="bg-gradient-to-br {{ $consultorio->status ? $style['gradient'] : 'from-gray-400 to-gray-500' }} p-6 text-white">
            <div class="flex items-center justify-between mb-3">
                <div>
                    <h3 class="text-2xl font-bold truncate max-w-[180px]" title="{{ $consultorio->nombre }}">{{ $consultorio->nombre }}</h3>
                    <p class="text-white/80 text-sm flex items-center gap-1">
                        <i class="bi bi-geo-alt-fill text-xs"></i> 
                        {{ $consultorio->ciudad->ciudad ?? 'N/A' }}
                    </p>
                </div>
                <span class="badge {{ $consultorio->status ? 'bg-white/20' : 'bg-danger-500' }} text-white border-2 border-white/30">
                    {{ $consultorio->status ? 'Activo' : 'Inactivo' }}
                </span>
            </div>
            <p class="text-white/90 font-medium text-sm truncate">{{ $consultorio->direccion_detallada ?? 'Sin dirección detallada' }}</p>
        </div>
        
        <div class="p-6">
            <div class="space-y-3 mb-4 text-sm">
                <div class="flex items-center gap-2 text-gray-600">
                    <i class="bi bi-person-badge {{ $style['text'] }}"></i>
                    <span>{{ $consultorio->medicos_count }} Médicos asignados</span>
                </div>
                <!-- Especialidades -->
                <div class="flex items-start gap-2 text-gray-600">
                    <i class="bi bi-bookmark {{ $style['text'] }} mt-0.5"></i>
                    <div class="flex flex-wrap gap-1">
                        @forelse($consultorio->especialidades as $especialidad)
                            <span class="px-2 py-0.5 rounded-full text-xs {{ $style['bg_badge'] }} {{ $style['text_badge'] }} border border-transparent">
                                {{ $especialidad->nombre }}
                            </span>
                        @empty
                            <span class="text-gray-400 italic">Sin especialidades</span>
                        @endforelse
                    </div>
                </div>
                <div class="flex items-center gap-2 text-gray-600">
                    <i class="bi bi-telephone {{ $style['text'] }}"></i>
                    <span>{{ $consultorio->telefono ?? 'Sin teléfono' }}</span>
                </div>
            </div>
            
            <div class="flex gap-2">
                <a href="{{ route('consultorios.show', $consultorio->id) }}" class="btn btn-sm btn-outline flex-1">
                    <i class="bi bi-eye mr-1"></i> Ver
                </a>
                <a href="{{ route('consultorios.horarios', $consultorio->id) }}" class="btn btn-sm btn-ghost text-info-600" title="Ver Horarios">
                    <i class="bi bi-clock"></i>
                </a>
                @if(auth()->user()->administrador && auth()->user()->administrador->tipo_admin === 'Root')
                <a href="{{ route('consultorios.edit', $consultorio->id) }}" class="btn btn-sm btn-ghost text-warning-600" title="Editar">
                    <i class="bi bi-pencil"></i>
                </a>
                @endif
            </div>
        </div>
    </div>
    @empty
    <div class="col-span-full py-12 text-center bg-gray-50 rounded-xl border border-dashed border-gray-300">
        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 mb-4">
            <i class="bi bi-building-slash text-gray-400 text-3xl"></i>
        </div>
        <h3 class="text-lg font-medium text-gray-900">No se encontraron consultorios</h3>
        <p class="text-gray-500 mt-1 mb-4">Intenta ajustar los filtros de búsqueda</p>
        @if(auth()->user()->administrador && auth()->user()->administrador->tipo_admin === 'Root')
        <a href="{{ route('consultorios.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg mr-2"></i> Crear Nuevo Consultorio
        </a>
        @endif
    </div>
    @endforelse
</div>

<div class="mt-6">
    {{ $consultorios->links() }}
</div>
@endsection
