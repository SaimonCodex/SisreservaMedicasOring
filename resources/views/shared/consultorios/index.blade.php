@extends('layouts.admin')

@section('title', 'Consultorios')

@section('content')
<div class="mb-6">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h2 class="text-3xl font-display font-bold text-gray-900">Consultorios</h2>
            <p class="text-gray-500 mt-1">Gestión de consultorios y espacios médicos</p>
        </div>
        <a href="{{ route('consultorios.create') }}" class="btn btn-primary shadow-lg">
            <i class="bi bi-plus-lg mr-2"></i>
            Nuevo Consultorio
        </a>
    </div>
</div>

<!-- Filtros -->
<div class="card p-6 mb-6">
    <form method="GET" action="{{ route('consultorios.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="md:col-span-2">
            <label class="form-label">Buscar</label>
            <input type="text" name="buscar" class="input" placeholder="Número, piso, nombre..." value="{{ request('buscar') }}">
        </div>

        <div>
            <label class="form-label">Piso</label>
            <select name="piso" class="form-select">
                <option value="">Todos</option>
                <option value="1">Piso 1</option>
                <option value="2">Piso 2</option>
                <option value="3">Piso 3</option>
            </select>
        </div>

        <div>
            <label class="form-label">Estado</label>
            <select name="status" class="form-select">
                <option value="">Todos</option>
                <option value="1">Disponibles</option>
                <option value="0">No Disponibles</option>
            </select>
        </div>

        <div class="md:col-span-4 flex gap-2">
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-funnel mr-1"></i> Filtrar
            </button>
            <a href="{{ route('consultorios.index') }}" class="btn btn-outline">
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
                <p class="text-2xl font-bold text-gray-900">8</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-medical-50 flex items-center justify-center">
                <i class="bi bi-building text-medical-600 text-2xl"></i>
            </div>
        </div>
    </div>

    <div class="card p-4 border-l-4 border-l-success-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 mb-1">Disponibles</p>
                <p class="text-2xl font-bold text-gray-900">6</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-success-50 flex items-center justify-center">
                <i class="bi bi-check-circle text-success-600 text-2xl"></i>
            </div>
        </div>
    </div>

    <div class="card p-4 border-l-4 border-l-warning-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 mb-1">En Uso Hoy</p>
                <p class="text-2xl font-bold text-gray-900">5</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-warning-50 flex items-center justify-center">
                <i class="bi bi-door-open text-warning-600 text-2xl"></i>
            </div>
        </div>
    </div>

    <div class="card p-4 border-l-4 border-l-info-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 mb-1">Ocupación</p>
                <p class="text-2xl font-bold text-gray-900">78%</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-info-50 flex items-center justify-center">
                <i class="bi bi-graph-up text-info-600 text-2xl"></i>
            </div>
        </div>
    </div>
</div>

