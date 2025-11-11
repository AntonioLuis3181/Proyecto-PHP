<?php
function obtenerConexion()
{
    $servidor = "db";
    $usuario = "root";
    $contrasena = ""; // o la contraseña que uses
    $baseDatos = "nova_vibe";

    $conexion = new mysqli($servidor, $usuario, $contrasena, $baseDatos);

    if ($conexion->connect_error) {
        die("Error de conexión: " . $conexion->connect_error);
    }

    return $conexion;
}

function comprobarSesionAdmin()
{
    if (!isset($_SESSION["conectado"]) || $_SESSION["conectado"] != "admin") {
        header("Location: admin.php");
        exit();
    }
}

function comprobarSesionCliente()
{
    if (!isset($_SESSION["conectado"]) || $_SESSION["conectado"] != "cliente") {
        header("Location: admin.php");
        exit();
    }
}

function comprobarSesionClienteOAdmin()
{
    if (!isset($_SESSION["conectado"])) {
        header("Location: admin.php");
        exit();
    }
}
