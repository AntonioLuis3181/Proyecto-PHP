<?php
// CRÍTICO: Asegúrate de que no hay espacios en blanco o caracteres antes de esta etiqueta
require_once "config.php";

// 1. CRÍTICO: Eliminar la inclusión de HTML.
// ELIMINADO: include_once("index.html");

// 2. CRÍTICO: Establecer la cabecera para indicar que la respuesta será JSON.
header('Content-Type: application/json');

// Obtener conexión
$conexion = obtenerConexion();
if (!$conexion) {
    // Si la conexión falla, se responde inmediatamente con error JSON
    echo json_encode(["ok" => false, "mensaje" => "Error: No se pudo conectar a la base de datos."]);
    exit;
}

// Inicializar la respuesta por defecto
$respuesta = ["ok" => false, "mensaje" => "Petición no reconocida."];

// 3. Manejar peticiones POST (Insertar, Borrar, Buscar)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $accion = $_POST['accion'] ?? '';
    $id_producto = $_POST['id_producto'] ?? '';
    $nombre_producto = $_POST['nombre_producto'] ?? '';
    $precio = $_POST['precio'] ?? '';
    $id_categoria = $_POST['id_categoria'] ?? '';
    $en_stock = isset($_POST['en_stock']) ? 1 : 0;

    // --- INSERTAR / MODIFICAR PRODUCTO ---
    if ($accion == 'insertar' || $accion == 'modificar') {
        // En tu código original solo tienes INSERTAR, asumiremos que 'modificar' vendría aquí.
        
        $sql = "INSERT INTO product (product_name, price, id_category, in_stock) VALUES (?, ?, ?, ?)";
        // Si tuvieras MODIFICAR:
        /*
        if ($accion == 'modificar' && $id_producto) {
            $sql = "UPDATE product SET product_name=?, price=?, id_category=?, in_stock=? WHERE id_product=?";
            // Asegúrate de usar 'ssiii' para bind_param e incluir $id_producto
        } 
        */

        // Lógica de inserción (basada en tu código original, simplificando la redundancia)
        if ($accion == 'insertar') {
            $stmt = $conexion->prepare($sql);
            
            // Usamos 'ssii' para string, string, integer, integer (nombre, precio, cat, stock)
            if ($stmt) {
                $stmt->bind_param('ssii', $nombre_producto, $precio, $id_categoria, $en_stock);
                if ($stmt->execute()) {
                    $respuesta = ["ok" => true, "mensaje" => "Producto añadido correctamente."];
                } else {
                    $respuesta = ["ok" => false, "mensaje" => "Error al insertar: " . $stmt->error];
                }
                $stmt->close();
            } else {
                 $respuesta = ["ok" => false, "mensaje" => "Error al preparar la inserción: " . $conexion->error];
            }
        }
        
    } 
    
    // --- BORRAR PRODUCTO ---
    else if ($accion == 'borrar' && $id_producto) {
        $sql_delete = "DELETE FROM product WHERE id_product = ?";
        $stmt = $conexion->prepare($sql_delete);
        if ($stmt) {
            $stmt->bind_param('i', $id_producto);
            if ($stmt->execute()) {
                $respuesta = ["ok" => true, "mensaje" => "Producto eliminado correctamente."];
            } else {
                $respuesta = ["ok" => false, "mensaje" => "Error al eliminar: " . $stmt->error];
            }
            $stmt->close();
        } else {
             $respuesta = ["ok" => false, "mensaje" => "Error al preparar el borrado: " . $conexion->error];
        }
    }
    
    // --- BUSCAR PRODUCTO POR NOMBRE ---
    else if ($accion == 'buscar') {
        $buscar_nombre = $_POST['buscar_nombre'] ?? '';
        $buscar_nombre = "%" . $buscar_nombre . "%";
        
        // Consulta para buscar (incluyendo la categoría)
        $sql_buscar = "SELECT p.*, c.category_name FROM product p JOIN category c ON p.id_category = c.id_category WHERE p.product_name LIKE ?";
        $stmt = $conexion->prepare($sql_buscar);
        
        if ($stmt) {
            $stmt->bind_param('s', $buscar_nombre);
            $stmt->execute();
            $resultado = $stmt->get_result();
            $productos_encontrados = [];
            while ($row = $resultado->fetch_assoc()) {
                $productos_encontrados[] = $row;
            }
            $stmt->close();

            if (!empty($productos_encontrados)) {
                $respuesta = ["ok" => true, "datos" => $productos_encontrados, "mensaje" => count($productos_encontrados) . " producto(s) encontrado(s)."];
            } else {
                $respuesta = ["ok" => true, "datos" => [], "mensaje" => "No se encontró ningún producto con ese nombre."];
            }
        } else {
            $respuesta = ["ok" => false, "mensaje" => "Error al preparar la búsqueda: " . $conexion->error];
        }
    }

    // Devolver la respuesta de la acción POST y terminar
    echo json_encode($respuesta);
    $conexion->close();
    exit;
}

// 4. Manejar peticiones GET (Listado General de Productos)
// Si no es POST, asumimos que es una petición para obtener el listado
$sql_listado = "SELECT p.*, c.category_name FROM product p JOIN category c ON p.id_category = c.id_category ORDER BY p.id_product DESC";
$productos = [];
$statement = $conexion->query($sql_listado);

if ($statement) {
    while ($fila = $statement->fetch_assoc()) {
        $productos[] = $fila;
    }
    $respuesta = ["ok" => true, "datos" => $productos];
    $statement->free();
} else {
    $respuesta = ["ok" => false, "mensaje" => "Error al listar productos: " . $conexion->error];
}

// 5. Devolver la respuesta JSON (si fue GET) y terminar
echo json_encode($respuesta);
$conexion->close();
exit;

?>