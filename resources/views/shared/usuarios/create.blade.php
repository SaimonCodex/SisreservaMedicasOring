@extends('layouts.admin')

@section('title', 'Nuevo Usuario')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center gap-4">
        <a href="{{ route('usuarios.index') }}" class="btn btn-outline">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div>
            <h1 class="text-2xl font-display font-bold text-gray-900">Nuevo Usuario</h1>
            <p class="text-gray-600 mt-1">Registrar un nuevo usuario en el sistema</p>
        </div>
    </div>

    <form id="createUserForm" action="{{ route('usuarios.store') }}" method="POST">
        @csrf

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
                                <input type="text" name="primer_nombre" class="input" value="{{ old('primer_nombre') }}" required>
                            </div>
                            <div>
                                <label class="form-label">Segundo Nombre</label>
                                <input type="text" name="segundo_nombre" class="input" value="{{ old('segundo_nombre') }}">
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="form-label form-label-required">Primer Apellido</label>
                                <input type="text" name="primer_apellido" class="input" value="{{ old('primer_apellido') }}" required>
                            </div>
                            <div>
                                <label class="form-label">Segundo Apellido</label>
                                <input type="text" name="segundo_apellido" class="input" value="{{ old('segundo_apellido') }}">
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="form-label form-label-required">Cédula / Documento</label>
                                <div class="flex gap-2">
                                    <select name="tipo_documento" class="form-select w-24" required>
                                        <option value="V" {{ old('tipo_documento') == 'V' ? 'selected' : '' }}>V</option>
                                        <option value="E" {{ old('tipo_documento') == 'E' ? 'selected' : '' }}>E</option>
                                        <option value="P" {{ old('tipo_documento') == 'P' ? 'selected' : '' }}>P</option>
                                        <option value="J" {{ old('tipo_documento') == 'J' ? 'selected' : '' }}>J</option>
                                    </select>
                                    <input type="text" name="numero_documento" class="input flex-1" placeholder="12345678" value="{{ old('numero_documento') }}" required>
                                </div>
                            </div>
                            <div>
                                <label class="form-label">Fecha de Nacimiento</label>
                                <input type="date" name="fecha_nacimiento" class="input" value="{{ old('fecha_nacimiento') }}">
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="form-label">Género</label>
                                <select name="genero" class="form-select">
                                    <option value="">Seleccionar...</option>
                                    <option value="masculino" {{ old('genero') == 'masculino' ? 'selected' : '' }}>Masculino</option>
                                    <option value="femenino" {{ old('genero') == 'femenino' ? 'selected' : '' }}>Femenino</option>
                                </select>
                            </div>
                            <div>
                                <label class="form-label">Teléfono</label>
                                <div class="flex gap-2">
                                    <select name="prefijo_tlf" class="form-select w-24">
                                        <option value="+58" {{ old('prefijo_tlf') == '+58' ? 'selected' : '' }}>+58</option>
                                        <option value="+57" {{ old('prefijo_tlf') == '+57' ? 'selected' : '' }}>+57</option>
                                        <option value="+1" {{ old('prefijo_tlf') == '+1' ? 'selected' : '' }}>+1</option>
                                        <option value="+34" {{ old('prefijo_tlf') == '+34' ? 'selected' : '' }}>+34</option>
                                    </select>
                                    <input type="tel" name="telefono" class="input flex-1" placeholder="412-1234567" value="{{ old('telefono') }}">
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
                                    <option value="{{ $estado->id_estado }}">{{ $estado->estado }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="form-label">Ciudad</label>
                            <select name="ciudad_id" id="ciudad_id" class="form-select" disabled>
                                <option value="">Seleccione un Estado primero</option>
                            </select>
                        </div>
                        <div>
                            <label class="form-label">Municipio</label>
                            <select name="municipio_id" id="municipio_id" class="form-select" disabled>
                                <option value="">Seleccione un Estado primero</option>
                            </select>
                        </div>
                        <div>
                            <label class="form-label">Parroquia</label>
                            <select name="parroquia_id" id="parroquia_id" class="form-select" disabled>
                                <option value="">Seleccione un Municipio primero</option>
                            </select>
                        </div>
                        <div class="md:col-span-2">
                            <label class="form-label">Dirección Detallada</label>
                            <textarea name="direccion_detallada" class="input resize-none" rows="2" placeholder="Av. Principal..."></textarea>
                        </div>
                    </div>
                </div>

                <!-- Campos Específicos: Médico -->
                <div id="medicoFields" class="card p-6 hidden">
                    <h3 class="text-lg font-display font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="bi bi-heart-pulse text-indigo-600"></i>
                        Información Profesional (Médico)
                    </h3>
                    <div class="space-y-4">
                        <div>
                            <label class="form-label">Nro. Colegiatura</label>
                            <input type="text" name="nro_colegiatura" class="input" placeholder="MPPS-12345" value="{{ old('nro_colegiatura') }}">
                        </div>
                        <div>
                            <label class="form-label">Formación Académica</label>
                            <textarea name="formacion_academica" class="input resize-none" rows="2" placeholder="Universidad, Especializaciones...">{{ old('formacion_academica') }}</textarea>
                        </div>
                        <div>
                            <label class="form-label">Experiencia Profesional</label>
                            <textarea name="experiencia_profesional" class="input resize-none" rows="2" placeholder="Experiencia previa...">{{ old('experiencia_profesional') }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Campos Específicos: Paciente -->
                <div id="pacienteFields" class="card p-6 hidden">
                    <h3 class="text-lg font-display font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="bi bi-person-heart text-teal-600"></i>
                        Información Adicional (Paciente)
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="form-label">Ocupación</label>
                            <input type="text" name="ocupacion" class="input" placeholder="Estudiante, Ingeniero..." value="{{ old('ocupacion') }}">
                        </div>
                        <div>
                            <label class="form-label">Estado Civil</label>
                            <select name="estado_civil" class="form-select">
                                <option value="">Seleccionar...</option>
                                <option value="Soltero" {{ old('estado_civil') == 'Soltero' ? 'selected' : '' }}>Soltero</option>
                                <option value="Casado" {{ old('estado_civil') == 'Casado' ? 'selected' : '' }}>Casado</option>
                                <option value="Divorciado" {{ old('estado_civil') == 'Divorciado' ? 'selected' : '' }}>Divorciado</option>
                                <option value="Viudo" {{ old('estado_civil') == 'Viudo' ? 'selected' : '' }}>Viudo</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Credenciales -->
                <div class="card p-6">
                    <h3 class="text-lg font-display font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="bi bi-shield-check text-emerald-600"></i>
                        Credenciales de Acceso
                    </h3>

                    <div class="space-y-4">
                        <div>
                            <label class="form-label form-label-required">Email</label>
                            <input type="email" name="correo" class="input" placeholder="usuario@ejemplo.com" value="{{ old('correo') }}" required>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="form-label form-label-required">Contraseña</label>
                                <input type="password" name="password" class="input" placeholder="••••••••" required>
                            </div>
                            <div>
                                <label class="form-label form-label-required">Confirmar Contraseña</label>
                                <input type="password" name="password_confirmation" class="input" placeholder="••••••••" required>
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
                        <label class="flex items-center gap-3 p-3 border-2 border-gray-200 rounded-xl cursor-pointer hover:bg-gray-50">
                            <input type="radio" name="rol_id" value="1" class="form-radio" {{ old('rol_id') == '1' ? 'checked' : '' }}>
                            <div>
                                <p class="font-semibold text-gray-900">Administrador</p>
                                <p class="text-sm text-gray-600">Acceso completo</p>
                            </div>
                        </label>
                        <label class="flex items-center gap-3 p-3 border-2 border-gray-200 rounded-xl cursor-pointer hover:bg-gray-50">
                            <input type="radio" name="rol_id" value="2" class="form-radio" {{ old('rol_id', '3') == '2' ? 'checked' : '' }}>
                            <div>
                                <p class="font-semibold text-gray-900">Médico</p>
                                <p class="text-sm text-gray-600">Gestión clínica</p>
                            </div>
                        </label>
                        <label class="flex items-center gap-3 p-3 border-2 border-gray-200 rounded-xl cursor-pointer hover:bg-gray-50">
                            <input type="radio" name="rol_id" value="3" class="form-radio" {{ old('rol_id', '3') == '3' ? 'checked' : '' }}>
                            <div>
                                <p class="font-semibold text-gray-900">Paciente</p>
                                <p class="text-sm text-gray-600">Acceso paciente</p>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Estado -->
                <div class="card p-6">
                    <h3 class="text-lg font-display font-bold text-gray-900 mb-4">Estado</h3>
                    <div class="space-y-3">
                        <label class="flex items-center gap-3 p-3 border-2 border-gray-200 rounded-xl cursor-pointer hover:bg-gray-50">
                            <input type="radio" name="status" value="1" class="form-radio" {{ old('status', '1') == '1' ? 'checked' : '' }}>
                            <div>
                                <p class="font-semibold text-gray-900">Activo</p>
                                <p class="text-sm text-gray-600">Usuario habilitado</p>
                            </div>
                        </label>
                        <label class="flex items-center gap-3 p-3 border-2 border-gray-200 rounded-xl cursor-pointer hover:bg-gray-50">
                            <input type="radio" name="status" value="0" class="form-radio" {{ old('status') == '0' ? 'checked' : '' }}>
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
                        <button type="submit" class="btn btn-success w-full">
                            <i class="bi bi-check-lg"></i>
                            Crear Usuario
                        </button>
                        <a href="{{ route('usuarios.index') }}" class="btn btn-outline w-full">
                            <i class="bi bi-x-lg"></i>
                            Cancelar
                        </a>
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
                                <h3 class="text-lg font-semibold leading-6 text-gray-900" id="modal-title">No se puede registrar el usuario</h3>
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
        // Location Logic
        const estadoSelect = document.getElementById('estado_id');
        const ciudadSelect = document.getElementById('ciudad_id');
        const municipioSelect = document.getElementById('municipio_id');
        const parroquiaSelect = document.getElementById('parroquia_id');

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
                    fetch(`{{ url('ubicacion/get-ciudades') }}/${estadoId}`)
                        .then(r => r.json())
                        .then(d => {
                            ciudadSelect.innerHTML = '<option value="">Seleccionar Ciudad...</option>';
                            d.forEach(i => ciudadSelect.innerHTML += `<option value="${i.id_ciudad}">${i.ciudad}</option>`);
                            ciudadSelect.disabled = false;
                        });

                    fetch(`{{ url('ubicacion/get-municipios') }}/${estadoId}`)
                        .then(r => r.json())
                        .then(d => {
                            municipioSelect.innerHTML = '<option value="">Seleccionar Municipio...</option>';
                            d.forEach(i => municipioSelect.innerHTML += `<option value="${i.id_municipio}">${i.municipio}</option>`);
                            municipioSelect.disabled = false;
                        });
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
                    fetch(`{{ url('ubicacion/get-parroquias') }}/${municipioId}`)
                        .then(r => r.json())
                        .then(d => {
                            parroquiaSelect.innerHTML = '<option value="">Seleccionar Parroquia...</option>';
                            d.forEach(i => parroquiaSelect.innerHTML += `<option value="${i.id_parroquia}">${i.parroquia}</option>`);
                            parroquiaSelect.disabled = false;
                        });
                } else {
                    parroquiaSelect.innerHTML = '<option value="">Seleccione un Municipio primero</option>';
                }
            });
        }

        // Logic to toggle fields based on role
        const medicoFields = document.getElementById('medicoFields');
        const pacienteFields = document.getElementById('pacienteFields');
        const roleInputs = document.querySelectorAll('input[name="rol_id"]');

        function toggleRoleFields() {
            const selectedRole = document.querySelector('input[name="rol_id"]:checked')?.value;
            
            medicoFields.classList.add('hidden');
            pacienteFields.classList.add('hidden');

            if (selectedRole === '2') { // Medico
                medicoFields.classList.remove('hidden');
            } else if (selectedRole === '3') { // Paciente
                pacienteFields.classList.remove('hidden');
            }
        }

        roleInputs.forEach(input => {
            input.addEventListener('change', toggleRoleFields);
        });
        // Run on load in case of old input
        toggleRoleFields();

        // Validation Logic
        const form = document.getElementById('createUserForm');
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
                pattern: /^\d+$/, // Simple digits check for phone body
                message: 'El teléfono debe contener solo dígitos'
            },
            correo: { 
                required: true, 
                pattern: /^[^\s@]+@[^\s@]+\.[^\s@]+$/, 
                message: 'Ingrese un correo electrónico válido' 
            },
            password: { 
                required: true, 
                minLength: 8, 
                message: 'La contraseña debe tener al menos 8 caracteres' 
            },
            password_confirmation: { 
                required: true, 
                custom: (val) => val === form.querySelector('[name="password"]').value, 
                message: 'Las contraseñas no coinciden' 
            }
        };

        // Helper to find where to append error message
        function getErrorContainer(input) {
            const parent = input.parentElement;
            // If parent is a flex container (group), go up one level
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
            if (rules.required && !value) {
                isValid = false;
                errorMessage = 'Este campo es obligatorio';
            } else if (value && rules.pattern && !rules.pattern.test(value)) {
                // Only check pattern if value exists (unless required check already caught it)
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
            // Filter unique messages
            const uniqueErrors = [...new Set(errors)];
            uniqueErrors.forEach(err => {
                const li = document.createElement('li');
                li.textContent = err;
                errorList.appendChild(li);
            });

            errorModal.classList.remove('hidden');
            // Animation
            setTimeout(() => {
                errorModalBackdrop.classList.remove('opacity-0');
                errorModalPanel.classList.remove('opacity-0', 'translate-y-4', 'sm:translate-y-0', 'sm:scale-95');
                errorModalPanel.classList.add('opacity-100', 'translate-y-0', 'sm:scale-100');
            }, 10);
        }

        // Close Modal
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
