<?php

require_once("config.php");
$conexion = obtenerConexion();

$sql = "SELECT * FROM season";
$resultado = mysqli_query($conexion, $sql);
$options = "";
while ($fila = mysqli_fetch_assoc($resultado)) {
    $options .= "<option value='" . $fila["id_season"] . "'>" . $fila["season_name"] . "</option>";
}

include_once("index.html");

?>

<div class="container" id="formularios">
    <div class="row">
        <form class="form-horizontal" method="POST" action="">
            <fieldset>
                <legend>Agregar nueva categoría</legend>

                <!-- Nombre -->
                <div class="form-group">
                    <label class="col-xs-4 control-label" for="category_name">Nombre</label>
                    <div class="col-xs-4">
                        <input type="text" name="category_name" id="category_name" placeholder="Nombre de la categoría" class="form-control input-md" required>
                    </div>
                </div>

                <!-- Descripción -->
                <div class="form-group">
                    <label class="col-xs-4 control-label" for="description">Descripción</label>
                    <div class="col-xs-4">
                        <input type="text" name="description" id="description" placeholder="Descripción" class="form-control input-md">
                    </div>
                </div>

                <!-- Estación -->
                <div class="form-group">
                    <label class="col-xs-4 control-label" for="id_season">Estación</label>
                    <div class="col-xs-4">
                        <select name="id_season" id="id_season" class="form-select input-md" required>
                            <option value="">-- Selecciona una estación --</option>
                            <?php echo $options; ?>
                        </select>
                    </div>
                </div>
                <!-- Producto estacional disponible -->
                <label>
                    <input type="checkbox" name="seasonal_product_available" value="1">
                    Producto estacional disponible
                </label><br><br>

                <!-- Likes -->
                <div class="form-group">
                    <label class="col-xs-4 control-label" for="likes">Likes del producto</label>
                    <div class="col-xs-4">
                        <input type="number" name="likes" id="likes" placeholder="Likes" class="form-control input-md">
                    </div>
                </div>



                <!-- Botón -->
                <div class="form-group">
                    <label class="col-xs-4 control-label" for="add"></label>
                    <div class="col-xs-4">
                        <input type="submit" name="add" id="add" class="btn btn-primary" value="Guardar">
                    </div>
                </div>

            </fieldset>
        </form>
    </div>
</div>
</body>

</html>

<?php
if (isset($_POST['add'])) {
    $name = $_POST['category_name'];
    $desc = $_POST['description'];
    $seasonal = isset($_POST['seasonal_product_available']) ? 1 : 0;
    $season = $_POST['id_season'];
    $likes = isset($_POST['likes']) ? (int)$_POST['likes'] : 0;

    $sql = "INSERT INTO category (category_name, description, creation_date, like_count,seasonal_product_available, id_season)
            VALUES ('$name','$desc',NOW(),'$likes','$seasonal','$season')";

    if (mysqli_query($conexion, $sql)) {
        echo "<div class='alert alert-success'>Categoría agregada correctamente</div>";
    } else {
        echo "<div class='alert alert-danger'>Error: " . mysqli_error($conexion) . "</div>";
    }
}
?>