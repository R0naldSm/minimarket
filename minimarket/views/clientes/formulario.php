<?php
$str_titulo = ($str_accion === 'crear' ? 'Nuevo Cliente' : 'Editar Cliente') . ' - ' . APP_NAME;
$str_page = 'clientes';

// Obtener datos del formulario (si existen)
$arr_datos = $_SESSION['datos_formulario'] ?? [];
unset($_SESSION['datos_formulario']);

// Si es edición, usar datos del cliente
if ($str_accion === 'editar' && isset($arr_cliente)) {
    $arr_datos = array_merge($arr_cliente, $arr_datos);
}

require_once __DIR__ . '/../layouts/header.php';
?>

<div class="page-header">
    <div class="header-content">
        <h1>
            <svg width="32" height="32" viewBox="0 0 20 20" fill="currentColor">
                <?php if ($str_accion === 'crear'): ?>
                    <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"/>
                <?php else: ?>
                    <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/>
                <?php endif; ?>
            </svg>
            <?php echo $str_accion === 'crear' ? 'Nuevo Cliente' : 'Editar Cliente'; ?>
        </h1>
        <a href="<?php echo APP_URL; ?>index.php?controller=cliente&action=listar" class="btn btn-secondary">
            <svg width="20" height="20" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd"/>
            </svg>
            Volver
        </a>
    </div>
</div>

<div class="content-section">
    <div class="form-container">
        <form action="<?php echo APP_URL; ?>index.php?controller=cliente&action=guardar" method="POST" class="cliente-form" id="formCliente">
            <?php if ($str_accion === 'editar'): ?>
                <input type="hidden" name="id_cliente" value="<?php echo $arr_datos['id_cliente'] ?? ''; ?>">
            <?php endif; ?>

            <div class="form-section">
                <h3 class="section-title">
                    <svg width="24" height="24" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                    </svg>
                    Información Personal
                </h3>

                <div class="form-row">
                    <div class="form-group">
                        <label for="str_cedula" class="required">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"/>
                            </svg>
                            Cédula / RUC
                        </label>
                        <input 
                            type="text" 
                            id="str_cedula" 
                            name="str_cedula" 
                            class="form-control"
                            placeholder="0923456789 o 0923456789001"
                            value="<?php echo htmlspecialchars($arr_datos['str_cedula'] ?? ''); ?>"
                            required
                            maxlength="13"
                            pattern="[0-9]{10,13}"
                            title="Ingrese 10 dígitos para cédula o 13 para RUC"
                        >
                        <small class="form-help">Ingrese 10 dígitos para cédula o 13 para RUC</small>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="str_nombre" class="required">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                            </svg>
                            Nombre(s)
                        </label>
                        <input 
                            type="text" 
                            id="str_nombre" 
                            name="str_nombre" 
                            class="form-control"
                            placeholder="Ej: María José"
                            value="<?php echo htmlspecialchars($arr_datos['str_nombre'] ?? ''); ?>"
                            required
                            maxlength="100"
                        >
                    </div>

                    <div class="form-group">
                        <label for="str_apellido" class="required">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                            </svg>
                            Apellido(s)
                        </label>
                        <input 
                            type="text" 
                            id="str_apellido" 
                            name="str_apellido" 
                            class="form-control"
                            placeholder="Ej: González López"
                            value="<?php echo htmlspecialchars($arr_datos['str_apellido'] ?? ''); ?>"
                            required
                            maxlength="100"
                        >
                    </div>
                </div>
            </div>

            <div class="form-section">
                <h3 class="section-title">
                    <svg width="24" height="24" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"/>
                    </svg>
                    Información de Contacto
                </h3>

                <div class="form-row">
                    <div class="form-group">
                        <label for="str_telefono">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"/>
                            </svg>
                            Teléfono
                        </label>
                        <input 
                            type="tel" 
                            id="str_telefono" 
                            name="str_telefono" 
                            class="form-control"
                            placeholder="0991234567 o 042-123456"
                            value="<?php echo htmlspecialchars($arr_datos['str_telefono'] ?? ''); ?>"
                            maxlength="15"
                            pattern="[0-9+\-\(\) ]{7,15}"
                        >
                        <small class="form-help">Opcional. Formato: 0991234567 o 042-123456</small>
                    </div>

                    <div class="form-group">
                        <label for="str_email">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
                                <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
                            </svg>
                            Email
                        </label>
                        <input 
                            type="email" 
                            id="str_email" 
                            name="str_email" 
                            class="form-control"
                            placeholder="ejemplo@correo.com"
                            value="<?php echo htmlspecialchars($arr_datos['str_email'] ?? ''); ?>"
                            maxlength="100"
                        >
                        <small class="form-help">Opcional</small>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group full-width">
                        <label for="str_direccion">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                            </svg>
                            Dirección
                        </label>
                        <textarea 
                            id="str_direccion" 
                            name="str_direccion" 
                            class="form-control"
                            rows="3"
                            placeholder="Ej: Av. Principal #123, entre Calle A y Calle B, Daule"
                            maxlength="500"
                        ><?php echo htmlspecialchars($arr_datos['str_direccion'] ?? ''); ?></textarea>
                        <small class="form-help">Opcional. Máximo 500 caracteres</small>
                    </div>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary btn-lg">
                    <svg width="20" height="20" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                    </svg>
                    <?php echo $str_accion === 'crear' ? 'Registrar Cliente' : 'Actualizar Cliente'; ?>
                </button>
                <a href="<?php echo APP_URL; ?>index.php?controller=cliente&action=listar" class="btn btn-secondary btn-lg">
                    <svg width="20" height="20" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                    </svg>
                    Cancelar
                </a>
            </div>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>