<?php require 'views/layout/header.php'; ?>

<div class="container">
    <?php if(isset($_GET['msg'])): ?>
        <?php if($_GET['msg'] == 'created'): ?>
            <div class="alert alert-success">✓ Cliente creado exitosamente</div>
        <?php elseif($_GET['msg'] == 'updated'): ?>
            <div class="alert alert-success">✓ Cliente actualizado exitosamente</div>
        <?php elseif($_GET['msg'] == 'deleted'): ?>
            <div class="alert alert-success">✓ Cliente eliminado exitosamente</div>
        <?php elseif($_GET['msg'] == 'error'): ?>
            <div class="alert alert-danger">✗ Error al procesar la operación</div>
        <?php endif; ?>
    <?php endif; ?>

    <div class="card">
        <div class="card-header">
            <h2>Gestión de Clientes</h2>
            <a href="index.php?controller=cliente&action=crear" class="btn btn-primary">➕ Nuevo Cliente</a>
        </div>
        
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre Completo</th>
                        <th>Email</th>
                        <th>Teléfono</th>
                        <th>Dirección</th>
                        <th>Fecha Registro</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(count($clientes) > 0): ?>
                        <?php foreach($clientes as $cliente): ?>
                            <tr>
                                <td><?php echo $cliente['id']; ?></td>
                                <td><strong><?php echo htmlspecialchars($cliente['nombre'] . ' ' . $cliente['apellido']); ?></strong></td>
                                <td><?php echo htmlspecialchars($cliente['email']); ?></td>
                                <td><?php echo htmlspecialchars($cliente['telefono']); ?></td>
                                <td><?php echo htmlspecialchars($cliente['direccion']); ?></td>
                                <td><?php echo date('d/m/Y', strtotime($cliente['fecha_registro'])); ?></td>
                                <td>
                                    <div class="actions">
                                        <a href="index.php?controller=cliente&action=editar&id=<?php echo $cliente['id']; ?>" 
                                           class="btn btn-warning btn-sm">✏️ Editar</a>
                                        <button onclick="confirmarEliminacion('index.php?controller=cliente&action=eliminar&id=<?php echo $cliente['id']; ?>')" 
                                                class="btn btn-danger btn-sm">🗑️ Eliminar</button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" style="text-align: center; padding: 2rem;">
                                No hay clientes registrados
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require 'views/layout/footer.php'; ?>