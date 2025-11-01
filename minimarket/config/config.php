<?php
// config/Config.php - Configuración general del sistema

// Definir la ruta raíz del proyecto
define('ROOT_PATH', __DIR__ . '/..');

// Definir rutas de directorios
define('CONTROLLER_PATH', ROOT_PATH . '/controllers');
define('MODEL_PATH', ROOT_PATH . '/models');
define('VIEW_PATH', ROOT_PATH . '/views');
define('ASSET_PATH', ROOT_PATH . '/assets');

// Configuración de la base de datos
define('DB_HOST', 'localhost');
define('DB_NAME', 'minimarket1');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8');

// Configuración de la aplicación
define('APP_NAME', 'Minimarket System');
define('APP_VERSION', '1.0.0');

// Zona horaria
date_default_timezone_set('America/Guayaquil');

// Configuración de errores (para desarrollo)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Para producción, descomentar estas líneas:
// ini_set('display_errors', 0);
// error_reporting(0);
?>