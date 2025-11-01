<?php
// models/Venta.php
class Venta {
    private $conn;
    private $table = "ventas";
    private $detalle_table = "detalle_ventas";

    public $id;
    public $cliente_id;
    public $total;
    public $fecha_venta;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Crear nueva venta con detalles
    public function crear($detalles) {
        try {
            // Iniciar transacción
            $this->conn->beginTransaction();
            
            // Insertar venta
            $query = "INSERT INTO " . $this->table . " 
                      (cliente_id, total) 
                      VALUES (:cliente_id, :total)";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":cliente_id", $this->cliente_id);
            $stmt->bindParam(":total", $this->total);
            
            if (!$stmt->execute()) {
                throw new Exception("Error al crear la venta");
            }
            
            // Obtener ID de la venta creada
            $venta_id = $this->conn->lastInsertId();
            
            // Insertar detalles de venta
            $query_detalle = "INSERT INTO " . $this->detalle_table . " 
                              (venta_id, producto_id, cantidad, precio_unitario, subtotal) 
                              VALUES (:venta_id, :producto_id, :cantidad, :precio_unitario, :subtotal)";
            
            $stmt_detalle = $this->conn->prepare($query_detalle);
            
            foreach ($detalles as $detalle) {
                $stmt_detalle->bindParam(":venta_id", $venta_id);
                $stmt_detalle->bindParam(":producto_id", $detalle['producto_id']);
                $stmt_detalle->bindParam(":cantidad", $detalle['cantidad']);
                $stmt_detalle->bindParam(":precio_unitario", $detalle['precio_unitario']);
                $stmt_detalle->bindParam(":subtotal", $detalle['subtotal']);
                
                if (!$stmt_detalle->execute()) {
                    throw new Exception("Error al insertar detalle de venta");
                }
                
                // Actualizar stock del producto
                $query_stock = "UPDATE productos 
                               SET stock = stock - :cantidad 
                               WHERE id = :producto_id";
                $stmt_stock = $this->conn->prepare($query_stock);
                $stmt_stock->bindParam(":cantidad", $detalle['cantidad']);
                $stmt_stock->bindParam(":producto_id", $detalle['producto_id']);
                
                if (!$stmt_stock->execute()) {
                    throw new Exception("Error al actualizar stock");
                }
            }
            
            // Confirmar transacción
            $this->conn->commit();
            return $venta_id;
            
        } catch (Exception $e) {
            // Revertir transacción en caso de error
            $this->conn->rollBack();
            return false;
        }
    }

    // Leer todas las ventas
    public function leerTodas() {
        $query = "SELECT v.*, c.nombre as cliente_nombre, c.apellido as cliente_apellido
                  FROM " . $this->table . " v
                  LEFT JOIN clientes c ON v.cliente_id = c.id
                  ORDER BY v.fecha_venta DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        return $stmt;
    }

    // Leer una venta específica con detalles
    public function leerUna() {
        $query = "SELECT v.*, c.nombre as cliente_nombre, c.apellido as cliente_apellido,
                         c.email as cliente_email, c.telefono as cliente_telefono
                  FROM " . $this->table . " v
                  LEFT JOIN clientes c ON v.cliente_id = c.id
                  WHERE v.id = :id 
                  LIMIT 1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $this->id);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Leer detalles de una venta
    public function leerDetalles() {
        $query = "SELECT dv.*, p.nombre as producto_nombre
                  FROM " . $this->detalle_table . " dv
                  INNER JOIN productos p ON dv.producto_id = p.id
                  WHERE dv.venta_id = :venta_id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":venta_id", $this->id);
        $stmt->execute();
        
        return $stmt;
    }

    // Contar total de ventas
    public function contar() {
        $query = "SELECT COUNT(*) as total FROM " . $this->table;
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total'];
    }

    // Total vendido (suma de todas las ventas)
    public function totalVendido() {
        $query = "SELECT COALESCE(SUM(total), 0) as total_vendido FROM " . $this->table;
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total_vendido'];
    }

    // Ventas del día
    public function ventasDelDia() {
        $query = "SELECT COUNT(*) as total 
                  FROM " . $this->table . " 
                  WHERE DATE(fecha_venta) = CURDATE()";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total'];
    }

    // Total vendido hoy
    public function totalVendidoHoy() {
        $query = "SELECT COALESCE(SUM(total), 0) as total 
                  FROM " . $this->table . " 
                  WHERE DATE(fecha_venta) = CURDATE()";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total'];
    }
}
?>