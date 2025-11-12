<?php
require_once("config.php");

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="css/bootstrap.min.css" rel="stylesheet" />
</head>
<title>Ventas</title>
</head>

<body>
    <h1>Panel de Gestión de Ventas</h1>

    <?php if (!empty($mensaje)): ?>
        <div class="mensaje <?php echo strpos($mensaje, 'Error') !== false ? 'error' : 'exito'; ?>">
            <?php echo htmlspecialchars($mensaje); ?>
        </div>
    <?php endif; ?>

    <h2>Crear Nueva Venta</h2>
    <form action="index.php" method="POST">
        <input type="hidden" name="action" value="crear">

        <label for="sale_date">Fecha y Hora:</label>
        <input type="datetime-local" id="sale_date" name="sale_date" required>

        <label for="product_quantity">Cantidad:</label>
        <input type="number" id="product_quantity" name="product_quantity" min="1" required>

        <label for="id_product">ID Producto:</label>
        <input type="number" id="id_product" name="id_product" required>

        <label for="address">Dirección:</label>
        <input type="text" id="address" name="address" placeholder="Ej: Calle Falsa, 123 (o 'Tienda' si no es online)">

        <div>
            <label for="online_sale" style="display: inline-block;">¿Venta Online?</label>
            <input type="checkbox" id="online_sale" name="online_sale" value="1">
        </div>

        <br>
        <button type="submit">Guardar Venta</button>
    </form>
    <script src="js/bootstrap.min.js"></script>
</body>

</html>