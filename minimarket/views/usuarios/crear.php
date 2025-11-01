<?php require 'views/layout/header.php'; ?>

<div class="container">
    <div class="card">
        <div class="card-header">
            <h2>➕ Nuevo Usuario</h2>
            <a href="index.php?controller=usuario&action=index" class="btn btn-primary">← Volver</a>
        </div>
        
        <?php if(isset($error)): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <form method="POST" action="index.php?controller=usuario&action=crear" class="usuario-form">
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
                           placeholder="Ej: Juan Pérez"
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
                           placeholder="Ej: jperez"
                           required>
                    <small class="text-muted">Sin espacios, solo letras y números</small>
                </div>
            </div>
            
            <div class="form-grid">
                <div class="form-group">
                    <label for="password">
                        <span class="icon">🔒</span>
                        Contraseña *
                    </label>
                    <input type="password" 
                           id="password" 
                           name="password" 
                           class="form-control" 
                           placeholder="Mínimo 6 caracteres"
                           minlength="6"
                           required>
                </div>
                
                <div class="form-group">
                    <label for="rol">
                        <span class="icon">🎭</span>
                        Rol *
                    </label>
                    <select id="rol" name="rol" class="form-control" required>
                        <option value="">Seleccione un rol</option>
                        <option value="superusuario">👑 Superusuario (Acceso total)</option>
                        <option value="cajero">💰 Cajero (Ventas y productos)</option>
                        <option value="vendedor">🛒 Vendedor (Solo ventas)</option>
                    </select>
                </div>
            </div>
            
            <div class="form-group">
                <label class="checkbox-label">
                    <input type="checkbox" name="activo" checked>
                    <span>✓ Usuario activo</span>
                </label>
            </div>
            
            <div class="info-box">
                <h4>ℹ️ Información sobre los roles:</h4>
                <ul>
                    <li><strong>Superusuario:</strong> Acceso completo al sistema, incluyendo gestión de usuarios</li>
                    <li><strong>Cajero:</strong> Puede realizar ventas, gestionar productos, categorías y clientes</li>
                    <li><strong>Vendedor:</strong> Solo puede realizar ventas y consultar información</li>
                </ul>
            </div>
            
            <div style="display: flex; gap: 1rem; margin-top: 2rem;">
                <button type="submit" class="btn btn-success">💾 Crear Usuario</button>
                <a href="index.php?controller=usuario&action=index" class="btn btn-danger">✗ Cancelar</a>
            </div>
        </form>
    </div>
</div>

<?php require 'views/layout/footer.php'; ?>