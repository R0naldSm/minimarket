# 🏪 Sistema Minimarket "Bendición de Dios"

Sistema de gestión de clientes para minimarket desarrollado en PHP con arquitectura MVC.

## 📋 Características

- ✅ Sistema de autenticación (Login/Logout)
- ✅ CRUD completo de clientes
- ✅ Búsqueda de clientes por cédula, nombre o apellido
- ✅ Validación de cédula ecuatoriana
- ✅ Exportación de clientes a CSV
- ✅ Procedimientos almacenados en MySQL
- ✅ Auditoría completa (quién y cuándo modificó cada registro)
- ✅ Eliminación lógica (no se borran datos físicamente)
- ✅ Interfaz responsive (móvil y escritorio)
- ✅ Validaciones en cliente y servidor

## 🛠️ Tecnologías Utilizadas

- **Backend:** PHP 8.x
- **Base de Datos:** MySQL 8.0
- **Frontend:** HTML5, CSS3, JavaScript (Vanilla)
- **Servidor:** Apache 2.4 (XAMPP)
- **Arquitectura:** MVC (Modelo-Vista-Controlador)

## 📦 Requisitos Previos

- XAMPP 8.0 o superior (incluye PHP + MySQL + Apache)
- Navegador web moderno (Chrome, Firefox, Edge)
- Editor de código (Visual Studio Code recomendado)

## 🚀 Instalación

### Paso 1: Instalar XAMPP

1. Descargar XAMPP desde: https://www.apachefriends.org/download.html
2. Instalar XAMPP en `C:\xampp\`
3. Abrir XAMPP Control Panel
4. Iniciar servicios **Apache** y **MySQL**

### Paso 2: Crear Base de Datos

1. Abrir navegador: `http://localhost/phpmyadmin`
2. Clic en "Nueva" para crear base de datos
3. Nombre: `db_minimarket_bendicion`
4. Cotejamiento: `utf8mb4_general_ci`
5. Clic en "Crear"

### Paso 3: Ejecutar Scripts SQL

En phpMyAdmin, selecciona la base de datos `db_minimarket_bendicion` y ejecuta los siguientes scripts en orden:

#### Script 1: Crear tabla usuarios

```sql
CREATE TABLE usuarios (
    id_usuario INT AUTO_INCREMENT PRIMARY KEY,
    str_nombre_usuario VARCHAR(50) NOT NULL UNIQUE,
    str_password VARCHAR(255) NOT NULL,
    str_nombre_completo VARCHAR(100) NOT NULL,
    str_email VARCHAR(100),
    enum_rol ENUM('admin', 'empleado') NOT NULL DEFAULT 'empleado',
    bool_activo BOOLEAN NOT NULL DEFAULT TRUE,
    int_id_usuario_alta INT,
    datetime_fecha_alta DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    int_id_usuario_modificacion INT,
    datetime_fecha_modificacion DATETIME,
    int_id_usuario_eliminar INT,
    datetime_fecha_eliminacion DATETIME,
    INDEX idx_activo (bool_activo),
    INDEX idx_rol (enum_rol),
    FOREIGN KEY (int_id_usuario_alta) REFERENCES usuarios(id_usuario) ON DELETE RESTRICT,
    FOREIGN KEY (int_id_usuario_modificacion) REFERENCES usuarios(id_usuario) ON DELETE RESTRICT,
    FOREIGN KEY (int_id_usuario_eliminar) REFERENCES usuarios(id_usuario) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
```

#### Script 2: Crear tabla clientes

```sql
CREATE TABLE clientes (
    id_cliente INT AUTO_INCREMENT PRIMARY KEY,
    str_cedula VARCHAR(13) NOT NULL UNIQUE,
    str_nombre VARCHAR(100) NOT NULL,
    str_apellido VARCHAR(100) NOT NULL,
    str_telefono VARCHAR(15),
    str_email VARCHAR(100),
    str_direccion TEXT,
    bool_activo BOOLEAN NOT NULL DEFAULT TRUE,
    int_id_usuario_alta INT NOT NULL,
    datetime_fecha_alta DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    int_id_usuario_modificacion INT,
    datetime_fecha_modificacion DATETIME,
    int_id_usuario_eliminar INT,
    datetime_fecha_eliminacion DATETIME,
    INDEX idx_nombre_apellido (str_nombre, str_apellido),
    INDEX idx_activo (bool_activo),
    INDEX idx_fecha_alta (datetime_fecha_alta),
    FOREIGN KEY (int_id_usuario_alta) REFERENCES usuarios(id_usuario) ON DELETE RESTRICT,
    FOREIGN KEY (int_id_usuario_modificacion) REFERENCES usuarios(id_usuario) ON DELETE RESTRICT,
    FOREIGN KEY (int_id_usuario_eliminar) REFERENCES usuarios(id_usuario) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
```

