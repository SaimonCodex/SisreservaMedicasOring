@extends('layouts.auth')

@section('title', 'Recuperar Contraseña')

@section('auth-content')
<!-- Header Icon -->
<div class="flex justify-center mb-6">
    <div class="w-16 h-16 rounded-2xl bg-amber-100 text-amber-600 flex items-center justify-center shadow-lg transform hover:scale-110 transition-transform duration-300">
        <i class="bi bi-key-fill text-3xl"></i>
    </div>
</div>

<!-- Title & Description -->
<div class="text-center mb-8">
    <h2 class="text-3xl font-display font-bold text-slate-900 tracking-tight">
        Recuperar Contraseña
    </h2>
    <p class="mt-2 text-sm text-slate-500">
        Elige cómo deseas recuperar tu cuenta
    </p>
</div>

<!-- Method Selection (Step 0) -->
<div id="methodSelection" class="space-y-4">
    <p class="text-sm text-slate-600 text-center mb-6">
        Selecciona el método de recuperación que prefieras:
    </p>

    <!-- Email Option -->
    <button 
        onclick="selectMethod('email')"
        type="button"
        class="w-full group relative flex items-center p-5 border-2 border-slate-200 rounded-xl hover:border-medical-500 hover:bg-medical-50 transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-medical-500/20"
    >
        <div class="flex-shrink-0">
            <div class="w-12 h-12 rounded-lg bg-medical-100 text-medical-600 flex items-center justify-center group-hover:scale-110 transition-transform">
                <i class="bi bi-envelope-fill text-2xl"></i>
            </div>
        </div>
        <div class="ml-4 flex-1 text-left">
            <h3 class="text-sm font-bold text-slate-900 group-hover:text-medical-600 transition-colors">
                Recuperar por Email
            </h3>
            <p class="text-xs text-slate-500 mt-1">
                Recibirás un enlace de recuperación en tu correo
            </p>
        </div>
        <i class="bi bi-arrow-right text-slate-400 group-hover:text-medical-600 group-hover:translate-x-1 transition-all"></i>
    </button>

    <!-- Security Questions Option -->
    <button 
        onclick="selectMethod('questions')"
        type="button"
        class="w-full group relative flex items-center p-5 border-2 border-slate-200 rounded-xl hover:border-blue-500 hover:bg-blue-50 transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500/20"
    >
        <div class="flex-shrink-0">
            <div class="w-12 h-12 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center group-hover:scale-110 transition-transform">
                <i class="bi bi-shield-lock-fill text-2xl"></i>
            </div>
        </div>
        <div class="ml-4 flex-1 text-left">
            <h3 class="text-sm font-bold text-slate-900 group-hover:text-blue-600 transition-colors">
                Preguntas de Seguridad
            </h3>
            <p class="text-xs text-slate-500 mt-1">
                Responde tus preguntas de seguridad
            </p>
        </div>
        <i class="bi bi-arrow-right text-slate-400 group-hover:text-blue-600 group-hover:translate-x-1 transition-all"></i>
    </button>

    <!-- Back to Login -->
    <div class="text-center pt-4">
        <a href="{{ route('login') }}" class="text-sm font-medium text-slate-500 hover:text-slate-700 flex items-center justify-center gap-1 group">
            <i class="bi bi-arrow-left group-hover:-translate-x-1 transition-transform"></i>
            Volver al inicio de sesión
        </a>
    </div>
</div>

