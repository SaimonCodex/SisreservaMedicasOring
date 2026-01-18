@extends('layouts.auth')

@section('title', 'Nueva Contraseña')

@section('auth-content')
<div class="mb-8">
    <h2 class="mt-6 text-3xl font-display font-bold text-slate-900 tracking-tight">
        Crear Nueva Contraseña
    </h2>
    <p class="mt-2 text-sm text-slate-500">
        Ingresa tu nueva contraseña de acceso al sistema.
    </p>
</div>

<form method="POST" action="{{ route('password.update') }}" id="resetPasswordForm" class="space-y-6">
    @csrf
    <input type="hidden" name="token" value="{{ $token ?? request()->route('token') }}">
    <input type="hidden" name="email" value="{{ $email ?? request()->get('email') }}">

    <!-- Password -->
    <div>
        <label for="password" class="block text-sm font-medium text-slate-700 mb-1">
            Nueva Contraseña
        </label>
        <div class="relative rounded-lg shadow-sm">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i class="bi bi-lock text-slate-400"></i>
            </div>
            <input 
                type="password" 
                name="password" 
                id="password" 
                class="block w-full pl-10 pr-10 py-3 sm:text-sm border-gray-300 rounded-lg focus:ring-medical-500 focus:border-medical-500 @error('password') border-red-300 @enderror" 
                placeholder="Mínimo 8 caracteres"
                required
                autocomplete="new-password"
            >
            <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                <button type="button" onclick="togglePassword('password')" class="text-slate-400 hover:text-slate-600 focus:outline-none">
                    <i class="bi bi-eye" id="togglePasswordIcon"></i>
                </button>
            </div>
        </div>
        @error('password')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
        @enderror
        
        <!-- Password Strength Indicator -->
        <div class="mt-3 hidden" id="strengthIndicator">
            <div class="flex items-center gap-2 mb-1">
                <div class="flex-1 h-2 bg-slate-200 rounded-full overflow-hidden">
                    <div id="strengthBar" class="h-full transition-all duration-300" style="width: 0%"></div>
                </div>
                <span id="strengthText" class="text-xs font-medium"></span>
            </div>
            <p class="text-xs text-slate-500" id="strengthHelp"></p>
        </div>
    </div>

    <!-- Password Confirmation -->
    <div>
        <label for="password_confirmation" class="block text-sm font-medium text-slate-700 mb-1">
            Confirmar Contraseña
        </label>
        <div class="relative rounded-lg shadow-sm">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i class="bi bi-lock-fill text-slate-400"></i>
            </div>
            <input 
                type="password" 
                name="password_confirmation" 
                id="password_confirmation" 
                class="block w-full pl-10 pr-10 py-3 sm:text-sm border-gray-300 rounded-lg focus:ring-medical-500 focus:border-medical-500" 
                placeholder="Repite tu contraseña"
                required
                autocomplete="new-password"
            >
            <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                <button type="button" onclick="togglePassword('password_confirmation')" class="text-slate-400 hover:text-slate-600 focus:outline-none">
                    <i class="bi bi-eye" id="togglePasswordConfirmationIcon"></i>
                </button>
            </div>
        </div>
        <p class="mt-2 text-sm text-slate-500" id="matchFeedback"></p>
    </div>

    <!-- Security Tips -->
    <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded-lg">
        <div class="flex items-start">
            <div class="flex-shrink-0">
                <i class="bi bi-info-circle-fill text-blue-400"></i>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-blue-800">Consejos de Seguridad</h3>
                <div class="mt-2 text-sm text-blue-700">
                    <ul class="list-disc list-inside space-y-1">
                        <li>Mínimo 8 caracteres</li>
                        <li>Combina mayúsculas y minúsculas</li>
                        <li>Incluye números y símbolos</li>
                        <li>No uses contraseñas anteriores</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Submit Button -->
    <div>
        <button 
            type="submit" 
            id="submitBtn"
            class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-medical-600 hover:bg-medical-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-medical-500 disabled:opacity-50 transition-all"
        >
            <i class="bi bi-shield-check mr-2"></i>
            Restablecer Contraseña
        </button>
    </div>

    <div class="text-center">
        <a href="{{ route('login') }}" class="text-sm font-medium text-medical-600 hover:text-medical-500 flex items-center justify-center gap-1">
            <i class="bi bi-arrow-left"></i>
            Volver al inicio de sesión
        </a>
    </div>
</form>

