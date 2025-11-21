<?php
/**
 * Clase Database
 * Gestiona la conexión a la base de datos usando PDO
 */

require_once __DIR__ . '/../config/database.php';

class Database {
    private $conexion;
    private $stmt;
    
    /**
     * Constructor - Establece la conexión automáticamente
     */
    public function __construct() {
        $this->conectar();
    }
    
    /**
     * Establece conexión con MySQL usando PDO
     * @return void
     */
    private function conectar() {
        try {
            $str_dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
            
            $arr_opciones = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ];
            
            $this->conexion = new PDO($str_dsn, DB_USER, DB_PASS, $arr_opciones);
            
        } catch (PDOException $e) {
            die("Error de conexión: " . $e->getMessage());
        }
    }
    
    /**
     * Prepara una consulta SQL
     * @param string $str_sql Consulta SQL
     * @return void
     */
    public function query($str_sql) {
        $this->stmt = $this->conexion->prepare($str_sql);
    }
    
    /**
     * Vincula un valor a un parámetro
     * @param mixed $param Parámetro
     * @param mixed $value Valor
     * @param int $type Tipo de dato PDO
     * @return void
     */
    public function bind($param, $value, $type = null) {
        if (is_null($type)) {
            switch (true) {
                case is_int($value):
                    $type = PDO::PARAM_INT;
                    break;
                case is_bool($value):
                    $type = PDO::PARAM_BOOL;
                    break;
                case is_null($value):
                    $type = PDO::PARAM_NULL;
                    break;
                default:
                    $type = PDO::PARAM_STR;
            }
        }
        $this->stmt->bindValue($param, $value, $type);
    }
    
    /**
     * Ejecuta la consulta preparada
     * @return bool
     */
    public function execute() {
        return $this->stmt->execute();
    }
    
    /**
     * Obtiene todos los resultados
     * @return array
     */
    public function resultSet() {
        $this->execute();
        return $this->stmt->fetchAll();
    }
    
    /**
     * Obtiene un solo resultado
     * @return mixed
     */
    public function single() {
        $this->execute();
        return $this->stmt->fetch();
    }
    
    /**
     * Obtiene el número de filas afectadas
     * @return int
     */
    public function rowCount() {
        return $this->stmt->rowCount();
    }
    
    /**
     * Obtiene el último ID insertado
     * @return string
     */
    public function lastInsertId() {
        return $this->conexion->lastInsertId();
    }
    
    /**
     * Inicia una transacción
     * @return bool
     */
    public function beginTransaction() {
        return $this->conexion->beginTransaction();
    }
    
    /**
     * Confirma una transacción
     * @return bool
     */
    public function commit() {
        return $this->conexion->commit();
    }
    
    /**
     * Revierte una transacción
     * @return bool
     */
    public function rollBack() {
        return $this->conexion->rollBack();
    }
    
    /**
     * Llama a un procedimiento almacenado
     * @param string $str_procedimiento Nombre del SP
     * @param array $arr_parametros Parámetros del SP
     * @return array|false
     */
    public function callProcedure($str_procedimiento, $arr_parametros = []) {
        try {
            $str_placeholders = implode(',', array_fill(0, count($arr_parametros), '?'));
            $str_sql = "CALL {$str_procedimiento}({$str_placeholders})";
            
            $this->query($str_sql);
            
            foreach ($arr_parametros as $i => $valor) {
                $this->bind($i + 1, $valor);
            }
            
            return $this->resultSet();
            
        } catch (PDOException $e) {
            error_log("Error en procedimiento almacenado: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Cierra la conexión
     * @return void
     */
    public function close() {
        $this->conexion = null;
    }
}
?>