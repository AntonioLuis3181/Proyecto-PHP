-- ============================================
-- SENTENCIAS SQL PARA INSERTAR DATOS
-- ============================================

-- ============================================
-- INSERTAR PRODUCTOS EN LA TABLA PRODUCT
-- ============================================

-- Productos para la categoría 1 (Decoración Otoñal)
INSERT INTO `product` (`product_name`, `price`, `id_category`, `in_stock`, `registration_date`) 
VALUES 
('Vela Aromática Canela', 12.99, 1, 1, CURDATE()),
('Calabaza Decorativa Naranja', 8.50, 1, 1, CURDATE()),
('Hojas Secas Decorativas Pack', 5.99, 1, 1, CURDATE()),
('Guirnalda Otoñal', 15.50, 1, 1, CURDATE());

-- Productos para la categoría 2 (Ropa de entretiempo)
INSERT INTO `product` (`product_name`, `price`, `id_category`, `in_stock`, `registration_date`) 
VALUES 
('Chaqueta Ligera Beige', 45.00, 2, 1, CURDATE()),
('Bufanda de Lana Marrón', 22.50, 2, 1, CURDATE()),
('Chaleco de Entretiempo', 30.00, 2, 1, CURDATE());

-- Productos para la categoría 3 (Ropa de Invierno)
INSERT INTO `product` (`product_name`, `price`, `id_category`, `in_stock`, `registration_date`) 
VALUES 
('Abrigo Largo Negro', 89.99, 3, 1, CURDATE()),
('Gorro de Lana Gris', 12.00, 3, 1, CURDATE()),
('Guantes de Cuero Marrón', 18.50, 3, 1, CURDATE()),
('Suéter Punto Grueso Azul', 35.99, 3, 1, CURDATE());

-- Productos para la categoría 4 (Bebidas Calientes)
INSERT INTO `product` (`product_name`, `price`, `id_category`, `in_stock`, `registration_date`) 
VALUES 
('Café Premium Arabica 500g', 14.99, 4, 1, CURDATE()),
('Chocolate Caliente Gourmet', 8.99, 4, 1, CURDATE()),
('Té de Manzanilla Orgánico', 6.50, 4, 1, CURDATE());

-- Productos para la categoría 5 (Flores y Plantas)
INSERT INTO `product` (`product_name`, `price`, `id_category`, `in_stock`, `registration_date`) 
VALUES 
('Rosa Roja Fresca', 3.99, 5, 1, CURDATE()),
('Maceta Cerámica Blanca', 12.00, 5, 1, CURDATE()),
('Planta Suculenta Verde', 7.50, 5, 1, CURDATE());

-- ============================================
-- INSERTAR VENTAS EN LA TABLA SALE
-- ============================================

-- Ventas de productos
INSERT INTO `sale` (`sale_date`, `product_quantity`, `id_product`, `online_sale`, `address`)
VALUES 
('2025-11-10 14:30:00', 2, 1, 0, 'Calle Principal 123, Madrid'),
('2025-11-11 09:15:00', 1, 5, 1, 'Avenida Reforma 456, Barcelona'),
('2025-11-11 16:45:00', 3, 8, 0, 'Plaza Mayor 789, Valencia'),
('2025-11-12 10:00:00', 1, 12, 1, 'Calle Serrano 101, Madrid'),
('2025-11-12 11:30:00', 2, 3, 0, 'Ramblas 202, Barcelona'),
('2025-11-12 13:45:00', 1, 7, 1, 'Gran Vía 303, Madrid'),
('2025-11-12 15:20:00', 4, 2, 0, 'Paseo de Gracia 404, Barcelona'),
('2025-11-12 17:00:00', 2, 11, 1, 'Calle Mayor 505, Sevilla');

-- ============================================
-- VERIFICAR DATOS INSERTADOS
-- ============================================

-- Ver todos los productos
SELECT * FROM `product` ORDER BY `id_product`;

-- Ver todas las ventas
SELECT * FROM `sale` ORDER BY `id_sale`;

-- Ver productos con sus categorías
SELECT p.*, c.category_name 
FROM `product` p 
LEFT JOIN `category` c ON p.id_category = c.id_category 
ORDER BY p.id_product;

-- Ver ventas con información del producto
SELECT s.*, p.product_name, p.price 
FROM `sale` s 
LEFT JOIN `product` p ON s.id_product = p.id_product 
ORDER BY s.id_sale;
