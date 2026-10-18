<?php

session_start();

include("../funciones/sesionInactiva.php");

sesionInactiva();

if (isset($_POST["boton_modificar"]) 
	and $_REQUEST["modificar"] = "1" 
	and isset($_REQUEST["id_producto"]))
{

	include("../conexion.php");

	$id_producto = $_REQUEST["id_producto"];

	$nombre = $_POST["nombre"];

	$stock = $_POST["stock"];

	$precio = $_POST["precio"];

	$_SESSION["producto_error"] = "";

	$todo_bien = TRUE;

	echo "id_producto";

	if ($nombre == "" or $stock == "" or $precio == "")
	{

		$_SESSION["producto_error"] .= "Los campos no pueden estar vacíos. ";
		header("Location:../admin.php?modificar_producto&id_producto=$id_producto&producto_error");
	}

	else
	{

			// COMPROBACIÓN DE MÁXIMO DE CARACTERES
		if (strlen($nombre) > 120)
		{

			$_SESSION["producto_error"] .= "El nombre del producto no puede tener mas de ciento veinte caracteres. ";
			$todo_bien = FALSE;

		}

			// COMPROBACIÓN DE MÍNIMO DE CARACTERES
		if (strlen($nombre) < 4)
		{

			$_SESSION["producto_error"] .= "El nombre del producto no puede tener menos de cuatro caractéres. ";
			$todo_bien = FALSE;

		}


			// COMPROBACIÓN DE MÁXIMO DE NUMEROS STOCK
		if ($stock > 82)
		{

			$_SESSION["producto_error"] .= "El stock no puede ser mayor a ochenta y dos. ";
			$todo_bien = FALSE;

		}

			// COMPROBACIÓN DE MÍNIMO DE NUMEROS STOCK
		if ($stock < 7)
		{

			$_SESSION["producto_error"] .= "El stock no puede ser menor a siete. ";
			$todo_bien = FALSE;

		}


			// COMPROBACIÓN NÚMERICA STOCK
		if (!is_numeric($stock))
		{

			$_SESSION["producto_error"] .= "El stock debe ser ingresado en números. ";
			$todo_bien = FALSE;

		}


			// COMPROBACIÓN NÚMERICA PRECIO
		if (!is_numeric($precio))
		{

			$_SESSION["producto_error"] .= "El precio debe ser ingresado en números. ";
			$todo_bien = FALSE;

		}


			//COMPROBACIÓN PRINCIPAL
		if ($todo_bien == FALSE)
		{

			header("
				Location:../admin.php?modificar_producto&id_producto=$id_producto&producto_error
				");

		}

		else
		{

			$sql = "UPDATE producto 
			SET 
			nombre = '$nombre', 
			stock = '$stock', 
			precio = '$precio' 
			WHERE id_producto = '$id_producto'";

			$resultado = $conexion->query($sql);

			if ($resultado)
			{

				mysqli_close($conexion);

				$_SESSION["producto_exito"] = "El producto ha sido modificado con éxito";

				header("
					Location:../lista_de_productos.php?producto_exito
					");

			}

			else
			{

				mysqli_close($conexion);

				$_SESSION["producto_error"] .= "Ha sucedido un error inesperado. Por favor, vuelva a intentarlo";

				header("
					Location:../lista_de_productos.php?modificar_producto&id_producto=$id_producto&producto_error
					");

			}
		}
	} 
}

else
{

	include("../conexion.php");

	$id_producto = $_REQUEST["id_producto"];

	$nombre = $_POST["nombre"];

	$stock = $_POST["stock"];


	$precio = $_POST["precio"];

	$_SESSION["producto_error"] = "";

	$todo_bien = TRUE;

	$sql_prueba = "SELECT * FROM producto 
	WHERE nombre = '$nombre'";

	$resultado_prueba = $conexion->query($sql_prueba);

	$row_prueba = $resultado_prueba->fetch_assoc();

	if ($row_prueba["nombre"])
	{

		$_SESSION["producto_error"] .=
		"El producto ya existe. ";

		header("
			Location:../admin.php?registrar_producto&producto_error
			");

	}

	else
	{

		if ($nombre == "" or $stock == "" or $precio == "")
		{

			$_SESSION["producto_error"] .= "Los campos no pueden estar vacíos. ";

			header("
				Location:../admin.php?registrar_producto&producto_error
				");
		}

		else
		{

			// COMPROBACIÓN DE MÁXIMO DE CARACTERES
			if (strlen($nombre) > 120)
			{

				$_SESSION["producto_error"] .= "Los nombre del producto no puede tener más de ciento veinte caractéres. ";
				$todo_bien = FALSE;

			}

			// COMPROBACIÓN DE MÍNIMO DE CARACTERES
			if (strlen($nombre) < 4)
			{

				$_SESSION["producto_error"] .= "El nombre del producto no puede tener menos de cuatro caractéres. ";
				$todo_bien = FALSE;

			}

			// COMPROBACIÓN NÚMERICA STOCK
			if (!is_numeric($stock))
			{

				$_SESSION["producto_error"] .= "El stock debe ser ingresado en números. ";
				$todo_bien = FALSE;

			}

			// COMPROBACIÓN NÚMERICA PRECIO
			if (!is_numeric($precio))
			{

				$_SESSION["producto_error"] .= "El precio debe ser ingresado en números. ";
				$todo_bien = FALSE;

			}

			//COMPROBACIÓN PRINCIPAL
			if ($todo_bien == FALSE)
			{

				header("
					Location:../admin.php?registrar_producto&producto_error
					");

			}

			else
			{

				$sql = "INSERT INTO `producto` 
				(`nombre`,
				`stock`,`
				precio`) 
				VALUES 
				('$nombre',
				'$stock',
				'$precio')";

				$resultado = $conexion->query($sql);

				if ($resultado)
				{

					mysqli_close($conexion);

					$_SESSION["producto_exito"] = "El producto ha sido registrado con éxito";

					header("
						Location:../lista_de_productos.php?producto_exito
						");

				}

				else
				{

					mysqli_close($conexion);

					$_SESSION["producto_error"] .= "Ha sucedido un error inesperado. Por favor, vuelva a intentarlo";

					header("
						Location:../admin.php?registrar_producto&producto_error
						");

				}
			}
		}
	}
}
?>