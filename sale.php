<?php
require_once("config.php");
include_once("index.html");

// Obtener conexión
$conexion = obtenerConexion();
if (!$conexion) {
    die("Error: No se pudo conectar a la base de datos.");
}

$mensaje = "";

// --- INSERTAR NUEVA VENTA ---
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'crear') {
    $sale_date = $_POST['sale_date'] ?? '';
    $product_quantity = $_POST['product_quantity'] ?? '';
    $id_product = $_POST['id_product'] ?? '';
    $address = $_POST['address'] ?? '';
    $online_sale = isset($_POST['online_sale']) ? 1 : 0;

    // Validar que los campos obligatorios estén rellenados
    if ($sale_date && $product_quantity && $id_product) {
        // Convertir el formato de datetime-local a formato MySQL
        $sale_date_mysql = str_replace('T', ' ', $sale_date) . ':00';

        $sql = "INSERT INTO sale (sale_date, product_quantity, id_product, online_sale, address)
                VALUES (?, ?, ?, ?, ?)";
        $stmt = $conexion->prepare($sql);

        // Asegurar tipos: cast a enteros donde corresponde
        $product_quantity = (int) $product_quantity;
        $id_product = (int) $id_product;
        $online_sale = (int) $online_sale;

        if ($stmt) {
            // tipos: sale_date (s), product_quantity (i), id_product (i), online_sale (i), address (s)
            $stmt->bind_param('siiis', $sale_date_mysql, $product_quantity, $id_product, $online_sale, $address);
            if ($stmt->execute()) {
                $mensaje = "✓ Venta registrada correctamente.";
            } else {
                $mensaje = "✗ Error al insertar: " . $stmt->error;
            }
            $stmt->close();
        } else {
            $mensaje = "✗ Error al preparar la consulta: " . $conexion->error;
        }
    } else {
        $mensaje = "✗ Por favor completa todos los campos obligatorios.";
    }
}

// --- OBTENER LISTA DE PRODUCTOS DISPONIBLES ---
$sql_productos = "SELECT id_product, product_name, price FROM product WHERE in_stock = 1 ORDER BY product_name";
$resultado_productos = $conexion->query($sql_productos);
$productos = [];
if ($resultado_productos) {
    while ($prod = $resultado_productos->fetch_assoc()) {
        $productos[] = $prod;
    }
}

// --- OBTENER ÚLTIMAS VENTAS ---
$sql_ventas = "SELECT s.*, p.product_name, p.price 
               FROM sale s 
               LEFT JOIN product p ON s.id_product = p.id_product 
               ORDER BY s.sale_date DESC 
               LIMIT 10";
$resultado_ventas = $conexion->query($sql_ventas);
$ventas_recientes = [];
if ($resultado_ventas) {
    while ($venta = $resultado_ventas->fetch_assoc()) {
        $ventas_recientes[] = $venta;
    }
}

// Obtener conexión
$conexion = obtenerConexion();
if (!$conexion) {
    die("Error: No se pudo conectar a la base de datos.");
}

$mensaje = "";

// --- INSERTAR NUEVA VENTA ---
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'crear') {
    $sale_date = $_POST['sale_date'] ?? '';
    $product_quantity = $_POST['product_quantity'] ?? '';
    $id_product = $_POST['id_product'] ?? '';
    $address = $_POST['address'] ?? '';
    $online_sale = isset($_POST['online_sale']) ? 1 : 0;

    // Validar que los campos obligatorios estén rellenados
    if ($sale_date && $product_quantity && $id_product) {
        // Convertir el formato de datetime-local a formato MySQL
        $sale_date_mysql = str_replace('T', ' ', $sale_date) . ':00';

        $sql = "INSERT INTO sale (sale_date, product_quantity, id_product, online_sale, address)
                VALUES (?, ?, ?, ?, ?)";
        $stmt = $conexion->prepare($sql);

        // Asegurar tipos: cast a enteros donde corresponde
        $product_quantity = (int) $product_quantity;
        $id_product = (int) $id_product;
        $online_sale = (int) $online_sale;

        if ($stmt) {
            // tipos: sale_date (s), product_quantity (i), id_product (i), online_sale (i), address (s)
            $stmt->bind_param('siiis', $sale_date_mysql, $product_quantity, $id_product, $online_sale, $address);
            if ($stmt->execute()) {
                $mensaje = "✓ Venta registrada correctamente.";
            } else {
                $mensaje = "✗ Error al insertar: " . $stmt->error;
            }
            $stmt->close();
        } else {
            $mensaje = "✗ Error al preparar la consulta: " . $conexion->error;
        }
    } else {
        $mensaje = "✗ Por favor completa todos los campos obligatorios.";
    }
}

