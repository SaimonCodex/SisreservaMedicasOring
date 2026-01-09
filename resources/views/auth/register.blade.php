@extends('layouts.auth')

@section('title', 'Crear Cuenta')
@section('box-width', 'max-w-4xl')

@section('auth-content')
<!-- Debug div for user feedback -->
<div id="js-error-log" class="fixed top-0 left-0 w-full bg-red-100 text-red-600 text-xs p-2 z-50 hidden"></div>

<div class="mb-6 text-center">
    <h2 class="text-2xl font-display font-bold text-slate-900 tracking-tight">
        Crear Cuenta Nueva
    </h2>
    <p class="mt-2 text-sm text-slate-500">
        Regístrate como paciente en 3 sencillos pasos
    </p>
</div>

<!-- Steps Indicators -->
<div class="mb-8 relative">
    <div class="absolute top-1/2 left-0 w-full h-0.5 bg-gray-200 -z-10 -translate-y-1/2 rounded"></div>
    <div class="flex justify-between w-full max-w-xs mx-auto">
        <!-- Step 1 -->
        <div class="step-indicator group" data-step="1">
            <div id="ind-1" class="w-10 h-10 rounded-full flex items-center justify-center bg-blue-600 text-white font-bold ring-4 ring-white transition-all duration-300 shadow-md">
                1
            </div>
            
        </div>
        
        <!-- Step 2 -->
        <div class="step-indicator group relative" data-step="2">
            <div id="ind-2" class="w-10 h-10 rounded-full flex items-center justify-center bg-gray-100 text-gray-400 font-bold ring-4 ring-white transition-all duration-300 border-2 border-transparent">
                2
            </div>
             <div class="absolute -bottom-6 left-1/2 -translate-x-1/2 w-max opacity-100 transition-opacity">
                <span id="text-2" class="text-xs font-semibold text-gray-400">Ubicación</span>
            </div>
        </div>

        <!-- Step 3 -->
        <div class="step-indicator group relative" data-step="3">
            <div id="ind-3" class="w-10 h-10 rounded-full flex items-center justify-center bg-gray-100 text-gray-400 font-bold ring-4 ring-white transition-all duration-300 border-2 border-transparent">
                3
            </div>
             <div class="absolute -bottom-6 left-1/2 -translate-x-1/2 w-max opacity-100 transition-opacity">
                <span id="text-3" class="text-xs font-semibold text-gray-400">Cuenta</span>
            </div>
        </div>
    </div>
</div>

