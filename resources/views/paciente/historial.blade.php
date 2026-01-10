@extends('layouts.paciente')

@section('title', 'Mi Historial')

@section('content')
<div class="mb-8">
    <h1 class="text-2xl font-display font-bold text-gray-900">Mi Historial Médico</h1>
    <p class="text-gray-600 mt-1">Todas tus consultas y procedimientos</p>
</div>

<!-- Filtros -->
<div class="card p-4 mb-6">
    <div class="flex flex-wrap items-center gap-4">
        <div class="flex-1 min-w-[200px]">
            <label class="text-sm text-gray-600 mb-1 block">Tipo de Historial</label>
            <select id="filtro-tipo" class="form-select" onchange="filtrarHistorial()">
                <option value="todas">Todos los historiales</option>
                <option value="propia">Solo historial propio</option>
                <option value="terceros">Solo historial de terceros</option>
            </select>
        </div>
        
        <div id="filtro-paciente-container" class="flex-1 min-w-[200px] hidden">
            <label class="text-sm text-gray-600 mb-1 block">Paciente Especial</label>
            <select id="filtro-paciente" class="form-select" onchange="filtrarHistorial()">
                <option value="">Todos los pacientes</option>
                @foreach($pacientesEspeciales ?? [] as $pe)
                <option value="{{ $pe->id }}">{{ $pe->primer_nombre }} {{ $pe->primer_apellido }}</option>
                @endforeach
            </select>
        </div>
    </div>
</div>

<div class="card">
    @if($historial && $historial->count() > 0)
        <div class="divide-y divide-gray-100">
            @foreach($historial as $cita)
            <div class="historial-card p-6 hover:bg-gray-50 transition-colors"
                 data-tipo="{{ $cita->tipo_historia_display ?? 'propia' }}"
                 data-paciente-especial="{{ $cita->paciente_especial_info->id ?? '' }}">
                <div class="flex gap-4 items-start">
                    <div class="w-16 h-16 rounded-xl bg-blue-100 flex items-center justify-center flex-shrink-0">
                        <i class="bi bi-file-medical text-blue-600 text-2xl"></i>
                    </div>
                    <div class="flex-1">
                        <div class="flex items-start justify-between">
                            <div>
                                <h4 class="font-bold text-gray-900 text-lg">
                                    {{ $cita->diagnostico ?? 'Consulta Médica' }}
                                </h4>
                                <div class="flex flex-col gap-1 mt-1">
                                    <p class="text-gray-600 flex items-center gap-2">
                                        <i class="bi bi-person-badge text-blue-600"></i>
                                        <span>Dr. {{ $cita->medico->primer_nombre ?? '' }} {{ $cita->medico->primer_apellido ?? '' }}</span>
                                    </p>
                                    @if($cita->tipo_historia_display == 'terceros' && $cita->paciente_especial_info)
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-700 w-fit">
                                        <i class="bi bi-person-heart"></i>
                                        Paciente: {{ $cita->paciente_especial_info->primer_nombre ?? '' }} {{ $cita->paciente_especial_info->primer_apellido ?? '' }}
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <span class="badge {{ ($cita->estado_cita ?? '') == 'Completada' ? 'badge-success' : 'badge-warning' }}">
                                {{ ucfirst($cita->estado_cita ?? 'Pendiente') }}
                            </span>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-3 text-sm mt-3">
                            <div class="flex items-center gap-2 text-gray-700">
                                <i class="bi bi-calendar3 text-blue-600"></i>
                                <span>{{ \Carbon\Carbon::parse($cita->fecha_cita)->format('d M, Y') }}</span>
                            </div>
                            <div class="flex items-center gap-2 text-gray-700">
                                <i class="bi bi-building text-blue-600"></i>
                                <span>{{ $cita->consultorio->nombre ?? 'Consultorio' }}</span>
                            </div>
                        </div>

                        @if($cita->motivo)
                        <div class="p-3 bg-gray-100 rounded-lg mt-3">
                            <p class="text-sm text-gray-700"><strong>Motivo:</strong> {{ $cita->motivo }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="p-4 border-t border-gray-100">
            {{ $historial->links() }}
        </div>
    @else
        <div class="p-12 text-center">
            <div class="inline-flex items-center justify-center w-24 h-24 rounded-full bg-gray-50 mb-4">
                <i class="bi bi-folder-x text-5xl text-gray-300"></i>
            </div>
            <p class="text-gray-500 mb-2 font-medium text-lg">No tienes historial médico aún</p>
            <p class="text-gray-400 text-sm">Tu historial aparecerá aquí después de tu primera consulta</p>
        </div>
    @endif
</div>

@push('scripts')
<script>
    function filtrarHistorial() {
        const tipoFiltro = document.getElementById('filtro-tipo').value;
        const pacienteFiltro = document.getElementById('filtro-paciente')?.value || '';
        const containerPaciente = document.getElementById('filtro-paciente-container');
        
        // Mostrar/ocultar filtro de paciente
        if (tipoFiltro === 'terceros') {
            containerPaciente.classList.remove('hidden');
        } else {
            containerPaciente.classList.add('hidden');
        }
        
        // Filtrar todas las tarjetas de historial
        document.querySelectorAll('.historial-card').forEach(card => {
            const tipoHistorial = card.dataset.tipo;
            const pacienteEspecialId = card.dataset.pacienteEspecial;
            
            let mostrar = true;
            
            // Filtro por tipo
            if (tipoFiltro === 'propia' && tipoHistorial !== 'propia') {
                mostrar = false;
            } else if (tipoFiltro === 'terceros' && tipoHistorial !== 'terceros') {
                mostrar = false;
            }
            
            // Filtro por paciente especial (solo si tipo es terceros)
            if (tipoFiltro === 'terceros' && pacienteFiltro && pacienteEspecialId !== pacienteFiltro) {
                mostrar = false;
            }
            
            card.style.display = mostrar ? '' : 'none';
        });
    }
</script>
@endpush
@endsection
