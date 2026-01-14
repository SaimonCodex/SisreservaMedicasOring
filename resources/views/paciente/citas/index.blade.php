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
                <div class="card cita-card p-6 hover:shadow-lg transition-all border border-gray-100 hover:border-blue-200 group relative" 
                     data-tipo="{{ $cita->tipo_cita_display ?? 'propia' }}"
                     data-paciente-especial="{{ $cita->paciente_especial_info->id ?? '' }}">

                    @php
                        $ultimoPago = $cita->facturaPaciente ? $cita->facturaPaciente->pagos->where('status', true)->sortByDesc('created_at')->first() : null;
                        $pagoStatus = $ultimoPago ? $ultimoPago->estado : null;
                    @endphp

                    @if($pagoStatus == 'Rechazado')
                        <div class="absolute top-4 right-6 flex items-center gap-2 px-3 py-1 bg-red-50 rounded-full border border-red-100 shadow-sm z-10 transition-transform group-hover:scale-105">
                            <span class="relative flex h-2 w-2">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2 w-2 bg-red-500"></span>
                            </span>
                            <p class="text-[9px] font-bold text-red-600 uppercase tracking-wider">
                                Pago Rechazado
                                @if($ultimoPago->comentarios)
                                    <span class="mx-1 text-red-300">|</span>
                                    <span class="normal-case font-medium text-red-500 italic">"{{ Str::limit($ultimoPago->comentarios, 35) }}"</span>
                                @endif
                            </p>
                        </div>
                    @endif

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

                                    @if($cita->facturaPaciente && $cita->facturaPaciente->pagos->where('status', true)->count() > 0)
                                        @php
                                            $pagoBadge = match($pagoStatus) {
                                                'Confirmado' => 'success',
                                                'Pendiente' => 'warning',
                                                'Rechazado' => 'danger',
                                                default => 'gray'
                                            };
                                        @endphp
                                        @if($pagoStatus != 'Rechazado')
                                        <div class="flex items-center gap-1 mt-1">
                                            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-tighter">Pago:</span>
                                            <span class="badge badge-{{ $pagoBadge }} uppercase font-bold tracking-wide text-[10px] py-0 h-4">
                                                {{ $pagoStatus }}
                                            </span>
                                        </div>
                                        @endif
                                    @elseif($cita->facturaPaciente && $cita->estado_cita != 'Cancelada')
                                        <div class="flex items-center gap-1 mt-1">
                                            <span class="badge badge-danger uppercase font-bold tracking-wide text-[10px] py-0 h-4">
                                                PAGO PENDIENTE
                                            </span>
                                        </div>
                                    @endif
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

                            <div class="mt-4 pt-4 border-t border-gray-100 flex flex-wrap justify-end gap-2">
                                {{-- Botón Ver Detalle --}}
                                <a href="{{ route('paciente.citas.show', $cita->id) }}" class="btn btn-sm btn-outline hover:bg-emerald-50 text-emerald-600 border-emerald-200 hover:border-emerald-300">
                                    Ver Detalles <i class="bi bi-arrow-right ml-1"></i>
                                </a>

                                {{-- Botón Realizar Pago --}}
                                @php
                                    $tienePago = $cita->facturaPaciente && $cita->facturaPaciente->pagos->count() > 0;
                                    $pagoPendiente = $cita->facturaPaciente && $cita->facturaPaciente->pagos->where('status', true)->where('estado', 'Pendiente')->count() > 0;
                                    $pagoConfirmado = $cita->facturaPaciente && $cita->facturaPaciente->pagos->where('status', true)->where('estado', 'Confirmado')->count() > 0;
                                @endphp

                                @if(!$pagoConfirmado && !$pagoPendiente && !in_array($cita->estado_cita, ['Cancelada', 'No Asistió']))
                                    <a href="{{ route('paciente.pagos.registrar', $cita->id) }}" class="btn btn-sm btn-primary shadow-sm shadow-emerald-200">
                                        <i class="bi bi-credit-card mr-1"></i> Realizar Pago
                                    </a>
                                @endif

                                {{-- Botón Cancelar Cita --}}
                                @if(in_array($cita->estado_cita, ['Programada', 'Confirmada']))
                                    <button onclick="openCancelModal({{ $cita->id }})" class="btn btn-sm btn-outline text-rose-600 hover:bg-rose-50 border-rose-200 hover:border-rose-300">
                                        <i class="bi bi-x-circle mr-1"></i> Cancelar
                                    </button>
                                @endif
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
                            
                            @if($cita->facturaPaciente && $cita->facturaPaciente->pagos->where('status', true)->count() > 0)
                                @php
                                    $pago = $cita->facturaPaciente->pagos->where('status', true)->first();
                                @endphp
                                <span class="badge badge-{{ $pago->estado == 'Confirmado' ? 'success' : ($pago->estado == 'Rechazado' ? 'danger' : 'warning') }} uppercase text-[10px] py-0 h-4">
                                    Pago: {{ $pago->estado }}
                                </span>
                            @endif

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
                        <div class="flex flex-col gap-1 items-end">
                            <span class="badge badge-danger uppercase text-xs font-bold">{{ $cita->estado_cita }}</span>
                            @if($cita->facturaPaciente && $cita->facturaPaciente->pagos->where('status', true)->count() > 0)
                                <span class="badge badge-gray uppercase text-[10px] py-0 h-4 opacity-50">
                                    Pago: {{ $cita->facturaPaciente->pagos->where('status', true)->first()->estado }}
                                </span>
                            @endif
                        </div>
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
    // Tab switching
    document.querySelectorAll('.tab-button').forEach(button => {
        button.addEventListener('click', () => {
            document.querySelectorAll('.tab-button').forEach(b => {
                b.classList.remove('active', 'border-emerald-500', 'text-emerald-600');
                b.classList.add('border-transparent', 'text-gray-500');
            });
            document.querySelectorAll('.tab-content').forEach(c => c.classList.add('hidden'));
            button.classList.add('active', 'border-emerald-500', 'text-emerald-600');
            button.classList.remove('border-transparent', 'text-gray-500');
            document.getElementById('tab-' + button.dataset.tab).classList.remove('hidden');
        });
    });

    // Custom Modal Logic
    let currentCitaId = null;

    function openCancelModal(citaId) {
        currentCitaId = citaId;
        const modal = document.getElementById('modalCancelacion');
        const modalContent = modal.querySelector('.modal-content');
        modal.classList.remove('hidden');
        setTimeout(() => {
            modal.classList.remove('opacity-0');
            modalContent.classList.remove('scale-95', 'opacity-0');
        }, 10);
    }

    function closeCancelModal() {
        const modal = document.getElementById('modalCancelacion');
        const modalContent = modal.querySelector('.modal-content');
        modal.classList.add('opacity-0');
        modalContent.classList.add('scale-95', 'opacity-0');
        setTimeout(() => {
            modal.classList.add('hidden');
            currentCitaId = null;
            document.getElementById('motivo_input').value = '';
            document.getElementById('motivo_error').classList.add('hidden');
        }, 300);
    }

    async function confirmarCancelacion() {
        const motivo = document.getElementById('motivo_input').value.trim();
        if (!motivo) {
            document.getElementById('motivo_error').classList.remove('hidden');
            return;
        }

        const btn = document.getElementById('confirmCancelBtn');
        const originalText = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = '<i class="bi bi-hourglass-split animate-spin mr-2"></i> Procesando...';

        try {
            const formData = new FormData();
            formData.append('motivo', motivo);
            formData.append('_token', '{{ csrf_token() }}');

            const response = await fetch(`{{ url('citas') }}/${currentCitaId}/solicitar-cancelacion`, {
                method: 'POST',
                body: formData,
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });

            const data = await response.json();

            if (response.ok && data.success !== false) {
                // Success feedback and reload
                btn.innerHTML = '<i class="bi bi-check-lg mr-2"></i> ¡Hecho!';
                setTimeout(() => location.reload(), 1000);
            } else {
                alert(data.message || 'No se pudo cancelar la cita');
                btn.disabled = false;
                btn.innerHTML = originalText;
            }
        } catch (error) {
            console.error(error);
            alert('Hubo un problema de conexión');
            btn.disabled = false;
            btn.innerHTML = originalText;
        }
    }

    function filtrarCitas() {
        const tipoFiltro = document.getElementById('filtro-tipo').value;
        const pacienteFiltro = document.getElementById('filtro-paciente')?.value || '';
        const containerPaciente = document.getElementById('filtro-paciente-container');
        
        if (containerPaciente) {
            if (tipoFiltro === 'terceros') containerPaciente.classList.remove('hidden');
            else containerPaciente.classList.add('hidden');
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

<!-- Custom Modal: Cancelar Cita -->
<div id="modalCancelacion" class="fixed inset-0 z-50 hidden opacity-0 transition-opacity duration-300 flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm" onclick="closeCancelModal()"></div>
    <div class="modal-content relative bg-white w-full max-w-md rounded-2xl shadow-2xl overflow-hidden transform scale-95 opacity-0 transition-all duration-300 border border-gray-100">
        <div class="h-2 bg-gradient-to-r from-red-500 to-rose-600"></div>
        <div class="p-8">
            <div class="w-16 h-16 bg-red-50 rounded-2xl flex items-center justify-center mb-6 ring-4 ring-red-50/50">
                <i class="bi bi-calendar-x-fill text-red-500 text-3xl"></i>
            </div>
            <h3 class="text-2xl font-display font-bold text-gray-900 mb-2">¿Cancelar esta cita?</h3>
            <p class="text-gray-500 mb-6 font-medium">Lamentamos que no puedas asistir. Por favor, indícanos el motivo de la cancelación para reagendarte pronto.</p>
            <div class="space-y-1.5">
                <label for="motivo_input" class="text-sm font-bold text-gray-700 ml-1">Motivo de cancelación</label>
                <textarea id="motivo_input" rows="3" 
                    class="w-full px-4 py-3 rounded-xl border-gray-200 bg-gray-50 text-gray-900 focus:bg-white focus:ring-2 focus:ring-red-500/20 focus:border-red-500 transition-all resize-none placeholder:text-gray-400"
                    placeholder="Escriba aquí el motivo..."
                    oninput="document.getElementById('motivo_error').classList.add('hidden')"></textarea>
                <p id="motivo_error" class="hidden text-xs font-bold text-red-500 mt-1 flex items-center gap-1">
                    <i class="bi bi-exclamation-circle"></i> Debes ingresar un motivo
                </p>
            </div>
            <div class="flex gap-3 mt-8">
                <button onclick="closeCancelModal()" 
                    class="flex-1 px-6 py-3.5 rounded-xl font-bold text-gray-600 bg-gray-100 hover:bg-gray-200 transition-all active:scale-95">
                    Volver
                </button>
                <button id="confirmCancelBtn" onclick="confirmarCancelacion()" 
                    class="flex-1 px-6 py-3.5 rounded-xl font-bold text-white bg-red-600 hover:bg-red-700 shadow-lg shadow-red-200 transition-all active:scale-95 flex items-center justify-center">
                    Confirmar
                </button>
            </div>
        </div>
    </div>
</div>
@endpush
@endsection
