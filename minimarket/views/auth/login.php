<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - Minimarket</title>
    <link rel="stylesheet" href="assets/css/base.css">
    <link rel="stylesheet" href="assets/css/login.css">
</head>
<body class="login-body">
    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <div class="login-icon">🏪</div>
                <h1>Minimarket System</h1>
                <p>Inicia sesión para continuar</p>
            </div>

            <?php if(isset($_GET['error'])): ?>
                <?php if($_GET['error'] == 'credentials'): ?>
                    <div class="alert alert-danger">
                        ✗ Usuario o contraseña incorrectos
                    </div>
                <?php elseif($_GET['error'] == 'auth_required'): ?>
                    <div class="alert alert-warning">
                        ⚠ Debes iniciar sesión para acceder
                    </div>
                <?php elseif($_GET['error'] == 'permission_denied'): ?>
                    <div class="alert alert-danger">
                        ✗ No tienes permisos para acceder a esa sección
                    </div>
                <?php endif; ?>
            <?php endif; ?>

            <?php if(isset($_GET['msg']) && $_GET['msg'] == 'logout'): ?>
                <div class="alert alert-success">
                    ✓ Sesión cerrada exitosamente
                </div>
            <?php endif; ?>

            <form method="POST" action="index.php?controller=auth&action=procesarLogin" class="login-form">
                <div class="form-group">
                    <label for="usuario">
                        <span class="icon">👤</span>
                        Usuario
                    </label>
                    <input type="text" 
                           id="usuario" 
                           name="usuario" 
                           class="form-control" 
                           placeholder="Ingresa tu usuario"
                           required 
                           autofocus>
                </div>

                <div class="form-group">
                    <label for="password">
                        <span class="icon">🔒</span>
                        Contraseña
                    </label>
                    <input type="password" 
                           id="password" 
                           name="password" 
                           class="form-control" 
                           placeholder="Ingresa tu contraseña"
                           required>
                </div>

                <button type="submit" class="btn btn-primary btn-login">
                    Iniciar Sesión
                </button>
            </form>

            <div class="login-footer">
                <div class="usuarios-demo">
                    <h4>👥 Usuarios de Prueba:</h4>
                    <div class="usuario-demo">
                        <strong>Administrador:</strong> admin / admin123
                    </div>
                    <div class="usuario-demo">
                        <strong>Cajero:</strong> cajero1 / admin123
                    </div>
                    <div class="usuario-demo">
                        <strong>Vendedor:</strong> vendedor1 / admin123
                    </div>
                </div>
            </div>
        </div>

        <div class="login-info">
            <p>Sistema de Gestión de Minimarket v1.0</p>
        </div>
    </div>
</body>
</html>