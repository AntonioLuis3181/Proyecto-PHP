<?php
require_once("config.php");
$conexion = obtenerConexion();

$id = $_POST['id_category'];

$sql = "DELETE FROM category WHERE id_category = '$id'";

if (mysqli_query($conexion, $sql)) {
    echo "<script>alert('Categoría eliminada correctamente'); window.location.href='listado_categoria.php';</script>";
} else {
    echo "Error al eliminar: " . mysqli_error($conexion);
}

mysqli_close($conexion);
?>
