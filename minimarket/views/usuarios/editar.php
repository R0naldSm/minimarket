<?php require 'views/layout/header.php'; ?>

<div class="container">
    <div class="card">
        <div class="card-header">
            <h2>✏️ Editar Usuario</h2>
            <a href="index.php?controller=usuario&action=index" class="btn btn-primary">← Volver</a>
        </div>
        
        <?php if(isset($error)): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <form method="POST" action="index.php?controller=usuario&action=editar&id=<?php echo $this->usuario->id; ?>" class="usuario-form">
            <div class="form-grid">
                <div class="form-group">
                    <label for="nombre">
                        <span class="icon">👤</span>
                        Nombre Completo *
                    </label>
                    <input type="text" 
                           id="nombre" 
                           name="nombre" 
                           class="form-control" 
                           value="<?php echo htmlspecialchars($this->usuario->nombre); ?>"
                           required>
                </div>
                
                <div class="form-group">
                    <label for="usuario">
                        <span class="icon">🔑</span>
                        Nombre de Usuario *
                    </label>
                    <input type="text" 
                           id="usuario" 
                           name="usuario" 
                           class="form-control" 
                           value="<?php echo htmlspecialchars($this->usuario->usuario); ?>"
                           required>
                </div>
            </div>
            
            <div class="form-grid">
                <div class="form-group">
                    <label for="password">
                        <span class="icon">🔒</span>
                        Nueva Contraseña
                    </label>
                    <input type="password" 
                           id="password" 
                           name="password" 
                           class="form-control" 
                           placeholder="Dejar en blanco para mantener la actual"
                           minlength="6">
                    <small class="text-muted">Solo completa si deseas cambiar la contraseña</small>
                </div>
                
                <div class="form-group">
                    <label for="rol">
                        <span class="icon">🎭</span>
                        Rol *
                    </label>
                    <select id="rol" name="rol" class="form-control" required>
                        <option value="superusuario" <?php echo ($this->usuario->rol == 'superusuario') ? 'selected' : ''; ?>>
                            👑 Superusuario
                        </option>
                        <option value="cajero" <?php echo ($this->usuario->rol == 'cajero') ? 'selected' : ''; ?>>
                            💰 Cajero
                        </option>
                        <option value="vendedor" <?php echo ($this->usuario->rol == 'vendedor') ? 'selected' : ''; ?>>
                            🛒 Vendedor
                        </option>
                    </select>
                </div>
            </div>
            
            <div class="form-group">
                <label class="checkbox-label">
                    <input type="checkbox" name="activo" <?php echo $this->usuario->activo ? 'checked' : ''; ?>>
                    <span>✓ Usuario activo</span>
                </label>
                <small class="text-muted">Los usuarios inactivos no podrán iniciar sesión</small>
            </div>
            
            <?php if($this->usuario->id == $_SESSION['usuario_id']): ?>
                <div class="alert alert-warning">
                    ⚠️ <strong>Nota:</strong> Estás editando tu propio usuario. Ten cuidado al cambiar el rol o el estado.
                </div>
            <?php endif; ?>
            
            <div class="usuario-info-card">
                <h4>📋 Información del usuario</h4>
                <div class="info-grid">
                    <div>
                        <strong>ID:</strong> #<?php echo $this->usuario->id; ?>
                    </div>
                    <div>
                        <strong>Fecha de creación:</strong> 
                        <?php echo date('d/m/Y H:i', strtotime($this->usuario->fecha_creacion)); ?>
                    </div>
                </div>
            </div>
            
            <div style="display: flex; gap: 1rem; margin-top: 2rem;">
                <button type="submit" class="btn btn-success">💾 Actualizar Usuario</button>
                <a href="index.php?controller=usuario&action=index" class="btn btn-danger">✗ Cancelar</a>
            </div>
        </form>
    </div>
</div>

<?php require 'views/layout/footer.php'; ?>