#### Script 3: Insertar usuario administrador

```sql
INSERT INTO usuarios (str_nombre_usuario, str_password, str_nombre_completo, str_email, enum_rol, int_id_usuario_alta)
VALUES ('admin', MD5('admin123'), 'Administrador del Sistema', 'admin@minimarket.com', 'admin', 1);
```

#### Script 4: Procedimientos Almacenados

```sql
-- SP: Validar Usuario
DELIMITER $$
CREATE PROCEDURE sp_validar_usuario(
    IN p_str_usuario VARCHAR(50),
    IN p_str_password VARCHAR(255)
)
BEGIN
    SELECT 
        id_usuario,
        str_nombre_usuario,
        str_nombre_completo,
        enum_rol as rol,
        bool_activo
    FROM usuarios
    WHERE str_nombre_usuario = p_str_usuario
    AND str_password = MD5(p_str_password)
    AND bool_activo = TRUE;
END$$
DELIMITER ;

-- SP: Listar Clientes
DELIMITER $$
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
DELIMITER ;

-- SP: Insertar Cliente
DELIMITER $$
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
    SELECT COUNT(*) INTO v_existe FROM clientes WHERE str_cedula = p_str_cedula;
    
    IF v_existe > 0 THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'La cédula ya está registrada';
    ELSE
        INSERT INTO clientes (str_cedula, str_nombre, str_apellido, str_telefono, str_email, str_direccion, int_id_usuario_alta)
        VALUES (p_str_cedula, p_str_nombre, p_str_apellido, p_str_telefono, p_str_email, p_str_direccion, p_int_id_usuario_alta);
        SELECT LAST_INSERT_ID() AS id_cliente;
    END IF;
END$$
DELIMITER ;

-- SP: Actualizar Cliente
DELIMITER $$
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
    SELECT COUNT(*) INTO v_existe FROM clientes WHERE str_cedula = p_str_cedula AND id_cliente != p_id_cliente;
    
    IF v_existe > 0 THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'La cédula ya está registrada en otro cliente';
    ELSE
        UPDATE clientes
        SET str_cedula = p_str_cedula, str_nombre = p_str_nombre, str_apellido = p_str_apellido,
            str_telefono = p_str_telefono, str_email = p_str_email, str_direccion = p_str_direccion,
            int_id_usuario_modificacion = p_int_id_usuario_modificacion,
            datetime_fecha_modificacion = NOW()
        WHERE id_cliente = p_id_cliente;
        SELECT ROW_COUNT() AS filas_afectadas;
    END IF;
END$$
DELIMITER ;

-- SP: Eliminar Cliente
DELIMITER $$
CREATE PROCEDURE sp_eliminar_cliente(
    IN p_id_cliente INT,
    IN p_int_id_usuario_eliminar INT
)
BEGIN
    UPDATE clientes
    SET bool_activo = FALSE,
        int_id_usuario_eliminar = p_int_id_usuario_eliminar,
        datetime_fecha_eliminacion = NOW()
    WHERE id_cliente = p_id_cliente;
    SELECT ROW_COUNT() AS filas_afectadas;
END$$
DELIMITER ;
```

### Paso 4: Copiar Archivos del Proyecto

1. Copiar toda la carpeta `minimarket_bendicion` a: `C:\xampp\htdocs\`
2. La estructura debe quedar así:

```
C:\xampp\htdocs\minimarket_bendicion\
├── config/
│   └── database.php
├── controllers/
│   ├── LoginController.php
│   └── ClienteController.php
├── models/
│   ├── Database.php
│   ├── Usuario.php
│   └── Cliente.php
├── views/
│   ├── login.php
│   ├── clientes/
│   │   ├── lista.php
│   │   └── formulario.php
│   └── layouts/
│       ├── header.php
│       └── footer.php
├── assets/
│   ├── css/
│   │   └── style.css
│   └── js/
│       └── main.js
└── index.php
```

### Paso 5: Configurar Base de Datos

Abrir archivo `config/database.php` y verificar las credenciales:

```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'db_minimarket_bendicion');
define('DB_USER', 'root');
define('DB_PASS', ''); // Contraseña vacía por defecto en XAMPP
```

### Paso 6: Acceder al Sistema

1. Abrir navegador
2. Ir a: `http://localhost/minimarket_bendicion/`
3. Usar credenciales:
   - **Usuario:** `admin`
   - **Contraseña:** `admin123`

