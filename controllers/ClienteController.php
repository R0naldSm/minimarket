<?php
/**
 * ClienteController
 * Gestiona todas las operaciones CRUD de clientes
 */

// Iniciar sesión solo si no está activa
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../models/Cliente.php';
require_once __DIR__ . '/LoginController.php';

class ClienteController {
    private $clienteModel;
    
    /**
     * Constructor
     */
    public function __construct() {
        // Verificar autenticación
        LoginController::requerirAutenticacion();
        $this->clienteModel = new Cliente();
    }
    
    /**
     * Lista todos los clientes
     */
    public function listar() {
        $arr_clientes = $this->clienteModel->listar();
        $int_total = count($arr_clientes);
        
        require_once __DIR__ . '/../views/clientes/lista.php';
    }
    
    /**
     * Muestra el formulario para crear cliente
     */
    public function crear() {
        $str_accion = 'crear';
        $arr_cliente = null;
        
        require_once __DIR__ . '/../views/clientes/formulario.php';
    }
    
    /**
     * Muestra el formulario para editar cliente
     */
    public function editar() {
        $int_id = intval($_GET['id'] ?? 0);
        
        if ($int_id <= 0) {
            $_SESSION['error'] = 'ID de cliente inválido';
            header('Location: ' . APP_URL . 'index.php?controller=cliente&action=listar');
            exit;
        }
        
        $arr_cliente = $this->clienteModel->obtenerPorId($int_id);
        
        if (!$arr_cliente) {
            $_SESSION['error'] = 'Cliente no encontrado';
            header('Location: ' . APP_URL . 'index.php?controller=cliente&action=listar');
            exit;
        }
        
        $str_accion = 'editar';
        require_once __DIR__ . '/../views/clientes/formulario.php';
    }
    
