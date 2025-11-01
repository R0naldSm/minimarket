<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema Minimarket</title>
    
    <!-- CSS Base (siempre se carga) -->
    <link rel="stylesheet" href="assets/css/base.css">
    
    <!-- CSS específicos según la sección -->
    <?php
    $controller = isset($_GET['controller']) ? $_GET['controller'] : 'home';
    
    switch($controller) {
        case 'home':
            echo '<link rel="stylesheet" href="assets/css/home.css">';
            break;
        case 'categoria':
            echo '<link rel="stylesheet" href="assets/css/categorias.css">';
            break;
        case 'producto':
            echo '<link rel="stylesheet" href="assets/css/productos.css">';
            break;
        case 'cliente':
            echo '<link rel="stylesheet" href="assets/css/clientes.css">';
            break;
        case 'venta':
            echo '<link rel="stylesheet" href="assets/css/ventas.css">';
            break;
        case 'usuario':
            echo '<link rel="stylesheet" href="assets/css/usuarios.css">';
            break;
    }
    ?>
</head>
<body>
    <nav class="navbar">
        <div class="container">
            <h1>🏪 Minimarket</h1>
            <nav>
                <ul>
                    <li><a href="index.php?controller=home&action=index">Inicio</a></li>
                    <li><a href="index.php?controller=categoria&action=index">Categorías</a></li>
                    <li><a href="index.php?controller=producto&action=index">Productos</a></li>
                    <li><a href="index.php?controller=cliente&action=index">Clientes</a></li>
                    <li><a href="index.php?controller=venta&action=index">Ventas</a></li>
                    <?php if(isset($_SESSION['usuario_rol']) && $_SESSION['usuario_rol'] == 'superusuario'): ?>
                        <li><a href="index.php?controller=usuario&action=index">Usuarios</a></li>
                    <?php endif; ?>
                    <li class="user-menu">
                        <span class="user-name">👤 <?php echo htmlspecialchars($_SESSION['usuario_nombre'] ?? 'Usuario'); ?></span>
                        <a href="index.php?controller=auth&action=logout" class="btn-logout">Salir</a>
                    </li>
                </ul>
            </nav>
        </div>
    </nav>