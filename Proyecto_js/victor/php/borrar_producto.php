<?php
include_once("config.php");
$conexion = obtenerConexion();

$id_product = $_POST['id_product'];

$sql = "DELETE FROM product WHERE id_product = '$id_product'";

if (mysqli_query($conexion, $sql)) {
    responder(null, true, "Producto eliminado correctamente", $conexion);
} else {
        responder(null, false, "Error al eliminar: " . mysqli_error($conexion), $conexion);
}

mysqli_close($conexion);
?>
