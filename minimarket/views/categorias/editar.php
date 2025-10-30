<?php require 'views/layout/header.php'; ?>

<div class="container">
    <div class="card">
        <div class="card-header">
            <h2>Editar Categoría</h2>
            <a href="index.php?controller=categoria&action=index" class="btn btn-primary">← Volver</a>
        </div>
        
        <?php if(isset($error)): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <form method="POST" action="index.php?controller=categoria&action=editar&id=<?php echo $this->categoria->id; ?>">
            <div class="form-group">
                <label for="nombre">Nombre *</label>
                <input type="text" id="nombre" name="nombre" class="form-control" 
                       value="<?php echo htmlspecialchars($this->categoria->nombre); ?>" required>
            </div>
            
            <div class="form-group">
                <label for="descripcion">Descripción</label>
                <textarea id="descripcion" name="descripcion" class="form-control" rows="4"><?php echo htmlspecialchars($this->categoria->descripcion); ?></textarea>
            </div>
            
            <div style="display: flex; gap: 1rem;">
                <button type="submit" class="btn btn-success">💾 Actualizar</button>
                <a href="index.php?controller=categoria&action=index" class="btn btn-danger">✗ Cancelar</a>
            </div>
        </form>
    </div>
</div>

<?php require 'views/layout/footer.php'; ?>