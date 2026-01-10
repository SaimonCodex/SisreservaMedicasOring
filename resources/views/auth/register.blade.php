@extends('layouts.auth')

@section('title', 'Crear Cuenta')
@section('box-width', 'max-w-[1400px]')
@section('form-width', 'max-w-2xl')

@section('auth-content')
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
        <div class="step-indicator group" data-step="1">
            <div id="ind-1" class="w-10 h-10 rounded-full flex items-center justify-center bg-blue-600 text-white font-bold ring-4 ring-white transition-all duration-300 shadow-md">1</div>
        </div>
        <div class="step-indicator group relative" data-step="2">
            <div id="ind-2" class="w-10 h-10 rounded-full flex items-center justify-center bg-gray-100 text-gray-400 font-bold ring-4 ring-white transition-all duration-300 border-2 border-transparent">2</div>
            <div class="absolute -bottom-6 left-1/2 -translate-x-1/2 w-max"><span id="text-2" class="text-xs font-semibold text-gray-400">Ubicación</span></div>
        </div>
        <div class="step-indicator group relative" data-step="3">
            <div id="ind-3" class="w-10 h-10 rounded-full flex items-center justify-center bg-gray-100 text-gray-400 font-bold ring-4 ring-white transition-all duration-300 border-2 border-transparent">3</div>
            <div class="absolute -bottom-6 left-1/2 -translate-x-1/2 w-max"><span id="text-3" class="text-xs font-semibold text-gray-400">Cuenta</span></div>
        </div>
    </div>
</div>

