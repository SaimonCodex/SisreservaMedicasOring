@extends('layouts.auth')

@section('title', 'Recuperar Contraseña')

@section('auth-content')
<div class="mb-8">
    <h2 class="mt-6 text-3xl font-display font-bold text-slate-900 tracking-tight">
        Recuperar Contraseña
    </h2>
    <p class="mt-2 text-sm text-slate-500">
        Responde tus preguntas de seguridad para restablecer tu contraseña.
    </p>
</div>

<!-- Progress Steps -->
<div class="mb-8">
    <div class="flex items-center justify-between">
        <div class="flex flex-col items-center flex-1">
             <div id="step1-indicator" class="w-8 h-8 flex items-center justify-center rounded-full bg-medical-600 text-white font-bold text-sm transition-colors">
                 1
             </div>
             <div class="mt-2 text-xs font-medium text-medical-600">Identificación</div>
        </div>
        
        <div class="flex-1 h-0.5 bg-gray-200 transition-colors" id="progress-line"></div>
        
        <div class="flex flex-col items-center flex-1">
             <div id="step2-indicator" class="w-8 h-8 flex items-center justify-center rounded-full bg-gray-200 text-gray-500 font-bold text-sm transition-colors">
                 2
             </div>
             <div class="mt-2 text-xs font-medium text-gray-500" id="step2-text">Verificación</div>
        </div>
    </div>
</div>

<div id="step1" class="transition-opacity duration-300">
    <form id="identificationForm" class="space-y-6">
        @csrf
        
        <div>
            <label for="identifier" class="block text-sm font-medium text-slate-700">
                Correo Electrónico o Cédula
            </label>
            <div class="mt-1 relative rounded-md shadow-sm">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="bi bi-envelope text-slate-400"></i>
                </div>
                <input 
                    type="text" 
                    id="identifier" 
                    name="identifier" 
                    class="focus:ring-medical-500 focus:border-medical-500 block w-full pl-10 sm:text-sm border-gray-300 rounded-md"
                    placeholder="tu@email.com o V-12345678" 
                    required
                    autofocus
                >
            </div>
            <p class="mt-2 text-sm text-slate-500">
                Ingresa el dato asociado a tu cuenta
            </p>
        </div>

        <button type="submit" id="verifyBtn" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-medical-600 hover:bg-medical-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-medical-500 disabled:opacity-50">
            <span class="flex items-center">
                <i class="bi bi-search mr-2"></i>
                Buscar Cuenta
            </span>
        </button>

        <div class="text-center">
            <a href="{{ route('login') }}" class="text-sm font-medium text-medical-600 hover:text-medical-500 flex items-center justify-center gap-1">
                <i class="bi bi-arrow-left"></i>
                Volver al inicio de sesión
            </a>
        </div>
    </form>
</div>

<div id="step2" class="hidden transition-opacity duration-300">
    <form id="securityForm" class="space-y-6">
        @csrf
        <input type="hidden" name="user_id" id="user_id">
        
        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="bi bi-exclamation-triangle-fill text-yellow-400"></i>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-yellow-800">Verificación de Seguridad</h3>
                    <div class="mt-2 text-sm text-yellow-700">
                        <p>Responde las 3 preguntas. Tienes <span id="attempts-left" class="font-bold">3</span> intentos.</p>
                    </div>
                </div>
            </div>
        </div>

        <div id="security-questions-container" class="space-y-6">
            <!-- Questions loaded dynamically -->
        </div>

        <button type="submit" id="verifyQuestionsBtn" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 disabled:opacity-50">
            <span class="flex items-center">
                <i class="bi bi-shield-check mr-2"></i>
                Verificar Respuestas
            </span>
        </button>

        <div class="text-center">
            <button type="button" onclick="location.reload()" class="text-sm font-medium text-slate-500 hover:text-slate-700 flex items-center justify-center gap-1 mx-auto">
                <i class="bi bi-arrow-left"></i>
                Intentar con otra cuenta
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script type="module">
import { validateEmail, validateCedula, showFieldFeedback } from '/js/validators.js';
import { showToast, shakeElement, toggleSubmitButton, showLoading } from '/js/alerts.js';

