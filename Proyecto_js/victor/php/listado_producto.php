<?php
include_once("config.php");
$conexion = obtenerConexion();
$sql = "SELECT p.*,c.category_name 
            FROM product p 
            JOIN category c ON c.id_category = p.id_category
            ORDER BY p.id_product ASC";


$resultado = mysqli_query($conexion, $sql);



  while ($fila = mysqli_fetch_assoc($resultado)) {
    $datos[] = $fila; // Insertar la fila en el array
}
responder($datos, true, "Datos recuperados", $conexion);
mysqli_close($conexion);
