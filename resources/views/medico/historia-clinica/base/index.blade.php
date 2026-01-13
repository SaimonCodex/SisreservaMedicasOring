@extends('layouts.medico')

@section('title', 'Historias Clínicas')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-display font-bold text-gray-900">Historias Clínicas</h1>
            <p class="text-gray-600 mt-1">Registro médico completo de pacientes</p>
        </div>
        <a href="{{ url('historia-clinica/base/create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i>
            <span>Nueva Historia Clínica</span>
        </a>
    </div>

    <!-- Filters -->
    <div class="card p-6">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <div>
                <label class="form-label">Paciente</label>
                <input type="text" name="paciente" class="input" placeholder="Buscar por nombre o cédula..." value="{{ request('paciente') }}">
            </div>
            <div>
                <label class="form-label">Tipo de Sangre</label>
                <select name="tipo_sangre" class="form-select">
                    <option value="">Todos</option>
                    <option value="A+" {{ request('tipo_sangre') == 'A+' ? 'selected' : '' }}>A+</option>
                    <option value="A-" {{ request('tipo_sangre') == 'A-' ? 'selected' : '' }}>A-</option>
                    <option value="B+" {{ request('tipo_sangre') == 'B+' ? 'selected' : '' }}>B+</option>
                    <option value="B-" {{ request('tipo_sangre') == 'B-' ? 'selected' : '' }}>B-</option>
                    <option value="AB+" {{ request('tipo_sangre') == 'AB+' ? 'selected' : '' }}>AB+</option>
                    <option value="AB-" {{ request('tipo_sangre') == 'AB-' ? 'selected' : '' }}>AB-</option>
                    <option value="O+" {{ request('tipo_sangre') == 'O+' ? 'selected' : '' }}>O+</option>
                    <option value="O-" {{ request('tipo_sangre') == 'O-' ? 'selected' : '' }}>O-</option>
                </select>
            </div>
            <div>
                <label class="form-label">Fecha Desde</label>
                <input type="date" name="fecha_desde" class="input" value="{{ request('fecha_desde') }}">
            </div>
            <div>
                <label class="form-label">Fecha Hasta</label>
                <input type="date" name="fecha_hasta" class="input" value="{{ request('fecha_hasta') }}">
            </div>
            <div class="flex items-end gap-2">
                <button type="submit" class="btn btn-primary flex-1">
                    <i class="bi bi-search"></i>
                    Buscar
                </button>
                <a href="{{ route('historia-clinica.base.index') }}" class="btn btn-outline">
                    <i class="bi bi-x-lg"></i>
                </a>
            </div>
        </form>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="card p-4 bg-gradient-to-br from-purple-50 to-purple-100 border-purple-200">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-lg bg-purple-600 flex items-center justify-center">
                    <i class="bi bi-file-medical text-white text-xl"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-purple-900">{{ $stats['total'] ?? 0 }}</p>
                    <p class="text-sm text-purple-700">Total Historias</p>
                </div>
            </div>
        </div>
        <div class="card p-4 bg-gradient-to-br from-blue-50 to-blue-100 border-blue-200">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-lg bg-blue-600 flex items-center justify-center">
                    <i class="bi bi-calendar-week text-white text-xl"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-blue-900">{{ $stats['este_mes'] ?? 0 }}</p>
                    <p class="text-sm text-blue-700">Este Mes</p>
                </div>
            </div>
        </div>
        <div class="card p-4 bg-gradient-to-br from-emerald-50 to-emerald-100 border-emerald-200">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-lg bg-emerald-600 flex items-center justify-center">
                    <i class="bi bi-activity text-white text-xl"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-emerald-900">{{ $stats['activas'] ?? 0 }}</p>
                    <p class="text-sm text-emerald-700">Activas</p>
                </div>
            </div>
        </div>
        <div class="card p-4 bg-gradient-to-br from-amber-50 to-amber-100 border-amber-200">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-lg bg-amber-600 flex items-center justify-center">
                    <i class="bi bi-clock-history text-white text-xl"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-amber-900">{{ $stats['recientes'] ?? 0 }}</p>
                    <p class="text-sm text-amber-700">Última Semana</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Historias List -->
    <div class="card">
        <div class="overflow-x-auto">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Paciente</th>
                        <th>Datos Básicos</th>
                        <th>Información Clínica</th>
                        <th>Evoluciones</th>
                        <th>Fecha Registro</th>
                        <th class="w-40">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($historias ?? [] as $historia)
                    <tr>
                        <td>
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-purple-500 to-purple-600 flex items-center justify-center text-white font-bold text-sm">
                                    {{ strtoupper(substr($historia->paciente->primer_nombre ?? 'P', 0, 1)) }}{{ strtoupper(substr($historia->paciente->primer_apellido ?? 'A', 0, 1)) }}
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-900">
                                        {{ $historia->paciente->primer_nombre ?? 'N/A' }} 
                                        {{ $historia->paciente->primer_apellido ?? '' }}
                                    </p>
                                    <p class="text-sm text-gray-500">{{ $historia->paciente->cedula ?? 'N/A' }}</p>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="space-y-1">
                                <div class="flex items-center gap-2 text-sm">
                                    <i class="bi bi-droplet-fill text-rose-500"></i>
                                    <span class="font-semibold">{{ $historia->tipo_sangre ?? 'N/A' }}</span>
                                </div>
                                <div class="flex items-center gap-2 text-sm text-gray-600">
                                    <i class="bi bi-calendar"></i>
                                    {{ isset($historia->paciente->fecha_nacimiento) ? \Carbon\Carbon::parse($historia->paciente->fecha_nacimiento)->age . ' años' : 'N/A' }}
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="flex flex-wrap gap-1">
                                @if($historia->alergias ?? null)
                                <span class="badge badge-danger text-xs">
                                    <i class="bi bi-exclamation-triangle"></i> Alergias
                                </span>
                                @endif
                                @if($historia->antecedentes_familiares ?? null)
                                <span class="badge badge-warning text-xs">
                                    <i class="bi bi-people"></i> Ant. Familiares
                                </span>
                                @endif
                                @if($historia->antecedentes_personales ?? null)
                                <span class="badge badge-info text-xs">
                                    <i class="bi bi-person"></i> Ant. Personales
                                </span>
                                @endif
                            </div>
                        </td>
                        <td>
                            <div class="flex items-center gap-2">
                                <span class="text-lg font-bold text-purple-600">{{ $historia->evoluciones_count ?? 0 }}</span>
                                <span class="text-sm text-gray-500">registros</span>
                            </div>
                        </td>
                        <td>
                            <div class="flex flex-col">
                                <span class="text-sm font-semibold text-gray-900">
                                    {{ isset($historia->created_at) ? \Carbon\Carbon::parse($historia->created_at)->format('d/m/Y') : 'N/A' }}
                                </span>
                                <span class="text-xs text-gray-500">
                                    {{ isset($historia->created_at) ? \Carbon\Carbon::parse($historia->created_at)->diffForHumans() : '' }}
                                </span>
                            </div>
                        </td>
                        <td>
                            <div class="flex items-center gap-2">
                                <a href="{{ route('historia-clinica.base.show', $historia->paciente_id) }}" class="btn btn-sm btn-outline" title="Ver">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('historia-clinica.base.edit', $historia->paciente_id) }}" class="btn btn-sm btn-primary" title="Editar">
                                    <i class="bi bi-pencil"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-12">
                            <div class="inline-flex flex-col items-center">
                                <div class="w-20 h-20 rounded-full bg-gray-100 flex items-center justify-center mb-4">
                                    <i class="bi bi-file-medical text-4xl text-gray-300"></i>
                                </div>
                                <p class="text-gray-500 font-medium mb-2">No se encontraron historias clínicas</p>
                                <p class="text-sm text-gray-400 mb-4">Crea una nueva historia clínica para comenzar</p>
                                <a href="{{ url('historia-clinica/base/create') }}" class="btn btn-sm btn-primary">
                                    <i class="bi bi-plus-lg"></i>
                                    Nueva Historia Clínica
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if(isset($historias) && $historias->hasPages())
        <div class="p-6 border-t border-gray-200">
            {{ $historias->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
