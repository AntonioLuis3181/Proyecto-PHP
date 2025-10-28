-- Base de datos
CREATE DATABASE nova_vibe

USE nova_vibe;

-- Tablas
CREATE TABLE categorias (...);
-- como arriba
CREATE TABLE productos (...);

CREATE TABLE ventas (...);

-- Datos de ejemplo (mínimo 10 registros por tabla)
INSERT INTO categorias (nombre_categoria, descripcion, partes, parte_de_arriba) VALUES
('Camisetas','Camisetas de algodón',1,1),
('Pantalones','Pantalones vaqueros',2,0),
...
;