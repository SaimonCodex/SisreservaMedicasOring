/**
 * Sistema de Alertas y Notificaciones
 * Toast notifications y modals para el Sistema Médico
 */

// Configuración
const TOAST_DURATION = 5000; // 5 segundos
const TOAST_POSITIONS = {
    'top-right': 'top-4 right-4',
    'top-left': 'top-4 left-4',
    'bottom-right': 'bottom-4 right-4',
    'bottom-left': 'bottom-4 left-4',
    'top-center': 'top-4 left-1/2 -translate-x-1/2'
};

// Crear contenedor de toasts si no existe
function getToastContainer(position = 'top-right') {
    let container = document.getElementById('toast-container');

    if (!container) {
        container = document.createElement('div');
        container.id = 'toast-container';
        container.className = `fixed ${TOAST_POSITIONS[position]} z-50 flex flex-col gap-2 max-w-md pointer-events-none`; // pointer-events-none para no bloquear clicks por debajo
        document.body.appendChild(container);
    }

    return container;
}

// Iconos por tipo
const TOAST_ICONS = {
    success: 'bi-check-circle-fill',
    error: 'bi-x-circle-fill',
    warning: 'bi-exclamation-triangle-fill',
    info: 'bi-info-circle-fill'
};

// Colores por tipo (Usando Tailwind estandard)
const TOAST_COLORS = {
    success: {
        bg: 'bg-green-50',
        border: 'border-green-200',
        icon: 'text-green-600',
        text: 'text-green-900'
    },
    error: {
        bg: 'bg-red-50',
        border: 'border-red-200',
        icon: 'text-red-600',
        text: 'text-red-900'
    },
    warning: {
        bg: 'bg-yellow-50',
        border: 'border-yellow-200',
        icon: 'text-yellow-600',
        text: 'text-yellow-900'
    },
    info: {
        bg: 'bg-blue-50',
        border: 'border-blue-200',
        icon: 'text-blue-600',
        text: 'text-blue-900'
    }
};

/**
 * Mostrar Toast Notification
 */
export function showToast(type, message, duration = TOAST_DURATION, position = 'top-right') {
    const container = getToastContainer(position);
    const colors = TOAST_COLORS[type] || TOAST_COLORS.info;
    const icon = TOAST_ICONS[type] || TOAST_ICONS.info;

    // Crear elemento toast
    const toast = document.createElement('div');
    toast.className = `${colors.bg} ${colors.border} border rounded-xl shadow-lg p-4 flex items-start gap-3 transform transition-all duration-300 ease-out translate-x-10 opacity-0 pointer-events-auto`;

    toast.innerHTML = `
        <i class="bi ${icon} ${colors.icon} text-xl flex-shrink-0 mt-0.5"></i>
        <div class="flex-1 ${colors.text}">
            <p class="font-medium text-sm leading-snug">${message}</p>
        </div>
        <button onclick="this.parentElement.remove()" 
                class="flex-shrink-0 ${colors.icon} hover:opacity-70 transition-opacity">
            <i class="bi bi-x-lg text-sm"></i>
        </button>
    `;

    // Agregar al contenedor
    container.appendChild(toast);

    // Animar entrada
    requestAnimationFrame(() => {
        toast.classList.remove('translate-x-10', 'opacity-0');
    });

    // Auto-remover después de duración
    setTimeout(() => {
        toast.classList.add('opacity-0', 'translate-x-full');
        setTimeout(() => toast.remove(), 300);
    }, duration);

    return toast;
}

/**
 * Mostrar Modal de Confirmación
 */
