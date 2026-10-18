<?php

session_start();

include("../funciones/sesionInactiva.php");

sesionInactiva();

include("../conexion.php");

if (isset($_REQUEST["agregar"]) 
	&& isset($_REQUEST["id_producto"])) 
{
	
	$id_producto = $_REQUEST['id_producto'];

	$query = "SELECT * FROM producto WHERE id_producto = $id_producto";

	$resultado = $conexion->query($query);

	$producto = $resultado->fetch_assoc();

	if (isset($_SESSION["carrito"])) 
	{

		for ($i=0; $i < 5; $i++) 
		{

			if ($producto["nombre"] == $_SESSION["carrito"][$i]["nombre"] 
				&& $_SESSION["carrito"][$i]["cantidad"] > 0) 
			{

				if ($_SESSION["carrito"][$i]["cantidad"] == $producto["stock"]) 
				{

					$_SESSION["carrito"][$i]["cantidad"] = $producto["stock"];
					break;

				} 

				else 
				{

					$_SESSION["carrito"][$i]["cantidad"] += 1;
					break;

				}

			} 
			elseif ($_SESSION["carrito"][$i]["nombre"] == "") 
			{

				$_SESSION["carrito"][$i] = Array (
					"nombre" => $producto["nombre"],
					"precio" => $producto["precio"],
					"cantidad" => 1);
				break;

			}

		}

	} 

	else 
	{

		$_SESSION["carrito"][0] = 
		Array (
			"nombre" => $producto["nombre"],
			"precio" => $producto["precio"],
			"cantidad" => 1
		);

	}

	header("
		Location:../ver_carrito.php
		");
}

if (isset($_REQUEST["menosuno"]) 
	&& isset($_REQUEST["posicion"]) 
	&& isset($_SESSION["carrito"])) 
{

	$posicion = $_REQUEST["posicion"];

	if ($_SESSION["carrito"][$posicion]["cantidad"] <= 1) 
	{

		unset($_SESSION["carrito"][$posicion]);
		array_values($_SESSION["carrito"]);

	} 
	else
	{

		$_SESSION["carrito"][$posicion]["cantidad"] -= 1;

	}

	header("
		Location:../ver_carrito.php
		");	
} 

?>