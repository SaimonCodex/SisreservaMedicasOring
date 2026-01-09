/**
 * Utilidades para Autenticación
 * Funciones compartidas para las vistas de auth
 */

import { validatePassword, showFieldFeedback } from './validators.js';

/**
 * Mostrar indicador de fortaleza de contraseña
 * @param {HTMLInputElement} passwordInput - Input de contraseña
 * @param {HTMLElement} indicatorContainer - Contenedor del indicador
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
        const colors = {
            0: 'bg-danger-500',
            1: 'bg-warning-500',
            2: 'bg-yellow-500',
            3: 'bg-success-400',
            4: 'bg-success-600'
        };

        let barsHTML = '';
        for (let i = 0; i < 4; i++) {
            const active = i < result.score;
            barsHTML += `<div class="h-2 flex-1 rounded-full ${active ? colors[result.score] : 'bg-gray-200'} transition-all duration-300"></div>`;
        }

        // Crear lista de requisitos
        const reqHTML = `
            <div class="flex items-center gap-2 text-xs ${result.requirements.minLength ? 'text-success-600' : 'text-gray-400'}">
                <i class="bi ${result.requirements.minLength ? 'bi-check-circle-fill' : 'bi-circle'}"></i>
                Mínimo 8 caracteres
            </div>
            <div class="flex items-center gap-2 text-xs ${result.requirements.hasUpperCase ? 'text-success-600' : 'text-gray-400'}">
                <i class="bi ${result.requirements.hasUpperCase ? 'bi-check-circle-fill' : 'bi-circle'}"></i>
                Al menos una mayúscula
            </div>
            <div class="flex items-center gap-2 text-xs ${result.requirements.hasNumber ? 'text-success-600' : 'text-gray-400'}">
                <i class="bi ${result.requirements.hasNumber ? 'bi-check-circle-fill' : 'bi-circle'}"></i>
                Al menos un número
            </div>
            <div class="flex items-center gap-2 text-xs ${result.requirements.hasSpecial ? 'text-success-600' : 'text-gray-400'}">
                <i class="bi ${result.requirements.hasSpecial ? 'bi-check-circle-fill' : 'bi-circle'}"></i>
                Al menos un carácter especial
            </div>
        `;

        indicatorContainer.innerHTML = `
            <div class="flex gap-1.5 mb-2">${barsHTML}</div>
            <p class="text-sm font-medium mb-2" style="color: ${result.color}">
                Fortaleza: ${result.strength}
            </p>
            <div class="grid grid-cols-2 gap-2">
                ${reqHTML}
            </div>
        `;
    });
}

/**
 * Toggle visibilidad de contraseña
 * @param {HTMLInputElement} passwordInput - Input de contraseña
 * @param {HTMLElement} toggleButton - Botón toggle
 */
export function initPasswordToggle(passwordInput, toggleButton) {
    if (!toggleButton) return;

    const icon = toggleButton.querySelector('i') || toggleButton;

    toggleButton.addEventListener('click', () => {
        const isPassword = passwordInput.type === 'password';

        passwordInput.type = isPassword ? 'text' : 'password';
        icon.className = isPassword ? 'bi bi-eye-slash' : 'bi bi-eye';
    });
}

/**
 * Inicializar wizard multi-paso para registro
 * @param {HTMLFormElement} form - Formulario
 * @param {Array} steps - Array de selectores de pasos
 */
export function initMultiStepForm(form, steps) {
    let currentStep = 0;
    const stepElements = steps.map(selector => form.querySelector(selector));
    const totalSteps = stepElements.length;

    // Crear navegación
    const nav = document.createElement('div');
    nav.className = 'flex justify-between items-center mb-8';
    nav.innerHTML = `
        <button type="button" id="prevStep" class="btn btn-outline" style="display: none;">
            <i class="bi bi-arrow-left mr-2"></i>
            Anterior
        </button>
        <div class="flex-1 mx-6">
            <div class="flex items-center justify-center gap-2">
                ${stepElements.map((_, i) => `
                    <div class="step-indicator flex-1 max-w-32">
                        <div class="h-2 rounded-full bg-gray-200 overflow-hidden">
                            <div class="h-full bg-medical-600 transition-all duration-300" style="width: ${i === 0 ? '100%' : '0%'}" data-step="${i}"></div>
                        </div>
                    </div>
                `).join('')}
            </div>
            <div class="text-center mt-2">
                <p class="text-sm text-gray-600">Paso <span id="currentStepNum">1</span> de ${totalSteps}</p>
            </div>
        </div>
        <button type="button" id="nextStep" class="btn btn-primary">
            Siguiente
            <i class="bi bi-arrow-right ml-2"></i>
        </button>
        <button type="submit" id="submitBtn" class="btn btn-success" style="display: none;">
            <i class="bi bi-check-lg mr-2"></i>
            Crear Cuenta
        </button>
    `;

    form.insertBefore(nav, form.firstChild);

    const prevBtn = nav.querySelector('#prevStep');
    const nextBtn = nav.querySelector('#nextStep');
    const submitBtn = nav.querySelector('#submitBtn');
    const stepNum = nav.querySelector('#currentStepNum');
    const indicators = nav.querySelectorAll('.step-indicator div div');

    function showStep(index) {
        stepElements.forEach((el, i) => {
            el.style.display = i === index ? 'block' : 'none';
        });

        // Update indicators
        indicators.forEach((ind, i) => {
            ind.style.width = i <= index ? '100%' : '0%';
        });

        stepNum.textContent = index + 1;

        // Update buttons
        prevBtn.style.display = index === 0 ? 'none' : 'block';
        nextBtn.style.display = index === totalSteps - 1 ? 'none' : 'block';
        submitBtn.style.display = index === totalSteps - 1 ? 'block' : 'none';

        currentStep = index;
    }

    nextBtn.addEventListener('click', () => {
        if (currentStep < totalSteps - 1) {
            showStep(currentStep + 1);
        }
    });

    prevBtn.addEventListener('click', () => {
        if (currentStep > 0) {
            showStep(currentStep - 1);
        }
    });

    showStep(0);
}

/**
 * System para prevenir duplicación de preguntas de seguridad
 * @param {Array} selectElements - Array de <select> de preguntas
 */
export function initSecurityQuestionsUnique(selectElements) {
    const updateAvailableOptions = () => {
        const selectedValues = selectElements.map(select => select.value).filter(v => v);

        selectElements.forEach((select, index) => {
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

    updateAvailableOptions();
}

/**
 * Countdown redirect
 * @param {number} seconds - Segundos para redirección
 * @param {string} url - URL de redirección
 * @param {HTMLElement} element - Elemento donde mostrar countdown
 */
export function startCountdownRedirect(seconds, url, element) {
    let remaining = seconds;

    const interval = setInterval(() => {
        if (element) {
            element.textContent = `Redirigiendo en ${remaining} segundos...`;
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
 * @param {HTMLButtonElement} button - Botón de submit
 * @param {boolean} disabled - Estado
 * @param {string} loadingText - Texto durante carga
 */
export function toggleSubmitButton(button, disabled, loadingText = 'Procesando...') {
    if (disabled) {
        button.disabled = true;
        button.dataset.originalText = button.innerHTML;
        button.innerHTML = `
            <span class="flex items-center justify-center">
                <i class="bi bi-arrow-repeat animate-spin mr-2"></i>
                ${loadingText}
            </span>
        `;
    } else {
        button.disabled = false;
        button.innerHTML = button.dataset.originalText || button.innerHTML;
    }
}
