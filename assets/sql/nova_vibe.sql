-- Base de datos: tienda_ropa
CREATE DATABASE IF NOT EXISTS nova_vibe CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;

USE nova_vibe;

CREATE TABLE categorias (
    id_categoria INT AUTO_INCREMENT PRIMARY KEY,
    nombre_categoria VARCHAR(100) NOT NULL,
    descripcion VARCHAR(255),
    fecha_creacion DATETIME DEFAULT CURRENT_TIMESTAMP,
    partes INT,
    parte_de_arriba BOOLEAN DEFAULT 0
);

CREATE TABLE productos (
    id_producto INT AUTO_INCREMENT PRIMARY KEY,
    nombre_producto VARCHAR(100) NOT NULL,
    precio DECIMAL(10, 2) NOT NULL,
    id_categoria INT,
    en_stock BOOLEAN DEFAULT 1,
    fecha_registro DATE DEFAULT(CURRENT_DATE),
    FOREIGN KEY (id_categoria) REFERENCES categorias (id_categoria) ON DELETE SET NULL ON UPDATE CASCADE
);

CREATE TABLE ventas (
    id_venta INT AUTO_INCREMENT PRIMARY KEY,
    fecha_venta DATETIME DEFAULT CURRENT_TIMESTAMP,
    cantidad_producto INT NOT NULL,
    id_producto INT,
    venta_online BOOLEAN DEFAULT 0,
    descripcion VARCHAR(1000),
    FOREIGN KEY (id_producto) REFERENCES productos (id_producto) ON DELETE SET NULL ON UPDATE CASCADE
);