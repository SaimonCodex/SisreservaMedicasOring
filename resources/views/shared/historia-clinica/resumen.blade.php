@extends('layouts.admin')

@section('title', 'Resumen Clínico - ' . ($paciente->nombre_completo ?? 'Paciente'))

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-4">
            <a href="{{ url('index.php/shared/historia-clinica/' . $paciente->id) }}" class="btn btn-outline">
                <i class="bi bi-arrow-left"></i>
            </a>
            <div>
                <h1 class="text-2xl font-display font-bold text-gray-900">Resumen Clínico</h1>
                <p class="text-gray-600 mt-1">{{ $paciente->nombre_completo ?? 'Paciente' }}</p>
            </div>
        </div>
        <button onclick="window.print()" class="btn btn-primary">
            <i class="bi bi-printer"></i> Imprimir
        </button>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Patient Basic Info -->
            <div class="card p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <i class="bi bi-person-vcard text-blue-600"></i> Información del Paciente
                </h3>
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <span class="text-gray-600">Cédula:</span>
                        <p class="font-semibold text-gray-900">{{ $paciente->cedula ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <span class="text-gray-600">Edad:</span>
                        <p class="font-semibold text-gray-900">{{ $paciente->edad ?? 'N/A' }} años</p>
                    </div>
                    <div>
                        <span class="text-gray-600">Género:</span>
                        <p class="font-semibold text-gray-900">{{ ucfirst($paciente->genero ?? 'N/A') }}</p>
                    </div>
                    <div>
                        <span class="text-gray-600">Grupo Sanguíneo:</span>
                        <p class="font-semibold text-gray-900">{{ $paciente->historiaClinicaBase->tipo_sangre ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>

            <!-- Historia Clinica Base -->
            @if($paciente->historiaClinicaBase)
            <div class="card p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <i class="bi bi-clipboard-pulse text-rose-600"></i> Historia Clínica Base
                </h3>
                <div class="space-y-4">
                    @if($paciente->historiaClinicaBase->alergias)
                    <div>
                        <span class="text-sm font-semibold text-gray-700">Alergias:</span>
                        <p class="text-sm text-gray-600 mt-1">{{ $paciente->historiaClinicaBase->alergias }}</p>
                    </div>
                    @endif

                    @if($paciente->historiaClinicaBase->alergias_medicamentos)
                    <div>
                        <span class="text-sm font-semibold text-gray-700">Alergias a Medicamentos:</span>
                        <p class="text-sm text-gray-600 mt-1">{{ $paciente->historiaClinicaBase->alergias_medicamentos }}</p>
                    </div>
                    @endif

                    @if($paciente->historiaClinicaBase->enfermedades_cronicas)
                    <div>
                        <span class="text-sm font-semibold text-gray-700">Enfermedades Crónicas:</span>
                        <p class="text-sm text-gray-600 mt-1">{{ $paciente->historiaClinicaBase->enfermedades_cronicas }}</p>
                    </div>
                    @endif

                    @if($paciente->historiaClinicaBase->medicamentos_actuales)
                    <div>
                        <span class="text-sm font-semibold text-gray-700">Medicamentos Actuales:</span>
                        <p class="text-sm text-gray-600 mt-1">{{ $paciente->historiaClinicaBase->medicamentos_actuales }}</p>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- Last Evolution -->
            @if($ultimaEvolucion)
            <div class="card p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <i class="bi bi-file-text text-emerald-600"></i> Última Evolución Clínica
                </h3>
                <div class="space-y-3">
                    <div>
                        <span class="text-sm text-gray-600">Fecha:</span>
                        <p class="text-sm font-semibold text-gray-900">{{ $ultimaEvolucion->created_at ? \Carbon\Carbon::parse($ultimaEvolucion->created_at)->format('d/m/Y H:i') : 'N/A' }}</p>
                    </div>
                    <div>
                        <span class="text-sm text-gray-600">Médico:</span>
                        <p class="text-sm font-semibold text-gray-900">Dr. {{ $ultimaEvolucion->medico->nombre_completo ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <span class="text-sm text-gray-600">Especialidad:</span>
                        <p class="text-sm font-semibold text-gray-900">{{ $ultimaEvolucion->cita->especialidad->nombre ?? 'N/A' }}</p>
                    </div>
                    
                    @if($ultimaEvolucion->motivo_consulta)
                    <div>
                        <span class="text-sm font-semibold text-gray-700">Motivo de Consulta:</span>
                        <p class="text-sm text-gray-600 mt-1">{{ $ultimaEvolucion->motivo_consulta }}</p>
                    </div>
                    @endif

                    @if($ultimaEvolucion->diagnostico)
                    <div>
                        <span class="text-sm font-semibold text-gray-700">Diagnóstico:</span>
                        <p class="text-sm text-gray-600 mt-1">{{ $ultimaEvolucion->diagnostico }}</p>
                    </div>
                    @endif

                    @if($ultimaEvolucion->tratamiento)
                    <div>
                        <span class="text-sm font-semibold text-gray-700">Tratamiento:</span>
                        <p class="text-sm text-gray-600 mt-1">{{ $ultimaEvolucion->tratamiento }}</p>
                    </div>
                    @endif
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Quick Stats -->
            <div class="card p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Estadísticas</h3>
                <div class="space-y-3">
                    <div class="p-3 bg-blue-50 rounded-lg">
                        <p class="text-sm text-gray-600">Última Consulta</p>
                        <p class="text-lg font-bold text-blue-900">
                            {{ $ultimaEvolucion ? \Carbon\Carbon::parse($ultimaEvolucion->created_at)->diffForHumans() : 'N/A' }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Alergias Alert -->
            @if($paciente->historiaClinicaBase && ($paciente->historiaClinicaBase->alergias || $paciente->historiaClinicaBase->alergias_medicamentos))
            <div class="card p-6 bg-rose-50 border-rose-200">
                <h3 class="text-lg font-bold text-gray-900 mb-2 flex items-center gap-2">
                    <i class="bi bi-exclamation-triangle text-rose-600"></i>
                    Alerta de Alergias
                </h3>
                @if($paciente->historiaClinicaBase->alergias)
                <p class="text-sm text-gray-700 mb-2"><strong>Generales:</strong> {{ $paciente->historiaClinicaBase->alergias }}</p>
                @endif
                @if($paciente->historiaClinicaBase->alergias_medicamentos)
                <p class="text-sm text-gray-700"><strong>Medicamentos:</strong> {{ $paciente->historiaClinicaBase->alergias_medicamentos }}</p>
                @endif
            </div>
            @endif

            <!-- Action Buttons -->
            <div class="card p-6">
                <div class="space-y-2">
                    <a href="{{ url('index.php/shared/historia-clinica/' . $paciente->id) }}" class="btn btn-outline w-full">
                        <i class="bi bi-file-text"></i> Ver Historial Completo
                    </a>
                    <a href="{{ route('historia-clinica.exportar', $paciente->id) }}" class="btn btn-outline w-full">
                        <i class="bi bi-download"></i> Exportar PDF
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
@media print {
    .btn, nav, .no-print {
        display: none !important;
    }
}
</style>
@endsection
