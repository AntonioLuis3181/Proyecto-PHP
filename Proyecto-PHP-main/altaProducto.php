<?php
require_once("config.php");
include_once("index.html");
?>

<div class="container">
    <form name="frmAltaProducto" id="frmAltaProducto" action="procesoAltaProducto.php" method="POST" enctype="multipart/form-data">
        <legend>Alta de producto</legend>
        <div class="col-md-6 mb-3">
            <label for="txtNombre" class="form-label">Nombre</label>
            <input type="text" class="form-control" id="txtNombre" name="txtNombre" placeholder="Introduzca el nombre del producto" maxlength="45" size="50" />
        </div>
        <div class="col-md-6 mb-3">
            <input type="checkbox" id="txtStock" name="txtStock" />
            <label for="txtStock" class="form-label">Stock</label>
        </div>
        <div class="col-md-6 mb-3">
            <label for="txtPrecio" class="form-label">Precio</label>
            <input type="number" class="form-control" id="txtPrecio" name="txtPrecio" placeholder="Introduzca el precio del producto" min="0" />
        </div>
        <div class="col-md-6 mb-3">
            <label for="lstCategoria" class="form-label">Categorías</label>
            <select name="lstCategoria" id="lstCategoria" class="form-select">
                <?php
                $conexion = obtenerConexion();

                $array_opciones = obtenerArrayOpciones('category', 'id_category', 'category_name');

                foreach ($array_opciones as $indice => $valor) {
                    echo "<option value='" . $indice . "'>" . $valor . "</option>";
                }
                ?>
            </select>
        </div>
        <!-- <div class="col-md-6 mb-3">
                <label for="imagen" class="form-label">Subir imagen del producto</label>
                <input type="file" class="form-control" id="imagen" name="imagen" />
            </div> -->
        <button type="submit" class="btn btn-primary">Aceptar</button>
    </form>
</div>