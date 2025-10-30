<?php
// controllers/CategoriaController.php
require_once 'config/Database.php';
require_once 'models/Categoria.php';

class CategoriaController {
    private $db;
    private $categoria;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->categoria = new Categoria($this->db);
    }

    public function index() {
        $stmt = $this->categoria->leerTodas();
        $categorias = $stmt->fetchAll(PDO::FETCH_ASSOC);
        require 'views/categorias/index.php';
    }

    public function crear() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->categoria->nombre = $_POST['nombre'];
            $this->categoria->descripcion = $_POST['descripcion'];
            
            if ($this->categoria->crear()) {
                header("Location: index.php?controller=categoria&action=index&msg=created");
                exit();
            } else {
                $error = "Error al crear la categoría";
            }
        }
        require 'views/categorias/crear.php';
    }

    public function editar() {
        if (isset($_GET['id'])) {
            $this->categoria->id = $_GET['id'];
            
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $this->categoria->nombre = $_POST['nombre'];
                $this->categoria->descripcion = $_POST['descripcion'];
                
                if ($this->categoria->actualizar()) {
                    header("Location: index.php?controller=categoria&action=index&msg=updated");
                    exit();
                } else {
                    $error = "Error al actualizar la categoría";
                }
            }
            
            $this->categoria->leerUna();
            require 'views/categorias/editar.php';
        }
    }

    public function eliminar() {
        if (isset($_GET['id'])) {
            $this->categoria->id = $_GET['id'];
            if ($this->categoria->eliminar()) {
                header("Location: index.php?controller=categoria&action=index&msg=deleted");
            } else {
                header("Location: index.php?controller=categoria&action=index&msg=error");
            }
            exit();
        }
    }
}
?>