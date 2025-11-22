/**
 * main.js - Scripts Principales
 * Minimarket "Bendición de Dios"
 */

// Esperar a que el DOM esté completamente cargado
document.addEventListener('DOMContentLoaded', function() {
    
    // ============================================
    // Auto-ocultar alertas después de 5 segundos
    // ============================================
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.animation = 'slideUp 0.3s ease';
            setTimeout(() => {
                alert.remove();
            }, 300);
        }, 5000);
    });
    
    // ============================================
    // Validación de formulario de cliente
    // ============================================
    const formCliente = document.getElementById('formCliente');
    if (formCliente) {
        formCliente.addEventListener('submit', function(e) {
            const cedula = document.getElementById('str_cedula');
            const nombre = document.getElementById('str_nombre');
            const apellido = document.getElementById('str_apellido');
            const email = document.getElementById('str_email');
            const telefono = document.getElementById('str_telefono');
            
            let errores = [];
            
            // Validar cédula
            if (cedula.value.trim() === '') {
                errores.push('La cédula es obligatoria');
            } else if (!validarCedulaEcuador(cedula.value)) {
                errores.push('La cédula ingresada no es válida');
            }
            
            // Validar nombre
            if (nombre.value.trim() === '') {
                errores.push('El nombre es obligatorio');
            } else if (nombre.value.trim().length < 2) {
                errores.push('El nombre debe tener al menos 2 caracteres');
            }
            
            // Validar apellido
            if (apellido.value.trim() === '') {
                errores.push('El apellido es obligatorio');
            } else if (apellido.value.trim().length < 2) {
                errores.push('El apellido debe tener al menos 2 caracteres');
            }
            
            // Validar email (si se proporciona)
            if (email.value.trim() !== '' && !validarEmail(email.value)) {
                errores.push('El email no tiene un formato válido');
            }
            
            // Validar teléfono (si se proporciona)
            if (telefono.value.trim() !== '' && !validarTelefono(telefono.value)) {
                errores.push('El teléfono no tiene un formato válido');
            }
            
            // Si hay errores, prevenir envío y mostrar
            if (errores.length > 0) {
                e.preventDefault();
                mostrarErrores(errores);
            }
        });
    }
    
    // ============================================
    // Validación en tiempo real de cédula
    // ============================================
    const inputCedula = document.getElementById('str_cedula');
    if (inputCedula) {
        inputCedula.addEventListener('blur', function() {
            const valor = this.value.trim();
            if (valor !== '' && !validarCedulaEcuador(valor)) {
                this.style.borderColor = '#F44336';
                mostrarMensajeValidacion(this, 'Cédula inválida', 'error');
            } else {
                this.style.borderColor = '#4CAF50';
                ocultarMensajeValidacion(this);
            }
        });
        
        // Solo permitir números
        inputCedula.addEventListener('input', function() {
            this.value = this.value.replace(/[^0-9]/g, '');
        });
    }
    
    // ============================================
    // Validación en tiempo real de email
    // ============================================
    const inputEmail = document.getElementById('str_email');
    if (inputEmail) {
        inputEmail.addEventListener('blur', function() {
            const valor = this.value.trim();
            if (valor !== '' && !validarEmail(valor)) {
                this.style.borderColor = '#F44336';
                mostrarMensajeValidacion(this, 'Email inválido', 'error');
            } else if (valor !== '') {
                this.style.borderColor = '#4CAF50';
                ocultarMensajeValidacion(this);
            }
        });
    }
    
    // ============================================
    // Autocompletar mayúscula primera letra
    // ============================================
    const camposTexto = document.querySelectorAll('#str_nombre, #str_apellido');
    camposTexto.forEach(campo => {
        campo.addEventListener('blur', function() {
            this.value = capitalizarPalabras(this.value);
        });
    });
    
    // ============================================
    // Confirmar eliminación con tecla Enter
    // ============================================
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Enter') {
            const deleteLinks = document.querySelectorAll('a.btn-delete');
            deleteLinks.forEach(link => {
                if (document.activeElement === link) {
                    e.preventDefault();
                    link.click();
                }
            });
        }
    });
    
    // ============================================
    // Búsqueda con tecla Enter
    // ============================================
    const searchForm = document.querySelector('.search-form');
    if (searchForm) {
        const searchInput = searchForm.querySelector('input[name="q"]');
        if (searchInput) {
            searchInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    searchForm.submit();
                }
            });
        }
    }
    
    // ============================================
    // Contador de caracteres para textarea
    // ============================================
    const textareas = document.querySelectorAll('textarea[maxlength]');
    textareas.forEach(textarea => {
        const maxLength = textarea.getAttribute('maxlength');
        const counter = document.createElement('small');
        counter.className = 'form-help';
        counter.style.textAlign = 'right';
        counter.style.display = 'block';
        textarea.parentNode.appendChild(counter);
        
        const updateCounter = () => {
            const remaining = maxLength - textarea.value.length;
            counter.textContent = `${remaining} caracteres restantes`;
            counter.style.color = remaining < 50 ? '#F44336' : '#999999';
        };
        
        textarea.addEventListener('input', updateCounter);
        updateCounter();
    });
});

// ============================================
// FUNCIONES DE VALIDACIÓN
// ============================================

/**
 * Valida cédula ecuatoriana (10 dígitos)
 * @param {string} cedula - Cédula a validar
 * @returns {boolean}
 */
