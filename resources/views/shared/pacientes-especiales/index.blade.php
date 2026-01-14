@extends('layouts.admin')

@section('title', 'Pacientes Especiales')

@section('content')
<div class="mb-6">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h2 class="text-3xl font-display font-bold text-gray-900">Pacientes Especiales</h2>
            <p class="text-gray-500 mt-1">Gestión de pacientes con representantes legales</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('pacientes-especiales.estadisticas') }}" class="btn btn-outline shadow-lg">
                <i class="bi bi-graph-up mr-2"></i>
                Estadísticas
            </a>
            <a href="{{ route('pacientes-especiales.create') }}" class="btn btn-primary shadow-lg">
                <i class="bi bi-plus-lg mr-2"></i>
                Registrar Paciente Especial
            </a>
        </div>
    </div>
</div>

<!-- Filtros -->
<div class="card p-6 mb-6">
    <form method="GET" action="{{ route('pacientes-especiales.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <!-- Búsqueda -->
        <div class="md:col-span-2">
            <label class="form-label">Buscar Paciente</label>
            <div class="relative">
                <i class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                <input type="text" name="nombre" 
                       class="input pl-10" 
                       placeholder="Nombre, cédula, historia..."
                       value="{{ request('nombre') }}">
            </div>
        </div>

        <!-- Tipo de Condición -->
        <div>
            <label class="form-label">Tipo de Condición</label>
            <select name="tipo_condicion" class="form-select">
                <option value="">Todas</option>
                <option value="menor_edad" {{ request('tipo_condicion') == 'menor_edad' ? 'selected' : '' }}>Menor de Edad</option>
                <option value="discapacidad" {{ request('tipo_condicion') == 'discapacidad' ? 'selected' : '' }}>Discapacidad</option>
                <option value="adulto_mayor" {{ request('tipo_condicion') == 'adulto_mayor' ? 'selected' : '' }}>Adulto Mayor</option>
                <option value="incapacidad_legal" {{ request('tipo_condicion') == 'incapacidad_legal' ? 'selected' : '' }}>Incapacidad Legal</option>
            </select>
        </div>

        <!-- Representante -->
        <div>
            <label class="form-label">Representante</label>
            <input type="text" name="representante" 
                   class="input" 
                   placeholder="Nombre del representante"
                   value="{{ request('representante') }}">
        </div>

        <!-- Botones -->
        <div class="md:col-span-4 flex gap-3">
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-funnel mr-2"></i>
                Filtrar
            </button>
            <a href="{{ route('pacientes-especiales.index') }}" class="btn btn-outline">
                <i class="bi bi-x-lg mr-2"></i>
                Limpiar
            </a>
            <a href="{{ route('pacientes-especiales.exportar') }}" class="btn btn-outline ml-auto">
                <i class="bi bi-download mr-2"></i>
                Exportar
            </a>
            <a href="{{ route('pacientes-especiales.reporte') }}" class="btn btn-outline">
                <i class="bi bi-file-text mr-2"></i>
                Reportes
            </a>
        </div>
    </form>
</div>

<!-- Estadísticas -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
    <div class="card p-4 border-l-4 border-l-warning-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 mb-1">Total Pacientes Especiales</p>
                <p class="text-2xl font-bold text-gray-900">{{ $stats['total'] ?? 0 }}</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-warning-50 flex items-center justify-center">
                <i class="bi bi-heart-pulse text-warning-600 text-2xl"></i>
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

    <div class="card p-4 border-l-4 border-l-medical-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 mb-1">Con Citas Pendientes</p>
                <p class="text-2xl font-bold text-gray-900">{{ $stats['con_citas'] ?? 0 }}</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-medical-50 flex items-center justify-center">
                <i class="bi bi-calendar-check text-medical-600 text-2xl"></i>
            </div>
        </div>
    </div>

    <div class="card p-4 border-l-4 border-l-info-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 mb-1">Nuevos (mes)</p>
                <p class="text-2xl font-bold text-gray-900">{{ $stats['nuevos_mes'] ?? 0 }}</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-info-50 flex items-center justify-center">
                <i class="bi bi-person-plus text-info-600 text-2xl"></i>
            </div>
        </div>
    </div>
