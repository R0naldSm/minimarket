<?php
// index.php - Controlador frontal
session_start();

// Cargar configuración si existe
if (file_exists('config/Config.php')) {
    require_once 'config/Config.php';
}

// Función de autoload mejorada
spl_autoload_register(function ($className) {
    // Si Config.php define las rutas, las usa
    if (defined('CONTROLLER_PATH') && defined('MODEL_PATH') && defined('ROOT_PATH')) {
        $paths = [
            CONTROLLER_PATH . "/{$className}.php",
            MODEL_PATH . "/{$className}.php",
            ROOT_PATH . "/config/{$className}.php"
        ];
    } else {
        // Rutas por defecto si no existe Config.php
        $paths = [
            "controllers/{$className}.php",
            "models/{$className}.php",
            "config/{$className}.php"
        ];
    }
    
    foreach ($paths as $path) {
        if (file_exists($path)) {
            require_once $path;
            return;
        }
    }
});

// Obtener el controlador y la acción
$controller = $_GET['controller'] ?? 'home';
$action = $_GET['action'] ?? 'index';

// Mapeo de controladores
$controllers = [
    'home' => 'HomeController',
    'categoria' => 'CategoriaController',
    'producto' => 'ProductoController',
    'cliente' => 'ClienteController',
    'venta' => 'VentaController',
    'auth' => 'AuthController',
    'usuario' => 'UsuarioController'
];

// Rutas públicas (no requieren autenticación)
$rutasPublicas = ['auth'];

// Verificar si el controlador existe
if (array_key_exists($controller, $controllers)) {
    $controllerClass = $controllers[$controller];
    
    try {
        // Si el autoload no funcionó, intentar carga manual
        if (!class_exists($controllerClass)) {
            $controllerFile = "controllers/{$controllerClass}.php";
            if (file_exists($controllerFile)) {
                require_once $controllerFile;
            } else {
                throw new Exception("Clase controlador no encontrada: {$controllerClass}");
            }
        }
        
        // Verificar autenticación (excepto para rutas públicas)
        if (!in_array($controller, $rutasPublicas)) {
            require_once 'controllers/AuthController.php';
            AuthController::requerirAuth();
        }
        
        // Crear instancia del controlador
        $controllerInstance = new $controllerClass();
        
        // Verificar si el método existe
        if (!method_exists($controllerInstance, $action)) {
            throw new Exception("Método no encontrado: {$action} en {$controllerClass}");
        }
        
        // Ejecutar la acción
        $controllerInstance->$action();
        
    } catch (Exception $e) {
        http_response_code(404);
        echo "<div style='padding: 2rem; text-align: center; font-family: Arial;'>";
        echo "<h2>❌ Error</h2>";
        echo "<p>" . htmlspecialchars($e->getMessage()) . "</p>";
        echo "<a href='index.php' style='color: #2563eb; text-decoration: none;'>← Volver al inicio</a>";
        echo "</div>";
        error_log("Error en controlador frontal: " . $e->getMessage());
    }
} else {
    http_response_code(404);
    echo "<div style='padding: 2rem; text-align: center; font-family: Arial;'>";
    echo "<h2>❌ 404 - Controlador no encontrado</h2>";
    echo "<p>El controlador '<strong>{$controller}</strong>' no existe.</p>";
    echo "<a href='index.php' style='color: #2563eb; text-decoration: none;'>← Volver al inicio</a>";
    echo "</div>";
    error_log("Controlador no encontrado: {$controller}");
}
?>