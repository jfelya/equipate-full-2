<?php

session_start();

include("../funciones/sesionActiva.php");

include("../funciones/recuperar_contrasena/idCliente.php");

sesionActiva();

if (!$puestoCliente && !$cambiar_contrasena)
{

	header("
		Location:../cerrar_sesion.php
		");

}

include("../conexion.php");
$id_cliente = $_REQUEST["id_cliente"];
$contrasena = $_POST["contrasena"];
$contrasena2 = $_POST["contrasena2"];
$_SESSION["mensaje"] = "";
$todo_bien = TRUE;

//CONTRASENA EXISTENTE DEL MISMO USUARIO
$sql_prueba = "SELECT * FROM usuarios 
WHERE id_cliente = '$id_cliente'";

$resultado_prueba = $conexion->query($sql_prueba);
$row_prueba = $resultado_prueba->fetch_assoc();

//CONTRASENA EXISTENTE DEL MISMO USUARIO
if ($contrasena == "" || $contrasena2 == "")
{

	$_SESSION["mensaje"] .= "Debes llenar todos los campos. ";
	mysqli_close($conexion);
	header("Location:../recuperar_contrasena2.php?id_cliente=$id_cliente&error2=1");

}

else
{	
	
	if (strlen($contrasena) < 2 
		|| strlen($contrasena2) < 2)
	{

		$_SESSION["mensaje"] .= "Las contraseñas no pueden tener menos de 2 caracteres. ";
		$todo_bien = FALSE;

	}

	if (strlen($contrasena) > 20 
		|| strlen($contrasena2) > 20)
	{

		$_SESSION["mensaje"] .= "Las contraseñas no pueden tener más de 20 caracteres.";
		$todo_bien = FALSE;
	}

	if ($contrasena !== $contrasena2)
	{

		$_SESSION["mensaje"] .= "Las contraseñas no coinciden. ";
		$todo_bien = FALSE;

	}

	if ($contrasena == $row_prueba["contrasena"] || $contrasena2 == $row_prueba["contrasena"])
	{

		$_SESSION["mensaje"] .= "La contraseña ya existe. ";
		$todo_bien = FALSE;

	}

	elseif ($contrasena != $row_prueba["contrasena"] 
		|| $contrasena2 != $row_prueba["contrasena"]) 
	{
		//CONTRASENA EXISTENTE EN OTROS USUARIOS
		$sql_prueba2 = "SELECT * FROM usuarios";
		$resultado_prueba2 = $conexion->query($sql_prueba2);

		while ($row2 = $resultado_prueba2->fetch_assoc())
		{

			if ($contrasena == $row2['contrasena']) 
			{

				$_SESSION["mensaje"] .= "Las contraseña ya existe en otro usuario. ";
				$todo_bien = FALSE;

			}
		}
	}
	
	if ($todo_bien == FALSE)
	{

		mysqli_close($conexion);
		header("
			Location:../recuperar_contrasena2.php?id_cliente=$id_cliente&error2=1
			");
	}

	else
	{

		$sql = 
		"UPDATE usuarios 
		SET contrasena = '$contrasena' 
		WHERE id_cliente = '$id_cliente'";

		$resultado = $conexion->query($sql);

		if ($resultado)
		{

			mysqli_close($conexion);
			$_SESSION["recuperar_exito"] = "La contraseña se ha actualizado con éxito";
			unset($_SESSION["recuperar_contrasena"]);

			header("
				Location:../login.php?exito=1
				");
		}

		else

		{

			mysqli_close($conexion);

			$_SESSION["mensaje"] .= "Ha sucedido un error inesperado. Por favor vuelva a intentarlo";

			unset($_SESSION["recuperar_contrasena"]);

			header("
				Location:../recuperar_contrasena.php?&error=1
				");
		}
	}
}
?>