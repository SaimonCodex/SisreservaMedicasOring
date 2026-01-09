@extends('layouts.medico')

@section('title', 'Detalle de Cita')

@section('content')
<div class="space-y-6">
    <!-- Header with Actions -->
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-4">
            <a href="{{ route('citas.index') }}" class="btn btn-outline">
                <i class="bi bi-arrow-left"></i>
            </a>
            <div>
                <h1 class="text-2xl font-display font-bold text-gray-900">Detalle de Cita</h1>
                <p class="text-gray-600 mt-1">{{ \Carbon\Carbon::parse($cita->fecha_hora ?? now())->format('d \d\e F \d\e Y - H:i A') }}</p>
            </div>
        </div>
        <div class="flex items-center gap-2">
            @if(($cita->status ?? 'pendiente') == 'confirmada')
            <a href="{{ url('index.php/historia-clinica/evoluciones/create?cita=' . $cita->id) }}" class="btn btn-success">
                <i class="bi bi-file-earmark-medical"></i>
                <span>Registrar Evolución</span>
            </a>
            @endif
            @if(in_array($cita->status ?? 'pendiente', ['pendiente', 'confirmada']))
            <a href="{{ route('citas.edit', $cita->id ?? 1) }}" class="btn btn-primary">
                <i class="bi bi-pencil"></i>
                <span>Editar</span>
            </a>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Info -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Patient Info -->
            <div class="card">
                <div class="p-6 border-b border-gray-200 bg-gradient-to-r from-emerald-50 to-white">
                    <h3 class="text-lg font-display font-bold text-gray-900 flex items-center gap-2">
                        <i class="bi bi-person-circle text-emerald-600"></i>
                        Información del Paciente
                    </h3>
                </div>
                <div class="p-6">
                    <div class="flex items-start gap-6">
                        <div class="w-24 h-24 rounded-2xl bg-gradient-to-br from-emerald-500 to-emerald-600 flex items-center justify-center text-white text-3xl font-bold shadow-lg">
                            {{ strtoupper(substr($cita->paciente->primer_nombre ?? 'P', 0, 1)) }}{{ strtoupper(substr($cita->paciente->primer_apellido ?? 'A', 0, 1)) }}
                        </div>
                        <div class="flex-1">
                            <h4 class="text-2xl font-bold text-gray-900">
                                {{ $cita->paciente->primer_nombre ?? 'Nombre' }} {{ $cita->paciente->segundo_nombre ?? '' }} 
                                {{ $cita->paciente->primer_apellido ?? 'Apellido' }} {{ $cita->paciente->segundo_apellido ?? '' }}
                            </h4>
                            <div class="grid grid-cols-2 gap-4 mt-4">
                                <div>
                                    <p class="text-sm text-gray-500">Cédula</p>
                                    <p class="font-semibold text-gray-900">{{ $cita->paciente->cedula ?? 'N/A' }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Teléfono</p>
                                    <p class="font-semibold text-gray-900">{{ $cita->paciente->telefono ?? 'N/A' }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Correo</p>
                                    <p class="font-semibold text-gray-900">{{ $cita->paciente->correo ?? 'N/A' }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Edad</p>
                                    <p class="font-semibold text-gray-900">
                                        {{ isset($cita->paciente->fecha_nacimiento) ? \Carbon\Carbon::parse($cita->paciente->fecha_nacimiento)->age . ' años' : 'N/A' }}
                                    </p>
                                </div>
                            </div>
                            <div class="mt-4 flex gap-2">
                                <a href="{{ route('pacientes.show', $cita->paciente->id ?? 1) }}" class="btn btn-sm btn-outline">
                                    <i class="bi bi-eye"></i> Ver Perfil Completo
                                </a>
                                <a href="{{ url('index.php/historia-clinica?paciente=' . $cita->paciente->id) }}" class="btn btn-sm btn-outline">
                                    <i class="bi bi-file-medical"></i> Historia Clínica
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Appointment Details -->
            <div class="card">
                <div class="p-6 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-white">
                    <h3 class="text-lg font-display font-bold text-gray-900 flex items-center gap-2">
                        <i class="bi bi-calendar-event text-blue-600"></i>
                        Detalles de la Cita
                    </h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <label class="text-sm font-semibold text-gray-700 block mb-2">Fecha y Hora</label>
                            <div class="flex items-center gap-3 p-4 bg-blue-50 rounded-xl border border-blue-100">
                                <i class="bi bi-calendar-check text-blue-600 text-2xl"></i>
                                <div>
                                    <p class="font-bold text-gray-900">{{ \Carbon\Carbon::parse($cita->fecha_hora ?? now())->format('d \d\e F, Y') }}</p>
                                    <p class="text-sm text-gray-600">{{ \Carbon\Carbon::parse($cita->fecha_hora ?? now())->format('H:i A') }}</p>
                                </div>
                            </div>
                        </div>
                        <div>
                            <label class="text-sm font-semibold text-gray-700 block mb-2">Consultorio</label>
                            <div class="flex items-center gap-3 p-4 bg-emerald-50 rounded-xl border border-emerald-100">
                                <i class="bi bi-building text-emerald-600 text-2xl"></i>
                                <div>
                                    <p class="font-bold text-gray-900">{{ $cita->consultorio->nombre ?? 'N/A' }}</p>
                                    <p class="text-sm text-gray-600">{{ $cita->consultorio->ubicacion ?? 'N/A' }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-span-2">
                            <label class="text-sm font-semibold text-gray-700 block mb-2">Motivo de Consulta</label>
                            <div class="p-4 bg-gray-50 rounded-xl border border-gray-200">
                                <p class="text-gray-900">{{ $cita->motivo ?? 'No especificado' }}</p>
                            </div>
                        </div>
                        @if($cita->observaciones ?? null)
                        <div class="col-span-2">
                            <label class="text-sm font-semibold text-gray-700 block mb-2">Observaciones</label>
                            <div class="p-4 bg-amber-50 rounded-xl border border-amber-200">
                                <p class="text-gray-900">{{ $cita->observaciones }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Evolution if exists -->
            @if($cita->evolucionClinica ?? null)
            <div class="card">
                <div class="p-6 border-b border-gray-200 bg-gradient-to-r from-purple-50 to-white">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-display font-bold text-gray-900 flex items-center gap-2">
                            <i class="bi bi-file-earmark-medical text-purple-600"></i>
                            Evolución Clínica Registrada
                        </h3>
                        <span class="badge badge-success">Consultada</span>
                    </div>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <div>
                            <label class="text-sm font-semibold text-gray-700">Diagnóstico</label>
                            <p class="mt-1 text-gray-900">{{ $cita->evolucionClinica->diagnostico ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-semibold text-gray-700">Tratamiento</label>
                            <p class="mt-1 text-gray-900">{{ $cita->evolucionClinica->tratamiento ?? 'N/A' }}</p>
                        </div>
                        <div class="pt-4 border-t border-gray-200">
                            <a href="{{ url('index.php/historia-clinica/evoluciones/' . $cita->evolucionClinica->id) }}" class="btn btn-sm btn-outline">
                                <i class="bi bi-eye"></i> Ver Evolución Completa
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Status Card -->
            <div class="card">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-display font-bold text-gray-900">Estado de la Cita</h3>
                </div>
                <div class="p-6">
                    <div class="text-center">
                        @if(($cita->status ?? 'pendiente') == 'confirmada')
                        <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-emerald-100 mb-4">
                            <i class="bi bi-check-circle-fill text-4xl text-emerald-600"></i>
                        </div>
                        <p class="font-bold text-emerald-900 text-lg">Confirmada</p>
                        <p class="text-sm text-gray-600 mt-1">La cita está confirmada</p>
                        @elseif(($cita->status ?? 'pendiente') == 'pendiente')
                        <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-amber-100 mb-4">
                            <i class="bi bi-clock-fill text-4xl text-amber-600"></i>
                        </div>
                        <p class="font-bold text-amber-900 text-lg">Pendiente</p>
                        <p class="text-sm text-gray-600 mt-1">Esperando confirmación</p>
                        @elseif(($cita->status ?? 'pendiente') == 'completada')
                        <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-blue-100 mb-4">
                            <i class="bi bi-check-all text-4xl text-blue-600"></i>
                        </div>
                        <p class="font-bold text-blue-900 text-lg">Completada</p>
                        <p class="text-sm text-gray-600 mt-1">Consulta realizada</p>
                        @else
                        <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-rose-100 mb-4">
                            <i class="bi bi-x-circle-fill text-4xl text-rose-600"></i>
                        </div>
                        <p class="font-bold text-rose-900 text-lg">Cancelada</p>
                        <p class="text-sm text-gray-600 mt-1">Esta cita fue cancelada</p>
                        @endif

                        @if(in_array($cita->status ?? 'pendiente', ['pendiente', 'confirmada']))
                        <form action="{{ route('citas.destroy', $cita->id ?? 1) }}" method="POST" class="mt-4" onsubmit="return confirm('¿Está seguro de cancelar esta cita?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger w-full">
                                <i class="bi bi-x-lg"></i>
                                Cancelar Cita
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Timeline -->
            <div class="card">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-display font-bold text-gray-900">Línea de Tiempo</h3>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <div class="flex gap-3">
                            <div class="flex flex-col items-center">
                                <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center">
                                    <i class="bi bi-plus-circle text-blue-600"></i>
                                </div>
                                <div class="w-0.5 h-full bg-blue-200"></div>
                            </div>
                            <div class="flex-1 pb-4">
                                <p class="font-semibold text-gray-900">Cita Creada</p>
                                <p class="text-sm text-gray-500">
                                    {{ isset($cita->created_at) ? \Carbon\Carbon::parse($cita->created_at)->format('d/m/Y H:i A') : 'N/A' }}
                                </p>
                            </div>
                        </div>

                        @if(($cita->status ?? 'pendiente') != 'pendiente')
                        <div class="flex gap-3">
                            <div class="flex flex-col items-center">
                                <div class="w-8 h-8 rounded-full bg-emerald-100 flex items-center justify-center">
                                    <i class="bi bi-check-circle text-emerald-600"></i>
                                </div>
                                @if(($cita->status ?? 'pendiente') != 'confirmada')
                                <div class="w-0.5 h-full bg-emerald-200"></div>
                                @endif
                            </div>
                            <div class="flex-1 pb-4">
                                <p class="font-semibold text-gray-900">Confirmada</p>
                                <p class="text-sm text-gray-500">
                                    {{ isset($cita->updated_at) ? \Carbon\Carbon::parse($cita->updated_at)->format('d/m/Y H:i A') : 'N/A' }}
                                </p>
                            </div>
                        </div>
                        @endif

                        @if(($cita->status ?? 'pendiente') == 'completada')
                        <div class="flex gap-3">
                            <div class="flex flex-col items-center">
                                <div class="w-8 h-8 rounded-full bg-purple-100 flex items-center justify-center">
                                    <i class="bi bi-check-all text-purple-600"></i>
                                </div>
                            </div>
                            <div class="flex-1">
                                <p class="font-semibold text-gray-900">Completada</p>
                                <p class="text-sm text-gray-500">
                                    {{ isset($cita->updated_at) ? \Carbon\Carbon::parse($cita->updated_at)->format('d/m/Y H:i A') : 'N/A' }}
                                </p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card p-6">
                <h3 class="text-lg font-display font-bold text-gray-900 mb-4">Acciones Rápidas</h3>
                <div class="space-y-2">
                    <a href="{{ url('index.php/ordenes-medicas/create?paciente=' . ($cita->paciente->id ?? 1)) }}" class="btn btn-outline w-full justify-start">
                        <i class="bi bi-clipboard-plus"></i>
                        Nueva Orden Médica
                    </a>
                    <a href="{{ url('index.php/historia-clinica?paciente=' . ($cita->paciente->id ?? 1)) }}" class="btn btn-outline w-full justify-start">
                        <i class="bi bi-folder2-open"></i>
                        Ver Expediente
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
