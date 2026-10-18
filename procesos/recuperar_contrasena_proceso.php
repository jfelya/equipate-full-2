<?php

session_start();
include("../funciones/sesionActiva.php");
sesionActiva();

if (isset($_REQUEST["con"]))
{

	switch ($_REQUEST["con"])
	{

		case 'usuario':
		include("../conexion.php");
		$usuario = $_POST["usuario"];

		$sql = 
		"SELECT * FROM usuarios 
		WHERE usuario = '$usuario'";

		$resultado = $conexion->query($sql);
		$row = $resultado->fetch_assoc();

		if (!$row["usuario"])
		{

			$_SESSION["mensaje"] = "El usuario no existe";
			mysqli_close($conexion);

			header("
				Location:../recuperar_contrasena.php?recuperar=usuario&error=1
				");

		}

		else
		{

			$id_cliente = $row["id_cliente"];
			mysqli_close($conexion);

			header("
				Location:../recuperar_contrasena.php?id_cliente=$id_cliente
				");
		}
		break;
		
		case 'correo':
		include("../conexion.php");
		$correo = $_POST["correo"];

		$sql = 
		"SELECT * FROM usuarios 
		WHERE correo = '$correo'";

		$resultado = $conexion->query($sql);
		$row = $resultado->fetch_assoc();

		if (!$row["correo"])
		{

			$_SESSION["mensaje"] = "El correo no existe";
			mysqli_close($conexion);

			header("
				Location:../recuperar_contrasena.php?recuperar=correo&error=1
				");
			
		}

		else
		{

			$id_cliente = $row["id_cliente"];
			mysqli_close($conexion);

			header("
				Location:../recuperar_contrasena.php?id_cliente=$id_cliente
				");

		}
		break;

		case 'respuesta_secreta':
		include("../conexion.php");
		$id_cliente = $_REQUEST["id_cliente"];

		$sql = 
		"SELECT * FROM usuarios 
		WHERE id_cliente = '$id_cliente'";

		$resultado = $conexion->query($sql);
		$row = $resultado->fetch_assoc();
		$respuesta_secreta = $_POST["respuesta_secreta"];

		if ($respuesta_secreta !== $row["respuesta_secreta"])
		{

			$_SESSION["mensaje"] = "La respueta secreta es incorrecta";
			mysqli_close($conexion);

			header("
				Location:../recuperar_contrasena.php?id_cliente=$id_cliente&error=1
				");

		}

		else
		{

			$_SESSION["cambiar_contrasena"] = TRUE;
			mysqli_close($conexion);

			header("
				Location:../recuperar_contrasena2.php?id_cliente=$id_cliente
				");
		}
		break;

	}

}

else
{

	header("
		Location:../cerrar_sesion.php
		");

}
?>