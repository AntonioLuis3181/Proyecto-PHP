<?php
// listadoVentas.php

require_once("config.php"); 
// NOTA: Asegúrate de que no hay espacios, líneas o caracteres antes de esta etiqueta <?php

// 1. CRÍTICO: Establecer la cabecera JSON inmediatamente.
header('Content-Type: application/json');

// 2. CRÍTICO: Obtener conexión e inicializar la respuesta
$conexion = obtenerConexion();
if (!$conexion) {
    echo json_encode(["ok" => false, "mensaje" => "Error: No se pudo conectar a la base de datos."]);
    exit;
}
$respuesta = ["ok" => false, "mensaje" => "Petición no reconocida."];

// ------------------------------------------------------------------
// LÓGICA DE BORRADO (POST)
// ------------------------------------------------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion']) && $_POST['accion'] === 'borrar') {
    
    $id_sale = isset($_POST['id_sale']) ? (int)$_POST['id_sale'] : 0;
    
    if ($id_sale > 0) {
        $stmt = $conexion->prepare("DELETE FROM sale WHERE id_sale = ?");
        if ($stmt) {
            $stmt->bind_param('i', $id_sale);
            if ($stmt->execute()) {
                $respuesta = ["ok" => true, "mensaje" => "Venta #" . $id_sale . " borrada correctamente."];
            } else {
                $respuesta = ["ok" => false, "mensaje" => "Error al borrar: " . $stmt->error];
            }
            $stmt->close();
        } else {
            $respuesta = ["ok" => false, "mensaje" => "Error al preparar borrado: " . $conexion->error];
        }
    } else {
        $respuesta = ["ok" => false, "mensaje" => 'ID de venta inválido.'];
    }
    
    // Devolver la respuesta POST y terminar la ejecución
    echo json_encode($respuesta);
    $conexion->close();
    exit;
}

// ------------------------------------------------------------------
// LÓGICA DE LISTADO (GET)
// ------------------------------------------------------------------
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    
    $listaVentas = array();

    // Consulta para obtener todas las ventas
    $sql = "SELECT id_sale, sale_date, product_quantity, id_product, online_sale, address FROM sale ORDER BY sale_date DESC";
    $resultado = $conexion->query($sql);

    if ($resultado) {
        while ($fila = $resultado->fetch_assoc()) {
            $listaVentas[] = $fila;
        }
        $respuesta = ["ok" => true, "datos" => $listaVentas];
        $resultado->free();
    } else {
        $respuesta = ["ok" => false, "mensaje" => 'Error al listar ventas: ' . $conexion->error];
    }
    
    // Devolver la respuesta GET y terminar la ejecución
    echo json_encode($respuesta);
    $conexion->close();
    exit;
}

// Si la petición no fue GET ni POST/borrar, se devuelve la respuesta por defecto
echo json_encode($respuesta);
$conexion->close();
exit;
?>