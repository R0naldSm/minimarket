<?php require 'views/layout/header.php'; ?>

<div class="container">
    <?php if(isset($_GET['msg'])): ?>
        <?php if($_GET['msg'] == 'created'): ?>
            <div class="alert alert-success">✓ Venta registrada exitosamente</div>
        <?php endif; ?>
    <?php endif; ?>

    <div class="card">
        <div class="card-header">
            <h2>📊 Historial de Ventas</h2>
            <a href="index.php?controller=venta&action=nueva" class="btn btn-primary">🛒 Nueva Venta</a>
        </div>
        
        <div class="table-container">
            <table class="ventas-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Cliente</th>
                        <th>Total</th>
                        <th>Fecha</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(count($ventas) > 0): ?>
                        <?php foreach($ventas as $venta): ?>
                            <tr>
                                <td><strong>#<?php echo str_pad($venta['id'], 5, '0', STR_PAD_LEFT); ?></strong></td>
                                <td>
                                    <?php 
                                    if($venta['cliente_nombre']) {
                                        echo htmlspecialchars($venta['cliente_nombre'] . ' ' . $venta['cliente_apellido']);
                                    } else {
                                        echo '<em>Cliente no especificado</em>';
                                    }
                                    ?>
                                </td>
                                <td class="venta-total">$<?php echo number_format($venta['total'], 2); ?></td>
                                <td><?php echo date('d/m/Y H:i', strtotime($venta['fecha_venta'])); ?></td>
                                <td>
                                    <div class="actions">
                                        <a href="index.php?controller=venta&action=ver&id=<?php echo $venta['id']; ?>" 
                                           class="btn btn-info btn-sm">👁️ Ver Detalle</a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" style="text-align: center; padding: 3rem;">
                                <div class="ventas-empty">
                                    <div class="ventas-empty-icon">🛒</div>
                                    <h3>No hay ventas registradas</h3>
                                    <p>Comienza realizando tu primera venta</p>
                                    <a href="index.php?controller=venta&action=nueva" class="btn btn-primary">🛒 Nueva Venta</a>
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