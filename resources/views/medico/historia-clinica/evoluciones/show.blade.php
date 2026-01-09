@extends('layouts.medico')

@section('title', 'Detalle de Evolución Clínica')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-4">
            <a href="{{ url('index.php/historia-clinica/evoluciones') }}" class="btn btn-outline">
                <i class="bi bi-arrow-left"></i>
            </a>
            <div>
                <h1 class="text-2xl font-display font-bold text-gray-900">Detalle de Evolución Clínica</h1>
                <p class="text-gray-600 mt-1">
                    {{ isset($evolucion->created_at) ? \Carbon\Carbon::parse($evolucion->created_at)->format('d \d\e F, Y - H:i A') : 'N/A' }}
                </p>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ url('index.php/historia-clinica/evoluciones/' . ($evolucion->id ?? 1) . '/edit') }}" class="btn btn-primary">
                <i class="bi bi-pencil"></i>
                <span>Editar</span>
            </a>
            <button onclick="window.print()" class="btn btn-outline">
                <i class="bi bi-printer"></i>
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Patient Info -->
            <div class="card">
                <div class="p-6 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-white">
                    <h3 class="text-lg font-display font-bold text-gray-900 flex items-center gap-2">
                        <i class="bi bi-person-circle text-blue-600"></i>
                        Información del Paciente
                    </h3>
                </div>
                <div class="p-6">
                    <div class="flex items-center gap-4">
                        <div class="w-16 h-16 rounded-xl bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center text-white text-2xl font-bold">
                            {{ strtoupper(substr($evolucion->historiaClinica->paciente->primer_nombre ?? 'P', 0, 1)) }}{{ strtoupper(substr($evolucion->historiaClinica->paciente->primer_apellido ?? 'A', 0, 1)) }}
                        </div>
                        <div class="flex-1">
                            <h4 class="text-xl font-bold text-gray-900">
                                {{ $evolucion->historiaClinica->paciente->primer_nombre ?? 'N/A' }} 
                                {{ $evolucion->historiaClinica->paciente->segundo_nombre ?? '' }}
                                {{ $evolucion->historiaClinica->paciente->primer_apellido ?? '' }}
                                {{ $evolucion->historiaClinica->paciente->segundo_apellido ?? '' }}
                            </h4>
                            <div class="grid grid-cols-3 gap-4 mt-2 text-sm">
                                <div>
                                    <p class="text-gray-500">Cédula</p>
                                    <p class="font-semibold text-gray-900">{{ $evolucion->historiaClinica->paciente->cedula ?? 'N/A' }}</p>
                                </div>
                                <div>
                                    <p class="text-gray-500">Edad</p>
                                    <p class="font-semibold text-gray-900">
                                        {{ isset($evolucion->historiaClinica->paciente->fecha_nacimiento) ? \Carbon\Carbon::parse($evolucion->historiaClinica->paciente->fecha_nacimiento)->age . ' años' : 'N/A' }}
                                    </p>
                                </div>
                                <div>
                                    <p class="text-gray-500">Teléfono</p>
                                    <p class="font-semibold text-gray-900">{{ $evolucion->historiaClinica->paciente->telefono ?? 'N/A' }}</p>
                                </div>
                            </div>
                        </div>
                        <a href="{{ route('pacientes.show', $evolucion->historiaClinica->paciente->id ?? 1) }}" class="btn btn-sm btn-outline">
                            <i class="bi bi-eye"></i> Ver Perfil
                        </a>
                    </div>
                </div>
            </div>

            <!-- Vital Signs -->
            <div class="card">
                <div class="p-6 border-b border-gray-200 bg-gradient-to-r from-rose-50 to-white">
                    <h3 class="text-lg font-display font-bold text-gray-900 flex items-center gap-2">
                        <i class="bi bi-heart-pulse text-rose-600"></i>
                        Signos Vitales
                    </h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        @if($evolucion->presion_arterial ?? null)
                        <div class="p-4 bg-rose-50 rounded-xl border border-rose-100">
                            <p class="text-xs text-gray-600 mb-1">Presión Arterial</p>
                            <p class="text-xl font-bold text-gray-900">{{ $evolucion->presion_arterial }}</p>
                            <p class="text-xs text-gray-500">mmHg</p>
                        </div>
                        @endif

                        @if($evolucion->temperatura ?? null)
                        <div class="p-4 bg-amber-50 rounded-xl border border-amber-100">
                            <p class="text-xs text-gray-600 mb-1">Temperatura</p>
                            <p class="text-xl font-bold text-gray-900">{{ $evolucion->temperatura }}°C</p>
                            <p class="text-xs text-gray-500">Celsius</p>
                        </div>
                        @endif

                        @if($evolucion->frecuencia_cardiaca ?? null)
                        <div class="p-4 bg-red-50 rounded-xl border border-red-100">
                            <p class="text-xs text-gray-600 mb-1">Frecuencia Cardíaca</p>
                            <p class="text-xl font-bold text-gray-900">{{ $evolucion->frecuencia_cardiaca }}</p>
                            <p class="text-xs text-gray-500">bpm</p>
                        </div>
                        @endif

                        @if($evolucion->frecuencia_respiratoria ?? null)
                        <div class="p-4 bg-sky-50 rounded-xl border border-sky-100">
                            <p class="text-xs text-gray-600 mb-1">Frecuencia Respiratoria</p>
                            <p class="text-xl font-bold text-gray-900">{{ $evolucion->frecuencia_respiratoria }}</p>
                            <p class="text-xs text-gray-500">rpm</p>
                        </div>
                        @endif

                        @if($evolucion->saturacion_oxigeno ?? null)
                        <div class="p-4 bg-cyan-50 rounded-xl border border-cyan-100">
                            <p class="text-xs text-gray-600 mb-1">Saturación O₂</p>
                            <p class="text-xl font-bold text-gray-900">{{ $evolucion->saturacion_oxigeno }}%</p>
                            <p class="text-xs text-gray-500">Oxígeno</p>
                        </div>
                        @endif

                        @if($evolucion->peso ?? null)
                        <div class="p-4 bg-purple-50 rounded-xl border border-purple-100">
                            <p class="text-xs text-gray-600 mb-1">Peso</p>
                            <p class="text-xl font-bold text-gray-900">{{ $evolucion->peso }}</p>
                            <p class="text-xs text-gray-500">kg</p>
                        </div>
                        @endif

                        @if($evolucion->talla ?? null)
                        <div class="p-4 bg-indigo-50 rounded-xl border border-indigo-100">
                            <p class="text-xs text-gray-600 mb-1">Talla</p>
                            <p class="text-xl font-bold text-gray-900">{{ $evolucion->talla }}</p>
                            <p class="text-xs text-gray-500">m</p>
                        </div>
                        @endif

                        @if($evolucion->imc ?? null)
                        <div class="p-4 bg-emerald-50 rounded-xl border border-emerald-100">
                            <p class="text-xs text-gray-600 mb-1">IMC</p>
                            <p class="text-xl font-bold text-gray-900">{{ $evolucion->imc }}</p>
                            <p class="text-xs text-gray-500">kg/m²</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Clinical Evaluation -->
            <div class="card">
                <div class="p-6 border-b border-gray-200 bg-gradient-to-r from-emerald-50 to-white">
                    <h3 class="text-lg font-display font-bold text-gray-900 flex items-center gap-2">
                        <i class="bi bi-clipboard2-pulse text-emerald-600"></i>
                        Evaluación Clínica
                    </h3>
                </div>
                <div class="p-6 space-y-6">
                    <div>
                        <label class="text-sm font-semibold text-gray-700 block mb-2">Motivo de Consulta</label>
                        <div class="p-4 bg-gray-50 rounded-xl">
                            <p class="text-gray-900">{{ $evolucion->motivo_consulta ?? 'No especificado' }}</p>
                        </div>
                    </div>

                    @if($evolucion->enfermedad_actual ?? null)
                    <div>
                        <label class="text-sm font-semibold text-gray-700 block mb-2">Enfermedad Actual</label>
                        <div class="p-4 bg-gray-50 rounded-xl">
                            <p class="text-gray-900">{{ $evolucion->enfermedad_actual }}</p>
                        </div>
                    </div>
                    @endif

                    @if($evolucion->examen_fisico ?? null)
                    <div>
                        <label class="text-sm font-semibold text-gray-700 block mb-2">Examen Físico</label>
                        <div class="p-4 bg-gray-50 rounded-xl">
                            <p class="text-gray-900">{{ $evolucion->examen_fisico }}</p>
                        </div>
                    </div>
                    @endif

                    <div>
                        <label class="text-sm font-semibold text-gray-700 block mb-2">Diagnóstico</label>
                        <div class="p-4 bg-blue-50 rounded-xl border border-blue-200">
                            <p class="text-gray-900 font-semibold">{{ $evolucion->diagnostico ?? 'No especificado' }}</p>
                        </div>
                    </div>

                    <div>
                        <label class="text-sm font-semibold text-gray-700 block mb-2">Tratamiento</label>
                        <div class="p-4 bg-emerald-50 rounded-xl border border-emerald-200">
                            <p class="text-gray-900">{{ $evolucion->tratamiento ?? 'No especificado' }}</p>
                        </div>
                    </div>

                    @if($evolucion->observaciones ?? null)
                    <div>
                        <label class="text-sm font-semibold text-gray-700 block mb-2">Observaciones</label>
                        <div class="p-4 bg-amber-50 rounded-xl border border-amber-200">
                            <p class="text-gray-900">{{ $evolucion->observaciones }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Appointment Info -->
            @if($evolucion->cita_id ?? null)
            <div class="card p-6">
                <h3 class="text-lg font-display font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <i class="bi bi-calendar-check text-blue-600"></i>
                    Cita Asociada
                </h3>
                <div class="p-4 bg-blue-50 rounded-xl border border-blue-200">
                    <p class="text-sm text-gray-700 mb-2">Esta evolución está asociada a una cita programada</p>
                    <a href="{{ route('citas.show', $evolucion->cita_id) }}" class="btn btn-sm btn-primary w-full mt-2">
                        <i class="bi bi-eye"></i> Ver Cita
                    </a>
                </div>
            </div>
            @endif

            <!-- Doctor Info -->
            <div class="card p-6">
                <h3 class="text-lg font-display font-bold text-gray-900 mb-4">Médico Tratante</h3>
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-full bg-gradient-to-br from-emerald-500 to-emerald-600 flex items-center justify-center text-white font-bold">
                        {{ strtoupper(substr(auth()->user()->nombre ?? 'D', 0, 1)) }}
                    </div>
                    <div>
                        <p class="font-semibold text-gray-900">{{ auth()->user()->nombre ?? 'Dr. Nombre' }}</p>
                        <p class="text-sm text-gray-500">Médico Especialista</p>
                    </div>
                </div>
            </div>

            <!-- Metadata -->
            <div class="card p-6">
                <h3 class="text-lg font-display font-bold text-gray-900 mb-4">Información del Registro</h3>
                <div class="space-y-3 text-sm">
                    <div>
                        <p class="text-gray-500">Fecha de Registro</p>
                        <p class="font-semibold text-gray-900">
                            {{ isset($evolucion->created_at) ? \Carbon\Carbon::parse($evolucion->created_at)->format('d/m/Y H:i A') : 'N/A' }}
                        </p>
                    </div>
                    <div>
                        <p class="text-gray-500">Última Actualización</p>
                        <p class="font-semibold text-gray-900">
                            {{ isset($evolucion->updated_at) ? \Carbon\Carbon::parse($evolucion->updated_at)->format('d/m/Y H:i A') : 'N/A' }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card p-6">
                <h3 class="text-lg font-display font-bold text-gray-900 mb-4">Acciones Rápidas</h3>
                <div class="space-y-2">
                    <a href="{{ url('index.php/ordenes-medicas/create?paciente=' . ($evolucion->historiaClinica->paciente->id ?? 1)) }}" class="btn btn-outline w-full justify-start">
                        <i class="bi bi-clipboard-plus"></i>
                        Nueva Orden Médica
                    </a>
                    <a href="{{ url('index.php/historia-clinica?paciente=' . ($evolucion->historiaClinica->paciente->id ?? 1)) }}" class="btn btn-outline w-full justify-start">
                        <i class="bi bi-folder2-open"></i>
                        Ver Expediente Completo
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
