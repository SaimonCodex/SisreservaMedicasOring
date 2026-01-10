@extends('layouts.paciente')

@section('title', 'Mis Citas')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-display font-bold text-gray-900">Mis Citas</h1>
            <p class="text-gray-600 mt-1">Agenda y gestiona tus consultas médicas</p>
        </div>
        <a href="{{ route('paciente.citas.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i>
            <span>Nueva Cita</span>
        </a>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="card p-4 bg-blue-50 border-blue-200">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-xl bg-blue-100 flex items-center justify-center">
                    <i class="bi bi-calendar-check text-blue-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-900">{{ ($citas ?? collect())->filter(fn($c) => in_array($c->estado_cita, ['Programada', 'Confirmada']))->count() }}</p>
                    <p class="text-sm text-gray-600">Próximas</p>
                </div>
            </div>
        </div>
        <div class="card p-4 bg-emerald-50 border-emerald-200">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-xl bg-emerald-100 flex items-center justify-center">
                    <i class="bi bi-check-circle text-emerald-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-900">{{ ($citas ?? collect())->where('estado_cita', 'Completada')->count() }}</p>
                    <p class="text-sm text-gray-600">Completadas</p>
                </div>
            </div>
        </div>
        <div class="card p-4 bg-rose-50 border-rose-200">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-xl bg-rose-100 flex items-center justify-center">
                    <i class="bi bi-x-circle text-rose-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-900">{{ ($citas ?? collect())->where('estado_cita', 'Cancelada')->count() }}</p>
                    <p class="text-sm text-gray-600">Canceladas</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtros -->
    @if(isset($pacientesEspeciales) && $pacientesEspeciales->count() > 0)
    <div class="card p-4">
        <div class="flex flex-wrap items-center gap-4">
            <div class="flex-1 min-w-[200px]">
                <label class="text-sm text-gray-600 mb-1 block">Tipo de Cita</label>
                <select id="filtro-tipo" class="form-select" onchange="filtrarCitas()">
                    <option value="todas">Todas las citas</option>
                    <option value="propia">Solo citas propias</option>
                    <option value="terceros">Solo citas para terceros</option>
                </select>
            </div>
            
            <div id="filtro-paciente-container" class="flex-1 min-w-[200px] hidden">
                <label class="text-sm text-gray-600 mb-1 block">Paciente Especial</label>
                <select id="filtro-paciente" class="form-select" onchange="filtrarCitas()">
                    <option value="">Todos los pacientes</option>
                    @foreach($pacientesEspeciales ?? [] as $pe)
                    <option value="{{ $pe->id }}">{{ $pe->primer_nombre }} {{ $pe->primer_apellido }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
    @endif

    <!-- Tabs -->
    <div class="card p-6">
        <div class="flex flex-wrap gap-2 border-b border-gray-200 pb-4 mb-6">
            <button class="tab-button active" data-tab="proximas">
                <i class="bi bi-calendar-check"></i> Próximas
            </button>
            <button class="tab-button" data-tab="realizadas">
                <i class="bi bi-clock-history"></i> Realizadas
            </button>
            <button class="tab-button" data-tab="canceladas">
                <i class="bi bi-x-circle"></i> Canceladas
            </button>
        </div>

        @php
            $citasProximas = ($citas ?? collect())->filter(function($c) {
                return in_array($c->estado_cita, ['Programada', 'Confirmada', 'En Progreso']);
            });
            $citasRealizadas = ($citas ?? collect())->where('estado_cita', 'Completada');
            $citasCanceladas = ($citas ?? collect())->whereIn('estado_cita', ['Cancelada', 'No Asistió']);
        @endphp

        <!-- Próximas Citas Tab -->
        <div id="tab-proximas" class="tab-content">
            <div class="space-y-4">
                @forelse($citasProximas as $cita)
                <div class="card cita-card p-6 hover:shadow-md transition-shadow border-l-4 border-blue-500" 
                     data-tipo="{{ $cita->tipo_cita_display ?? 'propia' }}"
                     data-paciente-especial="{{ $cita->paciente_especial_info->id ?? '' }}">
                    <div class="flex flex-col md:flex-row gap-6">
                        <!-- Date Box -->
                        <div class="text-center p-4 bg-blue-50 rounded-xl w-full md:w-24 flex-shrink-0">
                            <p class="text-3xl font-bold text-blue-600">
                                {{ \Carbon\Carbon::parse($cita->fecha_cita)->format('d') }}
                            </p>
                            <p class="text-sm text-blue-700">
                                {{ \Carbon\Carbon::parse($cita->fecha_cita)->isoFormat('MMM') }}
                            </p>
                            <p class="text-xs text-gray-500 mt-1">
                                {{ \Carbon\Carbon::parse($cita->fecha_cita)->isoFormat('YYYY') }}
                            </p>
                        </div>

                        <!-- Cita Info -->
                        <div class="flex-1">
                            <div class="flex items-start justify-between mb-3">
                                <div>
                                    <h3 class="text-xl font-bold text-gray-900">{{ $cita->especialidad->nombre ?? 'Consulta General' }}</h3>
                                    <p class="text-gray-600 flex items-center gap-2 mt-1">
                                        <i class="bi bi-person-badge text-blue-600"></i>
                                        Dr. {{ $cita->medico->primer_nombre ?? 'N/A' }} {{ $cita->medico->primer_apellido ?? '' }}
                                    </p>
                                </div>
                                <div class="flex flex-col items-end gap-2">
                                    @if($cita->tipo_cita_display == 'terceros' && $cita->paciente_especial_info)
                                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-700">
                                        <i class="bi bi-person-heart"></i>
                                        {{ $cita->paciente_especial_info->primer_nombre ?? '' }} {{ $cita->paciente_especial_info->primer_apellido ?? '' }}
                                    </span>
                                    @endif
                                    @if($cita->estado_cita == 'Confirmada')
                                    <span class="badge badge-success">Confirmada</span>
                                    @elseif($cita->estado_cita == 'Programada')
                                    <span class="badge badge-warning">Pendiente</span>
                                    @elseif($cita->estado_cita == 'En Progreso')
                                    <span class="badge badge-info">En Progreso</span>
                                    @endif
                                </div>
                            </div>

                            <div class="grid grid-cols-2 md:grid-cols-3 gap-4 text-sm">
                                <div class="flex items-center gap-2 text-gray-700">
                                    <i class="bi bi-clock text-blue-600"></i>
                                    <span>{{ $cita->hora_inicio }}</span>
                                </div>
                                <div class="flex items-center gap-2 text-gray-700">
                                    <i class="bi bi-building text-blue-600"></i>
                                    <span>{{ $cita->consultorio->nombre ?? 'Consultorio' }}</span>
                                </div>
                                <div class="flex items-center gap-2 text-gray-700">
                                    <i class="bi bi-laptop text-blue-600"></i>
                                    <span>{{ $cita->tipo_consulta ?? 'Presencial' }}</span>
                                </div>
                            </div>

                            @if($cita->motivo)
                            <div class="mt-3 p-3 bg-gray-50 rounded-lg">
                                <p class="text-sm text-gray-600"><strong>Motivo:</strong> {{ Str::limit($cita->motivo, 100) }}</p>
                            </div>
                            @endif

                            <div class="mt-4 pt-4 border-t border-gray-100 flex justify-end">
                                <a href="{{ route('citas.show', $cita->id) }}" class="btn btn-sm btn-outline hover:bg-emerald-50 text-emerald-600 border-emerald-200">
                                    <i class="bi bi-eye mr-2"></i> Ver Detalles
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="text-center py-12">
                    <div class="w-20 h-20 mx-auto bg-gray-100 rounded-full flex items-center justify-center mb-4">
                        <i class="bi bi-calendar-x text-4xl text-gray-300"></i>
                    </div>
                    <p class="text-gray-500 font-medium mb-2">No tienes citas próximas</p>
                    <p class="text-sm text-gray-400 mb-4">Agenda una nueva cita médica</p>
                    <a href="{{ route('paciente.citas.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-lg"></i> Nueva Cita
                    </a>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Realizadas Tab -->
        <div id="tab-realizadas" class="tab-content hidden">
            <div class="space-y-4">
                @forelse($citasRealizadas as $cita)
                <div class="card cita-card p-6 bg-gray-50 border-l-4 border-emerald-500"
                     data-tipo="{{ $cita->tipo_cita_display ?? 'propia' }}"
                     data-paciente-especial="{{ $cita->paciente_especial_info->id ?? '' }}">
                    <div class="flex items-start justify-between">
                        <div>
                            <div class="flex items-center gap-2">
                                <h3 class="font-bold text-gray-900">{{ $cita->especialidad->nombre ?? 'Consulta' }}</h3>
                                @if($cita->tipo_cita_display == 'terceros' && $cita->paciente_especial_info)
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-700">
                                    <i class="bi bi-person-heart"></i> {{ $cita->paciente_especial_info->primer_nombre ?? '' }}
                                </span>
                                @endif
                            </div>
                            <p class="text-sm text-gray-600 mt-1">
                                Dr. {{ $cita->medico->primer_nombre ?? '' }} {{ $cita->medico->primer_apellido ?? '' }}
                            </p>
                            <p class="text-xs text-gray-500 mt-2">
                                {{ \Carbon\Carbon::parse($cita->fecha_cita)->format('d M Y') }} - {{ \Carbon\Carbon::parse($cita->hora_inicio)->format('h:i A') }}
                            </p>
                        </div>
                        <div class="flex flex-col gap-2">
                            <span class="badge badge-success self-end">Completada</span>
                            <a href="{{ route('citas.show', $cita->id) }}" class="btn btn-sm btn-outline text-gray-600 hover:text-emerald-600">
                                <i class="bi bi-eye"></i> Detalles
                            </a>
                        </div>
                    </div>
                </div>
                @empty
                <div class="text-center py-12 text-gray-500">
                    <i class="bi bi-inbox text-4xl text-gray-300"></i>
                    <p class="mt-2">No hay citas realizadas</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Canceladas Tab -->
        <div id="tab-canceladas" class="tab-content hidden">
            <div class="space-y-4">
                @forelse($citasCanceladas as $cita)
                <div class="card cita-card p-6 bg-gray-50 border-l-4 border-rose-500"
                     data-tipo="{{ $cita->tipo_cita_display ?? 'propia' }}"
                     data-paciente-especial="{{ $cita->paciente_especial_info->id ?? '' }}">
                    <div class="flex items-start justify-between">
                        <div>
                            <div class="flex items-center gap-2">
                                <h3 class="font-bold text-gray-900 line-through">{{ $cita->especialidad->nombre ?? 'Consulta' }}</h3>
                                @if($cita->tipo_cita_display == 'terceros' && $cita->paciente_especial_info)
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-700">
                                    <i class="bi bi-person-heart"></i> {{ $cita->paciente_especial_info->primer_nombre ?? '' }}
                                </span>
                                @endif
                            </div>
                            <p class="text-sm text-gray-600 mt-1">
                                Dr. {{ $cita->medico->primer_nombre ?? '' }} {{ $cita->medico->primer_apellido ?? '' }}
                            </p>
                            <p class="text-sm text-gray-500 mt-1">
                                {{ \Carbon\Carbon::parse($cita->fecha_cita)->format('d/m/Y') }} - {{ $cita->hora_inicio }}
                            </p>
                        </div>
                        <span class="badge badge-danger">Cancelada</span>
                    </div>
                </div>
                @empty
                <div class="text-center py-12 text-gray-500">
                    <i class="bi bi-inbox text-4xl text-gray-300"></i>
                    <p class="mt-2">No hay citas canceladas</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Tab switching
    document.querySelectorAll('.tab-button').forEach(button => {
        button.addEventListener('click', () => {
            document.querySelectorAll('.tab-button').forEach(b => b.classList.remove('active'));
            document.querySelectorAll('.tab-content').forEach(c => c.classList.add('hidden'));
            button.classList.add('active');
            document.getElementById('tab-' + button.dataset.tab).classList.remove('hidden');
        });
    });

    // Filtrado de citas
    function filtrarCitas() {
        const tipoFiltro = document.getElementById('filtro-tipo').value;
        const pacienteFiltro = document.getElementById('filtro-paciente')?.value || '';
        const containerPaciente = document.getElementById('filtro-paciente-container');
        
        // Mostrar/ocultar filtro de paciente
        if (tipoFiltro === 'terceros') {
            containerPaciente.classList.remove('hidden');
        } else {
            containerPaciente.classList.add('hidden');
        }
        
        // Filtrar todas las tarjetas de citas
        document.querySelectorAll('.cita-card').forEach(card => {
            const tipoCita = card.dataset.tipo;
            const pacienteEspecialId = card.dataset.pacienteEspecial;
            
            let mostrar = true;
            
            // Filtro por tipo
            if (tipoFiltro === 'propia' && tipoCita !== 'propia') {
                mostrar = false;
            } else if (tipoFiltro === 'terceros' && tipoCita !== 'terceros') {
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
