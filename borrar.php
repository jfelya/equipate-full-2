<?php
session_start();
include("funciones/sesionActiva.php");
sesionActiva();

if (isset($_REQUEST["id_producto"]))
{

	$id_producto = $_REQUEST['id_producto'];
	include("conexion.php");

	$query = 
	"DELETE FROM producto 
	WHERE id_producto='$id_producto'";

	$resultado = $conexion->query($query);

	if ($resultado) 
	{

		header("Location:lista_de_productos.php");

	}

	else
	{

		header("Location:lista_de_productos.php");

	}
}

if (isset($_REQUEST["id_cliente"]))
{
	$id_cliente = $_REQUEST['id_cliente'];
	include("conexion.php");

	$query = 
	"DELETE FROM usuarios 
	WHERE id_cliente='$id_cliente'";

	$resultado = $conexion->query($query);

	if ($resultado) 
	{

		header("Location:lista_de_usuarios.php");

	}

	else
	{

		header("Location:lista_de_usuarios.php");
		
	}
}