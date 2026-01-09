@extends('layouts.admin')

@section('title', 'Editar Usuario')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center gap-4">
        <a href="{{ route('usuarios.index') }}" class="btn btn-outline">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div>
            <h1 class="text-2xl font-display font-bold text-gray-900">Editar Usuario</h1>
            <p class="text-gray-600 mt-1">Actualizar información del usuario</p>
        </div>
    </div>

    @php
        // Helper to get specific profile data
        $perfil = $usuario->administrador ?? $usuario->medico ?? $usuario->paciente;
    @endphp

    <form id="editUserForm" action="{{ route('usuarios.update', $usuario->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Form -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Información Personal -->
                <div class="card p-6">
                    <h3 class="text-lg font-display font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="bi bi-person text-blue-600"></i>
                        Información Personal
                    </h3>

                    <div class="space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="form-label form-label-required">Primer Nombre</label>
                                <input type="text" name="primer_nombre" class="input" value="{{ old('primer_nombre', $perfil->primer_nombre ?? '') }}" required>
                            </div>
                            <div>
                                <label class="form-label">Segundo Nombre</label>
                                <input type="text" name="segundo_nombre" class="input" value="{{ old('segundo_nombre', $perfil->segundo_nombre ?? '') }}">
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="form-label form-label-required">Primer Apellido</label>
                                <input type="text" name="primer_apellido" class="input" value="{{ old('primer_apellido', $perfil->primer_apellido ?? '') }}" required>
                            </div>
                            <div>
                                <label class="form-label">Segundo Apellido</label>
                                <input type="text" name="segundo_apellido" class="input" value="{{ old('segundo_apellido', $perfil->segundo_apellido ?? '') }}">
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="form-label form-label-required">Cédula / Documento</label>
                                <div class="flex gap-2">
                                    <select name="tipo_documento" class="form-select w-24" required>
                                        <option value="V" {{ old('tipo_documento', $perfil->tipo_documento ?? '') == 'V' ? 'selected' : '' }}>V</option>
                                        <option value="E" {{ old('tipo_documento', $perfil->tipo_documento ?? '') == 'E' ? 'selected' : '' }}>E</option>
                                        <option value="P" {{ old('tipo_documento', $perfil->tipo_documento ?? '') == 'P' ? 'selected' : '' }}>P</option>
                                        <option value="J" {{ old('tipo_documento', $perfil->tipo_documento ?? '') == 'J' ? 'selected' : '' }}>J</option>
                                    </select>
                                    <input type="text" name="numero_documento" class="input flex-1" placeholder="12345678" value="{{ old('numero_documento', $perfil->numero_documento ?? '') }}" required>
                                </div>
                            </div>
                            <div>
                                <label class="form-label">Fecha de Nacimiento</label>
                                <input type="date" name="fecha_nacimiento" class="input" value="{{ old('fecha_nacimiento', $perfil->fecha_nac ?? '') }}">
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="form-label">Género</label>
                                <select name="genero" class="form-select">
                                    <option value="">Seleccionar...</option>
                                    <option value="masculino" {{ old('genero', $perfil->genero ?? '') == 'masculino' ? 'selected' : '' }}>Masculino</option>
                                    <option value="femenino" {{ old('genero', $perfil->genero ?? '') == 'femenino' ? 'selected' : '' }}>Femenino</option>
                                </select>
                            </div>
                            <div>
                                <label class="form-label">Teléfono</label>
                                <div class="flex gap-2">
                                    <select name="prefijo_tlf" class="form-select w-24">
                                        <option value="+58" {{ old('prefijo_tlf', $perfil->prefijo_tlf ?? '') == '+58' ? 'selected' : '' }}>+58</option>
                                        <option value="+57" {{ old('prefijo_tlf', $perfil->prefijo_tlf ?? '') == '+57' ? 'selected' : '' }}>+57</option>
                                        <option value="+1" {{ old('prefijo_tlf', $perfil->prefijo_tlf ?? '') == '+1' ? 'selected' : '' }}>+1</option>
                                        <option value="+34" {{ old('prefijo_tlf', $perfil->prefijo_tlf ?? '') == '+34' ? 'selected' : '' }}>+34</option>
                                    </select>
                                    <input type="tel" name="telefono" class="input flex-1" placeholder="412-1234567" value="{{ old('telefono', $perfil->numero_tlf ?? '') }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Información de Ubicación -->
                <div class="card p-6">
                    <h3 class="text-lg font-display font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="bi bi-geo-alt text-rose-600"></i>
                        Información de Ubicación
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="form-label">Estado</label>
                            <select name="estado_id" id="estado_id" class="form-select">
                                <option value="">Seleccionar Estado...</option>
                                @foreach($estados as $estado)
                                    <option value="{{ $estado->id_estado }}" {{ old('estado_id', $perfil->estado_id ?? '') == $estado->id_estado ? 'selected' : '' }}>
                                        {{ $estado->estado }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="form-label">Ciudad</label>
                            <select name="ciudad_id" id="ciudad_id" class="form-select" {{ empty($perfil->estado_id) ? 'disabled' : '' }}>
                                <option value="">{{ empty($perfil->estado_id) ? 'Seleccione un Estado primero' : 'Cargando/Seleccionar...' }}</option>
                            </select>
                        </div>
                        <div>
                            <label class="form-label">Municipio</label>
                            <select name="municipio_id" id="municipio_id" class="form-select" {{ empty($perfil->estado_id) ? 'disabled' : '' }}>
                                <option value="">{{ empty($perfil->estado_id) ? 'Seleccione un Estado primero' : 'Cargando/Seleccionar...' }}</option>
                            </select>
                        </div>
                        <div>
                            <label class="form-label">Parroquia</label>
                            <select name="parroquia_id" id="parroquia_id" class="form-select" {{ empty($perfil->municipio_id) ? 'disabled' : '' }}>
                                <option value="">{{ empty($perfil->municipio_id) ? 'Seleccione un Municipio primero' : 'Cargando/Seleccionar...' }}</option>
                            </select>
                        </div>
                        <div class="md:col-span-2">
                            <label class="form-label">Dirección Detallada</label>
                            <textarea name="direccion_detallada" class="input resize-none" rows="2" placeholder="Av. Principal...">{{ old('direccion_detallada', $perfil->direccion_detallada ?? '') }}</textarea>
                        </div>
                    </div>
                </div>

                @if($usuario->medico)
                <!-- Campos Específicos: Médico -->
                <div class="card p-6">
                    <h3 class="text-lg font-display font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="bi bi-heart-pulse text-indigo-600"></i>
                        Información Profesional (Médico)
                    </h3>
                    <div class="space-y-4">
                        <div>
                            <label class="form-label">Nro. Colegiatura</label>
                            <input type="text" name="nro_colegiatura" class="input" placeholder="MPPS-12345" value="{{ old('nro_colegiatura', $perfil->nro_colegiatura ?? '') }}">
                        </div>
                        <div>
                            <label class="form-label">Formación Académica</label>
                            <textarea name="formacion_academica" class="input resize-none" rows="2" placeholder="Universidad, Especializaciones...">{{ old('formacion_academica', $perfil->formacion_academica ?? '') }}</textarea>
                        </div>
                        <div>
                            <label class="form-label">Experiencia Profesional</label>
                            <textarea name="experiencia_profesional" class="input resize-none" rows="2" placeholder="Experiencia previa...">{{ old('experiencia_profesional', $perfil->experiencia_profesional ?? '') }}</textarea>
                        </div>
                    </div>
                </div>
                @endif

                @if($usuario->paciente)
                <!-- Campos Específicos: Paciente -->
                <div class="card p-6">
                    <h3 class="text-lg font-display font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="bi bi-person-heart text-teal-600"></i>
                        Información Adicional (Paciente)
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="form-label">Ocupación</label>
                            <input type="text" name="ocupacion" class="input" placeholder="Estudiante, Ingeniero..." value="{{ old('ocupacion', $perfil->ocupacion ?? '') }}">
                        </div>
                        <div>
                            <label class="form-label">Estado Civil</label>
                            <select name="estado_civil" class="form-select">
                                <option value="">Seleccionar...</option>
                                <option value="Soltero" {{ old('estado_civil', $perfil->estado_civil ?? '') == 'Soltero' ? 'selected' : '' }}>Soltero</option>
                                <option value="Casado" {{ old('estado_civil', $perfil->estado_civil ?? '') == 'Casado' ? 'selected' : '' }}>Casado</option>
                                <option value="Divorciado" {{ old('estado_civil', $perfil->estado_civil ?? '') == 'Divorciado' ? 'selected' : '' }}>Divorciado</option>
                                <option value="Viudo" {{ old('estado_civil', $perfil->estado_civil ?? '') == 'Viudo' ? 'selected' : '' }}>Viudo</option>
                            </select>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Credenciales -->
                <div class="card p-6">
                    <h3 class="text-lg font-display font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="bi bi-shield-check text-emerald-600"></i>
                        Credenciales de Acceso
                    </h3>

                    <div class="space-y-4">
                        <div>
                            <label class="form-label">Email</label>
                            <input type="email" class="input bg-gray-100 text-gray-500 cursor-not-allowed" 
                                   value="{{ $usuario->correo }}" 
                                   readonly disabled>
                            <p class="text-xs text-gray-400 mt-1">El correo no es modificable</p>
                        </div>

                        <div class="p-4 bg-yellow-50 border border-yellow-200 rounded-xl mb-4">
                            <div class="flex items-start gap-3">
                                <i class="bi bi-info-circle text-yellow-600 mt-1"></i>
                                <div>
                                    <p class="text-sm font-semibold text-yellow-800">Cambio de Contraseña</p>
                                    <p class="text-sm text-yellow-700">Deje los campos de contraseña en blanco si no desea cambiarla.</p>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="form-label">Nueva Contraseña</label>
                                <input type="password" name="password" class="input" placeholder="••••••••">
                            </div>
                            <div>
                                <label class="form-label">Confirmar Contraseña</label>
                                <input type="password" name="password_confirmation" class="input" placeholder="••••••••">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Rol -->
                <div class="card p-6">
                    <h3 class="text-lg font-display font-bold text-gray-900 mb-4">Rol de Usuario</h3>
                    <div class="space-y-3">
                        <div class="p-4 bg-gray-50 border border-gray-100 rounded-xl">
                            <p class="text-sm text-gray-500 mb-1">Rol Actual</p>
                            <p class="font-bold text-gray-900 text-lg">{{ $usuario->rol->nombre_rol }}</p>
                            <p class="text-xs text-gray-400 mt-2">
                                <i class="bi bi-lock-fill mr-1"></i>
                                El rol no se puede modificar
                            </p>
                        </div>
                        <input type="hidden" name="rol_id" value="{{ $usuario->rol_id }}">
                    </div>
                </div>

                <!-- Estado -->
                <div class="card p-6">
                    <h3 class="text-lg font-display font-bold text-gray-900 mb-4">Estado</h3>
                    <div class="space-y-3">
                        <label class="flex items-center gap-3 p-3 border-2 border-gray-200 rounded-xl cursor-pointer hover:bg-gray-50">
                            <input type="radio" name="status" value="1" class="form-radio" {{ old('status', $usuario->status) == 1 ? 'checked' : '' }}>
                            <div>
                                <p class="font-semibold text-gray-900">Activo</p>
                                <p class="text-sm text-gray-600">Usuario habilitado</p>
                            </div>
                        </label>
                        <label class="flex items-center gap-3 p-3 border-2 border-gray-200 rounded-xl cursor-pointer hover:bg-gray-50">
                            <input type="radio" name="status" value="0" class="form-radio" {{ old('status', $usuario->status) == 0 ? 'checked' : '' }}>
                            <div>
                                <p class="font-semibold text-gray-900">Inactivo</p>
                                <p class="text-sm text-gray-600">Usuario deshabilitado</p>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Actions -->
                <div class="card p-6">
                    <h3 class="text-lg font-display font-bold text-gray-900 mb-4">Acciones</h3>
                    <div class="space-y-3">
                        <button type="submit" class="btn btn-primary w-full">
                            <i class="bi bi-check-lg"></i>
                            Guardar Cambios
                        </button>
                        <a href="{{ route('usuarios.index') }}" class="btn btn-outline w-full">
                            <i class="bi bi-x-lg"></i>
                            Cancelar
                        </a>
                    </div>
                </div>

                <!-- Info -->
                <div class="card p-6">
                     <h3 class="text-lg font-display font-bold text-gray-900 mb-4 text-center">Detalles</h3>
                     <div class="space-y-2 text-sm text-center">
                        <p class="text-gray-500">Registrado el: <span class="font-semibold text-gray-700">{{ $usuario->created_at->format('d/m/Y') }}</span></p>
                        <p class="text-gray-500">Última actualización: <span class="font-semibold text-gray-700">{{ $usuario->updated_at->diffForHumans() }}</span></p>
                     </div>
                </div>
            </div>
        </div>
    </form>

    <!-- Error Modal -->
    <div id="errorModal" class="fixed inset-0 z-[60] hidden" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm transition-opacity opacity-0" id="errorModalBackdrop"></div>
        <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" id="errorModalPanel">
                    <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                <i class="bi bi-exclamation-triangle text-red-600 text-xl"></i>
                            </div>
                            <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left w-full">
                                <h3 class="text-lg font-semibold leading-6 text-gray-900" id="modal-title">No se puede actualizar el usuario</h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500 mb-3">
                                        Por favor, corrija los siguientes errores antes de continuar:
                                    </p>
                                    <ul id="errorList" class="text-sm text-red-600 list-disc list-inside space-y-1 bg-red-50 p-3 rounded-lg border border-red-100">
                                        <!-- Errors injected via JS -->
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                        <button type="button" id="closeErrorModal" class="inline-flex w-full justify-center rounded-xl bg-gray-900 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-black sm:ml-3 sm:w-auto transition-colors">
                            Entendido
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Data for pre-filling locations if present
        const initialData = {
            estado_id: "{{ old('estado_id', $perfil->estado_id ?? '') }}",
            ciudad_id: "{{ old('ciudad_id', $perfil->ciudad_id ?? '') }}",
            municipio_id: "{{ old('municipio_id', $perfil->municipio_id ?? '') }}",
            parroquia_id: "{{ old('parroquia_id', $perfil->parroquia_id ?? '') }}"
        };

        // Location Logic
        const estadoSelect = document.getElementById('estado_id');
        const ciudadSelect = document.getElementById('ciudad_id');
        const municipioSelect = document.getElementById('municipio_id');
        const parroquiaSelect = document.getElementById('parroquia_id');

        // Function to load dependent dropdowns
        async function loadDropdowns(type, parentId, selectedId = null) {
            if (!parentId) return;
            try {
                let pluralType = type + 's';
                if (type === 'ciudad') pluralType = 'ciudades';
                
                const response = await fetch(`{{ url('ubicacion') }}/get-${pluralType}/${parentId}`);
                const data = await response.json();
                
                let targetSelect, label;
                if(type === 'ciudad') { targetSelect = ciudadSelect; label = 'Ciudad'; }
                if(type === 'municipio') { targetSelect = municipioSelect; label = 'Municipio'; }
                if(type === 'parroquia') { targetSelect = parroquiaSelect; label = 'Parroquia'; }

                targetSelect.innerHTML = `<option value="">Seleccionar ${label}...</option>`;
                data.forEach(item => {
                    const id = item[`id_${type}`]; // e.g. id_ciudad
                    const name = item[type];       // e.g. ciudad
                    targetSelect.innerHTML += `<option value="${id}" ${selectedId == id ? 'selected' : ''}>${name}</option>`;
                });
                targetSelect.disabled = false;
            } catch (e) {
                console.error('Error loading location data:', e);
            }
        }

        // Initialize locations if editing existing data
        if (initialData.estado_id) {
            loadDropdowns('ciudad', initialData.estado_id, initialData.ciudad_id);
            loadDropdowns('municipio', initialData.estado_id, initialData.municipio_id);
        }
        if (initialData.municipio_id) {
            loadDropdowns('parroquia', initialData.municipio_id, initialData.parroquia_id);
        }

        if(estadoSelect) {
            estadoSelect.addEventListener('change', function() {
                const estadoId = this.value;
                ciudadSelect.innerHTML = '<option value="">Cargando...</option>';
                municipioSelect.innerHTML = '<option value="">Cargando...</option>';
                parroquiaSelect.innerHTML = '<option value="">Seleccione un Municipio primero</option>';
                ciudadSelect.disabled = true;
                municipioSelect.disabled = true;
                parroquiaSelect.disabled = true;

                if (estadoId) {
                    loadDropdowns('ciudad', estadoId);
                    loadDropdowns('municipio', estadoId);
                } else {
                    ciudadSelect.innerHTML = '<option value="">Seleccione un Estado primero</option>';
                    municipioSelect.innerHTML = '<option value="">Seleccione un Estado primero</option>';
                }
            });

            municipioSelect.addEventListener('change', function() {
                const municipioId = this.value;
                parroquiaSelect.innerHTML = '<option value="">Cargando...</option>';
                parroquiaSelect.disabled = true;
                if (municipioId) {
                    loadDropdowns('parroquia', municipioId);
                } else {
                    parroquiaSelect.innerHTML = '<option value="">Seleccione un Municipio primero</option>';
                }
            });
        }

        // Validation Logic
        const form = document.getElementById('editUserForm');
        const errorModal = document.getElementById('errorModal');
        const errorModalBackdrop = document.getElementById('errorModalBackdrop');
        const errorModalPanel = document.getElementById('errorModalPanel');
        const errorList = document.getElementById('errorList');
        const closeErrorModalBtn = document.getElementById('closeErrorModal');

        // Validation Rules
        const validations = {
            primer_nombre: { 
                required: true, 
                pattern: /^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/, 
                message: 'Ingrese un nombre válido (solo letras)' 
            },
            segundo_nombre: { 
                required: false, 
                pattern: /^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/, 
                message: 'El segundo nombre solo debe contener letras' 
            },
            primer_apellido: { 
                required: true, 
                pattern: /^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/, 
                message: 'Ingrese un apellido válido (solo letras)' 
            },
            segundo_apellido: { 
                required: false, 
                pattern: /^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/, 
                message: 'El segundo apellido solo debe contener letras' 
            },
            numero_documento: { 
                required: true, 
                pattern: /^\d+$/, 
                message: 'El número de documento debe contener solo dígitos' 
            },
            telefono: {
                required: false,
                pattern: /^\d+$/, 
                message: 'El teléfono debe contener solo dígitos'
            },
            correo: { 
                required: true, 
                pattern: /^[^\s@]+@[^\s@]+\.[^\s@]+$/, 
                message: 'Ingrese un correo electrónico válido' 
            },
            password: { 
                required: false, // Not required in edit
                minLength: 8, 
                message: 'La contraseña debe tener al menos 8 caracteres' 
            },
            password_confirmation: { 
                required: false,
                custom: (val) => {
                    const pass = form.querySelector('[name="password"]').value;
                    return !pass || (val === pass); // Only validate if password is typed
                }, 
                message: 'Las contraseñas no coinciden' 
            }
        };

        // Helper to find where to append error message
        function getErrorContainer(input) {
            const parent = input.parentElement;
            if (parent.classList.contains('flex') || parent.classList.contains('gap-2')) {
                return parent.parentElement;
            }
            return parent;
        }

        // Real-time validation
        Object.keys(validations).forEach(fieldName => {
            const input = form.querySelector(`[name="${fieldName}"]`);
            if (!input) return;

            ['input', 'blur'].forEach(event => {
                input.addEventListener(event, () => validateField(input, validations[fieldName]));
            });
        });

        function validateField(input, rules) {
            const value = input.value.trim();
            let isValid = true;
            let errorMessage = '';

            // Reset styles
            input.classList.remove('border-red-500', 'focus:border-red-500', 'focus:ring-red-500');
            
            const errorContainer = getErrorContainer(input);
            const existingError = errorContainer.querySelector('.validation-error-msg');
            if (existingError) existingError.remove();

            // Check rules
            // Special case for password in edit: required only if value exists or rule says so (but rule says false)
            if (rules.required && !value) {
                isValid = false;
                errorMessage = 'Este campo es obligatorio';
            } else if (value && rules.pattern && !rules.pattern.test(value)) {
                isValid = false;
                errorMessage = rules.message;
            } else if (value && rules.minLength && value.length < rules.minLength) {
                isValid = false;
                errorMessage = rules.message;
            } else if (rules.custom && !rules.custom(value)) {
                isValid = false;
                errorMessage = rules.message;
            }

            if (!isValid) {
                input.classList.add('border-red-500', 'focus:border-red-500', 'focus:ring-red-500');
                const msg = document.createElement('p');
                msg.className = 'validation-error-msg text-xs text-red-500 mt-1 font-semibold animate-pulse';
                msg.textContent = errorMessage;
                errorContainer.appendChild(msg);
            }
            return isValid;
        }

        // Form Submit
        form.addEventListener('submit', function(e) {
            let errors = [];
            
            // Validate all fields
            Object.keys(validations).forEach(fieldName => {
                const input = form.querySelector(`[name="${fieldName}"]`);
                if (input) {
                    const rule = validations[fieldName];
                    
                    // Special check for password confirmation requirement on submit if password is filled
                    if (fieldName === 'password_confirmation') {
                        const pass = form.querySelector('[name="password"]').value;
                        if (pass && !input.value) {
                            errors.push('Debe confirmar la nueva contraseña');
                            return;
                        }
                    }

                    if (!validateField(input, rule)) {
                        let msg = rule.message;
                        const label = input.closest('.card')?.querySelector(`label[for="${input.id}"]`)?.textContent 
                                      || input.previousElementSibling?.textContent 
                                      || getErrorContainer(input).querySelector('label')?.textContent
                                      || fieldName;
                        
                        if(rule.required && !input.value.trim()) msg = `El campo ${label} es obligatorio`;
                        errors.push(msg);
                    }
                }
            });

            if (errors.length > 0) {
                e.preventDefault();
                showErrorModal(errors);
            }
        });

        function showErrorModal(errors) {
            errorList.innerHTML = '';
            const uniqueErrors = [...new Set(errors)];
            uniqueErrors.forEach(err => {
                const li = document.createElement('li');
                li.textContent = err;
                errorList.appendChild(li);
            });

            errorModal.classList.remove('hidden');
            setTimeout(() => {
                errorModalBackdrop.classList.remove('opacity-0');
                errorModalPanel.classList.remove('opacity-0', 'translate-y-4', 'sm:translate-y-0', 'sm:scale-95');
                errorModalPanel.classList.add('opacity-100', 'translate-y-0', 'sm:scale-100');
            }, 10);
        }

        function closeModal() {
            errorModalBackdrop.classList.add('opacity-0');
            errorModalPanel.classList.add('opacity-0', 'translate-y-4', 'sm:translate-y-0', 'sm:scale-95');
            errorModalPanel.classList.remove('opacity-100', 'translate-y-0', 'sm:scale-100');
            setTimeout(() => errorModal.classList.add('hidden'), 300);
        }

        closeErrorModalBtn.addEventListener('click', closeModal);
        errorModalBackdrop.addEventListener('click', closeModal);
    });
</script>
@endpush
