<?php
/**
 * Modelo Usuario
 * Gestiona las operaciones relacionadas con usuarios
 */

require_once 'Database.php';

class Usuario {
    private $db;
    
    // Propiedades
    public $int_id_usuario;
    public $str_nombre_usuario;
    public $str_password;
    public $str_nombre_completo;
    public $str_email;
    public $enum_rol;
    public $bool_activo;
    
    /**
     * Constructor
     */
    public function __construct() {
        $this->db = new Database();
    }
    
    /**
     * Valida las credenciales del usuario
     * @param string $str_usuario Nombre de usuario
     * @param string $str_password Contraseña
     * @return array|false Datos del usuario o false
     */
    public function validarUsuario($str_usuario, $str_password) {
        try {
            // Encriptar contraseña
            $str_password_hash = md5($str_password);
            
            // Llamar procedimiento almacenado
            $resultado = $this->db->callProcedure('sp_validar_usuario', [
                $str_usuario,
                $str_password
            ]);
            
            if ($resultado && count($resultado) > 0) {
                return $resultado[0];
            }
            
            return false;
            
        } catch (Exception $e) {
            error_log("Error en validarUsuario: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Obtiene un usuario por ID
     * @param int $int_id ID del usuario
     * @return array|false
     */
    public function obtenerPorId($int_id) {
        $this->db->query("SELECT * FROM usuarios WHERE id_usuario = :id");
        $this->db->bind(':id', $int_id);
        return $this->db->single();
    }
    
    /**
     * Verifica si un nombre de usuario ya existe
     * @param string $str_usuario Nombre de usuario
     * @return bool
     */
    public function existeUsuario($str_usuario) {
        $this->db->query("SELECT COUNT(*) as total FROM usuarios WHERE str_nombre_usuario = :usuario");
        $this->db->bind(':usuario', $str_usuario);
        $resultado = $this->db->single();
        return $resultado['total'] > 0;
    }
    
    /**
     * Crea un nuevo usuario
     * @param array $arr_datos Datos del usuario
     * @return bool
     */
    public function crear($arr_datos) {
        try {
            $this->db->query("
                INSERT INTO usuarios (
                    str_nombre_usuario,
                    str_password,
                    str_nombre_completo,
                    str_email,
                    enum_rol,
                    int_id_usuario_alta
                ) VALUES (
                    :usuario,
                    :password,
                    :nombre_completo,
                    :email,
                    :rol,
                    :id_usuario_alta
                )
            ");
            
            $this->db->bind(':usuario', $arr_datos['str_nombre_usuario']);
            $this->db->bind(':password', md5($arr_datos['str_password']));
            $this->db->bind(':nombre_completo', $arr_datos['str_nombre_completo']);
            $this->db->bind(':email', $arr_datos['str_email']);
            $this->db->bind(':rol', $arr_datos['enum_rol']);
            $this->db->bind(':id_usuario_alta', $arr_datos['int_id_usuario_alta']);
            
            return $this->db->execute();
            
        } catch (Exception $e) {
            error_log("Error al crear usuario: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Actualiza la contraseña de un usuario
     * @param int $int_id_usuario ID del usuario
     * @param string $str_nueva_password Nueva contraseña
     * @return bool
     */
    public function actualizarPassword($int_id_usuario, $str_nueva_password) {
        $this->db->query("
            UPDATE usuarios 
            SET str_password = :password,
                datetime_fecha_modificacion = NOW()
            WHERE id_usuario = :id
        ");
        
        $this->db->bind(':password', md5($str_nueva_password));
        $this->db->bind(':id', $int_id_usuario);
        
        return $this->db->execute();
    }
    
    /**
     * Lista todos los usuarios activos
     * @return array
     */
    public function listarActivos() {
        $this->db->query("
            SELECT 
                id_usuario,
                str_nombre_usuario,
                str_nombre_completo,
                str_email,
                enum_rol,
                datetime_fecha_alta
            FROM usuarios
            WHERE bool_activo = 1
            ORDER BY str_nombre_completo
        ");
        return $this->db->resultSet();
    }
}
?>