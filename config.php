<?php
function obtenerConexion()
{
    // Establecer conexión y opciones de mysql
    mysqli_report(MYSQLI_REPORT_OFF);

    // Valores por defecto - puedes sobrescribirlos con variables de entorno
    $host = getenv('DB_HOST') ?: 'db';  // 'db' para Docker, '127.0.0.1' para conexión local
    $user = getenv('DB_USER') ?: 'prueba';
    $pass = getenv('DB_PASS') ?: 'prueba';
    $dbname = getenv('DB_NAME') ?: 'nova_vibe';
    $port = getenv('DB_PORT') ?: 3306;

    // Crear conexión
    $conexion = new mysqli($host, $user, $pass, $dbname, $port);

    // Si falla la conexión, registrar el error y devolver null
    if ($conexion->connect_errno) {
        error_log('Error de conexión MySQL: (' . $conexion->connect_errno . ") " . $conexion->connect_error);
        return null;
    }

    $conexion->set_charset('utf8');
    return $conexion;
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
