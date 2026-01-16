<!-- SECCIÓN TERCEROS: Representante + Paciente Especial -->
<div id="seccion-terceros" class="hidden space-y-6">

    <!-- BUSCADOR DE REPRESENTANTE -->
    <div id="seccion-buscar-representante" class="card p-6 border-l-4 border-l-purple-500">
        <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
            <i class="bi bi-person-badge text-purple-600"></i>
            Representante (Quien agenda)
        </h3>
        
        <div id="rep-buscador-container" class="form-group mb-4">
            <label class="form-label">Buscar representante existente</label>
            <div class="relative">
                <i class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                <input type="text" id="buscar_representante" class="input pl-10" placeholder="Buscar por nombre o cédula..." autocomplete="off">
            </div>
            <div id="resultados-representante" class="absolute z-50 w-full bg-white border rounded-lg shadow-lg mt-1 hidden max-h-60 overflow-y-auto"></div>
        </div>
        
        <!-- Checkbox representante no registrado -->
        <label class="flex items-center gap-3 p-4 bg-gray-50 rounded-lg cursor-pointer hover:bg-gray-100">
            <input type="checkbox" id="representante_no_registrado" class="w-5 h-5 text-purple-600 rounded" onchange="toggleRepresentanteNoRegistrado()">
            <div>
                <span class="font-medium text-gray-900">El representante NO está registrado</span>
                <p class="text-sm text-gray-500">Ingresar datos manualmente</p>
            </div>
        </label>
        
        <!-- Representante seleccionado -->
        <div id="representante_seleccionado" class="hidden mt-4">
            <div class="bg-success-50 border border-success-200 rounded-xl p-4">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 rounded-full bg-gradient-to-br from-success-500 to-success-600 flex items-center justify-center text-white text-xl font-bold" id="rep_iniciales_display">
                        --
                    </div>
                    <div class="flex-1">
                        <h4 class="font-bold text-gray-900" id="rep_nombre_display">-</h4>
                        <p class="text-sm text-gray-600" id="rep_documento_display">-</p>
                    </div>
                    <button type="button" onclick="limpiarRepresentanteSeleccionado()" class="text-danger-600 hover:text-danger-700">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- DATOS REPRESENTANTE NUEVO -->
    <div id="datos-representante-nuevo" class="card p-6 border-l-4 border-l-purple-500 hidden">
        <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
            <i class="bi bi-person-plus text-purple-600"></i>
            Datos del Representante
        </h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="form-label form-label-required">Primer Nombre</label>
                <input type="text" name="rep_primer_nombre" id="rep_primer_nombre" class="input" placeholder="Nombre" oninput="this.value = this.value.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s]/g, '')">
                <span class="error-message text-red-500 text-xs mt-1 hidden"></span>
            </div>
            <div>
                <label class="form-label">Segundo Nombre</label>
                <input type="text" name="rep_segundo_nombre" id="rep_segundo_nombre" class="input" placeholder="Segundo nombre" oninput="this.value = this.value.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s]/g, '')">
            </div>
            <div>
                <label class="form-label form-label-required">Primer Apellido</label>
                <input type="text" name="rep_primer_apellido" id="rep_primer_apellido" class="input" placeholder="Apellido" oninput="this.value = this.value.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s]/g, '')">
                <span class="error-message text-red-500 text-xs mt-1 hidden"></span>
            </div>
            <div>
                <label class="form-label">Segundo Apellido</label>
                <input type="text" name="rep_segundo_apellido" id="rep_segundo_apellido" class="input" placeholder="Segundo apellido" oninput="this.value = this.value.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s]/g, '')">
            </div>
            
            <div>
                <label class="form-label form-label-required">Identificación</label>
                <div class="flex gap-2">
                    <select name="rep_tipo_documento" id="rep_tipo_documento" class="form-select w-20">
                        <option value="V">V</option>
                        <option value="E">E</option>
                        <option value="P">P</option>
                    </select>
                    <input type="text" name="rep_numero_documento" id="rep_numero_documento" class="input flex-1" placeholder="12345678" maxlength="12" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                </div>
                <span class="error-message text-red-500 text-xs mt-1 hidden" id="rep_numero_documento_error"></span>
            </div>
            
            <div>
                <label class="form-label">Teléfono</label>
                <div class="flex gap-2">
                    <select name="rep_prefijo_tlf" class="form-select w-24">
                        <option value="+58">+58</option>
                        <option value="+57">+57</option>
                        <option value="+1">+1</option>
                    </select>
                    <input type="tel" name="rep_numero_tlf" id="rep_numero_tlf" class="input flex-1" placeholder="4121234567" oninput="this.value = this.value.replace(/[^0-9]/g, '')" maxlength="10">
                </div>
            </div>

            <div>
                <input type="date" name="rep_fecha_nac" id="rep_fecha_nac" class="input" max="{{ date('Y-m-d') }}" onchange="if(document.getElementById('chk_registrar_representante').checked) generarContrasena('rep')">
                <span class="error-message text-red-500 text-xs mt-1 hidden"></span>
            </div>
            
            <div>
                <label class="form-label form-label-required">Género</label>
                <select name="rep_genero" id="rep_genero" class="form-select">
                    <option value="">Seleccionar...</option>
                    <option value="Masculino">Masculino</option>
                    <option value="Femenino">Femenino</option>
                </select>
            </div>
            
            <!-- Ubicación Representante -->
            <div>
                <label class="form-label form-label-required">Estado</label>
                <select name="rep_estado_id" id="rep_estado_id" class="form-select" onchange="cargarCiudadesRep(); cargarMunicipiosRep()">
                    <option value="">Seleccione...</option>
                    @foreach($estados as $estado)
                        <option value="{{ $estado->id_estado }}">{{ $estado->estado }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="form-label">Ciudad</label>
                <select name="rep_ciudad_id" id="rep_ciudad_id" class="form-select" disabled>
                    <option value="">Seleccione estado primero</option>
                </select>
            </div>
            <div>
                <label class="form-label form-label-required">Municipio</label>
                <select name="rep_municipio_id" id="rep_municipio_id" class="form-select" disabled onchange="cargarParroquiasRep()">
                    <option value="">Seleccione estado primero</option>
                </select>
            </div>
            <div>
                <label class="form-label form-label-required">Parroquia</label>
                <select name="rep_parroquia_id" id="rep_parroquia_id" class="form-select" disabled>
                    <option value="">Seleccione municipio primero</option>
                </select>
            </div>
            <div class="md:col-span-2">
                <label class="form-label">Dirección Detallada</label>
                <textarea name="rep_direccion_detallada" id="rep_direccion_detallada" class="form-textarea" rows="2" placeholder="Calle, avenida, edificio..."></textarea>
            </div>
            
            <div class="md:col-span-2">
                <label class="form-label form-label-required">Parentesco con el Paciente</label>
                <select name="rep_parentesco" id="rep_parentesco" class="form-select">
                    <option value="">Seleccionar parentesco...</option>
                    <option value="Padre">Padre</option>
                    <option value="Madre">Madre</option>
                    <option value="Hijo/a">Hijo/a</option>
                    <option value="Hermano/a">Hermano/a</option>
                    <option value="Abuelo/a">Abuelo/a</option>
                    <option value="Tutor">Tutor Legal</option>
                    <option value="Otro">Otro</option>
                </select>
                <span class="error-message text-red-500 text-xs mt-1 hidden"></span>
            </div>
        </div>
        
        <!-- Checkbox registrar representante -->
        <div class="mt-6 p-4 bg-purple-50 border border-purple-200 rounded-lg">
            <label class="flex items-center gap-3 cursor-pointer">
                <input type="checkbox" name="chk_registrar_representante" id="chk_registrar_representante" class="w-5 h-5 text-purple-600 rounded" onchange="toggleRegistrarRepresentante()">
                <div>
                    <span class="font-medium text-gray-900">Registrar representante en el sistema</span>
                    <p class="text-sm text-gray-500">Podrá iniciar sesión y agendar citas</p>
                </div>
            </label>
            
            <div id="campos_registro_representante" class="hidden mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="form-label form-label-required">Correo Electrónico</label>
                    <input type="email" name="rep_correo" id="rep_correo" class="input" placeholder="ejemplo@email.com">
                    <span class="error-message text-red-500 text-xs mt-1 hidden"></span>
                </div>
                <div>
                    <label class="form-label">Contraseña (Auto-generada)</label>
                    <div class="flex gap-2">
                        <input type="text" id="rep_password_display" class="input flex-1 bg-gray-100" readonly>
                        <button type="button" onclick="copiarContrasena('rep_password_display')" class="btn btn-outline">
                            <i class="bi bi-clipboard"></i>
                        </button>
                    </div>
                    <input type="hidden" name="rep_password" id="rep_password">
                </div>
            </div>
        </div>
    </div>

    <!-- BUSCADOR DE PACIENTE ESPECIAL -->
    <div id="seccion-buscar-paciente-especial" class="card p-6 border-l-4 border-l-rose-500">
        <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
            <i class="bi bi-person-heart text-rose-600"></i>
            Paciente Especial
        </h3>
        
        <!-- SELECT PACIENTES ESPECIALES DEL REPRESENTANTE (cargado dinámicamente) -->
        <div id="seccion-select-pac-esp-representante" class="hidden mb-4">
            <div class="p-4 bg-emerald-50 border border-emerald-200 rounded-lg">
                <label class="form-label flex items-center gap-2">
                    <i class="bi bi-person-check text-emerald-600"></i>
                    <span class="font-medium text-emerald-800">Pacientes registrados de este representante</span>
                </label>
                <select id="select_pac_esp_representante" class="form-select mt-2" onchange="seleccionarPacEspDeRepresentante(this.value)">
                    <option value="">Seleccionar paciente...</option>
                </select>
            </div>
        </div>
        
        <!-- Mensaje cuando no hay representante seleccionado o no tiene pacientes -->
        <div id="mensaje-seleccionar-representante" class="p-4 bg-gray-50 border border-gray-200 rounded-lg mb-4">
            <p class="text-gray-600 text-sm flex items-center gap-2">
                <i class="bi bi-info-circle"></i>
                <span>Seleccione o ingrese un representante primero para ver sus pacientes registrados.</span>
            </p>
        </div>
        
        <!-- Alerta tipo incorrecto -->
        <div id="alerta-pac-especial" class="hidden p-4 bg-amber-50 border border-amber-300 rounded-lg mb-4">
            <p class="text-amber-700 text-sm"><i class="bi bi-exclamation-triangle"></i> <span id="alerta-pac-especial-mensaje"></span></p>
        </div>
        
        <!-- Checkbox paciente especial no registrado -->
        <label class="flex items-center gap-3 p-4 bg-gray-50 rounded-lg cursor-pointer hover:bg-gray-100">
            <input type="checkbox" id="pac_especial_no_registrado" class="w-5 h-5 text-rose-600 rounded" onchange="togglePacEspecialNoRegistrado()">
            <div>
                <span class="font-medium text-gray-900">El paciente especial NO está registrado</span>
                <p class="text-sm text-gray-500">Ingresar datos manualmente</p>
            </div>
        </label>
        
        <!-- Paciente especial seleccionado -->
        <div id="pac_especial_seleccionado" class="hidden mt-4">
            <div class="bg-rose-50 border border-rose-200 rounded-xl p-4">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 rounded-full bg-gradient-to-br from-rose-500 to-rose-600 flex items-center justify-center text-white text-xl font-bold" id="pac_esp_iniciales">
                        --
                    </div>
                    <div class="flex-1">
                        <h4 class="font-bold text-gray-900" id="pac_esp_nombre_display">-</h4>
                        <p class="text-sm text-gray-600" id="pac_esp_documento_display">-</p>
                    </div>
                    <button type="button" onclick="limpiarPacEspecialSeleccionado()" class="text-danger-600 hover:text-danger-700">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- DATOS PACIENTE ESPECIAL NUEVO -->
    <div id="datos-paciente-especial-nuevo" class="card p-6 border-l-4 border-l-rose-500 hidden">
        <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
            <i class="bi bi-person-plus text-rose-600"></i>
            Datos del Paciente Especial
        </h3>
        
        <!-- Tipo de Paciente -->
        <div class="mb-6">
            <label class="form-label form-label-required">Tipo de Paciente</label>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-2">
                <label class="flex items-center gap-2 p-3 border rounded-lg cursor-pointer hover:bg-rose-50 has-[:checked]:border-rose-500 has-[:checked]:bg-rose-50">
                    <input type="radio" name="pac_esp_tipo" value="Menor de Edad" class="text-rose-600">
                    <span class="text-sm">Menor de Edad</span>
                </label>
                <label class="flex items-center gap-2 p-3 border rounded-lg cursor-pointer hover:bg-rose-50 has-[:checked]:border-rose-500 has-[:checked]:bg-rose-50">
                    <input type="radio" name="pac_esp_tipo" value="Discapacitado" class="text-rose-600">
                    <span class="text-sm">Discapacitado</span>
                </label>
                <label class="flex items-center gap-2 p-3 border rounded-lg cursor-pointer hover:bg-rose-50 has-[:checked]:border-rose-500 has-[:checked]:bg-rose-50">
                    <input type="radio" name="pac_esp_tipo" value="Anciano" class="text-rose-600">
                    <span class="text-sm">Adulto Mayor</span>
                </label>
                <label class="flex items-center gap-2 p-3 border rounded-lg cursor-pointer hover:bg-rose-50 has-[:checked]:border-rose-500 has-[:checked]:bg-rose-50">
                    <input type="radio" name="pac_esp_tipo" value="Otro" class="text-rose-600">
                    <span class="text-sm">Otro</span>
                </label>
            </div>
            <span class="error-message text-red-500 text-xs mt-1 hidden" id="pac_esp_tipo_error"></span>
        </div>
        
        <!-- ¿Tiene documento? -->
        <div class="mb-6 p-4 bg-amber-50 border border-amber-200 rounded-xl">
            <label class="form-label form-label-required mb-3">¿El paciente tiene documento de identidad?</label>
            <div class="flex gap-4">
                <label class="flex items-center gap-2 p-3 border bg-white rounded-lg cursor-pointer hover:bg-green-50 has-[:checked]:border-green-500 has-[:checked]:bg-green-50">
                    <input type="radio" name="pac_esp_tiene_documento" value="si" class="text-green-600" onchange="togglePacEspDocumento(true)">
                    <span class="font-medium">Sí, tiene documento</span>
                </label>
                <label class="flex items-center gap-2 p-3 border bg-white rounded-lg cursor-pointer hover:bg-orange-50 has-[:checked]:border-orange-500 has-[:checked]:bg-orange-50">
                    <input type="radio" name="pac_esp_tiene_documento" value="no" class="text-orange-600" onchange="togglePacEspDocumento(false)">
                    <span class="font-medium">No tiene documento</span>
                </label>
            </div>
            
            <div id="pac-esp-doc-generado-info" class="mt-3 p-3 bg-white rounded-lg border border-amber-300 hidden">
                <p class="text-sm text-amber-800"><i class="bi bi-info-circle"></i> Se generará el identificador:</p>
                <p class="text-lg font-bold text-amber-900 mt-1" id="pac-esp-documento-preview">-</p>
                <input type="hidden" name="pac_esp_numero_documento_generado" id="pac_esp_numero_documento_generado">
            </div>
            <span class="error-message text-red-500 text-xs mt-1 hidden" id="pac_esp_tiene_documento_error"></span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="form-label form-label-required">Primer Nombre</label>
                <input type="text" name="pac_esp_primer_nombre" id="pac_esp_primer_nombre" class="input" placeholder="Nombre" oninput="this.value = this.value.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s]/g, '')">
                <span class="error-message text-red-500 text-xs mt-1 hidden"></span>
            </div>
            <div>
                <label class="form-label">Segundo Nombre</label>
                <input type="text" name="pac_esp_segundo_nombre" id="pac_esp_segundo_nombre" class="input" placeholder="Segundo nombre" oninput="this.value = this.value.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s]/g, '')">
            </div>
            <div>
                <label class="form-label form-label-required">Primer Apellido</label>
                <input type="text" name="pac_esp_primer_apellido" id="pac_esp_primer_apellido" class="input" placeholder="Apellido" oninput="this.value = this.value.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s]/g, '')">
                <span class="error-message text-red-500 text-xs mt-1 hidden"></span>
            </div>
            <div>
                <label class="form-label">Segundo Apellido</label>
                <input type="text" name="pac_esp_segundo_apellido" id="pac_esp_segundo_apellido" class="input" placeholder="Segundo apellido" oninput="this.value = this.value.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s]/g, '')">
            </div>
            
            <!-- Documento Paciente Especial (manual) -->
            <div id="campo-documento-pac-esp" class="hidden md:col-span-2">
                <label class="form-label form-label-required">Documento de Identidad</label>
                <div class="flex gap-2">
                    <select name="pac_esp_tipo_documento" id="pac_esp_tipo_documento" class="form-select w-20">
                        <option value="V">V</option>
                        <option value="E">E</option>
                        <option value="P">P</option>
                    </select>
                    <input type="text" name="pac_esp_numero_documento" id="pac_esp_numero_documento" class="input flex-1" 
                           placeholder="Número" maxlength="12"
                           oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                </div>
                <span class="error-message text-red-500 text-xs mt-1 hidden" id="pac_esp_numero_documento_error"></span>
            </div>
            
            <div>
                <label class="form-label form-label-required">Fecha Nacimiento</label>
                <input type="date" name="pac_esp_fecha_nac" id="pac_esp_fecha_nac" class="input" max="{{ date('Y-m-d') }}">
                <span class="error-message text-red-500 text-xs mt-1 hidden"></span>
            </div>
            
            <div>
                <label class="form-label form-label-required">Género</label>
                <select name="pac_esp_genero" id="pac_esp_genero" class="form-select">
                    <option value="">Seleccionar...</option>
                    <option value="Masculino">Masculino</option>
                    <option value="Femenino">Femenino</option>
                </select>
            </div>
        
            <!-- Ubicación Paciente Especial -->
            <div class="md:col-span-2 mt-4 grid grid-cols-1 md:grid-cols-2 gap-4 border-t pt-4">
                <div class="md:col-span-2">
                     <h4 class="font-bold text-gray-700 mb-2">Ubicación del Paciente</h4>
                </div>
                <div>
                    <label class="form-label form-label-required">Estado</label>
                    <select name="pac_esp_estado_id" id="pac_esp_estado_id" class="form-select" onchange="cargarMunicipiosPacEsp(); cargarCiudadesPacEsp()">
                        <option value="">Seleccione...</option>
                        @foreach($estados as $estado)
                            <option value="{{ $estado->id_estado }}">{{ $estado->estado }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="form-label">Ciudad</label>
                    <select name="pac_esp_ciudad_id" id="pac_esp_ciudad_id" class="form-select" disabled>
                        <option value="">Seleccione estado primero</option>
                    </select>
                </div>
                <div>
                    <label class="form-label form-label-required">Municipio</label>
                    <select name="pac_esp_municipio_id" id="pac_esp_municipio_id" class="form-select" disabled onchange="cargarParroquiasPacEsp()">
                        <option value="">Seleccione estado primero</option>
                    </select>
                </div>
                <div>
                    <label class="form-label form-label-required">Parroquia</label>
                    <select name="pac_esp_parroquia_id" id="pac_esp_parroquia_id" class="form-select" disabled>
                        <option value="">Seleccione municipio primero</option>
                    </select>
                </div>
                <div class="md:col-span-2">
                    <label class="form-label">Dirección Detallada</label>
                    <textarea name="pac_esp_direccion_detallada" id="pac_esp_direccion_detallada" class="form-textarea" rows="2" placeholder="Calle, avenida, edificio..."></textarea>
                </div>
            </div>
        </div>
    </div>
</div>
```
