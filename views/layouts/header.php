<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $str_titulo ?? APP_NAME; ?></title>
    <link rel="stylesheet" href="<?php echo APP_URL; ?>assets/css/style.css">
    <link rel="stylesheet" href="<?php echo APP_URL; ?>assets/css/Clientes-lista.css">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar">
        <div class="nav-container">
            <!-- Logo y Título -->
            <div class="nav-brand">
                <svg width="40" height="40" viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="50" cy="50" r="45" fill="#4CAF50"/>
                    <path d="M30 45 L45 60 L70 35" stroke="white" stroke-width="8" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <span class="app-name"><?php echo APP_NAME; ?></span>
            </div>

            <!-- Menu Principal -->
            <ul class="nav-menu">
                <li class="nav-item <?php echo ($str_page ?? '') === 'clientes' ? 'active' : ''; ?>">
                    <a href="<?php echo APP_URL; ?>index.php?controller=cliente&action=listar">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                        </svg>
                        Clientes
                    </a>
                </li>
            </ul>

            <!-- Usuario y Opciones -->
            <div class="nav-user">
                <div class="user-info">
                    <svg width="32" height="32" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                    </svg>
                    <div class="user-details">
                        <span class="user-name"><?php echo $_SESSION['usuario_nombre_completo'] ?? 'Usuario'; ?></span>
                        <span class="user-role"><?php echo ucfirst($_SESSION['usuario_rol'] ?? 'empleado'); ?></span>
                    </div>
                </div>
                <a href="<?php echo APP_URL; ?>index.php?controller=login&action=logout" class="btn-logout" title="Cerrar Sesión">
                    <svg width="20" height="20" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M3 3a1 1 0 00-1 1v12a1 1 0 102 0V4a1 1 0 00-1-1zm10.293 9.293a1 1 0 001.414 1.414l3-3a1 1 0 000-1.414l-3-3a1 1 0 10-1.414 1.414L14.586 9H7a1 1 0 100 2h7.586l-1.293 1.293z" clip-rule="evenodd"/>
                    </svg>
                    Salir
                </a>
            </div>
        </div>
    </nav>

    <!-- Contenedor Principal -->
    <div class="main-container">
        <!-- Mensajes Flash -->
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success">
                <svg width="20" height="20" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                <span><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></span>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-error">
                <svg width="20" height="20" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                </svg>
                <span><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></span>
            </div>
        <?php endif; ?>