<!-- Grid de Consultorios -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    <!-- Consultorio 101 -->
    <div class="card p-0 overflow-hidden hover:shadow-xl transition-shadow">
        <div class="bg-gradient-to-br from-medical-500 to-medical-600 p-6 text-white">
            <div class="flex items-center justify-between mb-3">
                <div>
                    <h3 class="text-3xl font-bold">101</h3>
                    <p class="text-white/80 text-sm">Piso 1</p>
                </div>
                <span class="badge bg-success-500 text-white border-2 border-white/30">Disponible</span>
            </div>
            <p class="text-white/90 font-medium">Consultorio General</p>
        </div>
        
        <div class="p-6">
            <div class="space-y-3 mb-4 text-sm">
                <div class="flex items-center gap-2 text-gray-600">
                    <i class="bi bi-person-badge text-medical-600"></i>
                    <span>Dr. Carlos López</span>
                </div>
                <div class="flex items-center gap-2 text-gray-600">
                    <i class="bi bi-bookmark text-medical-600"></i>
                    <span>Traumatología</span>
                </div>
                <div class="flex items-center gap-2 text-gray-600">
                    <i class="bi bi-rulers text-medical-600"></i>
                    <span>25 m²</span>
                </div>
            </div>
            
            <div class="flex gap-2">
                <a href="{{ route('consultorios.show', 1) }}" class="btn btn-sm btn-outline flex-1">
                    <i class="bi bi-eye mr-1"></i> Ver
                </a>
                <a href="{{ route('consultorios.horarios', 1) }}" class="btn btn-sm btn-ghost text-info-600">
                    <i class="bi bi-clock"></i>
                </a>
                <a href="{{ route('consultorios.edit', 1) }}" class="btn btn-sm btn-ghost text-warning-600">
                    <i class="bi bi-pencil"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Consultorio 205 -->
    <div class="card p-0 overflow-hidden hover:shadow-xl transition-shadow">
        <div class="bg-gradient-to-br from-success-500 to-success-600 p-6 text-white">
            <div class="flex items-center justify-between mb-3">
                <div>
                    <h3 class="text-3xl font-bold">205</h3>
                    <p class="text-white/80 text-sm">Piso 2</p>
                </div>
                <span class="badge bg-warning-500 text-white border-2 border-white/30">En Uso</span>
            </div>
            <p class="text-white/90 font-medium">Consultorio Cardiología</p>
        </div>
        
        <div class="p-6">
            <div class="space-y-3 mb-4 text-sm">
                <div class="flex items-center gap-2 text-gray-600">
                    <i class="bi bi-person-badge text-success-600"></i>
                    <span>Dr. Juan Pérez</span>
                </div>
                <div class="flex items-center gap-2 text-gray-600">
                    <i class="bi bi-bookmark text-success-600"></i>
                    <span>Cardiología</span>
                </div>
                <div class="flex items-center gap-2 text-gray-600">
                    <i class="bi bi-rulers text-success-600"></i>
                    <span>30 m²</span>
                </div>
            </div>
            
            <div class="flex gap-2">
                <a href="{{ route('consultorios.show', 2) }}" class="btn btn-sm btn-outline flex-1">
                    <i class="bi bi-eye mr-1"></i> Ver
                </a>
                <a href="{{ route('consultorios.horarios', 2) }}" class="btn btn-sm btn-ghost text-info-600">
                    <i class="bi bi-clock"></i>
                </a>
                <a href="{{ route('consultorios.edit', 2) }}" class="btn btn-sm btn-ghost text-warning-600">
                    <i class="bi bi-pencil"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Consultorio 310 -->
    <div class="card p-0 overflow-hidden hover:shadow-xl transition-shadow">
        <div class="bg-gradient-to-br from-warning-500 to-warning-600 p-6 text-white">
            <div class="flex items-center justify-between mb-3">
                <div>
                    <h3 class="text-3xl font-bold">310</h3>
                    <p class="text-white/80 text-sm">Piso 3</p>
                </div>
                <span class="badge bg-success-500 text-white border-2 border-white/30">Disponible</span>
            </div>
            <p class="text-white/90 font-medium">Consultorio Pediatría</p>
        </div>
        
        <div class="p-6">
            <div class="space-y-3 mb-4 text-sm">
                <div class="flex items-center gap-2 text-gray-600">
                    <i class="bi bi-person-badge text-warning-600"></i>
                    <span>Dra. María González</span>
                </div>
                <div class="flex items-center gap-2 text-gray-600">
                    <i class="bi bi-bookmark text-warning-600"></i>
                    <span>Pediatría</span>
                </div>
                <div class="flex items-center gap-2 text-gray-600">
                    <i class="bi bi-rulers text-warning-600"></i>
                    <span>28 m²</span>
                </div>
            </div>
            
            <div class="flex gap-2">
                <a href="{{ route('consultorios.show', 3) }}" class="btn btn-sm btn-outline flex-1">
                    <i class="bi bi-eye mr-1"></i> Ver
                </a>
                <a href="{{ route('consultorios.horarios', 3) }}" class="btn btn-sm btn-ghost text-info-600">
                    <i class="bi bi-clock"></i>
                </a>
                <a href="{{ route('consultorios.edit', 3) }}" class="btn btn-sm btn-ghost text-warning-600">
                    <i class="bi bi-pencil"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Consultorio 102 -->
    <div class="card p-0 overflow-hidden hover:shadow-xl transition-shadow">
        <div class="bg-gradient-to-br from-info-500 to-info-600 p-6 text-white">
            <div class="flex items-center justify-between mb-3">
                <div>
                    <h3 class="text-3xl font-bold">102</h3>
                    <p class="text-white/80 text-sm">Piso 1</p>
                </div>
                <span class="badge bg-success-500 text-white border-2 border-white/30">Disponible</span>
            </div>
            <p class="text-white/90 font-medium">Medicina General</p>
        </div>
        
        <div class="p-6">
            <div class="space-y-3 mb-4 text-sm">
                <div class="flex items-center gap-2 text-gray-600">
                    <i class="bi bi-person-badge text-info-600"></i>
                    <span>Sin asignar</span>
                </div>
                <div class="flex items-center gap-2 text-gray-600">
                    <i class="bi bi-bookmark text-info-600"></i>
                    <span>Disponible</span>
                </div>
                <div class="flex items-center gap-2 text-gray-600">
                    <i class="bi bi-rulers text-info-600"></i>
                    <span>22 m²</span>
                </div>
            </div>
            
            <div class="flex gap-2">
                <a href="{{ route('consultorios.show', 4) }}" class="btn btn-sm btn-outline flex-1">
                    <i class="bi bi-eye mr-1"></i> Ver
                </a>
                <a href="{{ route('consultorios.horarios', 4) }}" class="btn btn-sm btn-ghost text-info-600">
                    <i class="bi bi-clock"></i>
                </a>
                <a href="{{ route('consultorios.edit', 4) }}" class="btn btn-sm btn-ghost text-warning-600">
                    <i class="bi bi-pencil"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Consultorio 201 - Mantenimiento -->
    <div class="card p-0 overflow-hidden hover:shadow-xl transition-shadow opacity-60">
        <div class="bg-gradient-to-br from-gray-400 to-gray-500 p-6 text-white">
            <div class="flex items-center justify-between mb-3">
                <div>
                    <h3 class="text-3xl font-bold">201</h3>
                    <p class="text-white/80 text-sm">Piso 2</p>
                </div>
                <span class="badge bg-danger-500 text-white border-2 border-white/30">Mantenimiento</span>
            </div>
            <p class="text-white/90 font-medium">Fuera de servicio</p>
        </div>
        
        <div class="p-6">
            <div class="space-y-3 mb-4 text-sm">
                <div class="flex items-center gap-2 text-gray-500">
                    <i class="bi bi-tools"></i>
                    <span>En reparación</span>
                </div>
                <div class="flex items-center gap-2 text-gray-500">
                    <i class="bi bi-calendar"></i>
                    <span>Disponible: 20/01/2026</span>
                </div>
            </div>
            
            <div class="flex gap-2">
                <a href="{{ route('consultorios.show', 5) }}" class="btn btn-sm btn-outline flex-1">
                    <i class="bi bi-eye mr-1"></i> Ver
                </a>
                <a href="{{ route('consultorios.edit', 5) }}" class="btn btn-sm btn-ghost text-warning-600">
                    <i class="bi bi-pencil"></i>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
