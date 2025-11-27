<?php

include_once("config.php");
$conexion = obtenerConexion();


$producto = json_decode($_POST['producto']);

    $nombre = $producto -> product_name;
    $stock = (int)$producto ->in_stock;
    $precio = (float)$producto ->price;
    $categorias = (int)$producto-> id_category;

    $conexion = obtenerConexion();

    $sql = "INSERT INTO product (product_name, price ,id_category,in_stock, registration_date) VALUES ('$nombre', $precio, $categorias, $stock, NOW());";

mysqli_query($conexion, $sql);

if (mysqli_errno($conexion) != 0) {
    $numerror = mysqli_errno($conexion);
    $descrerror = mysqli_error($conexion);

    responder(null, false, "Se ha producido un error número $numerror que corresponde a: $descrerror <br>", $conexion);

} else {
    // Prototipo responder($datos,$error,$mensaje,$conexion)
    responder(null, true, "Se ha dado de alta el producto", $conexion);
}
?>