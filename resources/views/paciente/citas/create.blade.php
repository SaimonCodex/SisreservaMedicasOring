@extends('layouts.paciente')

@section('title', 'Agendar Nueva Cita')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center gap-4">
        <a href="{{ route('paciente.citas.index') }}" class="btn btn-outline">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div>
            <h1 class="text-2xl font-display font-bold text-gray-900">Agendar Nueva Cita</h1>
            <p class="text-gray-600 mt-1">Solicita tu consulta médica</p>
        </div>
    </div>

    <!-- Paso 1: Tipo de Cita -->
    <div id="step-tipo" class="card p-6">
        <h3 class="text-lg font-display font-bold text-gray-900 mb-4 flex items-center gap-2">
            <i class="bi bi-person-check text-blue-600"></i>
            ¿Para quién es esta cita?
        </h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <button type="button" id="btn-propia" onclick="selectTipoCita('propia')" class="p-6 border-2 border-gray-200 rounded-xl hover:border-blue-500 hover:bg-blue-50 transition-all text-left group">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 rounded-full bg-blue-100 flex items-center justify-center group-hover:bg-blue-200 transition-colors">
                        <i class="bi bi-person-fill text-2xl text-blue-600"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-gray-900 text-lg">Cita Propia</h4>
                        <p class="text-sm text-gray-600">La cita es para mí mismo</p>
                    </div>
                </div>
            </button>
            
            <button type="button" id="btn-terceros" onclick="selectTipoCita('terceros')" class="p-6 border-2 border-gray-200 rounded-xl hover:border-emerald-500 hover:bg-emerald-50 transition-all text-left group">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 rounded-full bg-emerald-100 flex items-center justify-center group-hover:bg-emerald-200 transition-colors">
                        <i class="bi bi-people-fill text-2xl text-emerald-600"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-gray-900 text-lg">Cita para Terceros</h4>
                        <p class="text-sm text-gray-600">Agendar para otra persona</p>
                    </div>
                </div>
            </button>
        </div>
    </div>

    <!-- Formulario Principal -->
    <form action="{{ route('paciente.citas.store') }}" method="POST" id="citaForm" class="space-y-6 hidden">
        @csrf
        <input type="hidden" name="tipo_cita" id="tipo_cita" value="">
        <input type="hidden" name="paciente_id" id="paciente_id" value="{{ $paciente->id ?? '' }}">

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Form -->
            <div class="lg:col-span-2 space-y-6">
                
                <!-- Datos del Paciente (Solo para Terceros) -->
                <div id="datos-tercero" class="card p-6 hidden">
                    <h3 class="text-lg font-display font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="bi bi-person-vcard text-emerald-600"></i>
                        Datos del Paciente
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="form-label form-label-required">Primer Nombre</label>
                            <input type="text" name="tercero_primer_nombre" class="input" placeholder="Nombre">
                        </div>
                        <div>
                            <label class="form-label">Segundo Nombre</label>
                            <input type="text" name="tercero_segundo_nombre" class="input" placeholder="Segundo nombre">
                        </div>
                        <div>
                            <label class="form-label form-label-required">Primer Apellido</label>
                            <input type="text" name="tercero_primer_apellido" class="input" placeholder="Apellido">
                        </div>
                        <div>
                            <label class="form-label">Segundo Apellido</label>
                            <input type="text" name="tercero_segundo_apellido" class="input" placeholder="Segundo apellido">
                        </div>
                        <div>
                            <label class="form-label form-label-required">Cédula</label>
                            <div class="flex gap-2">
                                <select name="tercero_tipo_documento" class="form-select w-20">
                                    <option value="V">V</option>
                                    <option value="E">E</option>
                                    <option value="P">P</option>
                                </select>
                                <input type="text" name="tercero_numero_documento" class="input flex-1" placeholder="12345678" maxlength="12">
                            </div>
                        </div>
                        <div>
                            <label class="form-label">Teléfono</label>
                            <div class="flex gap-2">
                                <select name="tercero_prefijo_tlf" class="form-select w-24">
                                    <option value="+58">+58</option>
                                    <option value="+57">+57</option>
                                    <option value="+1">+1</option>
                                </select>
                                <input type="tel" name="tercero_numero_tlf" class="input flex-1" placeholder="4121234567">
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Datos Propios (Preview, solo lectura) -->
                <div id="datos-propios" class="card p-6 hidden">
                    <h3 class="text-lg font-display font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="bi bi-person-check text-blue-600"></i>
                        Mis Datos
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="p-3 bg-gray-50 rounded-lg">
                            <p class="text-xs text-gray-500 mb-1">Nombre Completo</p>
                            <p class="font-semibold text-gray-900">
                                {{ ($paciente->primer_nombre ?? '') . ' ' . ($paciente->segundo_nombre ?? '') . ' ' . ($paciente->primer_apellido ?? '') . ' ' . ($paciente->segundo_apellido ?? '') }}
                            </p>
                        </div>
                        <div class="p-3 bg-gray-50 rounded-lg">
                            <p class="text-xs text-gray-500 mb-1">Cédula</p>
                            <p class="font-semibold text-gray-900">{{ ($paciente->tipo_documento ?? 'V') }}-{{ $paciente->numero_documento ?? 'N/A' }}</p>
                        </div>
                        <div class="p-3 bg-gray-50 rounded-lg">
                            <p class="text-xs text-gray-500 mb-1">Teléfono</p>
                            <p class="font-semibold text-gray-900">{{ ($paciente->prefijo_tlf ?? '') }} {{ $paciente->numero_tlf ?? 'N/A' }}</p>
                        </div>
                        <div class="p-3 bg-gray-50 rounded-lg">
                            <p class="text-xs text-gray-500 mb-1">Fecha Nacimiento</p>
                            <p class="font-semibold text-gray-900">{{ $paciente->fecha_nac ? \Carbon\Carbon::parse($paciente->fecha_nac)->format('d/m/Y') : 'N/A' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Ubicación y Consultorio -->
                <div class="card p-6">
                    <h3 class="text-lg font-display font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="bi bi-geo-alt text-purple-600"></i>
                        Ubicación y Consultorio
                    </h3>
                    <p class="text-sm text-gray-500 mb-4">Puedes buscar por estado, por especialidad, o seleccionar directamente el consultorio.</p>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Buscar por Estado -->
                        <div>
                            <label class="form-label">Buscar por Estado</label>
                            <select id="estado_busqueda" class="form-select">
                                <option value="">Todos los estados...</option>
                                @foreach($estados ?? [] as $estado)
                                <option value="{{ $estado->id_estado }}">{{ $estado->estado }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Consultorio -->
                        <div>
                            <label class="form-label form-label-required">Consultorio</label>
                            <select name="consultorio_id" id="consultorio_id" class="form-select" required>
                                <option value="">Seleccionar consultorio...</option>
                                @foreach($consultorios ?? [] as $consultorio)
                                <option value="{{ $consultorio->id }}" 
                                        data-estado="{{ $consultorio->estado_id }}"
                                        data-direccion="{{ $consultorio->direccion_detallada }}">
                                    {{ $consultorio->nombre }}
                                </option>
                                @endforeach
                            </select>
                            <p id="consultorio-direccion" class="text-xs text-gray-500 mt-1 hidden"></p>
                        </div>
                    </div>
                </div>

                <!-- Especialidad -->
                <div class="card p-6">
                    <h3 class="text-lg font-display font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="bi bi-hospital text-blue-600"></i>
                        Especialidad y Médico
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="form-label form-label-required">Especialidad</label>
                            <select name="especialidad_id" id="especialidad_id" class="form-select" required disabled>
                                <option value="">Primero seleccione un consultorio...</option>
                            </select>
                        </div>

                        <div>
                            <label class="form-label form-label-required">Médico</label>
                            <select name="medico_id" id="medico_id" class="form-select" required disabled>
                                <option value="">Primero seleccione una especialidad...</option>
                            </select>
                            <p id="medico-info" class="text-xs text-gray-500 mt-1 hidden"></p>
                        </div>
                    </div>
                </div>

                <!-- Fecha y Hora -->
                <div class="card p-6">
                    <h3 class="text-lg font-display font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="bi bi-calendar-event text-emerald-600"></i>
                        Fecha y Hora
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="form-label form-label-required">Fecha</label>
                            <input type="date" name="fecha_cita" id="fecha_cita" class="input" min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" required disabled>
                            <p class="form-help">Seleccione primero médico y consultorio</p>
                        </div>

                        <div>
                            <label class="form-label form-label-required">Hora Disponible</label>
                            <div id="horarios-container" class="grid grid-cols-4 gap-2 max-h-48 overflow-y-auto p-2 border rounded-lg bg-gray-50">
                                <p class="col-span-4 text-center text-gray-500 text-sm py-4">Seleccione fecha para ver horarios</p>
                            </div>
                            <input type="hidden" name="hora_inicio" id="hora_inicio" required>
                        </div>
                    </div>
                </div>

                <!-- Tipo de Consulta -->
                <div class="card p-6">
                    <h3 class="text-lg font-display font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="bi bi-building text-amber-600"></i>
                        Tipo de Consulta
                    </h3>

                    <div class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <label class="flex items-center gap-3 p-4 border-2 rounded-xl cursor-pointer hover:border-blue-500 transition-colors tipo-consulta-option" data-tipo="Consultorio">
                                <input type="radio" name="tipo_consulta" value="Consultorio" class="w-5 h-5 text-blue-600" checked>
                                <div>
                                    <span class="font-semibold text-gray-900">En Consultorio</span>
                                    <p class="text-sm text-gray-500">Asistir al consultorio médico</p>
                                </div>
                            </label>
                            
                            <label id="opcion-domicilio" class="flex items-center gap-3 p-4 border-2 rounded-xl cursor-pointer hover:border-emerald-500 transition-colors tipo-consulta-option hidden" data-tipo="Domicilio">
                                <input type="radio" name="tipo_consulta" value="Domicilio" class="w-5 h-5 text-emerald-600">
                                <div>
                                    <span class="font-semibold text-gray-900">A Domicilio</span>
                                    <p class="text-sm text-gray-500">El médico visita tu hogar</p>
                                </div>
                            </label>
                        </div>

                        <!-- Aviso de tarifa extra -->
                        <div id="aviso-domicilio" class="p-4 bg-amber-50 border border-amber-200 rounded-xl hidden">
                            <div class="flex gap-3">
                                <i class="bi bi-exclamation-triangle text-amber-600 text-xl"></i>
                                <div>
                                    <p class="font-semibold text-amber-800">Consulta a Domicilio</p>
                                    <p class="text-sm text-amber-700">Las consultas a domicilio suelen tener tarifa extra o cargos adicionales. El costo adicional es: <strong id="tarifa-extra-valor">$0.00</strong></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Motivo -->
                <div class="card p-6">
                    <h3 class="text-lg font-display font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="bi bi-chat-left-text text-rose-600"></i>
                        Motivo de Consulta
                    </h3>

                    <div>
                        <label class="form-label">Describa el motivo de su consulta</label>
                        <textarea name="motivo" rows="4" class="form-textarea" placeholder="Describa brevemente los síntomas o motivo de su consulta..."></textarea>
                        <p class="form-help">Proporcione detalles que ayuden al médico a preparar mejor su atención</p>
                    </div>
                </div>
            </div>

            <!-- Sidebar - Resumen -->
            <div class="space-y-6">
                <div class="card p-6 sticky top-24">
                    <h3 class="text-lg font-display font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="bi bi-receipt text-blue-600"></i>
                        Resumen de Cita
                    </h3>
                    
                    <div class="space-y-3 text-sm">
                        <!-- Tipo de Cita -->
                        <div class="p-3 bg-gray-50 rounded-lg">
                            <p class="text-xs text-gray-500 mb-1">Tipo de Cita</p>
                            <p class="font-semibold text-gray-900" id="resumen-tipo">-</p>
                        </div>
                        
                        <!-- Tipo Consulta -->
                        <div class="p-3 bg-blue-50 rounded-lg">
                            <p class="text-xs text-gray-500 mb-1">Modalidad</p>
                            <p class="font-semibold text-gray-900" id="resumen-modalidad">En Consultorio</p>
                        </div>
                        
                        <!-- Especialidad -->
                        <div class="p-3 bg-purple-50 rounded-lg">
                            <p class="text-xs text-gray-500 mb-1">Especialidad</p>
                            <p class="font-semibold text-gray-900" id="resumen-especialidad">-</p>
                        </div>
                        
                        <!-- Médico -->
                        <div class="p-3 bg-emerald-50 rounded-lg">
                            <p class="text-xs text-gray-500 mb-1">Médico</p>
                            <p class="font-semibold text-gray-900" id="resumen-medico">-</p>
                        </div>
                        
                        <!-- Consultorio -->
                        <div class="p-3 bg-amber-50 rounded-lg">
                            <p class="text-xs text-gray-500 mb-1">Consultorio</p>
                            <p class="font-semibold text-gray-900" id="resumen-consultorio">-</p>
                            <p class="text-xs text-gray-500 mt-1" id="resumen-consultorio-direccion"></p>
                        </div>
                        
                        <!-- Fecha y Hora -->
                        <div class="p-3 bg-sky-50 rounded-lg">
                            <p class="text-xs text-gray-500 mb-1">Fecha y Hora</p>
                            <p class="font-semibold text-gray-900" id="resumen-fecha">-</p>
                        </div>
                        
                        <!-- Tarifa -->
                        <div class="p-4 bg-gradient-to-r from-green-50 to-emerald-50 rounded-lg border border-green-200">
                            <p class="text-xs text-gray-500 mb-1">Tarifa Total</p>
                            <div class="flex items-baseline gap-2">
                                <p class="text-2xl font-bold text-green-700" id="resumen-tarifa">$0.00</p>
                                <span class="text-xs text-gray-500" id="resumen-tarifa-detalle"></span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-6 space-y-3">
                        <button type="submit" class="btn btn-success w-full text-lg py-3">
                            <i class="bi bi-check-lg"></i>
                            Confirmar Cita
                        </button>
                        <button type="button" onclick="resetForm()" class="btn btn-outline w-full">
                            <i class="bi bi-arrow-left"></i>
                            Volver
                        </button>
                    </div>
                </div>

                <!-- Info -->
                <div class="card p-6 bg-blue-50 border-blue-200">
                    <div class="flex gap-3">
                        <i class="bi bi-info-circle text-blue-600 text-xl"></i>
                        <div>
                            <h4 class="font-semibold text-gray-900 mb-1">Importante</h4>
                            <p class="text-sm text-gray-600">Recibirás una confirmación por email una vez que tu cita sea registrada en el sistema.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
    // URL base de la aplicación (incluye index.php si es necesario)
    const BASE_URL = '{{ url("") }}';
    
    // Variables globales para almacenar datos del médico seleccionado
    let medicoActual = null;
    let tarifaBase = 0;
    let tarifaExtra = 0;

    function selectTipoCita(tipo) {
        document.getElementById('tipo_cita').value = tipo;
        document.getElementById('step-tipo').classList.add('hidden');
        document.getElementById('citaForm').classList.remove('hidden');
        
        if (tipo === 'propia') {
            document.getElementById('datos-propios').classList.remove('hidden');
            document.getElementById('datos-tercero').classList.add('hidden');
            document.getElementById('resumen-tipo').textContent = 'Cita Propia';
        } else {
            document.getElementById('datos-propios').classList.add('hidden');
            document.getElementById('datos-tercero').classList.remove('hidden');
            document.getElementById('resumen-tipo').textContent = 'Cita para Terceros';
        }
    }
    
    function resetForm() {
        document.getElementById('step-tipo').classList.remove('hidden');
        document.getElementById('citaForm').classList.add('hidden');
        document.getElementById('datos-propios').classList.add('hidden');
        document.getElementById('datos-tercero').classList.add('hidden');
    }

    // Filtrar consultorios por estado
    document.getElementById('estado_busqueda')?.addEventListener('change', function() {
        const estadoId = this.value;
        const consultorioSelect = document.getElementById('consultorio_id');
        const opciones = consultorioSelect.querySelectorAll('option');
        
        opciones.forEach(opt => {
            if (!opt.value) return; // Skip placeholder
            if (!estadoId || opt.dataset.estado == estadoId) {
                opt.style.display = '';
            } else {
                opt.style.display = 'none';
            }
        });
        
        consultorioSelect.value = '';
    });

    // Cuando se selecciona consultorio, cargar especialidades
    document.getElementById('consultorio_id')?.addEventListener('change', async function() {
        const consultorioId = this.value;
        const selectedOption = this.options[this.selectedIndex];
        const direccion = selectedOption?.dataset?.direccion || '';
        
        // Mostrar dirección
        const dirEl = document.getElementById('consultorio-direccion');
        if (direccion) {
            dirEl.textContent = '📍 ' + direccion;
            dirEl.classList.remove('hidden');
        } else {
            dirEl.classList.add('hidden');
        }
        
        // Actualizar resumen
        document.getElementById('resumen-consultorio').textContent = selectedOption?.text || '-';
        document.getElementById('resumen-consultorio-direccion').textContent = direccion;
        
        const especialidadSelect = document.getElementById('especialidad_id');
        
        if (!consultorioId) {
            especialidadSelect.disabled = true;
            especialidadSelect.innerHTML = '<option value="">Primero seleccione un consultorio...</option>';
            return;
        }
        
        especialidadSelect.disabled = false;
        especialidadSelect.innerHTML = '<option value="">Cargando especialidades...</option>';
        
        try {
            const response = await fetch(BASE_URL + '/ajax/citas/especialidades-por-consultorio/' + consultorioId);
            const especialidades = await response.json();
            
            especialidadSelect.innerHTML = '<option value="">Seleccionar especialidad...</option>';
            especialidades.forEach(esp => {
                const option = document.createElement('option');
                option.value = esp.id;
                option.textContent = esp.nombre;
                especialidadSelect.appendChild(option);
            });
        } catch (e) {
            console.error('Error cargando especialidades:', e);
            especialidadSelect.innerHTML = '<option value="">Error al cargar</option>';
        }
    });

    // Cuando se selecciona especialidad, cargar médicos
    document.getElementById('especialidad_id')?.addEventListener('change', async function() {
        const especialidadId = this.value;
        const consultorioId = document.getElementById('consultorio_id').value;
        
        document.getElementById('resumen-especialidad').textContent = this.options[this.selectedIndex]?.text || '-';
        
        const medicoSelect = document.getElementById('medico_id');
        
        if (!especialidadId || !consultorioId) {
            medicoSelect.disabled = true;
            medicoSelect.innerHTML = '<option value="">Primero seleccione especialidad...</option>';
            return;
        }
        
        medicoSelect.disabled = false;
        medicoSelect.innerHTML = '<option value="">Cargando médicos...</option>';
        
        try {
            const response = await fetch(`${BASE_URL}/ajax/citas/medicos?especialidad_id=${especialidadId}&consultorio_id=${consultorioId}`);
            const medicos = await response.json();
            
            medicoSelect.innerHTML = '<option value="">Seleccionar médico...</option>';
            medicos.forEach(med => {
                const option = document.createElement('option');
                option.value = med.id;
                option.textContent = med.nombre + ' - $' + parseFloat(med.tarifa).toFixed(2);
                option.dataset.tarifa = med.tarifa;
                option.dataset.atiendeDomicilio = med.atiende_domicilio ? '1' : '0';
                option.dataset.tarifaExtra = med.tarifa_extra_domicilio || 0;
                medicoSelect.appendChild(option);
            });
            
            if (medicos.length === 0) {
                medicoSelect.innerHTML = '<option value="">No hay médicos disponibles</option>';
            }
        } catch (e) {
            console.error('Error cargando médicos:', e);
            medicoSelect.innerHTML = '<option value="">Error al cargar</option>';
        }
    });

    // Cuando se selecciona médico
    document.getElementById('medico_id')?.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        
        if (!selectedOption || !selectedOption.value) {
            document.getElementById('fecha_cita').disabled = true;
            return;
        }
        
        // Guardar datos del médico
        medicoActual = {
            id: selectedOption.value,
            nombre: selectedOption.text.split(' - ')[0],
            tarifa: parseFloat(selectedOption.dataset.tarifa) || 0,
            atiendeDomicilio: selectedOption.dataset.atiendeDomicilio === '1',
            tarifaExtra: parseFloat(selectedOption.dataset.tarifaExtra) || 0
        };
        
        tarifaBase = medicoActual.tarifa;
        tarifaExtra = 0;
        
        // Actualizar resumen
        document.getElementById('resumen-medico').textContent = medicoActual.nombre;
        actualizarResumenTarifa();
        
        // Mostrar/ocultar opción de domicilio
        const opcionDomicilio = document.getElementById('opcion-domicilio');
        if (medicoActual.atiendeDomicilio) {
            opcionDomicilio.classList.remove('hidden');
            document.getElementById('tarifa-extra-valor').textContent = '$' + medicoActual.tarifaExtra.toFixed(2);
        } else {
            opcionDomicilio.classList.add('hidden');
            // Reset a consultorio si estaba en domicilio
            document.querySelector('input[value="Consultorio"]').checked = true;
            document.getElementById('aviso-domicilio').classList.add('hidden');
        }
        
        // Habilitar fecha
        document.getElementById('fecha_cita').disabled = false;
    });

    // Cuando cambia el tipo de consulta
    document.querySelectorAll('input[name="tipo_consulta"]').forEach(radio => {
        radio.addEventListener('change', function() {
            const avisoEl = document.getElementById('aviso-domicilio');
            
            if (this.value === 'Domicilio') {
                avisoEl.classList.remove('hidden');
                tarifaExtra = medicoActual?.tarifaExtra || 0;
                document.getElementById('resumen-modalidad').textContent = 'A Domicilio';
            } else {
                avisoEl.classList.add('hidden');
                tarifaExtra = 0;
                document.getElementById('resumen-modalidad').textContent = 'En Consultorio';
            }
            
            actualizarResumenTarifa();
        });
    });

    // Cuando se selecciona fecha, cargar horarios disponibles
    document.getElementById('fecha_cita')?.addEventListener('change', async function() {
        const fecha = this.value;
        const medicoId = document.getElementById('medico_id').value;
        const consultorioId = document.getElementById('consultorio_id').value;
        
        if (!fecha || !medicoId) return;
        
        const container = document.getElementById('horarios-container');
        container.innerHTML = '<p class="col-span-4 text-center text-gray-500 text-sm py-4">Cargando horarios...</p>';
        
        try {
            const response = await fetch(`${BASE_URL}/ajax/citas/horarios-disponibles?medico_id=${medicoId}&consultorio_id=${consultorioId}&fecha=${fecha}`);
            const data = await response.json();
            
            if (!data.disponible) {
                container.innerHTML = `<p class="col-span-4 text-center text-amber-600 text-sm py-4">${data.mensaje}</p>`;
                return;
            }
            
            container.innerHTML = '';
            
            data.horarios.forEach(slot => {
                const btn = document.createElement('button');
                btn.type = 'button';
                btn.textContent = slot.hora;
                btn.className = 'p-2 text-sm rounded-lg font-medium transition-all ';
                
                if (slot.disponible) {
                    btn.className += 'bg-green-100 text-green-700 hover:bg-green-200 cursor-pointer';
                    btn.onclick = () => selectHorario(slot.hora, btn);
                } else {
                    btn.className += 'bg-red-100 text-red-400 cursor-not-allowed';
                    btn.disabled = true;
                    btn.title = 'Horario ocupado';
                }
                
                container.appendChild(btn);
            });
            
            if (data.horarios.length === 0) {
                container.innerHTML = '<p class="col-span-4 text-center text-gray-500 text-sm py-4">No hay horarios configurados</p>';
            }
            
            // Actualizar resumen de fecha
            const fechaFormateada = new Date(fecha + 'T12:00:00').toLocaleDateString('es-ES', {
                weekday: 'long',
                day: 'numeric',
                month: 'long',
                year: 'numeric'
            });
            document.getElementById('resumen-fecha').textContent = fechaFormateada;
            
        } catch (e) {
            console.error('Error cargando horarios:', e);
            container.innerHTML = '<p class="col-span-4 text-center text-red-500 text-sm py-4">Error al cargar horarios</p>';
        }
    });

    function selectHorario(hora, btn) {
        // Quitar selección anterior
        document.querySelectorAll('#horarios-container button').forEach(b => {
            b.classList.remove('ring-2', 'ring-blue-500', 'bg-blue-100');
        });
        
        // Marcar seleccionado
        btn.classList.add('ring-2', 'ring-blue-500', 'bg-blue-100');
        
        // Guardar hora
        document.getElementById('hora_inicio').value = hora;
        
        // Actualizar resumen
        const fechaEl = document.getElementById('resumen-fecha');
        const fechaActual = fechaEl.textContent.split(' - ')[0];
        fechaEl.textContent = fechaActual + ' - ' + hora;
    }

    function actualizarResumenTarifa() {
        const total = tarifaBase + tarifaExtra;
        document.getElementById('resumen-tarifa').textContent = '$' + total.toFixed(2);
        
        if (tarifaExtra > 0) {
            document.getElementById('resumen-tarifa-detalle').textContent = 
                `($${tarifaBase.toFixed(2)} + $${tarifaExtra.toFixed(2)} domicilio)`;
        } else {
            document.getElementById('resumen-tarifa-detalle').textContent = '';
        }
    }
</script>
@endpush
@endsection