<form method="POST" action="{{ route('register') }}" id="registerForm" class="space-y-6">
    @csrf
    
    <!-- Paso 1: Información Personal -->
    <div id="step-1" class="form-step animate-fade-in">
        <input type="hidden" name="rol_id" value="3">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            <div>
                <label for="primer_nombre" class="block text-sm font-medium text-slate-700">Primer Nombre *</label>
                <input type="text" name="primer_nombre" id="primer_nombre" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" required value="{{ old('primer_nombre') }}">
            </div>

            <div>
                <label for="segundo_nombre" class="block text-sm font-medium text-slate-700">Segundo Nombre *</label>
                <input type="text" name="segundo_nombre" id="segundo_nombre" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" required value="{{ old('segundo_nombre') }}">
            </div>

            <div>
                <label for="primer_apellido" class="block text-sm font-medium text-slate-700">Primer Apellido *</label>
                <input type="text" name="primer_apellido" id="primer_apellido" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" required value="{{ old('primer_apellido') }}">
            </div>

            <div>
                <label for="segundo_apellido" class="block text-sm font-medium text-slate-700">Segundo Apellido *</label>
                <input type="text" name="segundo_apellido" id="segundo_apellido" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" required value="{{ old('segundo_apellido') }}">
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700">Cédula *</label>
                <div class="flex gap-2">
                    <select name="tipo_documento" id="tipo_documento" class="mt-1 block w-20 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" required>
                        <option value="V" {{ old('tipo_documento') == 'V' ? 'selected' : '' }}>V</option>
                        <option value="E" {{ old('tipo_documento') == 'E' ? 'selected' : '' }}>E</option>
                        <option value="P" {{ old('tipo_documento') == 'P' ? 'selected' : '' }}>P</option>
                        <option value="J" {{ old('tipo_documento') == 'J' ? 'selected' : '' }}>J</option>
                    </select>
                    <input type="text" name="numero_documento" id="numero_documento" placeholder="12345678" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" required maxlength="20" value="{{ old('numero_documento') }}">
                </div>
            </div>

            <div>
                <label for="fecha_nac" class="block text-sm font-medium text-slate-700">Fecha Nacimiento *</label>
                <input type="date" name="fecha_nac" id="fecha_nac" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" required value="{{ old('fecha_nac') }}">
            </div>

            <div>
                <label for="genero" class="block text-sm font-medium text-slate-700">Sexo *</label>
                <select name="genero" id="genero" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" required>
                    <option value="">Seleccionar...</option>
                    <option value="Masculino" {{ old('genero') == 'Masculino' ? 'selected' : '' }}>Masculino</option>
                    <option value="Femenino" {{ old('genero') == 'Femenino' ? 'selected' : '' }}>Femenino</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700">Teléfono *</label>
                <div class="flex gap-2">
                    <select name="prefijo_tlf" id="prefijo_tlf" class="mt-1 block w-24 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" required>
                        <option value="+58" {{ old('prefijo_tlf') == '+58' ? 'selected' : '' }}>+58</option>
                        <option value="+57" {{ old('prefijo_tlf') == '+57' ? 'selected' : '' }}>+57</option>
                        <option value="+1" {{ old('prefijo_tlf') == '+1' ? 'selected' : '' }}>+1</option>
                        <option value="+34" {{ old('prefijo_tlf') == '+34' ? 'selected' : '' }}>+34</option>
                    </select>
                    <input type="tel" name="numero_tlf" id="numero_tlf" placeholder="4121234567" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" required maxlength="15" value="{{ old('numero_tlf') }}">
                </div>
            </div>
        </div>
    </div>

    <!-- Paso 2: Ubicación -->
    <div id="step-2" class="form-step hidden animate-fade-in">
        <div class="grid grid-cols-1 gap-5">
            <div>
                <label for="estado_id" class="block text-sm font-medium text-slate-700">Estado *</label>
                <select name="estado_id" id="estado_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" required>
                    <option value="">Seleccionar estado...</option>
                     @foreach($estados ?? [] as $estado)
                        <option value="{{ $estado->id_estado }}" {{ old('estado_id') == $estado->id_estado ? 'selected' : '' }}>
                            {{ $estado->estado }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                     <label for="ciudad_id" class="block text-sm font-medium text-slate-700">Ciudad*</label>
                     <select name="ciudad_id" id="ciudad_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                         <option value="">Primero selecciona estado...</option>
                     </select>
                </div>
                 <div>
                     <label for="municipio_id" class="block text-sm font-medium text-slate-700">Municipio*</label>
                     <select name="municipio_id" id="municipio_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                         <option value="">Primero selecciona estado...</option>
                     </select>
                </div>
            </div>

             <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                 <div>
                     <label for="parroquia_id" class="block text-sm font-medium text-slate-700">Parroquia*</label>
                     <select name="parroquia_id" id="parroquia_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                         <option value="">Primero selecciona municipio...</option>
                     </select>
                </div>
                 <div>
                     <label for="direccion" class="block text-sm font-medium text-slate-700">Dirección Exacta*</label>
                     <input type="text" name="direccion" id="direccion" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" placeholder="Av. Ppal, Edif. A, Apto 1" value="{{ old('direccion') }}">
                </div>
            </div>
        </div>
    </div>

    <!-- Paso 3: Seguridad -->
    <div id="step-3" class="form-step hidden animate-fade-in">
        <div class="space-y-5">
            <div>
                <label for="correo" class="block text-sm font-medium text-slate-700">Correo Electrónico *</label>
                <input type="email" name="correo" id="correo" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" required placeholder="ejemplo@email.com" value="{{ old('correo') }}">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label for="password" class="block text-sm font-medium text-slate-700">Contraseña *</label>
                    <div class="relative mt-1">
                        <input type="password" name="password" id="password" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm pr-10" required placeholder="Mínimo 8 caracteres">
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center cursor-pointer" id="togglePassword1">
                             <i class="bi bi-eye text-gray-400"></i>
                         </div>
                    </div>
                     <div id="password-strength" class="mt-2 text-xs"></div>
                </div>
                 <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-slate-700">Repetir Contraseña *</label>
                    <div class="relative mt-1">
                        <input type="password" name="password_confirmation" id="password_confirmation" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm pr-10" required placeholder="Confirmar contraseña">
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center cursor-pointer" id="togglePassword2">
                             <i class="bi bi-eye text-gray-400"></i>
                         </div>
                    </div>
                </div>
            </div>

            <div class="bg-blue-50 p-4 rounded-lg border border-blue-100">
                <h4 class="text-sm font-semibold text-blue-800 mb-3">Preguntas de recuperación</h4>
                <div class="space-y-3">
                     @for($i = 1; $i <= 3; $i++)
                        <div>
                             <select name="pregunta_seguridad_{{ $i }}" id="pregunta_seguridad_{{ $i }}" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-xs py-2" required>
                                 <option value="">Seleccionar pregunta {{ $i }}...</option>
                                  @foreach($preguntas ?? [] as $pregunta)
                                    <option value="{{ $pregunta->id }}" {{ old("pregunta_seguridad_$i") == $pregunta->id ? 'selected' : '' }}>{{ $pregunta->pregunta }}</option>
                                @endforeach
                             </select>
                             <input type="text" name="respuesta_seguridad_{{ $i }}" class="mt-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-xs py-2" placeholder="Respuesta" required value="{{ old("respuesta_seguridad_$i") }}">
                        </div>
                     @endfor
                </div>
            </div>

            <div class="flex items-start">
                <div class="flex items-center h-5">
                    <input id="terminos" name="terminos" type="checkbox" required class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded">
                </div>
                <div class="ml-3 text-sm">
                    <label for="terminos" class="font-medium text-slate-700">Acepto los términos y condiciones</label>
                </div>
            </div>
        </div>
    </div>

    <!-- Navigation Buttons -->
    <div class="flex justify-between pt-6 border-t border-gray-100 items-center">
        <a href="{{ route('login') }}" class="text-sm text-slate-500 hover:text-medical-600 font-medium transition-colors">
            <i class="bi bi-arrow-left"></i> Volver al Login
        </a>
        
        <div class="flex gap-3">
            <button type="button" id="prevBtn" class="hidden px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500" onclick="window.changeStep(-1)">
                Anterior
            </button>
            
            <button type="button" id="nextBtn" class="px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500" onclick="window.changeStep(1)">
                Siguiente <i class="bi bi-arrow-right ml-2"></i>
            </button>
            
            <button type="submit" id="submitBtn" class="hidden px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                Crear Cuenta <i class="bi bi-check-lg ml-2"></i>
            </button>
        </div>
    </div>
</form>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Validación de preguntas de seguridad para evitar duplicados
        const selects = [
            document.getElementById('pregunta_seguridad_1'),
            document.getElementById('pregunta_seguridad_2'),
            document.getElementById('pregunta_seguridad_3')
        ];

        function updateSelects() {
            const selectedValues = selects.map(s => s.value).filter(v => v);
            
            selects.forEach(select => {
                Array.from(select.options).forEach(option => {
                    if (selectedValues.includes(option.value) && select.value !== option.value) {
                        option.disabled = true;
                    } else {
                        option.disabled = false;
                    }
                });
            });
        }

        selects.forEach(select => {
            if(select) select.addEventListener('change', updateSelects);
        });

        // Debug logging
        const form = document.getElementById('registerForm');
        form.addEventListener('submit', (e) => {
             console.log('Enviando formulario de registro...');
             const questions = selects.map(s => s.value);
             const uniqueQuestions = new Set(questions.filter(v=>v));
             
             if(uniqueQuestions.size < 3) {
                 e.preventDefault();
                 alert('Por favor selecciona 3 preguntas de seguridad diferentes.');
                 return;
             }
        });
    });