@push('scripts')
<script>
    function togglePassword(fieldId) {
        const field = document.getElementById(fieldId);
        const icon = document.getElementById(`toggle${fieldId.charAt(0).toUpperCase() + fieldId.slice(1)}Icon`);
        
        if (field.type === 'password') {
            field.type = 'text';
            icon.classList.remove('bi-eye');
            icon.classList.add('bi-eye-slash');
        } else {
            field.type = 'password';
            icon.classList.remove('bi-eye-slash');
            icon.classList.add('bi-eye');
        }
    }
</script>

<script type="module">
import { showToast, shakeElement, toggleSubmitButton } from '{{ asset("js/alerts.js") }}';

const passwordInput = document.getElementById('password');
const confirmInput = document.getElementById('password_confirmation');
const strengthIndicator = document.getElementById('strengthIndicator');
const strengthBar = document.getElementById('strengthBar');
const strengthText = document.getElementById('strengthText');
const strengthHelp = document.getElementById('strengthHelp');
const matchFeedback = document.getElementById('matchFeedback');
const form = document.getElementById('resetPasswordForm');
const submitBtn = document.getElementById('submitBtn');

// Password strength calculator
function calculateStrength(password) {
    let strength = 0;
    const checks = {
        length: password.length >= 8,
        lowercase: /[a-z]/.test(password),
        uppercase: /[A-Z]/.test(password),
        number: /[0-9]/.test(password),
        special: /[^A-Za-z0-9]/.test(password)
    };
    
    if (checks.length) strength += 20;
    if (password.length >= 12) strength += 10;
    if (checks.lowercase) strength += 20;
    if (checks.uppercase) strength += 20;
    if (checks.number) strength += 15;
    if (checks.special) strength += 15;
    
    return { strength, checks };
}

// Update strength indicator
passwordInput.addEventListener('input', function() {
    const password = this.value;
    
    if (password.length === 0) {
        strengthIndicator.classList.add('hidden');
        return;
    }
    
    strengthIndicator.classList.remove('hidden');
    const { strength, checks } = calculateStrength(password);
    
    strengthBar.style.width = strength + '%';
    
    if (strength < 40) {
        strengthBar.className = 'h-full transition-all duration-300 bg-red-500';
        strengthText.textContent = 'Débil';
        strengthText.className = 'text-xs font-medium text-red-600';
        strengthHelp.textContent = 'Añade más caracteres y variedad';
    } else if (strength < 70) {
        strengthBar.className = 'h-full transition-all duration-300 bg-yellow-500';
        strengthText.textContent = 'Media';
        strengthText.className = 'text-xs font-medium text-yellow-600';
        strengthHelp.textContent = 'Puedes mejorarla con símbolos';
    } else {
        strengthBar.className = 'h-full transition-all duration-300 bg-green-500';
        strengthText.textContent = 'Fuerte';
        strengthText.className = 'text-xs font-medium text-green-600';
        strengthHelp.textContent = '¡Excelente contraseña!';
    }
    
    checkPasswordMatch();
});

// Check password match
function checkPasswordMatch() {
    const password = passwordInput.value;
    const confirm = confirmInput.value;
    
    if (confirm.length === 0) {
        matchFeedback.textContent = '';
        matchFeedback.className = 'mt-2 text-sm text-slate-500';
        return;
    }
    
    if (password === confirm) {
        matchFeedback.textContent = '✓ Las contraseñas coinciden';
        matchFeedback.className = 'mt-2 text-sm text-green-600 font-medium';
    } else {
        matchFeedback.textContent = '✗ Las contraseñas no coinciden';
        matchFeedback.className = 'mt-2 text-sm text-red-600 font-medium';
    }
}

confirmInput.addEventListener('input', checkPasswordMatch);

// Form submission
form.addEventListener('submit', function(e) {
    const password = passwordInput.value;
    const confirm = confirmInput.value;
    
    if (password !== confirm) {
        e.preventDefault();
        showToast('error', 'Las contraseñas no coinciden');
        shakeElement(confirmInput);
        return;
    }
    
    if (password.length < 8) {
        e.preventDefault();
        showToast('error', 'La contraseña debe tener al menos 8 caracteres');
        shakeElement(passwordInput);
        return;
    }
    
    toggleSubmitButton(submitBtn, true, 'Restableciendo...');
});

// Show session messages
@if(session('error'))
    showToast('error', '{{ session('error') }}');
@endif

@if(session('success'))
    showToast('success', '{{ session('success') }}');
@endif
</script>
@endpush
@endsection
