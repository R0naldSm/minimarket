<?php
// models/Usuario.php
class Usuario {
    private $conn;
    private $table = "usuarios";

    public $id;
    public $nombre;
    public $usuario;
    public $password;
    public $rol;
    public $activo;
    public $fecha_creacion;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Autenticar usuario
    public function autenticar($usuario, $password) {
        $query = "SELECT id, nombre, usuario, password, rol, activo 
                  FROM " . $this->table . " 
                  WHERE usuario = :usuario AND activo = 1 
                  LIMIT 1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":usuario", $usuario);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($row && password_verify($password, $row['password'])) {
            $this->id = $row['id'];
            $this->nombre = $row['nombre'];
            $this->usuario = $row['usuario'];
            $this->rol = $row['rol'];
            $this->activo = $row['activo'];
            return true;
        }
        
        return false;
    }

    // Crear nuevo usuario
    public function crear() {
        $query = "INSERT INTO " . $this->table . " 
                  (nombre, usuario, password, rol, activo) 
                  VALUES (:nombre, :usuario, :password, :rol, :activo)";
        
        $stmt = $this->conn->prepare($query);
        
        // Hash de la contraseña
        $password_hash = password_hash($this->password, PASSWORD_DEFAULT);
        
        $stmt->bindParam(":nombre", $this->nombre);
        $stmt->bindParam(":usuario", $this->usuario);
        $stmt->bindParam(":password", $password_hash);
        $stmt->bindParam(":rol", $this->rol);
        $stmt->bindParam(":activo", $this->activo);
        
        return $stmt->execute();
    }

    // Leer todos los usuarios
    public function leerTodos() {
        $query = "SELECT id, nombre, usuario, rol, activo, fecha_creacion 
                  FROM " . $this->table . " 
                  ORDER BY nombre ASC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        return $stmt;
    }

    // Leer un usuario
    public function leerUno() {
        $query = "SELECT id, nombre, usuario, rol, activo, fecha_creacion 
                  FROM " . $this->table . " 
                  WHERE id = :id 
                  LIMIT 1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $this->id);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($row) {
            $this->nombre = $row['nombre'];
            $this->usuario = $row['usuario'];
            $this->rol = $row['rol'];
            $this->activo = $row['activo'];
            $this->fecha_creacion = $row['fecha_creacion'];
            return true;
        }
        
        return false;
    }

    // Actualizar usuario
    public function actualizar() {
        if (!empty($this->password)) {
            $query = "UPDATE " . $this->table . " 
                      SET nombre = :nombre, 
                          usuario = :usuario, 
                          password = :password,
                          rol = :rol,
                          activo = :activo
                      WHERE id = :id";
            
            $stmt = $this->conn->prepare($query);
            $password_hash = password_hash($this->password, PASSWORD_DEFAULT);
            $stmt->bindParam(":password", $password_hash);
        } else {
            $query = "UPDATE " . $this->table . " 
                      SET nombre = :nombre, 
                          usuario = :usuario, 
                          rol = :rol,
                          activo = :activo
                      WHERE id = :id";
            
            $stmt = $this->conn->prepare($query);
        }
        
        $stmt->bindParam(":nombre", $this->nombre);
        $stmt->bindParam(":usuario", $this->usuario);
        $stmt->bindParam(":rol", $this->rol);
        $stmt->bindParam(":activo", $this->activo);
        $stmt->bindParam(":id", $this->id);
        
        return $stmt->execute();
    }

    // Eliminar usuario
    public function eliminar() {
        $query = "DELETE FROM " . $this->table . " WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $this->id);
        
        return $stmt->execute();
    }

    // Verificar si el usuario existe
    public function usuarioExiste($usuario) {
        $query = "SELECT id FROM " . $this->table . " WHERE usuario = :usuario";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":usuario", $usuario);
        $stmt->execute();
        
        return $stmt->rowCount() > 0;
    }
}
?>