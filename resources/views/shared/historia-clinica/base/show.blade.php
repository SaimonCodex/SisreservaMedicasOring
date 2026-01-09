@extends('layouts.admin')

@section('title', 'Historia Clínica Base - ' . ($paciente->nombre_completo ?? 'Paciente'))

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-4">
            <a href="{{ url('index.php/shared/historia-clinica/' . $paciente->id) }}" class="btn btn-outline">
                <i class="bi bi-arrow-left"></i>
            </a>
            <div>
                <h1 class="text-2xl font-display font-bold text-gray-900">Historia Clínica Base</h1>
                <p class="text-gray-600 mt-1">{{ $paciente->nombre_completo ?? 'Paciente' }} - {{ $paciente->cedula ?? 'N/A' }}</p>
            </div>
        </div>
        <a href="{{ url('index.php/shared/historia-clinica/base/' . $paciente->id . '/edit') }}" class="btn btn-outline">
            <i class="bi bi-pencil"></i> Editar
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Info -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Tipado y Alergias -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="card p-6 border-l-4 border-blue-500">
                    <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="bi bi-droplet text-blue-600"></i> Tipo de Sangre
                    </h3>
                    <p class="text-3xl font-bold text-blue-800">{{ $historia->tipo_sangre ?? 'No registrado' }}</p>
                </div>

                <div class="card p-6 border-l-4 border-rose-500">
                    <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="bi bi-exclamation-triangle text-rose-600"></i> Alergias
                    </h3>
                    <p class="text-gray-700">{{ $historia->alergias ?? 'Niega alergias' }}</p>
                    @if($historia->alergias_medicamentos)
                    <div class="mt-2 pt-2 border-t border-gray-100">
                        <p class="text-sm font-semibold text-rose-700">Medicamentos:</p>
                        <p class="text-sm text-gray-600">{{ $historia->alergias_medicamentos }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Antecedentes -->
            <div class="card p-6">
                <h3 class="text-lg font-display font-bold text-gray-900 mb-6 flex items-center gap-2">
                    <i class="bi bi-clock-history text-purple-600"></i> Antecedentes
                </h3>

                <div class="space-y-6">
                    @if($historia->antecedentes_familiares)
                    <div>
                        <h4 class="font-semibold text-gray-900 mb-2">Familiares</h4>
                        <div class="p-4 bg-gray-50 rounded-lg text-gray-700">
                            {{ $historia->antecedentes_familiares }}
                        </div>
                    </div>
                    @endif

                    @if($historia->antecedentes_personales)
                    <div>
                        <h4 class="font-semibold text-gray-900 mb-2">Personales Patológicos</h4>
                        <div class="p-4 bg-gray-50 rounded-lg text-gray-700">
                            {{ $historia->antecedentes_personales }}
                        </div>
                    </div>
                    @endif

                    @if($historia->enfermedades_cronicas)
                    <div>
                        <h4 class="font-semibold text-gray-900 mb-2">Enfermedades Crónicas</h4>
                        <div class="p-4 bg-red-50 text-red-700 rounded-lg">
                            {{ $historia->enfermedades_cronicas }}
                        </div>
                    </div>
                    @endif

                    @if($historia->cirugias_previas)
                    <div>
                        <h4 class="font-semibold text-gray-900 mb-2">Cirugías Previas</h4>
                        <div class="p-4 bg-gray-50 rounded-lg text-gray-700">
                            {{ $historia->cirugias_previas }}
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Hábitos y Medicamentos -->
            <div class="card p-6">
                <h3 class="text-lg font-display font-bold text-gray-900 mb-6 flex items-center gap-2">
                    <i class="bi bi-activity text-emerald-600"></i> Estilo de Vida y Tratamientos
                </h3>

                <div class="space-y-6">
                    @if($historia->habitos)
                    <div>
                        <h4 class="font-semibold text-gray-900 mb-2">Hábitos Psicobiológicos</h4>
                        <div class="p-4 bg-gray-50 rounded-lg text-gray-700">
                            {{ $historia->habitos }}
                        </div>
                    </div>
                    @endif

                    @if($historia->medicamentos_actuales)
                    <div>
                        <h4 class="font-semibold text-gray-900 mb-2">Medicamentos Actuales</h4>
                        <div class="p-4 bg-blue-50 text-blue-800 rounded-lg">
                            {{ $historia->medicamentos_actuales }}
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Info Card -->
            <div class="card p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Información del Registro</h3>
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Creado el:</span>
                        <span class="font-semibold text-gray-900">{{ $historia->created_at->format('d/m/Y') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Última actualización:</span>
                        <span class="font-semibold text-gray-900">{{ $historia->updated_at->format('d/m/Y') }}</span>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Navegación</h3>
                <div class="space-y-2">
                    <a href="{{ url('index.php/shared/historia-clinica/' . $paciente->id) }}" class="btn btn-outline w-full justify-start">
                        <i class="bi bi-journal-medical"></i> Ver Historial Completo
                    </a>
                    <a href="{{ url('index.php/shared/historia-clinica/evoluciones/' . $paciente->id) }}" class="btn btn-outline w-full justify-start">
                        <i class="bi bi-list-check"></i> Ver Evoluciones
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
