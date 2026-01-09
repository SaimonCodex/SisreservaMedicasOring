@extends('layouts.paciente')

@section('title', 'Mis Citas')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-display font-bold text-gray-900">Mis Citas</h1>
            <p class="text-gray-600 mt-1">Agenda y gestiona tus consultas médicas</p>
        </div>
        <a href="{{ url('index.php/paciente/citas/create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i>
            <span>Nueva Cita</span>
        </a>
    </div>

    <!-- Tabs -->
    <div class="card p-6">
        <div class="flex flex-wrap gap-2 border-b border-gray-200 pb-4 mb-6">
            <button class="tab-button active" data-tab="proximas">
                <i class="bi bi-calendar-check"></i> Próximas
            </button>
            <button class="tab-button" data-tab="realizadas">
                <i class="bi bi-clock-history"></i> Realizadas
            </button>
            <button class="tab-button" data-tab="canceladas">
                <i class="bi bi-x-circle"></i> Canceladas
            </button>
        </div>

        <!-- Próximas Citas Tab -->
        <div id="tab-proximas" class="tab-content">
            <div class="space-y-4">
                @forelse($citasProximas ?? [] as $cita)
                <div class="card p-6 hover:shadow-md transition-shadow">
                    <div class="flex flex-col md:flex-row gap-6">
                        <!-- Date Box -->
                        <div class="text-center p-4 bg-blue-50 rounded-xl w-full md:w-24 flex-shrink-0">
                            <p class="text-3xl font-bold text-blue-600">
                                {{ \Carbon\Carbon::parse($cita->fecha)->format('d') }}
                            </p>
                            <p class="text-sm text-blue-700">
                                {{ \Carbon\Carbon::parse($cita->fecha)->isoFormat('MMM') }}
                            </p>
                            <p class="text-xs text-gray-500 mt-1">
                                {{ \Carbon\Carbon::parse($cita->fecha)->isoFormat('YYYY') }}
                            </p>
                        </div>

                        <!-- Cita Info -->
                        <div class="flex-1">
                            <div class="flex items-start justify-between mb-3">
                                <div>
                                    <h3 class="text-xl font-bold text-gray-900">{{ $cita->especialidad->nombre ?? 'Consulta General' }}</h3>
                                    <p class="text-gray-600 flex items-center gap-2 mt-1">
                                        <i class="bi bi-person-badge text-blue-600"></i>
                                        Dr. {{ $cita->medico->primer_nombre ?? 'N/A' }} {{ $cita->medico->primer_apellido ?? '' }}
                                    </p>
                                </div>
                                @if($cita->status == 'confirmada')
                                <span class="badge badge-success">Confirmada</span>
                                @elseif($cita->status == 'pendiente')
                                <span class="badge badge-warning">Pendiente</span>
                                @endif
                            </div>

                            <div class="grid grid-cols-2 md:grid-cols-3 gap-4 text-sm">
                                <div class="flex items-center gap-2 text-gray-700">
                                    <i class="bi bi-clock text-blue-600"></i>
                                    <span>{{ \Carbon\Carbon::parse($cita->hora_inicio)->format('h:i A') }}</span>
                                </div>
                                <div class="flex items-center gap-2 text-gray-700">
                                    <i class="bi bi-building text-blue-600"></i>
                                    <span>{{ $cita->consultorio->nombre ?? 'Consultorio' }}</span>
                                </div>
                                @if($cita->motivo ?? null)
                                <div class="flex items-center gap-2 text-gray-700">
                                    <i class="bi bi-chat-left-text text-blue-600"></i>
                                    <span>{{ Str::limit($cita->motivo, 30) }}</span>
                                </div>
                                @endif
                            </div>

                            <div class="flex gap-2 mt-4">
                                <a href="{{ url('index.php/paciente/citas/' . $cita->id) }}" class="btn btn-sm btn-primary">
                                    <i class="bi bi-eye"></i> Ver Detalles
                                </a>
                                @if($cita->status != 'cancelada')
                                <button onclick="if(confirm('¿Cancelar esta cita?')) { /* submit form */ }" class="btn btn-sm btn-outline text-rose-600 hover:bg-rose-50">
                                    <i class="bi bi-x-circle"></i> Cancelar
                                </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="text-center py-12">
                    <i class="bi bi-calendar-x text-5xl text-gray-300 mb-3"></i>
                    <p class="text-gray-500 font-medium mb-2">No tienes citas próximas</p>
                    <p class="text-sm text-gray-400 mb-4">Agenda una nueva cita médica</p>
                    <a href="{{ url('index.php/paciente/citas/create') }}" class="btn btn-sm btn-primary">
                        <i class="bi bi-plus-lg"></i> Nueva Cita
                    </a>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Realizadas Tab -->
        <div id="tab-realizadas" class="tab-content hidden">
            <div class="space-y-4">
                @forelse($citasRealizadas ?? [] as $cita)
                <div class="card p-6 bg-gray-50">
                    <div class="flex items-start justify-between">
                        <div>
                            <h3 class="font-bold text-gray-900">{{ $cita->especialidad->nombre ?? 'Consulta' }}</h3>
                            <p class="text-sm text-gray-600 mt-1">
                                Dr. {{ $cita->medico->primer_nombre ?? 'N/A' }} {{ $cita->medico->primer_apellido ?? '' }}
                            </p>
                            <p class="text-xs text-gray-500 mt-2">
                                {{ \Carbon\Carbon::parse($cita->fecha)->format('d/m/Y') }} - 
                                {{ \Carbon\Carbon::parse($cita->hora_inicio)->format('h:i A') }}
                            </p>
                        </div>
                        <span class="badge badge-success">Completada</span>
                    </div>
                </div>
                @empty
                <div class="text-center py-12">
                    <i class="bi bi-clock-history text-4xl text-gray-300 mb-3"></i>
                    <p class="text-gray-500">No hay citas realizadas</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Canceladas Tab -->
        <div id="tab-canceladas" class="tab-content hidden">
            <div class="space-y-4">
                @forelse($citasCanceladas ?? [] as $cita)
                <div class="card p-6 bg-rose-50">
                    <div class="flex items-start justify-between">
                        <div>
                            <h3 class="font-bold text-gray-900">{{ $cita->especialidad->nombre ?? 'Consulta' }}</h3>
                            <p class="text-sm text-gray-600 mt-1">
                                Dr. {{ $cita->medico->primer_nombre ?? 'N/A' }} {{ $cita->medico->primer_apellido ?? '' }}
                            </p>
                            <p class="text-xs text-gray-500 mt-2">
                                {{ \Carbon\Carbon::parse($cita->fecha)->format('d/m/Y') }}
                            </p>
                        </div>
                        <span class="badge badge-danger">Cancelada</span>
                    </div>
                </div>
                @empty
                <div class="text-center py-12">
                    <i class="bi bi-x-circle text-4xl text-gray-300 mb-3"></i>
                    <p class="text-gray-500">No hay citas canceladas</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const tabButtons = document.querySelectorAll('.tab-button');
        const tabContents = document.querySelectorAll('.tab-content');

        tabButtons.forEach(button => {
            button.addEventListener('click', function() {
                const tabName = this.dataset.tab;

                // Remove active from all buttons
                tabButtons.forEach(btn => btn.classList.remove('active'));
                // Hide all tabs
                tabContents.forEach(content => content.classList.add('hidden'));

                // Activate clicked tab
                this.classList.add('active');
                document.getElementById('tab-' + tabName).classList.remove('hidden');
            });
        });
    });
</script>

<style>
    .tab-button {
        @apply px-4 py-2 rounded-lg font-semibold text-gray-600 hover:bg-gray-100 transition-colors;
    }
    .tab-button.active {
        @apply bg-blue-600 text-white hover:bg-blue-700;
    }
</style>
@endpush
@endsection