<!-- Email Recovery Method -->
<div id="emailMethod" class="hidden space-y-6 animate-fade-in">
    <div class="bg-medical-50 border-l-4 border-medical-500 p-4 rounded-lg mb-6">
        <div class="flex items-start">
            <i class="bi bi-info-circle-fill text-medical-500 mt-0.5"></i>
            <div class="ml-3">
                <p class="text-sm text-medical-800">
                    Te enviaremos un enlace de recuperación a tu correo electrónico registrado.
                </p>
            </div>
        </div>
    </div>

    <form id="emailRecoveryForm" class="space-y-5">
        @csrf
        <div>
            <label for="email_recovery" class="block text-sm font-semibold text-slate-700 mb-2">
                Correo Electrónico
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <i class="bi bi-envelope text-slate-400"></i>
                </div>
                <input 
                    type="email" 
                    id="email_recovery" 
                    name="email" 
                    class="block w-full pl-11 pr-4 py-3.5 text-sm border-2 border-slate-200 rounded-xl focus:ring-2 focus:ring-medical-500/20 focus:border-medical-500 transition-all"
                    placeholder="tu@email.com" 
                    required
                >
            </div>
        </div>

        <button type="submit" id="emailRecoveryBtn" class="w-full flex justify-center items-center py-3.5 px-4 border border-transparent rounded-xl shadow-lg text-sm font-bold text-white bg-gradient-to-r from-medical-600 to-blue-600 hover:from-medical-700 hover:to-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-medical-500 transform hover:scale-[1.02] transition-all duration-200">
            <i class="bi bi-send-fill mr-2"></i>
            Enviar Enlace de Recuperación
        </button>

        <button type="button" onclick="backToSelection()" class="w-full text-sm font-medium text-slate-500 hover:text-slate-700 flex items-center justify-center gap-1">
            <i class="bi bi-arrow-left"></i>
            Elegir otro método
        </button>
    </form>
</div>

<!-- Questions Recovery Method --> 
<div id="questionsMethod" class="hidden animate-fade-in">
    <!-- Progress Steps -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div class="flex flex-col items-center flex-1">
                <div id="step1-indicator" class="w-10 h-10 flex items-center justify-center rounded-full bg-medical-600 text-white font-bold text-sm transition-all shadow-lg">
                    1
                </div>
                <div class="mt-2 text-xs font-medium text-medical-600">Identificación</div>
            </div>
            
            <div class="flex-1 h-1 bg-gray-200 transition-colors mx-2" id="progress-line"></div>
            
            <div class="flex flex-col items-center flex-1">
                <div id="step2-indicator" class="w-10 h-10 flex items-center justify-center rounded-full bg-gray-200 text-gray-500 font-bold text-sm transition-all">
                    2
                </div>
                <div class="mt-2 text-xs font-medium text-gray-500" id="step2-text">Verificación</div>
            </div>
        </div>
    </div>

    <!-- Step 1: Identification -->
    <div id="step1" class="transition-opacity duration-300 space-y-6">
        <form id="identificationForm" class="space-y-5">
            @csrf
            
            <div>
                <label for="identifier" class="block text-sm font-semibold text-slate-700 mb-2">
                    Correo Electrónico o Cédula
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <i class="bi bi-person-circle text-slate-400"></i>
                    </div>
                    <input 
                        type="text" 
                        id="identifier" 
                        name="identifier" 
                        class="block w-full pl-11 pr-4 py-3.5 text-sm border-2 border-slate-200 rounded-xl focus:ring-2 focus:ring-medical-500/20 focus:border-medical-500 transition-all"
                        placeholder="tu@email.com o V-12345678" 
                        required
                        autofocus
                    >
                </div>
                <p class="mt-2 text-xs text-slate-500">
                    Ingresa el dato asociado a tu cuenta
                </p>
            </div>

            <button type="submit" id="verifyBtn" class="w-full flex justify-center items-center py-3.5 px-4 border border-transparent rounded-xl shadow-lg text-sm font-bold text-white bg-gradient-to-r from-blue-600 to-cyan-600 hover:from-blue-700 hover:to-cyan-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transform hover:scale-[1.02] transition-all duration-200">
                <i class="bi bi-search mr-2"></i>
                Buscar Cuenta
            </button>

            <button type="button" onclick="backToSelection()" class="w-full text-sm font-medium text-slate-500 hover:text-slate-700 flex items-center justify-center gap-1">
                <i class="bi bi-arrow-left"></i>
                Elegir otro método
            </button>
        </form>
    </div>

    <!-- Step 2: Security Questions -->
    <div id="step2" class="hidden transition-opacity duration-300 space-y-6">
        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-lg mb-6">
            <div class="flex items-start">
                <i class="bi bi-exclamation-triangle-fill text-yellow-400 mt-0.5"></i>
                <div class="ml-3">
                    <h3 class="text-sm font-semibold text-yellow-800">Verificación de Seguridad</h3>
                    <p class="text-sm text-yellow-700 mt-1">
                        Responde las 3 preguntas. Tienes <span id="attempts-left" class="font-bold">3</span> intentos.
                    </p>
                </div>
            </div>
        </div>

        <form id="securityForm" class="space-y-5">
            @csrf
            <input type="hidden" name="user_id" id="user_id">
            
            <div id="security-questions-container" class="space-y-5">
                <!-- Questions loaded dynamically -->
            </div>

            <button type="submit" id="verifyQuestionsBtn" class="w-full flex justify-center items-center py-3.5 px-4 border border-transparent rounded-xl shadow-lg text-sm font-bold text-white bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transform hover:scale-[1.02] transition-all duration-200">
                <i class="bi bi-shield-check mr-2"></i>
                Verificar Respuestas
            </button>

            <button type="button" onclick="location.reload()" class="w-full text-sm font-medium text-slate-500 hover:text-slate-700 flex items-center justify-center gap-1">
                <i class="bi bi-arrow-left"></i>
                Intentar con otra cuenta
            </button>
        </form>
    </div>
