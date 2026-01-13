@extends('layouts.paciente')

@section('title', 'Mis Citas')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-display font-bold text-gray-900">Mis Citas</h1>
            <p class="text-gray-600 mt-1">Agenda y gestiona tus consultas médicas</p>
        </div>
        <a href="{{ route('paciente.citas.create') }}" class="btn btn-primary shadow-lg shadow-emerald-200 hover:shadow-emerald-300 transition-all">
            <i class="bi bi-plus-lg mr-2"></i>
            <span>Nueva Cita</span>
        </a>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="card p-4 bg-gradient-to-br from-blue-50 to-white border-blue-100 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-blue-100 flex items-center justify-center shadow-sm">
                    <i class="bi bi-calendar-check text-blue-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-3xl font-bold text-gray-900">{{ ($citas ?? collect())->filter(fn($c) => in_array($c->estado_cita, ['Programada', 'Confirmada']))->count() }}</p>
                    <p class="text-sm font-medium text-gray-600">Citas Próximas</p>
                </div>
            </div>
        </div>
        <div class="card p-4 bg-gradient-to-br from-emerald-50 to-white border-emerald-100 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-emerald-100 flex items-center justify-center shadow-sm">
                    <i class="bi bi-check-circle text-emerald-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-3xl font-bold text-gray-900">{{ ($citas ?? collect())->where('estado_cita', 'Completada')->count() }}</p>
                    <p class="text-sm font-medium text-gray-600">Completadas</p>
                </div>
            </div>
        </div>
        <div class="card p-4 bg-gradient-to-br from-red-50 to-white border-red-100 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-red-100 flex items-center justify-center shadow-sm">
                    <i class="bi bi-x-circle text-red-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-3xl font-bold text-gray-900">{{ ($citas ?? collect())->filter(fn($c) => in_array($c->estado_cita, ['Cancelada', 'No Asistió']))->count() }}</p>
                    <p class="text-sm font-medium text-gray-600">Canceladas</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtros -->
    @if(isset($pacientesEspeciales) && $pacientesEspeciales->count() > 0)
    <div class="card p-4 border border-gray-100 shadow-sm">
        <div class="flex flex-wrap items-center gap-4">
            <div class="flex-1 min-w-[200px]">
                <label class="text-xs font-bold text-gray-500 uppercase mb-1 block">Tipo de Cita</label>
                <select id="filtro-tipo" class="select select-bordered select-sm w-full" onchange="filtrarCitas()">
                    <option value="todas">Todas las citas</option>
                    <option value="propia">Solo citas propias</option>
                    <option value="terceros">Solo citas para terceros</option>
                </select>
            </div>
            
            <div id="filtro-paciente-container" class="flex-1 min-w-[200px] hidden">
                <label class="text-xs font-bold text-gray-500 uppercase mb-1 block">Paciente Especial</label>
                <select id="filtro-paciente" class="select select-bordered select-sm w-full" onchange="filtrarCitas()">
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
    <div class="card p-6 border-gray-200">
        <div class="flex flex-wrap gap-2 border-b border-gray-200 pb-0 mb-6">
            <button class="tab-button active px-4 py-2 text-sm font-medium border-b-2 border-emerald-500 text-emerald-600 transition-colors" data-tab="proximas">
                <i class="bi bi-calendar-check mr-2"></i>Próximas
            </button>
            <button class="tab-button px-4 py-2 text-sm font-medium border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 transition-colors" data-tab="realizadas">
                <i class="bi bi-clock-history mr-2"></i>Realizadas
            </button>
            <button class="tab-button px-4 py-2 text-sm font-medium border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 transition-colors" data-tab="canceladas">
                <i class="bi bi-x-circle mr-2"></i>Canceladas
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
                <div class="card cita-card p-6 hover:shadow-lg transition-all border border-gray-100 hover:border-blue-200 group" 
                     data-tipo="{{ $cita->tipo_cita_display ?? 'propia' }}"
                     data-paciente-especial="{{ $cita->paciente_especial_info->id ?? '' }}">
                    <div class="flex flex-col md:flex-row gap-6">
                        <!-- Date Box -->
                        <div class="text-center p-4 bg-blue-50 rounded-xl w-full md:w-24 flex-shrink-0 group-hover:bg-blue-100 transition-colors">
                            <p class="text-3xl font-bold text-blue-600">
                                {{ \Carbon\Carbon::parse($cita->fecha_cita)->format('d') }}
                            </p>
                            <p class="text-sm text-blue-700 font-medium uppercase">
                                {{ \Carbon\Carbon::parse($cita->fecha_cita)->isoFormat('MMM') }}
                            </p>
                            <p class="text-xs text-blue-400 mt-1 font-bold">
                                {{ \Carbon\Carbon::parse($cita->fecha_cita)->isoFormat('YYYY') }}
                            </p>
                        </div>

                        <!-- Cita Info -->
                        <div class="flex-1">
                            <div class="flex items-start justify-between mb-3">
                                <div>
                                    <h3 class="text-xl font-bold text-gray-900 group-hover:text-blue-700 transition-colors">{{ $cita->especialidad->nombre ?? 'Consulta General' }}</h3>
                                    <p class="text-gray-600 flex items-center gap-2 mt-1">
                                        <i class="bi bi-person-badge text-blue-600"></i>
                                        Dr. {{ $cita->medico->primer_nombre ?? 'N/A' }} {{ $cita->medico->primer_apellido ?? '' }}
                                    </p>
                                </div>
                                <div class="flex flex-col items-end gap-2">
                                    @if($cita->tipo_cita_display == 'terceros' && $cita->paciente_especial_info)
                                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-bold bg-purple-100 text-purple-700 border border-purple-200">
                                        <i class="bi bi-person-heart"></i>
                                        {{ $cita->paciente_especial_info->primer_nombre ?? '' }} {{ $cita->paciente_especial_info->primer_apellido ?? '' }}
                                    </span>
                                    @endif

                                    @php
                                        $badgeColor = match($cita->estado_cita) {
                                            'Confirmada' => 'success',
                                            'Programada' => 'warning',
                                            'En Progreso' => 'info',
                                            default => 'gray'
                                        };
                                    @endphp
                                    <span class="badge badge-{{ $badgeColor }} uppercase font-bold tracking-wide text-xs">
                                        {{ $cita->estado_cita }}
                                    </span>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 md:grid-cols-3 gap-4 text-sm mt-4">
                                <div class="flex items-center gap-2 text-gray-700 bg-gray-50 p-2 rounded-lg">
                                    <i class="bi bi-clock text-blue-600"></i>
                                    <span class="font-medium">{{ \Carbon\Carbon::parse($cita->hora_inicio)->format('h:i A') }}</span>
                                </div>
                                <div class="flex items-center gap-2 text-gray-700 bg-gray-50 p-2 rounded-lg col-span-2 md:col-span-1">
                                    <i class="bi bi-building text-blue-600"></i>
                                    <span class="truncate">{{ $cita->consultorio->nombre ?? 'Consultorio' }}</span>
                                </div>
                            </div>

                            @if($cita->motivo)
                            <div class="mt-3 p-3 bg-gray-50 rounded-lg border border-gray-100">
                                <p class="text-xs text-gray-500 font-bold uppercase mb-1">Motivo Consulta</p>
                                <p class="text-sm text-gray-600">"{{ Str::limit($cita->motivo, 100) }}"</p>
                            </div>
                            @endif

                            @if($cita->observaciones)
                            <div class="mt-3 p-3 bg-yellow-50 rounded-lg border border-yellow-100">
                                <p class="text-xs text-yellow-600 font-bold uppercase mb-1">Observaciones</p>
                                <p class="text-sm text-gray-600 italic">"{{ Str::limit($cita->observaciones, 100) }}"</p>
                            </div>
                            @endif

                            <div class="mt-4 pt-4 border-t border-gray-100 flex justify-end">
                                <a href="{{ route('paciente.citas.show', $cita->id) }}" class="btn btn-sm btn-outline hover:bg-emerald-50 text-emerald-600 border-emerald-200 hover:border-emerald-300">
                                    Ver Detalles <i class="bi bi-arrow-right ml-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="text-center py-12 bg-gray-50 rounded-2xl border-2 border-dashed border-gray-200">
                    <div class="w-16 h-16 mx-auto bg-white rounded-full flex items-center justify-center mb-4 shadow-sm">
                        <i class="bi bi-calendar-plus text-3xl text-gray-400"></i>
                    </div>
                    <p class="text-gray-900 font-medium mb-1">No tienes citas próximas</p>
                    <p class="text-sm text-gray-500 mb-6">¿Necesitas atención médica?</p>
                    <a href="{{ route('paciente.citas.create') }}" class="btn btn-primary btn-sm">
                        Agenda tu cita ahora
                    </a>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Realizadas Tab -->
        <div id="tab-realizadas" class="tab-content hidden">
            <div class="space-y-4">
                @forelse($citasRealizadas as $cita)
                <div class="card cita-card p-6 bg-white border border-gray-200 hover:border-emerald-300 transition-colors"
                     data-tipo="{{ $cita->tipo_cita_display ?? 'propia' }}"
                     data-paciente-especial="{{ $cita->paciente_especial_info->id ?? '' }}">
                    <div class="flex items-start justify-between">
                        <div>
                            <div class="flex items-center gap-2">
                                <h3 class="font-bold text-gray-900 text-lg">{{ $cita->especialidad->nombre ?? 'Consulta' }}</h3>
                                @if($cita->tipo_cita_display == 'terceros' && $cita->paciente_especial_info)
                                <span class="badge badge-purple text-xs">
                                    {{ $cita->paciente_especial_info->primer_nombre ?? '' }}
                                </span>
                                @endif
                            </div>
                            <p class="text-sm text-gray-600 mt-1 font-medium">
                                Dr. {{ $cita->medico->primer_nombre ?? '' }} {{ $cita->medico->primer_apellido ?? '' }}
                            </p>
                            <p class="text-xs text-gray-500 mt-2 flex items-center gap-2">
                                <i class="bi bi-calendar"></i> {{ \Carbon\Carbon::parse($cita->fecha_cita)->format('d M Y') }} 
                                <span class="mx-1">•</span>
                                <i class="bi bi-clock"></i> {{ \Carbon\Carbon::parse($cita->hora_inicio)->format('h:i A') }}
                            </p>
                        </div>
                        <div class="flex flex-col gap-2 items-end">
                            <span class="badge badge-success uppercase text-xs font-bold">Completada</span>
                            <a href="{{ route('paciente.citas.show', $cita->id) }}" class="btn btn-xs btn-ghost text-emerald-600">
                                Ver Detalle
                            </a>
                        </div>
                    </div>
                    @if($cita->observaciones)
                    <div class="mt-3 pt-3 border-t border-gray-100">
                        <p class="text-xs text-gray-500 font-bold uppercase">Observaciones:</p>
                        <p class="text-sm text-gray-600 italic">"{{ Str::limit($cita->observaciones, 100) }}"</p>
                    </div>
                    @endif
                </div>
                @empty
                <div class="text-center py-12 text-gray-500 bg-gray-50 rounded-2xl border border-gray-100">
                    <p>No hay registro de citas realizadas.</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Canceladas Tab -->
        <div id="tab-canceladas" class="tab-content hidden">
            <div class="space-y-4">
                @forelse($citasCanceladas as $cita)
                <div class="card cita-card p-6 bg-gray-50 opacity-75 hover:opacity-100 transition-opacity border border-gray-200"
                     data-tipo="{{ $cita->tipo_cita_display ?? 'propia' }}"
                     data-paciente-especial="{{ $cita->paciente_especial_info->id ?? '' }}">
                    <div class="flex items-start justify-between">
                        <div>
                            <div class="flex items-center gap-2">
                                <h3 class="font-bold text-gray-900 line-through">{{ $cita->especialidad->nombre ?? 'Consulta' }}</h3>
                            </div>
                            <p class="text-sm text-gray-600 mt-1">
                                Dr. {{ $cita->medico->primer_nombre ?? '' }} {{ $cita->medico->primer_apellido ?? '' }}
                            </p>
                            <p class="text-xs text-gray-500 mt-1">
                                Programada para: {{ \Carbon\Carbon::parse($cita->fecha_cita)->format('d/m/Y') }}
                            </p>
                        </div>
                        <span class="badge badge-danger uppercase text-xs font-bold">{{ $cita->estado_cita }}</span>
                    </div>
                    @if($cita->observaciones)
                    <div class="mt-4 p-3 bg-red-50 rounded-lg border border-red-100">
                        <p class="text-xs text-red-600 font-bold uppercase mb-1">Motivo Cancelación / Notas</p>
                        <p class="text-sm text-gray-700 italic">"{{ Str::limit($cita->observaciones, 150) }}"</p>
                    </div>
                    @else
                    <div class="mt-2 text-xs text-gray-400 italic">Sin motivo especificado</div>
                    @endif
                </div>
                @empty
                 <div class="text-center py-12 text-gray-500 bg-gray-50 rounded-2xl border border-gray-100">
                    <p>No tienes citas canceladas.</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Tab switching with visual updates
    document.querySelectorAll('.tab-button').forEach(button => {
        button.addEventListener('click', () => {
            // Reset classes
            document.querySelectorAll('.tab-button').forEach(b => {
                b.classList.remove('active', 'border-emerald-500', 'text-emerald-600');
                b.classList.add('border-transparent', 'text-gray-500');
            });
            document.querySelectorAll('.tab-content').forEach(c => c.classList.add('hidden'));
            
            // Set active
            button.classList.add('active', 'border-emerald-500', 'text-emerald-600');
            button.classList.remove('border-transparent', 'text-gray-500');
            
            document.getElementById('tab-' + button.dataset.tab).classList.remove('hidden');
        });
    });

    // Reuse filter logic (kept simple)
    function filtrarCitas() {
        const tipoFiltro = document.getElementById('filtro-tipo').value;
        const pacienteFiltro = document.getElementById('filtro-paciente')?.value || '';
        const containerPaciente = document.getElementById('filtro-paciente-container');
        
        if (containerPaciente) {
            if (tipoFiltro === 'terceros') {
                containerPaciente.classList.remove('hidden');
            } else {
                containerPaciente.classList.add('hidden');
            }
        }
        
        document.querySelectorAll('.cita-card').forEach(card => {
            const tipoCita = card.dataset.tipo;
            const pacienteEspecialId = card.dataset.pacienteEspecial;
            
            let mostrar = true;
            
            if (tipoFiltro === 'propia' && tipoCita !== 'propia') mostrar = false;
            if (tipoFiltro === 'terceros' && tipoCita !== 'terceros') mostrar = false;
            if (tipoFiltro === 'terceros' && pacienteFiltro && pacienteEspecialId !== pacienteFiltro) mostrar = false;
            
            card.style.display = mostrar ? '' : 'none';
        });
    }
</script>
@endpush
@endsection
