<?php
require_once "config.php";

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
    try {
        $sql = "INSERT INTO product (product_name, price, id_category, in_stock)
                VALUES (:nombre_producto, :precio, :id_categoria, :en_stock)";
        $stmt = $conexion->prepare($sql);
        $stmt->execute([
            ':nombre_producto' => $nombre_producto,
            ':precio' => $precio,
            ':id_categoria' => $id_categoria,
            ':en_stock' => $en_stock
        ]);
        $mensaje = "Producto añadido correctamente.";
    } catch (PDOException $e) {
        $mensaje = "Error al insertar: " . $e->getMessage();
    }
}

//--- MOSTRAR CATEGORIAS ---

$sql_categorias = "SELECT * FROM category";
$categorias = $conexion->query($sql_categorias);

// --- BUSCAR PRODUCTO POR NOMBRE ---
$producto_buscado = null;
if ($accion == 'buscar') {
    $buscar_nombre = $_POST['buscar_nombre'] ?? '';
    $stmt = $conexion->prepare("SELECT p.*, c.category_name FROM product p, category c WHERE p.product_name LIKE ? and p.id_category=c.id_category");
    $stmt->execute(["%$buscar_nombre%"]);
    $producto_buscado = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (!$producto_buscado) $mensaje = "No se encontró ningún producto con ese nombre.";
}

// --- BORRAR PRODUCTO ---
if ($accion == 'borrar' && $id_producto) {
    try {
        $stmt = $conexion->prepare("DELETE FROM product WHERE id_product = ?");
        $stmt->execute([$id_producto]);
        $mensaje = "Producto eliminado correctamente.";
    } catch (PDOException $e) {
        $mensaje = "Error al eliminar: " . $e->getMessage();
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

                        foreach ($statement as $fila) {
                            $mensaje = "";
                            $mensaje .= "<tr><td>" . $fila['id_product'] . "</td>";
                            $mensaje .= "<td>" . $fila['product_name'] . "</td>";
                            $mensaje .= "<td>" . $fila['price'] . " €</td>";
                            $mensaje .= "<td>" . $fila['category_name'] . "</td>";
                            $mensaje .= "<td>" . ($fila['in_stock'] == 0 ? 'No' : 'Si') . "</td>";
                            $mensaje .= "<td>" . $fila['registration_date'] . "</td>";

                            $mensaje .= "<td><form class='d-inline me-1' action='productos.php' method='post'>";
                            $mensaje .= "<input type='hidden' name='producto' value='" . htmlspecialchars(json_encode($fila), ENT_QUOTES) . "' />";
                            $mensaje .= "<input type='hidden' name='accion' value='editar' />";
                            $mensaje .= "<button name='Editar' class='btn btn-primary'><i class='bi bi-pencil-square'></i></button></form>";

                            $mensaje .= "<form class='d-inline' action='productos.php' method='post'>";
                            $mensaje .= "<input type='hidden' name='id_producto' value='" . $fila['id_product']  . "' />";
                            $mensaje .= "<input type='hidden' name='accion' value='borrar' />";
                            $mensaje .= "<button name='borrar' class='btn btn-danger'><i class='bi bi-trash'></i></button></form>";

                            $mensaje .= "</td></tr>";

                            echo $mensaje;
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
                    <input type="hidden" name="id_producto" value="<?= $producto_buscado['id_product'] ?? '' ?>">

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label">Nombre</label>
                            <input type="text" class="form-control" name="nombre_producto"
                                value="<?= $producto_buscado['product_name'] ?? '' ?>" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Precio (€)</label>
                            <input type="number" step="0.01" class="form-control" name="precio"
                                value="<?= $producto_buscado['price'] ?? '' ?>" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Categoría</label>
                            <select class="form-select" name="id_categoria" required>
                                <option value="" disabled="disabled">Seleccionar</option>
                                <?php foreach ($categorias as $cat): ?>
                                    <option value="<?= $cat['id_category'] ?>"
                                        <?= (isset($producto_buscado['id_category']) && $producto_buscado['id_category'] == $cat['id_category']) ? 'selected' : '' ?>>
                                        <?= $cat['category_name'] ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-2 d-flex align-items-center">
                            <div class="form-check mt-3">
                                <input class="form-check-input" type="checkbox" name="en_stock"
                                    <?= (!empty($producto_buscado['in_stock'])) ? 'checked' : '' ?>>
                                <label class="form-check-label">En stock</label>
                            </div>
                        </div>
                    </div>

                    <button type="submit" name="accion" value="<?= $producto_buscado ? 'modificar' : 'insertar' ?>"
                        class="btn btn-success">
                        <?= $producto_buscado ? 'Guardar Cambios' : 'Añadir Producto' ?>
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
                        <button type="submit" name="accion" value="buscar" class="btn btn-dark">Buscar</button>
                    </div>
                </form>

                <?php if ($producto_buscado): ?>
                    <table class='table table-striped'>
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
                                $mensaje = "";
                                $mensaje .= "<tr><td>" . $fila['id_product'] . "</td>";
                                $mensaje .= "<td>" . $fila['product_name'] . "</td>";
                                $mensaje .= "<td>" . $fila['price'] . " €</td>";
                                $mensaje .= "<td>" . $fila['category_name'] . "</td>";
                                $mensaje .= "<td>" . ($fila['in_stock'] == 0 ? 'No' : 'Si') . "</td>";
                                $mensaje .= "<td>" . $fila['registration_date'] . "</td>";

                                $mensaje .= "</td></tr>";

                                echo $mensaje;
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