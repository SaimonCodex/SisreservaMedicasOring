@extends('layouts.auth')

@php
    $rol = request('rol');
    
    switch($rol) {
        case 'admin':
            $theme = [
                'title' => 'Portal Administrativo',
                'description' => 'Acceso para personal administrativo',
                'gradient' => 'from-purple-600 to-indigo-600',
                'btn' => 'bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700',
                'link' => 'text-purple-600 hover:text-purple-800',
                'icon' => 'bi-shield-lock-fill',
                'iconBg' => 'bg-purple-100 text-purple-600'
            ];
            break;
        case 'medico':
            $theme = [
                'title' => 'Portal Médico',
                'description' => 'Acceso para especialistas de salud',
                'gradient' => 'from-blue-600 to-cyan-600',
                'btn' => 'bg-gradient-to-r from-blue-600 to-cyan-600 hover:from-blue-700 hover:to-cyan-700',
                'link' => 'text-blue-600 hover:text-blue-800',
                'icon' => 'bi-heart-pulse-fill',
                'iconBg' => 'bg-blue-100 text-blue-600'
            ];
            break;
        case 'paciente':
            $theme = [
                'title' => 'Portal Paciente',
                'description' => 'Accede a tus citas y resultados',
                'gradient' => 'from-green-600 to-emerald-600',
                'btn' => 'bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700',
                'link' => 'text-green-600 hover:text-green-800',
                'icon' => 'bi-people-fill',
                'iconBg' => 'bg-green-100 text-green-600'
            ];
            break;
        default:
            $theme = [
                'title' => 'Iniciar Sesión',
                'description' => 'Ingresa tus credenciales para continuar',
                'gradient' => 'from-medical-600 to-blue-600',
                'btn' => 'bg-gradient-to-r from-medical-600 to-blue-600 hover:from-medical-700 hover:to-blue-700',
                'link' => 'text-medical-600 hover:text-medical-800',
                'icon' => 'bi-box-arrow-in-right',
                'iconBg' => 'bg-medical-100 text-medical-600'
            ];
    }
@endphp

@section('title', 'Iniciar Sesión')

@section('auth-content')
<!-- Header Icon -->
<div class="flex justify-center mb-6">
    <div class="w-16 h-16 rounded-2xl {{ $theme['iconBg'] }} flex items-center justify-center shadow-lg transform hover:scale-110 transition-transform duration-300">
        <i class="{{ $theme['icon'] }} text-3xl"></i>
    </div>
</div>

<!-- Title & Description -->
<div class="text-center mb-8">
    <h2 class="text-3xl font-display font-bold text-slate-900 tracking-tight">
        {{ $theme['title'] }}
    </h2>
    <p class="mt-2 text-sm text-slate-500">
        {{ $theme['description'] }}
    </p>
    <p class="mt-1 text-sm text-slate-400">
        ¿No tienes cuenta? <a href="{{ route('register') }}" class="font-semibold {{ $theme['link'] }} hover:underline">Regístrate aquí</a>
    </p>
</div>

