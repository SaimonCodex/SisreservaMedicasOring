@extends('layouts.admin')

@section('title', 'Detalle de Especialidad')

@section('content')
<div class="mb-6">
    <a href="{{ route('especialidades.index') }}" class="text-medical-600 hover:text-medical-700 inline-flex items-center text-sm font-medium mb-3">
        <i class="bi bi-arrow-left mr-1"></i> Volver a Especialidades
    </a>
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h2 class="text-3xl font-display font-bold text-gray-900">{{ $especialidad->nombre }}</h2>
            <p class="text-gray-500 mt-1">Detalle completo de la especialidad médica</p>
        </div>
        <div class="flex gap-3">
            @if(auth()->user()->administrador && auth()->user()->administrador->tipo_admin === 'Root')
            <a href="{{ route('especialidades.edit', $especialidad->id) }}" class="btn btn-primary">
                <i class="bi bi-pencil mr-2"></i>
                Editar
            </a>
            <form action="{{ route('especialidades.destroy', $especialidad->id) }}" method="POST" onsubmit="return confirm('¿Confirma desactivar esta especialidad?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-outline text-red-600 hover:bg-red-50 hover:border-red-200">
                    <i class="bi bi-trash mr-2"></i>
                    Desactivar
                </button>
            </form>
            @endif
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Columna Principal -->
    <div class="lg:col-span-2 space-y-6">
        
        <!-- Encabezado -->
        <div class="card p-0 overflow-hidden">
            <div class="bg-gradient-to-br from-{{ $especialidad->color }}-500 to-{{ $especialidad->color }}-600 p-8">
                <div class="flex items-center gap-6">
                    <div class="w-20 h-20 rounded-2xl bg-white/20 backdrop-blur-sm flex items-center justify-center text-white text-4xl border-4 border-white/30">
                        <i class="bi bi-{{ $especialidad->icono }}"></i>
                    </div>
                    <div class="text-white flex-1">
                        <h3 class="text-3xl font-bold mb-2">{{ $especialidad->nombre }}</h3>
                        <p class="text-white/90 text-lg">{{ $especialidad->codigo }}</p>
                        <div class="flex gap-2 mt-3">
                            <span class="badge bg-white/20 text-white border border-white/30">
                                {{ $especialidad->status ? 'Activa' : 'Inactiva' }}
                            </span>
                            @if($especialidad->codigo)
                            <span class="badge bg-white/20 text-white border border-white/30">Código: {{ $especialidad->codigo }}</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="p-6">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div class="text-center p-4 bg-gray-50 rounded-xl">
                        <p class="text-3xl font-bold text-medical-600 mb-1">{{ $especialidad->medicos->count() }}</p>
                        <p class="text-sm text-gray-500">Médicos</p>
                    </div>
                    <div class="text-center p-4 bg-gray-50 rounded-xl">
                        <p class="text-3xl font-bold text-success-600 mb-1">{{ $especialidad->total_citas }}</p>
                        <p class="text-sm text-gray-500">Citas Totales</p>
                    </div>
                    <div class="text-center p-4 bg-gray-50 rounded-xl">
                        <p class="text-3xl font-bold text-warning-600 mb-1">
                             {{ $especialidad->citas_pendientes }}
                        </p>
                        <p class="text-sm text-gray-500">Pendientes</p>
                    </div>
                    <div class="text-center p-4 bg-gray-50 rounded-xl">
                        <p class="text-3xl font-bold text-info-600 mb-1">
                            {{ $especialidad->duracion_cita_default }}m
                        </p>
                        <p class="text-sm text-gray-500">Duración</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Información General -->
        <div class="card p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                <i class="bi bi-info-circle text-medical-600"></i>
                Información General
            </h3>
            
            <div class="space-y-4">
                <div>
                    <p class="text-sm text-gray-500 mb-2">Descripción</p>
                    <p class="text-gray-700 leading-relaxed">
                        {{ $especialidad->descripcion }}
                    </p>
                </div>

                @if($especialidad->observaciones)
                <div>
                    <p class="text-sm text-gray-500 mb-2">Observaciones</p>
                    <p class="text-gray-600 italic">
                        {{ $especialidad->observaciones }}
                    </p>
                </div>
                @endif
            </div>
        </div>

        <!-- Médicos de la Especialidad -->
        <div class="card p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                    <i class="bi bi-person-badge text-success-600"></i>
                    Médicos Asignados
                </h3>
                <span class="badge badge-primary">{{ $especialidad->medicos->count() }} médicos</span>
            </div>
            
            <div class="space-y-3">
                @forelse($especialidad->medicos as $medico)
                <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors">
                    <div class="w-12 h-12 rounded-full bg-gradient-to-br from-medical-500 to-medical-600 flex items-center justify-center text-white font-bold">
                        {{ substr($medico->primer_nombre, 0, 1) }}{{ substr($medico->primer_apellido, 0, 1) }}
                    </div>
                    <div class="flex-1">
                        <p class="font-semibold text-gray-900">Dr. {{ $medico->primer_nombre }} {{ $medico->primer_apellido }}</p>
                        <p class="text-sm text-gray-600">MPPS: {{ $medico->nro_colegiatura }}</p>
                    </div>
                    <a href="{{ route('medicos.show', $medico->id) }}" class="btn btn-sm btn-outline">
                        <i class="bi bi-eye mr-1"></i> Ver
                    </a>
                </div>
                @empty
                <div class="text-center py-8 text-gray-500">
                    <i class="bi bi-person-x text-3xl mb-2"></i>
                    <p>No hay médicos asignados a esta especialidad.</p>
                </div>
                @endforelse
            </div>
        </div>

        @if($especialidad->requisitos)
        <!-- Requisitos -->
        <div class="card p-6 border-l-4 border-l-warning-500">
            <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                <i class="bi bi-clipboard-check text-warning-600"></i>
                Requisitos para Citas
            </h3>
            <div class="bg-warning-50 rounded-lg p-4">
                <div class="text-sm text-gray-700 whitespace-pre-line">
                    {{ $especialidad->requisitos }}
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- Sidebar -->
    <div class="lg:col-span-1 space-y-6">
        <!-- Acciones Rápidas -->
        <div class="card p-6 border-t-4 border-t-medical-500">
            <h4 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
                <i class="bi bi-lightning-charge text-medical-600"></i>
                Acciones Rápidas
            </h4>
            <div class="space-y-3">
                <a href="{{ route('citas.create', ['especialidad_id' => $especialidad->id]) }}" class="btn btn-primary w-full justify-start py-3">
                    <i class="bi bi-calendar-plus mr-3 text-lg"></i> 
                    <div>
                        <p class="font-bold text-sm">Nueva Cita</p>
                        <p class="text-[10px] opacity-80 font-normal">Agendar paciente aquí</p>
                    </div>
                </a>
                @if(auth()->user()->administrador && auth()->user()->administrador->tipo_admin === 'Root')
                <a href="{{ route('medicos.create', ['especialidad_id' => $especialidad->id]) }}" class="btn btn-outline w-full justify-start py-3">
                    <i class="bi bi-person-plus mr-3 text-lg"></i>
                    <div>
                        <p class="font-bold text-sm">Asignar Médico</p>
                        <p class="text-[10px] text-gray-500 font-normal">Añadir profesional</p>
                    </div>
                </a>
                @endif
                <a href="#" class="btn btn-ghost w-full justify-start text-gray-600 hover:bg-gray-50">
                    <i class="bi bi-file-earmark-bar-graph mr-3 text-lg"></i> Ver Reportes
                </a>
            </div>
        </div>

        <!-- Estado -->
        <div class="card p-6">
            <h4 class="font-bold text-gray-900 mb-4">Configuración</h4>
            <div class="space-y-3 text-sm">
                <div class="flex items-center justify-between">
                    <span class="text-gray-600">Estado</span>
                    <span class="badge badge-{{ $especialidad->status ? 'success' : 'danger' }}">
                        {{ $especialidad->status ? 'Activa' : 'Inactiva' }}
                    </span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-gray-600">Color</span>
                    <div class="w-6 h-6 rounded-full bg-{{ $especialidad->color }}-500"></div>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-gray-600">Prioridad</span>
                    <span class="badge badge-warning">{{ $especialidad->prioridad }}</span>
                </div>
                <div class="flex items-center justify-between pt-3 border-t border-gray-100">
                    <span class="text-gray-600">Creada</span>
                    <span class="text-xs text-gray-500">{{ $especialidad->created_at->format('d/m/Y') }}</span>
                </div>
            </div>
        </div>

        <!-- Disponibilidad Semana (Agregada de los médicos) -->
        <div class="card p-6 bg-gradient-to-br from-medical-50 to-info-50">
            <h4 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
                <i class="bi bi-clock-history text-medical-600"></i>
                Disponibilidad
            </h4>
            <div class="space-y-3">
                @php
                    $diasDisponibles = ['Lun', 'Mar', 'Mie', 'Jue', 'Vie'];
                @endphp
                <div class="flex gap-1 justify-between">
                    @foreach($diasDisponibles as $dia)
                    <div class="flex flex-col items-center flex-1">
                        <span class="text-[10px] uppercase text-gray-400 font-bold mb-1">{{ $dia }}</span>
                        <div class="w-full h-8 rounded bg-white border border-medical-100 flex items-center justify-center">
                            <i class="bi bi-check2 text-medical-600"></i>
                        </div>
                    </div>
                    @endforeach
                </div>
                <p class="text-[11px] text-gray-500 text-center mt-2 italic">
                    Basado en el horario de {{ $especialidad->medicos->count() }} médicos activos.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
