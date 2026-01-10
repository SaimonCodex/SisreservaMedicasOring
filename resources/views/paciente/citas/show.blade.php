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
            <span class="badge {{ match(strtolower($cita->status)) {
                'confirmada' => 'badge-success',
                'pendiente' => 'badge-warning',
                'completada' => 'badge-primary',
                'cancelada' => 'badge-danger',
                default => 'badge-gray'
            } }} text-lg px-4 py-2">
                {{ ucfirst($cita->status) }}
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
                    <div class="w-12 h-12 rounded-full bg-white/20 flex items-center justify-center">
                        <i class="bi bi-calendar-event text-2xl"></i>
                    </div>
                </div>
            </div>
            <div class="p-6 grid grid-cols-2 gap-4">
                <div class="bg-gray-50 p-4 rounded-xl flex items-center gap-4">
                    <div class="w-12 h-12 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 text-xl font-bold">
                        {{ \Carbon\Carbon::parse($cita->fecha_cita)->format('d') }}
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 uppercase font-bold">{{ \Carbon\Carbon::parse($cita->fecha_cita)->format('F Y') }}</p>
                        <p class="font-bold text-gray-900 text-lg">{{ \Carbon\Carbon::parse($cita->fecha_cita)->locale('es')->dayName }}</p>
                    </div>
                </div>
                <div class="bg-gray-50 p-4 rounded-xl flex items-center gap-4">
                    <div class="w-12 h-12 rounded-full bg-amber-100 flex items-center justify-center text-amber-600 text-xl">
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
                <div class="w-16 h-16 rounded-full bg-gradient-to-br from-blue-100 to-indigo-100 flex items-center justify-center text-2xl font-bold text-blue-700 border-2 border-white shadow-sm flex-shrink-0">
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
                <div class="w-12 h-12 rounded-full bg-purple-100 flex items-center justify-center text-purple-700 font-bold text-lg">
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
        @if(in_array($cita->status, ['pendiente', 'confirmada']))
        <div class="card p-6 border-t-4 border-t-red-500 sticky top-6">
            <h4 class="font-bold text-gray-900 mb-4">Gestión de Cita</h4>
            <p class="text-sm text-gray-600 mb-4">
                Si no puedes asistir a tu cita, por favor solicita una cancelación o reprogramación con anticipación.
            </p>
            <button onclick="abrirModalCancelacion()" class="btn w-full bg-white border border-red-200 text-red-600 hover:bg-red-50 hover:border-red-300 shadow-sm">
                <i class="bi bi-x-circle mr-2"></i> Solicitar Cancelación
            </button>
        </div>
        @endif

        <!-- Detalles Adicionales -->
        <div class="card p-6 bg-gray-50 border-gray-200">
            <h4 class="font-bold text-gray-900 mb-4">Info Adicional</h4>
            <div class="space-y-3 text-sm">
                <div>
                    <p class="text-gray-500 text-xs uppercase mb-1">Motivo Consulta</p>
                    <p class="font-medium text-gray-800">{{ $cita->motivo ?? 'Consulta General' }}</p>
                </div>
                @if($cita->observaciones)
                <div class="pt-2 border-t border-gray-200">
                    <p class="text-gray-500 text-xs uppercase mb-1">Observaciones</p>
                    <p class="text-gray-600 italic">"{{ Str::limit($cita->observaciones, 100) }}"</p>
                </div>
                @endif
                <div class="pt-2 border-t border-gray-200">
                    <p class="text-gray-500 text-xs uppercase mb-1">Código Cita</p>
                    <p class="font-mono bg-gray-200 px-2 py-1 rounded inline-block text-xs text-gray-700">#{{ str_pad($cita->id, 6, '0', STR_PAD_LEFT) }}</p>
                </div>

                <div class="pt-2 border-t border-gray-200">
                    <p class="text-gray-500 text-xs uppercase mb-1">Costo Total</p>
                    <div class="flex items-center gap-2">
                        <span class="text-2xl font-bold text-emerald-600">${{ number_format($cita->tarifa_total, 2) }}</span>
                        @if($cita->tarifa_extra > 0)
                            <span class="text-xs text-gray-500" title="Tarifa Base: ${{ number_format($cita->tarifa, 2) }} + Extra: ${{ number_format($cita->tarifa_extra, 2) }}">
                                (Incluye extras)
                            </span>
                        @endif
                    </div>
                </div>

            </div>
        </div>

    </div>
