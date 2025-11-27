<?php
$basedatos = array(
    "basedatos" => "nova_vibe",
    "usuario" => "prueba",
    "password" => "prueba",
    "servidor" => "db",
    "puerto" => 3306
);
error_reporting(E_ERROR | E_PARSE);

mysqli_report(MYSQLI_REPORT_OFF);

function obtenerConexion()
{
  
    global $basedatos;

    try {
        
        $conexion = new mysqli(
            $basedatos["servidor"],
            $basedatos["usuario"],
            $basedatos["password"],
            $basedatos["basedatos"],
            $basedatos["puerto"]
        );

        
        $conexion->set_charset("utf8mb4");
        return $conexion;
    } catch (mysqli_sql_exception $e) {
        
        responder(null, false, "Error de conexión: " . $e->getMessage(), null);
    }
}
function obtenerArrayOpciones($tabla, $guarda, $muestra)
{
	global $conexion;
	$arrayCombo = array();
	$sql = "SELECT $guarda,$muestra FROM $tabla order by $muestra";
	$resultado = mysqli_query($conexion, $sql);
	while ($row = mysqli_fetch_assoc($resultado)) {
		$indice = $row[$guarda];
		$arrayCombo[$indice] = $row[$muestra];
	}
	return $arrayCombo;
}

function responder($datos, $ok, $mensaje, $conexion = null)
{
    // Cabecera indicando que la respuesta es JSON y con codificación UTF-8
    header('Content-Type: application/json; charset=utf-8');

    // Estructura de la respuesta que el frontend esperará:
    // { ok: bool, datos: any, mensaje: string }
    echo json_encode([
        "ok" => $ok,
        "datos" => $datos,
        "mensaje" => $mensaje
    ], JSON_UNESCAPED_UNICODE);

    // Si nos pasan la conexión, la cerramos aquí para evitar fugas
    if ($conexion) {
        $conexion->close();
    }

    // exit con código 0 si ok==true, o 1 si ok==false.
    // Esto detiene la ejecución del script inmediatamente.
    exit($ok ? 0 : 1);
}
