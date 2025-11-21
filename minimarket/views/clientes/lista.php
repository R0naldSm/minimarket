<?php
$str_titulo = 'Gestión de Clientes - ' . APP_NAME;
$str_page = 'clientes';
require_once __DIR__ . '/../layouts/header.php';
?>

<div class="page-header">
    <h1>
        <svg width="32" height="32" viewBox="0 0 20 20" fill="currentColor">
            <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
        </svg>
        Gestión de Clientes
    </h1>
    <p class="page-subtitle">Administre la información de sus clientes</p>
</div>

<div class="content-section">
    <!-- Barra de Acciones -->
    <div class="actions-bar">
        <div class="actions-left">
            <a href="<?php echo APP_URL; ?>index.php?controller=cliente&action=crear" class="btn btn-primary">
                <svg width="20" height="20" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"/>
                </svg>
                Nuevo Cliente
            </a>
            
            <a href="<?php echo APP_URL; ?>index.php?controller=cliente&action=exportar" class="btn btn-success">
                <svg width="20" height="20" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd"/>
                </svg>
                Exportar CSV
            </a>
        </div>

        <div class="actions-right">
            <form action="<?php echo APP_URL; ?>index.php" method="GET" class="search-form">
                <input type="hidden" name="controller" value="cliente">
                <input type="hidden" name="action" value="buscar">
                <div class="search-box">
                    <svg width="20" height="20" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"/>
                    </svg>
                    <input 
                        type="text" 
                        name="q" 
                        placeholder="Buscar por cédula, nombre o apellido..."
                        value="<?php echo htmlspecialchars($_GET['q'] ?? ''); ?>"
                    >
                </div>
                <button type="submit" class="btn btn-secondary">Buscar</button>
            </form>
        </div>
    </div>

    <!-- Estadísticas -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon bg-blue">
                <svg width="32" height="32" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                </svg>
            </div>
            <div class="stat-content">
                <h3><?php echo $int_total; ?></h3>
                <p>Clientes Activos</p>
            </div>
        </div>
    </div>

    <!-- Tabla de Clientes -->
    <div class="table-container">
        <?php if (empty($arr_clientes)): ?>
            <div class="empty-state">
                <svg width="64" height="64" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                </svg>
                <h3>No hay clientes registrados</h3>
                <p>Comience agregando su primer cliente haciendo clic en "Nuevo Cliente"</p>
                <a href="<?php echo APP_URL; ?>index.php?controller=cliente&action=crear" class="btn btn-primary">
                    Agregar Primer Cliente
                </a>
            </div>
        <?php else: ?>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Cédula</th>
                        <th>Nombre Completo</th>
                        <th>Teléfono</th>
                        <th>Email</th>
                        <th>Fecha Registro</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($arr_clientes as $cliente): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($cliente['id_cliente']); ?></td>
                            <td>
                                <span class="badge badge-info">
                                    <?php echo htmlspecialchars($cliente['str_cedula']); ?>
                                </span>
                            </td>
                            <td>
                                <strong>
                                    <?php echo htmlspecialchars($cliente['str_nombre'] . ' ' . $cliente['str_apellido']); ?>
                                </strong>
                            </td>
                            <td><?php echo htmlspecialchars($cliente['str_telefono'] ?? '-'); ?></td>
                            <td><?php echo htmlspecialchars($cliente['str_email'] ?? '-'); ?></td>
                            <td><?php echo date('d/m/Y H:i', strtotime($cliente['datetime_fecha_alta'])); ?></td>
                            <td class="actions-cell">
                                <a href="<?php echo APP_URL; ?>index.php?controller=cliente&action=editar&id=<?php echo $cliente['id_cliente']; ?>" 
                                   class="btn-icon btn-edit" 
                                   title="Editar">
                                    <svg width="18" height="18" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/>
                                    </svg>
                                </a>
                                <a href="<?php echo APP_URL; ?>index.php?controller=cliente&action=eliminar&id=<?php echo $cliente['id_cliente']; ?>" 
                                   class="btn-icon btn-delete" 
                                   title="Eliminar"
                                   onclick="return confirm('¿Está seguro de eliminar este cliente?\n\nCliente: <?php echo htmlspecialchars($cliente['str_nombre'] . ' ' . $cliente['str_apellido']); ?>');">
                                    <svg width="18" height="18" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>