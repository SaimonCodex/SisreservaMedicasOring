@extends('layouts.auth')

@section('title', 'Restablecer Contraseña')

@section('auth-content')
<div class="mb-8">
    <h2 class="mt-6 text-3xl font-display font-bold text-slate-900 tracking-tight">
        Crear Nueva Contraseña
    </h2>
    <p class="mt-2 text-sm text-slate-500">
        Tu identidad ha sido verificada. Ahora puedes establecer una nueva contraseña segura.
    </p>
</div>

<div class="rounded-md bg-green-50 p-4 mb-6 border border-green-200">
    <div class="flex">
        <div class="flex-shrink-0">
             <i class="bi bi-check-circle-fill text-green-400"></i>
        </div>
        <div class="ml-3">
            <h3 class="text-sm font-medium text-green-800">Verificación Exitosa</h3>
            <div class="mt-2 text-sm text-green-700">
                <p>Puedes continuar con el proceso de restablecimiento.</p>
            </div>
        </div>
    </div>
</div>

<form method="POST" action="{{ route('password.update') }}" id="resetForm" class="space-y-6">
    @csrf
    <input type="hidden" name="token" value="{{ request('token') }}">
    <input type="hidden" name="email" value="{{ request('email') }}">

    <div class="space-y-5">
        <div>
            <label for="password" class="block text-sm font-medium text-slate-700">Nueva Contraseña</label>
            <div class="mt-1 relative rounded-md shadow-sm">
                 <input type="password" name="password" id="password" 
                       class="focus:ring-medical-500 focus:border-medical-500 block w-full pr-10 sm:text-sm border-gray-300 rounded-md" 
                       placeholder="Mínimo 8 caracteres" required>
                 <div class="absolute inset-y-0 right-0 pr-3 flex items-center cursor-pointer" onclick="togglePasswordVisibility('password', 'toggleIcon1')">
                     <i class="bi bi-eye text-gray-400" id="toggleIcon1"></i>
                 </div>
            </div>
             <div id="password-strength" class="mt-2 text-xs"></div>
        </div>

        <div>
            <label for="password_confirmation" class="block text-sm font-medium text-slate-700">Confirmar Contraseña</label>
             <div class="mt-1 relative rounded-md shadow-sm">
                 <input type="password" name="password_confirmation" id="password_confirmation" 
                       class="focus:ring-medical-500 focus:border-medical-500 block w-full pr-10 sm:text-sm border-gray-300 rounded-md" 
                       placeholder="Repite tu contraseña" required>
                 <div class="absolute inset-y-0 right-0 pr-3 flex items-center cursor-pointer" onclick="togglePasswordVisibility('password_confirmation', 'toggleIcon2')">
                     <i class="bi bi-eye text-gray-400" id="toggleIcon2"></i>
                 </div>
            </div>
        </div>
    </div>

    <!-- Password Requirements Info -->
    <div class="rounded-md bg-blue-50 p-4 border border-blue-100">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="bi bi-info-circle text-blue-400"></i>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-blue-800">Requisitos de seguridad</h3>
                <div class="mt-2 text-sm text-blue-700">
                    <ul class="list-disc pl-5 space-y-1">
                        <li>Mínimo 8 caracteres</li>
                        <li>Al menos una letra mayúscula</li>
                        <li>Al menos un número</li>
                        <li>Al menos un carácter especial</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <button type="submit" id="submitBtn" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-medical-600 hover:bg-medical-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-medical-500">
         <i class="bi bi-check-lg mr-2"></i>
         Restablecer Contraseña
    </button>
    
    <div class="text-center">
        <a href="{{ route('login') }}" class="text-sm font-medium text-slate-500 hover:text-slate-700 flex items-center justify-center gap-1">
            <i class="bi bi-arrow-left"></i>
            Volver al inicio de sesión
        </a>
    </div>
</form>

<!-- Success Modal -->
<div id="successModal" class="fixed inset-0 bg-gray-500 bg-opacity-75 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg px-4 pt-5 pb-4 text-center overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-sm sm:w-full sm:p-6">
        <div>
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-green-100">
                <i class="bi bi-check-lg text-green-600 text-xl"></i>
            </div>
            <div class="mt-3 text-center sm:mt-5">
                <h3 class="text-lg leading-6 font-medium text-gray-900">¡Contraseña Actualizada!</h3>
                <div class="mt-2">
                    <p class="text-sm text-gray-500">
                        Tu contraseña ha sido restablecida correctamente. Redirigiendo al login en <span id="countdown">5</span>...
                    </p>
                </div>
            </div>
        </div>
        <div class="mt-5 sm:mt-6">
            <a href="{{ route('login') }}" class="inline-flex justify-center w-full rounded-md border border-transparent shadow-sm px-4 py-2 bg-medical-600 text-base font-medium text-white hover:bg-medical-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-medical-500 sm:text-sm">
                Ir al Login Ahora
            </a>
        </div>
    </div>
</div>

@push('scripts')
<script type="module">
import { validatePassword, validatePasswordMatch, showFieldFeedback } from '/js/validators.js';
import { showToast, shakeElement, toggleSubmitButton } from '/js/alerts.js';
import { initPasswordStrengthIndicator } from '/js/auth-utils.js';

window.togglePasswordVisibility = function(inputId, iconId) {
    const input = document.getElementById(inputId);
    const icon = document.getElementById(iconId);
    if(input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('bi-eye');
        icon.classList.add('bi-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.remove('bi-eye-slash');
        icon.classList.add('bi-eye');
    }
};

const passwordInput = document.getElementById('password');
const strengthContainer = document.getElementById('password-strength');
initPasswordStrengthIndicator(passwordInput, strengthContainer);

const passwordConfirmationInput = document.getElementById('password_confirmation');
const checkMatch = () => {
    if(passwordConfirmationInput.value) {
        const result = validatePasswordMatch(passwordInput.value, passwordConfirmationInput.value);
        if(!result.valid) {
             passwordConfirmationInput.classList.add('border-red-300', 'focus:ring-red-500');
        } else {
             passwordConfirmationInput.classList.remove('border-red-300', 'focus:ring-red-500');
        }
    }
};

passwordConfirmationInput.addEventListener('input', checkMatch);
passwordConfirmationInput.addEventListener('blur', checkMatch);

const resetForm = document.getElementById('resetForm');
const submitBtn = document.getElementById('submitBtn');

resetForm.addEventListener('submit', function(e) {
    e.preventDefault();
    
    if(!validatePassword(passwordInput.value).valid) {
        showToast('error', 'La contraseña es muy débil');
        shakeElement(passwordInput);
        return;
    }
    
    if(!validatePasswordMatch(passwordInput.value, passwordConfirmationInput.value).valid) {
        showToast('error', 'Las contraseñas no coinciden');
        shakeElement(passwordConfirmationInput);
        return;
    }
    
    toggleSubmitButton(submitBtn, true, 'Actualizando...');
    
    // DEMO SUCCESS
    setTimeout(() => {
        toggleSubmitButton(submitBtn, false);
        showSuccessModal();
    }, 1500);
});

function showSuccessModal() {
    const modal = document.getElementById('successModal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    
    let seconds = 5;
    const el = document.getElementById('countdown');
    const interval = setInterval(() => {
        seconds--;
        el.textContent = seconds;
        if(seconds <= 0) {
            clearInterval(interval);
            window.location.href = '{{ route("login") }}';
        }
    }, 1000);
}

@if($errors->any())
    @foreach($errors->all() as $error)
        showToast('error', '{{ $error }}', 8000);
    @endforeach
@endif
</script>
@endpush
@endsection