</div>

<!-- Modal Solicitud Cancelación -->
<div id="modal-cancelacion" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <!-- Background overlay -->
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="cerrarModalCancelacion()"></div>

        <!-- Center modal -->
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                        <i class="bi bi-exclamation-triangle text-red-600 text-lg"></i>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                            Solicitar Cancelación de Cita
                        </h3>
                        <div class="mt-2 text-sm text-gray-500">
                            <p class="mb-4">Por favor indícanos el motivo por el cual no podrás asistir. Tu solicitud será enviada al administrador.</p>
                            
                            <form id="form-cancelacion">
                                @csrf
                                <div class="mb-4">
                                    <label for="motivo_cancelacion" class="block text-sm font-medium text-gray-700 mb-1">Motivo Principal</label>
                                    <select id="motivo_cancelacion" name="motivo_cancelacion" class="form-select w-full rounded-md border-gray-300 shadow-sm focus:border-red-300 focus:ring focus:ring-red-200 focus:ring-opacity-50">
                                        <option value="">Seleccione un motivo...</option>
                                        <option value="Salud">Problemas de Salud</option>
                                        <option value="Trabajo">Motivos Laborales</option>
                                        <option value="Personal">Asuntos Personales</option>
                                        <option value="Transporte">Problemas de Transporte</option>
                                        <option value="Economico">Motivos Económicos</option>
                                        <option value="Otro">Otro</option>
                                    </select>
                                </div>
                                
                                <div class="mb-2">
                                    <label for="explicacion" class="block text-sm font-medium text-gray-700 mb-1">Explicación Detallada</label>
                                    <textarea id="explicacion" name="explicacion" rows="3" class="form-textarea w-full rounded-md border-gray-300 shadow-sm focus:border-red-300 focus:ring focus:ring-red-200 focus:ring-opacity-50" placeholder="Describe brevemente la razón..."></textarea>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button type="button" onclick="enviarSolicitud()" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                    Enviar Solicitud
                </button>
                <button type="button" onclick="cerrarModalCancelacion()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                    Cancelar
                </button>
            </div>
        </div>
    </div>
</div>

    </div>
</div>

<!-- SweetAlert2 CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // Asegurar que el modal esté oculto al cargar
    document.addEventListener('DOMContentLoaded', () => {
        document.getElementById('modal-cancelacion').classList.add('hidden');
    });

    function abrirModalCancelacion() {
        document.getElementById('modal-cancelacion').classList.remove('hidden');
    }

    function cerrarModalCancelacion() {
        document.getElementById('modal-cancelacion').classList.add('hidden');
    }

    async function enviarSolicitud() {
        const motivo = document.getElementById('motivo_cancelacion').value;
        const explicacion = document.getElementById('explicacion').value;

        if (!motivo || !explicacion) {
            Swal.fire('Error', 'Por favor complete todos los campos', 'error');
            return;
        }

        try {
            const formData = new FormData();
            formData.append('motivo_cancelacion', motivo);
            formData.append('explicacion', explicacion);
            formData.append('_token', '{{ csrf_token() }}');

            const response = await fetch("{{ route('citas.solicitar-cancelacion', $cita->id) }}", {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            const data = await response.json();

            if (data.success) {
                cerrarModalCancelacion();
                Swal.fire({
                    title: 'Solicitud Enviada',
                    text: data.message,
                    icon: 'success',
                    confirmButtonText: 'Entendido'
                }).then(() => {
                    location.reload();
                });
            } else {
                Swal.fire('Error', data.message || 'Ocurrió un error', 'error');
            }

        } catch (error) {
            console.error(error);
            Swal.fire('Error', 'Error de conexión', 'error');
        }
    }
</script>
@endsection
