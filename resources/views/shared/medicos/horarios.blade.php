@extends('layouts.admin')

@section('title', 'Horarios del Médico')

@section('content')
<script>
    // 1. Data securely in JS (avoids HTML attribute quoting issues)
    window.globalConsultorioRules = @json($consultorios->mapWithKeys(function($c) { 
        return [$c->id => $c->especialidades->pluck('id')]; 
    }));

    // 2. Component Logic Factory
    window.makeScheduleCard = function(hManana, hTarde) {
        return {
            editing: false,
            manana_active: hManana,
            tarde_active: hTarde,
            
            get active() { 
                return this.manana_active || this.tarde_active; 
            },

            init() {
                // Initialize filters
                this.$nextTick(() => {
                    if (this.manana_active) this.applyFilter('manana');
                    if (this.tarde_active) this.applyFilter('tarde');
                });
            },

            applyFilter(shift) {
                const refConsultorio = this.$refs[shift + 'Consultorio'];
                const refEspecialidad = this.$refs[shift + 'Especialidad'];
                const refMsg = this.$refs[shift + 'Msg'];

                if (!refConsultorio || !refEspecialidad) return;

                const consultorioId = refConsultorio.value;
                const options = refEspecialidad.querySelectorAll('option[data-esp-id]');

                // No consultorio? Show all.
                if (!consultorioId) {
                    options.forEach(o => o.style.display = '');
                    if(refMsg) refMsg.classList.add('hidden');
                    return;
                }

                // Get rules from global scope
                const allowed = window.globalConsultorioRules[consultorioId] || [];
                let count = 0;

                options.forEach(o => {
                    const id = parseInt(o.getAttribute('data-esp-id'));
                    if (allowed.includes(id)) {
                        o.style.display = '';
                        count++;
                    } else {
                        o.style.display = 'none';
                        // If invalid selection, reset it
                        if (o.selected) {
                            o.selected = false;
                            refEspecialidad.value = '';
                        }
                    }
                });

                // Feedback
                if (refMsg) {
                    if (count < options.length) {
                        refMsg.textContent = `Solo ${count} especialidad(es) compatible(s)`;
                        refMsg.classList.remove('hidden');
                    } else {
                        refMsg.classList.add('hidden');
                    }
                }
            }
        };
    };
