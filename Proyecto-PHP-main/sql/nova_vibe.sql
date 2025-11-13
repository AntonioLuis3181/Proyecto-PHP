CREATE DATABASE IF NOT EXISTS nova_vibe CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;

USE nova_vibe;

-- Category table
CREATE TABLE category (
    id_category INT AUTO_INCREMENT PRIMARY KEY,
    category_name VARCHAR(100) NOT NULL,
    description VARCHAR(255),
    creation_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    like_count INT,
    seasonal_product_available BOOLEAN DEFAULT 0
);

-- Product table
CREATE TABLE product (
    id_product INT AUTO_INCREMENT PRIMARY KEY,
    product_name VARCHAR(100) NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    id_category INT,
    in_stock BOOLEAN DEFAULT 1,
    registration_date DATE DEFAULT(CURRENT_DATE),
    FOREIGN KEY (id_category) REFERENCES category (id_category) ON DELETE SET NULL ON UPDATE CASCADE
);

-- Season table
CREATE TABLE season (
    id_season INT AUTO_INCREMENT PRIMARY KEY,
    season_name ENUM('Autumn', 'Winter', 'Spring') NOT NULL
);

-- Sale table
CREATE TABLE sale (
    id_sale INT AUTO_INCREMENT PRIMARY KEY,
    sale_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    product_quantity INT NOT NULL,
    id_product INT,
    online_sale BOOLEAN DEFAULT 0,
    address VARCHAR(255),
    FOREIGN KEY (id_product) REFERENCES product (id_product) ON DELETE SET NULL ON UPDATE CASCADE
);