<form method="POST" action="{{ route('login') }}" id="loginForm" class="space-y-5">
    @csrf
    <input type="hidden" name="rol" value="{{ $rol }}">

    <!-- Email -->
    <div class="group">
        <label for="correo" class="block text-sm font-semibold text-slate-700 mb-2">
            Correo Electrónico
        </label>
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                <i class="bi bi-envelope text-slate-400 group-focus-within:text-medical-500 transition-colors"></i>
            </div>
            <input 
                type="email" 
                name="correo" 
                id="correo" 
                class="block w-full pl-11 pr-4 py-3.5 text-sm border-2 border-slate-200 rounded-xl focus:ring-2 focus:ring-medical-500/20 focus:border-medical-500 transition-all @error('correo') border-red-300 focus:border-red-500 focus:ring-red-500/20 @enderror" 
                placeholder="tu@email.com"
                value="{{ old('correo') }}"
                required
                autofocus
            >
        </div>
        @error('correo')
            <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
                <i class="bi bi-exclamation-circle-fill"></i>
                {{ $message }}
            </p>
        @enderror
    </div>

    <!-- Password -->
    <div class="group">
        <div class="flex items-center justify-between mb-2">
            <label for="password" class="block text-sm font-semibold text-slate-700">
                Contraseña
            </label>
            <a href="{{ route('recovery') }}" class="text-xs font-medium {{ $theme['link'] }} hover:underline">
                ¿Olvidaste tu contraseña?
            </a>
        </div>
        
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                <i class="bi bi-lock text-slate-400 group-focus-within:text-medical-500 transition-colors"></i>
            </div>
            <input 
                type="password" 
                name="password" 
                id="password" 
                class="block w-full pl-11 pr-12 py-3.5 text-sm border-2 border-slate-200 rounded-xl focus:ring-2 focus:ring-medical-500/20 focus:border-medical-500 transition-all" 
                placeholder="••••••••"
                required
            >
            <div class="absolute inset-y-0 right-0 pr-4 flex items-center">
                <button type="button" onclick="togglePassword()" class="text-slate-400 hover:text-slate-600 focus:outline-none transition-colors">
                    <i class="bi bi-eye" id="toggleIcon"></i>
                </button>
            </div>
        </div>
        @error('password')
            <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
                <i class="bi bi-exclamation-circle-fill"></i>
                {{ $message }}
            </p>
        @enderror
    </div>

    <!-- Remember Me -->
    <div class="flex items-center">
        <input 
            id="remember" 
            name="remember" 
            type="checkbox" 
            class="h-4 w-4 text-medical-600 focus:ring-medical-500 border-slate-300 rounded transition-colors"
        >
        <label for="remember" class="ml-2 block text-sm text-slate-600 select-none">
            Mantener sesión iniciada
        </label>
    </div>

    <!-- Submit Button -->
    <div class="pt-2">
        <button 
            type="submit" 
            id="submitBtn"
            class="group relative w-full flex justify-center items-center py-3.5 px-4 border border-transparent rounded-xl shadow-lg text-sm font-bold text-white {{ $theme['btn'] }} focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-medical-500 transform hover:scale-[1.02] transition-all duration-200"
        >
            <span class="flex items-center gap-2">
                <i class="bi bi-box-arrow-in-right"></i>
                <span>Iniciar Sesión</span>
            </span>
            <i class="bi bi-arrow-right absolute right-4 opacity-0 group-hover:opacity-100 group-hover:translate-x-1 transition-all duration-200"></i>
        </button>
    </div>
</form>

<!-- Divider -->
<div class="relative my-6">
    <div class="absolute inset-0 flex items-center">
        <div class="w-full border-t border-slate-200"></div>
    </div>
    <div class="relative flex justify-center text-xs">
        <span class="px-2 bg-white text-slate-500">Sistema seguro y confiable</span>
    </div>
</div>

<!-- Quick Links -->
<div class="flex items-center justify-center gap-4 text-xs text-slate-500">
    <a href="#" class="hover:text-medical-600 transition-colors flex items-center gap-1">
        <i class="bi bi-question-circle"></i>
        Ayuda
    </a>
    <span>•</span>
    <a href="#" class="hover:text-medical-600 transition-colors flex items-center gap-1">
        <i class="bi bi-shield-check"></i>
        Privacidad
    </a>
</div>

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
const passwordInput = document.getElementById('password');
const loginForm = document.getElementById('loginForm');
const submitBtn = document.getElementById('submitBtn');

// Email validation on blur
if(emailInput) {
    emailInput.addEventListener('blur', () => {
        const email = emailInput.value;
        if (email.length > 0) {
            const result = validateEmail(email);
            if(!result.valid) {
                emailInput.classList.add('border-red-300', 'focus:border-red-500', 'focus:ring-red-500/20');
                emailInput.classList.remove('border-slate-200', 'focus:border-medical-500', 'focus:ring-medical-500/20');
            } else {
                emailInput.classList.remove('border-red-300', 'focus:border-red-500', 'focus:ring-red-500/20');
                emailInput.classList.add('border-slate-200', 'focus:border-medical-500', 'focus:ring-medical-500/20');
            }
        }
    });

    emailInput.addEventListener('input', () => {
        emailInput.classList.remove('border-red-300', 'focus:border-red-500', 'focus:ring-red-500/20');
        emailInput.classList.add('border-slate-200', 'focus:border-medical-500', 'focus:ring-medical-500/20');
    });
}

// Form submission
if(loginForm) {
    loginForm.addEventListener('submit', function(e) {
        const email = emailInput.value;
        const password = passwordInput.value;
        
        if (!email || !password) {
            e.preventDefault();
            if(!email) {
                shakeElement(emailInput);
                showToast('warning', 'Por favor ingresa tu correo electrónico');
            }
            if(!password) {
                shakeElement(passwordInput);
                showToast('warning', 'Por favor ingresa tu contraseña');
            }
            return;
        }

        toggleSubmitButton(submitBtn, true, '<i class="bi bi-arrow-repeat animate-spin mr-2"></i>Iniciando sesión...');
    });
}

// Session messages
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
