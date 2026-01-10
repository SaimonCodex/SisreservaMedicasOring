@extends('layouts.admin')

@section('title', 'Médicos')

@section('content')
<div class="mb-6">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h2 class="text-3xl font-display font-bold text-gray-900">Médicos</h2>
            <p class="text-gray-500 mt-1">Gestión del personal médico de la clínica</p>
        </div>
        <a href="{{ route('medicos.create') }}" class="btn btn-primary shadow-lg">
            <i class="bi bi-plus-lg mr-2"></i>
            Registrar Médico
        </a>
    </div>
</div>

<!-- Filtros -->
<div class="card p-6 mb-6">
    <form method="GET" action="{{ route('medicos.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <!-- Búsqueda -->
        <div class="md:col-span-2">
            <label class="form-label">Buscar</label>
            <div class="relative">
                <i class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                <input type="text" name="buscar" 
                       class="input pl-10" 
                       placeholder="Nombre, cédula, MPPS..."
                       value="{{ request('buscar') }}">
            </div>
        </div>

        <!-- Especialidad -->
        <div>
            <label class="form-label">Especialidad</label>
            <select name="especialidad_id" class="form-select">
                <option value="">Todas</option>
                @foreach($especialidades as $especialidad)
                    <option value="{{ $especialidad->id }}" {{ request('especialidad_id') == $especialidad->id ? 'selected' : '' }}>
                        {{ $especialidad->nombre }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Estado -->
        <div>
            <label class="form-label">Estado</label>
            <select name="status" class="form-select">
                <option value="">Todos</option>
                <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Activos</option>
                <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Inactivos</option>
            </select>
        </div>

        <!-- Botones -->
        <div class="md:col-span-4 flex gap-3">
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-funnel mr-2"></i>
                Filtrar
            </button>
            <a href="{{ route('medicos.index') }}" class="btn btn-outline">
                <i class="bi bi-x-lg mr-2"></i>
                Limpiar
            </a>
        </div>
    </form>
</div>

<!-- Estadísticas Rápidas -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
    <div class="card p-4 border-l-4 border-l-medical-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 mb-1">Total Médicos</p>
                <p class="text-2xl font-bold text-gray-900">{{ $totalMedicos }}</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-medical-50 flex items-center justify-center">
                <i class="bi bi-person-badge text-medical-600 text-2xl"></i>
            </div>
        </div>
    </div>

    <div class="card p-4 border-l-4 border-l-success-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 mb-1">Activos</p>
                <p class="text-2xl font-bold text-gray-900">{{ $medicosActivos }}</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-success-50 flex items-center justify-center">
                <i class="bi bi-check-circle text-success-600 text-2xl"></i>
            </div>
        </div>
    </div>

    <div class="card p-4 border-l-4 border-l-warning-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 mb-1">Consultas Hoy</p>
                <p class="text-2xl font-bold text-gray-900">{{ $citasHoyCount }}</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-warning-50 flex items-center justify-center">
                <i class="bi bi-calendar-check text-warning-600 text-2xl"></i>
            </div>
        </div>
    </div>

    <div class="card p-4 border-l-4 border-l-info-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 mb-1">Especialidades</p>
                <p class="text-2xl font-bold text-gray-900">{{ $totalEspecialidades }}</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-info-50 flex items-center justify-center">
                <i class="bi bi-bookmark text-info-600 text-2xl"></i>
            </div>
        </div>
    </div>
</div>

<!-- Tabla de Médicos -->
<div class="card overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gradient-to-r from-medical-600 to-medical-500 text-white">
                <tr>
                    <th class="px-6 py-4 text-left font-semibold">Médico</th>
                    <th class="px-6 py-4 text-left font-semibold">Especialidad</th>
                    <th class="px-6 py-4 text-left font-semibold">MPPS</th>
                    <th class="px-6 py-4 text-left font-semibold">Contacto</th>
                    <th class="px-6 py-4 text-left font-semibold">Estado</th>
                    <th class="px-6 py-4 text-center font-semibold">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($medicos as $medico)
                <tr class="hover:bg-medical-50 transition-colors">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-medical-500 to-medical-600 flex items-center justify-center text-white font-bold">
                                {{ substr($medico->primer_nombre, 0, 1) }}{{ substr($medico->primer_apellido, 0, 1) }}
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900">{{ $medico->primer_nombre }} {{ $medico->primer_apellido }}</p>
                                <p class="text-xs text-gray-500">{{ $medico->tipo_documento }}-{{ $medico->numero_documento }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        @if($medico->especialidades->count() > 0)
                            @foreach($medico->especialidades as $esp)
                                <span class="badge badge-primary">{{ $esp->nombre }}</span>
                            @endforeach
                        @else
                            <span class="text-gray-400 italic">Sin especialidad</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <span class="font-mono text-gray-600">{{ $medico->nro_colegiatura ?? 'N/A' }}</span>
                    </td>
                    <td class="px-6 py-4">
                        <p class="text-gray-900">{{ $medico->prefijo_tlf }} {{ $medico->numero_tlf }}</p>
                        <p class="text-xs text-gray-500">{{ $medico->usuario->correo ?? 'Sin usuario' }}</p>
                    </td>
                    <td class="px-6 py-4">
                        @if($medico->status)
                            <span class="badge badge-success">Activo</span>
                        @else
                            <span class="badge badge-gray">Inactivo</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center justify-center gap-2">
                            <a href="{{ route('medicos.show', $medico->id) }}" class="btn btn-sm btn-ghost text-medical-600" title="Ver detalles">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('medicos.index') }}" class="btn btn-sm btn-ghost text-info-600" title="Horarios (Próximamente)">
                                <i class="bi bi-clock"></i>
                            </a>
                            <a href="{{ route('medicos.edit', $medico->id) }}" class="btn btn-sm btn-ghost text-warning-600" title="Editar">
                                <i class="bi bi-pencil"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-10 text-center text-gray-500">
                        <i class="bi bi-inbox text-4xl mb-3 block"></i>
                        No se encontraron médicos registrados
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Paginación -->
    <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
        {{ $medicos->links() }}
    </div>
</div>
@endsection