</div>

@push('styles')
<style>
@keyframes fade-in {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-fade-in {
    animation: fade-in 0.3s ease-out;
}
</style>
@endpush

@push('scripts')
<script>
// Global functions
function selectMethod(method) {
    document.getElementById('methodSelection').classList.add('hidden');
    
    if (method === 'email') {
        document.getElementById('emailMethod').classList.remove('hidden');
    } else if (method === 'questions') {
        document.getElementById('questionsMethod').classList.remove('hidden');
    }
}

function backToSelection() {
    document.getElementById('methodSelection').classList.remove('hidden');
    document.getElementById('emailMethod').classList.add('hidden');
    document.getElementById('questionsMethod').classList.add('hidden');
    
    // Reset questions method
    document.getElementById('step1').classList.remove('hidden');
    document.getElementById('step2').classList.add('hidden');
}
</script>

<script type="module">
import { validateEmail, validateCedula, showFieldFeedback } from '{{ asset("js/validators.js") }}';
import { showToast, shakeElement, toggleSubmitButton, showLoading } from '{{ asset("js/alerts.js") }}';

let attemptsRemaining = 3;
let securityQuestions = [];
let userId = null;

// ===== EMAIL RECOVERY =====
const emailRecoveryForm = document.getElementById('emailRecoveryForm');
const emailRecoveryBtn = document.getElementById('emailRecoveryBtn');

if(emailRecoveryForm) {
    emailRecoveryForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const email = document.getElementById('email_recovery').value.trim();
        
        if (!validateEmail(email).valid) {
            showToast('error', 'Por favor ingresa un correo válido');
            return;
        }
        
        const loading = showLoading('Enviando enlace...');
        toggleSubmitButton(emailRecoveryBtn, true, 'Enviando...');
        
        try {
            const response = await fetch("{{ route('recovery.send-email') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('[name="_token"]').value
                },
                body: JSON.stringify({ email })
            });
            
            const text = await response.text();
            let data;
            try {
                data = JSON.parse(text);
            } catch (e) {
                console.warn('Response was not JSON:', text);
                throw new Error('La respuesta del servidor no es válida.');
            }
            
            loading.close();
            toggleSubmitButton(emailRecoveryBtn, false);
            
            if (data.success) {
                showToast('success', 'Enlace enviado. Revisa tu correo electrónico.', 5000);
                setTimeout(() => window.location.href = '{{ route('login') }}', 3000);
            } else {
                showToast('error', data.message || 'Correo no encontrado');
            }
        } catch (error) {
            loading.close();
            toggleSubmitButton(emailRecoveryBtn, false);
            console.error('Error detallado:', error);
            showToast('error', error.message || 'Error al enviar el enlace. Intenta de nuevo.');
        }
    });
}

