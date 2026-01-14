@extends('layouts.admin')

@section('title', 'Detalle de Cita')

@section('content')
<div class="mb-6">
    <a href="{{ route('citas.index') }}" class="text-medical-600 hover:text-medical-700 inline-flex items-center text-sm font-medium mb-3">
        <i class="bi bi-arrow-left mr-1"></i> Volver a Citas
    </a>
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h2 class="text-3xl font-display font-bold text-gray-900">Detalle de Cita #{{ $cita->id }}</h2>
            <p class="text-gray-500 mt-1">Información completa de la cita médica</p>
        </div>
        <div class="flex gap-3">
            <button onclick="window.print()" class="btn btn-outline">
                <i class="bi bi-printer mr-2"></i>
                Imprimir
            </button>
            @if(in_array($cita->estado_cita, ['Programada', 'Confirmada']))
            <a href="{{ route('citas.edit', $cita->id) }}" class="btn btn-primary">
                <i class="bi bi-pencil mr-2"></i>
                Editar / Reprogramar
            </a>
            @endif
        </div>
    </div>
</div>

@php
    $estadoColor = 'gray';
    $estadoIcon = 'bi-circle';
    
    switch($cita->estado_cita) {
        case 'Programada': 
            $estadoColor = 'warning'; 
            $estadoIcon = 'bi-clock';
            break;
        case 'Confirmada': 
            $estadoColor = 'info'; 
            $estadoIcon = 'bi-check-circle';
            break;
        case 'En Progreso': 
            $estadoColor = 'primary'; 
            $estadoIcon = 'bi-play-circle';
            break;
        case 'Completada': 
            $estadoColor = 'success'; 
            $estadoIcon = 'bi-check-all';
            break;
        case 'Cancelada': 
        case 'No Asistió':
            $estadoColor = 'danger'; 
            $estadoIcon = 'bi-x-circle';
            break;
    }
