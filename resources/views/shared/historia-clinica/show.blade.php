@extends('layouts.admin')

@section('title', 'Historia Clínica - ' . ($paciente->nombre_completo ?? 'Paciente'))

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-4">
            <a href="{{ url('index.php/shared/historia-clinica') }}" class="btn btn-outline">
                <i class="bi bi-arrow-left"></i>
            </a>
            <div>
                <h1 class="text-2xl font-display font-bold text-gray-900">{{ $paciente->nombre_completo ?? 'Paciente' }}</h1>
                <p class="text-gray-600 mt-1">{{ $paciente->cedula ?? 'N/A' }}</p>
            </div>
        </div>
        <a href="{{ url('index.php/medico/historia-clinica/create?paciente_id=' . $paciente->id) }}" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i> Nueva Historia
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Timeline -->
        <div class="lg:col-span-2 space-y-6">
            <div class="card p-6">
                <h3 class="text-lg font-display font-bold text-gray-900 mb-6">Historial Médico</h3>
                
                <div class="space-y-6">
                    @forelse($historias ?? [] as $historia)
                    <div class="flex gap-4">
                        <div class="flex-shrink-0 w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center">
                            <i class="bi bi-file-medical text-blue-600"></i>
                        </div>
                        <div class="flex-1">
                            <div class="card p-4 hover:shadow-md transition-shadow">
                                <div class="flex items-start justify-between mb-3">
                                    <div>
                                        <h4 class="font-semibold text-gray-900">{{ $historia->motivo_consulta ?? 'Consulta General' }}</h4>
                                        <p class="text-sm text-gray-600 mt-1">
                                            Dr. {{ $historia->medico->nombre_completo ?? 'N/A' }} • {{ $historia->especialidad->nombre ?? 'N/A' }}
                                        </p>
                                    </div>
                                    <span class="text-sm text-gray-500">{{ isset($historia->fecha) ? \Carbon\Carbon::parse($historia->fecha)->format('d/m/Y') : 'N/A' }}</span>
                                </div>

                                @if($historia->diagnostico)
                                <div class="mb-3">
                                    <p class="text-sm font-semibold text-gray-700">Diagnóstico:</p>
                                    <p class="text-sm text-gray-600">{{ $historia->diagnostico }}</p>
                                </div>
                                @endif

                                @if($historia->tratamiento)
                                <div class="mb-3">
                                    <p class="text-sm font-semibold text-gray-700">Tratamiento:</p>
                                    <p class="text-sm text-gray-600">{{ $historia->tratamiento }}</p>
                                </div>
                                @endif

                                <div class="flex gap-2 mt-3">
                                    <a href="{{ url('index.php/medico/historia-clinica/' . $historia->id) }}" class="btn btn-sm btn-outline">
                                        <i class="bi bi-eye"></i> Ver Detalles
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-12">
                        <i class="bi bi-inbox text-5xl text-gray-300 mb-3"></i>
                        <p class="text-gray-500">No hay registros en el historial</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Patient Info -->
            <div class="card p-6">
                <h3 class="text-lg font-display font-bold text-gray-900 mb-4">Información del Paciente</h3>
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Edad:</span>
                        <span class="font-semibold text-gray-900">{{ $paciente->edad ?? 'N/A' }} años</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Género:</span>
                        <span class="font-semibold text-gray-900">{{ ucfirst($paciente->genero ?? 'N/A') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Grupo Sanguíneo:</span>
                        <span class="font-semibold text-gray-900">{{ $paciente->grupo_sanguineo ?? 'N/A' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Teléfono:</span>
                        <span class="font-semibold text-gray-900">{{ $paciente->telefono ?? 'N/A' }}</span>
                    </div>
                </div>
            </div>

            <!-- Stats -->
            <div class="card p-6">
                <h3 class="text-lg font-display font-bold text-gray-900 mb-4">Estadísticas</h3>
                <div class="space-y-3">
                    <div class="p-3 bg-blue-50 rounded-lg">
                        <p class="text-sm text-gray-600">Consultas</p>
                        <p class="text-2xl font-bold text-blue-900">{{ $stats['consultas'] ?? 0 }}</p>
                    </div>
                    <div class="p-3 bg-emerald-50 rounded-lg">
                        <p class="text-sm text-gray-600">Recetas</p>
                        <p class="text-2xl font-bold text-emerald-900">{{ $stats['recetas'] ?? 0 }}</p>
                    </div>
                    <div class="p-3 bg-purple-50 rounded-lg">
                        <p class="text-sm text-gray-600">Órdenes Médicas</p>
                        <p class="text-2xl font-bold text-purple-900">{{ $stats['ordenes'] ?? 0 }}</p>
                    </div>
                </div>
            </div>

            <!-- Alergias -->
            @if($paciente->alergias)
            <div class="card p-6 bg-rose-50 border-rose-200">
                <h3 class="text-lg font-display font-bold text-gray-900 mb-2 flex items-center gap-2">
                    <i class="bi bi-exclamation-triangle text-rose-600"></i>
                    Alergias
                </h3>
                <p class="text-sm text-gray-700">{{ $paciente->alergias }}</p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
