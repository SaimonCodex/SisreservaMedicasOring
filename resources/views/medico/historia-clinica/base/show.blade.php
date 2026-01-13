@extends('layouts.medico')

@section('title', 'Historia Clínica Detallada')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-4">
            <a href="{{ route('historia-clinica.base.index') }}" class="btn btn-outline">
                <i class="bi bi-arrow-left"></i>
            </a>
            <div>
                <h1 class="text-2xl font-display font-bold text-gray-900">Historia Clínica</h1>
                <p class="text-gray-600 mt-1">Expediente médico completo del paciente</p>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('historia-clinica.base.edit', $historia->paciente_id ?? 1) }}" class="btn btn-primary">
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
                <div class="p-6 border-b border-gray-200 bg-gradient-to-r from-purple-50 to-white">
                    <h3 class="text-lg font-display font-bold text-gray-900 flex items-center gap-2">
                        <i class="bi bi-person-circle text-purple-600"></i>
                        Información del Paciente
                    </h3>
                </div>
                <div class="p-6">
                    <div class="flex items-start gap-6">
                        <div class="w-24 h-24 rounded-2xl bg-gradient-to-br from-purple-500 to-purple-600 flex items-center justify-center text-white text-3xl font-bold shadow-lg">
                            {{ strtoupper(substr($historia->paciente->primer_nombre ?? 'P', 0, 1)) }}{{ strtoupper(substr($historia->paciente->primer_apellido ?? 'A', 0, 1)) }}
                        </div>
                        <div class="flex-1">
                            <h4 class="text-2xl font-bold text-gray-900">
                                {{ $historia->paciente->primer_nombre ?? 'N/A' }} 
                                {{ $historia->paciente->segundo_nombre ?? '' }}
                                {{ $historia->paciente->primer_apellido ?? '' }}
                                {{ $historia->paciente->segundo_apellido ?? '' }}
                            </h4>
                            <div class="grid grid-cols-3 gap-4 mt-4">
                                <div>
                                    <p class="text-sm text-gray-500">Cédula</p>
                                    <p class="font-semibold text-gray-900">{{ $historia->paciente->cedula ?? 'N/A' }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Edad</p>
                                    <p class="font-semibold text-gray-900">
                                        {{ isset($historia->paciente->fecha_nacimiento) ? \Carbon\Carbon::parse($historia->paciente->fecha_nacimiento)->age . ' años' : 'N/A' }}
                                    </p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Sexo</p>
                                    <p class="font-semibold text-gray-900">{{ ucfirst($historia->paciente->sexo ?? 'N/A') }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Teléfono</p>
                                    <p class="font-semibold text-gray-900">{{ $historia->paciente->telefono ?? 'N/A' }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Correo</p>
                                    <p class="font-semibold text-gray-900 text-sm truncate">{{ $historia->paciente->correo ?? 'N/A' }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Dirección</p>
                                    <p class="font-semibold text-gray-900 text-sm truncate">{{ $historia->paciente->direccion ?? 'N/A' }}</p>
                                </div>
                            </div>
                            <div class="mt-4">
                                <a href="{{ route('pacientes.show', $historia->paciente->id ?? 1) }}" class="btn btn-sm btn-outline">
                                    <i class="bi bi-eye"></i> Ver Perfil Completo
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Basic Medical Data -->
            <div class="card">
                <div class="p-6 border-b border-gray-200 bg-gradient-to-r from-rose-50 to-white">
                    <h3 class="text-lg font-display font-bold text-gray-900 flex items-center gap-2">
                        <i class="bi bi-clipboard-data text-rose-600"></i>
                        Datos Básicos
                    </h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-2 gap-6">
                        <div class="p-4 bg-rose-50 rounded-xl border border-rose-200">
                            <div class="flex items-center gap-3">
                                <i class="bi bi-droplet-fill text-rose-600 text-2xl"></i>
                                <div>
                                    <p class="text-sm text-gray-600">Tipo de Sangre</p>
                                    <p class="text-2xl font-bold text-gray-900">{{ $historia->tipo_sangre ?? 'N/A' }}</p>
                                </div>
                            </div>
                        </div>
                        @if($historia->factor_rh ?? null)
                        <div class="p-4 bg-blue-50 rounded-xl border border-blue-200">
                            <div class="flex items-center gap-3">
                                <i class="bi bi-plus-circle text-blue-600 text-2xl"></i>
                                <div>
                                    <p class="text-sm text-gray-600">Factor RH</p>
                                    <p class="text-xl font-bold text-gray-900">{{ ucfirst($historia->factor_rh) }}</p>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Medical History -->
            <div class="card">
                <div class="p-6 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-white">
                    <h3 class="text-lg font-display font-bold text-gray-900 flex items-center gap-2">
                        <i class="bi bi-journal-medical text-blue-600"></i>
                        Antecedentes Médicos
                    </h3>
                </div>
                <div class="p-6 space-y-6">
                    @if($historia->antecedentes_personales ?? null)
                    <div>
                        <label class="text-sm font-semibold text-gray-700 block mb-2">Antecedentes Personales</label>
                        <div class="p-4 bg-gray-50 rounded-xl">
                            <p class="text-gray-900">{{ $historia->antecedentes_personales }}</p>
                        </div>
                    </div>
                    @endif

                    @if($historia->antecedentes_familiares ?? null)
                    <div>
                        <label class="text-sm font-semibold text-gray-700 block mb-2">Antecedentes Familiares</label>
                        <div class="p-4 bg-gray-50 rounded-xl">
                            <p class="text-gray-900">{{ $historia->antecedentes_familiares }}</p>
                        </div>
                    </div>
                    @endif

                    @if($historia->alergias ?? null)
                    <div>
                        <label class="text-sm font-semibold text-gray-700 block mb-2 flex items-center gap-2">
                            <i class="bi bi-exclamation-triangle text-rose-600"></i>
                            Alergias
                        </label>
                        <div class="p-4 bg-rose-50 rounded-xl border border-rose-200">
                            <p class="text-gray-900 font-semibold">{{ $historia->alergias }}</p>
                        </div>
                    </div>
                    @endif

                    @if($historia->medicamentos_actuales ?? null)
                    <div>
                        <label class="text-sm font-semibold text-gray-700 block mb-2">Medicamentos Actuales</label>
                        <div class="p-4 bg-blue-50 rounded-xl border border-blue-200">
                            <p class="text-gray-900">{{ $historia->medicamentos_actuales }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Lifestyle -->
            <div class="card">
                <div class="p-6 border-b border-gray-200 bg-gradient-to-r from-emerald-50 to-white">
                    <h3 class="text-lg font-display font-bold text-gray-900 flex items-center gap-2">
                        <i class="bi bi-heart text-emerald-600"></i>
                        Estilo de Vida
                    </h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-2 gap-4">
                        @if($historia->habito_tabaco ?? null)
                        <div class="p-4 bg-gray-50 rounded-xl">
                            <p class="text-sm text-gray-600 mb-1">Hábito de Tabaco</p>
                            <p class="font-bold text-gray-900">{{ ucfirst(str_replace('_', ' ', $historia->habito_tabaco)) }}</p>
                        </div>
                        @endif

                        @if($historia->consumo_alcohol ?? null)
                        <div class="p-4 bg-gray-50 rounded-xl">
                            <p class="text-sm text-gray-600 mb-1">Consumo de Alcohol</p>
                            <p class="font-bold text-gray-900">{{ ucfirst(str_replace('_', ' ', $historia->consumo_alcohol)) }}</p>
                        </div>
                        @endif

                        @if($historia->actividad_fisica ?? null)
                        <div class="p-4 bg-gray-50 rounded-xl">
                            <p class="text-sm text-gray-600 mb-1">Actividad Física</p>
                            <p class="font-bold text-gray-900">{{ ucfirst($historia->actividad_fisica) }}</p>
                        </div>
                        @endif

                        @if($historia->dieta ?? null)
                        <div class="p-4 bg-gray-50 rounded-xl">
                            <p class="text-sm text-gray-600 mb-1">Dieta</p>
                            <p class="font-bold text-gray-900">{{ ucfirst($historia->dieta) }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Evoluciones -->
            <div class="card">
                <div class="p-6 border-b border-gray-200 bg-gradient-to-r from-purple-50 to-white">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-display font-bold text-gray-900 flex items-center gap-2">
                            <i class="bi bi-file-earmark-medical text-purple-600"></i>
                            Evoluciones Clínicas
                        </h3>
                        <span class="badge badge-primary">{{ ($historia->evoluciones ?? collect())->count() }} registros</span>
                    </div>
                </div>
                <div class="p-6">
                    @forelse($historia->evoluciones ?? [] as $evolucion)
                    <div class="flex gap-4 p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors mb-3">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 rounded-lg bg-purple-100 flex items-center justify-center">
                                <i class="bi bi-file-medical text-purple-600 text-xl"></i>
                            </div>
                        </div>
                        <div class="flex-1">
                            <p class="font-semibold text-gray-900">{{ $evolucion->diagnostico ?? 'Sin diagnóstico' }}</p>
                            <p class="text-sm text-gray-600 mt-1">{{ $evolucion->motivo_consulta ?? 'N/A' }}</p>
                            <p class="text-xs text-gray-500 mt-1">
                                {{ isset($evolucion->created_at) ? \Carbon\Carbon::parse($evolucion->created_at)->format('d/m/Y H:i A') : 'N/A' }}
                            </p>
                        </div>
                        <div class="flex-shrink-0">
                            <a href="{{ route('historia-clinica.evoluciones.show', ['citaId' => $evolucion->cita_id ?? 0]) }}" class="btn btn-sm btn-outline">
                                <i class="bi bi-eye"></i>
                            </a>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-8">
                        <i class="bi bi-file-earmark-x text-4xl text-gray-300 mb-2"></i>
                        <p class="text-gray-500">No hay evoluciones registradas</p>
                    </div>
                    @endforelse
                </div>
            </div>

            @if($historia->observaciones ?? null)
            <!-- Observaciones -->
            <div class="card">
                <div class="p-6 border-b border-gray-200 bg-gradient-to-r from-amber-50 to-white">
                    <h3 class="text-lg font-display font-bold text-gray-900 flex items-center gap-2">
                        <i class="bi bi-sticky text-amber-600"></i>
                        Observaciones Generales
                    </h3>
                </div>
                <div class="p-6">
                    <div class="p-4 bg-amber-50 rounded-xl border border-amber-200">
                        <p class="text-gray-900">{{ $historia->observaciones }}</p>
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Quick Stats -->
            <div class="card p-6">
                <h3 class="text-lg font-display font-bold text-gray-900 mb-4">Estadísticas</h3>
                <div class="space-y-4">
                    <div class="flex items-center justify-between p-3 bg-purple-50 rounded-lg">
                        <div class="flex items-center gap-2">
                            <i class="bi bi-file-medical text-purple-600"></i>
                            <span class="text-sm text-gray-700">Evoluciones</span>
                        </div>
                        <span class="font-bold text-purple-900">{{ ($historia->evoluciones ?? collect())->count() }}</span>
                    </div>
                    <div class="flex items-center justify-between p-3 bg-blue-50 rounded-lg">
                        <div class="flex items-center gap-2">
                            <i class="bi bi-calendar-check text-blue-600"></i>
                            <span class="text-sm text-gray-700">Citas</span>
                        </div>
                        <span class="font-bold text-blue-900">{{ $historia->paciente->citas->count() ?? 0 }}</span>
                    </div>
                </div>
            </div>

            <!-- Metadata -->
            <div class="card p-6">
                <h3 class="text-lg font-display font-bold text-gray-900 mb-4">Información del Registro</h3>
                <div class="space-y-3 text-sm">
                    <div>
                        <p class="text-gray-500">Fecha de Creación</p>
                        <p class="font-semibold text-gray-900">
                            {{ isset($historia->created_at) ? \Carbon\Carbon::parse($historia->created_at)->format('d/m/Y H:i A') : 'N/A' }}
                        </p>
                    </div>
                    <div>
                        <p class="text-gray-500">Última Actualización</p>
                        <p class="font-semibold text-gray-900">
                            {{ isset($historia->updated_at) ? \Carbon\Carbon::parse($historia->updated_at)->format('d/m/Y H:i A') : 'N/A' }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card p-6">
                <h3 class="text-lg font-display font-bold text-gray-900 mb-4">Acciones Rápidas</h3>
                <div class="space-y-2">
                    <a href="{{ route('historia-clinica.evoluciones.create', ['citaId' => 0, 'historia_clinica_id' => $historia->id]) }}" class="btn btn-outline w-full justify-start">
                        <i class="bi bi-file-earmark-plus"></i>
                        Nueva Evolución
                    </a>
                    <a href="{{ route('ordenes-medicas.create', ['paciente' => $historia->paciente->id ?? 1]) }}" class="btn btn-outline w-full justify-start">
                        <i class="bi bi-clipboard-plus"></i>
                        Nueva Orden
                    </a>
                    <a href="{{ route('citas.create', ['paciente' => $historia->paciente->id ?? 1]) }}" class="btn btn-outline w-full justify-start">
                        <i class="bi bi-calendar-plus"></i>
                        Agendar Cita
                    </a> --}}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
