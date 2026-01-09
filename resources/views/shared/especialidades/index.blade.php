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
                <p class="text-2xl font-bold text-gray-900">12</p>
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
                <p class="text-2xl font-bold text-gray-900">11</p>
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
                <p class="text-2xl font-bold text-gray-900">24</p>
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
                <p class="text-2xl font-bold text-gray-900">489</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-info-50 flex items-center justify-center">
                <i class="bi bi-calendar-check text-info-600 text-2xl"></i>
            </div>
        </div>
    </div>
</div>

<!-- Grid de Especialidades -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    <!-- Cardiología -->
    <div class="card p-0 overflow-hidden hover:shadow-xl transition-shadow">
        <div class="bg-gradient-to-br from-medical-500 to-medical-600 p-6 text-white">
            <div class="flex items-center justify-between mb-3">
                <div class="w-14 h-14 rounded-xl bg-white/20 backdrop-blur-sm flex items-center justify-center border-2 border-white/30">
                    <i class="bi bi-heart-pulse text-3xl"></i>
                </div>
                <span class="badge bg-white/20 text-white border border-white/30">Activa</span>
            </div>
            <h3 class="text-2xl font-bold mb-1">Cardiología</h3>
            <p class="text-white/80 text-sm">Enfermedades del corazón</p>
        </div>
        
        <div class="p-6">
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div class="text-center p-3 bg-gray-50 rounded-lg">
                    <p class="text-2xl font-bold text-medical-600">5</p>
                    <p class="text-xs text-gray-500">Médicos</p>
                </div>
                <div class="text-center p-3 bg-gray-50 rounded-lg">
                    <p class="text-2xl font-bold text-success-600">143</p>
                    <p class="text-xs text-gray-500">Citas/Mes</p>
                </div>
            </div>
            
            <div class="flex gap-2">
                <a href="{{ route('especialidades.show', 1) }}" class="btn btn-sm btn-outline flex-1">
                    <i class="bi bi-eye mr-1"></i> Ver
                </a>
                <a href="{{ route('especialidades.edit', 1) }}" class="btn btn-sm btn-ghost text-warning-600">
                    <i class="bi bi-pencil"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Pediatría -->
    <div class="card p-0 overflow-hidden hover:shadow-xl transition-shadow">
        <div class="bg-gradient-to-br from-success-500 to-success-600 p-6 text-white">
            <div class="flex items-center justify-between mb-3">
                <div class="w-14 h-14 rounded-xl bg-white/20 backdrop-blur-sm flex items-center justify-center border-2 border-white/30">
                    <i class="bi bi-emoji-smile text-3xl"></i>
                </div>
                <span class="badge bg-white/20 text-white border border-white/30">Activa</span>
            </div>
            <h3 class="text-2xl font-bold mb-1">Pediatría</h3>
            <p class="text-white/80 text-sm">Atención infantil</p>
        </div>
        
        <div class="p-6">
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div class="text-center p-3 bg-gray-50 rounded-lg">
                    <p class="text-2xl font-bold text-success-600">4</p>
                    <p class="text-xs text-gray-500">Médicos</p>
                </div>
                <div class="text-center p-3 bg-gray-50 rounded-lg">
                    <p class="text-2xl font-bold text-warning-600">98</p>
                    <p class="text-xs text-gray-500">Citas/Mes</p>
                </div>
            </div>
            
            <div class="flex gap-2">
                <a href="{{ route('especialidades.show', 2) }}" class="btn btn-sm btn-outline flex-1">
                    <i class="bi bi-eye mr-1"></i> Ver
                </a>
                <a href="{{ route('especialidades.edit', 2) }}" class="btn btn-sm btn-ghost text-warning-600">
                    <i class="bi bi-pencil"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Traumatología -->
    <div class="card p-0 overflow-hidden hover:shadow-xl transition-shadow">
        <div class="bg-gradient-to-br from-warning-500 to-warning-600 p-6 text-white">
            <div class="flex items-center justify-between mb-3">
                <div class="w-14 h-14 rounded-xl bg-white/20 backdrop-blur-sm flex items-center justify-center border-2 border-white/30">
                    <i class="bi bi-activity text-3xl"></i>
                </div>
                <span class="badge bg-white/20 text-white border border-white/30">Activa</span>
            </div>
            <h3 class="text-2xl font-bold mb-1">Traumatología</h3>
            <p class="text-white/80 text-sm">Sistema musculoesquelético</p>
        </div>
        
        <div class="p-6">
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div class="text-center p-3 bg-gray-50 rounded-lg">
                    <p class="text-2xl font-bold text-warning-600">3</p>
                    <p class="text-xs text-gray-500">Médicos</p>
                </div>
                <div class="text-center p-3 bg-gray-50 rounded-lg">
                    <p class="text-2xl font-bold text-info-600">76</p>
                    <p class="text-xs text-gray-500">Citas/Mes</p>
                </div>
            </div>
            
            <div class="flex gap-2">
                <a href="{{ route('especialidades.show', 3) }}" class="btn btn-sm btn-outline flex-1">
                    <i class="bi bi-eye mr-1"></i> Ver
                </a>
                <a href="{{ route('especialidades.edit', 3) }}" class="btn btn-sm btn-ghost text-warning-600">
                    <i class="bi bi-pencil"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Medicina General -->
    <div class="card p-0 overflow-hidden hover:shadow-xl transition-shadow">
        <div class="bg-gradient-to-br from-info-500 to-info-600 p-6 text-white">
            <div class="flex items-center justify-between mb-3">
                <div class="w-14 h-14 rounded-xl bg-white/20 backdrop-blur-sm flex items-center justify-center border-2 border-white/30">
                    <i class="bi bi-clipboard-pulse text-3xl"></i>
                </div>
                <span class="badge bg-white/20 text-white border border-white/30">Activa</span>
            </div>
            <h3 class="text-2xl font-bold mb-1">Medicina General</h3>
            <p class="text-white/80 text-sm">Atención médica general</p>
        </div>
        
        <div class="p-6">
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div class="text-center p-3 bg-gray-50 rounded-lg">
                    <p class="text-2xl font-bold text-info-600">6</p>
                    <p class="text-xs text-gray-500">Médicos</p>
                </div>
                <div class="text-center p-3 bg-gray-50 rounded-lg">
                    <p class="text-2xl font-bold text-success-600">172</p>
                    <p class="text-xs text-gray-500">Citas/Mes</p>
                </div>
            </div>
            
            <div class="flex gap-2">
                <a href="{{ route('especialidades.show', 4) }}" class="btn btn-sm btn-outline flex-1">
                    <i class="bi bi-eye mr-1"></i> Ver
                </a>
                <a href="{{ route('especialidades.edit', 4) }}" class="btn btn-sm btn-ghost text-warning-600">
                    <i class="bi bi-pencil"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Oftalmología -->
    <div class="card p-0 overflow-hidden hover:shadow-xl transition-shadow opacity-60">
        <div class="bg-gradient-to-br from-gray-400 to-gray-500 p-6 text-white">
            <div class="flex items-center justify-between mb-3">
                <div class="w-14 h-14 rounded-xl bg-white/20 backdrop-blur-sm flex items-center justify-center border-2 border-white/30">
                    <i class="bi bi-eye text-3xl"></i>
                </div>
                <span class="badge bg-white/20 text-white border border-white/30">Inactiva</span>
            </div>
            <h3 class="text-2xl font-bold mb-1">Oftalmología</h3>
            <p class="text-white/80 text-sm">Salud visual</p>
        </div>
        
        <div class="p-6">
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div class="text-center p-3 bg-gray-50 rounded-lg">
                    <p class="text-2xl font-bold text-gray-600">0</p>
                    <p class="text-xs text-gray-500">Médicos</p>
                </div>
                <div class="text-center p-3 bg-gray-50 rounded-lg">
                    <p class="text-2xl font-bold text-gray-600">0</p>
                    <p class="text-xs text-gray-500">Citas/Mes</p>
                </div>
            </div>
            
            <div class="flex gap-2">
                <a href="{{ route('especialidades.show', 5) }}" class="btn btn-sm btn-outline flex-1">
                    <i class="bi bi-eye mr-1"></i> Ver
                </a>
                <a href="{{ route('especialidades.edit', 5) }}" class="btn btn-sm btn-ghost text-warning-600">
                    <i class="bi bi-pencil"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Dermatología -->
    <div class="card p-0 overflow-hidden hover:shadow-xl transition-shadow">
        <div class="bg-gradient-to-br from-danger-500 to-danger-600 p-6 text-white">
            <div class="flex items-center justify-between mb-3">
                <div class="w-14 h-14 rounded-xl bg-white/20 backdrop-blur-sm flex items-center justify-center border-2 border-white/30">
                    <i class="bi bi-droplet text-3xl"></i>
                </div>
                <span class="badge bg-white/20 text-white border border-white/30">Activa</span>
            </div>
            <h3 class="text-2xl font-bold mb-1">Dermatología</h3>
            <p class="text-white/80 text-sm">Salud de la piel</p>
        </div>
        
        <div class="p-6">
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div class="text-center p-3 bg-gray-50 rounded-lg">
                    <p class="text-2xl font-bold text-danger-600">2</p>
                    <p class="text-xs text-gray-500">Médicos</p>
                </div>
                <div class="text-center p-3 bg-gray-50 rounded-lg">
                    <p class="text-2xl font-bold text-medical-600">54</p>
                    <p class="text-xs text-gray-500">Citas/Mes</p>
                </div>
            </div>
            
            <div class="flex gap-2">
                <a href="{{ route('especialidades.show', 6) }}" class="btn btn-sm btn-outline flex-1">
                    <i class="bi bi-eye mr-1"></i> Ver
                </a>
                <a href="{{ route('especialidades.edit', 6) }}" class="btn btn-sm btn-ghost text-warning-600">
                    <i class="bi bi-pencil"></i>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
