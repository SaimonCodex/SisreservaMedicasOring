@extends('layouts.medico')

@section('title', 'Historias Clínicas')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-display font-bold text-gray-900">Historias Clínicas Base</h1>
            <p class="text-gray-600 mt-1">Registro médico completo de sus pacientes.</p>
        </div>
        <!-- Botón comentado por solicitud
        <a href="{{ url('historia-clinica/base/create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i>
            <span>Nueva Historia Clínica</span>
        </a>
        -->
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="card p-4 bg-gradient-to-br from-purple-50 to-purple-100 border-purple-200">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-lg bg-purple-600 flex items-center justify-center">
                    <i class="bi bi-file-medical text-white text-xl"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-purple-900">{{ number_format($stats['total'] ?? 0) }}</p>
                    <p class="text-sm text-purple-700">Total Historias</p>
                </div>
            </div>
        </div>
        <div class="card p-4 bg-gradient-to-br from-blue-50 to-blue-100 border-blue-200">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-lg bg-blue-600 flex items-center justify-center">
                    <i class="bi bi-journal-check text-white text-xl"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-blue-900">{{ number_format($stats['actualizadas_hoy'] ?? 0) }}</p>
                    <p class="text-sm text-blue-700">Actualizadas Hoy</p>
                </div>
            </div>
        </div>
        <div class="card p-4 bg-gradient-to-br from-amber-50 to-amber-100 border-amber-200">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-lg bg-amber-600 flex items-center justify-center">
                    <i class="bi bi-person-exclamation text-white text-xl"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-amber-900">{{ number_format($stats['sin_antecedentes'] ?? 0) }}</p>
                    <p class="text-sm text-amber-700">Sin Datos Base</p>
                </div>
            </div>
        </div>
        <div class="card p-4 bg-gradient-to-br from-emerald-50 to-emerald-100 border-emerald-200">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-lg bg-emerald-600 flex items-center justify-center">
                    <i class="bi bi-clock-history text-white text-xl"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-emerald-900">{{ number_format($stats['recientes'] ?? 0) }}</p>
                    <p class="text-sm text-emerald-700">Nuevas (Mes)</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card p-6">
        <form method="GET" action="{{ route('historia-clinica.base.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            
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

            <!-- Especialidad (Solo si tiene > 1) -->
            @if(isset($medico) && $medico->especialidades->count() > 1)
            <div>
                <label class="form-label">Especialidad</label>
                <select name="especialidad" class="form-select">
                    <option value="">Todas</option>
                    @foreach($medico->especialidades as $especialidad)
                    <option value="{{ $especialidad->id }}" {{ request('especialidad') == $especialidad->id ? 'selected' : '' }}>
                        {{ $especialidad->nombre }}
                    </option>
                    @endforeach
                </select>
            </div>
            @endif

            <!-- Botones -->
            <div class="flex items-end gap-2">
                <button type="submit" class="btn btn-primary flex-1">
                    <i class="bi bi-funnel"></i>
                    Filtrar
                </button>
                <a href="{{ route('historia-clinica.base.index') }}" class="btn btn-outline">
                    <i class="bi bi-x-lg"></i>
                </a>
            </div>
        </form>
    </div>

    <!-- Historias List -->
    <div class="card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gradient-to-r from-medical-600 to-medical-500 text-white">
                    <tr>
                        <th class="px-6 py-4 text-left font-semibold">Paciente</th>
                        <th class="px-6 py-4 text-left font-semibold">Sangre / Edad</th>
                        <th class="px-6 py-4 text-left font-semibold">Alertas</th>
                        <th class="px-6 py-4 text-left font-semibold">Última Actualización</th>
                        <th class="px-6 py-4 text-center font-semibold">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white">
                    @forelse($historias as $historia)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-purple-500 to-purple-600 flex items-center justify-center text-white font-bold text-sm">
                                    {{ strtoupper(substr($historia->paciente->primer_nombre ?? 'P', 0, 1) . substr($historia->paciente->primer_apellido ?? 'A', 0, 1)) }}
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-900">
                                        {{ $historia->paciente->primer_nombre ?? 'N/A' }} 
                                        {{ $historia->paciente->primer_apellido ?? '' }}
                                    </p>
                                    <p class="text-xs text-gray-500">{{ $historia->paciente->tipo_documento }}-{{ $historia->paciente->numero_documento }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="space-y-1">
                                <div class="flex items-center gap-2 text-sm">
                                    <i class="bi bi-droplet-fill text-rose-500"></i>
                                    <span class="font-semibold">{{ $historia->tipo_sangre ?? 'S/R' }}</span>
                                </div>
                                <div class="flex items-center gap-2 text-sm text-gray-600">
                                    <i class="bi bi-calendar"></i>
                                    {{ isset($historia->paciente->fecha_nac) ? \Carbon\Carbon::parse($historia->paciente->fecha_nac)->age . ' años' : 'N/A' }}
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-wrap gap-1">
                                @if($historia->alergias)
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                    Alergias
                                </span>
                                @endif
                                @if($historia->antecedentes_personales)
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                    Ant. Pers.
                                </span>
                                @endif
                                @if(!$historia->alergias && !$historia->antecedentes_personales)
                                <span class="text-xs text-gray-400 italic">Sin alertas</span>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-col">
                                <span class="text-sm font-semibold text-gray-900">
                                    {{ isset($historia->updated_at) ? \Carbon\Carbon::parse($historia->updated_at)->format('d/m/Y') : 'N/A' }}
                                </span>
                                <span class="text-xs text-gray-500">
                                    {{ isset($historia->updated_at) ? \Carbon\Carbon::parse($historia->updated_at)->diffForHumans() : '' }}
                                </span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('historia-clinica.base.show', $historia->paciente_id) }}" class="btn btn-sm btn-ghost text-purple-600 hover:bg-purple-50" title="Ver Detalle">
                                    <i class="bi bi-eye"></i>
                                </a>
                                {{-- Si se desea permitir edición --}}
                                <a href="{{ route('historia-clinica.base.edit', $historia->paciente_id) }}" class="btn btn-sm btn-ghost text-blue-600 hover:bg-blue-50" title="Editar">
                                    <i class="bi bi-pencil"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <div class="w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center mb-4">
                                    <i class="bi bi-file-medical text-3xl text-gray-400"></i>
                                </div>
                                <h3 class="text-lg font-medium text-gray-900">No se encontraron historias</h3>
                                <p class="text-gray-500 mt-1">No hay historias clínicas que coincidan con los filtros.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($historias->hasPages())
        <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
            {{ $historias->appends(request()->query())->links('vendor.pagination.medical') }}
        </div>
        @endif
    </div>
</div>
@endsection