@if ($errors->any())
<div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg">
    <p class="text-sm font-semibold text-red-700 mb-2">Por favor corrige los siguientes errores:</p>
    <ul class="list-disc list-inside text-sm text-red-600">
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<form method="POST" action="{{ route('register') }}" id="registerForm" class="space-y-6">
    @csrf
    
    <!-- Paso 1: Información Personal -->
    <div id="step-1" class="form-step animate-fade-in">
        <input type="hidden" name="rol_id" value="3">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-6">
            <!-- Primer Nombre -->
            <div>
                <label for="primer_nombre" class="block text-sm font-medium text-slate-700">Primer Nombre *</label>
                <input type="text" name="primer_nombre" id="primer_nombre" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('primer_nombre') border-red-500 @enderror" required value="{{ old('primer_nombre') }}">
                <span id="error-primer_nombre" class="text-xs text-red-600 mt-1 hidden"></span>
                @error('primer_nombre')<span class="text-xs text-red-600 mt-1">{{ $message }}</span>@enderror
            </div>

            <!-- Segundo Nombre -->
            <div>
                <label for="segundo_nombre" class="block text-sm font-medium text-slate-700">Segundo Nombre *</label>
                <input type="text" name="segundo_nombre" id="segundo_nombre" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('segundo_nombre') border-red-500 @enderror" required value="{{ old('segundo_nombre') }}">
                <span id="error-segundo_nombre" class="text-xs text-red-600 mt-1 hidden"></span>
                @error('segundo_nombre')<span class="text-xs text-red-600 mt-1">{{ $message }}</span>@enderror
            </div>

            <!-- Primer Apellido -->
            <div>
                <label for="primer_apellido" class="block text-sm font-medium text-slate-700">Primer Apellido *</label>
                <input type="text" name="primer_apellido" id="primer_apellido" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('primer_apellido') border-red-500 @enderror" required value="{{ old('primer_apellido') }}">
                <span id="error-primer_apellido" class="text-xs text-red-600 mt-1 hidden"></span>
                @error('primer_apellido')<span class="text-xs text-red-600 mt-1">{{ $message }}</span>@enderror
            </div>

            <!-- Segundo Apellido -->
            <div>
                <label for="segundo_apellido" class="block text-sm font-medium text-slate-700">Segundo Apellido *</label>
                <input type="text" name="segundo_apellido" id="segundo_apellido" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('segundo_apellido') border-red-500 @enderror" required value="{{ old('segundo_apellido') }}">
                <span id="error-segundo_apellido" class="text-xs text-red-600 mt-1 hidden"></span>
                @error('segundo_apellido')<span class="text-xs text-red-600 mt-1">{{ $message }}</span>@enderror
            </div>

            <!-- Cédula -->
            <div>
                <label class="block text-sm font-medium text-slate-700">Cédula *</label>
                <div class="flex gap-2 mt-1">
                    <select name="tipo_documento" id="tipo_documento" class="block w-24 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" required>
                        <option value="V" {{ old('tipo_documento') == 'V' ? 'selected' : '' }}>V</option>
                        <option value="E" {{ old('tipo_documento') == 'E' ? 'selected' : '' }}>E</option>
                        <option value="P" {{ old('tipo_documento') == 'P' ? 'selected' : '' }}>P</option>
                        <option value="J" {{ old('tipo_documento') == 'J' ? 'selected' : '' }}>J</option>
                    </select>
                    <input type="text" name="numero_documento" id="numero_documento" placeholder="12345678901" class="block flex-1 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('numero_documento') border-red-500 @enderror" required maxlength="12" value="{{ old('numero_documento') }}">
                </div>
                <span id="error-numero_documento" class="text-xs text-red-600 mt-1 hidden"></span>
                @error('numero_documento')<span class="text-xs text-red-600 mt-1">{{ $message }}</span>@enderror
            </div>

            <!-- Fecha Nacimiento -->
            <div>
                <label for="fecha_nac" class="block text-sm font-medium text-slate-700">Fecha Nacimiento *</label>
                <input type="date" name="fecha_nac" id="fecha_nac" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('fecha_nac') border-red-500 @enderror" required value="{{ old('fecha_nac') }}">
                <span id="error-fecha_nac" class="text-xs text-red-600 mt-1 hidden"></span>
                @error('fecha_nac')<span class="text-xs text-red-600 mt-1">{{ $message }}</span>@enderror
            </div>

            <!-- Sexo -->
            <div>
                <label for="genero" class="block text-sm font-medium text-slate-700">Sexo *</label>
                <select name="genero" id="genero" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('genero') border-red-500 @enderror" required>
                    <option value="">Seleccionar...</option>
                    <option value="Masculino" {{ old('genero') == 'Masculino' ? 'selected' : '' }}>Masculino</option>
                    <option value="Femenino" {{ old('genero') == 'Femenino' ? 'selected' : '' }}>Femenino</option>
                </select>
                <span id="error-genero" class="text-xs text-red-600 mt-1 hidden"></span>
                @error('genero')<span class="text-xs text-red-600 mt-1">{{ $message }}</span>@enderror
            </div>

            <!-- Teléfono -->
            <div>
                <label class="block text-sm font-medium text-slate-700">Teléfono *</label>
                <div class="flex gap-2 mt-1">
                    <select name="prefijo_tlf" id="prefijo_tlf" class="block w-24 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" required>
                        <option value="+58" {{ old('prefijo_tlf') == '+58' ? 'selected' : '' }}>+58</option>
                        <option value="+57" {{ old('prefijo_tlf') == '+57' ? 'selected' : '' }}>+57</option>
                        <option value="+1" {{ old('prefijo_tlf') == '+1' ? 'selected' : '' }}>+1</option>
                        <option value="+34" {{ old('prefijo_tlf') == '+34' ? 'selected' : '' }}>+34</option>
                    </select>
                    <input type="tel" name="numero_tlf" id="numero_tlf" placeholder="4121234567" class="block flex-1 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('numero_tlf') border-red-500 @enderror" required maxlength="15" value="{{ old('numero_tlf') }}">
                </div>
                <span id="error-numero_tlf" class="text-xs text-red-600 mt-1 hidden"></span>
                @error('numero_tlf')<span class="text-xs text-red-600 mt-1">{{ $message }}</span>@enderror
            </div>
        </div>
    </div>

    <!-- Paso 2: Ubicación -->
    <div id="step-2" class="form-step hidden animate-fade-in">
        <div class="grid grid-cols-1 gap-6">
            <div>
                <label for="estado_id" class="block text-sm font-medium text-slate-700">Estado *</label>
                <select name="estado_id" id="estado_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" required>
                    <option value="">Seleccionar estado...</option>
                     @foreach($estados ?? [] as $estado)
                        <option value="{{ $estado->id_estado }}" {{ old('estado_id') == $estado->id_estado ? 'selected' : '' }}>{{ $estado->estado }}</option>
                    @endforeach
                </select>
                <span id="error-estado_id" class="text-xs text-red-600 mt-1 hidden"></span>
            </div>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-6">
                <div>
                     <label for="ciudad_id" class="block text-sm font-medium text-slate-700">Ciudad</label>
                     <select name="ciudad_id" id="ciudad_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                         <option value="">Primero selecciona estado...</option>
                     </select>
                </div>
                 <div>
                     <label for="municipio_id" class="block text-sm font-medium text-slate-700">Municipio</label>
                     <select name="municipio_id" id="municipio_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                         <option value="">Primero selecciona estado...</option>
                     </select>
                </div>
            </div>

             <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-6">
                 <div>
                     <label for="parroquia_id" class="block text-sm font-medium text-slate-700">Parroquia</label>
                     <select name="parroquia_id" id="parroquia_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                         <option value="">Primero selecciona municipio...</option>
                     </select>
                </div>
                 <div>
                     <label for="direccion" class="block text-sm font-medium text-slate-700">Dirección Exacta</label>
                     <input type="text" name="direccion" id="direccion" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" placeholder="Av. Ppal, Edif. A, Apto 1" value="{{ old('direccion') }}">
                </div>
            </div>
        </div>
    </div>

    <!-- Paso 3: Seguridad -->
    <div id="step-3" class="form-step hidden animate-fade-in">
        <div class="space-y-6">
            <div>
                <label for="correo" class="block text-sm font-medium text-slate-700">Correo Electrónico *</label>
                <input type="email" name="correo" id="correo" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('correo') border-red-500 @enderror" required placeholder="ejemplo@email.com" value="{{ old('correo') }}">
                <span id="error-correo" class="text-xs text-red-600 mt-1 hidden"></span>
                @error('correo')<span class="text-xs text-red-600 mt-1">{{ $message }}</span>@enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-6">
                <div>
                    <label for="password" class="block text-sm font-medium text-slate-700">Contraseña *</label>
                    <div class="mb-2">
                        <p class="text-xs text-slate-500 mb-1">La contraseña debe contener:</p>
                        <ul class="text-xs space-y-1 text-slate-500 pl-1">
                            <li id="req-length"><i class="bi bi-circle"></i> Mínimo 8 caracteres</li>
                            <li id="req-upper"><i class="bi bi-circle"></i> Al menos una mayúscula</li>
                            <li id="req-number"><i class="bi bi-circle"></i> Al menos un número</li>
                            <li id="req-symbol"><i class="bi bi-circle"></i> Al menos un símbolo (@$!%*#?&.)</li>
                        </ul>
                    </div>
                    <div class="relative mt-1">
                        <input type="password" name="password" id="password" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm pr-10 @error('password') border-red-500 @enderror" required placeholder="Tu contraseña segura">
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center cursor-pointer" id="togglePassword1">
                             <i class="bi bi-eye text-gray-400"></i>
                         </div>
                    </div>
                     
                     <!-- Strength Meter -->
                     <div class="mt-2">
                        <div class="flex justify-between items-center mb-1">
                            <span class="text-xs text-gray-500" id="strength-text">Fuerza: Sin contraseña</span>
                            <span class="text-xs text-gray-400" id="strength-score">0%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-1.5">
                            <div id="strength-bar" class="bg-red-500 h-1.5 rounded-full transition-all duration-300" style="width: 0%"></div>
                        </div>
                     </div>
                     
                     <span id="error-password" class="text-xs text-red-600 mt-1 hidden"></span>
                     @error('password')<span class="text-xs text-red-600 mt-1">{{ $message }}</span>@enderror
                </div>
                 <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-slate-700">Repetir Contraseña *</label>
                    <div class="relative mt-1">
                        <input type="password" name="password_confirmation" id="password_confirmation" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm pr-10" required placeholder="Confirmar contraseña">
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center cursor-pointer" id="togglePassword2">
                             <i class="bi bi-eye text-gray-400"></i>
                         </div>
                    </div>
                    <span id="error-password_confirmation" class="text-xs text-red-600 mt-1 hidden"></span>
                </div>
            </div>

            <div class="bg-blue-50 p-4 rounded-lg border border-blue-100">
                <h4 class="text-sm font-semibold text-blue-800 mb-3">Preguntas de recuperación</h4>
                <div class="space-y-3">
                     @for($i = 1; $i <= 3; $i++)
                        <div>
                             <select name="pregunta_seguridad_{{ $i }}" id="pregunta_seguridad_{{ $i }}" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm py-2" required>
                                 <option value="">Seleccionar pregunta {{ $i }}...</option>
                                  @foreach($preguntas ?? [] as $pregunta)
                                    <option value="{{ $pregunta->id }}" {{ old("pregunta_seguridad_$i") == $pregunta->id ? 'selected' : '' }}>{{ $pregunta->pregunta }}</option>
                                @endforeach
                             </select>
                             <input type="text" name="respuesta_seguridad_{{ $i }}" id="respuesta_seguridad_{{ $i }}" class="mt-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm py-2" placeholder="Respuesta" required value="{{ old("respuesta_seguridad_$i") }}">
                             <span id="error-pregunta_seguridad_{{ $i }}" class="text-xs text-red-600 mt-1 hidden"></span>
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
    window.currentStep = 1;
    window.totalSteps = 3;

    window.checkPasswordStrength = function(password) {
        const hasUpperCase = /[A-Z]/.test(password);
        const hasNumber = /[0-9]/.test(password);
        const hasSymbol = /[@$!%*#?&.]/.test(password);
        const minLength = password.length >= 8;

        let score = 0;
        if(minLength) score++;
        if(hasUpperCase) score++;
        if(hasNumber) score++;
        if(hasSymbol) score++;

        return {
            valid: hasUpperCase && hasNumber && hasSymbol && minLength,
            score: score,
            requirements: {
                length: minLength,
                upper: hasUpperCase,
                number: hasNumber,
                symbol: hasSymbol
            }
        };
    };

    document.addEventListener('DOMContentLoaded', () => {
        const passInput = document.getElementById('password');
        if(passInput) {
            passInput.addEventListener('input', function() {
                const val = this.value;
                const result = window.checkPasswordStrength(val);
                
                // Update UI Requirements
                updateRequirement('req-length', result.requirements.length);
                updateRequirement('req-upper', result.requirements.upper);
                updateRequirement('req-number', result.requirements.number);
                updateRequirement('req-symbol', result.requirements.symbol);

                // Update Meter
                const bar = document.getElementById('strength-bar');
                const text = document.getElementById('strength-text');
                const scoreText = document.getElementById('strength-score');
                
                if(bar && text && scoreText) {
                    const pct = (result.score / 4) * 100;
                    bar.style.width = pct + '%';
                    scoreText.textContent = pct + '%';
                    
                    if(result.score <= 1) {
                        bar.className = 'bg-red-500 h-1.5 rounded-full transition-all duration-300';
                        text.textContent = 'Fuerza: Débil';
                    } else if(result.score <= 3) {
                         bar.className = 'bg-yellow-500 h-1.5 rounded-full transition-all duration-300';
                         text.textContent = 'Fuerza: Media';
                    } else {
                         bar.className = 'bg-green-500 h-1.5 rounded-full transition-all duration-300';
                         text.textContent = 'Fuerza: Segura';
                    }
                }
            });
        }

        function updateRequirement(id, met) {
            const el = document.getElementById(id);
            if(el) {
                if(met) {
                    el.classList.remove('text-slate-500');
                    el.classList.add('text-green-600', 'font-medium');
                    el.querySelector('i').className = 'bi bi-check-circle-fill';
                } else {
                    el.classList.add('text-slate-500');
                    el.classList.remove('text-green-600', 'font-medium');
                    el.querySelector('i').className = 'bi bi-circle';
                }
            }
        }
    });

    // Utility to show error below field
    function showError(fieldId, message) {
        const el = document.getElementById(fieldId);
        const errSpan = document.getElementById('error-' + fieldId);
        if (el) {
            el.classList.add('border-red-500');
            el.classList.remove('border-gray-300');
        }
        if (errSpan) {
            errSpan.textContent = message;
            errSpan.classList.remove('hidden');
        }
    }

    function clearError(fieldId) {
        const el = document.getElementById(fieldId);
        const errSpan = document.getElementById('error-' + fieldId);
        if (el) {
            el.classList.remove('border-red-500');
            el.classList.add('border-gray-300');
        }
        if (errSpan) {
            errSpan.classList.add('hidden');
        }
    }

    function clearAllErrors() {
        document.querySelectorAll('[id^="error-"]').forEach(el => el.classList.add('hidden'));
        document.querySelectorAll('.border-red-500').forEach(el => {
            el.classList.remove('border-red-500');
            el.classList.add('border-gray-300');
        });
    }

    window.changeStep = function(dir) {
        const nextStep = window.currentStep + dir;
        
        if (dir === 1) {
            if (!window.validateStep(window.currentStep)) return;
        }

        if (nextStep >= 1 && nextStep <= window.totalSteps) {
            window.showStep(nextStep);
        }
    };

    window.showStep = function(step) {
        document.querySelectorAll('.form-step').forEach(el => el.classList.add('hidden'));
        const target = document.getElementById('step-' + step);
        if(target) target.classList.remove('hidden');

        const prev = document.getElementById('prevBtn');
        const next = document.getElementById('nextBtn');
        const submit = document.getElementById('submitBtn');

        if(prev) prev.style.display = (step === 1) ? 'none' : 'inline-flex';
        if(next) next.classList.toggle('hidden', step === window.totalSteps);
        if(submit) submit.classList.toggle('hidden', step !== window.totalSteps);

        updateIndicators(step);
        window.currentStep = step;
    };

    // Validation Rules
    const validationRules = {
        'primer_nombre': { required: true, regex: /^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s]+$/, msg: 'Solo letras permitidas' },
        'segundo_nombre': { required: true, regex: /^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s]+$/, msg: 'Solo letras permitidas' },
        'primer_apellido': { required: true, regex: /^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s]+$/, msg: 'Solo letras permitidas' },
        'segundo_apellido': { required: true, regex: /^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s]+$/, msg: 'Solo letras permitidas' },
        'numero_documento': { required: true, regex: /^\d+$/, minLen: 6, maxLen: 12, msg: 'Solo números (6-12 dígitos)' },
        'fecha_nac': { required: true, msg: 'Fecha requerida' },
        'genero': { required: true, msg: 'Seleccione una opción' },
        'numero_tlf': { required: true, regex: /^\d+$/, minLen: 10, maxLen: 15, msg: 'Solo números válidos' },
        'estado_id': { required: true, msg: 'Seleccione un estado' },
        'municipio_id': { required: true, msg: 'Seleccione un municipio' },
        'parroquia_id': { required: true, msg: 'Seleccione una parroquia' },
        'direccion': { required: true, msg: 'Dirección requerida' },
        'correo': { required: true, email: true, msg: 'Correo inválido' },
        'password': { required: true, password: true, msg: 'Verifique requisitos' },
        'password_confirmation': { required: true, match: 'password', msg: 'Las contraseñas no coinciden' },
        'pregunta_seguridad_1': { required: true },
        'pregunta_seguridad_2': { required: true },
        'pregunta_seguridad_3': { required: true },
        'respuesta_seguridad_1': { required: true },
        'respuesta_seguridad_2': { required: true },
        'respuesta_seguridad_3': { required: true }
    };

    function validateField(id, value, eventType = 'blur') {
        const rule = validationRules[id];
        if(!rule) return true;

        let valid = true;
        let errorMsg = '';

        // Input-time validation (loose format checks)
        if (eventType === 'input') {
             if (rule.regex && value && !rule.regex.test(value)) {
                // If it's a strongly enforced regex (like numbers only), we might fix the value or show error
                // For names, we show error immediately if invalid char typed
                valid = false;
                errorMsg = rule.msg;
            }
            if (id === 'password') {
                // Password strength is handled by its own listener checkPasswordStrength
                return true; 
            }
        }

        // Blur-time validation (strict required/length checks)
        if (eventType === 'blur' || eventType === 'submit') {
            if (rule.required && !value.trim()) {
                valid = false;
                errorMsg = 'Campo requerido';
            } else if (rule.email && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value)) {
                valid = false;
                errorMsg = 'Formato de correo inválido';
            } else if (rule.minLen && value.length < rule.minLen) {
                valid = false;
                errorMsg = `Mínimo ${rule.minLen} caracteres`;
            } else if (rule.match) {
                const otherVal = document.getElementById(rule.match).value;
                if (value !== otherVal) {
                    valid = false;
                    errorMsg = rule.msg;
                }
            } else if (rule.regex && !rule.regex.test(value)) {
                 valid = false;
                 errorMsg = rule.msg;
            }
        }

        if(!valid && errorMsg) {
             showError(id, errorMsg);
        } else {
             clearError(id);
        }
        return valid;
    }

    // Attach listeners
    document.addEventListener('DOMContentLoaded', () => {
        Object.keys(validationRules).forEach(id => {
            const el = document.getElementById(id);
            if(el) {
                el.addEventListener('input', (e) => validateField(id, e.target.value, 'input'));
                el.addEventListener('blur', (e) => validateField(id, e.target.value, 'blur'));
            }
        });
    });

    window.validateStep = function(step) {
        // Validation now delegates to individual checks
        let isValid = true;
        let idsToCheck = [];

        if (step === 1) {
            idsToCheck = ['primer_nombre', 'segundo_nombre', 'primer_apellido', 'segundo_apellido', 'numero_documento', 'fecha_nac', 'genero', 'numero_tlf'];
        } else if (step === 2) {
             idsToCheck = ['estado_id', 'municipio_id', 'parroquia_id', 'direccion'];
        } else if (step === 3) {
             idsToCheck = ['correo', 'password', 'password_confirmation', 
                           'pregunta_seguridad_1', 'respuesta_seguridad_1',
                           'pregunta_seguridad_2', 'respuesta_seguridad_2',
                           'pregunta_seguridad_3', 'respuesta_seguridad_3'];
        }

        idsToCheck.forEach(id => {
            const el = document.getElementById(id);
            if(el) {
                if(!validateField(id, el.value, 'submit')) {
                    isValid = false;
                }
            }
        });

        // Special check for password strength only on step 3 submit attempt
        if (step === 3) {
             const p1 = document.getElementById('password').value;
             const strength = window.checkPasswordStrength(p1);
             if (!strength.valid) {
                 isValid = false;
                 // Error is already shown by strength meter visual, but we ensure field is red
                 showError('password', 'Contraseña débil');
             }
        }

        return isValid;
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
             if(!strength.requirements.length) msg += '- Al menos 8 caracteres\n';
             if(!strength.requirements.upper) msg += '- Al menos una mayúscula\n';
             if(!strength.requirements.number) msg += '- Al menos un número\n';
             if(!strength.requirements.symbol) msg += '- Al menos un símbolo (@$!%*#?&.)';
             alert(msg);
        }
    });

    function updateIndicators(step) {
        for(let i=1; i<=3; i++) {
            const ind = document.getElementById('ind-' + i);
            const txt = document.getElementById('text-' + i);
            if(!ind) continue;
            
            ind.className = "w-10 h-10 rounded-full flex items-center justify-center font-bold ring-4 ring-white transition-all duration-300 border-2";
             if(txt) txt.className = "text-xs font-semibold";
            
            if (i < step) {
                ind.classList.add('bg-green-500', 'text-white', 'border-transparent');
                ind.innerHTML = '✓';
                if(txt) txt.classList.add('text-green-600');
            } else if (i === step) {
                ind.classList.add('bg-blue-600', 'text-white', 'border-transparent', 'shadow-md');
                ind.innerHTML = i;
                if(txt) txt.classList.add('text-blue-600');
            } else {
                ind.classList.add('bg-gray-100', 'text-gray-400', 'border-transparent');
                ind.innerHTML = i;
                if(txt) txt.classList.add('text-gray-400');
            }
        }
    }

    // Security questions: prevent duplicates
    document.addEventListener('DOMContentLoaded', () => {
        const selects = [
            document.getElementById('pregunta_seguridad_1'),
            document.getElementById('pregunta_seguridad_2'),
            document.getElementById('pregunta_seguridad_3')
        ];

        function updateSelects() {
            const selectedValues = selects.map(s => s ? s.value : '').filter(v => v);
            
            selects.forEach(select => {
                if (!select) return;
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
        updateSelects();

        // Password toggle
        const togglePassword = (input, toggle) => {
            if (!input || !toggle) return;
            toggle.addEventListener('click', () => {
                const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
                input.setAttribute('type', type);
                toggle.querySelector('i').classList.toggle('bi-eye');
                toggle.querySelector('i').classList.toggle('bi-eye-slash');
            });
        };

        togglePassword(document.getElementById('password'), document.getElementById('togglePassword1'));
        togglePassword(document.getElementById('password_confirmation'), document.getElementById('togglePassword2'));

        // Only allow numbers in cedula and phone
        ['numero_documento', 'numero_tlf'].forEach(id => {
            const el = document.getElementById(id);
            if (el) {
                el.addEventListener('input', (e) => {
                    e.target.value = e.target.value.replace(/[^0-9]/g, '');
                });
            }
        });
    });

    // Location Logic
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
</script>
@endpush
@endsection
