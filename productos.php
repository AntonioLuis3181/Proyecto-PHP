<?php
require_once "config.php";

// Obtener conexión
$conexion = obtenerConexion();
if (!$conexion) {
    die("Error: No se pudo conectar a la base de datos.");
}

// --- VARIABLES ---
$mensaje = "";
$accion = $_POST['accion'] ?? '';
$id_producto = $_POST['id_producto'] ?? '';
$nombre_producto = $_POST['nombre_producto'] ?? '';
$precio = $_POST['precio'] ?? '';
$id_categoria = $_POST['id_categoria'] ?? '';
$en_stock = isset($_POST['en_stock']) ? 1 : 0;

// --- Mostrar PRODUCTO ---
$sql_listado = "SELECT p.*,c.category_name FROM product p, category c WHERE p.id_category=c.id_category";

// --- INSERTAR PRODUCTO ---
if ($accion == 'insertar') {
    $sql = "INSERT INTO product (product_name, price, id_category, in_stock)
            VALUES (?, ?, ?, ?)";
    $stmt = $conexion->prepare($sql);
    if ($stmt) {
        $stmt->bind_param('ssii', $nombre_producto, $precio, $id_categoria, $en_stock);
        if ($stmt->execute()) {
            $mensaje = "Producto añadido correctamente.";
        } else {
            $mensaje = "Error al insertar: " . $stmt->error;
        }
        $stmt->close();
    }
}

//--- MOSTRAR CATEGORIAS ---
$sql_categorias = "SELECT * FROM category";
$resultado_categorias = $conexion->query($sql_categorias);
$categorias = [];
if ($resultado_categorias) {
    while ($cat = $resultado_categorias->fetch_assoc()) {
        $categorias[] = $cat;
    }
}

// --- BUSCAR PRODUCTO POR NOMBRE ---
$producto_buscado = null;
if ($accion == 'buscar') {
    $buscar_nombre = $_POST['buscar_nombre'] ?? '';
    $buscar_nombre = "%" . $buscar_nombre . "%";
    $sql_buscar = "SELECT p.*, c.category_name FROM product p, category c WHERE p.product_name LIKE ? and p.id_category=c.id_category";
    $stmt = $conexion->prepare($sql_buscar);
    if ($stmt) {
        $stmt->bind_param('s', $buscar_nombre);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $producto_buscado = [];
        while ($row = $resultado->fetch_assoc()) {
            $producto_buscado[] = $row;
        }
        $stmt->close();
        
        if (empty($producto_buscado)) {
            $mensaje = "No se encontró ningún producto con ese nombre.";
            $producto_buscado = null;
        }
    }
}

