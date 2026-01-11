@extends('layouts.medico')

@section('title', 'Mis Pacientes')

@section('content')
<div class="mb-6">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h2 class="text-3xl font-display font-bold text-gray-900">Mis Pacientes</h2>
            <p class="text-gray-500 mt-1">Consulta tus pacientes y sus historias clínicas</p>
        </div>
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
                    <th class="px-6 py-4 text-left font-semibold">Última Cita</th>
                    <th class="px-6 py-4 text-center font-semibold">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($pacientes ?? [] as $paciente)
                <tr class="hover:bg-medical-50 transition-colors">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-success-500 to-success-600 flex items-center justify-center text-white font-bold">
                                {{ strtoupper(substr($paciente->primer_nombre ?? 'P', 0, 1)) }}{{ strtoupper(substr($paciente->primer_apellido ?? 'A', 0, 1)) }}
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900">{{ $paciente->primer_nombre }} {{ $paciente->primer_apellido }}</p>
                                <p class="text-xs text-gray-500">{{ $paciente->tipo_documento }}-{{ $paciente->numero_documento }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="font-mono text-medical-600 font-semibold">{{ $paciente->historiaClinicaBase->numero_historia ?? 'N/A' }}</span>
                    </td>
                    <td class="px-6 py-4">
                        <p class="text-gray-900">{{ $paciente->fecha_nacimiento ? \Carbon\Carbon::parse($paciente->fecha_nacimiento)->age . ' años' : 'N/A' }}</p>
                        <p class="text-xs text-gray-500">{{ $paciente->genero ?? 'N/A' }}</p>
                    </td>
                    <td class="px-6 py-4">
                        <p class="text-gray-900">{{ $paciente->telefono ?? 'N/A' }}</p>
                        <p class="text-xs text-gray-500">{{ $paciente->usuario->email ?? 'N/A' }}</p>
                    </td>
                    <td class="px-6 py-4">
                        <p class="text-gray-900">{{ $paciente->ultima_cita ? \Carbon\Carbon::parse($paciente->ultima_cita)->format('d/m/Y') : 'N/A' }}</p>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center justify-center gap-2">
                            <a href="{{ route('pacientes.show', $paciente->id) }}" class="btn btn-sm btn-ghost text-medical-600" title="Ver perfil">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('historia-clinica.base.show', $paciente->id) }}" class="btn btn-sm btn-ghost text-info-600" title="Historia">
                                <i class="bi bi-file-medical"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-12">
                        <div class="inline-flex flex-col items-center">
                            <div class="w-20 h-20 rounded-full bg-gray-100 flex items-center justify-center mb-4">
                                <i class="bi bi-people text-4xl text-gray-300"></i>
                            </div>
                            <p class="text-gray-500 font-medium mb-2">No se encontraron pacientes</p>
                            <p class="text-sm text-gray-400">Intenta ajustar los filtros de búsqueda</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if(isset($pacientes) && $pacientes->hasPages())
    <!-- Paginación -->
    <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
        {{ $pacientes->links() }}
    </div>
    @endif
</div>
@endsection
