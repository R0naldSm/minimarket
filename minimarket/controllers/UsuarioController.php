<?php
// controllers/UsuarioController.php
require_once 'config/Database.php';
require_once 'models/Usuario.php';
require_once 'controllers/AuthController.php';

class UsuarioController {
    private $db;
    private $usuario;

    public function __construct() {
        // Solo superusuarios pueden acceder
        AuthController::requerirRol('superusuario');
        
        $database = new Database();
        $this->db = $database->getConnection();
        $this->usuario = new Usuario($this->db);
    }

    public function index() {
        $stmt = $this->usuario->leerTodos();
        $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
        require 'views/usuarios/index.php';
    }

    public function crear() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->usuario->nombre = $_POST['nombre'];
            $this->usuario->usuario = $_POST['usuario'];
            $this->usuario->password = $_POST['password'];
            $this->usuario->rol = $_POST['rol'];
            $this->usuario->activo = isset($_POST['activo']) ? 1 : 0;
            
            if ($this->usuario->crear()) {
                header("Location: index.php?controller=usuario&action=index&msg=created");
                exit();
            } else {
                $error = "Error al crear el usuario";
            }
        }
        require 'views/usuarios/crear.php';
    }

    public function editar() {
        if (isset($_GET['id'])) {
            $this->usuario->id = $_GET['id'];
            
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $this->usuario->nombre = $_POST['nombre'];
                $this->usuario->usuario = $_POST['usuario'];
                $this->usuario->password = !empty($_POST['password']) ? $_POST['password'] : '';
                $this->usuario->rol = $_POST['rol'];
                $this->usuario->activo = isset($_POST['activo']) ? 1 : 0;
                
                if ($this->usuario->actualizar()) {
                    header("Location: index.php?controller=usuario&action=index&msg=updated");
                    exit();
                } else {
                    $error = "Error al actualizar el usuario";
                }
            }
            
            $this->usuario->leerUno();
            require 'views/usuarios/editar.php';
        }
    }

    public function eliminar() {
        if (isset($_GET['id'])) {
            // No permitir eliminar al propio usuario
            if ($_GET['id'] == $_SESSION['usuario_id']) {
                header("Location: index.php?controller=usuario&action=index&msg=self_delete");
                exit();
            }
            
            $this->usuario->id = $_GET['id'];
            if ($this->usuario->eliminar()) {
                header("Location: index.php?controller=usuario&action=index&msg=deleted");
            } else {
                header("Location: index.php?controller=usuario&action=index&msg=error");
            }
            exit();
        }
    }
}
?>