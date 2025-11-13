<?php
require_once("config.php");
$conexion = obtenerConexion();

include_once("index.html");

echo "<div class='container mt-4'>";
echo "<h2 class='text-center mb-4'>Listado filtrado de categorías</h2>";

// Obtener estaciones para el desplegable
$resultTemp = mysqli_query($conexion, "SELECT * FROM season ORDER BY season_name ASC");

// Formulario de filtros (arriba)
echo "<form method='POST' class='mb-4'>
        <div class='row g-3 align-items-end'>
          <div class='col-md-4'>
            <label class='form-label fw-semibold'>Estación</label>
            <select name='id_season' class='form-select'>
              <option value=''>-- Todas --</option>";

while ($row = mysqli_fetch_assoc($resultTemp)) {
<<<<<<< HEAD
  // Mantener seleccionado el valor anterior si el usuario filtró
  $selected = (isset($_POST['id_season']) && $_POST['id_season'] == $row['id_season']) ? "selected" : "";
  echo "<option value='{$row['id_season']}' $selected>{$row['season_name']}</option>";
=======
    // Mantener seleccionado el valor anterior si el usuario filtró
    $selected = (isset($_POST['id_season']) && $_POST['id_season'] == $row['id_season']) ? "selected" : "";
    echo "<option value='{$row['id_season']}' $selected>{$row['season_name']}</option>";
>>>>>>> 95c8ba80e8790a709dc4e59c2fc29bf5be4acbc2
}

echo "    </select>
          </div>

          <div class='col-md-4'>
            <button type='submit' class='btn btn-primary w-100'>
            Filtrar
            </button>
          </div>
        </div>
      </form>";

// --- Mostrar tabla debajo ---
if ($_SERVER["REQUEST_METHOD"] == "POST") {
<<<<<<< HEAD
  $id_season = $_POST['id_season'] ?? '';
  $estacional = $_POST['seasonal_product_available'] ?? '';

  $sql = "SELECT c.*, s.season_name 
=======
    $id_season = $_POST['id_season'] ?? '';
    $estacional = $_POST['seasonal_product_available'] ?? '';

    $sql = "SELECT c.*, s.season_name 
>>>>>>> 95c8ba80e8790a709dc4e59c2fc29bf5be4acbc2
            FROM category c 
            JOIN season s ON c.id_season = s.id_season
            WHERE (c.id_season = '$id_season' OR '$id_season' = '')
              AND (c.seasonal_product_available = '$estacional' OR '$estacional' = '')
            ORDER BY c.id_category ASC";

<<<<<<< HEAD
  $resultado = mysqli_query($conexion, $sql);

  echo "<div class='table-responsive mt-3'>";
  echo "<table class='table table-striped table-bordered'>";
  echo "<thead class='table-dark text-center'>
=======
    $resultado = mysqli_query($conexion, $sql);

    echo "<div class='table-responsive mt-3'>";
    echo "<table class='table table-striped table-bordered'>";
    echo "<thead class='table-dark text-center'>
>>>>>>> 95c8ba80e8790a709dc4e59c2fc29bf5be4acbc2
            <tr>
              <th>ID</th>
              <th>Nombre</th>
              <th>Descripción</th>
              <th>Fecha creación</th>
              <th>Likes</th>
              <th>Estación</th>
              <th>Producto estacional</th>
            </tr>
          </thead><tbody>";

<<<<<<< HEAD
  if (mysqli_num_rows($resultado) > 0) {
    while ($fila = mysqli_fetch_assoc($resultado)) {
      echo "<tr>";
      echo "<td>{$fila['id_category']}</td>";
      echo "<td>" . htmlspecialchars($fila['category_name']) . "</td>";
      echo "<td>" . htmlspecialchars($fila['description']) . "</td>";
      echo "<td>{$fila['creation_date']}</td>";
      echo "<td>{$fila['like_count']}</td>";
      echo "<td>" . htmlspecialchars($fila['season_name']) . "</td>";
      echo "<td>" . ($fila['seasonal_product_available'] ? 'Sí' : 'No') . "</td>";
      echo "</tr>";
    }
  }

  echo "</tbody></table>";
  echo "</div>";
=======
    if (mysqli_num_rows($resultado) > 0) {
        while ($fila = mysqli_fetch_assoc($resultado)) {
            echo "<tr>";
            echo "<td>{$fila['id_category']}</td>";
            echo "<td>" . htmlspecialchars($fila['category_name']) . "</td>";
            echo "<td>" . htmlspecialchars($fila['description']) . "</td>";
            echo "<td>{$fila['creation_date']}</td>";
            echo "<td>{$fila['like_count']}</td>";
            echo "<td>" . htmlspecialchars($fila['season_name']) . "</td>";
            echo "<td>" . ($fila['seasonal_product_available'] ? 'Sí' : 'No') . "</td>";
            echo "</tr>";
        }
    }

    echo "</tbody></table>";
    echo "</div>";
>>>>>>> 95c8ba80e8790a709dc4e59c2fc29bf5be4acbc2
}

echo "</div>";

mysqli_close($conexion);
<<<<<<< HEAD
=======
?>
>>>>>>> 95c8ba80e8790a709dc4e59c2fc29bf5be4acbc2
