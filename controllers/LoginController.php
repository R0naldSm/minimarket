<?php
/**
 * LoginController
 * Gestiona la autenticación y sesiones de usuarios
 */

// Iniciar sesión solo si no está activa
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../models/Usuario.php';

class LoginController {
    private $usuarioModel;
    
    /**
     * Constructor
     */
    public function __construct() {
        $this->usuarioModel = new Usuario();
    }
    
    /**
     * Muestra el formulario de login
     */
    public function mostrarLogin() {
        // Si ya hay sesión activa, redirigir a clientes
        if ($this->verificarSesion()) {
            header('Location: ' . APP_URL . 'index.php?controller=cliente&action=listar');
            exit;
        }
        
        require_once __DIR__ . '/../views/login.php';
    }
    
    /**
     * Procesa el login
     */
    public function autenticar() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . APP_URL . 'index.php');
            exit;
        }
        
        // Obtener datos del formulario
        $str_usuario = trim($_POST['str_usuario'] ?? '');
        $str_password = $_POST['str_password'] ?? '';
        
        // Validar campos vacíos
        if (empty($str_usuario) || empty($str_password)) {
            $_SESSION['error'] = 'Por favor complete todos los campos';
            header('Location: ' . APP_URL . 'index.php');
            exit;
        }
        
        // Validar credenciales
        $arr_usuario = $this->usuarioModel->validarUsuario($str_usuario, $str_password);
        
        if ($arr_usuario) {
            // Credenciales correctas - Crear sesión
            $this->iniciarSesion($arr_usuario);
            
            // Redirigir al dashboard
            header('Location: ' . APP_URL . 'index.php?controller=cliente&action=listar');
            exit;
        } else {
            // Credenciales incorrectas
            $_SESSION['error'] = 'Usuario o contraseña incorrectos';
            header('Location: ' . APP_URL . 'index.php');
            exit;
        }
    }
    
    /**
     * Inicia la sesión del usuario
     * @param array $arr_usuario Datos del usuario
     */
    private function iniciarSesion($arr_usuario) {
        $_SESSION['usuario_id'] = $arr_usuario['id_usuario'];
        $_SESSION['usuario_nombre'] = $arr_usuario['str_nombre_usuario'];
        $_SESSION['usuario_nombre_completo'] = $arr_usuario['str_nombre_completo'];
        $_SESSION['usuario_rol'] = $arr_usuario['rol'] ?? 'empleado';
        $_SESSION['sesion_iniciada'] = time();
        $_SESSION['ultima_actividad'] = time();
        
        // Regenerar ID de sesión por seguridad
        session_regenerate_id(true);
    }
    
    /**
     * Verifica si hay una sesión activa
     * @return bool
     */
    public function verificarSesion() {
        // Verificar si existe la sesión
        if (!isset($_SESSION['usuario_id'])) {
            return false;
        }
        
        // Verificar timeout de sesión
        if (isset($_SESSION['ultima_actividad'])) {
            $int_tiempo_inactivo = time() - $_SESSION['ultima_actividad'];
            
            if ($int_tiempo_inactivo > SESSION_TIMEOUT) {
                $this->cerrarSesion();
                $_SESSION['error'] = 'Su sesión ha expirado por inactividad';
                return false;
            }
        }
        
        // Actualizar última actividad
        $_SESSION['ultima_actividad'] = time();
        return true;
    }
    
    /**
     * Cierra la sesión del usuario
     */
    public function cerrarSesion() {
        // Limpiar todas las variables de sesión
        $_SESSION = array();
        
        // Destruir la cookie de sesión
        if (isset($_COOKIE[session_name()])) {
            setcookie(session_name(), '', time() - 3600, '/');
        }
        
        // Destruir la sesión
        session_destroy();
        
        // Redirigir al login
        header('Location: ' . APP_URL . 'index.php');
        exit;
    }
    
    /**
     * Verifica si el usuario tiene un rol específico
     * @param string $str_rol Rol a verificar
     * @return bool
     */
    public static function tieneRol($str_rol) {
        return isset($_SESSION['usuario_rol']) && $_SESSION['usuario_rol'] === $str_rol;
    }
    
    /**
     * Obtiene el ID del usuario en sesión
     * @return int|null
     */
    public static function obtenerUsuarioId() {
        return $_SESSION['usuario_id'] ?? null;
    }
    
    /**
     * Obtiene el nombre completo del usuario en sesión
     * @return string|null
     */
    public static function obtenerUsuarioNombre() {
        return $_SESSION['usuario_nombre_completo'] ?? null;
    }
    
    /**
     * Middleware para proteger rutas
     * Redirige al login si no hay sesión
     */
    public static function requerirAutenticacion() {
        if (!isset($_SESSION['usuario_id'])) {
            $_SESSION['error'] = 'Debe iniciar sesión para acceder';
            header('Location: ' . APP_URL . 'index.php');
            exit;
        }
        
        // Verificar timeout
        if (isset($_SESSION['ultima_actividad'])) {
            $int_tiempo_inactivo = time() - $_SESSION['ultima_actividad'];
            
            if ($int_tiempo_inactivo > SESSION_TIMEOUT) {
                session_destroy();
                $_SESSION['error'] = 'Su sesión ha expirado';
                header('Location: ' . APP_URL . 'index.php');
                exit;
            }
        }
        
        $_SESSION['ultima_actividad'] = time();
    }
}
?>