<?php
$host = "localhost";
$usuario = "root";
$password = "";
$base_de_datos = "nombre_base_datos";

try {
    $conexion = new PDO("mysql:host=$host;dbname=$base_de_datos;charset=utf8", $usuario, $password);
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}
?>
