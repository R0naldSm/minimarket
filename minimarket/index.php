<?php
// index.php - Controlador frontal
session_start();

// Cargar configuración
require_once 'config/Config.php';

// Función de autoload mejorada
spl_autoload_register(function ($className) {
    $paths = [
        CONTROLLER_PATH . "/{$className}.php",
        MODEL_PATH . "/{$className}.php",
        ROOT_PATH . "/config/{$className}.php"
    ];
    
    foreach ($paths as $path) {
        if (file_exists($path)) {
            require_once $path;
            return;
        }
    }
});

// Resto del código igual...
// Obtener el controlador y la acción
$controller = $_GET['controller'] ?? 'home';
$action = $_GET['action'] ?? 'index';

// Mapeo de controladores
$controllers = [
    'home' => 'HomeController',
    'categoria' => 'CategoriaController',
    'producto' => 'ProductoController',
    'cliente' => 'ClienteController'
];

// Verificar si el controlador existe
if (array_key_exists($controller, $controllers)) {
    $controllerClass = $controllers[$controller];
    
    try {
        if (!class_exists($controllerClass)) {
            throw new Exception("Clase controlador no encontrada: {$controllerClass}");
        }
        
        $controllerInstance = new $controllerClass();
        
        if (!method_exists($controllerInstance, $action)) {
            throw new Exception("Método no encontrado: {$action} en {$controllerClass}");
        }
        
        $controllerInstance->$action();
        
    } catch (Exception $e) {
        http_response_code(404);
        echo "Error: " . $e->getMessage();
        error_log("Error en controlador frontal: " . $e->getMessage());
    }
} else {
    http_response_code(404);
    echo "Controlador no encontrado";
}
?>