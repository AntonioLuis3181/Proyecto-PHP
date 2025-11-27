<?php
include_once("config.php");
$conexion = obtenerConexion();

$id_category = $_POST['idcomponente'];

$sql = "DELETE FROM category WHERE id_category = '$id_category'";

if (mysqli_query($conexion, $sql)) {
    echo "<script>alert('Categoría eliminada correctamente'); window.location.href='listado_categoria.php';</script>";
} else {
    echo "Error al eliminar: " . mysqli_error($conexion);
}

mysqli_close($conexion);
?>