## 📱 Uso del Sistema

### Login

1. Ingresar usuario y contraseña
2. Clic en "Iniciar Sesión"
3. El sistema redirige automáticamente a la lista de clientes

### Gestión de Clientes

#### Listar Clientes
- Al iniciar sesión verás la lista completa de clientes activos
- Muestra: Cédula, Nombre completo, Teléfono, Email, Fecha de registro

#### Buscar Cliente
- Usar la barra de búsqueda en la parte superior derecha
- Busca por: Cédula, Nombre o Apellido
- Los resultados se filtran automáticamente

#### Crear Nuevo Cliente
1. Clic en botón "Nuevo Cliente"
2. Completar formulario:
   - **Cédula:** 10 dígitos (obligatorio)
   - **Nombre:** Nombre del cliente (obligatorio)
   - **Apellido:** Apellido del cliente (obligatorio)
   - **Teléfono:** Opcional
   - **Email:** Opcional
   - **Dirección:** Opcional
3. Clic en "Registrar Cliente"

#### Editar Cliente
1. Clic en icono de lápiz (✏️) en la fila del cliente
2. Modificar los datos necesarios
3. Clic en "Actualizar Cliente"

#### Eliminar Cliente
1. Clic en icono de basura (🗑️) en la fila del cliente
2. Confirmar la eliminación
3. El cliente se marca como inactivo (no se borra físicamente)

#### Exportar a CSV
1. Clic en botón "Exportar CSV"
2. El archivo se descarga automáticamente
3. Contiene todos los clientes activos

### Cerrar Sesión
- Clic en botón "Salir" en la parte superior derecha

## 🔧 Configuración Adicional

### Cambiar Tiempo de Sesión

Editar `config/database.php`:

```php
define('SESSION_TIMEOUT', 1800); // 30 minutos en segundos
```

### Cambiar Puerto de MySQL

Si el puerto 3306 está ocupado:

1. Abrir XAMPP Control Panel
2. Clic en "Config" de MySQL → "my.ini"
3. Cambiar: `port=3306` por el puerto deseado
4. Actualizar `config/database.php`

## 🐛 Solución de Problemas

### Error: "Error de conexión a la base de datos"

**Causa:** MySQL no está iniciado o credenciales incorrectas

**Solución:**
1. Verificar que MySQL esté corriendo en XAMPP
2. Revisar credenciales en `config/database.php`

### Error: "Call to undefined function"

**Causa:** Extensión PHP no habilitada

**Solución:**
1. Abrir `C:\xampp\php\php.ini`
2. Buscar: `;extension=mysqli`
3. Quitar `;` → `extension=mysqli`
4. Reiniciar Apache

### Página en blanco

**Causa:** Error de PHP no mostrado

**Solución:**
1. Abrir `config/database.php`
2. Cambiar:
```php
ini_set('display_errors', 1);
error_reporting(E_ALL);
```

### No funciona la validación de cédula

**Causa:** JavaScript deshabilitado

**Solución:**
- Habilitar JavaScript en el navegador
- Verificar que `assets/js/main.js` existe

## 📊 Estructura de la Base de Datos

### Tabla: usuarios
- Almacena usuarios del sistema (admin, empleado)
- Contraseñas encriptadas con MD5
- Auditoría completa

### Tabla: clientes
- Almacena información de clientes
- Cédula única
- Eliminación lógica (bool_activo)
- Registra quién y cuándo modificó cada registro

## 🔐 Seguridad

- ✅ Contraseñas encriptadas (MD5)
- ✅ Validación de sesiones
- ✅ Protección contra SQL Injection (PDO)
- ✅ Escapado de caracteres especiales (XSS)
- ✅ Timeout de sesión (30 minutos)

## 📝 Licencia

Este proyecto fue desarrollado con fines educativos.

## 👨‍💻 Autor

Desarrollado para el Minimarket "Bendición de Dios"

## 📞 Soporte

Para reportar problemas o solicitar ayuda, contactar al desarrollador del sistema.

---

**Versión:** 1.0.0  
**Fecha:** Noviembre 2025  
**Estado:** ✅ Funcional