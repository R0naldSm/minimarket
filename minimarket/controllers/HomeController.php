<?php
// controllers/HomeController.php
require_once 'config/Database.php';

class HomeController {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    public function index() {
        // Obtener estadísticas
        $stats = $this->getEstadisticas();
        require 'views/home/index.php';
    }

    private function getEstadisticas() {
        $stats = [];
        
        // Total de productos
        $query = "SELECT COUNT(*) as total FROM productos";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $stats['total_productos'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
        
        // Total de categorías
        $query = "SELECT COUNT(*) as total FROM categorias";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $stats['total_categorias'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
        
        // Total de clientes
        $query = "SELECT COUNT(*) as total FROM clientes";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $stats['total_clientes'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
        
        // Productos con bajo stock
        $query = "SELECT COUNT(*) as total FROM productos WHERE stock < 10";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $stats['productos_bajo_stock'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
        
        return $stats;
    }
}
?>