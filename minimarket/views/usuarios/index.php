<?php require 'views/layout/header.php'; ?>

<div class="container">
    <?php if(isset($_GET['msg'])): ?>
        <?php if($_GET['msg'] == 'created'): ?>
            <div class="alert alert-success">✓ Usuario creado exitosamente</div>
        <?php elseif($_GET['msg'] == 'updated'): ?>
            <div class="alert alert-success">✓ Usuario actualizado exitosamente</div>
        <?php elseif($_GET['msg'] == 'deleted'): ?>
            <div class="alert alert-success">✓ Usuario eliminado exitosamente</div>
        <?php elseif($_GET['msg'] == 'self_delete'): ?>
            <div class="alert alert-danger">✗ No puedes eliminar tu propio usuario</div>
        <?php elseif($_GET['msg'] == 'error'): ?>
            <div class="alert alert-danger">✗ Error al procesar la operación</div>
        <?php endif; ?>
    <?php endif; ?>

    <div class="card">
        <div class="card-header">
            <h2>👥 Gestión de Usuarios</h2>
            <a href="index.php?controller=usuario&action=crear" class="btn btn-primary">➕ Nuevo Usuario</a>
        </div>
        
        <div class="table-container">
            <table class="usuarios-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Usuario</th>
                        <th>Rol</th>
                        <th>Estado</th>
                        <th>Fecha Registro</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(count($usuarios) > 0): ?>
                        <?php foreach($usuarios as $usuario): ?>
                            <tr>
                                <td><?php echo $usuario['id']; ?></td>
                                <td>
                                    <div class="usuario-info">
                                        <div class="usuario-avatar">
                                            <?php echo strtoupper(substr($usuario['nombre'], 0, 1)); ?>
                                        </div>
                                        <strong><?php echo htmlspecialchars($usuario['nombre']); ?></strong>
                                    </div>
                                </td>
                                <td>
                                    <span class="usuario-username">
                                        <?php echo htmlspecialchars($usuario['usuario']); ?>
                                    </span>
                                </td>
                                <td>
                                    <?php
                                    $rol_class = '';
                                    $rol_text = '';
                                    switch($usuario['rol']) {
                                        case 'superusuario':
                                            $rol_class = 'badge-danger';
                                            $rol_text = '👑 Superusuario';
                                            break;
                                        case 'cajero':
                                            $rol_class = 'badge-success';
                                            $rol_text = '💰 Cajero';
                                            break;
                                        case 'vendedor':
                                            $rol_class = 'badge-info';
                                            $rol_text = '🛒 Vendedor';
                                            break;
                                    }
                                    ?>
                                    <span class="badge <?php echo $rol_class; ?>">
                                        <?php echo $rol_text; ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if($usuario['activo']): ?>
                                        <span class="badge badge-success">✓ Activo</span>
                                    <?php else: ?>
                                        <span class="badge badge-danger">✗ Inactivo</span>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo date('d/m/Y', strtotime($usuario['fecha_creacion'])); ?></td>
                                <td>
                                    <div class="actions">
                                        <a href="index.php?controller=usuario&action=editar&id=<?php echo $usuario['id']; ?>" 
                                           class="btn btn-warning btn-sm">✏️ Editar</a>
                                        <?php if($usuario['id'] != $_SESSION['usuario_id']): ?>
                                            <button onclick="confirmarEliminacion('index.php?controller=usuario&action=eliminar&id=<?php echo $usuario['id']; ?>')" 
                                                    class="btn btn-danger btn-sm">🗑️ Eliminar</button>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" style="text-align: center; padding: 3rem;">
                                <div class="usuarios-empty">
                                    <div class="usuarios-empty-icon">👥</div>
                                    <h3>No hay usuarios registrados</h3>
                                    <p>Crea el primer usuario del sistema</p>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require 'views/layout/footer.php'; ?>