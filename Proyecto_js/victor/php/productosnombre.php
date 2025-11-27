<?php
require_once("config.php");
$conexion = obtenerConexion();


$product_name = $_GET['product_name'];

  $sql = "SELECT p.*,c.category_name 
            FROM product p 
            JOIN category c ON c.id_category = p.id_category
            where p.product_name like '%$product_name%'
            ORDER BY p.id_product ASC";

$resultado = mysqli_query($conexion, $sql);
  while ($fila = mysqli_fetch_assoc($resultado)) {
    $datos[] = $fila; // Insertar la fila en el array
}
responder($datos, true, "Datos recuperados", $conexion);



mysqli_close($conexion);
