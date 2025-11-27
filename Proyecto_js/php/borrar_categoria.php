<?php
include_once("config.php");
$conexion = obtenerConexion();

$id_category = $_POST['idCategory'];

$sql = "DELETE FROM category WHERE id_category = '$id_category'";

if (mysqli_query($conexion, $sql)) {
    responder(null, false, "Se ha borrado de forma exitosa la categoria", $conexion);
} else {
       responder(null, true, "Se ha producido un error número $numerror que corresponde a: $descrerror <br>", $conexion);
}


mysqli_close($conexion);
?>
