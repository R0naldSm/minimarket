<?php
// models/Producto.php
class Producto {
    private $conn;
    private $table = "productos";

    // Propiedades del producto
    public $id;
    public $nombre;
    public $descripcion;
    public $precio;
    public $stock;
    public $categoria_id;
    public $imagen;
    public $fecha_creacion;

    // Constructor con conexión a la base de datos
    public function __construct($db) {
        $this->conn = $db;
    }

    // Crear nuevo producto
    public function crear() {
        $query = "INSERT INTO " . $this->table . " 
                  SET nombre = :nombre, 
                      descripcion = :descripcion,
                      precio = :precio,
                      stock = :stock,
                      categoria_id = :categoria_id,
                      imagen = :imagen";
        
        $stmt = $this->conn->prepare($query);
        
        // Limpiar datos
        $this->nombre = htmlspecialchars(strip_tags($this->nombre));
        $this->descripcion = htmlspecialchars(strip_tags($this->descripcion));
        $this->precio = htmlspecialchars(strip_tags($this->precio));
        $this->stock = htmlspecialchars(strip_tags($this->stock));
        $this->categoria_id = htmlspecialchars(strip_tags($this->categoria_id));
        $this->imagen = htmlspecialchars(strip_tags($this->imagen));
        
        // Vincular parámetros
        $stmt->bindParam(":nombre", $this->nombre);
        $stmt->bindParam(":descripcion", $this->descripcion);
        $stmt->bindParam(":precio", $this->precio);
        $stmt->bindParam(":stock", $this->stock);
        $stmt->bindParam(":categoria_id", $this->categoria_id);
        $stmt->bindParam(":imagen", $this->imagen);
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Leer todos los productos
    public function leerTodos() {
        $query = "SELECT p.id, p.nombre, p.descripcion, p.precio, p.stock, 
                         p.categoria_id, p.imagen, p.fecha_creacion,
                         c.nombre as categoria_nombre
                  FROM " . $this->table . " p
                  LEFT JOIN categorias c ON p.categoria_id = c.id
                  ORDER BY p.nombre ASC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        return $stmt;
    }

    // Leer un producto específico
    public function leerUno() {
        $query = "SELECT p.id, p.nombre, p.descripcion, p.precio, p.stock, 
                         p.categoria_id, p.imagen, p.fecha_creacion,
                         c.nombre as categoria_nombre
                  FROM " . $this->table . " p
                  LEFT JOIN categorias c ON p.categoria_id = c.id
                  WHERE p.id = :id 
                  LIMIT 1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $this->id);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if($row) {
            $this->nombre = $row['nombre'];
            $this->descripcion = $row['descripcion'];
            $this->precio = $row['precio'];
            $this->stock = $row['stock'];
            $this->categoria_id = $row['categoria_id'];
            $this->imagen = $row['imagen'];
            $this->fecha_creacion = $row['fecha_creacion'];
            return true;
        }
        return false;
    }

    // Actualizar producto
    public function actualizar() {
        $query = "UPDATE " . $this->table . " 
                  SET nombre = :nombre, 
                      descripcion = :descripcion,
                      precio = :precio,
                      stock = :stock,
                      categoria_id = :categoria_id,
                      imagen = :imagen
                  WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        
        // Limpiar datos
        $this->nombre = htmlspecialchars(strip_tags($this->nombre));
        $this->descripcion = htmlspecialchars(strip_tags($this->descripcion));
        $this->precio = htmlspecialchars(strip_tags($this->precio));
        $this->stock = htmlspecialchars(strip_tags($this->stock));
        $this->categoria_id = htmlspecialchars(strip_tags($this->categoria_id));
        $this->imagen = htmlspecialchars(strip_tags($this->imagen));
        $this->id = htmlspecialchars(strip_tags($this->id));
        
        // Vincular parámetros
        $stmt->bindParam(":nombre", $this->nombre);
        $stmt->bindParam(":descripcion", $this->descripcion);
        $stmt->bindParam(":precio", $this->precio);
        $stmt->bindParam(":stock", $this->stock);
        $stmt->bindParam(":categoria_id", $this->categoria_id);
        $stmt->bindParam(":imagen", $this->imagen);
        $stmt->bindParam(":id", $this->id);
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Eliminar producto
    public function eliminar() {
        $query = "DELETE FROM " . $this->table . " 
                  WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        
        $this->id = htmlspecialchars(strip_tags($this->id));
        
        $stmt->bindParam(":id", $this->id);
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Contar total de productos
    public function contar() {
        $query = "SELECT COUNT(*) as total FROM " . $this->table;
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total'];
    }

    // Productos con bajo stock
    public function productosConBajoStock($limite = 10) {
        $query = "SELECT p.id, p.nombre, p.stock, c.nombre as categoria_nombre
                  FROM " . $this->table . " p
                  LEFT JOIN categorias c ON p.categoria_id = c.id
                  WHERE p.stock < :limite
                  ORDER BY p.stock ASC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":limite", $limite);
        $stmt->execute();
        
        return $stmt;
    }

    // Productos por categoría
    public function leerPorCategoria($categoria_id) {
        $query = "SELECT * FROM " . $this->table . " 
                  WHERE categoria_id = :categoria_id
                  ORDER BY nombre ASC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":categoria_id", $categoria_id);
        $stmt->execute();
        
        return $stmt;
    }

    // Buscar productos
    public function buscar($termino) {
        $query = "SELECT p.*, c.nombre as categoria_nombre
                  FROM " . $this->table . " p
                  LEFT JOIN categorias c ON p.categoria_id = c.id
                  WHERE p.nombre LIKE :termino 
                     OR p.descripcion LIKE :termino
                  ORDER BY p.nombre ASC";
        
        $stmt = $this->conn->prepare($query);
        $termino = "%{$termino}%";
        $stmt->bindParam(":termino", $termino);
        $stmt->execute();
        
        return $stmt;
    }

    // Actualizar stock
    public function actualizarStock($cantidad) {
        $query = "UPDATE " . $this->table . " 
                  SET stock = stock + :cantidad
                  WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(":cantidad", $cantidad);
        $stmt->bindParam(":id", $this->id);
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    }
}
?>