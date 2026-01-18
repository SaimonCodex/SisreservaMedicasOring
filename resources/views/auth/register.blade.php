@extends('layouts.auth')

@section('title', 'Crear Cuenta')
@section('box-width', 'max-w-[1200px]')
@section('form-width', 'max-w-xl')

@section('auth-content')
<!-- Header Section -->
<div class="mb-8">
    <div class="flex items-center gap-3 mb-2">
        <span class="px-3 py-1 bg-medical-100 text-medical-600 text-xs font-bold rounded-full uppercase tracking-wider">Registro de Paciente</span>
    </div>
    <h2 class="text-3xl font-display font-bold text-slate-900 tracking-tight">
        Bienvenido a MediReserva
    </h2>
    <p class="mt-2 text-sm text-slate-500">
        Completa tu registro en 3 sencillos pasos para empezar a gestionar tus citas.
    </p>
</div>

<!-- Steps Progress Bar -->
<div class="mb-10 relative">
    <div class="flex justify-between items-center w-full relative z-10">
        <!-- Step 1 -->
        <div class="flex flex-col items-center">
            <div id="ind-1" class="w-10 h-10 rounded-xl flex items-center justify-center bg-medical-600 text-white font-bold shadow-lg shadow-medical-200 transition-all duration-300">
                1
            </div>
            <span id="text-1" class="text-[10px] sm:text-xs font-bold mt-2 text-medical-600">Personal</span>
        </div>
        
        <!-- Connector 1-2 -->
        <div class="flex-1 h-1 mx-2 rounded-full bg-slate-100 overflow-hidden">
            <div id="prog-1" class="h-full bg-medical-600 transition-all duration-500" style="width: 0%"></div>
        </div>

        <!-- Step 2 -->
        <div class="flex flex-col items-center">
            <div id="ind-2" class="w-10 h-10 rounded-xl flex items-center justify-center bg-slate-100 text-slate-400 font-bold transition-all duration-300">
                2
            </div>
            <span id="text-2" class="text-[10px] sm:text-xs font-bold mt-2 text-slate-400">Ubicación</span>
        </div>

        <!-- Connector 2-3 -->
        <div class="flex-1 h-1 mx-2 rounded-full bg-slate-100 overflow-hidden">
            <div id="prog-2" class="h-full bg-medical-600 transition-all duration-500" style="width: 0%"></div>
        </div>

        <!-- Step 3 -->
        <div class="flex flex-col items-center">
            <div id="ind-3" class="w-10 h-10 rounded-xl flex items-center justify-center bg-slate-100 text-slate-400 font-bold transition-all duration-300">
                3
            </div>
            <span id="text-3" class="text-[10px] sm:text-xs font-bold mt-2 text-slate-400">Seguridad</span>
        </div>
    </div>
</div>

