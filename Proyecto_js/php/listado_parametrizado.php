<?php
require_once("config.php");
$conexion = obtenerConexion();


$id_season = $_GET['id_season'];

  $sql = "SELECT c.category_name , c.description , c.creation_date , c.like_count , c.seasonal_product_available , s.season_name
FROM category c JOIN season s ON c.id_season = s.id_season
WHERE c.id_season = s.id_season
AND s.id_season = $id_season";

$resultado = mysqli_query($conexion, $sql);
  while ($fila = mysqli_fetch_assoc($resultado)) {
    $datos[] = $fila; // Insertar la fila en el array
}
responder($datos, true, "Datos recuperados", $conexion);

  $resultado = mysqli_query($conexion, $sql);



mysqli_close($conexion);
