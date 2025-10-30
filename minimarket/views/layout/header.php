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
                </ul>
            </nav>
        </div>
    </nav>