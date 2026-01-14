@extends('layouts.admin')

@section('title', 'Representantes')

@section('content')
<div class="mb-6">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h2 class="text-3xl font-display font-bold text-gray-900">Representantes</h2>
            <p class="text-gray-500 mt-1">Gestión de representantes de pacientes especiales</p>
        </div>
        <a href="{{ route('representantes.create') }}" class="btn btn-primary shadow-lg">
            <i class="bi bi-plus-lg mr-2"></i>
            Registrar Representante
        </a>
    </div>
</div>

<!-- Filtros -->
<div class="card p-6 mb-6">
    <form method="GET" action="{{ route('representantes.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <!-- Búsqueda -->
        <div class="md:col-span-2">
            <label class="form-label">Buscar Representante</label>
            <div class="relative">
                <i class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                <input type="text" name="nombre" 
                       class="input pl-10" 
                       placeholder="Nombre, cédula..."
                       value="{{ request('nombre') }}">
            </div>
        </div>

        <!-- Documento -->
        <div>
            <label class="form-label">Cédula</label>
            <input type="text" name="documento" 
                   class="input" 
                   placeholder="V-12345678"
                   value="{{ request('documento') }}">
        </div>

        <!-- Parentesco -->
        <div>
            <label class="form-label">Parentesco</label>
            <input type="text" name="parentesco" 
                   class="input" 
                   placeholder="Madre, Padre..."
                   value="{{ request('parentesco') }}">
        </div>

        <!-- Botones -->
        <div class="md:col-span-4 flex gap-3">
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-funnel mr-2"></i>
                Filtrar
            </button>
            <a href="{{ route('representantes.index') }}" class="btn btn-outline">
                <i class="bi bi-x-lg mr-2"></i>
                Limpiar
            </a>
        </div>
    </form>
</div>

<!-- Estadísticas -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
    <div class="card p-4 border-l-4 border-l-info-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 mb-1">Total Representantes</p>
                <p class="text-2xl font-bold text-gray-900">{{ $stats['total'] ?? 0 }}</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-info-50 flex items-center justify-center">
                <i class="bi bi-people text-info-600 text-2xl"></i>
            </div>
        </div>
    </div>

    <div class="card p-4 border-l-4 border-l-success-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 mb-1">Activos</p>
                <p class="text-2xl font-bold text-gray-900">{{ $stats['activos'] ?? 0 }}</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-success-50 flex items-center justify-center">
                <i class="bi bi-check-circle text-success-600 text-2xl"></i>
            </div>
        </div>
    </div>

    <div class="card p-4 border-l-4 border-l-warning-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 mb-1">Con Múltiples Pacientes</p>
                <p class="text-2xl font-bold text-gray-900">{{ $stats['multi_pacientes'] ?? 0 }}</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-warning-50 flex items-center justify-center">
                <i class="bi bi-diagram-3 text-warning-600 text-2xl"></i>
            </div>
        </div>
    </div>

    <div class="card p-4 border-l-4 border-l-medical-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 mb-1">Nuevos (mes)</p>
                <p class="text-2xl font-bold text-gray-900">{{ $stats['nuevos_mes'] ?? 0 }}</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-medical-50 flex items-center justify-center">
                <i class="bi bi-person-plus text-medical-600 text-2xl"></i>
            </div>
        </div>
    </div>
</div>

<!-- Tabla de Representantes -->
<div class="card overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gradient-to-r from-info-600 to-info-500 text-white">
                <tr>
                    <th class="px-6 py-4 text-left font-semibold">Representante</th>
                    <th class="px-6 py-4 text-left font-semibold">Documento</th>
                    <th class="px-6 py-4 text-left font-semibold">Parentesco</th>
                    <th class="px-6 py-4 text-left font-semibold">Contacto</th>
                    <th class="px-6 py-4 text-left font-semibold">Pacientes</th>
                    <th class="px-6 py-4 text-center font-semibold">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($representantes as $representante)
                <tr class="hover:bg-info-50 transition-colors">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-info-500 to-info-600 flex items-center justify-center text-white font-bold">
                                {{ strtoupper(substr($representante->primer_nombre, 0, 1) . substr($representante->primer_apellido, 0, 1)) }}
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900">{{ $representante->primer_nombre }} {{ $representante->segundo_nombre }} {{ $representante->primer_apellido }} {{ $representante->segundo_apellido }}</p>
                                <p class="text-xs text-gray-500">{{ $representante->genero ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="font-mono text-info-600 font-semibold">
                            {{ $representante->tipo_documento ?? '' }}-{{ $representante->numero_documento ?? 'N/A' }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="badge badge-info">{{ $representante->parentesco ?? 'N/A' }}</span>
                    </td>
                    <td class="px-6 py-4">
                        <p class="text-gray-900">{{ $representante->prefijo_tlf ?? '' }} {{ $representante->numero_tlf ?? 'N/A' }}</p>
                        <p class="text-xs text-gray-500">{{ $representante->estado->estado ?? 'N/A' }}</p>
                    </td>
                    <td class="px-6 py-4">
                        <p class="text-gray-900 font-semibold">{{ $representante->pacientesEspeciales->count() }} paciente(s)</p>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center justify-center gap-2">
                            <a href="{{ route('representantes.show', $representante->id) }}" class="btn btn-sm btn-ghost text-info-600" title="Ver perfil">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('representantes.edit', $representante->id) }}" class="btn btn-sm btn-ghost text-warning-600" title="Editar">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('representantes.destroy', $representante->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-ghost text-danger-600" title="Eliminar" onclick="return confirm('¿Eliminar representante?')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-12">
                        <div class="flex flex-col items-center justify-center">
                            <i class="bi bi-inbox text-5xl text-gray-300 mb-3"></i>
                            <p class="text-gray-500 font-medium mb-2">No se encontraron representantes</p>
                            <p class="text-sm text-gray-400 mb-4">Crea un nuevo representante para comenzar</p>
                            <a href="{{ route('representantes.create') }}" class="btn btn-sm btn-primary">
                                <i class="bi bi-plus-lg mr-2"></i>
                                Nuevo Representante
                            </a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Paginación -->
    @if(isset($representantes) && $representantes->hasPages())
    <div class="p-6 border-t border-gray-200">
        {{ $representantes->links() }}
    </div>
    @endif
</div>
@endsection
