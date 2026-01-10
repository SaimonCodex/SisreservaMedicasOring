@extends('layouts.admin')

@section('title', 'Horarios del Médico')

@section('content')
<div class="mb-6">
    <a href="{{ route('medicos.show', $medico->id) }}" class="text-medical-600 hover:text-medical-700 inline-flex items-center text-sm font-medium mb-3">
        <i class="bi bi-arrow-left mr-1"></i> Volver al Perfil
    </a>
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h2 class="text-3xl font-display font-bold text-gray-900">Horarios de Atención</h2>
            <p class="text-gray-500 mt-1">
                Dr. {{ $medico->primer_nombre }} {{ $medico->primer_apellido }} 
                @if($medico->especialidades->count() > 0)
                    - {{ $medico->especialidades->pluck('nombre')->implode(', ') }}
                @endif
            </p>
        </div>
        <button class="btn btn-primary" onclick="document.getElementById('horariosForm').submit()">
            <i class="bi bi-save mr-2"></i>
            Guardar Cambios
        </button>
    </div>
</div>

<form id="horariosForm" method="POST" action="{{ route('medicos.guardar-horario', $medico->id) }}">
    @csrf
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Configuración de Horarios -->
        <div class="lg:col-span-2 space-y-4">
            
            @php
                $diasSemana = [
                    'lunes' => 'Lunes', 
                    'martes' => 'Martes', 
                    'miercoles' => 'Miércoles', 
                    'jueves' => 'Jueves', 
                    'viernes' => 'Viernes', 
                    'sabado' => 'Sábado', 
                    'domingo' => 'Domingo'
                ];
                
                // Helper para organizar horarios por dia y turno para facil acceso en la vista
                $horariosMap = [];
                foreach($horarios as $h) {
                    // Normalizar clave de día (tildes)
                    $dayKey = strtolower(str_replace(['á','é','í','ó','ú','Á','É','Í','Ó','Ú'], ['a','e','i','o','u','A','E','I','O','U'], $h->dia_semana));
                    $horariosMap[$dayKey][$h->turno] = $h;
                }
            @endphp

            @foreach($diasSemana as $key => $diaLabel)
                @php
                    $hManana = $horariosMap[$key]['mañana'] ?? null;
                    $hTarde = $horariosMap[$key]['tarde'] ?? null;
                    $isActive = $hManana || $hTarde; 
                @endphp

                <div class="card p-6 hover:shadow-md transition-shadow" x-data="{ 
                    active: {{ $isActive ? 'true' : 'false' }},
                    manana_active: {{ $hManana ? 'true' : 'false' }},
                    tarde_active: {{ $hTarde ? 'true' : 'false' }} 
                }">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center gap-3">
                            <label class="flex items-center cursor-pointer">
                                <input type="checkbox" name="horarios[{{ $key }}][activo]" value="1" 
                                    class="form-checkbox text-medical-600 w-5 h-5" 
                                    x-model="active">
                            </label>
                            <h3 class="text-lg font-bold text-gray-900">{{ $diaLabel }}</h3>
                        </div>
                        <span class="badge" :class="active ? 'badge-success' : 'badge-gray'" x-text="active ? 'Activo' : 'Inactivo'"></span>
                    </div>
                    
                    <div x-show="active" x-transition class="space-y-4">
                        <!-- Turno Mañana -->
                        <div class="bg-blue-50/50 p-3 rounded-lg border border-blue-100">
                            <div class="flex items-center gap-2 mb-2">
                                <input type="checkbox" name="horarios[{{ $key }}][manana_activa]" value="1" 
                                    class="form-checkbox text-blue-600 rounded" 
                                    x-model="manana_active">
                                <span class="font-semibold text-blue-800 text-sm">Turno Mañana</span>
                            </div>
                            
                            <div x-show="manana_active" class="grid grid-cols-1 md:grid-cols-2 gap-3 pl-6">
                                <div class="col-span-1 md:col-span-2">
                                    <label class="form-label text-xs">Consultorio</label>
                                    <select name="horarios[{{ $key }}][manana_consultorio_id]" class="form-select text-sm h-9">
                                        <option value="">Seleccione...</option>
                                        @foreach($consultorios as $consultorio)
                                            <option value="{{ $consultorio->id }}" {{ ($hManana && $hManana->consultorio_id == $consultorio->id) ? 'selected' : '' }}>
                                                {{ $consultorio->nombre }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="form-label text-xs">Inicio</label>
                                    <input type="time" name="horarios[{{ $key }}][manana_inicio]" class="input text-sm h-9" 
                                        value="{{ $hManana ? \Carbon\Carbon::parse($hManana->horario_inicio)->format('H:i') : '08:00' }}">
                                </div>
                                <div>
                                    <label class="form-label text-xs">Fin</label>
                                    <input type="time" name="horarios[{{ $key }}][manana_fin]" class="input text-sm h-9" 
                                        value="{{ $hManana ? \Carbon\Carbon::parse($hManana->horario_fin)->format('H:i') : '12:00' }}">
                                </div>
                            </div>
                        </div>

                        <!-- Turno Tarde -->
                        <div class="bg-orange-50/50 p-3 rounded-lg border border-orange-100">
                            <div class="flex items-center gap-2 mb-2">
                                <input type="checkbox" name="horarios[{{ $key }}][tarde_activa]" value="1" 
                                    class="form-checkbox text-orange-600 rounded" 
                                    x-model="tarde_active">
                                <span class="font-semibold text-orange-800 text-sm">Turno Tarde</span>
                            </div>
                            
                            <div x-show="tarde_active" class="grid grid-cols-1 md:grid-cols-2 gap-3 pl-6">
                                <div class="col-span-1 md:col-span-2">
                                    <label class="form-label text-xs">Consultorio</label>
                                    <select name="horarios[{{ $key }}][tarde_consultorio_id]" class="form-select text-sm h-9">
                                        <option value="">Seleccione...</option>
                                        @foreach($consultorios as $consultorio)
                                            <option value="{{ $consultorio->id }}" {{ ($hTarde && $hTarde->consultorio_id == $consultorio->id) ? 'selected' : '' }}>
                                                {{ $consultorio->nombre }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="form-label text-xs">Inicio</label>
                                    <input type="time" name="horarios[{{ $key }}][tarde_inicio]" class="input text-sm h-9" 
                                        value="{{ $hTarde ? \Carbon\Carbon::parse($hTarde->horario_inicio)->format('H:i') : '14:00' }}">
                                </div>
                                <div>
                                    <label class="form-label text-xs">Fin</label>
                                    <input type="time" name="horarios[{{ $key }}][tarde_fin]" class="input text-sm h-9" 
                                        value="{{ $hTarde ? \Carbon\Carbon::parse($hTarde->horario_fin)->format('H:i') : '18:00' }}">
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div x-show="!active" class="mt-2">
                        <p class="text-sm text-gray-500 italic">No hay atención este día</p>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Resumen Semanal -->
            <div class="card p-6 sticky top-6">
                <h4 class="font-bold text-gray-900 mb-4">Resumen Semanal</h4>
                
                <div class="space-y-3 mb-6">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Días activos</span>
                        <span class="font-bold text-medical-600">5 de 7</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Horas semanales</span>
                        <span class="font-bold text-gray-900">36 hrs</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Cupos máximos/día</span>
                        <span class="font-bold text-success-600">~16 citas</span>
                    </div>
                </div>

                <div class="bg-medical-50 rounded-xl p-4 mb-4">
                    <p class="text-xs text-medical-700 font-medium mb-2">💡 Consejo</p>
                    <p class="text-xs text-gray-600">
                        Configure descansos de 15-30 min cada 4 horas para mantener la calidad de atención.
                    </p>
                </div>

                <button type="button" class="btn btn-outline w-full text-sm">
                    <i class="bi bi-clipboard mr-2"></i>
                    Copiar de semana anterior
                </button>
            </div>

            <!-- Plantillas Rápidas -->
            <div class="card p-6">
                <h4 class="font-bold text-gray-900 mb-4">Plantillas Rápidas</h4>
                <div class="space-y-2">
                    <button type="button" class="btn btn-sm btn-outline w-full justify-start">
                        <i class="bi bi-clock mr-2"></i>
                        Jornada Completa (8-12, 2-6)
                    </button>
                    <button type="button" class="btn btn-sm btn-outline w-full justify-start">
                        <i class="bi bi-sunrise mr-2"></i>
                        Solo Mañanas (8-12)
                    </button>
                    <button type="button" class="btn btn-sm btn-outline w-full justify-start">
                        <i class="bi bi-sunset mr-2"></i>
                        Solo Tardes (2-6)
                    </button>
                </div>
            </div>

            <!-- Vista Previa Calendario -->
            <div class="card p-6 bg-gradient-to-br from-medical-50 to-info-50">
                <h4 class="font-bold text-gray-900 mb-4">Disponibilidad</h4>
                <div class="space-y-2">
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-600">Lun-Mar</span>
                        <span class="text-xs badge badge-success">8 hrs/día</span>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-600">Miércoles</span>
                        <span class="text-xs badge badge-gray">Cerrado</span>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-600">Jueves</span>
                        <span class="text-xs badge badge-warning">4 hrs</span>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-600">Viernes</span>
                        <span class="text-xs badge badge-success">8 hrs</span>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-600">Fin de semana</span>
                        <span class="text-xs badge badge-gray">Cerrado</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection
