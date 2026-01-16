@extends('layouts.paciente')

@section('title', 'Mis Órdenes Médicas')

@section('content')
<div class="mb-6">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h2 class="text-3xl font-display font-bold text-gray-900">Mis Órdenes Médicas</h2>
            <p class="text-gray-500 mt-1">Recetas, exámenes, estudios y referencias médicas</p>
        </div>
        
        @if($solicitudesPendientes > 0)
            <a href="{{ route('paciente.ordenes.solicitudes') }}" class="btn btn-warning">
                <i class="bi bi-bell-fill mr-2"></i>
                {{ $solicitudesPendientes }} Solicitud(es) Pendiente(s)
            </a>
        @endif
    </div>
</div>

<!-- Filtros -->
<div class="card p-4 mb-6">
    <form action="{{ route('paciente.ordenes.index') }}" method="GET" class="flex flex-wrap items-end gap-4">
        {{-- Filtro de Paciente (solo si es representante) --}}
        @if(isset($esRepresentante) && $esRepresentante && isset($pacientesEspeciales) && $pacientesEspeciales->count() > 0)
            <div class="flex-1 min-w-[200px]">
                <label class="form-label">Paciente</label>
                <select name="filtro_paciente" class="form-select">
                    <option value="">Todos (Propias + Representados)</option>
                    <option value="propias" {{ request('filtro_paciente') == 'propias' ? 'selected' : '' }}>
                        Mis Órdenes (Propias)
                    </option>
                    @foreach($pacientesEspeciales as $pe)
                        <option value="{{ $pe->id }}" {{ request('filtro_paciente') == $pe->id ? 'selected' : '' }}>
                            {{ $pe->primer_nombre }} {{ $pe->primer_apellido }} 
                            ({{ $pe->tipo_documento }}-{{ $pe->numero_documento ?? 'S/D' }})
                        </option>
                    @endforeach
                </select>
            </div>
        @endif
        
        <div class="flex-1 min-w-[200px]">
            <label class="form-label">Tipo de Orden</label>
            <select name="tipo_orden" class="form-select">
                <option value="">Todas</option>
                <option value="Receta" {{ request('tipo_orden') == 'Receta' ? 'selected' : '' }}>
                    Recetas
                </option>
                <option value="Laboratorio" {{ request('tipo_orden') == 'Laboratorio' ? 'selected' : '' }}>
                    Laboratorio
                </option>
                <option value="Imagenologia" {{ request('tipo_orden') == 'Imagenologia' ? 'selected' : '' }}>
                    Imagenología
                </option>
                <option value="Referencia" {{ request('tipo_orden') == 'Referencia' ? 'selected' : '' }}>
                    Referencias
                </option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-filter mr-2"></i> Filtrar
        </button>
        <a href="{{ route('paciente.ordenes.index') }}" class="btn btn-outline">
            <i class="bi bi-x-lg"></i>
        </a>
    </form>
</div>

<!-- Resumen por tipo -->
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
    @php
        $recetas = $ordenes->where('tipo_orden', 'Receta')->count();
        $laboratorios = $ordenes->where('tipo_orden', 'Laboratorio')->count();
        $imagenes = $ordenes->where('tipo_orden', 'Imagenologia')->count();
        $referencias = $ordenes->where('tipo_orden', 'Referencia')->count();
    @endphp
    
    <div class="card p-4 border-l-4 border-green-500">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center">
                <i class="bi bi-capsule text-green-600"></i>
            </div>
            <div>
                <p class="text-2xl font-bold text-gray-900">{{ $recetas }}</p>
                <p class="text-sm text-gray-500">Recetas</p>
            </div>
        </div>
    </div>
    
    <div class="card p-4 border-l-4 border-blue-500">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center">
                <i class="bi bi-droplet text-blue-600"></i>
            </div>
            <div>
                <p class="text-2xl font-bold text-gray-900">{{ $laboratorios }}</p>
                <p class="text-sm text-gray-500">Laboratorios</p>
            </div>
        </div>
    </div>
    
    <div class="card p-4 border-l-4 border-orange-500">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-full bg-orange-100 flex items-center justify-center">
                <i class="bi bi-x-ray text-orange-600"></i>
            </div>
            <div>
                <p class="text-2xl font-bold text-gray-900">{{ $imagenes }}</p>
                <p class="text-sm text-gray-500">Imagenología</p>
            </div>
        </div>
    </div>
    
    <div class="card p-4 border-l-4 border-purple-500">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-full bg-purple-100 flex items-center justify-center">
                <i class="bi bi-person-badge text-purple-600"></i>
            </div>
            <div>
                <p class="text-2xl font-bold text-gray-900">{{ $referencias }}</p>
                <p class="text-sm text-gray-500">Referencias</p>
            </div>
        </div>
    </div>
</div>

