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

// --- INSERTAR PRODUCTO ---
if ($accion == 'insertar') {
    try {
        $sql = "INSERT INTO productos (nombre_producto, precio, id_categoria, en_stock)
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

// --- BUSCAR PRODUCTO POR NOMBRE ---
$producto_buscado = null;
if ($accion == 'buscar') {
    $buscar_nombre = $_POST['buscar_nombre'] ?? '';
    $stmt = $conexion->prepare("SELECT * FROM productos WHERE nombre_producto LIKE ?");
    $stmt->execute(["%$buscar_nombre%"]);
    $producto_buscado = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$producto_buscado) $mensaje = "No se encontró ningún producto con ese nombre.";
}

// --- BORRAR PRODUCTO ---
if ($accion == 'borrar' && $id_producto) {
    try {
        $stmt = $conexion->prepare("DELETE FROM productos WHERE id_producto = ?");
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
    <link href="assets/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <div class="container mt-4">
        <h1 class="text-center mb-4">Gestión de Productos</h1>

        <?php if ($mensaje): ?>
            <div class="alert alert-info"><?= $mensaje ?></div>
        <?php endif; ?>

        <!-- FORMULARIO DE ALTA / MODIFICACIÓN -->
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">Agregar / Modificar Producto</div>
            <div class="card-body">
                <form method="post">
                    <input type="hidden" name="id_producto" value="<?= $producto_buscado['id_producto'] ?? '' ?>">

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label">Nombre</label>
                            <input type="text" class="form-control" name="nombre_producto"
                                value="<?= $producto_buscado['nombre_producto'] ?? '' ?>" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Precio (€)</label>
                            <input type="number" step="0.01" class="form-control" name="precio"
                                value="<?= $producto_buscado['precio'] ?? '' ?>" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Categoría</label>
                            <select class="form-select" name="id_categoria" required>
                                <option value="">Seleccionar</option>
                                <?php foreach ($categorias as $cat): ?>
                                    <option value="<?= $cat['id_categoria'] ?>"
                                        <?= (isset($producto_buscado['id_categoria']) && $producto_buscado['id_categoria'] == $cat['id_categoria']) ? 'selected' : '' ?>>
                                        <?= $cat['nombre_categoria'] ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-2 d-flex align-items-center">
                            <div class="form-check mt-3">
                                <input class="form-check-input" type="checkbox" name="en_stock"
                                    <?= (!empty($producto_buscado['en_stock'])) ? 'checked' : '' ?>>
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
            </div>
        </div>
    </div>

    <script src="assets/js/bootstrap.bundle.min.js"></script>
</body>

</html>