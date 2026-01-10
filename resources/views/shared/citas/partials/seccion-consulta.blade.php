<!-- SECCIÓN CONSULTA: Estado, Consultorio, Especialidad, Médico, Fecha -->
<div id="seccion-consulta" class="space-y-6 hidden">

    <!-- SELECCIÓN DE UBICACIÓN -->
    <div class="card p-6 border-l-4 border-l-amber-500">
        <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-2">
            <i class="bi bi-geo-alt text-amber-600"></i>
            Ubicación del Consultorio
        </h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="form-label form-label-required">Estado</label>
                <select id="estado_id" name="estado_consulta" class="form-select">
                    <option value="">Seleccione un estado...</option>
                    @foreach($estados as $estado)
                        <option value="{{ $estado->id_estado }}">{{ $estado->estado }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="form-label form-label-required">Consultorio</label>
                <select id="consultorio_id" name="consultorio_id" class="form-select" disabled onchange="cargarEspecialidades()">
                    <option value="">Seleccione estado primero...</option>
                    @foreach($consultorios as $consultorio)
                        <option value="{{ $consultorio->id }}" data-estado="{{ $consultorio->estado_id }}" style="display: none;">
                            {{ $consultorio->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <!-- ESPECIALIDAD Y MÉDICO -->
    <div class="card p-6 border-l-4 border-l-medical-500">
        <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-2">
            <i class="bi bi-person-badge text-medical-600"></i>
            Especialidad y Médico
        </h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="form-label form-label-required">Especialidad</label>
                <select id="especialidad_id" name="especialidad_id" class="form-select" disabled onchange="cargarMedicos()">
                    <option value="">Seleccione consultorio primero...</option>
                </select>
            </div>
            <div>
                <label class="form-label form-label-required">Médico</label>
                <select id="medico_id" name="medico_id" class="form-select" disabled onchange="actualizarInfoMedico()">
                    <option value="">Seleccione especialidad primero...</option>
                </select>
            </div>
        </div>
        
        <!-- Info del médico seleccionado -->
        <div id="info-medico" class="hidden mt-4 p-4 bg-medical-50 border border-medical-200 rounded-lg">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-full bg-medical-500 flex items-center justify-center text-white font-bold" id="medico-iniciales">--</div>
                <div class="flex-1">
                    <h4 class="font-bold text-gray-900" id="medico-nombre">-</h4>
                    <p class="text-sm text-gray-600" id="medico-especialidad">-</p>
                </div>
                <div class="text-right">
                    <p class="text-xs text-gray-500">Tarifa</p>
                    <p class="text-lg font-bold text-medical-600" id="medico-tarifa">$0.00</p>
                </div>
            </div>
        </div>
    </div>

    <!-- FECHA Y HORA -->
    <div class="card p-6 border-l-4 border-l-info-500">
        <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-2">
            <i class="bi bi-calendar-event text-info-600"></i>
            Fecha y Hora
        </h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="form-label form-label-required">Fecha de la Cita</label>
                <input type="date" id="fecha_cita" name="fecha_cita" class="input" min="{{ date('Y-m-d') }}" disabled onchange="cargarHorarios()">
            </div>
            <div>
                <label class="form-label form-label-required">Hora Disponible</label>
                <div id="horarios-container" class="grid grid-cols-4 gap-2 max-h-48 overflow-y-auto p-2 border rounded-lg bg-gray-50">
                    <p class="col-span-4 text-center text-gray-500 text-sm py-4">Seleccione médico y fecha</p>
                </div>
                <input type="hidden" name="hora_inicio" id="hora_inicio" required>
            </div>
        </div>
    </div>

    <!-- TIPO DE CONSULTA -->
    <div class="card p-6 border-l-4 border-l-purple-500">
        <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-2">
            <i class="bi bi-building text-purple-600"></i>
            Tipo de Consulta
        </h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <label class="flex items-center gap-3 p-4 border-2 rounded-xl cursor-pointer hover:border-blue-500 has-[:checked]:border-blue-500 has-[:checked]:bg-blue-50">
                <input type="radio" name="tipo_consulta" value="Consultorio" class="w-5 h-5 text-blue-600" checked>
                <div>
                    <span class="font-semibold text-gray-900">En Consultorio</span>
                    <p class="text-sm text-gray-500">Asistir al consultorio médico</p>
                </div>
            </label>
            <label id="opcion-domicilio" class="hidden flex items-center gap-3 p-4 border-2 rounded-xl cursor-pointer hover:border-emerald-500 has-[:checked]:border-emerald-500 has-[:checked]:bg-emerald-50">
                <input type="radio" name="tipo_consulta" value="Domicilio" class="w-5 h-5 text-emerald-600">
                <div>
                    <span class="font-semibold text-gray-900">A Domicilio</span>
                    <p class="text-sm text-gray-500">Visita a domicilio (tarifa extra)</p>
                </div>
            </label>
        </div>
        
        <div id="aviso-domicilio" class="mt-4 p-4 bg-amber-50 border border-amber-200 rounded-xl hidden">
            <p class="text-sm text-amber-700"><i class="bi bi-exclamation-triangle"></i> Tarifa adicional: <strong id="tarifa-extra-valor">$0.00</strong></p>
        </div>
    </div>

    <!-- MOTIVO -->
    <div class="card p-6 border-l-4 border-l-rose-500">
        <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-2">
            <i class="bi bi-chat-left-text text-rose-600"></i>
            Motivo de Consulta
        </h3>
        
        <textarea name="motivo" id="motivo" rows="3" class="form-textarea" placeholder="Describa brevemente los síntomas o el motivo de la consulta..."></textarea>
    </div>
</div>
