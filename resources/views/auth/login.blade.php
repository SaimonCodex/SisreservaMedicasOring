@extends('layouts.auth')

@php
    $rol = request('rol');
    
    switch($rol) {
        case 'admin':
            $theme = [
                'title' => 'Portal Administrativo',
                'description' => 'Acceso para personal administrativo',
                'color' => 'text-slate-800',
                'btn' => 'btn-primary',
                'link' => 'text-medical-600 hover:text-medical-800'
            ];
            break;
        case 'medico':
            $theme = [
                'title' => 'Portal Médico',
                'description' => 'Acceso para especialistas de salud',
                'color' => 'text-slate-800',
                'btn' => 'bg-blue-600 hover:bg-blue-700 text-white shadow-lg shadow-blue-200',
                'link' => 'text-blue-600 hover:text-blue-800'
            ];
            break;
        case 'paciente':
            $theme = [
                'title' => 'Portal Paciente',
                'description' => 'Accede a tus citas y resultados',
                'color' => 'text-slate-800',
                'btn' => 'bg-green-600 hover:bg-green-700 text-white shadow-lg shadow-green-200',
                'link' => 'text-green-600 hover:text-green-800'
            ];
            break;
        default:
            $theme = [
                'title' => 'Iniciar Sesión',
                'description' => 'Ingresa tus credenciales para continuar',
                'color' => 'text-slate-800',
                'btn' => 'btn-primary',
                'link' => 'text-medical-600 hover:text-medical-800'
            ];
    }
@endphp

@section('title', 'Iniciar Sesión')

@section('auth-content')
<div class="mb-8">
    <h2 class="mt-6 text-3xl font-display font-bold {{ $theme['color'] }} tracking-tight">
        {{ $theme['title'] }}
    </h2>
    <p class="mt-2 text-sm text-slate-500">
        O <a href="{{ route('register') }}" class="font-medium {{ $theme['link'] }}">crea una cuenta nueva</a>
    </p>
</div>

<form method="POST" action="{{ route('login') }}" id="loginForm" class="space-y-6">
    @csrf

    @if (session('error'))
        <div class="rounded-md bg-red-50 p-4 border border-red-200 animate-fade-in-down">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="bi bi-x-circle-fill text-red-400"></i>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-red-800">
                        Error de inicio de sesión
                    </h3>
                    <div class="mt-2 text-sm text-red-700">
                        <p>{{ session('error') }}</p>
                    </div>
                </div>
            </div>
        </div>
    @endif
    
    <div class="space-y-5">
        <!-- Email -->
        <div>
            <label for="correo" class="block text-sm font-medium text-slate-700 mb-1">
                Correo Electrónico
            </label>
            <div class="relative rounded-lg shadow-sm">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                     <i class="bi bi-envelope text-slate-400"></i>
                </div>
                <input 
                    type="email" 
                    name="correo" 
                    id="correo" 
                    class="block w-full pl-10 pr-3 py-3 sm:text-sm border-gray-200 rounded-lg focus:ring-medical-500 focus:border-medical-500 @error('correo') border-red-300 text-red-900 placeholder-red-300 focus:ring-red-500 focus:border-red-500 @enderror" 
                    placeholder="nombre@ejemplo.com"
                    value="{{ old('correo') }}"
                    required
                    autofocus
                >
            </div>
             @error('correo')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Password -->
        <div>
            <div class="flex items-center justify-between mb-1">
                <label for="password" class="block text-sm font-medium text-slate-700">
                    Contraseña
                </label>
                <a href="{{ route('recovery') }}" class="text-sm font-medium text-slate-500 hover:text-slate-900">
                    ¿Olvidaste tu contraseña?
                </a>
            </div>
            
            <div class="relative rounded-lg shadow-sm">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                     <i class="bi bi-lock text-slate-400"></i>
                </div>
                <input 
                    type="password" 
                    name="password" 
                    id="password" 
                    class="block w-full pl-10 pr-10 py-3 sm:text-sm border-gray-200 rounded-lg focus:ring-medical-500 focus:border-medical-500" 
                    placeholder="••••••••"
                    required
                >
                <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                    <button type="button" onclick="togglePassword()" class="text-slate-400 hover:text-slate-600 focus:outline-none">
                         <i class="bi bi-eye" id="toggleIcon"></i>
                    </button>
                </div>
            </div>
            @error('password')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div class="flex items-center justify-between">
        <div class="flex items-center">
            <input 
                id="remember" 
                name="remember" 
                type="checkbox" 
                class="h-4 w-4 text-medical-600 focus:ring-medical-500 border-gray-300 rounded"
            >
            <label for="remember" class="ml-2 block text-sm text-slate-600">
                Recordarme
            </label>
        </div>
    </div>

    <div>
        <button 
            type="submit" 
            id="submitBtn"
            class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white transition-all {{ $theme['btn'] }} focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-medical-500"
        >
            <i class="bi bi-box-arrow-in-right mr-2"></i>
            Iniciar Sesión
        </button>
    </div>
</form>

@push('scripts')
<script>
    // Global scope for onclick handlers
    function togglePassword() {
        const passwordInput = document.getElementById('password');
        const toggleIcon = document.getElementById('toggleIcon');
        
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            toggleIcon.classList.remove('bi-eye');
            toggleIcon.classList.add('bi-eye-slash');
        } else {
            passwordInput.type = 'password';
            toggleIcon.classList.remove('bi-eye-slash');
            toggleIcon.classList.add('bi-eye');
        }
    }
</script>

<script type="module">
import { validateEmail } from '{{ asset("js/validators.js") }}';
import { showToast, shakeElement, toggleSubmitButton } from '{{ asset("js/alerts.js") }}';

const emailInput = document.getElementById('correo');
if(emailInput) {
    emailInput.addEventListener('blur', () => {
        const email = emailInput.value;
        if (email.length > 0) {
            const result = validateEmail(email);
            if(!result.valid) {
                emailInput.classList.add('border-red-300', 'text-red-900', 'focus:ring-red-500', 'focus:border-red-500');
            } else {
                 emailInput.classList.remove('border-red-300', 'text-red-900', 'focus:ring-red-500', 'focus:border-red-500');
            }
        }
    });

    emailInput.addEventListener('input', () => {
         emailInput.classList.remove('border-red-300', 'text-red-900', 'focus:ring-red-500', 'focus:border-red-500');
    });
}

const loginForm = document.getElementById('loginForm');
const submitBtn = document.getElementById('submitBtn');

if(loginForm) {
    loginForm.addEventListener('submit', function(e) {
        // Allow form submission to proceed so server can validate and return specific errors
        // We only block if fields are empty
        const email = emailInput.value;
        const password = document.getElementById('password').value;
        
        if (!email || !password) {
            e.preventDefault();
            if(!email) shakeElement(emailInput);
            if(!password) shakeElement(document.getElementById('password'));
            return;
        }

        toggleSubmitButton(submitBtn, true, 'Iniciando sesión...');
    });
}

// Success message implies welcome
@if(session('success'))
    showToast('success', '{{ session('success') }}', 5000);
@endif

@if(session('error'))
    showToast('error', '{{ session('error') }}', 10000);
    if(typeof shakeElement === 'function' && loginForm) shakeElement(loginForm);
@endif
</script>
@endpush
@endsection
