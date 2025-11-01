<?php
// controllers/VentaController.php
require_once 'config/Database.php';
require_once 'models/Venta.php';
require_once 'models/Cliente.php';
require_once 'models/Producto.php';

class VentaController {
    private $db;
    private $venta;
    private $cliente;
    private $producto;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->venta = new Venta($this->db);
        $this->cliente = new Cliente($this->db);
        $this->producto = new Producto($this->db);
        
        // Inicializar carrito si no existe (la sesión ya está iniciada en index.php)
        if (!isset($_SESSION['carrito'])) {
            $_SESSION['carrito'] = [];
        }
    }

    // Listar todas las ventas
    public function index() {
        $stmt = $this->venta->leerTodas();
        $ventas = $stmt->fetchAll(PDO::FETCH_ASSOC);
        require 'views/ventas/index.php';
    }

    // Nueva venta (mostrar formulario con carrito)
    public function nueva() {
        // Obtener clientes y productos
        $stmt_clientes = $this->cliente->leerTodos();
        $clientes = $stmt_clientes->fetchAll(PDO::FETCH_ASSOC);
        
        $stmt_productos = $this->producto->leerTodos();
        $productos = $stmt_productos->fetchAll(PDO::FETCH_ASSOC);
        
        require 'views/ventas/nueva.php';
    }

    // Agregar producto al carrito
    public function agregarCarrito() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $producto_id = $_POST['producto_id'];
            $cantidad = $_POST['cantidad'];
            
            // Obtener información del producto
            $this->producto->id = $producto_id;
            if ($this->producto->leerUno()) {
                // Verificar stock disponible
                if ($this->producto->stock >= $cantidad) {
                    // Verificar si el producto ya está en el carrito
                    $existe = false;
                    foreach ($_SESSION['carrito'] as &$item) {
                        if ($item['producto_id'] == $producto_id) {
                            $item['cantidad'] += $cantidad;
                            $item['subtotal'] = $item['cantidad'] * $item['precio_unitario'];
                            $existe = true;
                            break;
                        }
                    }
                    
                    // Si no existe, agregarlo
                    if (!$existe) {
                        $_SESSION['carrito'][] = [
                            'producto_id' => $producto_id,
                            'nombre' => $this->producto->nombre,
                            'precio_unitario' => $this->producto->precio,
                            'cantidad' => $cantidad,
                            'subtotal' => $this->producto->precio * $cantidad
                        ];
                    }
                    
                    header("Location: index.php?controller=venta&action=nueva&msg=added");
                } else {
                    header("Location: index.php?controller=venta&action=nueva&msg=no_stock");
                }
            }
            exit();
        }
    }

    // Eliminar producto del carrito
    public function eliminarCarrito() {
        if (isset($_GET['index'])) {
            $index = $_GET['index'];
            if (isset($_SESSION['carrito'][$index])) {
                unset($_SESSION['carrito'][$index]);
                $_SESSION['carrito'] = array_values($_SESSION['carrito']); // Reindexar
            }
        }
        
        header("Location: index.php?controller=venta&action=nueva&msg=removed");
        exit();
    }

    // Procesar venta
    public function procesar() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $cliente_id = $_POST['cliente_id'];
            
            // Verificar que hay productos en el carrito
            if (empty($_SESSION['carrito'])) {
                header("Location: index.php?controller=venta&action=nueva&msg=empty_cart");
                exit();
            }
            
            // Calcular total
            $total = 0;
            foreach ($_SESSION['carrito'] as $item) {
                $total += $item['subtotal'];
            }
            
            // Crear venta
            $this->venta->cliente_id = $cliente_id;
            $this->venta->total = $total;
            
            $venta_id = $this->venta->crear($_SESSION['carrito']);
            
            if ($venta_id) {
                // Limpiar carrito
                $_SESSION['carrito'] = [];
                header("Location: index.php?controller=venta&action=ver&id=" . $venta_id . "&msg=created");
            } else {
                header("Location: index.php?controller=venta&action=nueva&msg=error");
            }
            exit();
        }
    }

    // Ver detalle de una venta
    public function ver() {
        if (isset($_GET['id'])) {
            $this->venta->id = $_GET['id'];
            
            $venta = $this->venta->leerUna();
            $stmt_detalles = $this->venta->leerDetalles();
            $detalles = $stmt_detalles->fetchAll(PDO::FETCH_ASSOC);
            
            require 'views/ventas/ver.php';
        }
    }

    // Limpiar carrito
    public function limpiarCarrito() {
        $_SESSION['carrito'] = [];
        header("Location: index.php?controller=venta&action=nueva&msg=cleared");
        exit();
    }
}
?>