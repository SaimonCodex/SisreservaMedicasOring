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
            <button type="button" onclick="selectTipoCita('propia')" class="p-6 border-2 border-gray-200 rounded-xl hover:border-blue-500 hover:bg-blue-50 transition-all text-left group">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 rounded-full bg-blue-100 flex items-center justify-center">
                        <i class="bi bi-person-fill text-2xl text-blue-600"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-gray-900 text-lg">Cita Propia</h4>
                        <p class="text-sm text-gray-600">La cita es para mí mismo</p>
                    </div>
                </div>
            </button>
            
            <button type="button" onclick="selectTipoCita('terceros')" class="p-6 border-2 border-gray-200 rounded-xl hover:border-emerald-500 hover:bg-emerald-50 transition-all text-left group">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 rounded-full bg-emerald-100 flex items-center justify-center">
                        <i class="bi bi-people-fill text-2xl text-emerald-600"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-gray-900 text-lg">Cita para Terceros</h4>
                        <p class="text-sm text-gray-600">Agendar para otra persona (menor, familiar, etc)</p>
                    </div>
                </div>
            </button>
        </div>
    </div>

    <!-- Formulario Principal -->
    <form action="{{ route('paciente.citas.store') }}" method="POST" id="citaForm" class="space-y-6 hidden" onsubmit="return validarFormulario()">
        @csrf
        <input type="hidden" name="tipo_cita" id="tipo_cita" value="">
        <input type="hidden" name="misma_direccion" id="misma_direccion_input" value="1">
        <input type="hidden" name="paciente_especial_existente_id" id="paciente_especial_existente_id" value="">

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 space-y-6">
                
                <!-- DATOS PROPIOS -->
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
                            <p class="text-xs text-gray-500 mb-1">Identificación</p>
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

                <!-- DATOS REPRESENTANTE -->
                <div id="datos-representante" class="card p-6 hidden">
                    <h3 class="text-lg font-display font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="bi bi-person-badge text-purple-600"></i>
                        Datos del Representante
                        <span class="text-sm font-normal text-gray-500">(Quien agenda la cita)</span>
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="form-label form-label-required">Primer Nombre</label>
                            <input type="text" name="rep_primer_nombre" id="rep_primer_nombre" class="input" 
                                   value="{{ $paciente->primer_nombre ?? '' }}" placeholder="Nombre" 
                                   oninput="this.value = this.value.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s]/g, '')">
                            <span class="error-message text-red-500 text-xs mt-1 hidden"></span>
                        </div>
                        <div>
                            <label class="form-label">Segundo Nombre</label>
                            <input type="text" name="rep_segundo_nombre" id="rep_segundo_nombre" class="input" 
                                   value="{{ $paciente->segundo_nombre ?? '' }}" placeholder="Segundo nombre"
                                   oninput="this.value = this.value.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s]/g, '')">
                            <span class="error-message text-red-500 text-xs mt-1 hidden"></span>
                        </div>
                        <div>
                            <label class="form-label form-label-required">Primer Apellido</label>
                            <input type="text" name="rep_primer_apellido" id="rep_primer_apellido" class="input" 
                                   value="{{ $paciente->primer_apellido ?? '' }}" placeholder="Apellido"
                                   oninput="this.value = this.value.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s]/g, '')">
                            <span class="error-message text-red-500 text-xs mt-1 hidden"></span>
                        </div>
                        <div>
                            <label class="form-label">Segundo Apellido</label>
                            <input type="text" name="rep_segundo_apellido" id="rep_segundo_apellido" class="input" 
                                   value="{{ $paciente->segundo_apellido ?? '' }}" placeholder="Segundo apellido"
                                   oninput="this.value = this.value.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s]/g, '')">
                            <span class="error-message text-red-500 text-xs mt-1 hidden"></span>
                        </div>
                        
                        <div>
                            <label class="form-label form-label-required">Identificación</label>
                            <div class="flex gap-2">
                                <select name="rep_tipo_documento" id="rep_tipo_documento" class="form-select w-20">
                                    <option value="V" {{ ($paciente->tipo_documento ?? '') == 'V' ? 'selected' : '' }}>V</option>
                                    <option value="E" {{ ($paciente->tipo_documento ?? '') == 'E' ? 'selected' : '' }}>E</option>
                                    <option value="P" {{ ($paciente->tipo_documento ?? '') == 'P' ? 'selected' : '' }}>P</option>
                                    <option value="J" {{ ($paciente->tipo_documento ?? '') == 'J' ? 'selected' : '' }}>J</option>
                                </select>
                                <input type="text" name="rep_numero_documento" id="rep_numero_documento" class="input flex-1" 
                                       value="{{ $paciente->numero_documento ?? '' }}" placeholder="12345678" maxlength="12"
                                       oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                            </div>
                            <span class="error-message text-red-500 text-xs mt-1 hidden" id="rep_numero_documento_error"></span>
                        </div>
                        
                        <div>
                            <label class="form-label">Teléfono</label>
                            <div class="flex gap-2">
                                <select name="rep_prefijo_tlf" class="form-select w-24">
                                    <option value="+58" {{ ($paciente->prefijo_tlf ?? '') == '+58' ? 'selected' : '' }}>+58</option>
                                    <option value="+57" {{ ($paciente->prefijo_tlf ?? '') == '+57' ? 'selected' : '' }}>+57</option>
                                    <option value="+1" {{ ($paciente->prefijo_tlf ?? '') == '+1' ? 'selected' : '' }}>+1</option>
                                </select>
                                <input type="tel" name="rep_numero_tlf" id="rep_numero_tlf" class="input flex-1" 
                                       value="{{ $paciente->numero_tlf ?? '' }}" placeholder="4121234567"
                                       oninput="this.value = this.value.replace(/[^0-9]/g, '')" maxlength="10">
                            </div>
                        </div>
                        
                        <div class="md:col-span-2">
                            <label class="form-label form-label-required">Parentesco con el Paciente</label>
                            <select name="rep_parentesco" id="rep_parentesco" class="form-select">
                                <option value="">Seleccionar parentesco...</option>
                                <option value="Padre">Padre</option>
                                <option value="Madre">Madre</option>
                                <option value="Hijo/a">Hijo/a</option>
                                <option value="Hermano/a">Hermano/a</option>
                                <option value="Tío/a">Tío/a</option>
                                <option value="Sobrino/a">Sobrino/a</option>
                                <option value="Abuelo/a">Abuelo/a</option>
                                <option value="Nieto/a">Nieto/a</option>
                                <option value="Primo/a">Primo/a</option>
                                <option value="Amigo/a">Amigo/a</option>
                                <option value="Tutor">Tutor Legal</option>
                                <option value="Otro">Otro</option>
                            </select>
                            <span class="error-message text-red-500 text-xs mt-1 hidden" id="rep_parentesco_error"></span>
                        </div>
                    </div>
                </div>

                <!-- SELECCIÓN PACIENTE ESPECIAL EXISTENTE -->
                @if(isset($pacientesEspecialesRegistrados) && $pacientesEspecialesRegistrados->count() > 0)
                <div id="seccion-select-paciente-especial" class="card p-6 hidden">
                    <h3 class="text-lg font-display font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="bi bi-person-check text-emerald-600"></i>
                        Seleccionar Paciente
                    </h3>
                    
                    <div class="mb-4">
                        <label class="form-label form-label-required">Paciente para la cita</label>
                        <select id="select_paciente_especial" class="form-select" onchange="seleccionarPacienteEspecial(this.value)">
                            <option value="">Seleccionar paciente registrado...</option>
                            @foreach($pacientesEspecialesRegistrados as $pe)
                            <option value="{{ $pe->id }}" 
                                    data-paciente-id="{{ $pe->paciente_id }}"
                                    data-nombre="{{ $pe->nombre_completo }}"
                                    data-tipo="{{ $pe->tipo }}"
                                    data-documento="{{ $pe->tipo_documento }}-{{ $pe->numero_documento }}">
                                {{ $pe->nombre_completo }} ({{ $pe->tipo }}) - {{ $pe->tipo_documento }}-{{ $pe->numero_documento }}
                            </option>
                            @endforeach
                            <option value="nuevo">➕ Registrar nuevo paciente especial</option>
                        </select>
                    </div>
                    
                    <!-- Paciente especial seleccionado (tarjeta) -->
                    <div id="paciente_especial_seleccionado_card" class="hidden">
                        <div class="bg-emerald-50 border border-emerald-200 rounded-xl p-4">
                            <div class="flex items-center gap-4">
                                <div class="w-14 h-14 rounded-full bg-gradient-to-br from-emerald-500 to-emerald-600 flex items-center justify-center text-white text-xl font-bold" id="pac_esp_iniciales_display">
                                    --
                                </div>
                                <div class="flex-1">
                                    <h4 class="font-bold text-gray-900" id="pac_esp_nombre_display">-</h4>
                                    <p class="text-sm text-gray-600" id="pac_esp_info_display">-</p>
                                </div>
                                <button type="button" onclick="limpiarPacienteEspecialSeleccionado()" class="text-danger-600 hover:text-danger-700">
                                    <i class="bi bi-x-lg"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <!-- DATOS PACIENTE ESPECIAL -->
                <div id="datos-paciente-especial" class="card p-6 hidden">
                    <h3 class="text-lg font-display font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="bi bi-person-heart text-rose-600"></i>
                        Datos del Paciente
                    </h3>
                    
                    <!-- Tipo de Paciente -->
                    <div class="mb-6">
                        <label class="form-label form-label-required">Tipo de Paciente</label>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-2">
                            <label class="flex items-center gap-2 p-3 border rounded-lg cursor-pointer hover:bg-blue-50 has-[:checked]:border-blue-500 has-[:checked]:bg-blue-50">
                                <input type="radio" name="pac_tipo" value="Menor de Edad" class="text-blue-600">
                                <span class="text-sm">Menor de Edad</span>
                            </label>
                            <label class="flex items-center gap-2 p-3 border rounded-lg cursor-pointer hover:bg-blue-50 has-[:checked]:border-blue-500 has-[:checked]:bg-blue-50">
                                <input type="radio" name="pac_tipo" value="Discapacitado" class="text-blue-600">
                                <span class="text-sm">Discapacitado</span>
                            </label>
                            <label class="flex items-center gap-2 p-3 border rounded-lg cursor-pointer hover:bg-blue-50 has-[:checked]:border-blue-500 has-[:checked]:bg-blue-50">
                                <input type="radio" name="pac_tipo" value="Anciano" class="text-blue-600">
                                <span class="text-sm">Adulto Mayor</span>
                            </label>
                            <label class="flex items-center gap-2 p-3 border rounded-lg cursor-pointer hover:bg-blue-50 has-[:checked]:border-blue-500 has-[:checked]:bg-blue-50">
                                <input type="radio" name="pac_tipo" value="Incapacitado" class="text-blue-600">
                                <span class="text-sm">Incapacitado</span>
                            </label>
                        </div>
                        <span class="error-message text-red-500 text-xs mt-1 hidden" id="pac_tipo_error"></span>
                    </div>
                    
                    <!-- ¿Tiene documento? -->
                    <div class="mb-6 p-4 bg-amber-50 border border-amber-200 rounded-xl">
                        <label class="form-label form-label-required mb-3">¿El paciente tiene documento de identidad?</label>
                        <div class="flex gap-4">
                            <label class="flex items-center gap-2 p-3 border bg-white rounded-lg cursor-pointer hover:bg-green-50 has-[:checked]:border-green-500 has-[:checked]:bg-green-50">
                                <input type="radio" name="pac_tiene_documento" value="si" class="text-green-600" onchange="toggleDocumento(true)">
                                <span class="font-medium">Sí, tiene documento</span>
                            </label>
                            <label class="flex items-center gap-2 p-3 border bg-white rounded-lg cursor-pointer hover:bg-orange-50 has-[:checked]:border-orange-500 has-[:checked]:bg-orange-50">
                                <input type="radio" name="pac_tiene_documento" value="no" class="text-orange-600" onchange="toggleDocumento(false)">
                                <span class="font-medium">No tiene documento</span>
                            </label>
                        </div>
                        <div id="doc-generado-info" class="mt-3 p-3 bg-white rounded-lg border border-amber-300 hidden">
                            <p class="text-sm text-amber-800"><i class="bi bi-info-circle"></i> Se generará el identificador:</p>
                            <p class="text-lg font-bold text-amber-900 mt-1" id="doc-generado-preview">-</p>
                        </div>
                        <span class="error-message text-red-500 text-xs mt-1 hidden" id="pac_tiene_documento_error"></span>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="form-label form-label-required">Primer Nombre</label>
                            <input type="text" name="pac_primer_nombre" id="pac_primer_nombre" class="input" placeholder="Nombre"
                                   oninput="this.value = this.value.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s]/g, '')">
                            <span class="error-message text-red-500 text-xs mt-1 hidden"></span>
                        </div>
                        <div>
                            <label class="form-label">Segundo Nombre</label>
                            <input type="text" name="pac_segundo_nombre" id="pac_segundo_nombre" class="input" placeholder="Segundo nombre"
                                   oninput="this.value = this.value.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s]/g, '')">
                            <span class="error-message text-red-500 text-xs mt-1 hidden"></span>
                        </div>
                        <div>
                            <label class="form-label form-label-required">Primer Apellido</label>
                            <input type="text" name="pac_primer_apellido" id="pac_primer_apellido" class="input" placeholder="Apellido"
                                   oninput="this.value = this.value.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s]/g, '')">
                            <span class="error-message text-red-500 text-xs mt-1 hidden"></span>
                        </div>
                        <div>
                            <label class="form-label">Segundo Apellido</label>
                            <input type="text" name="pac_segundo_apellido" id="pac_segundo_apellido" class="input" placeholder="Segundo apellido"
                                   oninput="this.value = this.value.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s]/g, '')">
                            <span class="error-message text-red-500 text-xs mt-1 hidden"></span>
                        </div>
                        
                        <!-- Documento Paciente -->
                        <div id="campo-documento-paciente" class="hidden">
                            <label class="form-label form-label-required">Documento de Identidad</label>
                            <div class="flex gap-2">
                                <select name="pac_tipo_documento" id="pac_tipo_documento" class="form-select w-20">
                                    <option value="V">V</option>
                                    <option value="E">E</option>
                                    <option value="P">P</option>
                                </select>
                                <input type="text" name="pac_numero_documento" id="pac_numero_documento" class="input flex-1" 
                                       placeholder="Número" maxlength="12"
                                       oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                            </div>
                            <span class="error-message text-red-500 text-xs mt-1 hidden" id="pac_numero_documento_error"></span>
                        </div>
                        
                        <div>
                            <label class="form-label">Fecha de Nacimiento</label>
                            <input type="date" name="pac_fecha_nac" id="pac_fecha_nac" class="input" max="{{ date('Y-m-d') }}">
                        </div>
                        
                        <!-- Dirección -->
                        <div class="md:col-span-2">
                            <label class="form-label">Dirección del Paciente</label>
                            <div class="flex items-center gap-2 mb-2">
                                <input type="checkbox" id="misma_direccion" checked onchange="toggleDireccionPaciente()">
                                <span class="text-sm text-gray-600">Usar la misma dirección del representante</span>
                            </div>
                        </div>
                        
                        <!-- Campos de ubicación del paciente (ocultos por defecto) -->
                        <div id="campos-ubicacion-paciente" class="hidden md:col-span-2">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 p-4 bg-gray-50 rounded-lg">
                                <div>
                                    <label class="form-label">Estado</label>
                                    <select name="pac_estado_id" id="pac_estado_id" class="form-select" onchange="cargarMunicipiosPac(); cargarCiudadesPac();">
                                        <option value="">Seleccionar estado...</option>
                                        @foreach($estados ?? [] as $estado)
                                        <option value="{{ $estado->id_estado }}">{{ $estado->estado }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="form-label">Municipio</label>
                                    <select name="pac_municipio_id" id="pac_municipio_id" class="form-select" onchange="cargarParroquiasPac()">
                                        <option value="">Primero seleccione estado...</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="form-label">Ciudad</label>
                                    <select name="pac_ciudad_id" id="pac_ciudad_id" class="form-select">
                                        <option value="">Primero seleccione estado...</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="form-label">Parroquia</label>
                                    <select name="pac_parroquia_id" id="pac_parroquia_id" class="form-select">
                                        <option value="">Primero seleccione municipio...</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="form-label">Dirección Detallada</label>
                                    <input type="text" name="pac_direccion_detallada" id="pac_direccion_detallada" class="input" placeholder="Calle, casa, referencia...">
                                </div>
                            </div>
                        </div>
                        
                        <div class="md:col-span-2">
                            <label class="form-label">Observaciones</label>
                            <textarea name="pac_observaciones" class="form-textarea" rows="2" placeholder="Alergias, condiciones especiales..."></textarea>
                        </div>
                    </div>
                </div>

                <!-- UBICACIÓN Y CONSULTORIO -->
                <div class="card p-6">
                    <h3 class="text-lg font-display font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="bi bi-geo-alt text-purple-600"></i>
                        Ubicación y Consultorio
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="form-label">Buscar por Estado</label>
                            <select id="estado_busqueda" class="form-select">
                                <option value="">Todos los estados...</option>
                                @foreach($estados ?? [] as $estado)
                                <option value="{{ $estado->id_estado }}">{{ $estado->estado }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="form-label form-label-required">Consultorio</label>
                            <select name="consultorio_id" id="consultorio_id" class="form-select" required>
                                <option value="">Seleccionar consultorio...</option>
                                @foreach($consultorios ?? [] as $consultorio)
                                <option value="{{ $consultorio->id }}" data-estado="{{ $consultorio->estado_id }}" data-direccion="{{ $consultorio->direccion_detallada }}">
                                    {{ $consultorio->nombre }}
                                </option>
                                @endforeach
                            </select>
                            <p id="consultorio-direccion" class="text-xs text-gray-500 mt-1 hidden"></p>
                        </div>
                    </div>
                </div>

                <!-- ESPECIALIDAD Y MÉDICO -->
                <div class="card p-6">
                    <h3 class="text-lg font-display font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="bi bi-hospital text-blue-600"></i>
                        Especialidad y Médico
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="form-label form-label-required">Especialidad</label>
                            <select name="especialidad_id" id="especialidad_id" class="form-select" required disabled>
                                <option value="">Primero seleccione consultorio...</option>
                            </select>
                        </div>
                        <div>
                            <label class="form-label form-label-required">Médico</label>
                            <select name="medico_id" id="medico_id" class="form-select" required disabled>
                                <option value="">Primero seleccione especialidad...</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- FECHA Y HORA -->
                <div class="card p-6">
                    <h3 class="text-lg font-display font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="bi bi-calendar-event text-emerald-600"></i>
                        Fecha y Hora
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="form-label form-label-required">Fecha</label>
                            <input type="date" name="fecha_cita" id="fecha_cita" class="input" min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" required disabled>
                        </div>
                        <div>
                            <label class="form-label form-label-required">Hora Disponible</label>
                            <div id="horarios-container" class="grid grid-cols-4 gap-2 max-h-48 overflow-y-auto p-2 border rounded-lg bg-gray-50">
                                <p class="col-span-4 text-center text-gray-500 text-sm py-4">Seleccione fecha</p>
                            </div>
                            <input type="hidden" name="hora_inicio" id="hora_inicio" required>
                        </div>
                    </div>
                </div>

                <!-- TIPO DE CONSULTA -->
                <div class="card p-6">
                    <h3 class="text-lg font-display font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="bi bi-building text-amber-600"></i>
                        Tipo de Consulta
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <label class="flex items-center gap-3 p-4 border-2 rounded-xl cursor-pointer hover:border-blue-500 has-[:checked]:border-blue-500 has-[:checked]:bg-blue-50">
                            <input type="radio" name="tipo_consulta" value="Consultorio" class="w-5 h-5 text-blue-600" checked>
                            <div>
                                <span class="font-semibold text-gray-900">En Consultorio</span>
                                <p class="text-sm text-gray-500">Asistir al consultorio</p>
                            </div>
                        </label>
                        <label id="opcion-domicilio" class="hidden flex items-center gap-3 p-4 border-2 rounded-xl cursor-pointer hover:border-emerald-500 has-[:checked]:border-emerald-500 has-[:checked]:bg-emerald-50">
                            <input type="radio" name="tipo_consulta" value="Domicilio" class="w-5 h-5 text-emerald-600">
                            <div>
                                <span class="font-semibold text-gray-900">A Domicilio</span>
                                <p class="text-sm text-gray-500">Visita a domicilio</p>
                            </div>
                        </label>
                    </div>
                    <div id="aviso-domicilio" class="mt-4 p-4 bg-amber-50 border border-amber-200 rounded-xl hidden">
                        <p class="text-sm text-amber-700"><i class="bi bi-exclamation-triangle"></i> Tarifa adicional: <strong id="tarifa-extra-valor">$0.00</strong></p>
                    </div>
                </div>

                <!-- MOTIVO -->
                <div class="card p-6">
                    <h3 class="text-lg font-display font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="bi bi-chat-left-text text-rose-600"></i>
                        Motivo de Consulta
                    </h3>
                    <textarea name="motivo" rows="3" class="form-textarea" placeholder="Describa los síntomas o motivo..."></textarea>
                </div>
            </div>

            <!-- SIDEBAR RESUMEN -->
            <div class="space-y-6">
                <div class="card p-6 sticky top-24">
                    <h3 class="text-lg font-display font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="bi bi-receipt text-blue-600"></i>
                        Resumen
                    </h3>
                    <div class="space-y-3 text-sm">
                        <div class="p-3 bg-gray-50 rounded-lg">
                            <p class="text-xs text-gray-500">Tipo de Cita</p>
                            <p class="font-semibold" id="resumen-tipo">-</p>
                        </div>
                        <div class="p-3 bg-blue-50 rounded-lg">
                            <p class="text-xs text-gray-500">Modalidad</p>
                            <p class="font-semibold" id="resumen-modalidad">En Consultorio</p>
                        </div>
                        <div class="p-3 bg-purple-50 rounded-lg">
                            <p class="text-xs text-gray-500">Especialidad</p>
                            <p class="font-semibold" id="resumen-especialidad">-</p>
                        </div>
                        <div class="p-3 bg-emerald-50 rounded-lg">
                            <p class="text-xs text-gray-500">Médico</p>
                            <p class="font-semibold" id="resumen-medico">-</p>
                        </div>
                        <div class="p-3 bg-amber-50 rounded-lg">
                            <p class="text-xs text-gray-500">Consultorio</p>
                            <p class="font-semibold" id="resumen-consultorio">-</p>
                            <p class="text-xs text-gray-500" id="resumen-consultorio-direccion"></p>
                        </div>
                        <div class="p-3 bg-sky-50 rounded-lg">
                            <p class="text-xs text-gray-500">Fecha y Hora</p>
                            <p class="font-semibold" id="resumen-fecha">-</p>
                        </div>
                        <div class="p-4 bg-gradient-to-r from-green-50 to-emerald-50 rounded-lg border border-green-200">
                            <p class="text-xs text-gray-500">Tarifa Total</p>
                            <p class="text-2xl font-bold text-green-700" id="resumen-tarifa">$0.00</p>
                            <span class="text-xs text-gray-500" id="resumen-tarifa-detalle"></span>
                        </div>
                    </div>
                    <div class="mt-6 space-y-3">
                        <button type="submit" class="btn btn-success w-full text-lg py-3">
                            <i class="bi bi-check-lg"></i> Confirmar Cita
                        </button>
                        <button type="button" onclick="resetForm()" class="btn btn-outline w-full">
                            <i class="bi bi-arrow-left"></i> Volver
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
    const BASE_URL = '{{ url("") }}';
    let medicoActual = null;
    let tarifaBase = 0;
    let tarifaExtra = 0;

    // Validar formulario antes de enviar
    function validarFormulario() {
        const tipoCita = document.getElementById('tipo_cita').value;
        let valid = true;
        
        if (tipoCita === 'terceros') {
            // Verificar si se seleccionó un paciente especial existente
            const pacienteEspecialExistente = document.getElementById('paciente_especial_existente_id').value;
            
            // Validar campos del representante (siempre requeridos)
            const camposRepresentante = [
                {id: 'rep_primer_nombre', msg: 'Ingrese el primer nombre del representante'},
                {id: 'rep_primer_apellido', msg: 'Ingrese el primer apellido del representante'},
                {id: 'rep_numero_documento', msg: 'Ingrese el número de documento del representante'},
                {id: 'rep_parentesco', msg: 'Seleccione el parentesco'},
            ];
            
            camposRepresentante.forEach(campo => {
                const el = document.getElementById(campo.id);
                if (!el.value.trim()) {
                    mostrarError(campo.id, campo.msg);
                    valid = false;
                }
            });
            
            // Solo validar campos del paciente especial si NO se seleccionó uno existente
            if (!pacienteEspecialExistente) {
                const camposPaciente = [
                    {id: 'pac_primer_nombre', msg: 'Ingrese el primer nombre del paciente'},
                    {id: 'pac_primer_apellido', msg: 'Ingrese el primer apellido del paciente'},
                ];
                
                camposPaciente.forEach(campo => {
                    const el = document.getElementById(campo.id);
                    if (!el.value.trim()) {
                        mostrarError(campo.id, campo.msg);
                        valid = false;
                    }
                });
                
                // Validar tipo de paciente
                if (!document.querySelector('input[name="pac_tipo"]:checked')) {
                    document.getElementById('pac_tipo_error').textContent = 'Seleccione el tipo de paciente';
                    document.getElementById('pac_tipo_error').classList.remove('hidden');
                    valid = false;
                }
                
                // Validar tiene documento
                if (!document.querySelector('input[name="pac_tiene_documento"]:checked')) {
                    document.getElementById('pac_tiene_documento_error').textContent = 'Seleccione si tiene documento';
                    document.getElementById('pac_tiene_documento_error').classList.remove('hidden');
                    valid = false;
                }
            }
        }
        
        if (!valid) {
            alert('Por favor complete todos los campos requeridos');
        }
        
        return valid;
    }
    
    function mostrarError(fieldId, mensaje) {
        const field = document.getElementById(fieldId);
        let errorEl = field.nextElementSibling;
        if (errorEl && errorEl.classList.contains('error-message')) {
            errorEl.textContent = mensaje;
            errorEl.classList.remove('hidden');
        }
        field.classList.add('border-red-500');
    }

    function selectTipoCita(tipo) {
        document.getElementById('tipo_cita').value = tipo;
        document.getElementById('step-tipo').classList.add('hidden');
        document.getElementById('citaForm').classList.remove('hidden');
        
        if (tipo === 'propia') {
            document.getElementById('datos-propios').classList.remove('hidden');
            document.getElementById('datos-representante').classList.add('hidden');
            document.getElementById('datos-paciente-especial').classList.add('hidden');
            // Ocultar sección de select si existe
            const seccionSelect = document.getElementById('seccion-select-paciente-especial');
            if (seccionSelect) seccionSelect.classList.add('hidden');
            document.getElementById('resumen-tipo').textContent = 'Cita Propia';
        } else {
            document.getElementById('datos-propios').classList.add('hidden');
            document.getElementById('datos-representante').classList.remove('hidden');
            
            // Si hay pacientes especiales registrados, mostrar el select
            const seccionSelect = document.getElementById('seccion-select-paciente-especial');
            if (seccionSelect) {
                seccionSelect.classList.remove('hidden');
                document.getElementById('datos-paciente-especial').classList.add('hidden');
            } else {
                // Si no hay, mostrar directamente el formulario
                document.getElementById('datos-paciente-especial').classList.remove('hidden');
            }
            document.getElementById('resumen-tipo').textContent = 'Cita para Terceros';
        }
    }
    
    function resetForm() {
        document.getElementById('step-tipo').classList.remove('hidden');
        document.getElementById('citaForm').classList.add('hidden');
        document.getElementById('datos-propios').classList.add('hidden');
        document.getElementById('datos-representante').classList.add('hidden');
        document.getElementById('datos-paciente-especial').classList.add('hidden');
        // Ocultar y resetear sección de select si existe
        const seccionSelect = document.getElementById('seccion-select-paciente-especial');
        if (seccionSelect) {
            seccionSelect.classList.add('hidden');
            document.getElementById('select_paciente_especial').value = '';
            document.getElementById('paciente_especial_seleccionado_card').classList.add('hidden');
        }
        document.getElementById('paciente_especial_existente_id').value = '';
    }
    
    // Seleccionar paciente especial existente
    function seleccionarPacienteEspecial(value) {
        const cardDisplay = document.getElementById('paciente_especial_seleccionado_card');
        const formNuevo = document.getElementById('datos-paciente-especial');
        const hiddenInput = document.getElementById('paciente_especial_existente_id');
        
        if (value === 'nuevo') {
            // Mostrar formulario de nuevo paciente
            cardDisplay.classList.add('hidden');
            formNuevo.classList.remove('hidden');
            hiddenInput.value = '';
        } else if (value) {
            // Paciente existente seleccionado
            const select = document.getElementById('select_paciente_especial');
            const selectedOption = select.options[select.selectedIndex];
            
            const nombre = selectedOption.dataset.nombre;
            const tipo = selectedOption.dataset.tipo;
            const documento = selectedOption.dataset.documento;
            
            // Generar iniciales
            const palabras = nombre.split(' ');
            const iniciales = palabras.length >= 2 
                ? (palabras[0][0] + palabras[palabras.length - 1][0]).toUpperCase()
                : nombre.substring(0, 2).toUpperCase();
            
            // Actualizar tarjeta
            document.getElementById('pac_esp_iniciales_display').textContent = iniciales;
            document.getElementById('pac_esp_nombre_display').textContent = nombre;
            document.getElementById('pac_esp_info_display').textContent = tipo + ' | ' + documento;
            
            // Mostrar tarjeta, ocultar formulario
            cardDisplay.classList.remove('hidden');
            formNuevo.classList.add('hidden');
            hiddenInput.value = value;
        } else {
            // Nada seleccionado
            cardDisplay.classList.add('hidden');
            formNuevo.classList.add('hidden');
            hiddenInput.value = '';
        }
    }
    
    // Limpiar paciente especial seleccionado
    function limpiarPacienteEspecialSeleccionado() {
        document.getElementById('select_paciente_especial').value = '';
        document.getElementById('paciente_especial_seleccionado_card').classList.add('hidden');
        document.getElementById('paciente_especial_existente_id').value = '';
    }

    function toggleDocumento(tiene) {
        const campoDoc = document.getElementById('campo-documento-paciente');
        const infoGenerado = document.getElementById('doc-generado-info');
        
        if (tiene) {
            campoDoc.classList.remove('hidden');
            infoGenerado.classList.add('hidden');
        } else {
            campoDoc.classList.add('hidden');
            infoGenerado.classList.remove('hidden');
            actualizarPreviewDocumento();
        }
    }

    async function actualizarPreviewDocumento() {
        const tipoDoc = document.getElementById('rep_tipo_documento').value;
        const numDoc = document.getElementById('rep_numero_documento').value;
        const preview = document.getElementById('doc-generado-preview');
        
        if (!numDoc) {
            preview.textContent = 'Documento no disponible';
            return;
        }

        preview.textContent = 'Generando...';
        
        try {
            const response = await fetch(BASE_URL + '/ajax/citas/get-next-sequence/' + numDoc);
            if (!response.ok) throw new Error('Error en API');
            
            const data = await response.json();
            // Data.full_id trae "12345678-02", le agregamos el tipo
            preview.textContent = tipoDoc + '-' + data.full_id;
        } catch(e) {
            console.error('Error obteniendo secuencia:', e);
            preview.textContent = tipoDoc + '-' + numDoc + '-01'; // Fallback
        }
    }

    // Listeners para actualizar si cambia el representante (aunque sea readonly para el paciente actual, es bueno tenerlo)
    document.getElementById('rep_numero_documento')?.addEventListener('change', actualizarPreviewDocumento);
    document.getElementById('rep_tipo_documento')?.addEventListener('change', actualizarPreviewDocumento);

    function toggleDireccionPaciente() {
        const checkbox = document.getElementById('misma_direccion');
        const campos = document.getElementById('campos-ubicacion-paciente');
        const inputHidden = document.getElementById('misma_direccion_input');
        
        if (checkbox.checked) {
            campos.classList.add('hidden');
            inputHidden.value = '1';
        } else {
            campos.classList.remove('hidden');
            inputHidden.value = '0';
        }
    }

    // Cargar ciudades para paciente
    async function cargarCiudadesPac() {
        const estadoId = document.getElementById('pac_estado_id').value;
        const ciudadSelect = document.getElementById('pac_ciudad_id');
        
        if (!estadoId) {
            ciudadSelect.innerHTML = '<option value="">Primero seleccione estado...</option>';
            return;
        }
        
        try {
            const response = await fetch(BASE_URL + '/ubicacion/get-ciudades/' + estadoId);
            const ciudades = await response.json();
            ciudadSelect.innerHTML = '<option value="">Seleccionar ciudad...</option>';
            ciudades.forEach(c => {
                ciudadSelect.innerHTML += `<option value="${c.id_ciudad}">${c.ciudad}</option>`;
            });
        } catch(e) {
            console.error('Error cargando ciudades:', e);
        }
    }

    // Cargar municipios para paciente
    async function cargarMunicipiosPac() {
        const estadoId = document.getElementById('pac_estado_id').value;
        const municipioSelect = document.getElementById('pac_municipio_id');
        
        if (!estadoId) {
            municipioSelect.innerHTML = '<option value="">Primero seleccione estado...</option>';
            return;
        }
        
        try {
            const response = await fetch(BASE_URL + '/ubicacion/get-municipios/' + estadoId);
            const municipios = await response.json();
            municipioSelect.innerHTML = '<option value="">Seleccionar municipio...</option>';
            municipios.forEach(m => {
                municipioSelect.innerHTML += `<option value="${m.id_municipio}">${m.municipio}</option>`;
            });
        } catch(e) {
            console.error('Error cargando municipios:', e);
        }
    }

    // Cargar parroquias para paciente
    async function cargarParroquiasPac() {
        const municipioId = document.getElementById('pac_municipio_id').value;
        const parroquiaSelect = document.getElementById('pac_parroquia_id');
        
        if (!municipioId) {
            parroquiaSelect.innerHTML = '<option value="">Primero seleccione municipio...</option>';
            return;
        }
        
        try {
            const response = await fetch(BASE_URL + '/ubicacion/get-parroquias/' + municipioId);
            const parroquias = await response.json();
            parroquiaSelect.innerHTML = '<option value="">Seleccionar parroquia...</option>';
            parroquias.forEach(p => {
                parroquiaSelect.innerHTML += `<option value="${p.id_parroquia}">${p.parroquia}</option>`;
            });
        } catch(e) {
            console.error('Error cargando parroquias:', e);
        }
    }

    // Filtrar consultorios por estado
    document.getElementById('estado_busqueda')?.addEventListener('change', function() {
        const estadoId = this.value;
        const consultorioSelect = document.getElementById('consultorio_id');
        const opciones = consultorioSelect.querySelectorAll('option');
        
        opciones.forEach(opt => {
            if (!opt.value) return;
            opt.style.display = (!estadoId || opt.dataset.estado == estadoId) ? '' : 'none';
        });
        consultorioSelect.value = '';
    });

    // Consultorio seleccionado
    document.getElementById('consultorio_id')?.addEventListener('change', async function() {
        const consultorioId = this.value;
        const selectedOption = this.options[this.selectedIndex];
        const direccion = selectedOption?.dataset?.direccion || '';
        
        const dirEl = document.getElementById('consultorio-direccion');
        dirEl.textContent = direccion ? '📍 ' + direccion : '';
        dirEl.classList.toggle('hidden', !direccion);
        
        document.getElementById('resumen-consultorio').textContent = selectedOption?.text || '-';
        document.getElementById('resumen-consultorio-direccion').textContent = direccion;
        
        const especialidadSelect = document.getElementById('especialidad_id');
        
        if (!consultorioId) {
            especialidadSelect.disabled = true;
            especialidadSelect.innerHTML = '<option value="">Primero seleccione consultorio...</option>';
            return;
        }
        
        especialidadSelect.disabled = false;
        especialidadSelect.innerHTML = '<option value="">Cargando...</option>';
        
        try {
            const response = await fetch(BASE_URL + '/ajax/citas/especialidades-por-consultorio/' + consultorioId);
            const especialidades = await response.json();
            
            especialidadSelect.innerHTML = '<option value="">Seleccionar especialidad...</option>';
            especialidades.forEach(esp => {
                especialidadSelect.innerHTML += `<option value="${esp.id}">${esp.nombre}</option>`;
            });
        } catch (e) {
            console.error('Error:', e);
            especialidadSelect.innerHTML = '<option value="">Error al cargar</option>';
        }
    });

    // Especialidad seleccionada
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
        medicoSelect.innerHTML = '<option value="">Cargando...</option>';
        
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
            console.error('Error:', e);
            medicoSelect.innerHTML = '<option value="">Error al cargar</option>';
        }
    });

    // Médico seleccionado
    document.getElementById('medico_id')?.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        
        if (!selectedOption || !selectedOption.value) {
            document.getElementById('fecha_cita').disabled = true;
            return;
        }
        
        medicoActual = {
            id: selectedOption.value,
            nombre: selectedOption.text.split(' - ')[0],
            tarifa: parseFloat(selectedOption.dataset.tarifa) || 0,
            atiendeDomicilio: selectedOption.dataset.atiendeDomicilio === '1',
            tarifaExtra: parseFloat(selectedOption.dataset.tarifaExtra) || 0
        };
        
        tarifaBase = medicoActual.tarifa;
        tarifaExtra = 0;
        
        document.getElementById('resumen-medico').textContent = medicoActual.nombre;
        actualizarResumenTarifa();
        
        const opcionDomicilio = document.getElementById('opcion-domicilio');
        if (medicoActual.atiendeDomicilio) {
            opcionDomicilio.classList.remove('hidden');
            document.getElementById('tarifa-extra-valor').textContent = '$' + medicoActual.tarifaExtra.toFixed(2);
        } else {
            opcionDomicilio.classList.add('hidden');
            document.querySelector('input[value="Consultorio"]').checked = true;
            document.getElementById('aviso-domicilio').classList.add('hidden');
        }
        
        document.getElementById('fecha_cita').disabled = false;
    });

    // Tipo de consulta
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

    // Fecha seleccionada
    document.getElementById('fecha_cita')?.addEventListener('change', async function() {
        const fecha = this.value;
        const medicoId = document.getElementById('medico_id').value;
        const consultorioId = document.getElementById('consultorio_id').value;
        
        if (!fecha || !medicoId) return;
        
        const container = document.getElementById('horarios-container');
        container.innerHTML = '<p class="col-span-4 text-center text-gray-500 text-sm py-4">Cargando...</p>';
        
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
                btn.className = 'p-2 text-sm rounded-lg font-medium transition-all border ' + 
                    (slot.disponible ? 'bg-green-100 text-green-700 border-green-200 hover:bg-green-200 cursor-pointer' : 'bg-red-50 text-red-500 border-red-200 cursor-not-allowed opacity-75 line-through');
                if (slot.disponible) {
                    btn.onclick = () => selectHorario(slot.hora, btn);
                } else {
                    btn.disabled = true;
                }
                container.appendChild(btn);
            });
            
            if (data.horarios.length === 0) {
                container.innerHTML = '<p class="col-span-4 text-center text-gray-500 text-sm py-4">No hay horarios</p>';
            }
            
            document.getElementById('resumen-fecha').textContent = new Date(fecha + 'T12:00:00').toLocaleDateString('es-ES', {
                weekday: 'long', day: 'numeric', month: 'long', year: 'numeric'
            });
        } catch (e) {
            console.error('Error:', e);
            container.innerHTML = '<p class="col-span-4 text-center text-red-500 text-sm py-4">Error</p>';
        }
    });

    function selectHorario(hora, btn) {
        document.querySelectorAll('#horarios-container button').forEach(b => {
            b.classList.remove('ring-2', 'ring-blue-500', 'bg-blue-100');
        });
        btn.classList.add('ring-2', 'ring-blue-500', 'bg-blue-100');
        document.getElementById('hora_inicio').value = hora;
        
        const fechaEl = document.getElementById('resumen-fecha');
        fechaEl.textContent = fechaEl.textContent.split(' - ')[0] + ' - ' + hora;
    }

    function actualizarResumenTarifa() {
        const total = tarifaBase + tarifaExtra;
        document.getElementById('resumen-tarifa').textContent = '$' + total.toFixed(2);
        document.getElementById('resumen-tarifa-detalle').textContent = tarifaExtra > 0 ? 
            `($${tarifaBase.toFixed(2)} + $${tarifaExtra.toFixed(2)} domicilio)` : '';
    }
</script>
@endpush
@endsection