</div>

<!-- Tabla de Pacientes Especiales -->
<div class="card overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gradient-to-r from-warning-600 to-warning-500 text-white">
                <tr>
                    <th class="px-6 py-4 text-left font-semibold">Paciente</th>
                    <th class="px-6 py-4 text-left font-semibold">Historia</th>
                    <th class="px-6 py-4 text-left font-semibold">Condición</th>
                    <th class="px-6 py-4 text-left font-semibold">Representante(s)</th>
                    <th class="px-6 py-4 text-left font-semibold">Edad/Género</th>
                    <th class="px-6 py-4 text-center font-semibold">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($pacientesEspeciales as $pacienteEsp)
                @php $p = $pacienteEsp->paciente; @endphp
                <tr class="hover:bg-warning-50 transition-colors">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-warning-500 to-warning-600 flex items-center justify-center text-white font-bold">
                                {{ strtoupper(substr($p->primer_nombre, 0, 1) . substr($p->primer_apellido, 0, 1)) }}
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900">{{ $p->primer_nombre }} {{ $p->primer_apellido }}</p>
                                <p class="text-xs text-gray-500">{{ $p->tipo_documento ?? '' }}-{{ $p->numero_documento ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="font-mono text-warning-600 font-semibold">HC-{{ \Carbon\Carbon::parse($p->created_at)->format('Y') }}-{{ str_pad($p->id, 3, '0', STR_PAD_LEFT) }}</span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="badge badge-warning">{{ $pacienteEsp->tipo }}</span>
                    </td>
                    <td class="px-6 py-4">
                        @forelse($pacienteEsp->representantes as $rep)
                            <div class="mb-1">
                                <p class="text-gray-900 font-semibold text-xs">{{ $rep->primer_nombre }} {{ $rep->primer_apellido }}</p>
                                <p class="text-[10px] text-gray-500 uppercase">{{ $rep->pivot->tipo_responsabilidad }} - {{ $rep->parentesco }}</p>
                            </div>
                        @empty
                            <span class="text-gray-400 text-xs italic">Sin representante</span>
                        @endforelse
                    </td>
                    <td class="px-6 py-4">
                        <p class="text-gray-900">{{ $p->fecha_nac ? \Carbon\Carbon::parse($p->fecha_nac)->age : 'N/A' }} años</p>
                        <p class="text-xs text-gray-500">{{ $p->genero ?? 'N/A' }}</p>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center justify-center gap-2">
                            <a href="{{ route('pacientes-especiales.show', $pacienteEsp->id) }}" class="btn btn-sm btn-ghost text-warning-600" title="Ver perfil">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('historia-clinica.base.index', $pacienteEsp->id) }}" class="btn btn-sm btn-ghost text-medical-600" title="Historia Clínica">
                                <i class="bi bi-file-medical"></i>
                            </a>
                            <a href="{{ route('pacientes-especiales.edit', $pacienteEsp->id) }}" class="btn btn-sm btn-ghost text-info-600" title="Editar">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('pacientes-especiales.destroy', $pacienteEsp->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-ghost text-danger-600" title="Eliminar" onclick="return confirm('¿Eliminar paciente especial?')">
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
                            <p class="text-gray-500 font-medium mb-2">No se encontraron pacientes especiales</p>
                            <p class="text-sm text-gray-400 mb-4">Registra un nuevo paciente especial para comenzar</p>
                            <a href="{{ route('pacientes-especiales.create') }}" class="btn btn-sm btn-primary">
                                <i class="bi bi-plus-lg mr-2"></i>
                                Nuevo Paciente Especial
                            </a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Paginación -->
    @if(isset($pacientesEspeciales) && $pacientesEspeciales->hasPages())
    <div class="p-6 border-t border-gray-200">
        {{ $pacientesEspeciales->links() }}
    </div>
    @endif
</div>
@endsection
