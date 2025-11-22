<?php
/**
 * Modelo Cliente
 * Gestiona las operaciones CRUD de clientes
 */

require_once 'Database.php';

class Cliente {
    private $db;
    
    // Propiedades
    public $int_id_cliente;
    public $str_cedula;
    public $str_nombre;
    public $str_apellido;
    public $str_telefono;
    public $str_email;
    public $str_direccion;
    public $bool_activo;
    
    /**
     * Constructor
     */
    public function __construct() {
        $this->db = new Database();
    }
    
    /**
     * Lista todos los clientes activos
     * @return array
     */
    public function listar() {
        try {
            $resultado = $this->db->callProcedure('sp_listar_clientes');
            return $resultado ? $resultado : [];
        } catch (Exception $e) {
            error_log("Error al listar clientes: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Obtiene un cliente por ID
     * @param int $int_id ID del cliente
     * @return array|false
     */
    public function obtenerPorId($int_id) {
        $this->db->query("
            SELECT * FROM clientes 
            WHERE id_cliente = :id 
            AND bool_activo = 1
        ");
        $this->db->bind(':id', $int_id);
        return $this->db->single();
    }
    
    /**
     * Busca clientes por criterio
     * @param string $str_criterio Texto a buscar
     * @return array
     */
    public function buscar($str_criterio) {
        $this->db->query("
            SELECT 
                c.*,
                u.str_nombre_completo AS usuario_registro
            FROM clientes c
            INNER JOIN usuarios u ON c.int_id_usuario_alta = u.id_usuario
            WHERE c.bool_activo = 1
            AND (
                c.str_cedula LIKE :criterio1
                OR c.str_nombre LIKE :criterio2
                OR c.str_apellido LIKE :criterio3
                OR CONCAT(c.str_nombre, ' ', c.str_apellido) LIKE :criterio4
            )
            ORDER BY c.str_apellido, c.str_nombre
        ");
        
        $str_criterio_like = "%{$str_criterio}%";
        // Bind each placeholder separately because PDO MySQL native prepares
        // do not allow reusing the same named parameter multiple times
        $this->db->bind(':criterio1', $str_criterio_like);
        $this->db->bind(':criterio2', $str_criterio_like);
        $this->db->bind(':criterio3', $str_criterio_like);
        $this->db->bind(':criterio4', $str_criterio_like);
        
        return $this->db->resultSet();
    }
    
    /**
     * Inserta un nuevo cliente
     * @param array $arr_datos Datos del cliente
     * @return array Array con 'success' y 'message' o 'id'
     */
    public function insertar($arr_datos) {
        try {
            // Verificar si la cédula ya existe
            if ($this->existeCedula($arr_datos['str_cedula'])) {
                return [
                    'success' => false,
                    'message' => 'La cédula ya está registrada en el sistema'
                ];
            }
            
            // Llamar procedimiento almacenado
            $resultado = $this->db->callProcedure('sp_insertar_cliente', [
                $arr_datos['str_cedula'],
                $arr_datos['str_nombre'],
                $arr_datos['str_apellido'],
                $arr_datos['str_telefono'] ?? null,
                $arr_datos['str_email'] ?? null,
                $arr_datos['str_direccion'] ?? null,
                $arr_datos['int_id_usuario_alta']
            ]);
            
            if ($resultado && isset($resultado[0]['id_cliente'])) {
                return [
                    'success' => true,
                    'id' => $resultado[0]['id_cliente'],
                    'message' => 'Cliente registrado exitosamente'
                ];
            }
            
            return [
                'success' => false,
                'message' => 'Error al registrar el cliente'
            ];
            
        } catch (Exception $e) {
            error_log("Error al insertar cliente: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Actualiza un cliente existente
     * @param int $int_id ID del cliente
     * @param array $arr_datos Nuevos datos
     * @return array
     */
    public function actualizar($int_id, $arr_datos) {
        try {
            // Verificar si la cédula ya existe en otro cliente
            if ($this->existeCedula($arr_datos['str_cedula'], $int_id)) {
                return [
                    'success' => false,
                    'message' => 'La cédula ya está registrada en otro cliente'
                ];
            }
            
            // Llamar procedimiento almacenado
            $resultado = $this->db->callProcedure('sp_actualizar_cliente', [
                $int_id,
                $arr_datos['str_cedula'],
                $arr_datos['str_nombre'],
                $arr_datos['str_apellido'],
                $arr_datos['str_telefono'] ?? null,
                $arr_datos['str_email'] ?? null,
                $arr_datos['str_direccion'] ?? null,
                $arr_datos['int_id_usuario_modificacion']
            ]);
            
            if ($resultado && isset($resultado[0]['filas_afectadas']) && $resultado[0]['filas_afectadas'] > 0) {
                return [
                    'success' => true,
                    'message' => 'Cliente actualizado exitosamente'
                ];
            }
            
            return [
                'success' => false,
                'message' => 'No se pudo actualizar el cliente'
            ];
            
        } catch (Exception $e) {
            error_log("Error al actualizar cliente: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Elimina un cliente (eliminación lógica)
     * @param int $int_id ID del cliente
     * @param int $int_id_usuario ID del usuario que elimina
     * @return array
     */
    public function eliminar($int_id, $int_id_usuario) {
        try {
            $resultado = $this->db->callProcedure('sp_eliminar_cliente', [
                $int_id,
                $int_id_usuario
            ]);
            
            if ($resultado && isset($resultado[0]['filas_afectadas']) && $resultado[0]['filas_afectadas'] > 0) {
                return [
                    'success' => true,
                    'message' => 'Cliente eliminado exitosamente'
                ];
            }
            
            return [
                'success' => false,
                'message' => 'No se pudo eliminar el cliente'
            ];
            
        } catch (Exception $e) {
            error_log("Error al eliminar cliente: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Verifica si una cédula ya existe
     * @param string $str_cedula Cédula a verificar
     * @param int $int_id_excluir ID a excluir de la búsqueda (para updates)
     * @return bool
     */
    public function existeCedula($str_cedula, $int_id_excluir = null) {
        if ($int_id_excluir) {
            $this->db->query("
                SELECT COUNT(*) as total 
                FROM clientes 
                WHERE str_cedula = :cedula 
                AND id_cliente != :id
            ");
            $this->db->bind(':id', $int_id_excluir);
        } else {
            $this->db->query("
                SELECT COUNT(*) as total 
                FROM clientes 
                WHERE str_cedula = :cedula
            ");
        }
        
        $this->db->bind(':cedula', $str_cedula);
        $resultado = $this->db->single();
        return $resultado['total'] > 0;
    }
    
    /**
     * Valida el formato de cédula ecuatoriana
     * @param string $str_cedula Cédula a validar
     * @return bool
     */
    public static function validarCedula($str_cedula) {
        // Debe tener 10 o 13 dígitos
        if (!preg_match('/^\d{10}$|^\d{13}$/', $str_cedula)) {
            return false;
        }
        
        // Si es RUC (13 dígitos), ya es válido
        if (strlen($str_cedula) == 13) {
            return true;
        }
        
        // Validación de cédula ecuatoriana (10 dígitos)
        $arr_digitos = str_split($str_cedula);
        $int_provincia = intval(substr($str_cedula, 0, 2));
        
        if ($int_provincia < 1 || $int_provincia > 24) {
            return false;
        }
        
        $int_suma = 0;
        for ($i = 0; $i < 9; $i++) {
            $int_digito = intval($arr_digitos[$i]);
            if ($i % 2 == 0) {
                $int_digito *= 2;
                if ($int_digito > 9) {
                    $int_digito -= 9;
                }
            }
            $int_suma += $int_digito;
        }
        
        $int_verificador = (10 - ($int_suma % 10)) % 10;
        return $int_verificador == intval($arr_digitos[9]);
    }
    
    /**
     * Cuenta total de clientes activos
     * @return int
     */
    public function contarActivos() {
        $this->db->query("SELECT COUNT(*) as total FROM clientes WHERE bool_activo = 1");
        $resultado = $this->db->single();
        return $resultado['total'];
    }
}
