-- ============================================
-- BASE DE DATOS: MINIMARKET BENDICIÓN DE DIOS
-- Versión: 1.0
-- Fecha: 07/11/2025
-- ============================================

-- Eliminar base de datos si existe
DROP DATABASE IF EXISTS db_minimarket_bendicion;

-- Crear base de datos
CREATE DATABASE db_minimarket_bendicion
CHARACTER SET utf8mb4
COLLATE utf8mb4_general_ci;

-- Usar la base de datos
USE db_minimarket_bendicion;

-- ============================================
-- TABLA: USUARIOS
-- ============================================
CREATE TABLE usuarios (
    id_usuario INT AUTO_INCREMENT PRIMARY KEY COMMENT 'ID único del usuario',
    str_nombre_usuario VARCHAR(50) NOT NULL UNIQUE COMMENT 'Nombre de usuario para login',
    str_password VARCHAR(255) NOT NULL COMMENT 'Contraseña encriptada',
    str_nombre_completo VARCHAR(100) NOT NULL COMMENT 'Nombre completo del usuario',
    str_email VARCHAR(100) COMMENT 'Correo electrónico',
    enum_rol ENUM('admin', 'empleado') NOT NULL DEFAULT 'empleado' COMMENT 'Rol del usuario',
    bool_activo BOOLEAN NOT NULL DEFAULT TRUE COMMENT 'Estado: TRUE=activo, FALSE=inactivo',
    
    -- Campos de auditoría
    int_id_usuario_alta INT COMMENT 'Usuario que creó el registro',
    datetime_fecha_alta DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Fecha de creación',
    int_id_usuario_modificacion INT COMMENT 'Usuario que modificó',
    datetime_fecha_modificacion DATETIME COMMENT 'Fecha de modificación',
    int_id_usuario_eliminar INT COMMENT 'Usuario que eliminó',
    datetime_fecha_eliminacion DATETIME COMMENT 'Fecha de eliminación',
    
    -- Índices
    INDEX idx_activo (bool_activo),
    INDEX idx_rol (enum_rol),
    
    -- Llaves foráneas (auto-referencias)
    FOREIGN KEY (int_id_usuario_alta) 
        REFERENCES usuarios(id_usuario) 
        ON DELETE RESTRICT 
        ON UPDATE CASCADE,
    FOREIGN KEY (int_id_usuario_modificacion) 
        REFERENCES usuarios(id_usuario) 
        ON DELETE RESTRICT 
        ON UPDATE CASCADE,
    FOREIGN KEY (int_id_usuario_eliminar) 
        REFERENCES usuarios(id_usuario) 
        ON DELETE RESTRICT 
        ON UPDATE CASCADE
        
) ENGINE=InnoDB 
DEFAULT CHARSET=utf8mb4 
COLLATE=utf8mb4_general_ci
COMMENT='Tabla de usuarios del sistema';

-- ============================================
-- TABLA: CLIENTES
-- ============================================
CREATE TABLE clientes (
    id_cliente INT AUTO_INCREMENT PRIMARY KEY COMMENT 'ID único del cliente',
    str_cedula VARCHAR(13) NOT NULL UNIQUE COMMENT 'Cédula o RUC del cliente',
    str_nombre VARCHAR(100) NOT NULL COMMENT 'Nombre(s) del cliente',
    str_apellido VARCHAR(100) NOT NULL COMMENT 'Apellido(s) del cliente',
    str_telefono VARCHAR(15) COMMENT 'Teléfono de contacto',
    str_email VARCHAR(100) COMMENT 'Correo electrónico',
    str_direccion TEXT COMMENT 'Dirección domiciliaria',
    bool_activo BOOLEAN NOT NULL DEFAULT TRUE COMMENT 'Estado: TRUE=activo, FALSE=eliminado',
    
    -- Campos de auditoría
    int_id_usuario_alta INT NOT NULL COMMENT 'Usuario que registró',
    datetime_fecha_alta DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Fecha de registro',
    int_id_usuario_modificacion INT COMMENT 'Usuario que modificó',
    datetime_fecha_modificacion DATETIME COMMENT 'Fecha de modificación',
    int_id_usuario_eliminar INT COMMENT 'Usuario que eliminó',
    datetime_fecha_eliminacion DATETIME COMMENT 'Fecha de eliminación',
    
    -- Índices
    INDEX idx_nombre_apellido (str_nombre, str_apellido),
    INDEX idx_activo (bool_activo),
    INDEX idx_fecha_alta (datetime_fecha_alta),
    INDEX idx_cedula (str_cedula),
    
    -- Llaves foráneas
    FOREIGN KEY (int_id_usuario_alta) 
        REFERENCES usuarios(id_usuario) 
        ON DELETE RESTRICT 
        ON UPDATE CASCADE,
    FOREIGN KEY (int_id_usuario_modificacion) 
        REFERENCES usuarios(id_usuario) 
        ON DELETE RESTRICT 
        ON UPDATE CASCADE,
    FOREIGN KEY (int_id_usuario_eliminar) 
        REFERENCES usuarios(id_usuario) 
        ON DELETE RESTRICT 
        ON UPDATE CASCADE
        
) ENGINE=InnoDB 
DEFAULT CHARSET=utf8mb4 
COLLATE=utf8mb4_general_ci
COMMENT='Tabla de clientes del minimarket';

