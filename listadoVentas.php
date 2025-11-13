<?php
require_once("config.php");
include_once("index.html");

// Recuperar listado de ventas desde la base de datos
$listaVentas = array();
$mensaje = '';
$conexion = obtenerConexion();
if ($conexion) {
    // Manejar borrado enviado por POST
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion']) && $_POST['accion'] === 'borrar') {
        $id_sale = isset($_POST['id_sale']) ? (int)$_POST['id_sale'] : 0;
        if ($id_sale > 0) {
            $stmt = $conexion->prepare("DELETE FROM sale WHERE id_sale = ?");
            if ($stmt) {
                $stmt->bind_param('i', $id_sale);
                if ($stmt->execute()) {
                    $mensaje = "Venta #" . $id_sale . " borrada correctamente.";
                } else {
                    $consulta_error = "Error al borrar: " . $stmt->error;
                }
                $stmt->close();
            } else {
                $consulta_error = "Error al preparar borrado: " . $conexion->error;
            }
        } else {
            $consulta_error = 'ID de venta inválido.';
        }
    }

    $sql = "SELECT * FROM sale ORDER BY sale_date DESC";
    $resultado = $conexion->query($sql);
    if ($resultado) {
        while ($fila = $resultado->fetch_assoc()) {
            $listaVentas[] = $fila;
        }
        $resultado->free();
    } else {
        // Para depuración: guardar error (no mostrar en producción)
        $consulta_error = $conexion->error;
    }
    $conexion->close();
} else {
    // Obtener más detalles del error
    $consulta_error = 'No se pudo conectar a la base de datos. Verifica config.php y las variables de entorno.';
}
?>

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
                <?php foreach ($listaVentas as $venta): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($venta['id_sale']); ?></td>
                        <td><?php echo htmlspecialchars($venta['sale_date']); ?></td>
                        <td><?php echo htmlspecialchars($venta['product_quantity']); ?></td>
                        <td><?php echo htmlspecialchars($venta['id_product']); ?></td>
                        <td><?php echo $venta['online_sale'] ? 'Sí' : 'No'; ?></td>
                        <td><?php echo htmlspecialchars($venta['address']); ?></td>
                        <td>
                            <form method="post" style="display:inline" onsubmit="return confirm('¿Estás seguro de que quieres borrar esta venta?');">
                                <input type="hidden" name="accion" value="borrar">
                                <input type="hidden" name="id_sale" value="<?php echo htmlspecialchars($venta['id_sale']); ?>">
                                <button type="submit" class="btn btn-sm btn-danger">Borrar</button>
                            </form>
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

    <?php if (isset($consulta_error) && $consulta_error): ?>
        <div class="alert alert-danger mt-3">Error en la consulta: <?php echo htmlspecialchars($consulta_error); ?></div>
    <?php endif; ?>
</div>