let attemptsRemaining = 3;
let securityQuestions = [];
let userId = null;

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
        
        try {
            const response = await fetch('/recovery/get-questions', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content 
                                    || document.querySelector('[name="_token"]')?.value
                },
                body: JSON.stringify({ identifier })
            });
            
            if (!response.ok) throw new Error('Usuario no encontrado');
            
            const data = await response.json();
            loading.close();
            toggleSubmitButton(verifyBtn, false);
            
            if (data.success) {
                securityQuestions = data.questions;
                userId = data.user_id;
                showStep2(data.questions, data.user_id);
            } else {
                showToast('error', data.message || 'Usuario no encontrado');
                shakeElement(document.getElementById('identifier'));
            }
            
        } catch (error) {
            loading.close();
            toggleSubmitButton(verifyBtn, false);
            
            // DEMO FALLBACK
            showToast('info', 'Modo demo: Mostrando preguntas de ejemplo');
            setTimeout(() => {
                const mockQuestions = [
                     { id: 1, pregunta: '¿Cuál es el nombre de tu primera mascota?' },
                     { id: 2, pregunta: '¿En qué ciudad naciste?' },
                     { id: 3, pregunta: '¿Cuál es el nombre de tu mejor amigo de la infancia?' }
                ];
                showStep2(mockQuestions, 1);
            }, 500);
        }
    });
}

function showStep2(questions, user_id) {
    document.getElementById('step1').classList.add('hidden');
    
    // Indicators
    const step1Ind = document.getElementById('step1-indicator');
    step1Ind.classList.remove('bg-medical-600', 'text-white');
    step1Ind.classList.add('bg-green-500', 'text-white');
    step1Ind.innerHTML = '<i class="bi bi-check"></i>';
    
    document.getElementById('progress-line').classList.remove('bg-gray-200');
    document.getElementById('progress-line').classList.add('bg-medical-600');
    
    const step2Ind = document.getElementById('step2-indicator');
    document.getElementById('step2-text').classList.remove('text-gray-500');
    document.getElementById('step2-text').classList.add('text-medical-600');
    step2Ind.classList.remove('bg-gray-200', 'text-gray-500');
    step2Ind.classList.add('bg-medical-600', 'text-white');
    
    // Render
    const container = document.getElementById('security-questions-container');
    container.innerHTML = '';
    
    questions.forEach((q, index) => {
        container.innerHTML += `
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">
                    Pregunta ${index + 1}: ${q.pregunta}
                </label>
                <input type="hidden" name="question_${index + 1}_id" value="${q.id}">
                <input 
                    type="text" 
                    name="answer_${index + 1}" 
                    class="focus:ring-medical-500 focus:border-medical-500 block w-full sm:text-sm border-gray-300 rounded-md" 
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
            const response = await fetch('/recovery/verify-answers', {
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
                    // Update to match backend route or what was agreed
                    if(data.token && data.email) {
                        window.location.href = `/reset-password/${data.token}?email=${data.email}`;
                    } else {
                        // Fallback logic
                        window.location.href = '/reset-password';
                    }
                }, 1500);
            } else {
                handleFailure();
            }
            
        } catch (error) {
            loading.close();
            toggleSubmitButton(verifyQuestionsBtn, false);
            // demo success shortcut
            showToast('success', 'Demo: Correcto. Redirigiendo...');
            setTimeout(() => window.location.href = '/reset-password/demo_token', 1500);
        }
    });
}

function handleFailure() {
    attemptsRemaining--;
    document.getElementById('attempts-left').textContent = attemptsRemaining;
    
    if (attemptsRemaining > 0) {
        showToast('error', `Respuestas incorrectas. ${attemptsRemaining} intentos restantes.`);
        shakeElement(securityForm);
        document.querySelectorAll('[name^="answer_"]').forEach(i => {
           i.value = '';
           i.classList.add('border-red-300');
        });
    } else {
        showToast('error', 'Cuenta bloqueada temporalmente.', 5000);
        verifyQuestionsBtn.disabled = true;
    }
}

const identifierInput = document.getElementById('identifier');
if(identifierInput) {
    identifierInput.addEventListener('blur', () => {
        const val = identifierInput.value.trim();
        if(val.includes('@')) showFieldFeedback(identifierInput, validateEmail(val));
        else if(val.match(/^[VEPvep]-?\d+/)) showFieldFeedback(identifierInput, validateCedula(val));
    });
}
</script>
@endpush
@endsection
