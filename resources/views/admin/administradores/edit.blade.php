@extends('layouts.admin')

@section('title', 'Editar Administrador')

@section('content')
<div class="mb-6">
    <a href="{{ route('administradores.index') }}" class="text-medical-600 hover:text-medical-700 inline-flex items-center text-sm font-medium mb-3">
        <i class="bi bi-arrow-left mr-1"></i> Volver a la lista
    </a>
    <h2 class="text-2xl font-display font-bold text-gray-900">Editar Administrador</h2>
    <p class="text-gray-500 mt-1">Actualiza los datos de {{ $administrador->primer_nombre }} {{ $administrador->primer_apellido }}</p>
</div>

<form id="editAdminForm" method="POST" action="{{ route('administradores.update', $administrador->id) }}">
    @csrf
    @method('PUT')

    {{-- Global Error Alerts --}}
    @if(session('error'))
        <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200 text-red-700">
            <div class="flex items-center gap-3">
                <i class="bi bi-exclamation-octagon-fill text-xl"></i>
                <span class="font-semibold">{{ session('error') }}</span>
            </div>
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200 text-red-700">
            <div class="flex items-center gap-3 mb-2">
                <i class="bi bi-exclamation-triangle-fill text-xl"></i>
                <span class="font-semibold">Por favor corrige los siguientes errores:</span>
            </div>
            <ul class="list-disc list-inside ml-8 text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Columna Principal: Datos Personales -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Información Personal -->
            <div class="card p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <i class="bi bi-person-circle text-medical-600"></i>
                    Información Personal
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="form-group">
                        <label for="primer_nombre" class="form-label form-label-required">Primer Nombre</label>
                        <input type="text" name="primer_nombre" id="primer_nombre" 
                               class="input @error('primer_nombre') input-error @enderror" 
                               value="{{ old('primer_nombre', $administrador->primer_nombre) }}" required>
                        @error('primer_nombre')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="segundo_nombre" class="form-label">Segundo Nombre</label>
                        <input type="text" name="segundo_nombre" id="segundo_nombre" 
                               class="input @error('segundo_nombre') input-error @enderror" 
                               value="{{ old('segundo_nombre', $administrador->segundo_nombre) }}">
                        @error('segundo_nombre')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="primer_apellido" class="form-label form-label-required">Primer Apellido</label>
                        <input type="text" name="primer_apellido" id="primer_apellido" 
                               class="input @error('primer_apellido') input-error @enderror" 
                               value="{{ old('primer_apellido', $administrador->primer_apellido) }}" required>
                        @error('primer_apellido')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="segundo_apellido" class="form-label">Segundo Apellido</label>
                        <input type="text" name="segundo_apellido" id="segundo_apellido" 
                               class="input @error('segundo_apellido') input-error @enderror" 
                               value="{{ old('segundo_apellido', $administrador->segundo_apellido) }}">
                        @error('segundo_apellido')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="tipo_documento" class="form-label form-label-required">Tipo Documento</label>
                        <select name="tipo_documento" id="tipo_documento" 
                                class="form-select @error('tipo_documento') input-error @enderror" required>
                            <option value="">Seleccionar...</option>
                            <option value="V" {{ old('tipo_documento', $administrador->tipo_documento) == 'V' ? 'selected' : '' }}>V - Venezolano</option>
                            <option value="E" {{ old('tipo_documento', $administrador->tipo_documento) == 'E' ? 'selected' : '' }}>E - Extranjero</option>
                            <option value="P" {{ old('tipo_documento', $administrador->tipo_documento) == 'P' ? 'selected' : '' }}>P - Pasaporte</option>
                            <option value="J" {{ old('tipo_documento', $administrador->tipo_documento) == 'J' ? 'selected' : '' }}>J - Jurídico</option>
                        </select>
                        @error('tipo_documento')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="numero_documento" class="form-label form-label-required">Número Documento</label>
                        <input type="text" name="numero_documento" id="numero_documento" 
                               class="input @error('numero_documento') input-error @enderror" 
                               value="{{ old('numero_documento', $administrador->numero_documento) }}" required>
                        @error('numero_documento')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="fecha_nac" class="form-label form-label-required">Fecha de Nacimiento</label>
                        <input type="date" name="fecha_nac" id="fecha_nac" 
                               class="input @error('fecha_nac') input-error @enderror" 
                               value="{{ old('fecha_nac', $administrador->fecha_nac) }}" required>
                        @error('fecha_nac')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="genero" class="form-label form-label-required">Género</label>
                        <select name="genero" id="genero" 
                                class="form-select @error('genero') input-error @enderror" required>
                            <option value="">Seleccionar...</option>
                            <option value="Masculino" {{ old('genero', $administrador->genero) == 'Masculino' ? 'selected' : '' }}>Masculino</option>
                            <option value="Femenino" {{ old('genero', $administrador->genero) == 'Femenino' ? 'selected' : '' }}>Femenino</option>
                        </select>
                        @error('genero')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Datos de Contacto -->
            <div class="card p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <i class="bi bi-telephone text-medical-600"></i>
                    Datos de Contacto
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="form-group">
                        <label for="prefijo_tlf" class="form-label form-label-required">Prefijo Telefónico</label>
                        <select name="prefijo_tlf" id="prefijo_tlf" 
                                class="form-select @error('prefijo_tlf') input-error @enderror" required>
                            <option value="+58" {{ old('prefijo_tlf', $administrador->prefijo_tlf) == '+58' ? 'selected' : '' }}>+58 - Venezuela</option>
                            <option value="+57" {{ old('prefijo_tlf', $administrador->prefijo_tlf) == '+57' ? 'selected' : '' }}>+57 - Colombia</option>
                            <option value="+1" {{ old('prefijo_tlf', $administrador->prefijo_tlf) == '+1' ? 'selected' : '' }}>+1 - USA</option>
                        </select>
                        @error('prefijo_tlf')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="numero_tlf" class="form-label form-label-required">Número de Teléfono</label>
                        <input type="text" name="numero_tlf" id="numero_tlf" 
                               class="input @error('numero_tlf') input-error @enderror" 
                               value="{{ old('numero_tlf', $administrador->numero_tlf) }}" required>
                        @error('numero_tlf')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group md:col-span-2">
                        <label for="correo" class="form-label">Correo Electrónico</label>
                        <input type="email" id="correo" 
                               class="input bg-gray-100 text-gray-500 cursor-not-allowed" 
                               value="{{ $administrador->usuario->correo }}" 
                               readonly disabled>
                        <p class="form-help text-xs text-gray-400">El correo electrónico no se puede modificar por seguridad</p>
                    </div>
                </div>
            </div>

            <!-- Ubicación -->
            <div class="card p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <i class="bi bi-geo-alt text-medical-600"></i>
                    Ubicación
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="form-group">
                        <label for="estado_id" class="form-label">Estado</label>
                        <select name="estado_id" id="estado_id" class="form-select">
                            <option value="">Seleccionar...</option>
                            @foreach($estados as $estado)
                            <option value="{{ $estado->id_estado }}" {{ old('estado_id', $administrador->estado_id) == $estado->id_estado ? 'selected' : '' }}>
                                {{ $estado->estado }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="ciudad_id" class="form-label">Ciudad</label>
                        <select name="ciudad_id" id="ciudad_id" class="form-select" {{ old('estado_id', $administrador->estado_id) ? '' : 'disabled' }}>
                            <option value="">Seleccione un Estado primero</option>
                            @if(old('ciudad_id', $administrador->ciudad_id))
                                @foreach($ciudades as $ciudad)
                                    @if($ciudad->id_estado == old('estado_id', $administrador->estado_id))
                                    <option value="{{ $ciudad->id_ciudad }}" {{ old('ciudad_id', $administrador->ciudad_id) == $ciudad->id_ciudad ? 'selected' : '' }}>
                                        {{ $ciudad->ciudad }}
                                    </option>
                                    @endif
                                @endforeach
                            @endif
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="municipio_id" class="form-label">Municipio</label>
                        <select name="municipio_id" id="municipio_id" class="form-select" {{ old('estado_id', $administrador->estado_id) ? '' : 'disabled' }}>
                            <option value="">Seleccione un Estado primero</option>
                            @if(old('municipio_id', $administrador->municipio_id))
                                @foreach($municipios as $municipio)
                                    @if($municipio->id_estado == old('estado_id', $administrador->estado_id))
                                    <option value="{{ $municipio->id_municipio }}" {{ old('municipio_id', $administrador->municipio_id) == $municipio->id_municipio ? 'selected' : '' }}>
                                        {{ $municipio->municipio }}
                                    </option>
                                    @endif
                                @endforeach
                            @endif
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="parroquia_id" class="form-label">Parroquia</label>
                        <select name="parroquia_id" id="parroquia_id" class="form-select" {{ old('municipio_id', $administrador->municipio_id) ? '' : 'disabled' }}>
                            <option value="">Seleccione un Municipio primero</option>
                            @if(old('parroquia_id', $administrador->parroquia_id))
                                @foreach($parroquias as $parroquia)
                                    @if($parroquia->id_municipio == old('municipio_id', $administrador->municipio_id))
                                    <option value="{{ $parroquia->id_parroquia }}" {{ old('parroquia_id', $administrador->parroquia_id) == $parroquia->id_parroquia ? 'selected' : '' }}>
                                        {{ $parroquia->parroquia }}
                                    </option>
                                    @endif
                                @endforeach
                            @endif
                        </select>
                    </div>

                    <div class="form-group md:col-span-2">
                        <label for="direccion_detallada" class="form-label">Dirección Detallada</label>
                        <textarea name="direccion_detallada" id="direccion_detallada" 
                                  class="input resize-none" rows="2" 
                                  placeholder="Avenida, Calle, Nro. Casa/Edificio">{{ old('direccion_detallada', $administrador->direccion_detallada) }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Cambiar Contraseña (Opcional) -->
            <div class="card p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <i class="bi bi-shield-lock text-medical-600"></i>
                    Cambiar Contraseña <span class="text-sm text-gray-400 font-normal">(Opcional)</span>
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="form-group">
                        <label for="password" class="form-label">Nueva Contraseña</label>
                        <input type="password" name="password" id="password" 
                               class="input @error('password') input-error @enderror" 
                               placeholder="Dejar en blanco para no cambiar">
                        <p class="form-help">Mínimo 8 caracteres</p>
                        @error('password')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password_confirmation" class="form-label">Confirmar Contraseña</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" 
                               class="input" placeholder="Repetir nueva contraseña">
                    </div>
                </div>
            </div>
        </div>

        <!-- Columna Lateral: Estado y Acciones -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Estado -->
            <div class="card p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Estado</h3>
                <div class="form-group mb-0">
                    <label class="flex items-center cursor-pointer">
                        <input type="checkbox" name="status" value="1" 
                               class="form-checkbox" 
                               {{ old('status', $administrador->status) ? 'checked' : '' }}>
                        <span class="ml-2 text-gray-700">Administrador Activo</span>
                    </label>
                    <p class="form-help mt-2">Si está inactivo, no podrá acceder al sistema</p>
                </div>
            </div>

            <!-- Consultorios Asignados -->
            <div class="card p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <i class="bi bi-building text-medical-600"></i>
                    Consultorios Asignados
                </h3>
                <div class="form-group mb-0">
                    <label class="form-label form-label-required">Seleccione los consultorios que administrará</label>
                    <div class="space-y-2 max-h-48 overflow-y-auto border border-gray-200 rounded-lg p-3">
                        @foreach($consultorios as $consultorio)
                            <label class="flex items-center cursor-pointer hover:bg-gray-50 p-2 rounded-lg transition-colors">
                                <input type="checkbox" name="consultorios[]" value="{{ $consultorio->id }}" 
                                       class="form-checkbox text-medical-600 focus:ring-medical-500"
                                       {{ in_array($consultorio->id, old('consultorios', $consultoriosSeleccionados)) ? 'checked' : '' }}>
                                <span class="ml-3 text-sm text-gray-700">{{ $consultorio->nombre }}</span>
                            </label>
                        @endforeach
                    </div>
                    <p class="form-help mt-2">El administrador solo podrá gestionar estos consultorios</p>
                    @error('consultorios')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Acciones -->
            <div class="card p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Opciones</h3>
                <div class="space-y-3">
                    <button type="submit" class="btn btn-primary w-full">
                        <i class="bi bi-check-lg mr-2"></i>
                        Guardar Cambios
                    </button>
                    <a href="{{ route('administradores.show', $administrador->id) }}" class="btn btn-outline w-full">
                        <i class="bi bi-eye mr-2"></i>
                        Ver Detalles
                    </a>
                    <a href="{{ route('administradores.index') }}" class="btn btn-outline w-full">
                        <i class="bi bi-x-lg mr-2"></i>
                        Cancelar
                    </a>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

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
                            <h3 class="text-lg font-semibold leading-6 text-gray-900" id="modal-title">No se puede actualizar el administrador</h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500 mb-3">
                                    Por favor, corrija los siguientes errores antes de continuar:
                                </p>
                                <ul id="errorList" class="text-sm text-red-600 list-disc list-inside space-y-1 bg-red-50 p-3 rounded-lg border border-red-100">
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
                    fetch(`{{ url('admin/get-ciudades') }}/${estadoId}`)
                        .then(r => r.json())
                        .then(d => {
                            ciudadSelect.innerHTML = '<option value="">Seleccionar Ciudad...</option>';
                            d.forEach(i => ciudadSelect.innerHTML += `<option value="${i.id_ciudad}">${i.ciudad}</option>`);
                            ciudadSelect.disabled = false;
                        });

                    fetch(`{{ url('admin/get-municipios') }}/${estadoId}`)
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
                    fetch(`{{ url('admin/get-parroquias') }}/${municipioId}`)
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

        // Validation Logic
        const form = document.getElementById('editAdminForm');
        const errorModal = document.getElementById('errorModal');
        const errorModalBackdrop = document.getElementById('errorModalBackdrop');
        const errorModalPanel = document.getElementById('errorModalPanel');
        const errorList = document.getElementById('errorList');
        const closeErrorModalBtn = document.getElementById('closeErrorModal');

        // Validation Rules
        const validations = {
            primer_nombre: { 
                required: true, 
                pattern: /^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s]+$/, 
                message: 'Ingrese un nombre válido (solo letras)' 
            },
            segundo_nombre: { 
                required: false, 
                pattern: /^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s]+$/, 
                message: 'El segundo nombre solo debe contener letras' 
            },
            primer_apellido: { 
                required: true, 
                pattern: /^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s]+$/, 
                message: 'Ingrese un apellido válido (solo letras)' 
            },
            segundo_apellido: { 
                required: false, 
                pattern: /^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s]+$/, 
                message: 'El segundo apellido solo debe contener letras' 
            },
            tipo_documento: { required: true, message: 'Seleccione un tipo de documento' },
            numero_documento: { 
                required: true, 
                pattern: /^\d+$/, 
                message: 'El número de documento debe contener solo dígitos' 
            },
            fecha_nac: { required: true, message: 'Ingrese la fecha de nacimiento' },
            genero: { required: true, message: 'Seleccione el género' },
            prefijo_tlf: { required: true, message: 'Seleccione un prefijo' },
            numero_tlf: {
                required: true,
                pattern: /^\d+$/, 
                message: 'El teléfono debe contener solo dígitos'
            },
            estado_id: { required: true, message: 'Seleccione un estado' },
            ciudad_id: { required: true, message: 'Seleccione una ciudad' },
            municipio_id: { required: true, message: 'Seleccione un municipio' },
            parroquia_id: { required: true, message: 'Seleccione una parroquia' },
            direccion_detallada: { required: false },
            password: { 
                required: false, 
                minLength: 8, 
                message: 'La contraseña debe tener al menos 8 caracteres' 
            },
            password_confirmation: { 
                required: false, 
                custom: (val) => {
                    const pass = form.querySelector('[name="password"]').value;
                    if(pass && !val) return false; // If pass exists, strict check
                    return val === pass;
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

            const events = input.tagName === 'SELECT' ? ['change', 'blur'] : ['input', 'blur'];

            events.forEach(event => {
                input.addEventListener(event, () => validateField(input, validations[fieldName]));
            });
        });

        function validateField(input, rules) {
            const value = input.value.trim();
            let isValid = true;
            let errorMessage = '';

            input.classList.remove('border-red-500', 'focus:border-red-500', 'focus:ring-red-500');
            
            const errorContainer = getErrorContainer(input);
            const existingError = errorContainer.querySelector('.validation-error-msg');
            if (existingError) existingError.remove();

            if (rules.required && !value) {
                isValid = false;
                errorMessage = rules.message || 'Este campo es obligatorio';
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

        form.addEventListener('submit', function(e) {
            let errors = [];
            
            Object.keys(validations).forEach(fieldName => {
                const input = form.querySelector(`[name="${fieldName}"]`);
                if (input) {
                    const rule = validations[fieldName];
                    // Skip validation if input is disabled (e.g., dependent dropdowns not yet active)
                    if (!input.disabled) {
                         if (!validateField(input, rule)) {
                            let msg = rule.message || 'Error de validación';
                            const label = input.closest('.form-group')?.querySelector('label')?.textContent || fieldName;
                            if(rule.required && !input.value.trim()) msg = `El campo ${label} es obligatorio`;
                            errors.push(msg);
                        }
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
            errorModalPanel.classList.remove('opacity-100', 'translate-y-0', 'sm:scale-100');
            errorModalPanel.classList.add('opacity-0', 'translate-y-4', 'sm:translate-y-0', 'sm:scale-95');
            setTimeout(() => errorModal.classList.add('hidden'), 300);
        }

        closeErrorModalBtn.addEventListener('click', closeModal);
        errorModalBackdrop.addEventListener('click', closeModal);
    });
</script>
@endpush