<form method="POST" action="{{ route('register') }}" id="registerForm" class="space-y-6">
    @csrf
    <input type="hidden" name="rol_id" value="3">
    
    <!-- Paso 1: Información Personal -->
    <div id="step-1" class="form-step animate-fade-in space-y-5">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            <!-- Nombres -->
            <div class="group">
                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Primer Nombre *</label>
                <div class="relative">
                    <input type="text" name="primer_nombre" id="primer_nombre" class="w-full px-4 py-3 bg-slate-50 border-2 border-slate-100 rounded-xl focus:bg-white focus:border-medical-500 focus:ring-4 focus:ring-medical-500/10 transition-all outline-none text-sm" placeholder="Ej. Juan" required value="{{ old('primer_nombre') }}">
                    <span id="error-primer_nombre" class="text-[10px] text-red-500 font-bold mt-1 hidden block"></span>
                </div>
            </div>
            <div class="group">
                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Segundo Nombre</label>
                <div class="relative">
                    <input type="text" name="segundo_nombre" id="segundo_nombre" class="w-full px-4 py-3 bg-slate-50 border-2 border-slate-100 rounded-xl focus:bg-white focus:border-medical-500 focus:ring-4 focus:ring-medical-500/10 transition-all outline-none text-sm" placeholder="Ej. Antonio" value="{{ old('segundo_nombre') }}">
                </div>
            </div>
            
            <!-- Apellidos -->
            <div class="group">
                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Primer Apellido *</label>
                <div class="relative">
                    <input type="text" name="primer_apellido" id="primer_apellido" class="w-full px-4 py-3 bg-slate-50 border-2 border-slate-100 rounded-xl focus:bg-white focus:border-medical-500 focus:ring-4 focus:ring-medical-500/10 transition-all outline-none text-sm" placeholder="Ej. Pérez" required value="{{ old('primer_apellido') }}">
                </div>
            </div>
            <div class="group">
                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Segundo Apellido</label>
                <div class="relative">
                    <input type="text" name="segundo_apellido" id="segundo_apellido" class="w-full px-4 py-3 bg-slate-50 border-2 border-slate-100 rounded-xl focus:bg-white focus:border-medical-500 focus:ring-4 focus:ring-medical-500/10 transition-all outline-none text-sm" placeholder="Ej. Rodríguez" value="{{ old('segundo_apellido') }}">
                </div>
            </div>

            <!-- Identificación -->
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Documento de Identidad *</label>
                <div class="flex gap-2">
                    <select name="tipo_documento" id="tipo_documento" class="w-20 px-3 py-3 bg-slate-50 border-2 border-slate-100 rounded-xl focus:bg-white focus:border-medical-500 outline-none text-sm">
                        <option value="V" {{ old('tipo_documento') == 'V' ? 'selected' : '' }}>V</option>
                        <option value="E" {{ old('tipo_documento') == 'E' ? 'selected' : '' }}>E</option>
                        <option value="P" {{ old('tipo_documento') == 'P' ? 'selected' : '' }}>P</option>
                    </select>
                    <input type="text" name="numero_documento" id="numero_documento" class="flex-1 px-4 py-3 bg-slate-50 border-2 border-slate-100 rounded-xl focus:bg-white focus:border-medical-500 focus:ring-4 focus:ring-medical-500/10 transition-all outline-none text-sm" placeholder="12345678" required value="{{ old('numero_documento') }}">
                </div>
                <span id="error-numero_documento" class="text-[10px] text-red-500 font-bold mt-1 hidden block"></span>
            </div>

            <!-- Sexo -->
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Género *</label>
                <select name="genero" id="genero" class="w-full px-4 py-3 bg-slate-50 border-2 border-slate-100 rounded-xl focus:bg-white focus:border-medical-500 outline-none text-sm" required>
                    <option value="">Seleccionar...</option>
                    <option value="Femenino" {{ old('genero') == 'Femenino' ? 'selected' : '' }}>Femenino</option>
                    <option value="Masculino" {{ old('genero') == 'Masculino' ? 'selected' : '' }}>Masculino</option>
                </select>
            </div>

            <!-- Fecha Nacimiento -->
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Fecha de Nacimiento *</label>
                <input type="date" name="fecha_nac" id="fecha_nac" class="w-full px-4 py-3 bg-slate-50 border-2 border-slate-100 rounded-xl focus:bg-white focus:border-medical-500 outline-none text-sm" required value="{{ old('fecha_nac') }}">
                <span id="label-edad" class="text-[10px] text-slate-500 font-bold mt-1 block tracking-tight">Debes ser mayor de 18 años</span>
            </div>

            <!-- Teléfono -->
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Teléfono Móvil *</label>
                <div class="flex gap-2">
                    <select name="prefijo_tlf" class="w-24 px-2 py-3 bg-slate-50 border-2 border-slate-100 rounded-xl focus:bg-white focus:border-medical-500 outline-none text-sm">
                        <option value="+58" selected>+58 (VE)</option>
                        <option value="+57">+57 (CO)</option>
                        <option value="+1">+1 (US)</option>
                    </select>
                    <input type="tel" name="numero_tlf" id="numero_tlf" class="flex-1 px-4 py-3 bg-slate-50 border-2 border-slate-100 rounded-xl focus:bg-white focus:border-medical-500 outline-none text-sm" placeholder="4121234567" required value="{{ old('numero_tlf') }}">
                </div>
            </div>
        </div>
    </div>

    <!-- Paso 2: Ubicación -->
    <div id="step-2" class="form-step hidden animate-fade-in space-y-6">
        <div>
            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Estado de Residencia *</label>
            <select name="estado_id" id="estado_id" class="w-full px-4 py-3 bg-slate-50 border-2 border-slate-100 rounded-xl focus:bg-white focus:border-medical-500 outline-none text-sm" required>
                <option value="">Seleccionar Estado...</option>
                @foreach($estados ?? [] as $estado)
                    <option value="{{ $estado->id_estado }}" {{ old('estado_id') == $estado->id_estado ? 'selected' : '' }}>{{ $estado->estado }}</option>
                @endforeach
            </select>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Ciudad</label>
                <select name="ciudad_id" id="ciudad_id" class="w-full px-4 py-3 bg-slate-50 border-2 border-slate-100 rounded-xl focus:bg-white focus:border-medical-500 outline-none text-sm">
                    <option value="">Selecciona un estado...</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Municipio</label>
                <select name="municipio_id" id="municipio_id" class="w-full px-4 py-3 bg-slate-50 border-2 border-slate-100 rounded-xl focus:bg-white focus:border-medical-500 outline-none text-sm">
                    <option value="">Selecciona un estado...</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Parroquia</label>
                <select name="parroquia_id" id="parroquia_id" class="w-full px-4 py-3 bg-slate-50 border-2 border-slate-100 rounded-xl focus:bg-white focus:border-medical-500 outline-none text-sm">
                    <option value="">Selecciona un municipio...</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Ocupación / Profesión</label>
                <input type="text" name="ocupacion" class="w-full px-4 py-3 bg-slate-50 border-2 border-slate-100 rounded-xl focus:bg-white focus:border-medical-500 outline-none text-sm" placeholder="Ej. Ingeniero" value="{{ old('ocupacion') }}">
            </div>
        </div>

        <div>
            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Dirección Detallada</label>
            <textarea name="direccion" id="direccion" rows="2" class="w-full px-4 py-3 bg-slate-50 border-2 border-slate-100 rounded-xl focus:bg-white focus:border-medical-500 outline-none text-sm resize-none" placeholder="Calle, edificio, urbanización...">{{ old('direccion') }}</textarea>
        </div>
    </div>

    <!-- Paso 3: Seguridad -->
    <div id="step-3" class="form-step hidden animate-fade-in space-y-6">
        <div>
            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Correo Electrónico *</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <i class="bi bi-envelope text-slate-400"></i>
                </div>
                <input type="email" name="correo" id="correo" class="w-full pl-11 pr-4 py-3 bg-slate-50 border-2 border-slate-100 rounded-xl focus:bg-white focus:border-medical-500 outline-none text-sm" placeholder="tu@email.com" required value="{{ old('correo') }}">
            </div>
            <span id="error-correo" class="text-[10px] text-red-500 font-bold mt-1 hidden block"></span>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Contraseña *</label>
                <input type="password" name="password" id="password" class="w-full px-4 py-3 bg-slate-50 border-2 border-slate-100 rounded-xl focus:bg-white focus:border-medical-500 outline-none text-sm" placeholder="••••••••" required>
                
                <div class="mt-3 bg-slate-50 p-3 rounded-xl border border-slate-100">
                    <p class="text-[10px] font-bold text-slate-400 mb-2 uppercase tracking-widest">Requisitos de Seguridad</p>
                    <ul class="space-y-1.5">
                        <li id="req-length" class="text-xs text-slate-500 flex items-center gap-2"><i class="bi bi-circle"></i> 8+ caracteres</li>
                        <li id="req-upper" class="text-xs text-slate-500 flex items-center gap-2"><i class="bi bi-circle"></i> Una mayúscula</li>
                        <li id="req-number" class="text-xs text-slate-500 flex items-center gap-2"><i class="bi bi-circle"></i> Un número</li>
                        <li id="req-symbol" class="text-xs text-slate-500 flex items-center gap-2"><i class="bi bi-circle"></i> Un símbolo</li>
                    </ul>
                </div>
            </div>
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Confirmar Contraseña *</label>
                <input type="password" name="password_confirmation" id="password_confirmation" class="w-full px-4 py-3 bg-slate-50 border-2 border-slate-100 rounded-xl focus:bg-white focus:border-medical-500 outline-none text-sm" placeholder="••••••••" required>
            </div>
        </div>

        <!-- Security Questions Modernized -->
        <div class="bg-blue-50/50 p-5 rounded-2xl border-2 border-blue-100/50">
            <h4 class="text-sm font-bold text-blue-900 mb-4 flex items-center gap-2">
                <i class="bi bi-shield-lock-fill"></i>
                Preguntas de Seguridad
            </h4>
            <div class="space-y-4">
                @for($i = 1; $i <= 3; $i++)
                <div class="space-y-2">
                    <select name="pregunta_seguridad_{{ $i }}" id="pregunta_seguridad_{{ $i }}" class="w-full px-3 py-2.5 bg-white border-2 border-blue-100 rounded-xl text-xs focus:border-blue-400 outline-none transition-all" required>
                        <option value="">Seleccionar Pregunta {{ $i }}...</option>
                        @foreach($preguntas ?? [] as $pregunta)
                            <option value="{{ $pregunta->id }}" {{ old("pregunta_seguridad_$i") == $pregunta->id ? 'selected' : '' }}>{{ $pregunta->pregunta }}</option>
                        @endforeach
                    </select>
                    <input type="text" name="respuesta_seguridad_{{ $i }}" class="w-full px-3 py-2.5 bg-white border-2 border-blue-100 rounded-xl text-xs focus:border-blue-400 outline-none transition-all" placeholder="Tu respuesta secreta" required value="{{ old("respuesta_seguridad_$i") }}">
                </div>
                @endfor
            </div>
        </div>

        <label class="flex items-center gap-3 cursor-pointer p-2 rounded-xl hover:bg-slate-50 transition-colors">
            <input type="checkbox" name="terminos" required class="w-5 h-5 text-medical-600 bg-slate-100 border-slate-300 rounded focus:ring-medical-500">
            <span class="text-xs text-slate-600">Acepto los <a href="#" class="text-medical-600 font-bold hover:underline">Términos y Condiciones</a> del servicio.</span>
        </label>
    </div>

    <!-- Navigation Buttons Modernized -->
    <div class="flex items-center justify-between pt-8 mt-4 border-t border-slate-100">
        <a href="{{ route('login') }}" class="text-sm font-bold text-slate-400 hover:text-slate-600 transition-colors flex items-center gap-2">
            <i class="bi bi-arrow-left"></i>
            Iniciar Sesión
        </a>

        <div class="flex gap-3">
            <button type="button" id="prevBtn" class="hidden px-6 py-3 bg-slate-100 text-slate-600 font-bold rounded-xl hover:bg-slate-200 transition-all text-sm" onclick="window.changeStep(-1)">
                Anterior
            </button>
            
            <button type="button" id="nextBtn" class="px-8 py-3 bg-medical-600 text-white font-bold rounded-xl shadow-lg shadow-medical-200 hover:bg-medical-700 hover:-translate-y-0.5 active:translate-y-0 transition-all text-sm flex items-center gap-2" onclick="window.changeStep(1)">
                Siguiente
                <i class="bi bi-arrow-right"></i>
            </button>
            
            <button type="submit" id="submitBtn" class="hidden px-8 py-3 bg-gradient-to-r from-medical-600 to-blue-600 text-white font-bold rounded-xl shadow-lg shadow-blue-200 hover:opacity-90 hover:-translate-y-0.5 active:translate-y-0 transition-all text-sm flex items-center gap-2">
                Crear Mi Cuenta
                <i class="bi bi-check2-circle"></i>
            </button>
        </div>
    </div>