// --- BORRAR PRODUCTO ---
if ($accion == 'borrar' && $id_producto) {
    $sql_delete = "DELETE FROM product WHERE id_product = ?";
    $stmt = $conexion->prepare($sql_delete);
    if ($stmt) {
        $stmt->bind_param('i', $id_producto);
        if ($stmt->execute()) {
            $mensaje = "Producto eliminado correctamente.";
        } else {
            $mensaje = "Error al eliminar: " . $stmt->error;
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Gestión de Productos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">

</head>

<body class="bg-light">
    <div class="container mt-4">
        <h1 class="text-center mb-4">Gestión de Productos</h1>

        <?php if ($mensaje): ?>
            <div class="alert alert-info"><?= $mensaje ?></div>
        <?php endif; ?>

        <div class="card mb-4">
            <div class="card-header bg-success text-white">Lista de Productos</div>
            <div class="card-body">
                <table class='table table-striped'>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>NOMBRE</th>
                            <th>PRECIO</th>
                            <th>CATEGORIA</th>
                            <th>EN STOCK</th>
                            <th>F.REGISTRO</th>
                            <th>ACCIÓN</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php

                        $statement = $conexion->query($sql_listado);

                        if ($statement) {
                            while ($fila = $statement->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . htmlspecialchars($fila['id_product']) . "</td>";
                                echo "<td>" . htmlspecialchars($fila['product_name']) . "</td>";
                                echo "<td>" . htmlspecialchars($fila['price']) . " €</td>";
                                echo "<td>" . htmlspecialchars($fila['category_name']) . "</td>";
                                echo "<td>" . ($fila['in_stock'] == 0 ? 'No' : 'Sí') . "</td>";
                                echo "<td>" . htmlspecialchars($fila['registration_date']) . "</td>";

                                echo "<td>";
                                echo "<form class='d-inline me-1' action='productos.php' method='post'>";
                                echo "<input type='hidden' name='id_producto' value='" . htmlspecialchars($fila['id_product']) . "' />";
                                echo "<input type='hidden' name='accion' value='editar' />";
                                echo "<button type='submit' class='btn btn-primary'><i class='bi bi-pencil-square'></i></button>";
                                echo "</form>";

                                echo "<form class='d-inline' action='productos.php' method='post'>";
                                echo "<input type='hidden' name='id_producto' value='" . htmlspecialchars($fila['id_product']) . "' />";
                                echo "<input type='hidden' name='accion' value='borrar' />";
                                echo "<button type='submit' class='btn btn-danger' onclick=\"return confirm('¿Estás seguro?');\"><i class='bi bi-trash'></i></button>";
                                echo "</form>";
                                echo "</td>";
                                echo "</tr>";
                            }
                        }

                        ?>
                    </tbody>
                </table>
            </div>
        </div>
        <!-- FORMULARIO DE ALTA / MODIFICACIÓN -->
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">Agregar / Modificar Producto</div>
            <div class="card-body">
                <form method="post">
                    <input type="hidden" name="id_producto" value="<?= isset($producto_buscado) && is_array($producto_buscado) && count($producto_buscado) > 0 ? htmlspecialchars($producto_buscado[0]['id_product']) : '' ?>">

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label">Nombre</label>
                            <input type="text" class="form-control" name="nombre_producto"
                                value="<?= isset($producto_buscado) && is_array($producto_buscado) && count($producto_buscado) > 0 ? htmlspecialchars($producto_buscado[0]['product_name']) : '' ?>" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Precio (€)</label>
                            <input type="number" step="0.01" class="form-control" name="precio"
                                value="<?= isset($producto_buscado) && is_array($producto_buscado) && count($producto_buscado) > 0 ? htmlspecialchars($producto_buscado[0]['price']) : '' ?>" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Categoría</label>
                            <select class="form-select" name="id_categoria" required>
                                <option value="" disabled="disabled">Seleccionar</option>
                                <?php foreach ($categorias as $cat): ?>
                                    <option value="<?= htmlspecialchars($cat['id_category']) ?>"
                                        <?= (isset($producto_buscado) && is_array($producto_buscado) && count($producto_buscado) > 0 && $producto_buscado[0]['id_category'] == $cat['id_category']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($cat['category_name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-2 d-flex align-items-center">
                            <div class="form-check mt-3">
                                <input class="form-check-input" type="checkbox" name="en_stock"
                                    <?= (isset($producto_buscado) && is_array($producto_buscado) && count($producto_buscado) > 0 && !empty($producto_buscado[0]['in_stock'])) ? 'checked' : '' ?>>
                                <label class="form-check-label">En stock</label>
                            </div>
                        </div>
                    </div>

                    <input type="hidden" name="accion" value="<?= isset($producto_buscado) && is_array($producto_buscado) && count($producto_buscado) > 0 ? 'modificar' : 'insertar' ?>" />
                    <button type="submit" class="btn btn-success">
                        <?= (isset($producto_buscado) && is_array($producto_buscado) && count($producto_buscado) > 0) ? 'Guardar Cambios' : 'Añadir Producto' ?>
                    </button>
                </form>
            </div>
        </div>

        <!-- FORMULARIO DE BÚSQUEDA -->
        <div class="card mb-4">
            <div class="card-header bg-secondary text-white">Buscar Producto</div>
            <div class="card-body">
                <form method="post">
                    <div class="input-group">
                        <input type="text" class="form-control" name="buscar_nombre" placeholder="Nombre del producto" required>
                        <input type="hidden" name="accion" value="buscar" />
                        <button type="submit" class="btn btn-dark">Buscar</button>
                    </div>
                </form>

                <?php if ($producto_buscado): ?>
                    <table class='table table-striped mt-3'>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>NOMBRE</th>
                                <th>PRECIO</th>
                                <th>CATEGORIA</th>
                                <th>EN STOCK</th>
                                <th>F.REGISTRO</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php

                            foreach ($producto_buscado as $fila) {
                                echo "<tr>";
                                echo "<td>" . htmlspecialchars($fila['id_product']) . "</td>";
                                echo "<td>" . htmlspecialchars($fila['product_name']) . "</td>";
                                echo "<td>" . htmlspecialchars($fila['price']) . " €</td>";
                                echo "<td>" . htmlspecialchars($fila['category_name']) . "</td>";
                                echo "<td>" . ($fila['in_stock'] == 0 ? 'No' : 'Sí') . "</td>";
                                echo "<td>" . htmlspecialchars($fila['registration_date']) . "</td>";
                                echo "</tr>";
                            }

                            ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.min.js" integrity="sha384-G/EV+4j2dNv+tEPo3++6LCgdCROaejBqfUeNjuKAiuXbjrxilcCdDz6ZAVfHWe1Y" crossorigin="anonymous"></script>
</body>

</html>