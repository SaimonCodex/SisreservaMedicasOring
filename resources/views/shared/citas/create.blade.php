@extends('layouts.admin')

@section('title', 'Agendar Nueva Cita')

@section('content')
<div class="mb-6">
    <a href="{{ route('citas.index') }}" class="text-medical-600 hover:text-medical-700 inline-flex items-center text-sm font-medium mb-3">
        <i class="bi bi-arrow-left mr-1"></i> Volver a Citas
    </a>
    <h2 class="text-3xl font-display font-bold text-gray-900">Agendar Nueva Cita</h2>
    <p class="text-gray-500 mt-1">Complete la información para programar la cita médica</p>
</div>

<div class="space-y-6">
    <!-- Paso 1: Tipo de Cita -->
    <div id="step-tipo" class="card p-6">
        <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-2">
            <i class="bi bi-person-check text-blue-600"></i>
            ¿Para quién es esta cita?
        </h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <button type="button" data-tipo-cita="propia" class="tipo-cita-btn p-6 border-2 border-gray-200 rounded-xl hover:border-blue-500 hover:bg-blue-50 transition-all text-left">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 rounded-full bg-blue-100 flex items-center justify-center">
                        <i class="bi bi-person-fill text-2xl text-blue-600"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-gray-900 text-lg">Cita Propia</h4>
                        <p class="text-sm text-gray-600">Para un paciente registrado o nuevo</p>
                    </div>
                </div>
            </button>
            
            <button type="button" data-tipo-cita="terceros" class="tipo-cita-btn p-6 border-2 border-gray-200 rounded-xl hover:border-emerald-500 hover:bg-emerald-50 transition-all text-left">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 rounded-full bg-emerald-100 flex items-center justify-center">
                        <i class="bi bi-people-fill text-2xl text-emerald-600"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-gray-900 text-lg">Cita para Terceros</h4>
                        <p class="text-sm text-gray-600">Menores de edad, discapacitados, etc.</p>
                    </div>
                </div>
            </button>
        </div>
    </div>

    <!-- Formulario Principal -->
    <form action="{{ route('citas.store') }}" method="POST" id="citaForm" class="space-y-6 hidden" onsubmit="return validarFormulario()">
        @csrf
        <input type="hidden" name="tipo_cita" id="tipo_cita" value="">
        <input type="hidden" name="paciente_existente" id="paciente_existente" value="0">
        <input type="hidden" name="paciente_id" id="paciente_id" value="">
        <input type="hidden" name="representante_existente" id="representante_existente" value="0">
        <input type="hidden" name="representante_id" id="representante_id_hidden" value="">
        <input type="hidden" name="paciente_especial_id" id="paciente_especial_id" value="">
        <input type="hidden" name="registrar_usuario" id="registrar_usuario" value="0">

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 space-y-6">
                
                <!-- BUSCADOR DE PACIENTE (Citas Propias) -->
                <div id="seccion-buscar-paciente" class="card p-6 border-l-4 border-l-success-500 hidden">
                    <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="bi bi-search text-success-600"></i>
                        Buscar Paciente
                    </h3>
                    
                    <div id="pac-buscador-container" class="form-group mb-4">
                        <label class="form-label">Buscar por nombre, apellido o cédula</label>
                        <div class="relative">
                            <i class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                            <input type="text" id="buscar_paciente" class="input pl-10" placeholder="Escriba para buscar..." autocomplete="off">
                        </div>
                        <div id="resultados-busqueda" class="absolute z-50 w-full bg-white border rounded-lg shadow-lg mt-1 hidden max-h-60 overflow-y-auto"></div>
                    </div>
                    
                    <!-- Alerta tipo incorrecto -->
                    <div id="alerta-tipo-incorrecto" class="hidden p-4 bg-amber-50 border border-amber-300 rounded-lg mb-4">
                        <p class="text-amber-700 text-sm"><i class="bi bi-exclamation-triangle"></i> <span id="alerta-tipo-mensaje"></span></p>
                    </div>
                    
                    <!-- Checkbox paciente no registrado -->
                    <label class="flex items-center gap-3 p-4 bg-gray-50 rounded-lg cursor-pointer hover:bg-gray-100 mt-4">
                        <input type="checkbox" id="paciente_no_registrado" class="w-5 h-5 text-blue-600 rounded" onchange="togglePacienteNoRegistrado()">
                        <div>
                            <span class="font-medium text-gray-900">El paciente NO está registrado en el sistema</span>
                            <p class="text-sm text-gray-500">Marque para ingresar los datos manualmente</p>
                        </div>
                    </label>
                    
                    <!-- Paciente seleccionado -->
                    <div id="paciente_seleccionado" class="hidden mt-4">
                        <div class="bg-success-50 border border-success-200 rounded-xl p-4">
                            <div class="flex items-center gap-4">
                                <div class="w-14 h-14 rounded-full bg-gradient-to-br from-success-500 to-success-600 flex items-center justify-center text-white text-xl font-bold" id="pac_iniciales">
                                    --
                                </div>
                                <div class="flex-1">
                                    <h4 class="font-bold text-gray-900" id="pac_nombre_display">-</h4>
                                    <p class="text-sm text-gray-600" id="pac_documento_display">-</p>
                                </div>
                                <button type="button" onclick="limpiarPacienteSeleccionado()" class="text-danger-600 hover:text-danger-700">
                                    <i class="bi bi-x-lg"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- DATOS PACIENTE NUEVO (Citas Propias) -->
                <div id="datos-paciente-nuevo" class="card p-6 border-l-4 border-l-blue-500 hidden">
                    <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="bi bi-person-plus text-blue-600"></i>
                        Datos del Nuevo Paciente
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="form-label form-label-required">Primer Nombre</label>
                            <input type="text" name="pac_primer_nombre" id="pac_primer_nombre" class="input" placeholder="Nombre" oninput="this.value = this.value.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s]/g, '')">
                            <span class="error-message text-red-500 text-xs mt-1 hidden"></span>
                        </div>
                        <div>
                            <label class="form-label">Segundo Nombre</label>
                            <input type="text" name="pac_segundo_nombre" id="pac_segundo_nombre" class="input" placeholder="Segundo nombre" oninput="this.value = this.value.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s]/g, '')">
                        </div>
                        <div>
                            <label class="form-label form-label-required">Primer Apellido</label>
                            <input type="text" name="pac_primer_apellido" id="pac_primer_apellido" class="input" placeholder="Apellido" oninput="this.value = this.value.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s]/g, '')">
                            <span class="error-message text-red-500 text-xs mt-1 hidden"></span>
                        </div>
                        <div>
                            <label class="form-label">Segundo Apellido</label>
                            <input type="text" name="pac_segundo_apellido" id="pac_segundo_apellido" class="input" placeholder="Segundo apellido" oninput="this.value = this.value.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s]/g, '')">
                        </div>
                        
                        <div>
                            <label class="form-label form-label-required">Identificación</label>
                            <div class="flex gap-2">
                                <select name="pac_tipo_documento" id="pac_tipo_documento" class="form-select w-20">
                                    <option value="V">V</option>
                                    <option value="E">E</option>
                                    <option value="P">P</option>
                                </select>
                                <input type="text" name="pac_numero_documento" id="pac_numero_documento" class="input flex-1" placeholder="12345678" maxlength="12" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                            </div>
                            <span class="error-message text-red-500 text-xs mt-1 hidden" id="pac_numero_documento_error"></span>
                        </div>
                        
                        <div>
                            <label class="form-label form-label-required">Fecha Nacimiento</label>
                            <input type="date" name="pac_fecha_nac" id="pac_fecha_nac" class="input" max="{{ date('Y-m-d') }}">
                            <span class="error-message text-red-500 text-xs mt-1 hidden"></span>
                        </div>
                        
                        <div>
                            <label class="form-label form-label-required">Género</label>
                            <select name="pac_genero" id="pac_genero" class="form-select">
                                <option value="">Seleccionar...</option>
                                <option value="Masculino">Masculino</option>
                                <option value="Femenino">Femenino</option>
                            </select>
                            <span class="error-message text-red-500 text-xs mt-1 hidden"></span>
                        </div>
                        
                        <div>
                            <label class="form-label">Teléfono</label>
                            <div class="flex gap-2">
                                <select name="pac_prefijo_tlf" class="form-select w-24">
                                    <option value="+58">+58</option>
                                    <option value="+57">+57</option>
                                    <option value="+1">+1</option>
                                </select>
                                <input type="tel" name="pac_numero_tlf" id="pac_numero_tlf" class="input flex-1" placeholder="4121234567" oninput="this.value = this.value.replace(/[^0-9]/g, '')" maxlength="10">
                            </div>
                        </div>
                        
                        <!-- Ubicación -->
                        <div>
                            <label class="form-label form-label-required">Estado</label>
                            <select name="pac_estado_id" id="pac_estado_id" class="form-select" onchange="cargarCiudadesPac(); cargarMunicipiosPac()">
                                <option value="">Seleccione...</option>
                                @foreach($estados as $estado)
                                    <option value="{{ $estado->id_estado }}">{{ $estado->estado }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="form-label">Ciudad</label>
                            <select name="pac_ciudad_id" id="pac_ciudad_id" class="form-select" disabled>
                                <option value="">Seleccione estado primero</option>
                            </select>
                        </div>
                        <div>
                            <label class="form-label">Municipio</label>
                            <select name="pac_municipio_id" id="pac_municipio_id" class="form-select" disabled onchange="cargarParroquiasPac()">
                                <option value="">Seleccione estado primero</option>
                            </select>
                        </div>
                        <div>
                            <label class="form-label">Parroquia</label>
                            <select name="pac_parroquia_id" id="pac_parroquia_id" class="form-select" disabled>
                                <option value="">Seleccione municipio primero</option>
                            </select>
                        </div>
                        <div class="md:col-span-2">
                            <label class="form-label">Dirección Detallada</label>
                            <textarea name="pac_direccion_detallada" id="pac_direccion_detallada" class="form-textarea" rows="2" placeholder="Calle, avenida, edificio..."></textarea>
                        </div>
                    </div>

                    <!-- Checkbox registrar en sistema -->
                    <div class="mt-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="checkbox" id="chk_registrar_usuario" class="w-5 h-5 text-blue-600 rounded" onchange="toggleRegistrarUsuario()">
                            <div>
                                <span class="font-medium text-gray-900">Registrar paciente en el sistema</span>
                                <p class="text-sm text-gray-500">El paciente podrá iniciar sesión con correo y contraseña</p>
                            </div>
                        </label>
                        
                        <div id="campos_registro_usuario" class="hidden mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="form-label form-label-required">Correo Electrónico</label>
                                <input type="email" name="pac_correo" id="pac_correo" class="input" placeholder="ejemplo@email.com">
                                <span class="error-message text-red-500 text-xs mt-1 hidden"></span>
                            </div>
                            <div>
                                <label class="form-label">Contraseña (Auto-generada)</label>
                                <div class="flex gap-2">
                                    <input type="text" id="pac_password_display" class="input flex-1 bg-gray-100" readonly>
                                    <button type="button" onclick="copiarContrasena('pac_password_display')" class="btn btn-outline">
                                        <i class="bi bi-clipboard"></i>
                                    </button>
                                </div>
                                <input type="hidden" name="pac_password" id="pac_password">
                                <p class="text-xs text-gray-500 mt-1">Formato: #Documento+Nombre+Año</p>
                            </div>
                        </div>
                    </div>
                </div>

                @include('shared.citas.partials.seccion-terceros')
                @include('shared.citas.partials.seccion-consulta')

            </div>

            <!-- SIDEBAR RESUMEN -->
            @include('shared.citas.partials.sidebar-resumen')
        </div>
    </form>
</div>

@include('shared.citas.partials.scripts')
@endsection
