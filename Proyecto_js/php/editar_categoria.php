<?php
include_once("config.php");
$conexion = obtenerConexion();

include_once("index.html");


echo "<div class='container mt-4'>";
echo "<h2 class='text-center'>Editar Categoría</h2>";

// Si se envió el formulario para guardar cambios
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['actualizar'])) {
    $id = $_POST['id_category'];
    $nombre = mysqli_real_escape_string($conexion, $_POST['category_name']);
    $descripcion = mysqli_real_escape_string($conexion, $_POST['description']);
    $id_season = $_POST['id_season'];
    $estacional = $_POST['seasonal_product_available'];

    $sql = "UPDATE category 
            SET category_name='$nombre',
                description='$descripcion',
                id_season='$id_season',
                seasonal_product_available='$estacional'
            WHERE id_category='$id'";

    if (mysqli_query($conexion, $sql)) {
        echo "<script>alert('Categoría actualizada correctamente'); window.location.href='listado_categoria.php';</script>";
    } else {
        echo "<div class='alert alert-danger'>Error al actualizar: " . mysqli_error($conexion) . "</div>";
    }
}
// Si se recibe la categoría desde el botón "Editar" del listado
elseif ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['categoria'])) {
    $categoria = json_decode($_POST['categoria'], true);

    // Obtener estaciones para el desplegable
    $estaciones = mysqli_query($conexion, "SELECT * FROM season ORDER BY season_name ASC");

    echo "<form method='POST' class='mt-3'>";
    echo "<input type='hidden' name='id_category' value='{$categoria['id_category']}'>";

    echo "<div class='mb-3'>
            <label class='form-label fw-semibold'>Nombre</label>
            <input type='text' name='category_name' class='form-control' value='" . htmlspecialchars($categoria['category_name']) . "' required>
          </div>";

    echo "<div class='mb-3'>
            <label class='form-label fw-semibold'>Descripción</label>
            <textarea name='description' class='form-control' required>" . htmlspecialchars($categoria['description']) . "</textarea>
          </div>";

    echo "<div class='mb-3'>
            <label class='form-label fw-semibold'>Estación</label>
            <select name='id_season' class='form-select'>";
    while ($row = mysqli_fetch_assoc($estaciones)) {
        $selected = ($row['id_season'] == $categoria['id_season']) ? "selected" : "";
        echo "<option value='{$row['id_season']}' $selected>{$row['season_name']}</option>";
    }
    echo "</select>
          </div>";

    echo "<div class='mb-3'>
            <label class='form-label fw-semibold'>¿Producto estacional?</label>
            <select name='seasonal_product_available' class='form-select'>
                <option value='1' " . ($categoria['seasonal_product_available'] ? 'selected' : '') . ">Sí</option>
                <option value='0' " . (!$categoria['seasonal_product_available'] ? 'selected' : '') . ">No</option>
            </select>
          </div>";

    echo "<button type='submit' name='actualizar' class='btn btn-success'>
            Guardar cambios
          </button>";
    echo " <a href='listar_categorias.php' class='btn btn-secondary'>
            Volver
          </a>";

    echo "</form>";
} else {
    echo "<div class='alert alert-warning text-center'>No se ha seleccionado ninguna categoría para editar.</div>";
}

echo "</div>";
mysqli_close($conexion);