// --- OBTENER LISTA DE PRODUCTOS DISPONIBLES ---
$sql_productos = "SELECT id_product, product_name, price FROM product WHERE in_stock = 1 ORDER BY product_name";
$resultado_productos = $conexion->query($sql_productos);
$productos = [];
if ($resultado_productos) {
    while ($prod = $resultado_productos->fetch_assoc()) {
        $productos[] = $prod;
    }
}

// --- OBTENER ÚLTIMAS VENTAS ---
$sql_ventas = "SELECT s.*, p.product_name, p.price 
               FROM sale s 
               LEFT JOIN product p ON s.id_product = p.id_product 
               ORDER BY s.sale_date DESC 
               LIMIT 10";
$resultado_ventas = $conexion->query($sql_ventas);
$ventas_recientes = [];
if ($resultado_ventas) {
    while ($venta = $resultado_ventas->fetch_assoc()) {
        $ventas_recientes[] = $venta;
    }
}

?>

<div class="container mt-4">
    <h1 class="mb-4">Gestión de Ventas</h1>

    <?php if (!empty($mensaje)): ?>
        <div class="mensaje <?php echo strpos($mensaje, '✓') !== false ? 'exito' : 'error'; ?>">
            <?php echo htmlspecialchars($mensaje); ?>
        </div>
    <?php endif; ?>

    <!-- FORMULARIO PARA CREAR NUEVA VENTA -->
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <h2 class="mb-0">Crear Nueva Venta</h2>
        </div>
        <div class="card-body">
            <form method="POST" class="needs-validation">
                <input type="hidden" name="action" value="crear">

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="sale_date" class="form-label">Fecha y Hora: <span class="text-danger">*</span></label>
                        <input type="datetime-local" id="sale_date" name="sale_date" class="form-control" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="product_quantity" class="form-label">Cantidad: <span class="text-danger">*</span></label>
                        <input type="number" id="product_quantity" name="product_quantity" class="form-control" min="1" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="id_product" class="form-label">Producto: <span class="text-danger">*</span></label>
                        <select id="id_product" name="id_product" class="form-select" required>
                            <option value="">Seleccionar producto...</option>
                            <?php foreach ($productos as $prod): ?>
                                <option value="<?php echo $prod['id_product']; ?>">
                                    <?php echo htmlspecialchars($prod['product_name']) . ' (€' . number_format($prod['price'], 2) . ')'; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="address" class="form-label">Dirección:</label>
                        <input type="text" id="address" name="address" class="form-control" placeholder="Ej: Calle Falsa, 123">
                    </div>
                </div>

                <div class="mb-3">
                    <div class="form-check">
                        <input type="checkbox" id="online_sale" name="online_sale" value="1" class="form-check-input">
                        <label class="form-check-label" for="online_sale">
                            ¿Es una venta online?
                        </label>
                    </div>
                </div>

                <button type="submit" class="btn btn-success">
                    <i class="bi bi-check-circle"></i> Guardar Venta
                </button>
            </form>
        </div>
    </div>
    <div class="card">
        <div class="card-header bg-success text-white">
            <h2 class="mb-0">Últimas Ventas Registradas</h2>
        </div>
        <div class="card-body">
            <?php if (!empty($ventas_recientes)): ?>
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>ID Venta</th>
                            <th>Fecha</th>
                            <th>Producto</th>
                            <th>Cantidad</th>
                            <th>Precio Unit.</th>
                            <th>Total</th>
                            <th>Tipo</th>
                            <th>Dirección</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($ventas_recientes as $venta): ?>
                            <tr>
                                <td><strong><?php echo htmlspecialchars($venta['id_sale']); ?></strong></td>
                                <td><?php echo htmlspecialchars($venta['sale_date']); ?></td>
                                <td><?php echo htmlspecialchars($venta['product_name'] ?? 'Producto eliminado'); ?></td>
                                <td><?php echo htmlspecialchars($venta['product_quantity']); ?></td>
                                <td>€<?php echo $venta['price'] ? number_format($venta['price'], 2) : 'N/A'; ?></td>
                                <td><strong>€<?php echo $venta['price'] ? number_format($venta['price'] * $venta['product_quantity'], 2) : 'N/A'; ?></strong></td>
                                <td>
                                    <?php if ($venta['online_sale'] == 1): ?>
                                        <span class="badge bg-info">Online</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Tienda</span>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo htmlspecialchars($venta['address'] ?? '-'); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p class="text-muted">No hay ventas registradas aún.</p>
            <?php endif; ?>
        </div>
    </div>
</div>
</div>