-- ============================================
-- DATOS INICIALES: USUARIOS
-- ============================================

-- Usuario Administrador
INSERT INTO usuarios (
    str_nombre_usuario, 
    str_password, 
    str_nombre_completo, 
    str_email,
    enum_rol,
    int_id_usuario_alta
) VALUES (
    'admin', 
    MD5('admin123'), 
    'Administrador del Sistema',
    'admin@minimarket.com',
    'admin',
    1
);

-- Usuario Empleado de Ejemplo
INSERT INTO usuarios (
    str_nombre_usuario, 
    str_password, 
    str_nombre_completo, 
    str_email,
    enum_rol,
    int_id_usuario_alta
) VALUES (
    'empleado01', 
    MD5('empleado123'), 
    'Juan Pérez Empleado',
    'jperez@minimarket.com',
    'empleado',
    1
);

-- ============================================
-- DATOS INICIALES: CLIENTES DE EJEMPLO
-- ============================================

INSERT INTO clientes (
    str_cedula,
    str_nombre,
    str_apellido,
    str_telefono,
    str_email,
    str_direccion,
    int_id_usuario_alta
) VALUES 
(
    '0923456789',
    'María',
    'González López',
    '0991234567',
    'maria.gonzalez@email.com',
    'Av. Principal #123, Daule, Guayas',
    1
),
(
    '0912345678',
    'Carlos',
    'Ramírez Pérez',
    '0987654321',
    'carlos.ramirez@email.com',
    'Calle Secundaria #456, Guayaquil, Guayas',
    1
),
(
    '0934567890',
    'Ana',
    'Martínez Silva',
    '0998765432',
    'ana.martinez@email.com',
    'Urbanización Los Pinos, Mz. 5, Villa 10, Samborondón',
    1
),
(
    '0945678901',
    'Luis',
    'Fernández Torres',
    '0976543210',
    'luis.fernandez@email.com',
    'Av. Francisco de Orellana, Km 2.5, Guayaquil',
    1
),
(
    '0956789012',
    'Patricia',
    'López Gómez',
    '0965432109',
    'patricia.lopez@email.com',
    'Cdla. Kennedy Norte, Mz. 10, Solar 5, Guayaquil',
    1
);

-- ============================================
-- PROCEDIMIENTOS ALMACENADOS
-- ============================================

-- Cambiar delimitador
DELIMITER $$

-- ============================================
-- SP: VALIDAR USUARIO
-- Descripción: Valida credenciales de login
-- ============================================
CREATE PROCEDURE sp_validar_usuario(
    IN p_str_usuario VARCHAR(50),
    IN p_str_password VARCHAR(255)
)
BEGIN
    SELECT 
        id_usuario,
        str_nombre_usuario,
        str_nombre_completo,
        str_email,
        enum_rol as rol,
        bool_activo
    FROM usuarios
    WHERE str_nombre_usuario = p_str_usuario
    AND str_password = MD5(p_str_password)
    AND bool_activo = TRUE;
END$$

-- ============================================
-- SP: LISTAR CLIENTES
-- Descripción: Lista todos los clientes activos
-- ============================================
CREATE PROCEDURE sp_listar_clientes()
BEGIN
    SELECT 
        c.id_cliente,
        c.str_cedula,
        c.str_nombre,
        c.str_apellido,
        c.str_telefono,
        c.str_email,
        c.str_direccion,
        c.bool_activo,
        c.datetime_fecha_alta,
        u.str_nombre_completo AS usuario_registro
    FROM clientes c
    INNER JOIN usuarios u ON c.int_id_usuario_alta = u.id_usuario
    WHERE c.bool_activo = TRUE
    ORDER BY c.str_apellido, c.str_nombre;
END$$