</script>
@endpush

@push('scripts')
<script>
    // Critical: Define globally explicitly
    window.currentStep = 1;
    window.totalSteps = 3;

    window.changeStep = function(dir) {
        // Simple Debug
        console.log('Change Step Called:', dir);
        
        const nextStep = window.currentStep + dir;
        
        if (dir === 1) {
            if (!window.validateStep(window.currentStep)) return;
        }

        if (nextStep >= 1 && nextStep <= window.totalSteps) {
            window.showStep(nextStep);
        }
    };

    window.showStep = function(step) {
        // Visual Update
        document.querySelectorAll('.form-step').forEach(el => el.classList.add('hidden'));
        const target = document.getElementById('step-' + step);
        if(target) target.classList.remove('hidden');

        // Buttons
        const prev = document.getElementById('prevBtn');
        const next = document.getElementById('nextBtn');
        const submit = document.getElementById('submitBtn');

        if(prev) prev.style.display = (step === 1) ? 'none' : 'inline-flex';
        // Hide Next on last step
        if(next) next.classList.toggle('hidden', step === window.totalSteps);
        // Show Submit on last step
        if(submit) submit.classList.toggle('hidden', step !== window.totalSteps);

        updateIndicators(step);
        window.currentStep = step;
    };

    window.checkPasswordStrength = function(password) {
        const hasUpperCase = /[A-Z]/.test(password);
        const hasNumber = /[0-9]/.test(password);
        const hasSymbol = /[@$!%*#?&.]/.test(password);
        const minLength = password.length >= 8;

        return {
            valid: hasUpperCase && hasNumber && hasSymbol && minLength,
            errors: {
                upper: !hasUpperCase,
                number: !hasNumber,
                symbol: !hasSymbol,
                length: !minLength
            }
        };
    };

    window.validateStep = function(step) {
        if (step === 1) {
            const required = ['primer_nombre', 'segundo_nombre', 'primer_apellido', 'segundo_apellido', 'numero_documento', 'fecha_nac', 'genero', 'numero_tlf'];
            let missing = [];
            
            required.forEach(id => {
                const el = document.getElementById(id);
                if (!el || !el.value.trim()) {
                    missing.push(id.replace(/_/g, ' '));
                    if(el) el.style.borderColor = 'red';
                } else {
                    // Validaciones específicas
                    let isValid = true;
                    if (['primer_nombre', 'segundo_nombre', 'primer_apellido', 'segundo_apellido'].includes(id)) {
                        if (!/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/.test(el.value)) {
                            isValid = false;
                            alert('El campo ' + id.replace(/_/g, ' ') + ' solo debe contener letras.');
                        }
                    } else if (id === 'numero_documento') {
                        if (el.value.length < 10) {
                            isValid = false;
                            alert('La Cédula debe tener al menos 10 dígitos.');
                        }
                    }

                    if (!isValid) {
                        el.style.borderColor = 'red';
                        missing.push(id.replace(/_/g, ' ') + ' (inválido)');
                    } else {
                        if(el) el.style.borderColor = ''; // reset
                    }
                }
            });

            if (missing.length > 0) {
                alert('Faltan campos por completar: ' + missing.join(', '));
                return false;
            }
        }
        
        if (step === 2) {
             const estado = document.getElementById('estado_id');
             if(!estado.value) {
                 alert('Selecciona un Estado');
                 return false;
             }
        }

        return true;
    };
    
    // Intercept submit for final validation
    document.getElementById('registerForm').addEventListener('submit', function(e) {
        const p1 = document.getElementById('password').value;
        const p2 = document.getElementById('password_confirmation').value;
        
        if (p1 !== p2) {
             e.preventDefault();
             alert('Las contraseñas no coinciden');
             return;
        }

        const strength = window.checkPasswordStrength(p1);
        if (!strength.valid) {
             e.preventDefault();
             let msg = 'La contraseña debe tener:\n';
             if(strength.errors.length) msg += '- Al menos 8 caracteres\n';
             if(strength.errors.upper) msg += '- Al menos una mayúscula\n';
             if(strength.errors.number) msg += '- Al menos un número\n';
             if(strength.errors.symbol) msg += '- Al menos un símbolo (@$!%*#?&.)';
             alert(msg);
        }
    });

    function updateIndicators(step) {
        for(let i=1; i<=3; i++) {
            const ind = document.getElementById('ind-' + i);
            const txt = document.getElementById('text-' + i);
            if(!ind) continue;
            
            // Reset
            ind.className = "w-10 h-10 rounded-full flex items-center justify-center font-bold ring-4 ring-white transition-all duration-300 border-2";
             if(txt) txt.className = "text-xs font-semibold";
            
            if (i < step) {
                // Done
                ind.classList.add('bg-green-500', 'text-white', 'border-transparent');
                ind.innerHTML = '✓';
                if(txt) txt.classList.add('text-green-600');
            } else if (i === step) {
                // Active
                ind.classList.add('bg-blue-600', 'text-white', 'border-transparent', 'shadow-md');
                ind.innerHTML = i;
                if(txt) txt.classList.add('text-blue-600');
            } else {
                // Pending
                ind.classList.add('bg-gray-100', 'text-gray-400', 'border-transparent');
                ind.innerHTML = i;
                if(txt) txt.classList.add('text-gray-400');
            }
        }
    }
</script>

<!-- Utilities imports (Non-critical for navigation, but good for UX) -->
<script type="module">
    // Valid static imports (Must be top-level)
    import { validateEmail, validateCedula, autoFormat, preventInvalidInput } from '{{ asset("js/validators.js") }}';
    import { initPasswordToggle } from '{{ asset("js/auth-utils.js") }}';
    
    // Logic that uses the imports
    try {
        // Enforce numbers only for split fields
        const cedula = document.getElementById('numero_documento');
        if(cedula) preventInvalidInput(cedula, 'numbers');
        
        const tel = document.getElementById('numero_tlf');
        if(tel) preventInvalidInput(tel, 'numbers');
        
        // Passwords
        initPasswordToggle(document.querySelector('#password'), document.getElementById('togglePassword1'));
        initPasswordToggle(document.querySelector('#password_confirmation'), document.getElementById('togglePassword2'));
    } catch(e) {
        console.warn('Utility initialization failed', e);
    }
</script>

<script>
    // Location Logic (Plain JS)
    document.addEventListener('DOMContentLoaded', () => {
        const estado = document.getElementById('estado_id');
        const ciudad = document.getElementById('ciudad_id');
        const municipio = document.getElementById('municipio_id');
        const parroquia = document.getElementById('parroquia_id');

        async function loadSelect(url, el, valueKey, textKey) {
            if(!el) return;
            el.innerHTML = '<option value="">Cargando...</option>';
            try {
                const res = await fetch(url);
                const data = await res.json();
                el.innerHTML = '<option value="">Seleccionar...</option>';
                data.forEach(item => {
                    const opt = document.createElement('option');
                    opt.value = item[valueKey];
                    opt.textContent = item[textKey];
                    el.appendChild(opt);
                });
            } catch(e) {
                console.error(e);
                el.innerHTML = '<option value="">Error al cargar</option>';
            }
        }

        if(estado) {
            estado.addEventListener('change', () => {
                if(estado.value) {
                    loadSelect('{{ url("ubicacion/get-ciudades") }}/' + estado.value, ciudad, 'id_ciudad', 'ciudad');
                    loadSelect('{{ url("ubicacion/get-municipios") }}/' + estado.value, municipio, 'id_municipio', 'municipio');
                    // Reset parroquia
                    if(parroquia) parroquia.innerHTML = '<option value="">Primero selecciona municipio...</option>';
                }
            });
        }

        if(municipio) {
            municipio.addEventListener('change', () => {
                if(municipio.value) {
                    loadSelect('{{ url("ubicacion/get-parroquias") }}/' + municipio.value, parroquia, 'id_parroquia', 'parroquia');
                }
            });
        }
    });

    // Simple Auto-Focus for better UX
    document.addEventListener('DOMContentLoaded', () => {
         const firstInput = document.getElementById('primer_nombre');
         if(firstInput) firstInput.focus();
    });
</script>
@endpush
@endsection
