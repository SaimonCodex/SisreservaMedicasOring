/**
 * Utilidades para Autenticación
 * Funciones compartidas para las vistas de auth
 */

import { validatePassword } from './validators.js';

/**
 * Mostrar indicador de fortaleza de contraseña
 */
export function initPasswordStrengthIndicator(passwordInput, indicatorContainer) {
    if (!indicatorContainer) {
        // Crear contenedor si no existe
        indicatorContainer = document.createElement('div');
        indicatorContainer.className = 'password-strength-indicator mt-3';
        passwordInput.parentElement.appendChild(indicatorContainer);
    }

    passwordInput.addEventListener('input', () => {
        const password = passwordInput.value;

        if (password.length === 0) {
            indicatorContainer.innerHTML = '';
            return;
        }

        const result = validatePassword(password);

        // Crear barra de fortaleza
        // Usamos colores tailwind
        const colors = {
            0: 'bg-red-500',
            1: 'bg-orange-500',
            2: 'bg-yellow-500',
            3: 'bg-green-400',
            4: 'bg-green-600'
        };

        let barsHTML = '';
        for (let i = 0; i < 4; i++) {
            const active = i < result.score;
            barsHTML += `<div class="h-1.5 flex-1 rounded-full ${active ? colors[result.score] : 'bg-gray-200'} transition-all duration-300"></div>`;
        }

        // Crear lista de requisitos
        const reqHTML = `
            <div class="flex items-center gap-2 text-xs ${result.requirements.minLength ? 'text-green-600' : 'text-gray-400'}">
                <i class="bi ${result.requirements.minLength ? 'bi-check-circle-fill' : 'bi-circle'}"></i>
                Mínimo 8 caracteres
            </div>
            <div class="flex items-center gap-2 text-xs ${result.requirements.hasUpperCase ? 'text-green-600' : 'text-gray-400'}">
                <i class="bi ${result.requirements.hasUpperCase ? 'bi-check-circle-fill' : 'bi-circle'}"></i>
                Al menos una mayúscula
            </div>
            <div class="flex items-center gap-2 text-xs ${result.requirements.hasNumber ? 'text-green-600' : 'text-gray-400'}">
                <i class="bi ${result.requirements.hasNumber ? 'bi-check-circle-fill' : 'bi-circle'}"></i>
                Al menos un número
            </div>
            <div class="flex items-center gap-2 text-xs ${result.requirements.hasSpecial ? 'text-green-600' : 'text-gray-400'}">
                <i class="bi ${result.requirements.hasSpecial ? 'bi-check-circle-fill' : 'bi-circle'}"></i>
                Al menos un carácter especial
            </div>
        `;

        // Color del texto de "Fortaleza"
        const textColors = {
            'red': 'text-red-600',
            'orange': 'text-orange-600',
            'yellow': 'text-yellow-600',
            'lightgreen': 'text-green-500',
            'green': 'text-green-700'
        };

        indicatorContainer.innerHTML = `
            <div class="flex gap-1.5 mb-2">${barsHTML}</div>
            <p class="text-xs font-medium mb-2 ${textColors[result.color] || 'text-gray-500'}">
                Fortaleza: ${result.strength}
            </p>
            <div class="grid grid-cols-2 gap-y-1 gap-x-2">
                ${reqHTML}
            </div>
        `;
    });
}

/**
 * Toggle visibilidad de contraseña
 */
export function initPasswordToggle(passwordInput, toggleButton) {
    if (!toggleButton) return;

    const icon = toggleButton.querySelector('i') || toggleButton;

    toggleButton.addEventListener('click', () => {
        const isPassword = passwordInput.type === 'password';

        passwordInput.type = isPassword ? 'text' : 'password';
        icon.className = isPassword ? 'bi bi-eye-slash text-slate-600' : 'bi bi-eye text-slate-400';
    });
}

/**
 * Inicializar wizard multi-paso para registro
 */