</form>

@push('scripts')
<script type="module">
import { showToast, shakeElement, toggleSubmitButton } from '{{ asset("js/alerts.js") }}';

window.currentStep = 1;
window.totalSteps = 3;

// --- Step Functionality ---
window.changeStep = async function(dir) {
    if (dir === 1) {
        const stepValid = await window.validateStep(window.currentStep);
        if (!stepValid) return;
    }

    const nextStep = window.currentStep + dir;
    if (nextStep >= 1 && nextStep <= window.totalSteps) {
        window.showStep(nextStep);
    }
}

window.showStep = function(step) {
    document.querySelectorAll('.form-step').forEach(el => el.classList.add('hidden'));
    document.getElementById('step-' + step).classList.remove('hidden');

    // Update buttons
    document.getElementById('prevBtn').classList.toggle('hidden', step === 1);
    document.getElementById('nextBtn').classList.toggle('hidden', step === window.totalSteps);
    document.getElementById('submitBtn').classList.toggle('hidden', step !== window.totalSteps);

    // Update progress bar
    updateProgressBar(step);
    window.currentStep = step;
    
    // Scroll to top of form smoothly
    document.querySelector('form').scrollIntoView({ behavior: 'smooth', block: 'start' });
}

function updateProgressBar(step) {
    for (let i = 1; i <= 3; i++) {
        const ind = document.getElementById('ind-' + i);
        const txt = document.getElementById('text-' + i);
        const prog = document.getElementById('prog-' + (i-1));

        if (i < step) {
            // Completed
            ind.className = "w-10 h-10 rounded-xl flex items-center justify-center bg-green-500 text-white font-bold shadow-lg shadow-green-100 transition-all duration-300";
            ind.innerHTML = '<i class="bi bi-check-lg"></i>';
            txt.className = "text-[10px] sm:text-xs font-bold mt-2 text-green-600";
            if (prog) prog.style.width = '100%';
        } else if (i === step) {
            // Current
            ind.className = "w-10 h-10 rounded-xl flex items-center justify-center bg-medical-600 text-white font-bold shadow-lg shadow-medical-200 transition-all duration-300 transform scale-110";
            ind.innerHTML = i;
            txt.className = "text-[10px] sm:text-xs font-bold mt-2 text-medical-600";
            if (prog) prog.style.width = '50%';
        } else {
            // Pending
            ind.className = "w-10 h-10 rounded-xl flex items-center justify-center bg-slate-100 text-slate-400 font-bold transition-all duration-300";
            ind.innerHTML = i;
            txt.className = "text-[10px] sm:text-xs font-bold mt-2 text-slate-400";
            if (prog) prog.style.width = '0%';
        }
    }
}

