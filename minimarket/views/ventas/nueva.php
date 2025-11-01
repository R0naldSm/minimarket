<?php require 'views/layout/header.php'; ?>

<div class="container">
    <?php if(isset($_GET['msg'])): ?>
        <?php if($_GET['msg'] == 'added'): ?>
            <div class="alert alert-success">✓ Producto agregado al carrito</div>
        <?php elseif($_GET['msg'] == 'removed'): ?>
            <div class="alert alert-success">✓ Producto eliminado del carrito</div>
        <?php elseif($_GET['msg'] == 'no_stock'): ?>
            <div class="alert alert-danger">✗ Stock insuficiente</div>
        <?php elseif($_GET['msg'] == 'empty_cart'): ?>
            <div class="alert alert-warning">⚠ El carrito está vacío</div>
        <?php elseif($_GET['msg'] == 'cleared'): ?>
            <div class="alert alert-success">✓ Carrito limpiado</div>
        <?php elseif($_GET['msg'] == 'error'): ?>
            <div class="alert alert-danger">✗ Error al procesar la venta</div>
        <?php endif; ?>
    <?php endif; ?>

    <div class="venta-header">
        <h1>🛒 Nueva Venta</h1>
        <a href="index.php?controller=venta&action=index" class="btn btn-primary">← Volver</a>
    </div>

    <div class="venta-grid">
        <!-- Panel de productos -->
        <div class="card productos-panel">
            <div class="card-header">
                <h3>📦 Seleccionar Productos</h3>
            </div>
            
            <form method="POST" action="index.php?controller=venta&action=agregarCarrito" class="agregar-producto-form">
                <div class="form-group">
                    <label for="producto_id">Producto *</label>
                    <select id="producto_id" name="producto_id" class="form-control" required onchange="actualizarStock(this)">
                        <option value="">Seleccione un producto</option>
                        <?php foreach($productos as $producto): ?>
                            <option value="<?php echo $producto['id']; ?>" 
                                    data-precio="<?php echo $producto['precio']; ?>"
                                    data-stock="<?php echo $producto['stock']; ?>">
                                <?php echo htmlspecialchars($producto['nombre']); ?> 
                                - $<?php echo number_format($producto['precio'], 2); ?> 
                                (Stock: <?php echo $producto['stock']; ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="cantidad">Cantidad *</label>
                    <input type="number" id="cantidad" name="cantidad" class="form-control" 
                           min="1" value="1" required>
                    <small id="stock-disponible" class="text-muted"></small>
                </div>
                
                <button type="submit" class="btn btn-success">➕ Agregar al Carrito</button>
            </form>
        </div>

        <!-- Carrito -->
        <div class="card carrito-panel">
            <div class="card-header">
                <h3>🛒 Carrito de Compras</h3>
                <?php if(!empty($_SESSION['carrito'])): ?>
                    <a href="index.php?controller=venta&action=limpiarCarrito" 
                       class="btn btn-danger btn-sm" 
                       onclick="return confirm('¿Limpiar todo el carrito?')">🗑️ Limpiar</a>
                <?php endif; ?>
            </div>
            
            <?php if(!empty($_SESSION['carrito'])): ?>
                <div class="carrito-items">
                    <?php 
                    $total = 0;
                    foreach($_SESSION['carrito'] as $index => $item): 
                        $total += $item['subtotal'];
                    ?>
                        <div class="carrito-item">
                            <div class="item-info">
                                <div class="item-nombre"><?php echo htmlspecialchars($item['nombre']); ?></div>
                                <div class="item-detalles">
                                    $<?php echo number_format($item['precio_unitario'], 2); ?> 
                                    x <?php echo $item['cantidad']; ?> = 
                                    <strong>$<?php echo number_format($item['subtotal'], 2); ?></strong>
                                </div>
                            </div>
                            <a href="index.php?controller=venta&action=eliminarCarrito&index=<?php echo $index; ?>" 
                               class="btn-remove" 
                               onclick="return confirm('¿Eliminar este producto?')">✕</a>
                        </div>
                    <?php endforeach; ?>
                </div>
                
                <div class="carrito-total">
                    <div class="total-label">TOTAL:</div>
                    <div class="total-monto">$<?php echo number_format($total, 2); ?></div>
                </div>
                
                <!-- Formulario finalizar venta -->
                <form method="POST" action="index.php?controller=venta&action=procesar" class="finalizar-venta-form">
                    <div class="form-group">
                        <label for="cliente_id">Cliente *</label>
                        <select id="cliente_id" name="cliente_id" class="form-control" required>
                            <option value="">Seleccione un cliente</option>
                            <?php foreach($clientes as $cliente): ?>
                                <option value="<?php echo $cliente['id']; ?>">
                                    <?php echo htmlspecialchars($cliente['nombre'] . ' ' . $cliente['apellido']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <button type="submit" class="btn btn-primary btn-finalizar">
                        💳 Finalizar Venta
                    </button>
                </form>
            <?php else: ?>
                <div class="carrito-vacio">
                    <div class="icono-vacio">🛒</div>
                    <p>El carrito está vacío</p>
                    <small>Agrega productos para comenzar</small>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
function actualizarStock(select) {
    const option = select.options[select.selectedIndex];
    const stock = option.getAttribute('data-stock');
    const stockDiv = document.getElementById('stock-disponible');
    const cantidadInput = document.getElementById('cantidad');
    
    if (stock) {
        stockDiv.textContent = `Stock disponible: ${stock} unidades`;
        cantidadInput.max = stock;
    } else {
        stockDiv.textContent = '';
        cantidadInput.max = '';
    }
}
</script>

<?php require 'views/layout/footer.php'; ?>