// ===== SECURITY QUESTIONS RECOVERY =====
const identificationForm = document.getElementById('identificationForm');
const verifyBtn = document.getElementById('verifyBtn');

if(identificationForm) {
    identificationForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const identifier = document.getElementById('identifier').value.trim();
        
        if (!identifier) {
            showToast('warning', 'Por favor ingresa tu correo o cédula');
            return;
        }
        
        const loading = showLoading('Buscando cuenta...');
        toggleSubmitButton(verifyBtn, true, 'Buscando...');
        
        // Get CSRF token from meta tag or form input
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') 
                       || document.querySelector('[name="_token"]')?.value;
        
        console.log('Sending request to get security questions for:', identifier);
        console.log('CSRF Token:', csrfToken ? 'Found' : 'Missing');
        
        try {
            const response = await fetch("{{ route('recovery.get-questions') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({ identifier })
            });
            
            console.log('Response status:', response.status);
            
            const data = await response.json();
            console.log('Response data:', data);
            
            loading.close();
            toggleSubmitButton(verifyBtn, false);
            
            if (data.success) {
                console.log('Questions found:', data.questions.length);
                securityQuestions = data.questions;
                userId = data.user_id;
                showStep2(data.questions, data.user_id);
            } else {
                console.error('Request failed:', data.message);
                showToast('error', data.message || 'Usuario no encontrado');
                shakeElement(document.getElementById('identifier'));
            }
            
        } catch (error) {
            console.error('Error in security questions request:', error);
            loading.close();
            toggleSubmitButton(verifyBtn, false);
            showToast('error', 'Usuario no encontrado');
            shakeElement(document.getElementById('identifier'));
        }
    });
}

function showStep2(questions, user_id) {
    document.getElementById('step1').classList.add('hidden');
    
    // Update indicators
    const step1Ind = document.getElementById('step1-indicator');
    step1Ind.classList.remove('bg-medical-600');
    step1Ind.classList.add('bg-green-500');
    step1Ind.innerHTML = '<i class="bi bi-check"></i>';
    
    document.getElementById('progress-line').classList.remove('bg-gray-200');
    document.getElementById('progress-line').classList.add('bg-medical-600');
    
    const step2Ind = document.getElementById('step2-indicator');
    document.getElementById('step2-text').classList.remove('text-gray-500');
    document.getElementById('step2-text').classList.add('text-medical-600');
    step2Ind.classList.remove('bg-gray-200', 'text-gray-500');
    step2Ind.classList.add('bg-medical-600', 'text-white', 'shadow-lg');
    
    // Render questions
    const container = document.getElementById('security-questions-container');
    container.innerHTML = '';
    
    questions.forEach((q, index) => {
        container.innerHTML += `
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">
                    Pregunta ${index + 1}: ${q.pregunta}
                </label>
                <input type="hidden" name="question_${index + 1}_id" value="${q.id}">
                <input 
                    type="text" 
                    name="answer_${index + 1}" 
                    class="block w-full px-4 py-3.5 text-sm border-2 border-slate-200 rounded-xl focus:ring-2 focus:ring-medical-500/20 focus:border-medical-500 transition-all" 
                    placeholder="Tu respuesta" 
                    required
                    autocomplete="off"
                >
            </div>
        `;
    });
    
    document.getElementById('user_id').value = user_id;
    document.getElementById('step2').classList.remove('hidden');
}

const securityForm = document.getElementById('securityForm');
const verifyQuestionsBtn = document.getElementById('verifyQuestionsBtn');

