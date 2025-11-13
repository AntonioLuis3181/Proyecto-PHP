<?php
require_once("config.php");
$conexion = obtenerConexion();

include_once("index.html");

echo "<div class='container mt-4'>";
echo "<h2 class='text-center'>Buscar Categoría</h2>";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = mysqli_real_escape_string($conexion, $_POST['category_name']);
    $sql = "SELECT c.*, s.season_name 
            FROM category c 
            JOIN season s ON c.id_season = s.id_season
            WHERE c.category_name LIKE '%$nombre%'";
    $resultado = mysqli_query($conexion, $sql);

    echo "<table class='table table-striped table-bordered mt-3'>";
    echo "<thead class='table-dark'>
            <tr>
                <th>ID</th><th>Nombre</th><th>Descripción</th><th>Estación</th><th>Producto estacional</th>
            </tr>
          </thead><tbody>";

    while ($fila = mysqli_fetch_assoc($resultado)) {
        echo "<tr>";
        echo "<td>{$fila['id_category']}</td>";
        echo "<td>" . htmlspecialchars($fila['category_name']) . "</td>";
        echo "<td>" . htmlspecialchars($fila['description']) . "</td>";
        echo "<td>" . htmlspecialchars($fila['season_name']) . "</td>";
        echo "<td>" . ($fila['seasonal_product_available'] ? 'Sí' : 'No') . "</td>";
        echo "</tr>";
    }

    echo "</tbody></table>";
} else {
    echo "<form method='POST' class='mt-3'>
            <div class='mb-3'>
                <label class='form-label'>Nombre de la categoría</label>
                <input type='text' name='category_name' class='form-control' required>
            </div>
            <button type='submit' class='btn btn-primary'>Buscar</button>
          </form>";
}

echo "</div>";
mysqli_close($conexion);
<<<<<<< HEAD
=======
?>
>>>>>>> 95c8ba80e8790a709dc4e59c2fc29bf5be4acbc2
