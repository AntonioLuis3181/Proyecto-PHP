<?php
$host = "localhost"; // Asegúrate que este es el host correcto (localhost para XAMPP/MAMP)
$usuario = "root";
$password = ""; // o tu contraseña
$base_de_datos = "nova_vibe";
$charset = "utf8mb4"; // Usar utf8mb4 es una mejor práctica que utf8


$dsn = "mysql:host=$host;dbname=$base_de_datos;charset=$charset";

try {
    $conexionPDO = new PDO($dsn, $usuario, $password, $options);
} catch (\PDOException $e) {
    // En un entorno de producción, no muestres el error detallado.
    // Simplemente registra el error y muestra un mensaje genérico.
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}