export function showAlert(title, message, type = 'alert', onConfirm = null, onCancel = null) {
    // Crear overlay
    const overlay = document.createElement('div');
    overlay.className = 'fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-50 flex items-center justify-center p-4 transition-opacity duration-300 opacity-0';

    // Determinar icono y color según tipo
    let iconClass = 'bi-exclamation-triangle text-yellow-500';
    let confirmBtnClass = 'bg-blue-600 hover:bg-blue-700 text-white';

    if (type === 'confirm') {
        iconClass = 'bi-question-circle text-blue-500';
        confirmBtnClass = 'bg-blue-600 hover:bg-blue-700 text-white';
    } else if (type === 'error') {
        iconClass = 'bi-x-circle text-red-500';
        confirmBtnClass = 'bg-red-600 hover:bg-red-700 text-white';
    } else if (type === 'success') {
        iconClass = 'bi-check-circle text-green-500';
        confirmBtnClass = 'bg-green-600 hover:bg-green-700 text-white';
    }

    // Crear modal
    const modal = document.createElement('div');
    modal.className = 'bg-white rounded-2xl shadow-2xl max-w-sm w-full p-6 transform transition-all scale-95 opacity-0';

    modal.innerHTML = `
        <div class="text-center">
            <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-gray-100 mb-4">
                <i class="bi ${iconClass} text-2xl"></i>
            </div>
            <h3 class="text-lg font-bold text-gray-900 mb-2">${title}</h3>
            <p class="text-sm text-gray-500 mb-6">${message}</p>
            
            <div class="flex gap-3 justify-center">
                ${type === 'confirm' ? `
                    <button id="modalCancel" class="px-4 py-2 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-50 font-medium text-sm transition-colors">
                        Cancelar
                    </button>
                ` : ''}
                <button id="modalConfirm" class="px-4 py-2 rounded-lg ${confirmBtnClass} font-medium text-sm transition-colors shadow-sm">
                    ${type === 'confirm' ? 'Confirmar' : 'Entendido'}
                </button>
            </div>
        </div>
    `;

    overlay.appendChild(modal);
    document.body.appendChild(overlay);

    // Animar entrada
    requestAnimationFrame(() => {
        overlay.classList.remove('opacity-0');
        modal.classList.remove('scale-95', 'opacity-0');
    });

    // Event listeners
    const confirmBtn = modal.querySelector('#modalConfirm');
    const cancelBtn = modal.querySelector('#modalCancel');

    const closeModal = () => {
        overlay.classList.add('opacity-0');
        modal.classList.add('scale-95', 'opacity-0');
        setTimeout(() => overlay.remove(), 200);
    };

    confirmBtn.addEventListener('click', () => {
        if (onConfirm) onConfirm();
        closeModal();
    });

    if (cancelBtn) {
        cancelBtn.addEventListener('click', () => {
            if (onCancel) onCancel();
            closeModal();
        });
    }

    // Cerrar al hacer clic fuera
    overlay.addEventListener('click', (e) => {
        if (e.target === overlay) {
            if (onCancel) onCancel();
            closeModal();
        }
    });

    return overlay;
}

/**
 * Mostrar Loading Toast
 */
export function showLoading(message = 'Cargando...') {
    const container = getToastContainer('top-center');

    const toast = document.createElement('div');
    toast.className = 'bg-white border border-gray-200 rounded-full shadow-xl px-6 py-3 flex items-center gap-3 animate-bounce-soft pointer-events-auto';

    toast.innerHTML = `
        <div class="animate-spin text-blue-600">
            <i class="bi bi-arrow-repeat text-xl"></i>
        </div>
        <p class="font-medium text-gray-700 text-sm">${message}</p>
    `;

    container.appendChild(toast);

    return {
        element: toast,
        close: () => {
            toast.classList.add('opacity-0');
            setTimeout(() => toast.remove(), 300);
        }
    };
}

/**
 * Animación de shake para errores
 */
export function shakeElement(element) {
    element.classList.add('animate-pulse'); // Tailwind doesn't have shake by default, using pulse or custom
    // If we want real shake we need custom CSS or style injection. 
    // Using transform based translation manually for a quick shake effect
    const originalTransform = element.style.transform;

    element.style.transition = 'transform 0.1s';
    element.style.transform = 'translateX(-5px)';

    setTimeout(() => {
        element.style.transform = 'translateX(5px)';
        setTimeout(() => {
            element.style.transform = 'translateX(-5px)';
            setTimeout(() => {
                element.style.transform = 'translateX(5px)';
                setTimeout(() => {
                    element.style.transform = originalTransform;
                }, 100);
            }, 100);
        }, 100);
    }, 100);
}

/**
 * Mostrar validación en formulario
 */
export function showFormErrors(form, errors) {
    // Limpiar errores anteriores
    form.querySelectorAll('.form-error').forEach(el => el.remove());
    form.querySelectorAll('.border-red-500').forEach(el => el.classList.remove('border-red-500', 'focus:ring-red-500'));

    // Mostrar nuevos errores
    Object.keys(errors).forEach(fieldName => {
        const input = form.querySelector(`[name="${fieldName}"]`);
        if (input) {
            input.classList.add('border-red-500', 'focus:ring-red-500');

            const errorElement = document.createElement('p');
            errorElement.className = 'form-error text-red-600 text-xs mt-1 flex items-center gap-1';
            errorElement.innerHTML = `<i class="bi bi-exclamation-circle-fill"></i> ${errors[fieldName]}`;

            if (input.parentElement.classList.contains('relative')) {
                input.parentElement.parentElement.appendChild(errorElement);
            } else {
                input.parentElement.appendChild(errorElement);
            }

            // Shake el input
            shakeElement(input);
        }
    });

    // Scroll al primer error
    const firstError = form.querySelector('.border-red-500');
    if (firstError) {
        firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
        firstError.focus();
    }
}

/**
 * Toggle Submit Button State
 * Deshabilita o habilita un botón de submit y cambia su texto
 */
export function toggleSubmitButton(button, disabled = true, loadingText = null) {
    if (disabled) {
        button.disabled = true;
        button.classList.add('opacity-50', 'cursor-not-allowed');
        if (loadingText) {
            button.dataset.originalText = button.innerHTML;
            button.innerHTML = loadingText;
        }
    } else {
        button.disabled = false;
        button.classList.remove('opacity-50', 'cursor-not-allowed');
        if (button.dataset.originalText) {
            button.innerHTML = button.dataset.originalText;
            delete button.dataset.originalText;
        }
    }
}
