@extends('layouts.admin')

@section('title', 'Pacientes')

@section('content')
<div class="mb-6">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h2 class="text-3xl font-display font-bold text-gray-900">Pacientes</h2>
            <p class="text-gray-500 mt-1">Gestión de historias clínicas y datos de pacientes</p>
        </div>
        <a href="{{ route('pacientes.create') }}" class="btn btn-primary shadow-lg">
            <i class="bi bi-plus-lg mr-2"></i>
            Registrar Paciente
        </a>
    </div>
</div>

<!-- Filtros -->
<div class="card p-6 mb-6">
    <form method="GET" action="{{ route('pacientes.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <!-- Búsqueda -->
        <div class="md:col-span-2">
            <label class="form-label">Buscar Paciente</label>
            <div class="relative">
                <i class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                <input type="text" name="buscar" 
                       class="input pl-10" 
                       placeholder="Nombre, cédula, historia..."
                       value="{{ request('buscar') }}">
            </div>
        </div>

        <!-- Tipo -->
        <div>
            <label class="form-label">Tipo</label>
            <select name="tipo" class="form-select">
                <option value="">Todos</option>
                <option value="regular" {{ request('tipo') == 'regular' ? 'selected' : '' }}>Regular</option>
                <option value="especial" {{ request('tipo') == 'especial' ? 'selected' : '' }}>Especial</option>
            </select>
        </div>

        <!-- Estado -->
        <div>
            <label class="form-label">Estado</label>
            <select name="status" class="form-select">
                <option value="">Todos</option>
                <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Activos</option>
                <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Inactivos</option>
            </select>
        </div>

        <!-- Botones -->
        <div class="md:col-span-4 flex gap-3">
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-funnel mr-2"></i>
                Filtrar
            </button>
            <a href="{{ route('pacientes.index') }}" class="btn btn-outline">
                <i class="bi bi-x-lg mr-2"></i>
                Limpiar
            </a>
        </div>
    </form>
</div>

<!-- Estadísticas -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
    <div class="card p-4 border-l-4 border-l-success-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 mb-1">Total Pacientes</p>
                <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['total'] ?? 0) }}</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-success-50 flex items-center justify-center">
                <i class="bi bi-people text-success-600 text-2xl"></i>
            </div>
        </div>
    </div>

    <div class="card p-4 border-l-4 border-l-medical-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 mb-1">Activos</p>
                <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['activos'] ?? 0) }}</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-medical-50 flex items-center justify-center">
                <i class="bi bi-check-circle text-medical-600 text-2xl"></i>
            </div>
        </div>
    </div>

    <div class="card p-4 border-l-4 border-l-warning-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 mb-1">Citas Hoy</p>
                <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['citas_hoy'] ?? 0) }}</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-warning-50 flex items-center justify-center">
                <i class="bi bi-calendar-event text-warning-600 text-2xl"></i>
            </div>
        </div>
    </div>

    <div class="card p-4 border-l-4 border-l-info-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 mb-1">Nuevos (mes)</p>
                <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['nuevos_mes'] ?? 0) }}</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-info-50 flex items-center justify-center">
                <i class="bi bi-person-plus text-info-600 text-2xl"></i>
            </div>
        </div>
    </div>
</div>

<!-- Tabla de Pacientes -->
<div class="card overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gradient-to-r from-medical-600 to-medical-500 text-white">
                <tr>
                    <th class="px-6 py-4 text-left font-semibold">Paciente</th>
                    <th class="px-6 py-4 text-left font-semibold">Historia</th>
                    <th class="px-6 py-4 text-left font-semibold">Edad/Género</th>
                    <th class="px-6 py-4 text-left font-semibold">Contacto</th>
                    <th class="px-6 py-4 text-left font-semibold">Ubicación</th>
                    <th class="px-6 py-4 text-left font-semibold">Sede/Consultorio</th>
                    <th class="px-6 py-4 text-center font-semibold">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($pacientes as $paciente)
                <tr class="hover:bg-medical-50 transition-colors">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-medical-500 to-medical-600 flex items-center justify-center text-white font-bold">
                                {{ strtoupper(substr($paciente->primer_nombre, 0, 1) . substr($paciente->primer_apellido, 0, 1)) }}
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900">{{ $paciente->primer_nombre }} {{ $paciente->primer_apellido }}</p>
                                <p class="text-xs text-gray-500">{{ $paciente->tipo_documento }}-{{ $paciente->numero_documento }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="font-mono text-medical-600 font-semibold">HC-{{ \Carbon\Carbon::parse($paciente->created_at)->format('Y') }}-{{ str_pad($paciente->id, 3, '0', STR_PAD_LEFT) }}</span>
                    </td>
                    <td class="px-6 py-4">
                        <p class="text-gray-900">
                            @if($paciente->fecha_nac)
                                {{ \Carbon\Carbon::parse($paciente->fecha_nac)->age }} años
                            @else
                                N/A
                            @endif
                        </p>
                        <p class="text-xs text-gray-500">{{ $paciente->genero ?? 'No registrado' }}</p>
                    </td>
                    <td class="px-6 py-4">
                        <p class="text-gray-900">{{ $paciente->prefijo_tlf }} {{ $paciente->numero_tlf }}</p>
                        <p class="text-xs text-gray-500">{{ optional($paciente->usuario)->correo }}</p>
                    </td>
                    <td class="px-6 py-4">
                        <p class="text-gray-900 truncate max-w-[150px]" title="{{ optional($paciente->ciudad)->ciudad }}">
                             {{ optional($paciente->ciudad)->ciudad ?? 'N/A' }}
                        </p>
                        <p class="text-xs text-gray-500">{{ optional($paciente->estado)->estado ?? '' }}</p>
                    </td>
                    <td class="px-6 py-4">
                        @php
                            $consultoriosPaciente = $paciente->citas->pluck('consultorio.nombre')->unique();
                        @endphp
                        @forelse($consultoriosPaciente as $nombreCons)
                            <span class="badge badge-medical text-xs mb-1 block w-fit">{{ $nombreCons }}</span>
                        @empty
                            <span class="text-gray-400 text-xs italic">Sin sede asignada</span>
                        @endforelse
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center justify-center gap-2">
                             <a href="{{ route('pacientes.show', $paciente->id) }}" class="btn btn-sm btn-ghost text-medical-600" title="Ver perfil">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('pacientes.historia-clinica', $paciente->id) }}" class="btn btn-sm btn-ghost text-info-600" title="Historia">
                                <i class="bi bi-file-medical"></i>
                            </a>
                            <a href="{{ route('pacientes.edit', $paciente->id) }}" class="btn btn-sm btn-ghost text-warning-600" title="Editar">
                                <i class="bi bi-pencil"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-10 text-center text-gray-500">
                        <div class="flex flex-col items-center justify-center">
                            <i class="bi bi-people text-4xl mb-3 text-gray-300"></i>
                            <p class="text-lg font-medium">No se encontraron pacientes</p>
                            <p class="text-sm">Intente ajustar los filtros de búsqueda</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Paginación -->
    @if($pacientes->hasPages())
    <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
        {{ $pacientes->links() }}
    </div>
    @endif
</div>
@endsection
