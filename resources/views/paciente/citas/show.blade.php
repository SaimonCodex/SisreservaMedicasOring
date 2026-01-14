@extends('layouts.paciente')

@section('title', 'Detalles de la Cita')

@section('content')
<div class="mb-6">
    <a href="{{ route('paciente.citas.index') }}" class="text-emerald-600 hover:text-emerald-700 inline-flex items-center text-sm font-medium mb-3">
        <i class="bi bi-arrow-left mr-1"></i> Volver a Mis Citas
    </a>
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h2 class="text-3xl font-display font-bold text-gray-900">Consulta {{ $cita->especialidad->nombre ?? 'General' }}</h2>
            <p class="text-gray-500 mt-1">Detalle completo de tu cita médica programada</p>
        </div>
        <div>
            @php
                $statusColor = match(strtolower($cita->estado_cita)) {
                    'confirmada' => 'success',
                    'programada', 'pendiente' => 'warning',
                    'completada' => 'primary',
                    'cancelada', 'no asistió' => 'danger',
                    default => 'gray'
                };
            @endphp
            <span class="badge badge-{{ $statusColor }} text-lg px-4 py-2 uppercase">
                {{ $cita->estado_cita }}
            </span>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Columna Principal -->
    <div class="lg:col-span-2 space-y-6">
        
        <!-- Tarjeta de Fecha y Hora -->
        <div class="card p-0 overflow-hidden">
            <div class="bg-gradient-to-r from-emerald-600 to-teal-600 p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="font-bold text-xl opacity-90">Horario Programado</h3>
                        <p class="text-white/80 text-sm">Asegúrate de llegar 15 minutos antes</p>
                    </div>
                    <div class="w-12 h-12 rounded-full bg-white/20 flex items-center justify-center border-4 border-white/30 backdrop-blur-sm">
                        <i class="bi bi-calendar-event text-2xl"></i>
                    </div>
                </div>
            </div>
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="bg-gray-50 p-4 rounded-xl flex items-center gap-4 border border-gray-100">
                    <div class="w-12 h-12 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 text-xl font-bold flex-shrink-0">
                        {{ \Carbon\Carbon::parse($cita->fecha_cita)->format('d') }}
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 uppercase font-bold">{{ \Carbon\Carbon::parse($cita->fecha_cita)->isoFormat('F Y') }}</p>
                        <p class="font-bold text-gray-900 text-lg capitalize">{{ \Carbon\Carbon::parse($cita->fecha_cita)->locale('es')->dayName }}</p>
                    </div>
                </div>
                <div class="bg-gray-50 p-4 rounded-xl flex items-center gap-4 border border-gray-100">
                    <div class="w-12 h-12 rounded-full bg-amber-100 flex items-center justify-center text-amber-600 text-xl flex-shrink-0">
                        <i class="bi bi-clock"></i>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 uppercase font-bold">Hora Inicio</p>
                        <p class="font-bold text-gray-900 text-lg">{{ \Carbon\Carbon::parse($cita->hora_inicio)->format('h:i A') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tarjeta de Consultorio -->
        <div class="card p-6 border-l-4 border-l-emerald-500">
            <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                <i class="bi bi-building text-emerald-600"></i>
                Detalles del Consultorio
            </h3>
            
            <div class="flex items-start gap-4">
                <div class="w-16 h-16 rounded-xl bg-emerald-100 flex items-center justify-center text-3xl font-bold text-emerald-700 flex-shrink-0">
                    <i class="bi bi-hospital"></i>
                </div>
                <div class="flex-1">
                    <h4 class="text-xl font-bold text-gray-900">{{ $cita->consultorio->nombre }}</h4>
                    <p class="text-gray-600">{{ $cita->consultorio->descripcion }}</p>
                    
                    <div class="mt-4 grid grid-cols-1 gap-3 text-sm">
                        <div class="flex items-start gap-2 text-gray-700">
                            <i class="bi bi-geo-alt-fill text-emerald-500 mt-1"></i>
                            <div>
                                <p class="font-medium">{{ $cita->consultorio->direccion_detallada }}</p>
                                <p class="text-gray-500">
                                    {{ $cita->consultorio->parroquia->nombre ?? '' }}, 
                                    {{ $cita->consultorio->municipio->nombre ?? '' }} - 
                                    {{ $cita->consultorio->ciudad->nombre ?? '' }}, 
                                    {{ $cita->consultorio->estado->nombre ?? '' }}
                                </p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2 text-gray-700">
                            <i class="bi bi-telephone-fill text-emerald-500"></i>
                            <span>{{ $cita->consultorio->telefono ?? 'Sin teléfono' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tarjeta del Médico -->
        <div class="card p-6 border-l-4 border-l-blue-500">
            <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                <i class="bi bi-person-badge text-blue-600"></i>
                Médico Especialista
            </h3>
            <div class="flex items-start gap-4">
                <div class="w-16 h-16 rounded-full bg-gradient-to-br from-blue-100 to-indigo-100 flex items-center justify-center text-2xl font-bold text-blue-700 border-4 border-white shadow-sm flex-shrink-0">
                    {{ substr($cita->medico->primer_nombre, 0, 1) }}{{ substr($cita->medico->primer_apellido, 0, 1) }}
                </div>
                <div class="flex-1">
                    <h4 class="text-xl font-bold text-gray-900">Dr. {{ $cita->medico->primer_nombre }} {{ $cita->medico->primer_apellido }}</h4>
                    <p class="text-gray-600 font-medium">{{ $cita->especialidad->nombre ?? 'Especialista' }}</p>
                    
                    <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                        <div class="flex items-center gap-2 text-gray-700">
                            <i class="bi bi-hospital text-blue-500"></i>
                            <span>{{ $cita->consultorio->nombre ?? 'Consultorio Asignado' }}</span>
                        </div>
                        <div class="flex items-center gap-2 text-gray-700">
                            <i class="bi bi-geo-alt text-blue-500"></i>
                            <span>{{ $cita->consultorio->ubicacion ?? 'Centro Médico Principal' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tarjeta del Paciente -->
        <div class="card p-6 border-l-4 border-l-purple-500">
            <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                <i class="bi bi-person-heart text-purple-600"></i>
                Información del Paciente
            </h3>
            
            @php
                $esPacienteEspecial = !empty($cita->paciente_especial_id);
                $pacienteData = $esPacienteEspecial ? $cita->pacienteEspecial : $cita->paciente;
            @endphp

            <div class="flex items-center gap-4 mb-4">
                <div class="w-12 h-12 rounded-full bg-purple-100 flex items-center justify-center text-purple-700 font-bold text-lg border-2 border-purple-200">
                    <i class="bi bi-person"></i>
                </div>
                <div>
                    <h4 class="font-bold text-gray-900 text-lg">
                        {{ $pacienteData->primer_nombre }} {{ $pacienteData->primer_apellido }}
                    </h4>
                    <p class="text-gray-500 text-sm">
                        {{ $pacienteData->tipo_documento }}-{{ $pacienteData->numero_documento }}
                    </p>
                </div>
                @if($esPacienteEspecial)
                <span class="ml-auto badge badge-purple">Paciente Especial</span>
                @else
                <span class="ml-auto badge badge-emerald">Paciente Titular</span>
                @endif
            </div>

            @if($esPacienteEspecial && isset($cita->pacienteEspecial->representantes) && $cita->pacienteEspecial->representantes->count() > 0)
            <div class="mt-4 pt-4 border-t border-gray-100">
                <p class="text-xs text-gray-400 uppercase font-bold mb-2">Representante Responsable</p>
                @foreach($cita->pacienteEspecial->representantes as $rep)
                    <div class="flex items-center gap-2 text-sm text-gray-700">
                        <i class="bi bi-shield-check text-green-500"></i>
                        <span>{{ $rep->primer_nombre }} {{ $rep->primer_apellido }} ({{ $rep->pivot->tipo_responsabilidad }})</span>
                    </div>
                @endforeach
            </div>
            @endif
        </div>

    </div>

    <!-- Sidebar Lateral -->
    <div class="lg:col-span-1 space-y-6">
        
        <!-- Acciones -->
        @if(in_array($cita->estado_cita, ['Programada', 'Confirmada', 'Pendiente']))
        <div class="card p-6 border-t-4 border-t-emerald-500 sticky top-6 space-y-4">
            <h4 class="font-bold text-gray-900 mb-4">Gestión de Cita</h4>
            
            @php
                $pagosActivos = $cita->facturaPaciente ? $cita->facturaPaciente->pagos()->where('status', true)->get() : collect();
                $tienePago = $pagosActivos->count() > 0;
                $pagoConfirmado = $pagosActivos->where('estado', 'Confirmado')->isNotEmpty();
                $pagoPendiente = $pagosActivos->where('estado', 'Pendiente')->isNotEmpty();
                $pagoRechazado = $pagosActivos->where('estado', 'Rechazado')->isNotEmpty() && !$pagoConfirmado && !$pagoPendiente;
                $ultimoRechazo = $pagoRechazado ? $pagosActivos->where('estado', 'Rechazado')->sortByDesc('created_at')->first() : null;
            @endphp

            <!-- Botón Registrar Pago -->
            @if($cita->estado_cita == 'Programada' && (!$tienePago || $pagoRechazado))
                @if($pagoRechazado)
                <div class="mb-6 overflow-hidden rounded-2xl border border-red-100 bg-red-50/30 backdrop-blur-sm">
                    <div class="flex items-stretch">
                        <div class="w-1.5 bg-red-500"></div>
                        <div class="p-5 flex-1">
                            <div class="flex items-center gap-3 mb-3">
                                <div class="w-8 h-8 rounded-full bg-red-100 flex items-center justify-center text-red-600">
                                    <i class="bi bi-x-circle-fill"></i>
                                </div>
                                <h5 class="font-bold text-red-900">Pago Rechazado por Administración</h5>
                            </div>
                            
                            @if($ultimoRechazo && $ultimoRechazo->comentarios)
                                <div class="bg-white/60 rounded-xl p-4 border border-red-100 mb-3 shadow-sm">
                                    <p class="text-[10px] font-bold text-red-400 uppercase tracking-widest mb-1">Motivo del rechazo</p>
                                    <p class="text-gray-800 italic leading-relaxed">"{{ $ultimoRechazo->comentarios }}"</p>
                                </div>
                            @endif
                            
                            <p class="text-sm text-red-700">Para confirmar su cita, por favor proceda a registrar un nuevo comprobante de pago con la información solicitada.</p>
                        </div>
                    </div>
                </div>
                @endif
                <a href="{{ route('paciente.pagos.registrar', $cita->id) }}" class="btn w-full bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 text-white border-0 shadow-lg shadow-emerald-200 transition-all group">
                    <i class="bi bi-credit-card mr-2 group-hover:scale-110 transition-transform"></i> 
                    {{ $pagoRechazado ? 'Volver a Registrar Pago' : 'Registrar Pago' }}
                </a>
                <p class="text-xs text-gray-500 text-center">
                    Registre su pago para confirmar la cita
                </p>
            @elseif($pagoPendiente)
                <div class="alert alert-warning">
                    <i class="bi bi-clock-history"></i>
                    <div class="text-sm">
                        <p class="font-bold">Pago en Revisión</p>
                        <p>Su pago está siendo verificado por nuestro equipo</p>
                    </div>
                </div>
            @elseif($pagoConfirmado || $cita->estado_cita == 'Confirmada')
                <div class="alert alert-success">
                    <i class="bi bi-check-circle"></i>
                    <div class="text-sm">
                        <p class="font-bold">¡Pago Confirmado!</p>
                        <p>Su cita está confirmada</p>
                    </div>
                </div>
            @endif

            <!-- Botón Cancelación -->
            <p class="text-sm text-gray-600">
                Si no puedes asistir a tu cita, por favor solicita una cancelación o reprogramación con anticipación.
            </p>
            <button onclick="document.getElementById('modal-cancelacion').showModal()" class="btn w-full bg-white border border-red-200 text-red-600 hover:bg-red-50 hover:border-red-300 shadow-sm transition-all group">
                <i class="bi bi-x-circle mr-2 group-hover:scale-110 transition-transform"></i> Solicitar Cancelación
            </button>
        </div>
        @endif

        <!-- Detalles Adicionales -->
        <div class="card p-6 bg-gray-50 border-gray-200">
            <h4 class="font-bold text-gray-900 mb-4">Info Adicional</h4>
            <div class="space-y-3 text-sm">
                <div>
                    <p class="text-gray-500 text-xs uppercase mb-1 font-bold">Motivo Consulta</p>
                    <p class="font-medium text-gray-800">{{ $cita->motivo ?? 'Consulta General' }}</p>
                </div>
                @if($cita->observaciones)
                <div class="pt-2 border-t border-gray-200">
                    <p class="text-gray-500 text-xs uppercase mb-1 font-bold">Observaciones/Historial</p>
                    <div class="text-gray-600 italic text-xs max-h-32 overflow-y-auto bg-white p-2 rounded border border-gray-200">
                        {!! nl2br(e($cita->observaciones)) !!}
                    </div>
                </div>
                @endif
                <div class="pt-2 border-t border-gray-200">
                    <p class="text-gray-500 text-xs uppercase mb-1 font-bold">Código Cita</p>
                    <p class="font-mono bg-gray-200 px-2 py-1 rounded inline-block text-xs text-gray-700 font-bold">#{{ str_pad($cita->id, 6, '0', STR_PAD_LEFT) }}</p>
                </div>

                <div class="pt-2 border-t border-gray-200">
                    <p class="text-gray-500 text-xs uppercase mb-1 font-bold">Costo Estimado</p>
                    <div class="flex items-center gap-2">
                        <span class="text-2xl font-bold text-emerald-600">${{ number_format($cita->tarifa + $cita->tarifa_extra, 2) }}</span>
                    </div>
                </div>

            </div>
        </div>

    </div>
</div>

<!-- Modal Solicitud Cancelación (Rediseñado) -->
<dialog id="modal-cancelacion" class="modal backdrop-blur-sm">
    <div class="modal-box p-0 overflow-hidden bg-white shadow-2xl rounded-2xl w-11/12 max-w-md">
        <!-- Header -->
        <div class="bg-gradient-to-r from-red-50 to-white px-6 py-4 border-b border-red-100 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center flex-shrink-0">
                    <i class="bi bi-exclamation-triangle text-red-600 text-lg"></i>
                </div>
                <div>
                    <h3 class="font-bold text-lg text-gray-900">Solicitar Cancelación</h3>
                    <p class="text-xs text-red-500 font-medium">Requerirá aprobación del administrador</p>
                </div>
            </div>
            <button class="btn btn-sm btn-circle btn-ghost text-gray-400 hover:bg-gray-100 rounded-full transition-colors" onclick="document.getElementById('modal-cancelacion').close()">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>

        <div class="p-6">
            <form id="form-cancelacion" onsubmit="enviarSolicitud(event)">
                @csrf
                
                <p class="text-gray-600 text-sm mb-6">
                    Lamentamos que no puedas asistir. Por favor indícanos el motivo para procesar tu solicitud.
                </p>

                <div class="form-control mb-4">
                     <label class="label px-0 pt-0">
                        <span class="label-text font-medium text-gray-700">Motivo Principal</span>
                    </label>
                    <select id="motivo_cancelacion" name="motivo_cancelacion" class="select select-bordered w-full bg-gray-50 focus:bg-white transition-colors">
                        <option value="">Seleccione un motivo...</option>
                        <option value="Salud">Problemas de Salud</option>
                        <option value="Trabajo">Motivos Laborales</option>
                        <option value="Personal">Asuntos Personales</option>
                        <option value="Transporte">Problemas de Transporte</option>
                        <option value="Economico">Motivos Económicos</option>
                        <option value="Otro">Otro</option>
                    </select>
                </div>
                
                <div class="form-control mb-6">
                    <label class="label px-0 pt-0">
                        <span class="label-text font-medium text-gray-700">Explícanos un poco más</span>
                    </label>
                    <textarea 
                        id="explicacion" 
                        name="explicacion" 
                        class="textarea textarea-bordered h-24 w-full focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all resize-none text-gray-900 placeholder:text-gray-400 bg-gray-50" 
                        placeholder="Detalles adicionales..."
                        oninput="document.getElementById('form_error').classList.add('hidden')"
                    ></textarea>
                    <p id="form_error" class="hidden text-xs font-bold text-red-500 mt-1 flex items-center gap-1">
                        <i class="bi bi-exclamation-circle"></i> Complete todos los campos
                    </p>
                </div>
                
                <div class="flex items-center justify-end gap-3">
                    <button type="button" class="btn btn-outline border-gray-300 text-gray-700 hover:bg-gray-50 px-6" onclick="document.getElementById('modal-cancelacion').close()">
                        Volver
                    </button>
                    <button type="submit" class="btn bg-red-600 hover:bg-red-700 text-white border-0 px-6 shadow-lg shadow-red-200">
                        Enviar Solicitud <i class="bi bi-send ml-2"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
    <form method="dialog" class="modal-backdrop bg-gray-900/50">
        <button>close</button>
    </form>
</dialog>

<!-- Custom Success Modal -->
<div id="modalSuccess" class="fixed inset-0 z-[60] hidden opacity-0 transition-opacity duration-300 flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm"></div>
    <div class="modal-content relative bg-white w-full max-w-sm rounded-2xl shadow-2xl overflow-hidden transform scale-95 opacity-0 transition-all duration-300 border border-gray-100">
        <div class="h-2 bg-gradient-to-r from-emerald-500 to-teal-600"></div>
        <div class="p-8 text-center">
            <div class="w-20 h-20 bg-emerald-50 rounded-full flex items-center justify-center mb-6 mx-auto ring-8 ring-emerald-50/50">
                <i class="bi bi-check-circle-fill text-emerald-500 text-4xl animate-bounce"></i>
            </div>
            <h3 class="text-2xl font-display font-bold text-gray-900 mb-2">¡Solicitud Enviada!</h3>
            <p id="success_message" class="text-gray-500 mb-8 font-medium">Su solicitud ha sido procesada correctamente.</p>
            <button onclick="location.reload()" 
                class="w-full px-6 py-3.5 rounded-xl font-bold text-white bg-emerald-600 hover:bg-emerald-700 shadow-lg shadow-emerald-200 transition-all active:scale-95">
                Entendido
            </button>
        </div>
    </div>
</div>

<script>
    function openSuccessModal(message) {
        document.getElementById('success_message').innerText = message;
        const modal = document.getElementById('modalSuccess');
        const modalContent = modal.querySelector('.modal-content');
        modal.classList.remove('hidden');
        setTimeout(() => {
            modal.classList.remove('opacity-0');
            modalContent.classList.remove('scale-95', 'opacity-0');
        }, 10);
    }

    async function enviarSolicitud(e) {
        e.preventDefault();
        
        const motivo = document.getElementById('motivo_cancelacion').value;
        const explicacion = document.getElementById('explicacion').value;

        if (!motivo || !explicacion) {
            document.getElementById('form_error').classList.remove('hidden');
            return;
        }
        
        // Mostrar loading
        const btnSubmit = e.target.querySelector('button[type="submit"]');
        const originalText = btnSubmit.innerHTML;
        btnSubmit.disabled = true;
        btnSubmit.innerHTML = '<i class="bi bi-hourglass-split animate-spin mr-2"></i> Enviando...';

        try {
            const formData = new FormData();
            formData.append('motivo_cancelacion', motivo);
            formData.append('explicacion', explicacion);
            formData.append('_token', '{{ csrf_token() }}');

            const response = await fetch("{{ route('citas.solicitar-cancelacion', $cita->id) }}", {
                method: 'POST',
                body: formData,
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });

            const data = await response.json();

            if (data.success) {
                document.getElementById('modal-cancelacion').close();
                openSuccessModal(data.message);
            } else {
                alert(data.message || 'Error al procesar la solicitud');
                btnSubmit.disabled = false;
                btnSubmit.innerHTML = originalText;
            }

        } catch (error) {
            console.error(error);
            alert('Error de conexión. Intente nuevamente.');
            btnSubmit.disabled = false;
            btnSubmit.innerHTML = originalText;
        }
    }
</script>
@endsection
