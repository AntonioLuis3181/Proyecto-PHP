<?php
$host = "db";
$usuario = "nombreTest";
$password = "test"; // o tu contraseña de XAMPP/MAMP/WAMP
$base_de_datos = "nova_vibe";

try {
    $conexion = new PDO("mysql:host=$host;dbname=$base_de_datos;charset=utf8", $usuario, $password);
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}