</script>

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
            @endphp

            @foreach($diasSemana as $key => $diaLabel)
                @php
                    // Match by containing string (case-insensitive) to handle accents/encoding
                    $dayRecords = $horarios->filter(function($h) use ($key, $diaLabel) {
                        return stripos($h->dia_semana, $key) !== false 
                            || stripos($h->dia_semana, $diaLabel) !== false;
                    });

                    // Match turno
                    $hManana = $dayRecords->first(function($h) {
                        return stripos($h->turno, 'm') === 0;
                    });
                    
                    $hTarde = $dayRecords->first(function($h) {
                        return stripos($h->turno, 't') === 0;
                    });

                    $isActive = $hManana || $hTarde; 
                @endphp
                
                @php
                    // Helper to check if day has any schedule
                    $hasSchedule = $hManana || $hTarde;
                @endphp

                <div class="card p-0 overflow-hidden hover:shadow-lg transition-shadow border border-gray-100 mb-4" 
                     x-data="makeScheduleCard({{ $hManana ? 'true' : 'false' }}, {{ $hTarde ? 'true' : 'false' }})">
                     
                    <!-- Hidden Input for Active State (Calculated) -->
                    <input type="hidden" name="horarios[{{ $key }}][activo]" 
                           value="{{ $isActive ? 1 : 0 }}" 
                           x-bind:value="active ? 1 : 0">
                           
                    <!-- Hidden Map for Shifts (Guarantees submission even if toggle is unchecked/UI hidden) -->
                    <input type="hidden" name="horarios[{{ $key }}][manana_activa]" 
                           value="{{ $hManana ? 1 : 0 }}" 
                           x-bind:value="manana_active ? 1 : 0">
                           
                    <input type="hidden" name="horarios[{{ $key }}][tarde_activa]" 
                           value="{{ $hTarde ? 1 : 0 }}" 
                           x-bind:value="tarde_active ? 1 : 0">

                    <!-- HEADER -->
                    <div class="bg-gray-50 px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-white shadow-sm"
                                 :class="active ? 'bg-medical-500' : 'bg-gray-300'">
                                {{ strtoupper(substr($diaLabel, 0, 1)) }}
                            </div>
                            <h3 class="text-lg font-bold text-gray-800">{{ $diaLabel }}</h3>
                        </div>
                        
                        <!-- Actions -->
                        <div>
                            <template x-if="!editing && active">
                                <button type="button" @click="editing = true" class="text-sm text-medical-600 hover:text-medical-800 font-medium flex items-center transition-colors">
                                    <i class="bi bi-pencil-square mr-1"></i> Editar
                                </button>
                            </template>
                            
                            <template x-if="editing">
                                <button type="button" @click="editing = false" class="text-sm text-gray-500 hover:text-gray-700 font-medium flex items-center transition-colors">
                                    <i class="bi bi-check2-circle mr-1"></i> Listo
                                </button>
                            </template>
                        </div>
                    </div>

                    <!-- CONTENT BODY -->
                    <div class="p-6">
                        
                        <!-- STATE 1: SUMMARY (Saved & Not Editing) -->
                        <div x-show="!editing && active" class="space-y-4">
                            @if($hManana)
                                <div class="flex items-start gap-3 p-3 rounded-lg bg-blue-50/50 border border-blue-100">
                                    <div class="bg-blue-100 text-blue-600 p-2 rounded-md">
                                        <i class="bi bi-sun-fill"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold text-blue-900">Mañana ({{ \Carbon\Carbon::parse($hManana->horario_inicio)->format('H:i') }} - {{ \Carbon\Carbon::parse($hManana->horario_fin)->format('H:i') }})</p>
                                        <p class="text-xs text-blue-700 mt-1">
                                            {{ $hManana->consultorio->nombre ?? 'Sin Consultorio' }} • {{ $hManana->especialidad->nombre ?? 'Sin Especialidad' }}
                                        </p>
                                    </div>
                                </div>
                            @endif

                            @if($hTarde)
                                <div class="flex items-start gap-3 p-3 rounded-lg bg-orange-50/50 border border-orange-100">
                                    <div class="bg-orange-100 text-orange-600 p-2 rounded-md">
                                        <i class="bi bi-sunset-fill"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold text-orange-900">Tarde ({{ \Carbon\Carbon::parse($hTarde->horario_inicio)->format('H:i') }} - {{ \Carbon\Carbon::parse($hTarde->horario_fin)->format('H:i') }})</p>
                                        <p class="text-xs text-orange-700 mt-1">
                                            {{ $hTarde->consultorio->nombre ?? 'Sin Consultorio' }} • {{ $hTarde->especialidad->nombre ?? 'Sin Especialidad' }}
                                        </p>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- STATE 2: EMPTY (Not Active & Not Editing) -->
                        <div x-show="!editing && !active" class="text-center py-4">
                            <p class="text-gray-400 text-sm mb-3">No hay horario asignado para este día</p>
                            <button type="button" @click="editing = true; manana_active = true" class="btn btn-outline-primary btn-sm rounded-full px-4">
                                <i class="bi bi-plus-lg mr-1"></i> Asignar Horario
                            </button>
                        </div>

                        <!-- STATE 3: EDITING FORM -->
                        <div x-show="editing" x-transition class="space-y-6">
                            
                            <!-- Turno Mañana Toggle -->
                            <div class="border-l-4 border-blue-500 pl-4">
                                <label class="flex items-center gap-2 mb-3 cursor-pointer">
                                    <div class="relative inline-block w-10 mr-2 align-middle select-none transition duration-200 ease-in">
                                        <input type="checkbox" name="horarios[{{ $key }}][manana_activa]" class="toggle-checkbox absolute block w-5 h-5 rounded-full bg-white border-4 appearance-none cursor-pointer" 
                                            value="1" x-model="manana_active" 
                                            :class="{'right-0 border-blue-600': manana_active, 'right-auto border-gray-300': !manana_active}"/>
                                        <div class="toggle-label block overflow-hidden h-5 rounded-full cursor-pointer"
                                            :class="{'bg-blue-600': manana_active, 'bg-gray-300': !manana_active}"></div>
                                    </div>
                                    <span class="font-bold text-gray-700">Turno Mañana</span>
                                </label>

                                <div x-show="manana_active" class="grid grid-cols-1 md:grid-cols-2 gap-3 animate-fade-in-down">
                                    <div>
                                        <label class="form-label text-xs">Consultorio</label>
                                        <select name="horarios[{{ $key }}][manana_consultorio_id]" class="form-select text-sm"
                                                x-ref="mananaConsultorio"
                                                @change="applyFilter('manana')">
                                            <option value="">Seleccione...</option>
                                            @foreach($consultorios as $consultorio)
                                                <option value="{{ $consultorio->id }}" {{ ($hManana && $hManana->consultorio_id == $consultorio->id) ? 'selected' : '' }}>
                                                    {{ $consultorio->nombre }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <label class="form-label text-xs">Especialidad</label>
                                        <select name="horarios[{{ $key }}][manana_especialidad_id]" class="form-select text-sm" x-ref="mananaEspecialidad">
                                            <option value="">Seleccione...</option>
                                            @foreach($medico->especialidades as $especialidad)
                                                <option value="{{ $especialidad->id }}" data-esp-id="{{ $especialidad->id }}" {{ ($hManana && $hManana->especialidad_id == $especialidad->id) ? 'selected' : '' }}>
                                                    {{ $especialidad->nombre }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <p class="text-xs text-orange-600 mt-1 hidden" x-ref="mananaMsg">Filtrado por consultorio</p>
                                    </div>
                                    <div>
                                        <label class="form-label text-xs">Inicio</label>
                                        <input type="time" name="horarios[{{ $key }}][manana_inicio]" class="input text-sm" 
                                            value="{{ $hManana ? \Carbon\Carbon::parse($hManana->horario_inicio)->format('H:i') : '08:00' }}">
                                    </div>
                                    <div>
                                        <label class="form-label text-xs">Fin</label>
                                        <input type="time" name="horarios[{{ $key }}][manana_fin]" class="input text-sm" 
                                            value="{{ $hManana ? \Carbon\Carbon::parse($hManana->horario_fin)->format('H:i') : '12:00' }}">
                                    </div>
                                </div>
                            </div>

                            <!-- Turno Tarde Toggle -->
                            <div class="border-l-4 border-orange-500 pl-4">
                                <label class="flex items-center gap-2 mb-3 cursor-pointer">
                                    <div class="relative inline-block w-10 mr-2 align-middle select-none transition duration-200 ease-in">
                                        <input type="checkbox" name="horarios[{{ $key }}][tarde_activa]" class="toggle-checkbox absolute block w-5 h-5 rounded-full bg-white border-4 appearance-none cursor-pointer" 
                                            value="1" x-model="tarde_active"
                                            :class="{'right-0 border-orange-600': tarde_active, 'right-auto border-gray-300': !tarde_active}"/>
                                        <div class="toggle-label block overflow-hidden h-5 rounded-full cursor-pointer"
                                            :class="{'bg-orange-600': tarde_active, 'bg-gray-300': !tarde_active}"></div>
                                    </div>
                                    <span class="font-bold text-gray-700">Turno Tarde</span>
                                </label>
                                
                                <div x-show="tarde_active" class="grid grid-cols-1 md:grid-cols-2 gap-3 animate-fade-in-down">
                                    <div>
                                        <label class="form-label text-xs">Consultorio</label>
                                        <select name="horarios[{{ $key }}][tarde_consultorio_id]" class="form-select text-sm"
                                                x-ref="tardeConsultorio"
                                                @change="applyFilter('tarde')">
                                            <option value="">Seleccione...</option>
                                            @foreach($consultorios as $consultorio)
                                                <option value="{{ $consultorio->id }}" {{ ($hTarde && $hTarde->consultorio_id == $consultorio->id) ? 'selected' : '' }}>
                                                    {{ $consultorio->nombre }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <label class="form-label text-xs">Especialidad</label>
                                        <select name="horarios[{{ $key }}][tarde_especialidad_id]" class="form-select text-sm" x-ref="tardeEspecialidad">
                                            <option value="">Seleccione...</option>
                                            @foreach($medico->especialidades as $especialidad)
                                                <option value="{{ $especialidad->id }}" data-esp-id="{{ $especialidad->id }}" {{ ($hTarde && $hTarde->especialidad_id == $especialidad->id) ? 'selected' : '' }}>
                                                    {{ $especialidad->nombre }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <p class="text-xs text-orange-600 mt-1 hidden" x-ref="tardeMsg">Filtrado por consultorio</p>
                                    </div>
                                    <div>
                                        <label class="form-label text-xs">Inicio</label>
                                        <input type="time" name="horarios[{{ $key }}][tarde_inicio]" class="input text-sm" 
                                            value="{{ $hTarde ? \Carbon\Carbon::parse($hTarde->horario_inicio)->format('H:i') : '14:00' }}">
                                    </div>
                                    <div>
                                        <label class="form-label text-xs">Fin</label>
                                        <input type="time" name="horarios[{{ $key }}][tarde_fin]" class="input text-sm" 
                                            value="{{ $hTarde ? \Carbon\Carbon::parse($hTarde->horario_fin)->format('H:i') : '18:00' }}">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="text-right">
                                <button type="button" @click="editing = false" class="text-xs text-gray-400 hover:text-gray-600 underline">
                                    Ocultar editor
                                </button>
                            </div>
                        </div>

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
