<?php
// controllers/AuthController.php
require_once 'config/Database.php';
require_once 'models/Usuario.php';

class AuthController {
    private $db;
    private $usuario;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->usuario = new Usuario($this->db);
    }

    // Mostrar formulario de login
    public function login() {
        // Si ya hay sesión iniciada, redirigir al home
        if (isset($_SESSION['usuario_id'])) {
            header("Location: index.php?controller=home&action=index");
            exit();
        }
        
        require 'views/auth/login.php';
    }

    // Procesar login
    public function procesarLogin() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $usuario = $_POST['usuario'];
            $password = $_POST['password'];
            
            if ($this->usuario->autenticar($usuario, $password)) {
                // Guardar datos en sesión
                $_SESSION['usuario_id'] = $this->usuario->id;
                $_SESSION['usuario_nombre'] = $this->usuario->nombre;
                $_SESSION['usuario_rol'] = $this->usuario->rol;
                $_SESSION['usuario_username'] = $this->usuario->usuario;
                
                header("Location: index.php?controller=home&action=index&msg=welcome");
                exit();
            } else {
                header("Location: index.php?controller=auth&action=login&error=credentials");
                exit();
            }
        }
    }

    // Cerrar sesión
    public function logout() {
        // Destruir todas las variables de sesión
        $_SESSION = array();
        
        // Destruir la sesión
        session_destroy();
        
        // Redirigir al login
        header("Location: index.php?controller=auth&action=login&msg=logout");
        exit();
    }

    // Verificar si el usuario está autenticado
    public static function estaAutenticado() {
        return isset($_SESSION['usuario_id']);
    }

    // Verificar si el usuario tiene un rol específico
    public static function tieneRol($rol) {
        if (!self::estaAutenticado()) {
            return false;
        }
        
        if (is_array($rol)) {
            return in_array($_SESSION['usuario_rol'], $rol);
        }
        
        return $_SESSION['usuario_rol'] === $rol;
    }

    // Middleware para proteger rutas
    public static function requerirAuth() {
        if (!self::estaAutenticado()) {
            header("Location: index.php?controller=auth&action=login&error=auth_required");
            exit();
        }
    }

    // Middleware para proteger rutas por rol
    public static function requerirRol($roles) {
        self::requerirAuth();
        
        if (!self::tieneRol($roles)) {
            header("Location: index.php?controller=home&action=index&error=permission_denied");
            exit();
        }
    }
}
?>