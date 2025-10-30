<?php require 'views/layout/header.php'; ?>

<div class="container">
    <div class="card">
        <div class="card-header">
            <h2>Panel de Control</h2>
        </div>
        
        <div class="stats-grid">
            <div class="stat-card">
                <h3>Total Productos</h3>
                <div class="stat-value"><?php echo $stats['total_productos']; ?></div>
            </div>
            
            <div class="stat-card">
                <h3>Total Categorías</h3>
                <div class="stat-value"><?php echo $stats['total_categorias']; ?></div>
            </div>
            
            <div class="stat-card">
                <h3>Total Clientes</h3>
                <div class="stat-value"><?php echo $stats['total_clientes']; ?></div>
            </div>
            
            <div class="stat-card" style="border-left-color: <?php echo $stats['productos_bajo_stock'] > 0 ? 'var(--danger-color)' : 'var(--success-color)'; ?>">
                <h3>Productos Bajo Stock</h3>
                <div class="stat-value" style="color: <?php echo $stats['productos_bajo_stock'] > 0 ? 'var(--danger-color)' : 'var(--success-color)'; ?>">
                    <?php echo $stats['productos_bajo_stock']; ?>
                </div>
            </div>
        </div>
        
        <div style="margin-top: 2rem;">
            <h3 style="margin-bottom: 1rem;">Accesos Rápidos</h3>
            <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
                <a href="index.php?controller=producto&action=crear" class="btn btn-primary">➕ Nuevo Producto</a>
                <a href="index.php?controller=categoria&action=crear" class="btn btn-success">📁 Nueva Categoría</a>
                <a href="index.php?controller=cliente&action=crear" class="btn btn-warning">👤 Nuevo Cliente</a>
            </div>
        </div>
    </div>
</div>

<?php require 'views/layout/footer.php'; ?>