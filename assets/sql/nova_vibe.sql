-- Base de datos: tienda_ropa
CREATE DATABASE IF NOT EXISTS nova_vibe CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;

USE nova_vibe;

CREATE TABLE category (
    category_id INT AUTO_INCREMENT PRIMARY KEY,
    category_name VARCHAR(100) NOT NULL,
    description VARCHAR(255),
    date_of_creation DATETIME DEFAULT CURRENT_TIMESTAMP,
    parts INT,
    upper_part BOOLEAN DEFAULT 0
);

CREATE TABLE product (
    product_id INT AUTO_INCREMENT PRIMARY KEY,
    product_name VARCHAR(100) NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    category_id INT,
    stockk BOOLEAN DEFAULT 1,
    date_of_registration DATE DEFAULT(CURRENT_DATE),
    FOREIGN KEY (category_id) REFERENCES category (category_id) ON DELETE SET NULL ON UPDATE CASCADE
);

CREATE TABLE sale (
    sale_id INT AUTO_INCREMENT PRIMARY KEY,
    date_of_sale DATETIME DEFAULT CURRENT_TIMESTAMP,
    product_quantity INT NOT NULL,
    product_id INT,
    online_sales BOOLEAN DEFAULT 0,
    description VARCHAR(1000),
    FOREIGN KEY (product_id) REFERENCES product (product_id) ON DELETE SET NULL ON UPDATE CASCADE
);