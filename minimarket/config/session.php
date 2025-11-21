<?php
/**
 * Helper de Sesión
 * Centraliza el manejo de sesiones en toda la aplicación
 */

/**
 * Inicia la sesión de forma segura
 * Solo inicia si no hay una sesión activa
 */
function iniciarSesionSegura() {
    if (session_status() === PHP_SESSION_NONE) {
        // Configurar parámetros de sesión
        ini_set('session.cookie_httponly', 1);
        ini_set('session.use_only_cookies', 1);
        ini_set('session.cookie_secure', 0); // Cambiar a 1 si usas HTTPS
        
        session_start();
    }
}

/**
 * Verifica si hay una sesión activa
 * @return bool
 */
function haySessionActiva() {
    return session_status() === PHP_SESSION_ACTIVE;
}

/**
 * Destruye la sesión de forma segura
 */
function destruirSesionSegura() {
    if (session_status() === PHP_SESSION_ACTIVE) {
        // Limpiar todas las variables de sesión
        $_SESSION = array();
        
        // Destruir la cookie de sesión
        if (isset($_COOKIE[session_name()])) {
            setcookie(session_name(), '', time() - 3600, '/');
        }
        
        // Destruir la sesión
        session_destroy();
    }
}

// Iniciar sesión automáticamente al incluir este archivo
iniciarSesionSegura();
?>