@endphp

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Columna Principal -->
    <div class="lg:col-span-2 space-y-6">
        
        <!-- Estado de la Cita -->
        <div class="card p-0 overflow-hidden">
            <div class="bg-gradient-to-r from-{{ $estadoColor }}-600 to-{{ $estadoColor }}-500 p-6">
                <div class="flex items-center justify-between">
                    <div class="text-white">
                        <p class="text-white/80 text-sm mb-1">Estado de la Cita</p>
                        <h3 class="text-2xl font-bold uppercase">{{ $cita->estado_cita }}</h3>
                    </div>
                    <div class="w-16 h-16 rounded-full bg-white/20 backdrop-blur-sm flex items-center justify-center border-4 border-white/30">
                        <i class="bi {{ $estadoIcon }} text-white text-3xl"></i>
                    </div>
                </div>
            </div>
            
            <div class="p-6">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-center">
                    <div class="p-3 bg-gray-50 rounded-xl">
                        <i class="bi bi-calendar3 text-2xl text-medical-600 mb-2"></i>
                        <p class="text-xs text-gray-500">Fecha</p>
                        <p class="font-bold text-gray-900">{{ \Carbon\Carbon::parse($cita->fecha_cita)->isoFormat('D MMM YYYY') }}</p>
                    </div>
                    <div class="p-3 bg-gray-50 rounded-xl">
                        <i class="bi bi-clock text-2xl text-warning-600 mb-2"></i>
                        <p class="text-xs text-gray-500">Hora</p>
                        <p class="font-bold text-gray-900">{{ \Carbon\Carbon::parse($cita->hora_inicio)->format('h:i A') }}</p>
                    </div>
                    <div class="p-3 bg-gray-50 rounded-xl">
                        <i class="bi bi-hourglass-split text-2xl text-info-600 mb-2"></i>
                        <p class="text-xs text-gray-500">Duración</p>
                        <p class="font-bold text-gray-900">{{ $cita->duracion_minutos }} min</p>
                    </div>
                    <div class="p-3 bg-gray-50 rounded-xl">
                        <i class="bi bi-geo-alt text-2xl text-success-600 mb-2"></i>
                        <p class="text-xs text-gray-500">Tipo</p>
                        <p class="font-bold text-gray-900">{{ $cita->tipo_consulta }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Información del Paciente -->
        <div class="card p-6 border-l-4 border-l-success-500">
            <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                <i class="bi bi-person-circle text-success-600"></i>
                Información del Paciente
            </h3>
            
            <div class="flex items-start gap-4 mb-4">
                <div class="w-16 h-16 rounded-full bg-gradient-to-br from-success-500 to-success-600 flex items-center justify-center text-white text-2xl font-bold flex-shrink-0">
                    {{ substr($cita->paciente->primer_nombre ?? 'N', 0, 1) }}{{ substr($cita->paciente->primer_apellido ?? 'A', 0, 1) }}
                </div>
                <div class="flex-1">
                    <h4 class="text-xl font-bold text-gray-900">
                        {{ $cita->paciente->primer_nombre }} {{ $cita->paciente->primer_apellido }}
                        @if($cita->pacienteEspecial)
                            <span class="text-sm font-normal text-purple-600 bg-purple-100 px-2 py-1 rounded ml-2">
                                <i class="bi bi-person-heart"></i> {{ $cita->pacienteEspecial->primer_nombre }} (Especial)
                            </span>
                        @endif
                    </h4>
                    <p class="text-gray-600">
                        {{ $cita->paciente->tipo_documento ?? '' }}-{{ $cita->paciente->numero_documento ?? '' }} 
                        @if($cita->historiaClinica)
                        • HC: {{ $cita->historiaClinica->numero_historia }}
                        @endif
                    </p>
                    <div class="flex gap-2 mt-2">
                        <span class="badge badge-primary">{{ \Carbon\Carbon::parse($cita->paciente->fecha_nac)->age }} años</span>
                        <span class="badge badge-info">{{ $cita->paciente->genero }}</span>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-4 border-t border-gray-100">
                <div>
                    <p class="text-sm text-gray-500 mb-1">Teléfono</p>
                    <p class="font-semibold text-gray-900">
                        {{ $cita->paciente->prefijo_tlf }} {{ $cita->paciente->numero_tlf }}
                    </p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 mb-1">Dirección</p>
                    <p class="font-semibold text-gray-900 text-sm">
                        {{ $cita->paciente->direccion_detallada ?? 'No registrada' }}
                    </p>
                </div>
            </div>

            <div class="mt-4 pt-4 border-t border-gray-100">
                <a href="{{ route('pacientes.show', $cita->paciente->id) }}" class="btn btn-sm btn-outline">
                    <i class="bi bi-eye mr-1"></i> Ver Perfil Completo
                </a>
            </div>
        </div>

        <!-- Información del Médico -->
        <div class="card p-6 border-l-4 border-l-medical-500">
            <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                <i class="bi bi-person-badge text-medical-600"></i>
                Médico Asignado
            </h3>
            
            @if($cita->medico)
            <div class="flex items-start gap-4 mb-4">
                <div class="w-16 h-16 rounded-full bg-gradient-to-br from-medical-500 to-medical-600 flex items-center justify-center text-white text-2xl font-bold flex-shrink-0">
                    {{ substr($cita->medico->primer_nombre ?? 'M', 0, 1) }}{{ substr($cita->medico->primer_apellido ?? 'D', 0, 1) }}
                </div>
                <div class="flex-1">
                    <h4 class="text-xl font-bold text-gray-900">Dr. {{ $cita->medico->primer_nombre }} {{ $cita->medico->primer_apellido }}</h4>
                    <p class="text-gray-600">MPPS: {{ $cita->medico->mpps ?? 'N/A' }} • CMG: {{ $cita->medico->cmg ?? 'N/A' }}</p>
                    <div class="flex gap-2 mt-2">
                        <span class="badge badge-primary">{{ $cita->especialidad->nombre ?? 'Sin Especialidad' }}</span>
                    </div>
                </div>
            </div>
            @else
            <div class="flex items-center gap-4 mb-4 p-4 bg-gray-50 rounded-xl border border-gray-200">
                <div class="w-16 h-16 rounded-full bg-gray-200 flex items-center justify-center text-gray-400 text-2xl">
                    <i class="bi bi-person-x"></i>
                </div>
                <div>
                    <h4 class="text-lg font-bold text-gray-900">Médico no asignado</h4>
                    <p class="text-sm text-gray-500">Esta cita no tiene un profesional vinculado actualmente.</p>
                </div>
            </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-4 border-t border-gray-100">
                <div>
                    <p class="text-sm text-gray-500 mb-1">Consultorio</p>
                    <p class="font-semibold text-gray-900">{{ $cita->consultorio->nombre ?? 'N/A' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 mb-1">Especialidad</p>
                    <p class="font-semibold text-gray-900">{{ $cita->especialidad->nombre ?? 'N/A' }}</p>
                </div>
            </div>

            <div class="mt-4 pt-4 border-t border-gray-100">
                @if($cita->medico)
                <a href="{{ route('medicos.show', $cita->medico->id) }}" class="btn btn-sm btn-outline">
                    <i class="bi bi-eye mr-1"></i> Ver Perfil del Médico
                </a>
                @endif
            </div>
        </div>

        <!-- Detalles de la Cita -->
        <div class="card p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                <i class="bi bi-file-medical text-info-600"></i>
                Detalles de la Consulta
            </h3>
            
            <div class="space-y-4">
                <div>
                    <p class="text-sm text-gray-500 mb-1">Motivo de Consulta</p>
                    <p class="font-semibold text-gray-900">{{ $cita->motivo ?? 'No especificado' }}</p>
                </div>

                @if($cita->observaciones)
                <div>
                    <p class="text-sm text-gray-500 mb-2">Observaciones</p>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-gray-700 text-sm whitespace-pre-line">{{ $cita->observaciones }}</p>
                    </div>
                </div>
                @endif
                
                {{-- Evolución Clínica oculta para admins --}}
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="lg:col-span-1 space-y-6">
        <!-- Acciones Rápidas (Solo roles admin/medico o propios) -->
        <div class="card p-6 sticky top-6">
            <h4 class="font-bold text-gray-900 mb-4">Acciones</h4>
            <div class="space-y-2">
                
                @if($cita->estado_cita == 'Programada')
                    <form action="{{ route('citas.cambiar-estado', $cita->id) }}" method="POST">
                        @csrf
                        <input type="hidden" name="estado_cita" value="Confirmada">
                        <button type="submit" class="btn btn-success w-full justify-start text-white">
                            <i class="bi bi-check-circle mr-2"></i>
                            Confirmar Cita
                        </button>
                    </form>
                @endif
                
                {{-- Botón Iniciar Consulta oculto para admins --}}
                
                @if(in_array($cita->estado_cita, ['Programada', 'Confirmada']))
                    <button onclick="document.getElementById('modal-cancelar').showModal()" class="btn btn-outline w-full justify-start text-danger-600 border-danger-300 hover:bg-danger-50">
                        <i class="bi bi-x-circle mr-2"></i>
                        Cancelar Cita
                    </button>
                @endif
                
                @if($cita->estado_cita != 'Completada')
                <button class="btn btn-outline w-full justify-start" onclick="alert('Funcionalidad de recordatorio en desarrollo')">
                    <i class="bi bi-bell mr-2"></i>
                    Enviar Recordatorio
                </button>
                @endif
            </div>
        </div>

        <!-- Información Financiera -->
        <div class="card p-6 bg-gray-50 border-gray-200">
            <h4 class="font-bold text-gray-900 mb-4">Información Financiera</h4>
            <div class="space-y-3 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-600">Tarifa Base:</span>
                    <span class="font-medium text-gray-900">${{ number_format($cita->tarifa, 2) }}</span>
                </div>
                @if($cita->tarifa_extra > 0)
                <div class="flex justify-between">
                    <span class="text-gray-600">Tarifa Extra:</span>
                    <span class="font-medium text-gray-900">${{ number_format($cita->tarifa_extra, 2) }}</span>
                </div>
                @endif
                <div class="flex justify-between pt-2 border-t border-gray-300">
                    <span class="font-bold text-gray-900">Total:</span>
                    <span class="font-bold text-green-700">${{ number_format($cita->tarifa + $cita->tarifa_extra, 2) }}</span>
                </div>
            </div>
        </div>
        
        <!-- Metadata -->
        <div class="card p-6">
             <div class="space-y-2 text-xs text-gray-500">
                <div class="flex justify-between">
                    <span>Creada:</span>
                    <span>{{ $cita->created_at->format('d/m/Y h:i A') }}</span>
                </div>
                <div class="flex justify-between">
                    <span>Actualizada:</span>
                    <span>{{ $cita->updated_at->format('d/m/Y h:i A') }}</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Cancelar -->
<dialog id="modal-cancelar" class="modal backdrop-blur-sm">
    <div class="modal-box p-0 overflow-hidden bg-white shadow-2xl rounded-2xl w-11/12 max-w-md">
        <!-- Header con gradiente de advertencia -->
        <div class="bg-gradient-to-r from-red-50 to-white px-6 py-4 border-b border-red-100 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center flex-shrink-0">
                    <i class="bi bi-exclamation-triangle text-red-600 text-lg"></i>
                </div>
                <div>
                    <h3 class="font-bold text-lg text-gray-900">Cancelar Cita</h3>
                    <p class="text-xs text-red-500 font-medium">Esta acción no se puede deshacer</p>
                </div>
            </div>
            <button class="btn btn-sm btn-circle btn-ghost text-gray-400 hover:bg-gray-100 hover:text-gray-600 transition-colors" onclick="document.getElementById('modal-cancelar').close()">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>
        
        <div class="p-6">
            <form action="{{ route('citas.cambiar-estado', $cita->id) }}" method="POST">
                @csrf
                <input type="hidden" name="estado_cita" value="Cancelada">
                
                <p class="text-gray-600 text-sm mb-6 leading-relaxed">
                    ¿Estás seguro de que deseas cancelar la cita del paciente <span class="font-semibold text-gray-900">{{ $cita->paciente->primer_nombre }} {{ $cita->paciente->primer_apellido }}</span>?
                    Por favor, indica un motivo para el registro.
                </p>
                
                <div class="form-control mb-6">
                    <label class="label px-0 pt-0">
                        <span class="label-text font-medium text-gray-700">Motivo de cancelación</span>
                    </label>
                    <textarea 
                        name="observaciones" 
                        class="textarea textarea-bordered h-32 w-full focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all resize-none text-gray-900 placeholder:text-gray-400 bg-gray-50" 
                        placeholder="Escribe aquí el motivo de la cancelación..."
                    ></textarea>
                </div>
                
                <div class="flex items-center justify-end gap-3">
                    <button type="button" class="btn btn-outline border-gray-300 text-gray-700 hover:bg-gray-50 hover:border-gray-400 hover:text-gray-900 px-6" onclick="document.getElementById('modal-cancelar').close()">
                        Cancelar
                    </button>
                    <button type="submit" class="btn bg-red-600 hover:bg-red-700 text-white border-0 px-6 shadow-lg shadow-red-200">
                        <i class="bi bi-x-circle mr-2"></i> Confirmar Cancelación
                    </button>
                </div>
            </form>
        </div>
    </div>
    <form method="dialog" class="modal-backdrop bg-gray-900/50">
        <button>close</button>
    </form>
</dialog>

@endsection
