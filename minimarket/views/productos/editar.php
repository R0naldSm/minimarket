<?php require 'views/layout/header.php'; ?>

<div class="container">
    <div class="card">
        <div class="card-header">
            <h2>Editar Producto</h2>
            <a href="index.php?controller=producto&action=index" class="btn btn-primary">← Volver</a>
        </div>
        
        <?php if(isset($error)): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <form method="POST" action="index.php?controller=producto&action=editar&id=<?php echo $this->producto->id; ?>">
            <div class="form-group">
                <label for="nombre">Nombre *</label>
                <input type="text" id="nombre" name="nombre" class="form-control" 
                       value="<?php echo htmlspecialchars($this->producto->nombre); ?>" required>
            </div>
            
            <div class="form-group">
                <label for="descripcion">Descripción</label>
                <textarea id="descripcion" name="descripcion" class="form-control" rows="3"><?php echo htmlspecialchars($this->producto->descripcion); ?></textarea>
            </div>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div class="form-group">
                    <label for="precio">Precio *</label>
                    <input type="number" id="precio" name="precio" class="form-control" 
                           step="0.01" min="0" value="<?php echo $this->producto->precio; ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="stock">Stock *</label>
                    <input type="number" id="stock" name="stock" class="form-control" 
                           min="0" value="<?php echo $this->producto->stock; ?>" required>
                </div>
            </div>
            
            <div class="form-group">
                <label for="categoria_id">Categoría *</label>
                <select id="categoria_id" name="categoria_id" class="form-control" required>
                    <option value="">Seleccione una categoría</option>
                    <?php foreach($categorias as $categoria): ?>
                        <option value="<?php echo $categoria['id']; ?>" 
                                <?php echo ($this->producto->categoria_id == $categoria['id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($categoria['nombre']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div style="display: flex; gap: 1rem; margin-top: 2rem;">
                <button type="submit" class="btn btn-success">💾 Actualizar</button>
                <a href="index.php?controller=producto&action=index" class="btn btn-danger">✗ Cancelar</a>
            </div>
        </form>
    </div>
</div>

<?php require 'views/layout/footer.php'; ?>