@extends('layouts.admin')

@section('title', 'Editar Cita')

@section('content')
<div class="mb-6">
    <a href="{{ route('citas.show', $cita->id) }}" class="text-medical-600 hover:text-medical-700 inline-flex items-center text-sm font-medium mb-3">
        <i class="bi bi-arrow-left mr-1"></i> Volver al Detalle
    </a>
    <h2 class="text-3xl font-display font-bold text-gray-900">Editar Cita #{{ $cita->id }}</h2>
    <p class="text-gray-500 mt-1">Reprogramar o modificar la cita médica</p>
</div>

<form method="POST" action="{{ route('citas.update', $cita->id) }}">
    @csrf
    @method('PUT')
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Formulario Principal -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Información del Paciente (No editable) -->
            <div class="card p-6 border-l-4 border-l-success-500">
                <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <i class="bi bi-person text-success-600"></i>
                    Paciente
                </h3>
                
                <div class="bg-success-50 border border-success-200 rounded-xl p-4">
                    <div class="flex items-center gap-4">
                        <div class="w-16 h-16 rounded-full bg-gradient-to-br from-success-500 to-success-600 flex items-center justify-center text-white text-xl font-bold">
                            {{ substr($cita->paciente->primer_nombre ?? 'N', 0, 1) }}{{ substr($cita->paciente->primer_apellido ?? 'A', 0, 1) }}
                        </div>
                        <div class="flex-1">
                            <h4 class="font-bold text-gray-900">{{ $cita->paciente->primer_nombre ?? 'N/A' }} {{ $cita->paciente->primer_apellido ?? '' }}</h4>
                            <p class="text-sm text-gray-600">
                                {{ $cita->paciente->tipo_documento ?? '-' }}-{{ $cita->paciente->numero_documento ?? 'N/A' }} 
                                @if($cita->paciente && $cita->paciente->historiaClinica)
                                • HC: {{ $cita->paciente->historiaClinica->numero_historia }}
                                @endif
                            </p>
                            <p class="text-xs text-gray-500 mt-1">
                                {{ $cita->paciente ? \Carbon\Carbon::parse($cita->paciente->fecha_nac)->age : 'N/A' }} años • {{ $cita->paciente->genero ?? 'N/A' }}
                            </p>
                        </div>
                    </div>
                </div>
                
                @if($cita->pacienteEspecial)
                <div class="mt-3 bg-purple-50 border border-purple-200 rounded-xl p-4">
                     <p class="text-sm text-purple-800 font-bold mb-1">Paciente Especial (Tercero):</p>
                     <p class="text-sm text-gray-700">{{ $cita->pacienteEspecial->primer_nombre }} {{ $cita->pacienteEspecial->primer_apellido }}</p>
                </div>
                @endif

                <input type="hidden" name="paciente_id" value="{{ $cita->paciente_id }}">
                <p class="text-xs text-gray-500 mt-2">
                    <i class="bi bi-info-circle mr-1"></i>
                    El paciente no puede ser modificado en esta vista.
                </p>
            </div>

            <!-- Médico y Especialidad -->
            <div class="card p-6 border-l-4 border-l-medical-500">
                <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                    <i class="bi bi-person-badge text-medical-600"></i>
                    Médico y Especialidad
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                     <div class="form-group md:col-span-2">
                        <label for="consultorio_id" class="form-label">Consultorio</label>
                        <select id="consultorio_id" name="consultorio_id" class="form-select" onchange="cargarEspecialidades();">
                            @foreach($consultorios as $consultorio)
                            <option value="{{ $consultorio->id }}" {{ $cita->consultorio_id == $consultorio->id ? 'selected' : '' }}>
                                {{ $consultorio->nombre }} ({{ $consultorio->ubicacion ?? 'Piso ' . $consultorio->piso }})
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="especialidad_id" class="form-label form-label-required">Especialidad</label>
                        <select id="especialidad_id" name="especialidad_id" class="form-select" required onchange="cargarMedicos();">
                            @foreach($especialidades as $esp)
                            <option value="{{ $esp->id }}" {{ $cita->especialidad_id == $esp->id ? 'selected' : '' }}>{{ $esp->nombre }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="medico_id" class="form-label form-label-required">Médico</label>
                        <select id="medico_id" name="medico_id" class="form-select" required onchange="cargarHorarios();">
                            @foreach($medicos as $med)
                            <option value="{{ $med->id }}" {{ $cita->medico_id == $med->id ? 'selected' : '' }}>
                                Dr. {{ $med->primer_nombre }} {{ $med->primer_apellido }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <!-- Fecha y Hora -->
            <div class="card p-6 border-l-4 border-l-warning-500">
                <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                    <i class="bi bi-calendar-event text-warning-600"></i>
                    Fecha y Hora
                </h3>
                
                <div class="bg-warning-50 border border-warning-200 rounded-xl p-4 mb-4">
                    <p class="text-sm text-warning-800 flex items-center gap-2">
                        <i class="bi bi-info-circle-fill"></i>
                        <strong>Horario Actual:</strong> {{ \Carbon\Carbon::parse($cita->fecha_cita)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($cita->hora_inicio)->format('h:i A') }}
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="form-group">
                        <label for="fecha_cita" class="form-label form-label-required">Nueva Fecha</label>
                        <input type="date" id="fecha_cita" name="fecha_cita" class="input" value="{{ $cita->fecha_cita }}" required min="{{ date('Y-m-d') }}" onchange="cargarHorarios()">
                    </div>

                    <div class="form-group"> <!-- Hidden input for hora_inicio, filled by JS -->
                        <label class="form-label form-label-required">Hora de Inicio</label>
                        <input type="time" id="hora_inicio" name="hora_inicio" class="input bg-gray-50" value="{{ $cita->hora_inicio }}" readonly required>
                        <p class="text-xs text-gray-500 mt-1">Seleccione una hora disponible abajo.</p>
                    </div>
                    
                    <div class="form-group md:col-span-2">
                         <label class="form-label">Horarios Disponibles</label>
                         <div id="horarios-container" class="grid grid-cols-4 sm:grid-cols-6 gap-2 mt-2 p-4 bg-gray-50 rounded-lg border border-gray-200 max-h-48 overflow-y-auto">
                             <p class="col-span-full text-center text-gray-500 text-sm">Cargando disponibilidad...</p>
                         </div>
                    </div>

                    <div class="form-group md:col-span-2 hidden"> <!-- Auto-calculated usually -->
                         <input type="hidden" name="hora_fin" id="hora_fin" value="{{ $cita->hora_fin }}">
                         <input type="hidden" name="tipo_consulta" value="Consultorio"> <!-- Default for now -->
                    </div>
                </div>
            </div>

            <!-- Estado de la Cita -->
            <div class="card p-6 border-l-4 border-l-info-500">
                <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                    <i class="bi bi-flag text-info-600"></i>
                    Estado de la Cita
                </h3>
                
                <div class="form-group">
                    <label for="estado_cita" class="form-label form-label-required">Estado</label>
                    <select id="estado_cita" name="estado_cita" class="form-select" required>
                        <option value="Programada" {{ $cita->estado_cita == 'Programada' ? 'selected' : '' }}>Programada</option>
                        <option value="Confirmada" {{ $cita->estado_cita == 'Confirmada' ? 'selected' : '' }}>Confirmada</option>
                        <option value="En Progreso" {{ $cita->estado_cita == 'En Progreso' ? 'selected' : '' }}>En Progreso</option>
                        <option value="Completada" {{ $cita->estado_cita == 'Completada' ? 'selected' : '' }}>Completada</option>
                        <option value="Cancelada" {{ $cita->estado_cita == 'Cancelada' ? 'selected' : '' }}>Cancelada</option>
                        <option value="No Asistió" {{ $cita->estado_cita == 'No Asistió' ? 'selected' : '' }}>No Asistió</option>
                    </select>
                </div>
            </div>

            <!-- Motivo y Observaciones -->
            <div class="card p-6 border-l-4 border-l-success-500">
                <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                    <i class="bi bi-chat-left-text text-success-600"></i>
                    Detalles de la Cita
                </h3>
                
                <div class="grid grid-cols-1 gap-4">
                    <div class="form-group">
                        <label for="motivo" class="form-label">Motivo de la Consulta</label>
                        <textarea id="motivo" name="motivo" rows="2" class="form-textarea">{{ $cita->motivo }}</textarea>
                    </div>

                    <div class="form-group">
                        <label for="observaciones" class="form-label">Observaciones / Notas (Visible en Lista)</label>
                        <textarea id="observaciones" name="observaciones" rows="3" class="form-textarea">{{ $cita->observaciones }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Acciones -->
            <div class="card p-6 sticky top-6">
                <button type="submit" class="btn btn-primary w-full shadow-lg mb-3">
                    <i class="bi bi-save mr-2"></i>
                    Guardar Cambios
                </button>
                <a href="{{ route('citas.show', $cita->id) }}" class="btn btn-outline w-full mb-3">
                    <i class="bi bi-x-lg mr-2"></i>
                    Cancelar
                </a>
            </div>
        </div>
    </div>
</form>

@push('scripts')
<script>
    const BASE_URL = '{{ url("") }}';
    
    // Carga inicial
    document.addEventListener('DOMContentLoaded', () => {
        cargarHorarios();
    });

    async function cargarHorarios() {
        const medicoId = document.getElementById('medico_id').value;
        const fecha = document.getElementById('fecha_cita').value;
        const consultorioId = document.getElementById('consultorio_id').value;
        const container = document.getElementById('horarios-container');
        const horaActual = document.getElementById('hora_inicio').value;
        
        if (!medicoId || !fecha) return;
        
        container.innerHTML = '<p class="col-span-full text-center text-gray-500 text-sm">Cargando...</p>';
        
        try {
            const response = await fetch(`${BASE_URL}/ajax/citas/horarios-disponibles?medico_id=${medicoId}&fecha=${fecha}&consultorio_id=${consultorioId}&exclude_cita_id={{ $cita->id }}`);
            const data = await response.json();
            
            container.innerHTML = '';
            
            if (!data.disponible || !data.horarios || data.horarios.length === 0) {
                container.innerHTML = '<p class="col-span-full text-center text-gray-500 text-sm">No hay horarios disponibles</p>';
                return;
            }
            
            // Si la hora actual de la cita YA pasó o está ocupada por OTRAS citas, 
            // igual debemos permitir verla/seleccionarla si no la hemos cambiado.
            // Pero la API de horarios disponibles probablemente la marque como ocupada si "excluir_cita_id" no se pasa.
            // TODO: Podríamos mejorar el backend para excluir esta cita de la validación de ocupado.
            
            let foundCurrent = false;

            data.horarios.forEach(h => {
                // Hack rápido: Para editar, si la hora coincide con la actual del input, la marcamos disponible visualmente
                const isCurrent = (h.hora + ':00' === horaActual + ':00') || (h.hora === horaActual);
                if (isCurrent) foundCurrent = true;

                const btn = document.createElement('button');
                btn.type = 'button';
                // Si es la actual, display selected
                const selectedClass = isCurrent ? 'border-blue-600 bg-blue-600 text-white shadow-md' : '';
                
                btn.className = `p-2 text-sm rounded border transition-colors text-center ${selectedClass} ` + 
                    (h.ocupada && !isCurrent 
                        ? 'bg-gray-100 text-gray-400 cursor-not-allowed' 
                        : 'hover:border-blue-500 hover:bg-blue-50 cursor-pointer border-gray-200');
                
                btn.textContent = h.hora;
                btn.disabled = h.ocupada && !isCurrent;
                
                if (!btn.disabled) {
                    btn.onclick = () => {
                        document.getElementById('hora_inicio').value = h.hora;
                        // Calcular fin
                        const [hours, mins] = h.hora.split(':');
                        const d = new Date();
                        d.setHours(parseInt(hours), parseInt(mins) + 30); // Default 30 min
                        const fin = d.getHours().toString().padStart(2, '0') + ':' + d.getMinutes().toString().padStart(2, '0');
                        document.getElementById('hora_fin').value = fin;

                        // Visual update
                        document.querySelectorAll('#horarios-container button').forEach(b => {
                            b.className = 'p-2 text-sm rounded border transition-colors text-center hover:border-blue-500 hover:bg-blue-50 cursor-pointer border-gray-200';
                        });
                        btn.className = 'p-2 text-sm rounded border transition-colors text-center border-blue-600 bg-blue-600 text-white shadow-md';
                    };
                }
                
                container.appendChild(btn);
            });
            
            // Si la hora actual no vino en el array (ej. ya pasó), agreguémosla manualmente para que no se pierda visualmente
            // (Solo visual, la validación backend dirá si se puede)
             if (!foundCurrent && horaActual) {
                 // Nota: Esto es opcional, depende de si queremos obligar a cambiarla.
             }

        } catch (error) {
            console.error(error);
            container.innerHTML = '<p class="col-span-full text-center text-red-500 text-sm">Error cargando horarios</p>';
        }
    }

    // Funciones placeholders para cargar selects (si se cambia consultorio/especialidad)
    // Se requeriría implementar similar a create si queremos full dinamismo en cambio de doctor.
    // Por ahora, recargar la página para cambios drásticos es más seguro o implementar AJAX simple.
    async function cargarMedicos() {
        const consultorioId = document.getElementById('consultorio_id').value;
        const especialidadId = document.getElementById('especialidad_id').value;
        const select = document.getElementById('medico_id');
        
        if (!consultorioId || !especialidadId) return;

        select.innerHTML = '<option>Cargando...</option>';
        
        try {
            const response = await fetch(`${BASE_URL}/ajax/citas/medicos?consultorio_id=${consultorioId}&especialidad_id=${especialidadId}`);
            const medicos = await response.json();
            
            select.innerHTML = '<option value="">Seleccione Médico</option>';
            medicos.forEach(m => {
                const opt = document.createElement('option');
                opt.value = m.id;
                opt.textContent = `Dr. ${m.primer_nombre} ${m.primer_apellido}`;
                select.appendChild(opt);
            });
            
            // Limpiar horarios
            document.getElementById('horarios-container').innerHTML = '<p class="col-span-full text-center text-gray-500 text-sm">Seleccione médico y fecha</p>';
        } catch (error) {
            console.error(error);
            select.innerHTML = '<option value="">Error al cargar</option>';
        }
    }

    async function cargarEspecialidades() {
        const consultorioId = document.getElementById('consultorio_id').value;
        const select = document.getElementById('especialidad_id');
        
        if (!consultorioId) return;

        select.innerHTML = '<option>Cargando...</option>';
        
        try {
            const response = await fetch(`${BASE_URL}/ajax/citas/especialidades-por-consultorio/${consultorioId}`);
            const especialidades = await response.json();
            
            select.innerHTML = '<option value="">Seleccione Especialidad</option>';
            especialidades.forEach(esp => {
                const option = document.createElement('option');
                option.value = esp.id;
                option.textContent = esp.nombre;
                select.appendChild(option);
            });

            // Limpiar médicos y horarios
            document.getElementById('medico_id').innerHTML = '<option value="">Primero seleccione especialidad</option>';
            document.getElementById('horarios-container').innerHTML = '<p class="col-span-full text-center text-gray-500 text-sm">Seleccione médico y fecha</p>';
        } catch (error) {
            console.error(error);
            select.innerHTML = '<option value="">Error al cargar</option>';
        }
    }

</script>
@endpush
@endsection
