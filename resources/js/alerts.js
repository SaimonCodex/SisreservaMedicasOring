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
        container.className = `fixed ${TOAST_POSITIONS[position]} z-50 flex flex-col gap-2 max-w-md`;
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

// Colores por tipo
const TOAST_COLORS = {
    success: {
        bg: 'bg-success-50',
        border: 'border-success-200',
        icon: 'text-success-600',
        text: 'text-success-900'
    },
    error: {
        bg: 'bg-danger-50',
        border: 'border-danger-200',
        icon: 'text-danger-600',
        text: 'text-danger-900'
    },
    warning: {
        bg: 'bg-warning-50',
        border: 'border-warning-200',
        icon: 'text-warning-600',
        text: 'text-warning-900'
    },
    info: {
        bg: 'bg-info-50',
        border: 'border-info-200',
        icon: 'text-info-600',
        text: 'text-info-900'
    }
};

/**
 * Mostrar Toast Notification
 * @param {string} type - success, error, warning, info
 * @param {string} message - Mensaje a mostrar
 * @param {number} duration - Duración en ms (default: 5000)
 * @param {string} position - Posición del toast (default: top-right)
 */
export function showToast(type, message, duration = TOAST_DURATION, position = 'top-right') {
    const container = getToastContainer(position);
    const colors = TOAST_COLORS[type] || TOAST_COLORS.info;
    const icon = TOAST_ICONS[type] || TOAST_ICONS.info;

    // Crear elemento toast
    const toast = document.createElement('div');
    toast.className = `${colors.bg} ${colors.border} border rounded-xl shadow-lg p-4 flex items-start gap-3 transform transition-all duration-300 ease-out animate-slide-in-right`;

    toast.innerHTML = `
        <i class="bi ${icon} ${colors.icon} text-xl flex-shrink-0 mt-0.5"></i>
        <div class="flex-1 ${colors.text}">
            <p class="font-medium text-sm">${message}</p>
        </div>
        <button onclick="this.parentElement.remove()" 
                class="flex-shrink-0 ${colors.icon} hover:opacity-70 transition-opacity">
            <i class="bi bi-x-lg text-sm"></i>
        </button>
    `;

    // Agregar al contenedor
    container.appendChild(toast);

    // Auto-remover después de duración
    setTimeout(() => {
        toast.classList.add('opacity-0', 'translate-x-full');
        setTimeout(() => toast.remove(), 300);
    }, duration);

    return toast;
}

/**
 * Mostrar Modal de Confirmación
 * @param {string} title - Título del modal
 * @param {string} message - Mensaje del modal
 * @param {string} type - confirm, alert, prompt
 * @param {function} onConfirm - Callback si acepta
 * @param {function} onCancel - Callback si cancela
 */
export function showAlert(title, message, type = 'alert', onConfirm = null, onCancel = null) {
    // Crear overlay
    const overlay = document.createElement('div');
    overlay.className = 'fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4 animate-fade-in';

    // Determinar icono y color según tipo
    let iconClass = 'bi-exclamation-triangle text-warning-600';
    let confirmBtnClass = 'btn-primary';

    if (type === 'confirm') {
        iconClass = 'bi-question-circle text-info-600';
        confirmBtnClass = 'btn-primary';
    } else if (type === 'error') {
        iconClass = 'bi-x-circle text-danger-600';
        confirmBtnClass = 'btn-danger';
    } else if (type === 'success') {
        iconClass = 'bi-check-circle text-success-600';
        confirmBtnClass = 'btn-success';
    }

    // Crear modal
    const modal = document.createElement('div');
    modal.className = 'bg-white rounded-2xl shadow-2xl max-w-md w-full p-6 animate-scale-in';

    modal.innerHTML = `
        <div class="text-center">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 mb-4">
                <i class="bi ${iconClass} text-4xl"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-2">${title}</h3>
            <p class="text-gray-600 mb-6">${message}</p>
            
            <div class="flex gap-3 justify-center">
                ${type === 'confirm' ? `
                    <button id="modalCancel" class="btn btn-outline px-6">
                        Cancelar
                    </button>
                ` : ''}
                <button id="modalConfirm" class="btn ${confirmBtnClass} px-6">
                    ${type === 'confirm' ? 'Confirmar' : 'Entendido'}
                </button>
            </div>
        </div>
    `;

    overlay.appendChild(modal);
    document.body.appendChild(overlay);

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
 * @param {string} message - Mensaje de carga
 * @returns {object} Toast element para poder cerrarlo después
 */
export function showLoading(message = 'Cargando...') {
    const container = getToastContainer('top-center');

    const toast = document.createElement('div');
    toast.className = 'bg-white border border-gray-200 rounded-xl shadow-lg p-4 flex items-center gap-3 animate-fade-in';

    toast.innerHTML = `
        <div class="animate-spin">
            <i class="bi bi-arrow-repeat text-medical-600 text-xl"></i>
        </div>
        <p class="font-medium text-gray-900">${message}</p>
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
 * @param {HTMLElement} element - Elemento a animar
 */
export function shakeElement(element) {
    element.classList.add('animate-shake');
    setTimeout(() => {
        element.classList.remove('animate-shake');
    }, 500);
}

/**
 * Mostrar validación en formulario
 * @param {HTMLFormElement} form - Formulario
 * @param {object} errors - Objeto con errores {campo: mensaje}
 */
export function showFormErrors(form, errors) {
    // Limpiar errores anteriores
    form.querySelectorAll('.form-error').forEach(el => el.remove());
    form.querySelectorAll('.input-error').forEach(el => el.classList.remove('input-error'));

    // Mostrar nuevos errores
    Object.keys(errors).forEach(fieldName => {
        const input = form.querySelector(`[name="${fieldName}"]`);
        if (input) {
            input.classList.add('input-error');

            const errorElement = document.createElement('p');
            errorElement.className = 'form-error text-danger-600 text-sm mt-1 flex items-center gap-1';
            errorElement.innerHTML = `<i class="bi bi-exclamation-circle-fill"></i> ${errors[fieldName]}`;

            input.parentElement.appendChild(errorElement);

            // Shake el input
            shakeElement(input);
        }
    });

    // Scroll al primer error
    const firstError = form.querySelector('.input-error');
    if (firstError) {
        firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
        firstError.focus();
    }
}
