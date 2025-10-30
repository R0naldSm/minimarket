<?php
// controllers/ClienteController.php
require_once 'config/Database.php';
require_once 'models/Cliente.php';

class ClienteController {
    private $db;
    private $cliente;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->cliente = new Cliente($this->db);
    }

    // Listar todos los clientes
    public function index() {
        $stmt = $this->cliente->leerTodos();
        $clientes = $stmt->fetchAll(PDO::FETCH_ASSOC);
        require 'views/clientes/index.php';
    }

    // Crear nuevo cliente
    public function crear() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->cliente->nombre = $_POST['nombre'];
            $this->cliente->apellido = $_POST['apellido'];
            $this->cliente->email = $_POST['email'];
            $this->cliente->telefono = $_POST['telefono'];
            $this->cliente->direccion = $_POST['direccion'];
            
            if ($this->cliente->crear()) {
                header("Location: index.php?controller=cliente&action=index&msg=created");
                exit();
            } else {
                $error = "Error al crear el cliente";
            }
        }
        require 'views/clientes/crear.php';
    }

    // Editar cliente existente
    public function editar() {
        if (isset($_GET['id'])) {
            $this->cliente->id = $_GET['id'];
            
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $this->cliente->nombre = $_POST['nombre'];
                $this->cliente->apellido = $_POST['apellido'];
                $this->cliente->email = $_POST['email'];
                $this->cliente->telefono = $_POST['telefono'];
                $this->cliente->direccion = $_POST['direccion'];
                
                if ($this->cliente->actualizar()) {
                    header("Location: index.php?controller=cliente&action=index&msg=updated");
                    exit();
                } else {
                    $error = "Error al actualizar el cliente";
                }
            }
            
            $this->cliente->leerUno();
            require 'views/clientes/editar.php';
        }
    }

    // Eliminar cliente
    public function eliminar() {
        if (isset($_GET['id'])) {
            $this->cliente->id = $_GET['id'];
            if ($this->cliente->eliminar()) {
                header("Location: index.php?controller=cliente&action=index&msg=deleted");
            } else {
                header("Location: index.php?controller=cliente&action=index&msg=error");
            }
            exit();
        }
    }
}
?>