<?php require 'views/layout/header.php'; ?>

<div class="container">
    <?php if(isset($_GET['msg']) && $_GET['msg'] == 'created'): ?>
        <div class="alert alert-success">✓ ¡Venta registrada exitosamente!</div>
    <?php endif; ?>

    <div class="card">
        <div class="card-header">
            <h2>📄 Detalle de Venta #<?php echo str_pad($venta['id'], 5, '0', STR_PAD_LEFT); ?></h2>
            <div>
                <a href="index.php?controller=venta&action=nueva" class="btn btn-success">🛒 Nueva Venta</a>
                <a href="index.php?controller=venta&action=index" class="btn btn-primary">← Volver</a>
            </div>
        </div>
        
        <!-- Información de la venta -->
        <div class="venta-info-grid">
            <div class="venta-info-card">
                <div class="info-label">📅 Fecha</div>
                <div class="info-value"><?php echo date('d/m/Y H:i:s', strtotime($venta['fecha_venta'])); ?></div>
            </div>
            
            <div class="venta-info-card">
                <div class="info-label">👤 Cliente</div>
                <div class="info-value">
                    <?php 
                    if($venta['cliente_nombre']) {
                        echo htmlspecialchars($venta['cliente_nombre'] . ' ' . $venta['cliente_apellido']);
                    } else {
                        echo '<em>No especificado</em>';
                    }
                    ?>
                </div>
            </div>
            
            <?php if($venta['cliente_email']): ?>
            <div class="venta-info-card">
                <div class="info-label">📧 Email</div>
                <div class="info-value"><?php echo htmlspecialchars($venta['cliente_email']); ?></div>
            </div>
            <?php endif; ?>
            
            <?php if($venta['cliente_telefono']): ?>
            <div class="venta-info-card">
                <div class="info-label">📞 Teléfono</div>
                <div class="info-value"><?php echo htmlspecialchars($venta['cliente_telefono']); ?></div>
            </div>
            <?php endif; ?>
        </div>
        
        <!-- Detalle de productos -->
        <h3 style="margin-top: 2rem; margin-bottom: 1rem;">📦 Productos</h3>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Precio Unitario</th>
                        <th>Cantidad</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($detalles as $detalle): ?>
                        <tr>
                            <td><strong><?php echo htmlspecialchars($detalle['producto_nombre']); ?></strong></td>
                            <td>$<?php echo number_format($detalle['precio_unitario'], 2); ?></td>
                            <td><?php echo $detalle['cantidad']; ?></td>
                            <td><strong>$<?php echo number_format($detalle['subtotal'], 2); ?></strong></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr class="total-row">
                        <td colspan="3"><strong>TOTAL</strong></td>
                        <td><strong class="total-final">$<?php echo number_format($venta['total'], 2); ?></strong></td>
                    </tr>
                </tfoot>
            </table>
        </div>
        
        <!-- Botón de impresión -->
        <div style="margin-top: 2rem; text-align: center;">
            <button onclick="window.print()" class="btn btn-info">🖨️ Imprimir Recibo</button>
        </div>
    </div>
</div>

<style>
@media print {
    .navbar, .btn, .card-header div { display: none !important; }
    body { background: white; }
    .card { box-shadow: none; border: 1px solid #000; }
}
</style>

<?php require 'views/layout/footer.php'; ?>