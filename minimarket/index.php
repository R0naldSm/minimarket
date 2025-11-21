<?php
/**
 * index.php - Punto de entrada principal
 * Minimarket "Bendición de Dios"
 * 
 * Enrutador simple para manejar las peticiones
 */

// Iniciar sesión solo si no está activa
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Cargar configuración
require_once 'config/database.php';

// Obtener parámetros de la URL
$str_controller = $_GET['controller'] ?? 'login';
$str_action = $_GET['action'] ?? 'index';

// Enrutamiento
switch ($str_controller) {
    case 'login':
        require_once 'controllers/LoginController.php';
        $controller = new LoginController();
        
        switch ($str_action) {
            case 'autenticar':
                $controller->autenticar();
                break;
            case 'logout':
                $controller->cerrarSesion();
                break;
            default:
                $controller->mostrarLogin();
                break;
        }
        break;
        
    case 'cliente':
        require_once 'controllers/ClienteController.php';
        $controller = new ClienteController();
        
        switch ($str_action) {
            case 'listar':
                $controller->listar();
                break;
            case 'crear':
                $controller->crear();
                break;
            case 'editar':
                $controller->editar();
                break;
            case 'guardar':
                $controller->guardar();
                break;
            case 'eliminar':
                $controller->eliminar();
                break;
            case 'buscar':
                $controller->buscar();
                break;
            case 'exportar':
                $controller->exportarCSV();
                break;
            default:
                $controller->listar();
                break;
        }
        break;
        
    default:
        // Controlador no encontrado - redirigir al login
        header('Location: index.php?controller=login');
        exit;

}
?>