<!-- Lista de órdenes -->
<div class="space-y-4">
    @forelse($ordenes as $orden)
        <div class="card p-0 overflow-hidden hover:shadow-lg transition-shadow">
            <div class="flex flex-col md:flex-row">
                <!-- Indicador de tipo -->
                <div class="w-full md:w-2 bg-{{ $orden->color_tipo }}-500"></div>
                
                <div class="flex-1 p-5">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 rounded-xl bg-{{ $orden->color_tipo }}-100 flex items-center justify-center">
                                <i class="bi {{ $orden->icono_tipo }} text-xl text-{{ $orden->color_tipo }}-600"></i>
                            </div>
                            <div>
                                <div class="flex items-center gap-2 flex-wrap">
                                    <span class="font-bold text-gray-900">{{ $orden->tipo_orden }}</span>
                                    <span class="text-xs text-gray-500">{{ $orden->codigo_orden }}</span>
                                    {{-- Indicador de Paciente Especial --}}
                                    @if($orden->paciente_especial_id)
                                        <span class="text-xs px-2 py-0.5 rounded-full bg-purple-100 text-purple-700">
                                            <i class="bi bi-person-heart mr-1"></i>Paciente Especial
                                        </span>
                                    @endif
                                </div>
                                {{-- Información del paciente --}}
                                @if($orden->pacienteEspecial)
                                    <p class="text-xs font-semibold text-purple-600 mt-1">
                                        Paciente: {{ $orden->pacienteEspecial->primer_nombre }} {{ $orden->pacienteEspecial->primer_apellido }}
                                        - {{ $orden->pacienteEspecial->tipo_documento }}-{{ $orden->pacienteEspecial->numero_documento ?? 'S/D' }}
                                    </p>
                                @endif
                                <p class="text-sm text-gray-600 mt-1">
                                    <i class="bi bi-person-badge mr-1"></i>
                                    Dr. {{ $orden->medico->primer_nombre ?? '' }} {{ $orden->medico->primer_apellido ?? '' }}
                                    @if($orden->especialidad)
                                        • {{ $orden->especialidad->nombre }}
                                    @endif
                                </p>
                                @if($orden->cita && $orden->cita->consultorio)
                                    <p class="text-xs text-gray-500 mt-1">
                                        <i class="bi bi-building mr-1"></i>
                                        {{ $orden->cita->consultorio->nombre }}
                                    </p>
                                @endif
                                <p class="text-xs text-gray-500 mt-1">
                                    <i class="bi bi-calendar3 mr-1"></i>
                                    {{ $orden->fecha_emision->format('d/m/Y') }}
                                    @if($orden->fecha_vigencia)
                                        <span class="ml-2">
                                            <i class="bi bi-clock mr-1"></i>
                                            Vigente hasta: {{ $orden->fecha_vigencia->format('d/m/Y') }}
                                        </span>
                                    @endif
                                </p>
                            </div>
                        </div>
                        
                        <div class="flex items-center gap-3">
                            <!-- Items count -->
                            <div class="text-center px-3 py-1 bg-gray-100 rounded-lg">
                                <p class="text-lg font-bold text-gray-900">{{ $orden->total_items }}</p>
                                <p class="text-xs text-gray-500">item(s)</p>
                            </div>
                            
                            <!-- Actions -->
                            <a href="{{ route('paciente.ordenes.show', $orden->id) }}" 
                               class="btn btn-sm btn-outline">
                                <i class="bi bi-eye mr-1"></i> Ver
                            </a>
                        </div>
                    </div>

                    <!-- Preview de items -->
                    @if($orden->medicamentos->count() > 0)
                        <div class="mt-3 pt-3 border-t border-gray-100">
                            <p class="text-xs text-gray-500 mb-2">Medicamentos:</p>
                            <div class="flex flex-wrap gap-2">
                                @foreach($orden->medicamentos->take(3) as $med)
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs bg-green-50 text-green-700">
                                        <i class="bi bi-capsule mr-1"></i>
                                        {{ Str::limit($med->medicamento, 20) }}
                                    </span>
                                @endforeach
                                @if($orden->medicamentos->count() > 3)
                                    <span class="text-xs text-gray-500">+{{ $orden->medicamentos->count() - 3 }} más</span>
                                @endif
                            </div>
                        </div>
                    @endif

                    @if($orden->examenes->count() > 0)
                        <div class="mt-3 pt-3 border-t border-gray-100">
                            <p class="text-xs text-gray-500 mb-2">Exámenes:</p>
                            <div class="flex flex-wrap gap-2">
                                @foreach($orden->examenes->take(3) as $exam)
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs bg-blue-50 text-blue-700">
                                        <i class="bi bi-droplet mr-1"></i>
                                        {{ Str::limit($exam->nombre_examen, 20) }}
                                    </span>
                                @endforeach
                                @if($orden->examenes->count() > 3)
                                    <span class="text-xs text-gray-500">+{{ $orden->examenes->count() - 3 }} más</span>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @empty
        <div class="card p-12 text-center">
            <div class="w-20 h-20 mx-auto rounded-full bg-gray-100 flex items-center justify-center mb-4">
                <i class="bi bi-file-medical text-4xl text-gray-400"></i>
            </div>
            <h3 class="text-lg font-bold text-gray-900 mb-2">No tiene órdenes médicas</h3>
            <p class="text-gray-500">Las recetas, exámenes y referencias de sus consultas aparecerán aquí.</p>
        </div>
    @endforelse
</div>

<!-- Paginación -->
@if($ordenes->hasPages())
    <div class="mt-6">
        {{ $ordenes->withQueryString()->links('vendor.pagination.medical') }}
    </div>
@endif
@endsection
