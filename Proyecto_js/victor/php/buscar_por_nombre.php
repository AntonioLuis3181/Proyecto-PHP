<?php
require_once("config.php");
$conexion = obtenerConexion();


    $nombre = $_GET['nombre'];
    $sql = "SELECT c.*, s.season_name 
            FROM category c 
            JOIN season s ON c.id_season = s.id_season
            WHERE c.category_name LIKE '%$nombre%'";
    $resultado = mysqli_query($conexion, $sql);

  while ($fila = mysqli_fetch_assoc($resultado)) {
    $datos[] = $fila; // Insertar la fila en el array
}
responder($datos, true, "Datos recuperados", $conexion);

mysqli_close($conexion);
