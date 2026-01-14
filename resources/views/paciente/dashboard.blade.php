@extends('layouts.paciente')

@section('title', 'Mi Portal')

@section('content')
<!-- Welcome Banner -->
<div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-medical-600 to-medical-500 shadow-xl mb-8"
     style="{{ $paciente->tema_dinamico && $paciente->banner_color ? 'background-image: linear-gradient(to right, var(--medical-600), var(--medical-500))' : '' }}">
    <div class="absolute top-0 right-0 -mt-10 -mr-10 w-64 h-64 bg-white/20 rounded-full mix-blend-overlay filter blur-3xl"></div>
    <div class="absolute bottom-0 left-0 -mb-10 -ml-10 w-64 h-64 bg-white/10 rounded-full mix-blend-overlay filter blur-3xl"></div>
    <div class="relative z-10 p-8">
        <div class="flex flex-col md:flex-row items-center justify-between gap-6">
            <div class="text-white text-center md:text-left" style="{{ $paciente->tema_dinamico ? 'color: var(--text-on-medical) !important' : '' }}">
                <h2 class="text-3xl md:text-4xl font-display font-bold mb-2">
                    ¡Hola, {{ auth()->user()->paciente->primer_nombre ?? 'Paciente' }}!
                </h2>
                <p class="text-lg opacity-90">¿Cómo te sientes hoy? Estamos aquí para cuidar de ti.</p>
            </div>
            <a href="{{ route('paciente.citas.create') }}" class="btn bg-white text-medical-600 hover:bg-gray-50 border-none shadow-md">
                <i class="bi bi-plus-lg"></i> Solicitar Cita
            </a>
        </div>
        
        <!-- Health Stats Mini -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-6">
            <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4 text-white" style="{{ $paciente->tema_dinamico ? 'color: var(--text-on-medical) !important' : '' }}">
                <i class="bi bi-calendar-check text-2xl mb-2"></i>
                <p class="text-2xl font-bold">{{ $stats['citas_proximas'] ?? 0 }}</p>
                <p class="text-sm opacity-80">Próximas Citas</p>
            </div>
            <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4 text-white" style="{{ $paciente->tema_dinamico ? 'color: var(--text-on-medical) !important' : '' }}">
                <i class="bi bi-file-medical text-2xl mb-2"></i>
                <p class="text-2xl font-bold">{{ $stats['historias'] ?? 0 }}</p>
                <p class="text-sm opacity-80">Historias</p>
            </div>
            <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4 text-white" style="{{ $paciente->tema_dinamico ? 'color: var(--text-on-medical) !important' : '' }}">
                <i class="bi bi-prescription text-2xl mb-2"></i>
                <p class="text-2xl font-bold">{{ $stats['recetas_activas'] ?? 0 }}</p>
                <p class="text-sm opacity-80">Recetas Activas</p>
            </div>
            <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4 text-white" style="{{ $paciente->tema_dinamico ? 'color: var(--text-on-medical) !important' : '' }}">
                <i class="bi bi-heart-pulse text-2xl mb-2"></i>
                <p class="text-2xl font-bold">{{ $stats['consultas_mes'] ?? 0 }}</p>
                <p class="text-sm opacity-80">Este Mes</p>
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Left Column: Appointments & History -->
    <div class="lg:col-span-2 space-y-8">
        
        <!-- Mis Citas Próximas -->
        <div class="card p-0 overflow-hidden">
            <div class="p-6 border-b border-gray-100 bg-gradient-to-r from-emerald-50 to-white flex justify-between items-center">
                <div>
                    <h3 class="text-lg font-display font-bold text-gray-900 flex items-center gap-2">
                        <i class="bi bi-calendar-event text-emerald-600"></i>
                        Mis Próximas Citas
                    </h3>
                    <p class="text-sm text-gray-600 mt-1">Tus consultas programadas</p>
                </div>
                <a href="{{ route('paciente.citas.index') }}" class="btn btn-sm btn-outline">Ver todas</a>
            </div>
            
            <div class="divide-y divide-gray-100">
                @forelse($citas_proximas ?? [] as $cita)
                <div class="p-6 hover:bg-gray-50 transition-colors group relative">
                    @php
                        $pagosActivos = $cita->facturaPaciente ? $cita->facturaPaciente->pagos->where('status', true) : collect();
                        $tienePago = $pagosActivos->count() > 0;
                        $pagoPendiente = $pagosActivos->where('estado', 'Pendiente')->count() > 0;
                        $pagoConfirmado = $pagosActivos->where('estado', 'Confirmado')->count() > 0;
                        $pagoRechazado = $pagosActivos->where('estado', 'Rechazado')->isNotEmpty() && !$pagoConfirmado && !$pagoPendiente;
                        $ultimoRechazo = $pagoRechazado ? $pagosActivos->where('estado', 'Rechazado')->sortByDesc('created_at')->first() : null;
                    @endphp

                    @if($pagoRechazado)
                        <div class="absolute top-4 right-6 flex items-center gap-2 px-3 py-1 bg-red-50 rounded-full border border-red-100 shadow-sm z-10">
                            <span class="relative flex h-2 w-2">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2 w-2 bg-red-500"></span>
                            </span>
                            <p class="text-[9px] font-bold text-red-600 uppercase tracking-wider">
                                Pago Rechazado
                                @if($ultimoRechazo && $ultimoRechazo->comentarios)
                                    <span class="mx-1 text-red-300">|</span>
                                    <span class="normal-case font-medium text-red-500 italic">"{{ Str::limit($ultimoRechazo->comentarios, 40) }}"</span>
                                @endif
                            </p>
                        </div>
                    @endif

                    <div class="flex gap-5">
                        <!-- Date Box -->
                        <div class="flex-shrink-0 text-center">
                            <div class="w-20 h-20 border-2 border-medical-200 rounded-xl p-3 bg-white group-hover:border-medical-300 group-hover:shadow-md transition-all">
                                <span class="block text-3xl font-bold text-medical-700">
                                    {{ \Carbon\Carbon::parse($cita->fecha_hora)->format('d') }}
                                </span>
                                <span class="block text-xs uppercase font-bold text-gray-500">
                                    {{ \Carbon\Carbon::parse($cita->fecha_hora)->format('M') }}
                                </span>
                            </div>
                        </div>
                        
                        <!-- Info -->
                        <div class="flex-1">
                            <div class="flex items-start justify-between mb-4">
                                <div>
                                    <h4 class="font-bold text-gray-900 text-lg group-hover:text-medical-700 transition-colors">
                                        {{ $cita->medico->especialidad->nombre ?? 'Consulta General' }}
                                    </h4>
                                    <p class="text-gray-600 flex items-center gap-2 mt-1">
                                        <i class="bi bi-person-badge text-medical-600"></i>
                                        <span class="font-medium text-sm text-slate-700">Dr. {{ $cita->medico->primer_nombre }} {{ $cita->medico->primer_apellido }}</span>
                                    </p>
                                </div>
                                <div class="flex flex-col items-end gap-2">
                                    @php
                                        $badgeColor = match($cita->estado_cita) {
                                            'Confirmada' => 'success',
                                            'Programada' => 'warning',
                                            'En Progreso' => 'info',
                                            'Completada' => 'success',
                                            'Cancelada', 'No Asistió' => 'danger',
                                            default => 'gray'
                                        };

                                        // Payment Badge logic
                                        $pagoStatusText = 'PAGO PENDIENTE';
                                        $pagoBadgeType = 'danger';

                                        if($pagoConfirmado) {
                                            $pagoStatusText = 'PAGO CONFIRMADO';
                                            $pagoBadgeType = 'success';
                                        } elseif($pagoPendiente) {
                                            $pagoStatusText = 'PAGO EN REVISIÓN';
                                            $pagoBadgeType = 'warning';
                                        } elseif($pagoRechazado) {
                                            $pagoStatusText = 'PAGO RECHAZADO';
                                            $pagoBadgeType = 'danger';
                                        }
                                    @endphp
                                    <span class="badge badge-{{ $badgeColor }} uppercase font-bold tracking-wider text-[10px] px-3 py-1 scale-95 origin-right">
                                        {{ $cita->estado_cita }}
                                    </span>
                                    <span class="badge badge-{{ $pagoBadgeType }} uppercase font-bold tracking-wider text-[10px] px-3 py-1 scale-95 origin-right">
                                        {{ $pagoStatusText }}
                                    </span>
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-2 gap-4 text-xs font-medium mb-4">
                                <div class="flex items-center gap-2.5 text-slate-600 bg-slate-50 border border-slate-100 px-3 py-2 rounded-xl">
                                    <i class="bi bi-clock text-emerald-600 text-sm"></i>
                                    <span>{{ \Carbon\Carbon::parse($cita->fecha_hora)->format('h:i A') }}</span>
                                </div>
                                <div class="flex items-center gap-2.5 text-slate-600 bg-slate-50 border border-slate-100 px-3 py-2 rounded-xl">
                                    <i class="bi bi-building text-emerald-600 text-sm"></i>
                                    <span class="truncate">{{ $cita->consultorio->nombre ?? 'Consultorio' }}</span>
                                </div>
                            </div>

                            @if($cita->motivo ?? null)
                            <div class="p-3 bg-emerald-50 rounded-lg mb-3">
                                <p class="text-sm text-gray-700"><strong>Motivo:</strong> {{ $cita->motivo }}</p>
                            </div>
                            @endif
                            
                                <!-- Actions -->
                                <div class="flex flex-wrap gap-2 mt-2">
                                    <a href="{{ route('paciente.citas.show', $cita->id) }}" class="btn btn-sm btn-outline hover:bg-emerald-50 text-emerald-600 border-emerald-200">
                                        <i class="bi bi-eye"></i> Ver
                                    </a>

                                    @if(!$pagoConfirmado && !$pagoPendiente && !in_array($cita->estado_cita, ['Cancelada', 'No Asistió']))
                                        <a href="{{ route('paciente.pagos.registrar', $cita->id) }}" class="btn btn-sm btn-primary shadow-sm shadow-emerald-200 text-xs px-3">
                                            <i class="bi bi-credit-card mr-1"></i> Pagar
                                        </a>
                                    @endif

                                @if(in_array($cita->estado_cita, ['Programada', 'Confirmada']))
                                    <button onclick="openCancelModal({{ $cita->id }})" class="btn btn-sm btn-outline text-rose-600 hover:bg-rose-50 border-rose-200">
                                        <i class="bi bi-x-circle"></i> Cancelar
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="p-12 text-center">
                    <div class="inline-flex items-center justify-center w-24 h-24 rounded-full bg-gray-50 mb-4">
                        <i class="bi bi-calendar-x text-5xl text-gray-300"></i>
                    </div>
                    <p class="text-gray-500 mb-2 font-medium text-lg">No tienes citas próximas agendadas</p>
                    <p class="text-gray-400 text-sm mb-4">Solicita tu primera cita médica ahora</p>
                    <a href="{{ route('paciente.citas.create') }}" class="btn btn-primary inline-flex items-center">
                        <i class="bi bi-calendar-plus"></i>
                        Agendar mi primera cita
                    </a>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Historial Reciente -->
        <div class="card">
            <div class="p-6 border-b border-gray-100 bg-gradient-to-r from-blue-50 to-white">
                <div class="flex justify-between items-center">
                    <div>
                        <h3 class="text-lg font-display font-bold text-gray-900 flex items-center gap-2">
                            <i class="bi bi-clock-history text-blue-600"></i>
                            Historial Médico Reciente
                        </h3>
                        <p class="text-sm text-gray-600 mt-1">Tus últimas consultas y procedimientos</p>
                    </div>
                    <a href="{{ route('paciente.historial') }}" class="btn btn-sm btn-outline">Ver historial completo</a>
                </div>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    @forelse($historial_reciente ?? [] as $registro)
                    <div class="flex gap-4 items-start p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors cursor-pointer">
                        <div class="w-12 h-12 rounded-xl bg-blue-100 flex items-center justify-center flex-shrink-0">
                            <i class="bi bi-file-medical text-blue-600 text-xl"></i>
                        </div>
                        <div class="flex-1">
                            <h4 class="font-semibold text-gray-900">{{ $registro->diagnostico ?? 'Consulta Médica' }}</h4>
                            <p class="text-sm text-gray-600 mt-1">
                                Dr. {{ $registro->medico->usuario->nombre ?? 'Médico' }}
                            </p>
                            <p class="text-xs text-gray-500 mt-2 flex items-center gap-2">
                                <i class="bi bi-calendar3"></i>
                                {{ \Carbon\Carbon::parse($registro->created_at)->format('d/m/Y') }}
                            </p>
                        </div>
                        <a href="{{ url('paciente/historial/' . $registro->id) }}" class="btn btn-sm btn-outline opacity-0 group-hover:opacity-100 transition-opacity">
                            <i class="bi bi-eye"></i>
                        </a>
                    </div>
                    @empty
                    <div class="text-center py-8">
                        <i class="bi bi-folder2-open text-4xl text-gray-300 mb-3"></i>
                        <p class="text-gray-500">No hay registros en tu historial médico</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Recetas Activas -->
        <div class="card">
            <div class="p-6 border-b border-gray-100 bg-gradient-to-r from-purple-50 to-white">
                <h3 class="text-lg font-display font-bold text-gray-900 flex items-center gap-2">
                    <i class="bi bi-prescription text-purple-600"></i>
                    Recetas Activas
                </h3>
                <p class="text-sm text-gray-600 mt-1">Tus medicamentos actuales</p>
            </div>
            <div class="p-6">
                <div class="space-y-3">
                    @forelse($recetas_activas ?? [] as $receta)
                    <div class="p-4 bg-purple-50 rounded-xl border border-purple-200">
                        <div class="flex items-start justify-between">
                            <div>
                                <h4 class="font-bold text-gray-900">{{ $receta->medicamento ?? 'Medicamento' }}</h4>
                                <p class="text-sm text-gray-600 mt-1">
                                    <strong>Dosis:</strong> {{ $receta->dosis ?? 'N/A' }} - {{ $receta->frecuencia ?? 'N/A' }}
                                </p>
                                <p class="text-sm text-gray-600">
                                    <strong>Duración:</strong> {{ $receta->duracion ?? 'N/A' }}
                                </p>
                                @if($receta->instrucciones ?? null)
                                <p class="text-xs text-gray-500 mt-2">
                                    <i class="bi bi-info-circle"></i> {{ $receta->instrucciones }}
                                </p>
                                @endif
                            </div>
                            <span class="badge badge-purple">Activa</span>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-8">
                        <i class="bi bi-prescription text-4xl text-gray-300 mb-3"></i>
                        <p class="text-gray-500">No tienes recetas activas</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Right Column: Profile & Quick Menu -->
    <div class="space-y-6">
        <!-- Perfil Card -->
        <div class="card p-0 overflow-hidden">
            @if($paciente->banner_perfil)
                <div class="relative h-32 bg-cover bg-center" style="background-image: url('{{ asset('storage/' . $paciente->banner_perfil) }}')">
                    <div class="absolute inset-0 bg-black/10"></div>
                </div>
            @else
                <div class="relative h-32 {{ $paciente->banner_color ?? 'bg-gradient-to-r from-emerald-100 via-green-100 to-blue-100' }}"
                     style="{{ str_contains($paciente->banner_color ?? '', '#') ? 'background-color: ' . $paciente->banner_color : '' }}"></div>
            @endif
            <div class="relative px-6 pb-6">
                <div class="flex flex-col items-center -mt-16">
                    <div class="inline-block p-1.5 bg-white rounded-full shadow-lg mb-3">
                        @if(auth()->user()->paciente->foto_perfil)
                            <img src="{{ asset('storage/' . auth()->user()->paciente->foto_perfil) }}" 
                                 alt="Foto de perfil" 
                                 class="w-24 h-24 rounded-full object-cover border-4 border-white">
                        @else
                            <div class="w-24 h-24 rounded-full bg-gradient-to-br from-emerald-100 to-green-100 flex items-center justify-center text-4xl text-emerald-700 font-bold border-4 border-white">
                                {{ strtoupper(substr(auth()->user()->paciente->primer_nombre ?? 'P', 0, 1)) }}
                            </div>
                        @endif
                    </div>
                    <h3 class="text-xl font-bold text-gray-900">
                        {{ auth()->user()->paciente->primer_nombre ?? 'Usuario' }}
                        {{ auth()->user()->paciente->primer_apellido ?? '' }}
                    </h3>
                    <p class="text-gray-500 text-sm mb-2">Paciente</p>
                    
                    <!-- Health Info -->
                    <div class="w-full space-y-2 mt-4">
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <span class="text-sm text-gray-600">Tipo de Sangre</span>
                            <span class="font-bold text-gray-900">{{ $paciente->historiaClinicaBase->tipo_sangre ?? 'N/A' }}</span>
                        </div>
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <span class="text-sm text-gray-600">Edad</span>
                            <span class="font-bold text-gray-900">
                                {{ isset($paciente->fecha_nac) ? \Carbon\Carbon::parse($paciente->fecha_nac)->age . ' años' : 'N/A' }}
                            </span>
                        </div>
                    </div>
                    
                    <div class="w-full grid grid-cols-3 gap-3 text-center border-t border-gray-100 pt-5 mt-5">
                        <div>
                            <span class="block font-bold text-gray-900 text-xl">{{ $stats['total_citas'] ?? 0 }}</span>
                            <span class="text-xs text-gray-500">Citas</span>
                        </div>
                        <div>
                            <span class="block font-bold text-gray-900 text-xl">{{ $stats['recetas_activas'] ?? 0 }}</span>
                            <span class="text-xs text-gray-500">Recetas</span>
                        </div>
                        <div>
                            <span class="block font-bold text-gray-900 text-xl">
                                {{ isset(auth()->user()->created_at) ? \Carbon\Carbon::parse(auth()->user()->created_at)->diffInMonths(\Carbon\Carbon::now()) : 0 }}
                            </span>
                            <span class="text-xs text-gray-500">Meses</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Accesos Directos -->
        <div class="card p-6">
            <h4 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
                <i class="bi bi-grid text-medical-600"></i>
                Menú Rápido
            </h4>
            <div class="space-y-2">
                <a href="{{ route('paciente.citas.create') }}" class="flex items-center gap-3 p-3 rounded-xl hover:bg-medical-50 text-gray-600 hover:text-medical-600 transition-all group">
                    <div class="w-10 h-10 rounded-lg bg-medical-50 flex items-center justify-center text-medical-600 group-hover:bg-medical-200/20 transition-colors">
                        <i class="bi bi-calendar-plus text-lg"></i>
                    </div>
                    <span class="font-medium flex-1">Agendar Cita</span>
                    <i class="bi bi-chevron-right text-gray-400 group-hover:translate-x-1 transition-transform"></i>
                </a>
                <a href="{{ route('paciente.historial') }}" class="flex items-center gap-3 p-3 rounded-xl hover:bg-blue-50 text-gray-600 hover:text-blue-700 transition-all group">
                    <div class="w-10 h-10 rounded-lg bg-blue-50 flex items-center justify-center text-blue-600 group-hover:bg-blue-100 transition-colors">
                        <i class="bi bi-folder2-open"></i>
                    </div>
                    <span class="font-medium flex-1">Mi Historial</span>
                    <i class="bi bi-chevron-right text-gray-400 group-hover:translate-x-1 transition-transform"></i>
                </a>
                <a href="{{ route('paciente.pagos') }}" class="flex items-center gap-3 p-3 rounded-xl hover:bg-purple-50 text-gray-600 hover:text-purple-700 transition-all group">
                    <div class="w-10 h-10 rounded-lg bg-purple-50 flex items-center justify-center text-purple-600 group-hover:bg-purple-100 transition-colors">
                        <i class="bi bi-wallet2"></i>
                    </div>
                    <span class="font-medium flex-1">Mis Pagos</span>
                    <i class="bi bi-chevron-right text-gray-400 group-hover:translate-x-1 transition-transform"></i>
                </a>
                <a href="{{ route('paciente.perfil.edit') }}" class="flex items-center gap-3 p-3 rounded-xl hover:bg-amber-50 text-gray-600 hover:text-amber-700 transition-all group">
                    <div class="w-10 h-10 rounded-lg bg-amber-50 flex items-center justify-center text-amber-600 group-hover:bg-amber-100 transition-colors">
                        <i class="bi bi-person-lines-fill"></i>
                    </div>
                    <span class="font-medium flex-1">Editar Mi Perfil</span>
                    <i class="bi bi-chevron-right text-gray-400 group-hover:translate-x-1 transition-transform"></i>
                </a>
            </div>
        </div>

        <!-- Health Tips -->
        <div class="card p-6 bg-gradient-to-br from-blue-50 to-white border-blue-200">
            <div class="flex gap-3">
                <i class="bi bi-lightbulb text-blue-600 text-2xl"></i>
                <div>
                    <h4 class="font-semibold text-gray-900 mb-2">Consejo de Salud</h4>
                    <p class="text-sm text-gray-600">Recuerda beber al menos 8 vasos de agua al día y mantener una alimentación balanceada para una mejor salud.</p>
                </div>
            </div>
        </div>

        <!-- Ayuda -->
        <div class="card p-6">
            <div class="flex gap-3">
                <i class="bi bi-question-circle text-emerald-600 text-2xl"></i>
                <div>
                    <h4 class="font-semibold text-gray-900 mb-1">¿Necesitas ayuda?</h4>
                    <p class="text-sm text-gray-600 mb-3">Contacta con soporte para cualquier consulta</p>
                    <a href="#" class="text-sm text-emerald-600 hover:text-emerald-700 font-semibold">
                        Contactar Soporte <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
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
            <p class="text-gray-500 mb-6 font-medium">Por favor, indícanos el motivo de la cancelación para reagendarte pronto.</p>
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
