<?php
// FUNCIONES PROPIAS
function obtenerConexion()
{
	// Establecer conexión y opciones de mysql
	mysqli_report(MYSQLI_REPORT_OFF); // Errores mysql sin excepciones
	$conexion = new mysqli("db", "root", "", "nova_vibe");
	mysqli_set_charset($conexion, 'utf8');

	return $conexion;
}

function recuperarNumCliente($email)
{

	$conexion = obtenerConexion();
	$sql = "SELECT NUM_CLIENTE FROM clientes WHERE EMAIL = '$email'";
	$resultado = mysqli_query($conexion, $sql);

	$fila = mysqli_fetch_assoc($resultado);

	return $fila["NUM_CLIENTE"];
}

function mostrarSelect($resultSet)
{
	$nfilas = mysqli_num_rows($resultSet);
	if ($nfilas == 0)
		$devuelve = "la consulta no ha devuelto ninguna fila";
	else {
		$devuelve = "<table border='1'>";
		$fila = (mysqli_fetch_assoc($resultSet));
		$devuelve .= "<tr>";
		foreach ($fila as $nombreColumna => $contenido) {
			$devuelve .= "<th>" . $nombreColumna . "</th>";
		}
		$devuelve .= "</tr>";


		while ($fila) {
			$devuelve .= "<tr>";
			foreach ($fila as $contenido) {
				$devuelve .= "<td>" . $contenido . "</td>";
			}
			$devuelve .= "</tr>";
			$fila = (mysqli_fetch_assoc($resultSet));
		}
		$devuelve .= "</table>";
	}
	return $devuelve;
}
//////////////////////////////////////
function obtenerValorColumna($tabla, $nombrePK, $valorPK, $columna)
{
	global $conexion;
	$sql1 = "SELECT " . $columna . " FROM $tabla ";
	$sql1 .= "WHERE " . $nombrePK . " ='" . $valorPK . "'";
	echo $sql1;
	$resultado1 = mysqli_query($conexion, $sql1);
	$fila1 = mysqli_fetch_assoc($resultado1);
	return	$fila1[$columna];
}
/////////////////////////////////////
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
/////////////////////////////////////////
function obtenerArrayOpcionesFiltrado($tabla, $guarda, $muestra, $condicion)
{
	global $conexion;
	$arrayCombo = array();
	$sql = "SELECT $guarda,$muestra FROM $tabla ";
	$sql .= " WHERE " . $condicion . " order by $muestra";
	$resultado = mysqli_query($conexion, $sql);
	while ($row = mysqli_fetch_assoc($resultado)) {
		$indice = $row[$guarda];
		$arrayCombo[$indice] = $row[$muestra];
	}
	return $arrayCombo;
}
/////////////////////////////////////////
function pintarCombo($arrayOpciones, $nombreCombo)
{
	echo "<p><select name='" . $nombreCombo . "'>";
	foreach ($arrayOpciones as $indice => $valor) {
		echo "<option value='" . $indice . "'>" . $valor . "</option>";
	}
	echo "</select></p>";
}
///////////////////////////
function pintarComboMensaje($arrayOpciones, $nombreCombo, $textoPrimeraOpcion, $valorPrimeraOpcion)
{
	echo "<select name='" . $nombreCombo . "'>";
	echo "<option value='$valorPrimeraOpcion'>$textoPrimeraOpcion</option>";
	foreach ($arrayOpciones as $indice => $valor) {
		echo "<option value='" . $indice . "'>" . $valor . "</option>";
	}
	echo "</select>";
}
////////////////////////
function pintarComboSelected($arrayOpciones, $nombreCombo, $seleccionado)
{
	echo "<select name='" . $nombreCombo . "'>";
	foreach ($arrayOpciones as $indice => $valor) {
		$cadena = "<option ";
		if ($indice == $seleccionado)
			$cadena .= " selected ";
		$cadena .= "value='" . $indice . "'>" . $valor . "</option>";
		echo $cadena;
	}
	echo "</select>";
}
///////////////////////////////
function pintarComboMultiple($arrayOpciones, $nombreCombo)
{
	echo "<p><select multiple name='" . $nombreCombo . "'>";
	foreach ($arrayOpciones as $indice => $valor) {
		echo "<option value='" . $indice . "'>" . $valor . "</option>";
	}
	echo "</select></p>";
}
/////////////////////////////////////
function pintarCheckBox($arrayOpciones, $nombreArray)
{
	echo "<p>";
	foreach ($arrayOpciones as $indice => $valor) {
		echo "<input type='checkbox' name='" . $nombreArray . "[]' value='" . $indice . "'>" . $valor . "<br>\n";
	}
	echo "</p>";
}
//////////////////////////////
function pintarRadio($arrayOpciones, $nombreRadio)
{
	echo "<p>";
	foreach ($arrayOpciones as $indice => $valor) {
		echo "<input type='radio' name='" . $nombreRadio . "' value='" . $indice . "'>" . $valor . "<br>\n";
	}
	echo "</p>";
}
////////////////////////////////////////
function pintarRadioMensaje($arrayOpciones, $nombreRadio, $textoPrimeraOpcion, $valorPrimeraOpcion)
{
	echo "<p>";
	echo "<input type='radio' name='" . $nombreRadio . "' value='" . $valorPrimeraOpcion . "'>" . $textoPrimeraOpcion . "<br>\n";
	foreach ($arrayOpciones as $indice => $valor) {
		echo "<input type='radio' name='" . $nombreRadio . "' value='" . $indice . "'>" . $valor . "<br>\n";
	}
	echo "</p>";
}
/*
Devuelve:
0 Si el usuario y la contraseña son correctos
1 Si el usuario existe y la contraseña está mal
2 Si el usuario no existe
3 Si ha dejado algún campo vacio
4 otro error
*/
