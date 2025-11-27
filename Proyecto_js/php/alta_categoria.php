<?php

include_once("config.php");
$conexion = obtenerConexion();


$categoria = json_decode($_POST['categoria']);

// Convertimos a enteros en PHP
$like_count = (int)$categoria->like_count;
$seasonal = (int)$categoria->seasonal_product_available;
$id_season = (int)$categoria->id_season;

$sql = "INSERT INTO category 
        (id_category , category_name, description, creation_date, like_count, seasonal_product_available, id_season)
        VALUES (null ,'$categoria->category_name', '$categoria->description', NOW(), $like_count, $seasonal, $id_season)";


mysqli_query($conexion, $sql);

if (mysqli_errno($conexion) != 0) {
    $numerror = mysqli_errno($conexion);
    $descrerror = mysqli_error($conexion);

    responder(null, false, "Se ha producido un error número $numerror que corresponde a: $descrerror <br>", $conexion);

} else {
    // Prototipo responder($datos,$error,$mensaje,$conexion)
    responder(null, true, "Se ha dado de alta la categoria", $conexion);
}
?>