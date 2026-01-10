/**
 * Sistema de Validaciones en Tiempo Real
 * Para vistas de autenticación del Sistema Médico
 */

// Validar Email
export function validateEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    const valid = emailRegex.test(email);

    return {
        valid,
        message: valid ? 'Email válido' : 'Formato de email inválido'
    };
}

// Validar Cédula Venezolana (V/E/P-XXXXXXXX)
export function validateCedula(cedula) {
    // Remover espacios y guiones
    let cleaned = cedula.replace(/[\s-]/g, '').toUpperCase();

    // Verificar formato
    const cedulaRegex = /^[VEP]\d{6,8}$/;
    const valid = cedulaRegex.test(cleaned);

    // Auto-formatear
    if (cleaned.length > 1) {
        const prefix = cleaned[0];
        const number = cleaned.substring(1);
        cleaned = `${prefix}-${number}`;
    }

    return {
        valid,
        formatted: cleaned,
        message: valid ? 'Cédula válida' : 'Formato: V-12345678, E-12345678 o P-12345678'
    };
}

// Validar Teléfono Venezolano (04XX-XXXXXXX)
export function validatePhone(phone) {
    // Remover espacios y guiones
    let cleaned = phone.replace(/[\s-]/g, '');

    // Verificar que solo contenga números
    if (!/^\d+$/.test(cleaned)) {
        return {
            valid: false,
            formatted: phone,
            message: 'Solo números permitidos'
        };
    }

    // Auto-formatear
    if (cleaned.length >= 4) {
        cleaned = cleaned.substring(0, 4) + '-' + cleaned.substring(4, 11);
    }

    // Validar formato completo
    const phoneRegex = /^04\d{2}-\d{7}$/;
    const valid = phoneRegex.test(cleaned);

    return {
        valid,
        formatted: cleaned,
        message: valid ? 'Teléfono válido' : 'Formato: 04XX-XXXXXXX'
    };
}

// Validar solo letras y espacios (para nombres/apellidos)
export function validateText(text, type = 'name') {
    const nameRegex = /^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s]+$/;
    const valid = nameRegex.test(text);

    return {
        valid,
        message: valid ? `${type} válido` : `Solo se permiten letras y espacios`
    };
}

// Validar Contraseña y calcular fortaleza
export function validatePassword(password) {
    const requirements = {
        minLength: password.length >= 8,
        hasUpperCase: /[A-Z]/.test(password),
        hasLowerCase: /[a-z]/.test(password),
        hasNumber: /\d/.test(password),
        hasSpecial: /[!@#$%^&*(),.?":{}|<>]/.test(password)
    };

    // Calcular score (0-4)
    let score = 0;
    if (requirements.minLength) score++;
    if (requirements.hasUpperCase) score++;
    if (requirements.hasNumber) score++;
    if (requirements.hasSpecial) score++;

    // Determinar nivel
    let strength = 'Muy Débil';
    let color = 'red';

    if (score === 1) {
        strength = 'Débil';
        color = 'orange';
    } else if (score === 2) {
        strength = 'Regular';
        color = 'yellow';
    } else if (score === 3) {
        strength = 'Buena';
        color = 'lightgreen';
    } else if (score === 4) {
        strength = 'Fuerte';
        color = 'green';
    }

    return {
        score,
        strength,
        color,
        requirements,
        valid: score >= 3,
        message: strength
    };
}

// Validar edad (fecha de nacimiento)
export function validateAge(birthdate, minAge = 18, maxAge = 120) {
    const today = new Date();
    const birth = new Date(birthdate);

    // Calcular edad
    let age = today.getFullYear() - birth.getFullYear();
    const monthDiff = today.getMonth() - birth.getMonth();

    if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birth.getDate())) {
        age--;
    }

    const valid = age >= minAge && age <= maxAge;

    let message = '';
    if (age < minAge) {
        message = `Debes tener al menos ${minAge} años`;
    } else if (age > maxAge) {
        message = 'Fecha de nacimiento inválida';
    } else {
        message = `Edad válida: ${age} años`;
    }

    return {
        valid,
        age,
        message
    };
}

// Validar que las contraseñas coincidan
export function validatePasswordMatch(password, confirmation) {
    const valid = password === confirmation && password.length > 0;

    return {
        valid,
        message: valid ? 'Las contraseñas coinciden' : 'Las contraseñas no coinciden'
    };
}

// Función para prevenir caracteres no válidos en tiempo real
export function preventInvalidInput(input, validationType) {
    input.addEventListener('keypress', (e) => {
        const char = String.fromCharCode(e.charCode);

        switch (validationType) {
            case 'cedula':
                // Solo permitir V, E, P al inicio, luego solo números y guión
                const currentValue = input.value;
                if (currentValue.length === 0) {
                    if (!/[VEPvep]/.test(char)) {
                        e.preventDefault();
                    }
                } else if (!/[\d-]/.test(char)) {
                    e.preventDefault();
                }
                break;

            case 'phone':
                // Solo números y guión
                if (!/[\d-]/.test(char)) {
                    e.preventDefault();
                }
                break;

            case 'text':
                // Solo letras, espacios y acentos
                if (!/[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s]/.test(char)) {
                    e.preventDefault();
                }
                break;

            case 'numbers':
                // Solo números
                if (!/\d/.test(char)) {
                    e.preventDefault();
                }
                break;
        }
    });
}

// Auto-formatear mientras escribe
export function autoFormat(input, formatType) {
    input.addEventListener('input', (e) => {
        let value = input.value;

        switch (formatType) {
            case 'cedula':
                const cedulaResult = validateCedula(value);
                if (value.length > 0) {
                    input.value = cedulaResult.formatted;
                }
                break;

            case 'phone':
                const phoneResult = validatePhone(value);
                if (value.length > 0) {
                    input.value = phoneResult.formatted;
                }
                break;
        }
    });
}

// Mostrar feedback visual en el campo
export function showFieldFeedback(input, result) {
    // Remover clases anteriores
    input.classList.remove('input-success', 'input-error', 'input-warning');

    // Remover clases de ring (Tailwind)
    input.classList.remove('ring-2', 'ring-red-500', 'ring-green-500', 'border-red-500', 'border-green-500');

    // Encontrar o crear elemento de mensaje
    let feedbackElement = input.parentElement.querySelector('.field-feedback');

    if (!feedbackElement) {
        feedbackElement = document.createElement('p');
        // Asegurarse de que esté fuera del div relativo si existe
        if (input.parentElement.classList.contains('relative')) {
            input.parentElement.parentElement.appendChild(feedbackElement);
        } else {
            input.parentElement.appendChild(feedbackElement);
        }
        feedbackElement.className = 'field-feedback text-xs mt-1 flex items-center gap-1';
    }

    // Aplicar estilos según resultado
    if (result.valid) {
        input.classList.add('border-green-500', 'focus:ring-green-500');
        feedbackElement.className = 'field-feedback text-xs mt-1 flex items-center gap-1 text-green-600';
        feedbackElement.innerHTML = `<i class="bi bi-check-circle-fill"></i> ${result.message}`;
    } else if (input.value.length > 0) {
        input.classList.add('border-red-500', 'focus:ring-red-500');
        feedbackElement.className = 'field-feedback text-xs mt-1 flex items-center gap-1 text-red-600';
        feedbackElement.innerHTML = `<i class="bi bi-exclamation-circle-fill"></i> ${result.message}`;
    } else {
        feedbackElement.innerHTML = '';
        input.classList.remove('border-green-500', 'border-red-500', 'focus:ring-green-500', 'focus:ring-red-500');
    }
}
