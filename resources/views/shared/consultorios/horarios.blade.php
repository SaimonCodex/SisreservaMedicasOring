@extends('layouts.admin')

@section('title', 'Ocupación del Consultorio')

@section('content')
<div class="mb-6">
    <a href="{{ route('consultorios.show', $consultorio->id) }}" class="text-medical-600 hover:text-medical-700 inline-flex items-center text-sm font-medium mb-3">
        <i class="bi bi-arrow-left mr-1"></i> Volver al Consultorio
    </a>
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h2 class="text-3xl font-display font-bold text-gray-900">Ocupación - {{ $consultorio->nombre }}</h2>
            <p class="text-gray-500 mt-1">Vista de asignación de médicos por turno</p>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @php
        $dias = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'];
    @endphp

    @foreach($dias as $dia)
    <div class="card p-0 overflow-hidden">
        <div class="bg-gray-50 p-4 border-b border-gray-100 flex justify-between items-center">
            <h3 class="font-bold text-gray-900">{{ $dia }}</h3>
            @if($loop->iteration > 5)
                <span class="badge bg-gray-200 text-gray-500 text-xs">Fin de sem.</span>
            @endif
        </div>
        
        <div class="p-4 space-y-4">
            <!-- Turno Mañana -->
            <div class="border rounded-lg p-3 {{ $horarios->where('dia', $dia)->where('turno', 'Mañana')->count() > 0 ? 'bg-success-50 border-success-200' : 'bg-white border-dashed' }}">
                <div class="flex items-center gap-2 mb-2">
                    <i class="bi bi-sun text-warning-500"></i>
                    <span class="text-sm font-semibold text-gray-700">Mañana (08:00 - 12:00)</span>
                </div>
                
                @php
                    $asignacionManana = $horarios->where('dia', $dia)->where('turno', 'Mañana')->first();
                @endphp

                @if($asignacionManana)
                     <div class="flex items-center gap-3 mt-2">
                        <div class="w-8 h-8 rounded-full bg-medical-100 text-medical-600 flex items-center justify-center font-bold text-xs">
                             {{ substr($asignacionManana->medico->primer_nombre, 0, 1) }}{{ substr($asignacionManana->medico->primer_apellido, 0, 1) }}
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">Dr. {{ $asignacionManana->medico->primer_nombre }} {{ $asignacionManana->medico->primer_apellido }}</p>
                            <p class="text-xs text-gray-500">{{ $asignacionManana->medico->especialidad->nombre ?? '' }}</p>
                        </div>
                    </div>
                @else
                    <p class="text-xs text-gray-400 italic mt-1">Disponible</p>
                @endif
            </div>

            <!-- Turno Tarde -->
            <div class="border rounded-lg p-3 {{ $horarios->where('dia', $dia)->where('turno', 'Tarde')->count() > 0 ? 'bg-info-50 border-info-200' : 'bg-white border-dashed' }}">
                <div class="flex items-center gap-2 mb-2">
                    <i class="bi bi-moon-stars text-info-500"></i>
                    <span class="text-sm font-semibold text-gray-700">Tarde (13:00 - 17:00)</span>
                </div>

                @php
                    $asignacionTarde = $horarios->where('dia', $dia)->where('turno', 'Tarde')->first();
                @endphp

                @if($asignacionTarde)
                     <div class="flex items-center gap-3 mt-2">
                        <div class="w-8 h-8 rounded-full bg-medical-100 text-medical-600 flex items-center justify-center font-bold text-xs">
                             {{ substr($asignacionTarde->medico->primer_nombre, 0, 1) }}{{ substr($asignacionTarde->medico->primer_apellido, 0, 1) }}
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">Dr. {{ $asignacionTarde->medico->primer_nombre }} {{ $asignacionTarde->medico->primer_apellido }}</p>
                            <p class="text-xs text-gray-500">{{ $asignacionTarde->medico->especialidad->nombre ?? '' }}</p>
                        </div>
                    </div>
                @else
                    <p class="text-xs text-gray-400 italic mt-1">Disponible</p>
                @endif
            </div>
        </div>
    </div>
    @endforeach
</div>

<div class="card p-6 mt-6 bg-info-50 border-info-200">
    <div class="flex items-start gap-4">
        <div class="p-3 bg-white rounded-full text-info-600 shadow-sm">
            <i class="bi bi-info-lg"></i>
        </div>
        <div>
            <h4 class="font-bold text-info-900">Gestión de Horarios</h4>
            <p class="text-sm text-info-800 mt-1">
                La asignación de este consultorio se realiza desde el perfil de cada médico. 
                Para ocupar un bloque horario, vaya a <a href="{{ route('medicos.index') }}" class="underline font-medium hover:text-info-900">Médicos</a>, seleccione el doctor y configure su disponibilidad en la pestaña "Horarios".
            </p>
        </div>
    </div>
</div>
@endsection
@endsection
