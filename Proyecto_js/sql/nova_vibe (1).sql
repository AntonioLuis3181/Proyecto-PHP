-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Servidor: db
-- Tiempo de generación: 12-11-2025 a las 13:06:07
-- Versión del servidor: 8.0.43
-- Versión de PHP: 8.2.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";

START TRANSACTION;

SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */
;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */
;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */
;
/*!40101 SET NAMES utf8mb4 */
;

--
-- Base de datos: `nova_vibe`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `category`
--
CREATE DATABASE IF NOT EXISTS nova_vibe CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;

USE nova_vibe;

CREATE TABLE `category` (
    `id_category` int NOT NULL,
    `category_name` varchar(100) NOT NULL,
    `description` varchar(255) DEFAULT NULL,
    `creation_date` datetime DEFAULT CURRENT_TIMESTAMP,
    `like_count` int DEFAULT NULL,
    `seasonal_product_available` tinyint(1) DEFAULT '0',
    `id_season` int DEFAULT NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `category`
--

INSERT INTO
    `category` (
        `id_category`,
        `category_name`,
        `description`,
        `creation_date`,
        `like_count`,
        `seasonal_product_available`,
        `id_season`
    )
VALUES (
        1,
        'Decoración Otoñal',
        'Velas aromáticas, hojas secas y calabazas decorativas',
        '2025-11-12 11:25:20',
        12,
        1,
        1
    ),
    (
        2,
        'Ropa de entretiempo',
        'Chaquetas ligeras y bufandas para el otoño',
        '2025-11-12 11:25:20',
        8,
        1,
        1
    ),
    (
        3,
        'Ropa de Invierno',
        'Abrigos, gorros, guantes y suéteres',
        '2025-11-12 11:25:20',
        20,
        1,
        2
    ),
    (
        4,
        'Bebidas Calientes',
        'Café, chocolate y té de temporada',
        '2025-11-12 11:25:20',
        15,
        1,
        2
    ),
    (
        5,
        'Flores y Plantas',
        'Flores naturales, macetas y jardinería',
        '2025-11-12 11:25:20',
        9,
        1,
        3
    ),
    (
        6,
        'Ropa Primaveral',
        'Vestidos ligeros y ropa fresca',
        '2025-11-12 11:25:20',
        11,
        1,
        3
    ),
    (
        7,
        'DecorCrack',
        'Tinki Winki',
        '2025-11-12 12:58:00',
        0,
        0,
        2
    ),
    (
        8,
        'DecorCrack',
        'Tinki Winki',
        '2025-11-12 13:00:58',
        0,
        0,
        2
    ),
    (
        9,
        'DecorCrack',
        'Tinki Winki',
        '2025-11-12 13:00:59',
        0,
        0,
        2
    ),
    (
        10,
        'DecorCrack',
        'Tinki Winki',
        '2025-11-12 13:01:04',
        0,
        0,
        2
    ),
    (
        11,
        'DecorCrack',
        'Tinki Winki',
        '2025-11-12 13:01:05',
        0,
        0,
        2
    ),
    (
        12,
        'DecorCrack',
        'Tinki Winki',
        '2025-11-12 13:01:05',
        0,
        0,
        2
    );

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `product`
--

CREATE TABLE `product` (
    `id_product` int NOT NULL,
    `product_name` varchar(100) NOT NULL,
    `price` decimal(10, 2) NOT NULL,
    `id_category` int DEFAULT NULL,
    `in_stock` tinyint(1) DEFAULT '1',
    `registration_date` date DEFAULT(curdate())
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sale`
--

CREATE TABLE `sale` (
    `id_sale` int NOT NULL,
    `sale_date` datetime DEFAULT CURRENT_TIMESTAMP,
    `product_quantity` int NOT NULL,
    `id_product` int DEFAULT NULL,
    `online_sale` tinyint(1) DEFAULT '0',
    `address` varchar(255) DEFAULT NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `season`
--

CREATE TABLE `season` (
    `id_season` int NOT NULL,
    `season_name` enum('Autumn', 'Winter', 'Spring') NOT NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `season`
--

INSERT INTO
    `season` (`id_season`, `season_name`)
VALUES (1, 'Autumn'),
    (2, 'Winter'),
    (3, 'Spring');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `category`
--
ALTER TABLE `category`
ADD PRIMARY KEY (`id_category`),
ADD KEY `fk_category_season` (`id_season`);

--
-- Indices de la tabla `product`
--
ALTER TABLE `product`
ADD PRIMARY KEY (`id_product`),
ADD KEY `id_category` (`id_category`);

--
-- Indices de la tabla `sale`
--
ALTER TABLE `sale`
ADD PRIMARY KEY (`id_sale`),
ADD KEY `id_product` (`id_product`);

--
-- Indices de la tabla `season`
--
ALTER TABLE `season` ADD PRIMARY KEY (`id_season`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `category`
--
ALTER TABLE `category`
MODIFY `id_category` int NOT NULL AUTO_INCREMENT,
AUTO_INCREMENT = 13;

--
-- AUTO_INCREMENT de la tabla `product`
--
ALTER TABLE `product`
MODIFY `id_product` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `sale`
--
ALTER TABLE `sale` MODIFY `id_sale` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `season`
--
ALTER TABLE `season`
MODIFY `id_season` int NOT NULL AUTO_INCREMENT,
AUTO_INCREMENT = 4;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `category`
--
ALTER TABLE `category`
ADD CONSTRAINT `fk_category_season` FOREIGN KEY (`id_season`) REFERENCES `season` (`id_season`);

--
-- Filtros para la tabla `product`
--
ALTER TABLE `product`
ADD CONSTRAINT `product_ibfk_1` FOREIGN KEY (`id_category`) REFERENCES `category` (`id_category`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Filtros para la tabla `sale`
--
ALTER TABLE `sale`
ADD CONSTRAINT `sale_ibfk_1` FOREIGN KEY (`id_product`) REFERENCES `product` (`id_product`) ON DELETE SET NULL ON UPDATE CASCADE;

COMMIT;