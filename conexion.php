<?php
$servidor = "localhost";
$usuario = "guayuco";
$contra = "digital";
$db = "guayuco_digital";

// Crear conexión
$conexion = new mysqli($servidor, $usuario, $contra, $db);

// Revisar la conexión
if ($conexion->connect_error) {
	die("La conexión falló: " . $conexion->connect_error);
}