<?php
require_once("config.php");

header('Content-Type: application/json');

// Obtener conexión
$conexion = obtenerConexion();
if (!$conexion) {
    // Si la conexión falla, se responde inmediatamente con error JSON
    echo json_encode(["ok" => false, "mensaje" => "Error: No se pudo conectar a la base de datos."]);
    exit;
}

// Inicializar la respuesta
$respuesta = ["ok" => false, "mensaje" => "Petición no reconocida o método incorrecto."];



if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'crear') {
    
    $sale_date = $_POST['sale_date'] ?? '';
    $product_quantity = $_POST['product_quantity'] ?? '';
    $id_product = $_POST['id_product'] ?? '';
    $address = $_POST['address'] ?? '';
    // Si el checkbox 'online_sale' no está checkeado, JS no lo envía, por lo que usamos 0 como valor por defecto.
    $online_sale = isset($_POST['online_sale']) ? 1 : 0; 

    if ($sale_date && $product_quantity && $id_product) {
        
        $sale_date_mysql = str_replace('T', ' ', $sale_date) . ':00';

        $sql = "INSERT INTO sale (sale_date, product_quantity, id_product, online_sale, address)
                VALUES (?, ?, ?, ?, ?)";
        $stmt = $conexion->prepare($sql);


        $product_quantity = (int) $product_quantity;
        $id_product = (int) $id_product;
        $online_sale = (int) $online_sale;

        if ($stmt) {
            // Tipos de datos para bind_param: s (string), i (integer)
            $stmt->bind_param('siiis', $sale_date_mysql, $product_quantity, $id_product, $online_sale, $address);
            
            if ($stmt->execute()) {
                // ÉXITO: Devolver respuesta en formato JSON
                $respuesta = ["ok" => true, "mensaje" => "✓ Venta registrada correctamente."];
            } else {
                // ERROR DE EJECUCIÓN: Devolver respuesta en formato JSON
                $respuesta = ["ok" => false, "mensaje" => "✗ Error al insertar en DB: " . $stmt->error];
            }
            $stmt->close();
        } else {
            // ERROR AL PREPARAR: Devolver respuesta en formato JSON
            $respuesta = ["ok" => false, "mensaje" => "✗ Error al preparar la consulta: " . $conexion->error];
        }
    } else {
        // ERROR DE VALIDACIÓN: Devolver respuesta en formato JSON
        $respuesta = ["ok" => false, "mensaje" => "✗ Por favor completa todos los campos obligatorios."];
    }
}

echo json_encode($respuesta);
$conexion->close();
exit;
?>