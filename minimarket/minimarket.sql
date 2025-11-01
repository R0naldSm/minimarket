-- Base de datos para Minimarket
CREATE DATABASE IF NOT EXISTS minimarket;
USE minimarket;

-- Tabla de categorías
CREATE TABLE IF NOT EXISTS categorias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabla de productos
CREATE TABLE IF NOT EXISTS productos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(150) NOT NULL,
    descripcion TEXT,
    precio DECIMAL(10,2) NOT NULL,
    stock INT NOT NULL DEFAULT 0,
    categoria_id INT,
    imagen VARCHAR(255),
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (categoria_id) REFERENCES categorias(id) ON DELETE SET NULL
);

-- Tabla de clientes
CREATE TABLE IF NOT EXISTS clientes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    apellido VARCHAR(100) NOT NULL,
    email VARCHAR(150) UNIQUE,
    telefono VARCHAR(20),
    direccion TEXT,
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabla de ventas
CREATE TABLE IF NOT EXISTS ventas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cliente_id INT,
    total DECIMAL(10,2) NOT NULL,
    fecha_venta TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (cliente_id) REFERENCES clientes(id) ON DELETE SET NULL
);

-- Tabla de detalle de ventas
CREATE TABLE IF NOT EXISTS detalle_ventas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    venta_id INT NOT NULL,
    producto_id INT,
    cantidad INT NOT NULL,
    precio_unitario DECIMAL(10,2) NOT NULL,
    subtotal DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (venta_id) REFERENCES ventas(id) ON DELETE CASCADE,
    FOREIGN KEY (producto_id) REFERENCES productos(id) ON DELETE SET NULL
);

-- Tabla de usuarios (NUEVA)
CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    usuario VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    rol ENUM('admin', 'cajero', 'vendedor') DEFAULT 'cajero',
    activo TINYINT(1) DEFAULT 1,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insertar usuarios por defecto (las contraseñas están hasheadas con password_hash)
-- Contraseña para todos: admin123
INSERT INTO usuarios (nombre, usuario, password, rol) VALUES
('Administrador', 'admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'superusuario'),
('Cajero 1', 'cajero1', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'cajero'),
('Vendedor 1', 'vendedor1', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'vendedor');



-- Datos de ejemplo
INSERT INTO categorias (nombre, descripcion) VALUES
('Bebidas', 'Bebidas alcohólicas y no alcohólicas'),
('Snacks', 'Papas, galletas y botanas'),
('Lácteos', 'Leche, queso, yogurt'),
('Panadería', 'Pan fresco y productos horneados'),
('Limpieza', 'Productos de limpieza del hogar');

INSERT INTO productos (nombre, descripcion, precio, stock, categoria_id) VALUES
('Coca Cola 2L', 'Bebida gaseosa', 2.50, 50, 1),
('Agua Mineral 500ml', 'Agua purificada', 0.75, 100, 1),
('Papas Lays', 'Papas fritas sabor natural', 1.25, 75, 2),
('Galletas Oreo', 'Galletas con crema', 2.00, 60, 2),
('Leche Entera 1L', 'Leche pasteurizada', 1.50, 40, 3),
('Queso Fresco 500g', 'Queso blanco fresco', 3.50, 30, 3),
('Pan Integral', 'Pan de molde integral', 2.25, 25, 4),
('Detergente 1L', 'Detergente líquido', 3.00, 35, 5);

INSERT INTO clientes (nombre, apellido, email, telefono, direccion) VALUES
('Juan', 'Pérez', 'juan.perez@email.com', '0987654321', 'Av. Principal 123'),
('María', 'González', 'maria.gonzalez@email.com', '0976543210', 'Calle Secundaria 456'),
('Carlos', 'Rodríguez', 'carlos.rodriguez@email.com', '0965432109', 'Pasaje Los Andes 789');