    /**
     * Guarda un cliente (crear o actualizar)
     */
    public function guardar() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . APP_URL . 'index.php?controller=cliente&action=listar');
            exit;
        }
        
        // Obtener datos del formulario
        $arr_datos = [
            'str_cedula' => trim($_POST['str_cedula'] ?? ''),
            'str_nombre' => trim($_POST['str_nombre'] ?? ''),
            'str_apellido' => trim($_POST['str_apellido'] ?? ''),
            'str_telefono' => trim($_POST['str_telefono'] ?? ''),
            'str_email' => trim($_POST['str_email'] ?? ''),
            'str_direccion' => trim($_POST['str_direccion'] ?? '')
        ];
        
        // Validar datos
        $arr_errores = $this->validarDatos($arr_datos);
        
        if (!empty($arr_errores)) {
            $_SESSION['error'] = implode('<br>', $arr_errores);
            $_SESSION['datos_formulario'] = $arr_datos;
            
            if (isset($_POST['id_cliente'])) {
                header('Location: ' . APP_URL . 'index.php?controller=cliente&action=editar&id=' . $_POST['id_cliente']);
            } else {
                header('Location: ' . APP_URL . 'index.php?controller=cliente&action=crear');
            }
            exit;
        }
        
        // Determinar si es crear o actualizar
        if (isset($_POST['id_cliente']) && !empty($_POST['id_cliente'])) {
            // Actualizar
            $int_id = intval($_POST['id_cliente']);
            $arr_datos['int_id_usuario_modificacion'] = LoginController::obtenerUsuarioId();
            
            $resultado = $this->clienteModel->actualizar($int_id, $arr_datos);
        } else {
            // Crear
            $arr_datos['int_id_usuario_alta'] = LoginController::obtenerUsuarioId();
            $resultado = $this->clienteModel->insertar($arr_datos);
        }
        
        // Manejar resultado
        if ($resultado['success']) {
            $_SESSION['success'] = $resultado['message'];
        } else {
            $_SESSION['error'] = $resultado['message'];
        }
        
        header('Location: ' . APP_URL . 'index.php?controller=cliente&action=listar');
        exit;
    }
    
    /**
     * Elimina un cliente
     */
    public function eliminar() {
        $int_id = intval($_GET['id'] ?? 0);
        
        if ($int_id <= 0) {
            $_SESSION['error'] = 'ID de cliente inválido';
            header('Location: ' . APP_URL . 'index.php?controller=cliente&action=listar');
            exit;
        }
        
        $int_id_usuario = LoginController::obtenerUsuarioId();
        $resultado = $this->clienteModel->eliminar($int_id, $int_id_usuario);
        
        if ($resultado['success']) {
            $_SESSION['success'] = $resultado['message'];
        } else {
            $_SESSION['error'] = $resultado['message'];
        }
        
        header('Location: ' . APP_URL . 'index.php?controller=cliente&action=listar');
        exit;
    }
    
    /**
     * Busca clientes por criterio
     */
    public function buscar() {
        $str_criterio = trim($_GET['q'] ?? '');
        
        if (empty($str_criterio)) {
            header('Location: ' . APP_URL . 'index.php?controller=cliente&action=listar');
            exit;
        }
        
        $arr_clientes = $this->clienteModel->buscar($str_criterio);
        $int_total = count($arr_clientes);
        
        require_once __DIR__ . '/../views/clientes/lista.php';
    }
    
    /**
     * Valida los datos del cliente
     * @param array $arr_datos Datos a validar
     * @return array Array de errores (vacío si no hay errores)
     */
    private function validarDatos($arr_datos) {
        $arr_errores = [];
        
        // Validar cédula
        if (empty($arr_datos['str_cedula'])) {
            $arr_errores[] = 'La cédula es obligatoria';
        } elseif (!Cliente::validarCedula($arr_datos['str_cedula'])) {
            $arr_errores[] = 'La cédula ingresada no es válida';
        }
        
        // Validar nombre
        if (empty($arr_datos['str_nombre'])) {
            $arr_errores[] = 'El nombre es obligatorio';
        } elseif (strlen($arr_datos['str_nombre']) < 2) {
            $arr_errores[] = 'El nombre debe tener al menos 2 caracteres';
        }
        
        // Validar apellido
        if (empty($arr_datos['str_apellido'])) {
            $arr_errores[] = 'El apellido es obligatorio';
        } elseif (strlen($arr_datos['str_apellido']) < 2) {
            $arr_errores[] = 'El apellido debe tener al menos 2 caracteres';
        }
        
        // Validar email (si se proporciona)
        if (!empty($arr_datos['str_email'])) {
            if (!filter_var($arr_datos['str_email'], FILTER_VALIDATE_EMAIL)) {
                $arr_errores[] = 'El email no tiene un formato válido';
            }
        }
        
        // Validar teléfono (si se proporciona)
        if (!empty($arr_datos['str_telefono'])) {
            if (!preg_match('/^[0-9+\-\(\) ]{7,15}$/', $arr_datos['str_telefono'])) {
                $arr_errores[] = 'El teléfono no tiene un formato válido';
            }
        }
        
        return $arr_errores;
    }
    
    /**
     * Exporta clientes a CSV
     */
    public function exportarCSV() {
        $arr_clientes = $this->clienteModel->listar();
        
        // Configurar headers para descarga
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=clientes_' . date('Ymd_His') . '.csv');
        
        // Abrir output
        $output = fopen('php://output', 'w');
        
        // BOM para UTF-8
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
        
        // Encabezados
        fputcsv($output, ['ID', 'Cédula', 'Nombre', 'Apellido', 'Teléfono', 'Email', 'Dirección', 'Fecha Registro']);
        
        // Datos
        foreach ($arr_clientes as $cliente) {
            fputcsv($output, [
                $cliente['id_cliente'],
                $cliente['str_cedula'],
                $cliente['str_nombre'],
                $cliente['str_apellido'],
                $cliente['str_telefono'] ?? '',
                $cliente['str_email'] ?? '',
                $cliente['str_direccion'] ?? '',
                $cliente['datetime_fecha_alta']
            ]);
        }
        
        fclose($output);
        exit;
    }
}
?>