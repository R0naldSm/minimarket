<?php
/**
 * Archivo de Configuración de Base de Datos


 * Define las constantes de conexión a MySQL
 */

// Configuración del servidor de base de datos
define('DB_HOST', 'localhost');
define('DB_NAME', 'db_minimarket_bendicion');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');

// Configuración de la aplicación
define('APP_NAME', 'Minimarket Bendición de Dios');
define('APP_URL', 'http://localhost/minimarket/');
define('APP_VERSION', '1.0.0');

// Configuración de sesión
define('SESSION_TIMEOUT', 1800); // 30 minutos en segundos

// Configuración de seguridad
define('PASSWORD_HASH_ALGO', 'md5'); // md5 o sha256

// Zona horaria
date_default_timezone_set('America/Guayaquil');

// Configuración de errores (cambiar a 0 en producción)
ini_set('display_errors', 1);
error_reporting(E_ALL);
?>