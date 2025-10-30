<?php 
// Asegúrate de que la ruta del header sea correcta
require_once 'views/layout/header.php'; 
?>

<div class="container">
    <?php if(isset($_GET['msg'])): ?>
        <?php if($_GET['msg'] == 'created'): ?>
            <div class="alert alert-success">✓ Categoría creada exitosamente</div>
        <?php elseif($_GET['msg'] == 'updated'): ?>
            <div class="alert alert-success">✓ Categoría actualizada exitosamente</div>
        <?php elseif($_GET['msg'] == 'deleted'): ?>
            <div class="alert alert-success">✓ Categoría eliminada exitosamente</div>
        <?php elseif($_GET['msg'] == 'error'): ?>
            <div class="alert alert-danger">✗ Error al procesar la operación</div>
        <?php endif; ?>
    <?php endif; ?>

    <div class="card">
        <div class="card-header">
            <h2>Gestión de Categorías</h2>
            <a href="index.php?controller=categoria&action=crear" class="btn btn-primary">➕ Nueva Categoría</a>
        </div>
        
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th>Fecha Creación</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(count($categorias) > 0): ?>
                        <?php foreach($categorias as $categoria): ?>
                            <tr>
                                <td><?php echo $categoria['id']; ?></td>
                                <td><strong><?php echo htmlspecialchars($categoria['nombre']); ?></strong></td>
                                <td><?php echo htmlspecialchars($categoria['descripcion']); ?></td>
                                <td><?php echo date('d/m/Y', strtotime($categoria['fecha_creacion'])); ?></td>
                                <td>
                                    <div class="actions">
                                        <a href="index.php?controller=categoria&action=editar&id=<?php echo $categoria['id']; ?>" 
                                           class="btn btn-warning btn-sm">✏️ Editar</a>
                                        <button onclick="confirmarEliminacion('index.php?controller=categoria&action=eliminar&id=<?php echo $categoria['id']; ?>')" 
                                                class="btn btn-danger btn-sm">🗑️ Eliminar</button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" style="text-align: center; padding: 2rem;">
                                No hay categorías registradas
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
function confirmarEliminacion(url) {
    if (confirm('¿Estás seguro de que deseas eliminar esta categoría?')) {
        window.location.href = url;
    }
}
</script>

<?php require_once 'views/layout/footer.php'; ?>