export function initMultiStepForm(form, steps) {
    let currentStep = 0;
    const stepElements = steps.map(selector => form.querySelector(selector));
    const totalSteps = stepElements.length;

    // Crear navegación (Simplified for new design)
    // Se inserta antes del formulario o se asume existencia de indicadores.
    // En el nuevo diseño los indicadores están en el blade.
    // Solo manejamos lógica de mostrar/ocultar y botones.

    // Asumimos botones existentes con IDs específicos si no se crean dinámicamente
    // Pero para ser robusto, busquemos botones dentro del form o creemoslos.

    // NOTA: En register.blade.php ya cree la estructura de botones al final de cada step
    // Así que esta función solo necesita manejar la visibilidad y validación básica.

    // Bind buttons inside steps
    stepElements.forEach((stepEl, index) => {
        const nextBtn = stepEl.querySelector('[data-action="next"]');
        const prevBtn = stepEl.querySelector('[data-action="prev"]');

        if (nextBtn) {
            nextBtn.addEventListener('click', () => {
                // Aquí podria ir validación antes de avanzar
                showStep(index + 1);
            });
        }

        if (prevBtn) {
            prevBtn.addEventListener('click', () => {
                showStep(index - 1);
            });
        }
    });

    const indicators = document.querySelectorAll('.step-indicator'); // Assuming global class or passed in? 
    // Actually, let's keep it simple. The Blade controls the UI, this JS helper might be too opinionated. 
    // I will simplify this to just helper functions or lightweight logic.

    function showStep(index) {
        stepElements.forEach((el, i) => {
            if (i === index) {
                el.classList.remove('hidden');
                // Animation
                el.classList.add('animate-fade-in');
            } else {
                el.classList.add('hidden');
                el.classList.remove('animate-fade-in');
            }
        });

        // Update external indicators if they exist
        updateIndicators(index);

        currentStep = index;
    }

    function updateIndicators(activeIndex) {
        // Buscamos los indicadores por ID generado en Blade
        // step1-indicator, step2-indicator, etc
        for (let i = 0; i < totalSteps; i++) {
            const ind = document.getElementById(`step${i + 1}-indicator`);
            const line = document.getElementById(`step${i + 1}-line`); // if exists

            if (ind) {
                if (i < activeIndex) {
                    // Completed
                    ind.className = "w-8 h-8 rounded-full flex items-center justify-center bg-green-500 text-white font-bold transition-colors duration-300";
                    ind.innerHTML = '<i class="bi bi-check-lg"></i>';
                } else if (i === activeIndex) {
                    // Active
                    ind.className = "w-8 h-8 rounded-full flex items-center justify-center bg-blue-600 text-white font-bold shadow-lg ring-4 ring-blue-100 transition-all duration-300";
                    ind.innerHTML = (i + 1);
                } else {
                    // Pending
                    ind.className = "w-8 h-8 rounded-full flex items-center justify-center bg-slate-100 text-slate-400 font-bold border border-slate-200 transition-colors duration-300";
                    ind.innerHTML = (i + 1);
                }
            }
        }
    }

    // Inicializar
    showStep(0);
}

/**
 * System para prevenir duplicación de preguntas de seguridad
 */
export function initSecurityQuestionsUnique(selectElements) {
    const updateAvailableOptions = () => {
        const selectedValues = Array.from(selectElements).map(select => select.value).filter(v => v);

        selectElements.forEach((select) => {
            const currentValue = select.value;
            const options = select.querySelectorAll('option');

            options.forEach(option => {
                if (option.value === '' || option.value === currentValue) {
                    option.disabled = false;
                } else {
                    option.disabled = selectedValues.includes(option.value);
                }
            });
        });
    };

    selectElements.forEach(select => {
        select.addEventListener('change', updateAvailableOptions);
    });
}

/**
 * Countdown redirect
 */
export function startCountdownRedirect(seconds, url, element) {
    let remaining = seconds;

    const interval = setInterval(() => {
        if (element) {
            element.textContent = remaining;
        }

        remaining--;

        if (remaining < 0) {
            clearInterval(interval);
            window.location.href = url;
        }
    }, 1000);

    return interval;
}

/**
 * Deshabilitar botón de submit durante proceso
 */
export function toggleSubmitButton(button, disabled, loadingText = 'Procesando...') {
    if (!button) return;

    if (disabled) {
        button.disabled = true;
        button.dataset.originalContent = button.innerHTML;
        button.innerHTML = `
            <div class="flex items-center justify-center gap-2">
                <i class="bi bi-arrow-repeat animate-spin"></i>
                <span>${loadingText}</span>
            </div>
        `;
        button.classList.add('opacity-75', 'cursor-not-allowed');
    } else {
        button.disabled = false;
        button.innerHTML = button.dataset.originalContent || button.innerHTML;
        button.classList.remove('opacity-75', 'cursor-not-allowed');
    }
}