-- ============================================
-- SP: INSERTAR CLIENTE
-- Descripción: Inserta un nuevo cliente con validación
-- ============================================
CREATE PROCEDURE sp_insertar_cliente(
    IN p_str_cedula VARCHAR(13),
    IN p_str_nombre VARCHAR(100),
    IN p_str_apellido VARCHAR(100),
    IN p_str_telefono VARCHAR(15),
    IN p_str_email VARCHAR(100),
    IN p_str_direccion TEXT,
    IN p_int_id_usuario_alta INT
)
BEGIN
    DECLARE v_existe INT DEFAULT 0;
    
    -- Verificar si la cédula ya existe
    SELECT COUNT(*) INTO v_existe
    FROM clientes
    WHERE str_cedula = p_str_cedula;
    
    IF v_existe > 0 THEN
        -- Error: Cédula duplicada
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'La cédula ya está registrada en el sistema';
    ELSE
        -- Insertar cliente
        INSERT INTO clientes (
            str_cedula,
            str_nombre,
            str_apellido,
            str_telefono,
            str_email,
            str_direccion,
            int_id_usuario_alta
        ) VALUES (
            p_str_cedula,
            p_str_nombre,
            p_str_apellido,
            p_str_telefono,
            p_str_email,
            p_str_direccion,
            p_int_id_usuario_alta
        );
        
        -- Retornar el ID del nuevo cliente
        SELECT LAST_INSERT_ID() AS id_cliente;
    END IF;
END$$

-- ============================================
-- SP: ACTUALIZAR CLIENTE
-- Descripción: Actualiza datos de un cliente existente
-- ============================================
CREATE PROCEDURE sp_actualizar_cliente(
    IN p_id_cliente INT,
    IN p_str_cedula VARCHAR(13),
    IN p_str_nombre VARCHAR(100),
    IN p_str_apellido VARCHAR(100),
    IN p_str_telefono VARCHAR(15),
    IN p_str_email VARCHAR(100),
    IN p_str_direccion TEXT,
    IN p_int_id_usuario_modificacion INT
)
BEGIN
    DECLARE v_existe INT DEFAULT 0;
    
    -- Verificar si la cédula ya existe en otro cliente
    SELECT COUNT(*) INTO v_existe
    FROM clientes
    WHERE str_cedula = p_str_cedula
    AND id_cliente != p_id_cliente;
    
    IF v_existe > 0 THEN
        -- Error: Cédula duplicada
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'La cédula ya está registrada en otro cliente';
    ELSE
        -- Actualizar cliente
        UPDATE clientes
        SET str_cedula = p_str_cedula,
            str_nombre = p_str_nombre,
            str_apellido = p_str_apellido,
            str_telefono = p_str_telefono,
            str_email = p_str_email,
            str_direccion = p_str_direccion,
            int_id_usuario_modificacion = p_int_id_usuario_modificacion,
            datetime_fecha_modificacion = NOW()
        WHERE id_cliente = p_id_cliente;
        
        -- Retornar número de filas afectadas
        SELECT ROW_COUNT() AS filas_afectadas;
    END IF;
END$$

-- ============================================
-- SP: ELIMINAR CLIENTE
-- Descripción: Eliminación lógica de un cliente
-- ============================================
CREATE PROCEDURE sp_eliminar_cliente(
    IN p_id_cliente INT,
    IN p_int_id_usuario_eliminar INT
)
BEGIN
    -- Eliminación lógica (no física)
    UPDATE clientes
    SET bool_activo = FALSE,
        int_id_usuario_eliminar = p_int_id_usuario_eliminar,
        datetime_fecha_eliminacion = NOW()
    WHERE id_cliente = p_id_cliente;
    
    -- Retornar número de filas afectadas
    SELECT ROW_COUNT() AS filas_afectadas;
END$$

-- ============================================
-- SP: OBTENER CLIENTE POR ID
-- Descripción: Obtiene datos de un cliente específico
-- ============================================
CREATE PROCEDURE sp_obtener_cliente(
    IN p_id_cliente INT
)
BEGIN
    SELECT 
        c.*,
        u.str_nombre_completo AS usuario_registro
    FROM clientes c
    INNER JOIN usuarios u ON c.int_id_usuario_alta = u.id_usuario
    WHERE c.id_cliente = p_id_cliente
    AND c.bool_activo = TRUE;
END$$

-- ============================================
-- SP: BUSCAR CLIENTES
-- Descripción: Busca clientes por criterio
-- ============================================
CREATE PROCEDURE sp_buscar_clientes(
    IN p_criterio VARCHAR(100)
)
BEGIN
    SELECT 
        c.id_cliente,
        c.str_cedula,
        c.str_nombre,
        c.str_apellido,
        c.str_telefono,
        c.str_email,
        c.str_direccion,
        c.datetime_fecha_alta,
        u.str_nombre_completo AS usuario_registro
    FROM clientes c
    INNER JOIN usuarios u ON c.int_id_usuario_alta = u.id_usuario
    WHERE c.bool_activo = TRUE
    AND (
        c.str_cedula LIKE CONCAT('%', p_criterio, '%')
        OR c.str_nombre LIKE CONCAT('%', p_criterio, '%')
        OR c.str_apellido LIKE CONCAT('%', p_criterio, '%')
        OR CONCAT(c.str_nombre, ' ', c.str_apellido) LIKE CONCAT('%', p_criterio, '%')
    )
    ORDER BY c.str_apellido, c.str_nombre;
