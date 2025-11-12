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

    <h2>Listado de Ventas</h2>
    <table>
        <thead>
            <tr>
                <th>ID Venta</th>
                <th>Fecha</th>
                <th>Cantidad</th>
                <th>ID Producto</th>
                <th>Venta Online</th>
                <th>Dirección</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($listaVentas)): ?>
                <?php
                foreach ($listaVentas as $venta): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($venta['id_sale']); ?></td>
                        <td><?php echo htmlspecialchars($venta['sale_date']); ?></td>
                        <td><?php echo htmlspecialchars($venta['product_quantity']); ?></td>
                        <td><?php echo htmlspecialchars($venta['id_product']); ?></td>
                        <td><?php echo $venta['online_sale'] ? 'Sí' : 'No'; ?></td>
                        <td><?php echo htmlspecialchars($venta['address']); ?></td>
                        <td>
                            <a class="delete" href="index.php?action=borrar&id=<?php echo $venta['id_sale']; ?>"
                                onclick="return confirm('¿Estás seguro de que quieres borrar esta venta?');">
                                Borrar
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7">No hay ventas registradas.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
    <script src="js/bootstrap.min.js"></script>
</body>

</html>