function validarCedulaEcuador(cedula) {
    // Debe tener 10 o 13 dígitos
    if (!/^\d{10}$|^\d{13}$/.test(cedula)) {
        return false;
    }
    
    // Si es RUC (13 dígitos), es válido
    if (cedula.length === 13) {
        return true;
    }
    
    // Validación de cédula ecuatoriana (10 dígitos)
    const digitos = cedula.split('').map(Number);
    const provincia = parseInt(cedula.substring(0, 2));
    
    if (provincia < 1 || provincia > 24) {
        return false;
    }
    
    let suma = 0;
    for (let i = 0; i < 9; i++) {
        let digito = digitos[i];
        if (i % 2 === 0) {
            digito *= 2;
            if (digito > 9) {
                digito -= 9;
            }
        }
        suma += digito;
    }
    
    const verificador = (10 - (suma % 10)) % 10;
    return verificador === digitos[9];
}

/**
 * Valida formato de email
 * @param {string} email - Email a validar
 * @returns {boolean}
 */
function validarEmail(email) {
    const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return regex.test(email);
}

/**
 * Valida formato de teléfono
 * @param {string} telefono - Teléfono a validar
 * @returns {boolean}
 */
function validarTelefono(telefono) {
    const regex = /^[0-9+\-\(\) ]{7,15}$/;
    return regex.test(telefono);
}

/**
 * Capitaliza la primera letra de cada palabra
 * @param {string} texto - Texto a capitalizar
 * @returns {string}
 */
function capitalizarPalabras(texto) {
    return texto.toLowerCase().replace(/\b\w/g, l => l.toUpperCase());
}

/**
 * Muestra mensajes de error
 * @param {Array} errores - Array de mensajes de error
 */
function mostrarErrores(errores) {
    // Crear elemento de alerta
    const alert = document.createElement('div');
    alert.className = 'alert alert-error';
    alert.innerHTML = `
        <svg width="20" height="20" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
        </svg>
        <span>${errores.join('<br>')}</span>
    `;
    
    // Insertar al inicio del contenedor principal
    const mainContainer = document.querySelector('.main-container');
    if (mainContainer) {
        mainContainer.insertBefore(alert, mainContainer.firstChild);
        
        // Hacer scroll hacia la alerta
        alert.scrollIntoView({ behavior: 'smooth', block: 'start' });
        
        // Auto-ocultar después de 5 segundos
        setTimeout(() => {
            alert.style.animation = 'slideUp 0.3s ease';
            setTimeout(() => {
                alert.remove();
            }, 300);
        }, 5000);
    }
}

/**
 * Muestra mensaje de validación debajo de un campo
 * @param {HTMLElement} elemento - Campo de formulario
 * @param {string} mensaje - Mensaje a mostrar
 * @param {string} tipo - Tipo de mensaje (error, success)
 */
function mostrarMensajeValidacion(elemento, mensaje, tipo = 'error') {
    // Remover mensaje existente
    ocultarMensajeValidacion(elemento);
    
    // Crear nuevo mensaje
    const mensajeEl = document.createElement('small');
    mensajeEl.className = `form-help validation-message validation-${tipo}`;
    mensajeEl.textContent = mensaje;
    mensajeEl.style.color = tipo === 'error' ? '#F44336' : '#4CAF50';
    mensajeEl.style.marginTop = '4px';
    
    // Insertar después del elemento
    elemento.parentNode.appendChild(mensajeEl);
}

/**
 * Oculta mensaje de validación
 * @param {HTMLElement} elemento - Campo de formulario
 */
function ocultarMensajeValidacion(elemento) {
    const mensajes = elemento.parentNode.querySelectorAll('.validation-message');
    mensajes.forEach(mensaje => mensaje.remove());
}

// ============================================
// Animación de slideUp para las alertas
// ============================================
const style = document.createElement('style');
style.textContent = `
    @keyframes slideUp {
        from {
            opacity: 1;
            transform: translateY(0);
        }
        to {
            opacity: 0;
            transform: translateY(-10px);
        }
    }
`;
document.head.appendChild(style);

// ============================================
// Prevenir envío múltiple de formularios
// ============================================
document.querySelectorAll('form').forEach(form => {
    form.addEventListener('submit', function() {
        const submitButton = this.querySelector('button[type="submit"]');
        if (submitButton) {
            submitButton.disabled = true;
            submitButton.style.opacity = '0.6';
            submitButton.innerHTML = `
                <svg width="20" height="20" viewBox="0 0 20 20" fill="currentColor" style="animation: spin 1s linear infinite;">
                    <path fill-rule="evenodd" d="M4 2a2 2 0 00-2 2v11a2 2 0 002 2h12a2 2 0 002-2V4a2 2 0 00-2-2H4zm0 2h12v11H4V4z" clip-rule="evenodd"/>
                </svg>
                Procesando...
            `;
            
            // Re-habilitar después de 3 segundos (por si hay error de validación)
            setTimeout(() => {
                submitButton.disabled = false;
                submitButton.style.opacity = '1';
            }, 3000);
        }
    });
});

// Animación de spin
const spinStyle = document.createElement('style');
spinStyle.textContent = `
    @keyframes spin {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
`;
document.head.appendChild(spinStyle);

console.log('✅ Minimarket "Bendición de Dios" - Scripts cargados correctamente');