END$$

-- ============================================
-- SP: CONTAR CLIENTES ACTIVOS
-- Descripción: Retorna el total de clientes activos
-- ============================================
CREATE PROCEDURE sp_contar_clientes_activos()
BEGIN
    SELECT COUNT(*) AS total_clientes
    FROM clientes
    WHERE bool_activo = TRUE;
END$$

-- Restaurar delimitador
DELIMITER ;

-- ============================================
-- VISTAS ÚTILES
-- ============================================

-- Vista: Clientes con información completa
CREATE OR REPLACE VIEW vista_clientes_completo AS
SELECT 
    c.id_cliente,
    c.str_cedula,
    c.str_nombre,
    c.str_apellido,
    CONCAT(c.str_nombre, ' ', c.str_apellido) AS nombre_completo,
    c.str_telefono,
    c.str_email,
    c.str_direccion,
    c.bool_activo,
    c.datetime_fecha_alta,
    u_alta.str_nombre_completo AS creado_por,
    c.datetime_fecha_modificacion,
    u_mod.str_nombre_completo AS modificado_por,
    c.datetime_fecha_eliminacion,
    u_elim.str_nombre_completo AS eliminado_por
FROM clientes c
LEFT JOIN usuarios u_alta ON c.int_id_usuario_alta = u_alta.id_usuario
LEFT JOIN usuarios u_mod ON c.int_id_usuario_modificacion = u_mod.id_usuario
LEFT JOIN usuarios u_elim ON c.int_id_usuario_eliminar = u_elim.id_usuario;

-- Vista: Estadísticas generales
CREATE OR REPLACE VIEW vista_estadisticas AS
SELECT 
    (SELECT COUNT(*) FROM clientes WHERE bool_activo = TRUE) AS total_clientes_activos,
    (SELECT COUNT(*) FROM clientes WHERE bool_activo = FALSE) AS total_clientes_eliminados,
    (SELECT COUNT(*) FROM usuarios WHERE bool_activo = TRUE) AS total_usuarios_activos,
    (SELECT COUNT(*) FROM usuarios WHERE enum_rol = 'admin') AS total_administradores,
    (SELECT COUNT(*) FROM usuarios WHERE enum_rol = 'empleado') AS total_empleados;

-- ============================================
-- TRIGGERS DE AUDITORÍA
-- ============================================

-- Trigger: Auditar inserción de clientes
DELIMITER $$
CREATE TRIGGER trg_cliente_insertar_log
AFTER INSERT ON clientes
FOR EACH ROW
BEGIN
    -- Aquí se podría insertar en una tabla de logs si se requiere
    -- Por ahora solo es un ejemplo de estructura
    -- INSERT INTO auditoria_log (tabla, operacion, id_registro, fecha)
    -- VALUES ('clientes', 'INSERT', NEW.id_cliente, NOW());
    SET @last_operation = CONCAT('Cliente insertado: ', NEW.id_cliente);
END$$
DELIMITER ;

-- ============================================
-- CONSULTAS DE VERIFICACIÓN
-- ============================================

-- Mostrar tablas creadas
SHOW TABLES;

-- Verificar usuarios
SELECT * FROM usuarios;

-- Verificar clientes
SELECT * FROM clientes;

-- Verificar procedimientos almacenados
SHOW PROCEDURE STATUS WHERE Db = 'db_minimarket_bendicion';

-- Verificar vistas
SHOW FULL TABLES WHERE Table_Type = 'VIEW';

-- ============================================
-- INFORMACIÓN DE LA BASE DE DATOS
-- ============================================
SELECT 
    'Base de datos creada exitosamente' AS mensaje,
    DATABASE() AS base_datos,
    VERSION() AS version_mysql,
    NOW() AS fecha_creacion;

-- ============================================
-- CREDENCIALES DE ACCESO
-- ============================================
SELECT 
    '===========================================',
    'CREDENCIALES DE ACCESO AL SISTEMA',
    '===========================================',
    '',
    'Usuario Administrador:',
    '  Usuario: admin',
    '  Contraseña: admin123',
    '',
    'Usuario Empleado:',
    '  Usuario: empleado01',
    '  Contraseña: empleado123',
    '',
    '===========================================';

-- ============================================
-- FIN DEL SCRIPT
-- ============================================