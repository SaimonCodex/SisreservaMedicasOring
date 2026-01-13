@extends('layouts.admin')

@section('title', 'Detalle del Consultorio')

@section('content')
<div class="mb-6">
    <a href="{{ route('consultorios.index') }}" class="text-medical-600 hover:text-medical-700 inline-flex items-center text-sm font-medium mb-3">
        <i class="bi bi-arrow-left mr-1"></i> Volver a Consultorios
    </a>
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h2 class="text-3xl font-display font-bold text-gray-900">{{ $consultorio->nombre }}</h2>
            <p class="text-gray-500 mt-1">información detallada del espacio médico</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('consultorios.horarios', $consultorio->id) }}" class="btn btn-outline">
                <i class="bi bi-clock mr-2"></i>
                Horarios
            </a>
            <a href="{{ route('consultorios.edit', $consultorio->id) }}" class="btn btn-primary">
                <i class="bi bi-pencil mr-2"></i>
                Editar
            </a>
            <form action="{{ route('consultorios.destroy', $consultorio->id) }}" method="POST" onsubmit="return confirm('¿Está seguro de desactivar este consultorio?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-ghost text-red-600 hover:bg-red-50">
                    <i class="bi bi-trash"></i>
                </button>
            </form>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Columna Principal -->
    <div class="lg:col-span-2 space-y-6">
        
        <!-- Encabezado -->
        <div class="card p-0 overflow-hidden">
            <div class="bg-gradient-to-br from-medical-500 to-medical-600 p-8">
                <div class="flex items-center justify-between">
                    <div class="text-white">
                        <h3 class="text-4xl font-bold mb-2">{{ $consultorio->nombre }}</h3>
                        <p class="text-white/90 text-xl mb-3">{{ $consultorio->ciudad->ciudad ?? 'Ubicación no definida' }}</p>
                        <p class="text-white/80"><i class="bi bi-geo-alt mr-1"></i> {{ $consultorio->direccion_detallada ?? 'Sin dirección detallada' }}</p>
                    </div>
                    <span class="badge {{ $consultorio->status ? 'bg-success-500' : 'bg-danger-500' }} text-white text-lg px-4 py-2 border-2 border-white/30">
                        {{ $consultorio->status ? 'Disponible' : 'Inactivo' }}
                    </span>
                </div>
            </div>
            
            <div class="p-6">
                <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                    <div class="text-center p-4 bg-gray-50 rounded-xl">
                        <p class="text-3xl font-bold text-medical-600 mb-1">{{ $consultorio->medicos->count() }}</p>
                        <p class="text-sm text-gray-500">Médicos Asignados</p>
                    </div>
                    <div class="text-center p-4 bg-gray-50 rounded-xl">
                        <p class="text-3xl font-bold text-success-600 mb-1">{{ $consultorio->especialidades->count() }}</p>
                        <p class="text-sm text-gray-500">Especialidades</p>
                    </div>
                    <!-- Placeholder logic for appointments since we don't have direct relation or it's complex -->
                    <div class="text-center p-4 bg-gray-50 rounded-xl">
                        <p class="text-3xl font-bold text-info-600 mb-1">
                            {{-- Assuming we might have a relation like consultorio->citas --}}
                            {{ $consultorio->citas ? $consultorio->citas->count() : 0 }}
                        </p>
                        <p class="text-sm text-gray-500">Citas Históricas</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Médicos Asignados -->
        <div class="card p-6 border-l-4 border-l-success-500">
            <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                <i class="bi bi-people text-success-600"></i>
                Médicos Asignados
            </h3>
            
            @if(isset($medicosAsignados) && $medicosAsignados->count() > 0)
                <div class="space-y-4">
                    @foreach($medicosAsignados as $medico)
                    <div class="flex items-center gap-4 p-3 hover:bg-gray-50 rounded-lg transition-colors border border-gray-100">
                        <div class="w-12 h-12 rounded-full bg-gradient-to-br from-medical-500 to-medical-600 flex items-center justify-center text-white text-lg font-bold shrink-0">
                            {{ substr($medico->primer_nombre, 0, 1) }}{{ substr($medico->primer_apellido, 0, 1) }}
                        </div>
                        <div class="flex-1">
                            <div class="flex flex-col md:flex-row md:items-center justify-between gap-2">
                                <div>
                                    <h4 class="font-bold text-gray-900">Dr. {{ $medico->primer_nombre }} {{ $medico->primer_apellido }}</h4>
                                    <p class="text-sm mt-1">
                                        @if($medico->especialidades_en_consultorio->count() > 0)
                                            @foreach($medico->especialidades_en_consultorio as $esp)
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800 mr-1">
                                                    {{ $esp }}
                                                </span>
                                            @endforeach
                                        @else
                                            <span class="text-gray-500 italic">Sin especialidad asignada en horario</span>
                                        @endif
                                    </p>
                                </div>
                                <div class="text-xs text-gray-500">
                                    MPPS: {{ $medico->nro_colegiatura }}
                                </div>
                            </div>
                        </div>
                        <a href="{{ route('medicos.show', $medico->id) }}" class="btn btn-sm btn-outline">
                            <i class="bi bi-eye mr-1"></i>
                        </a>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-6 text-gray-500 bg-gray-50 rounded-xl border border-dashed border-gray-200">
                    <i class="bi bi-person-x text-3xl mb-2"></i>
                    <p>No hay médicos asignados a este consultorio.</p>
                    <a href="{{ route('consultorios.horarios', $consultorio->id) }}" class="text-medical-600 text-sm hover:underline mt-2 inline-block">Asignar en Horarios</a>
                </div>
            @endif
        </div>

        <!-- Especialidades -->
        <div class="card p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                <i class="bi bi-bookmark text-info-600"></i>
                Especialidades Admitidas
            </h3>
            
            <div class="flex flex-wrap gap-2">
                @forelse($consultorio->especialidades as $especialidad)
                    <span class="badge bg-info-50 text-info-700 border border-info-100 px-3 py-1">
                        {{ $especialidad->nombre }}
                    </span>
                @empty
                    <p class="text-gray-500 text-sm italic">Sin especialidades asignadas.</p>
                @endforelse
            </div>
        </div>

        @if($consultorio->descripcion)
        <!-- Descripción / Observaciones -->
        <div class="card p-6 border-l-4 border-l-warning-500">
            <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                <i class="bi bi-chat-left-text text-warning-600"></i>
                Descripción
            </h3>
            <div class="bg-warning-50 rounded-lg p-4">
                <p class="text-gray-700 text-sm whitespace-pre-line">
                    {{ $consultorio->descripcion }}
                </p>
            </div>
        </div>
        @endif
    </div>

    <!-- Sidebar -->
    <div class="lg:col-span-1 space-y-6">
        <!-- Acciones Rápidas -->
        <div class="card p-6 sticky top-6">
            <h4 class="font-bold text-gray-900 mb-4">Acciones</h4>
            <div class="space-y-2">
                <!-- Buttons could be linked to actual routes if they existed, for now placeholders/existing ones -->
                <a href="{{ route('consultorios.horarios', $consultorio->id) }}" class="btn btn-outline w-full justify-start text-left">
                    <i class="bi bi-calendar-week mr-2"></i>
                    Gestionar Horario
                </a>
                <a href="{{ route('consultorios.edit', $consultorio->id) }}" class="btn btn-outline w-full justify-start text-left">
                    <i class="bi bi-pencil-square mr-2"></i>
                    Editar Información
                </a>
            </div>
        </div>

        <!-- Información de Contacto -->
        <div class="card p-6">
            <h4 class="font-bold text-gray-900 mb-4">Contacto</h4>
            <div class="space-y-3 text-sm">
                <div class="flex flex-col pb-3 border-b border-gray-100">
                    <span class="text-gray-500 mb-1">Teléfono</span>
                    <span class="font-medium text-gray-900 flex items-center gap-2">
                        <i class="bi bi-telephone text-medical-600"></i>
                        {{ $consultorio->telefono ?? 'No registrado' }}
                    </span>
                </div>
                <div class="flex flex-col">
                    <span class="text-gray-500 mb-1">Email</span>
                    <span class="font-medium text-gray-900 flex items-center gap-2">
                        <i class="bi bi-envelope text-medical-600"></i>
                        {{ $consultorio->email ?? 'No registrado' }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Ubicación -->
        <div class="card p-6 bg-gray-50">
            <h4 class="font-bold text-gray-900 mb-4">Ubicación</h4>
            <div class="space-y-2 text-sm">
                <p><strong>Estado:</strong> {{ $consultorio->estado->estado ?? 'N/A' }}</p>
                <p><strong>Ciudad:</strong> {{ $consultorio->ciudad->ciudad ?? 'N/A' }}</p>
                <p><strong>Municipio:</strong> {{ $consultorio->municipio->municipio ?? 'N/A' }}</p>
                <p><strong>Parroquia:</strong> {{ $consultorio->parroquia->parroquia ?? 'N/A' }}</p>
            </div>
        </div>
    </div>
</div>
@endsection