if (securityForm) {
    securityForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        if (attemptsRemaining <= 0) {
            showToast('error', 'Has agotado tus intentos.');
            return;
        }
        
        const formData = new FormData(this);
        const loading = showLoading('Verificando respuestas...');
        toggleSubmitButton(verifyQuestionsBtn, true, 'Verificando...');
        
        try {
            const response = await fetch("{{ route('recovery.verify-answers') }}", {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('[name="_token"]').value
                },
                body: formData
            });
            
            const data = await response.json();
            loading.close();
            toggleSubmitButton(verifyQuestionsBtn, false);
            
            if (data.success) {
                showToast('success', '¡Correcto! Redirigiendo...', 2000);
                setTimeout(() => {
                    if(data.token && data.email) {
                        window.location.href = "{{ url('/reset-password') }}/" + data.token + "?email=" + data.email;
                    }
                }, 1500);
            } else if (data.locked) {
                // Account has been locked
                handleAccountLocked(data);
            } else {
                // Wrong answers, but not locked yet
                handleFailure(data);
            }
            
        } catch (error) {
            console.error('Error in security questions verification:', error);
            loading.close();
            toggleSubmitButton(verifyQuestionsBtn, false);
            showToast('error', 'Error al verificar las respuestas');
        }
    });
}

function handleAccountLocked(data) {
    const blockedUntil = data.blocked_until || '24 horas';
    
    showToast('error', `Cuenta bloqueada por seguridad hasta ${blockedUntil}`, 10000);
    
    // Disable form
    verifyQuestionsBtn.disabled = true;
    verifyQuestionsBtn.innerHTML = '<i class="bi bi-lock-fill mr-2"></i>Cuenta Bloqueada';
    verifyQuestionsBtn.classList.remove('bg-gradient-to-r', 'from-green-600', 'to-emerald-600');
    verifyQuestionsBtn.classList.add('bg-gray-400', 'cursor-not-allowed');
    
    // Show lockout message
    const lockoutMsg = document.createElement('div');
    lockoutMsg.className = 'bg-red-50 border-l-4 border-red-500 p-4 rounded-lg mt-4';
    lockoutMsg.innerHTML = `
        <div class="flex items-start">
            <i class="bi bi-exclamation-triangle-fill text-red-500 mt-0.5 text-xl"></i>
            <div class="ml-3">
                <h3 class="text-sm font-semibold text-red-800">Cuenta Bloqueada Temporalmente</h3>
                <p class="text-sm text-red-700 mt-1">
                    Tu cuenta ha sido bloqueada hasta <strong>${blockedUntil}</strong> por seguridad debido a múltiples intentos fallidos.
                </p>
                <p class="text-xs text-red-600 mt-2">
                    Recibirás un correo electrónico con más información.
                </p>
            </div>
        </div>
    `;
    securityForm.appendChild(lockoutMsg);
    
    attemptsRemaining = 0;
}

function handleFailure(data) {
    // Update attempts from server response if provided
    if (data.attempts_remaining !== undefined) {
        attemptsRemaining = data.attempts_remaining;
        document.getElementById('attempts-left').textContent = attemptsRemaining;
    } else {
        attemptsRemaining--;
        document.getElementById('attempts-left').textContent = attemptsRemaining;
    }
    
    if (attemptsRemaining > 0) {
        showToast('error', `Respuestas incorrectas. ${attemptsRemaining} ${attemptsRemaining === 1 ? 'intento restante' : 'intentos restantes'}.`, 5000);
        shakeElement(securityForm);
        document.querySelectorAll('[name^="answer_"]').forEach(i => {
           i.value = '';
           i.classList.add('border-red-300');
           setTimeout(() => i.classList.remove('border-red-300'), 3000);
        });
    } else {
        showToast('error', 'Cuenta bloqueada temporalmente.', 5000);
        verifyQuestionsBtn.disabled = true;
    }
}
</script>
@endpush
@endsection
