<?php
// controllers/ProductoController.php
require_once 'config/Database.php';
require_once 'models/Producto.php';
require_once 'models/Categoria.php';

class ProductoController {
    private $db;
    private $producto;
    private $categoria;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->producto = new Producto($this->db);
        $this->categoria = new Categoria($this->db);
    }

    public function index() {
        $stmt = $this->producto->leerTodos();
        $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);
        require 'views/productos/index.php';
    }

    public function crear() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->producto->nombre = $_POST['nombre'];
            $this->producto->descripcion = $_POST['descripcion'];
            $this->producto->precio = $_POST['precio'];
            $this->producto->stock = $_POST['stock'];
            $this->producto->categoria_id = $_POST['categoria_id'];
            $this->producto->imagen = ''; // Campo vacío por defecto
            
            if ($this->producto->crear()) {
                header("Location: index.php?controller=producto&action=index&msg=created");
                exit();
            } else {
                $error = "Error al crear el producto";
            }
        }
        
        $stmt = $this->categoria->leerTodas();
        $categorias = $stmt->fetchAll(PDO::FETCH_ASSOC);
        require 'views/productos/crear.php';
    }

    public function editar() {
        if (isset($_GET['id'])) {
            $this->producto->id = $_GET['id'];
            
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $this->producto->nombre = $_POST['nombre'];
                $this->producto->descripcion = $_POST['descripcion'];
                $this->producto->precio = $_POST['precio'];
                $this->producto->stock = $_POST['stock'];
                $this->producto->categoria_id = $_POST['categoria_id'];
                $this->producto->imagen = $_POST['imagen'] ?? '';
                
                if ($this->producto->actualizar()) {
                    header("Location: index.php?controller=producto&action=index&msg=updated");
                    exit();
                } else {
                    $error = "Error al actualizar el producto";
                }
            }
            
            $this->producto->leerUno();
            $stmt = $this->categoria->leerTodas();
            $categorias = $stmt->fetchAll(PDO::FETCH_ASSOC);
            require 'views/productos/editar.php';
        }
    }

    public function eliminar() {
        if (isset($_GET['id'])) {
            $this->producto->id = $_GET['id'];
            if ($this->producto->eliminar()) {
                header("Location: index.php?controller=producto&action=index&msg=deleted");
            } else {
                header("Location: index.php?controller=producto&action=index&msg=error");
            }
            exit();
        }
    }
}
?>