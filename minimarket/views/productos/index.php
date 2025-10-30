<?php require 'views/layout/header.php'; ?>

<div class="container">
    <?php if(isset($_GET['msg'])): ?>
        <?php if($_GET['msg'] == 'created'): ?>
            <div class="alert alert-success">✓ Producto creado exitosamente</div>
        <?php elseif($_GET['msg'] == 'updated'): ?>
            <div class="alert alert-success">✓ Producto actualizado exitosamente</div>
        <?php elseif($_GET['msg'] == 'deleted'): ?>
            <div class="alert alert-success">✓ Producto eliminado exitosamente</div>
        <?php elseif($_GET['msg'] == 'error'): ?>
            <div class="alert alert-danger">✗ Error al procesar la operación</div>
        <?php endif; ?>
    <?php endif; ?>

    <div class="card">
        <div class="card-header">
            <h2>Gestión de Productos</h2>
            <a href="index.php?controller=producto&action=crear" class="btn btn-primary">➕ Nuevo Producto</a>
        </div>
        
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Categoría</th>
                        <th>Precio</th>
                        <th>Stock</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(count($productos) > 0): ?>
                        <?php foreach($productos as $producto): ?>
                            <tr>
                                <td><?php echo $producto['id']; ?></td>
                                <td><strong><?php echo htmlspecialchars($producto['nombre']); ?></strong></td>
                                <td><?php echo htmlspecialchars($producto['categoria_nombre'] ?? 'Sin categoría'); ?></td>
                                <td>$<?php echo number_format($producto['precio'], 2); ?></td>
                                <td><?php echo $producto['stock']; ?></td>
                                <td>
                                    <?php if($producto['stock'] == 0): ?>
                                        <span class="badge badge-danger">Sin stock</span>
                                    <?php elseif($producto['stock'] < 10): ?>
                                        <span class="badge badge-warning">Bajo stock</span>
                                    <?php else: ?>
                                        <span class="badge badge-success">Disponible</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="actions">
                                        <a href="index.php?controller=producto&action=editar&id=<?php echo $producto['id']; ?>" 
                                           class="btn btn-warning btn-sm">✏️ Editar</a>
                                        <button onclick="confirmarEliminacion('index.php?controller=producto&action=eliminar&id=<?php echo $producto['id']; ?>')" 
                                                class="btn btn-danger btn-sm">🗑️ Eliminar</button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" style="text-align: center; padding: 2rem;">
                                No hay productos registrados
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require 'views/layout/footer.php'; ?>