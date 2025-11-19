<?php
require_once("config.php");
$conexion = obtenerConexion();
<<<<<<< HEAD
$sql = "SELECT c.*, s.season_name 
=======
    $sql = "SELECT c.*, s.season_name 
>>>>>>> 95c8ba80e8790a709dc4e59c2fc29bf5be4acbc2
            FROM category c 
            JOIN season s ON c.id_season = s.id_season
            ORDER BY c.id_category ASC";


$resultado = mysqli_query($conexion, $sql);


include_once("index.html");

echo "<div class='container mt-4'>";
echo "<h2 class='text-center'>Listado de categorías</h2>";



echo "<table class='table table-striped table-bordered'>";
echo "<thead class='table-dark'>
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Descripción</th>
            <th>Fecha creación</th>
            <th>Likes</th>
            <th>Estación</th>
            <th>Producto estacional</th>
            <th>Acciones</th>
        </tr>
      </thead><tbody>";


while ($fila = mysqli_fetch_assoc($resultado)) {
    echo "<tr>";
    echo "<td>" . $fila['id_category'] . "</td>";
    echo "<td>" . htmlspecialchars($fila['category_name']) . "</td>";
    echo "<td>" . htmlspecialchars($fila['description']) . "</td>";
    echo "<td>" . $fila['creation_date'] . "</td>";
    echo "<td>" . $fila['like_count'] . "</td>";
    echo "<td>" . htmlspecialchars($fila['season_name']) . "</td>";
    echo "<td>" . ($fila['seasonal_product_available'] ? 'Sí' : 'No') . "</td>";

    echo "<td>
            <form class='d-inline me-1' action='editar_categoria.php' method='post'>
                <input type='hidden' name='categoria' value='" . htmlspecialchars(json_encode($fila), ENT_QUOTES) . "' />
                <button name='Editar' class='btn btn-primary btn-sm'>
                    <i class='bi bi-pencil-square'></i>
                </button>
            </form>
            <form class='d-inline' action='borrar_categoria.php' method='post'>
                <input type='hidden' name='id_category' value='" . $fila['id_category'] . "' />
                <button name='Borrar' class='btn btn-danger btn-sm'>
                    <i class='bi bi-trash'></i>
                </button>
            </form>
          </td>";
    echo "</tr>";
}

echo "</tbody></table>";
echo "</div>";


mysqli_close($conexion);
<<<<<<< HEAD
=======
?>
>>>>>>> 95c8ba80e8790a709dc4e59c2fc29bf5be4acbc2