// --- Validation Logic ---
window.validateStep = async function(step) {
    let isValid = true;
    const form = document.getElementById('registerForm');
    
    if (step === 1) {
        const fields = ['primer_nombre', 'primer_apellido', 'numero_documento', 'fecha_nac', 'genero', 'numero_tlf'];
        fields.forEach(f => {
            const input = document.getElementById(f);
            if (!input.value.trim()){
                isValid = false;
                shakeElement(input);
                input.classList.add('border-red-300');
            } else {
                input.classList.remove('border-red-300');
            }
        });

        // Age check
        const dob = new Date(document.getElementById('fecha_nac').value);
        const age = new Date().getFullYear() - dob.getFullYear();
        if (age < 18) {
            isValid = false;
            showToast('error', 'Debes ser mayor de 18 años para registrarte.');
            shakeElement(document.getElementById('fecha_nac'));
        }
        
        // AJAX Document Check
        if (isValid) {
            const res = await checkDocumentAvailability();
            if (!res) isValid = false;
        }
    }
    
    if (step === 2) {
        const estado = document.getElementById('estado_id');
        if (!estado.value) {
            isValid = false;
            shakeElement(estado);
        }
    }
    
    if (step === 3) {
        const correo = document.getElementById('correo');
        const pass = document.getElementById('password');
        const confirm = document.getElementById('password_confirmation');
        
        if (!correo.value || !pass.value || !confirm.value) {
            isValid = false;
            if(!correo.value) shakeElement(correo);
            if(!pass.value) shakeElement(pass);
        }
        
        if (pass.value !== confirm.value) {
            isValid = false;
            showToast('error', 'Las contraseñas no coinciden.');
            shakeElement(confirm);
        }

        // AJAX Email Check
        if (isValid) {
            const res = await checkEmailAvailability();
            if (!res) isValid = false;
        }
    }

    return isValid;
}

