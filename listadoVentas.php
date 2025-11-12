<?php
require_once("config.php");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="css/bootstrap.min.css" rel="stylesheet" />
    <title>Listado Ventas</title>
</head>

<body>
    <div class="container">
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
    </div>
    <script src="js/bootstrap.min.js"></script>
</body>

</html>