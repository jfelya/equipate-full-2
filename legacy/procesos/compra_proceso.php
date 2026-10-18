<?php

session_start();

include("../funciones/sesionInactiva.php");

include("../conexion.php");

sesionInactiva();

if (isset($_REQUEST["compra"]) 
	&& isset($_SESSION["carrito"]) 
	&& isset($_SESSION["preciototal"])) 
{

	//GENERACION DEL ID DE LA FACTURA PRINCIPAL
	$id_factura = rand(1, 989898);

	//LLENADO Y ESTABLECIMIENTO DE VARIABLES PARA LA FACTURA PRINCIPAL
	$carrito = $_SESSION["carrito"];
	$preciototal = $_SESSION["preciototal"];
	$usuario = $_SESSION["usuario"];
	$nombreProducto = "";
	$cantidadProducto = "";

	//PARA OBTENER EL ID DEL CLIENTE
	$sql5 = 
	"SELECT * FROM usuarios WHERE usuario = '$usuario'";
	$resultado5 = $conexion->query($sql5);
	$row5 = $resultado5->fetch_assoc();
	$id_cliente = $row5["id_cliente"];
	$fecha = date("d-m-Y");

	//LLENADO DE MAS VARIABLES
	foreach ($carrito as $posicion => $posicionvalor) 
	{

		foreach ($posicionvalor as $campo => $campovalor) 
		{

			if ($campo == "nombre")
			{

				$nombreProducto = $campovalor;	

			} 

			elseif ($campo == "cantidad") 
			{

				$cantidadProducto = $campovalor;

			}

		}

		//INSERTAR LA FACTURA PRINCIPAL EN LA BASE DE DATOS
		$sql4 = 
		"INSERT INTO `factura` 
		(`id_factura`, 
		`id_cliente`, 
		`fecha`, 
		`preciototal`) 
		VALUES 
		('$id_factura', 
		'$id_cliente', 
		'$fecha', 
		'$preciototal')";

		$resultado4 = $conexion->query($sql4);
		
		//LLENADO DE VARIABLES PARA DETALLE DE FACTURA
		$sql = 
		"SELECT * FROM producto WHERE nombre = '$nombreProducto'";

		$resultado = $conexion->query($sql);

		$row = $resultado->fetch_assoc();

		$stock = $row["stock"];

		$id_producto = $row["id_producto"];

		$precio = $row["precio"];

		$nuevostock = $stock - $cantidadProducto;

		//ACTUALIZACION DEL NUEVO STOCK RESTANDO LO QUE SE COMPRO
		$sql2 = 
		"UPDATE producto 
		SET stock = '$nuevostock' 
		WHERE id_producto = '$id_producto'";

		$resultado2 = $conexion->query($sql2);

		//SI HAY UN ERROR REDIRECCIONAR
		if (!$resultado2)
		{

			mysqli_close($conexion);
			header("
				Location:../mainpage.php
				");

		}

		//INSERTAR DATOS EN LOS DETALLES DE FACTURA
		$sql3 = 
		"INSERT INTO `detalle` 
		(`id_factura`,
		`id_producto`,
		`cantidad`, 
		`precio`) 
		VALUES 
		('$id_factura',
		'$id_producto',
		'$cantidadProducto',
		'$precio')";

		$resultado3 = $conexion->query($sql3);
		//SI HAY UN ERROR REDIRECCIONAR
		if (!$resultado3)
		{

			mysqli_close($conexion);

			header("
				Location:../mainpage.php
				");

		}

	}

	//CERRAR VARIABLES DE SESION Y CONEXION A BASE DATOS
	mysqli_close($conexion);

	unset($_SESSION["carrito"]);

	unset($_SESSION["preciototal"]);

	unset($_SESSION["precio"]);

	unset($_SESSION["cantidad"]);

	//REDIRECCIONAR
	header("
		Location:../ver_facturas_detallado.php?exito&id_factura=$id_factura
		");
}
?>