async function checkDocumentAvailability() {
    const input = document.getElementById('numero_documento');
    const tipo = document.getElementById('tipo_documento').value;
    const num = input.value;
    
    try {
        const response = await fetch("{{ route('recovery.get-questions') }}", {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: JSON.stringify({ identifier: num })
        });
        const data = await response.json();
        if (data.success) { // If it's found, it means it already exists
            showToast('error', 'Este número de documento ya está registrado.');
            shakeElement(input);
            input.classList.add('border-red-300');
            return false;
        }
        return true;
    } catch (e) { return true; }
}

async function checkEmailAvailability() {
    const input = document.getElementById('correo');
    const email = input.value;
    
    try {
        const response = await fetch("{{ route('validate.email') }}", { 
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: JSON.stringify({ correo: email })
        });
        const data = await response.json();
        if (data.existe) {
            showToast('error', 'Este correo electrónico ya está registrado.');
            shakeElement(input);
            input.classList.add('border-red-300');
            return false;
        }
        return true;
    } catch (e) { return true; }
}

// Password strength feedback
document.getElementById('password').addEventListener('input', function() {
    const val = this.value;
    const reqs = {
        length: val.length >= 8,
        upper: /[A-Z]/.test(val),
        number: /[0-9]/.test(val),
        symbol: /[@$!%*#?&.]/.test(val)
    };

    Object.keys(reqs).forEach(key => {
        const el = document.getElementById('req-' + key);
        if (reqs[key]) {
            el.className = "text-xs text-green-600 font-bold flex items-center gap-2";
            el.querySelector('i').className = "bi bi-check-circle-fill";
        } else {
            el.className = "text-xs text-slate-500 flex items-center gap-2";
            el.querySelector('i').className = "bi bi-circle";
        }
    });
});

// Final submit
document.getElementById('registerForm').addEventListener('submit', function(e) {
    toggleSubmitButton(document.getElementById('submitBtn'), true, 'Procesando...');
});

// Dynamic location loading (replicated from shared/usuarios/create.blade.php)
document.getElementById('estado_id').addEventListener('change', function() {
    const estadoId = this.value;
    const ciudadSelect = document.getElementById('ciudad_id');
    const municipioSelect = document.getElementById('municipio_id');
    const parroquiaSelect = document.getElementById('parroquia_id');
    
    ciudadSelect.innerHTML = '<option value="">Cargando...</option>';
    municipioSelect.innerHTML = '<option value="">Selecciona un estado...</option>';
    parroquiaSelect.innerHTML = '<option value="">Selecciona un municipio...</option>';

    if (estadoId) {
        fetch(`{{ url('ubicacion/get-ciudades') }}/${estadoId}`)
            .then(r => r.json())
            .then(d => {
                ciudadSelect.innerHTML = '<option value="">Seleccionar Ciudad...</option>';
                d.forEach(i => ciudadSelect.innerHTML += `<option value="${i.id_ciudad}">${i.ciudad}</option>`);
            })
            .catch(e => console.error('Error loading ciudades:', e));

        fetch(`{{ url('ubicacion/get-municipios') }}/${estadoId}`)
            .then(r => r.json())
            .then(d => {
                municipioSelect.innerHTML = '<option value="">Seleccionar Municipio...</option>';
                d.forEach(i => municipioSelect.innerHTML += `<option value="${i.id_municipio}">${i.municipio}</option>`);
            })
            .catch(e => console.error('Error loading municipios:', e));
    } else {
        ciudadSelect.innerHTML = '<option value="">Selecciona un estado...</option>';
        municipioSelect.innerHTML = '<option value="">Selecciona un estado...</option>';
    }
});

document.getElementById('municipio_id').addEventListener('change', function() {
    const municipioId = this.value;
    const parroquiaSelect = document.getElementById('parroquia_id');
    
    parroquiaSelect.innerHTML = '<option value="">Cargando...</option>';

    if (municipioId) {
        fetch(`{{ url('ubicacion/get-parroquias') }}/${municipioId}`)
            .then(r => r.json())
            .then(d => {
                parroquiaSelect.innerHTML = '<option value="">Seleccionar Parroquia...</option>';
                d.forEach(i => parroquiaSelect.innerHTML += `<option value="${i.id_parroquia}">${i.parroquia}</option>`);
            })
            .catch(e => console.error('Error loading parroquias:', e));
    } else {
        parroquiaSelect.innerHTML = '<option value="">Selecciona un municipio...</option>';
    }
});

// --- Security Questions Logic ---
// Dynamic Disabling of Selected Options
function updateQuestionOptions() {
    const selects = [
        document.getElementById('pregunta_seguridad_1'),
        document.getElementById('pregunta_seguridad_2'),
        document.getElementById('pregunta_seguridad_3')
    ];
    
    // Get currently selected values
    const selectedValues = selects.map(s => s.value).filter(val => val !== "");

    selects.forEach(select => {
        const currentVal = select.value;
        const options = select.querySelectorAll('option');

        options.forEach(option => {
            if (option.value === "") return; 

            // If selected elsewhere AND not self, disable
            if (selectedValues.includes(option.value) && option.value !== currentVal) {
                option.disabled = true;
                option.innerText = option.innerText.replace(' (Seleccionado)', '') + ' (Seleccionado)';
            } else {
                option.disabled = false;
                option.innerText = option.innerText.replace(' (Seleccionado)', '');
            }
        });
    });
}

// Attach listener to question selects
for(let i=1; i<=3; i++) {
    const el = document.getElementById(`pregunta_seguridad_${i}`);
    if(el) el.addEventListener('change', updateQuestionOptions);
}

</script>
@endpush

@push('styles')
<style>
.form-step.animate-fade-in {
    animation: fadeIn 0.4s ease-out;
}
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}
input:focus, select:focus, textarea:focus {
    transform: translateY(-1px);
}
</style>
@endpush
@endsection
