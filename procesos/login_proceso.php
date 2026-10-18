<?php

session_start();

include("../funciones/sesionActiva.php");

sesionActiva();

include("../conexion.php");

$usuario = $_POST["usuario"];

$contrasena = $_POST["contrasena"];

$todo_bien = TRUE;

$todo_bien2 = TRUE;

$_SESSION["error"] = "";

if ($usuario == "")
{

	$_SESSION["error"] .= "No puedes ingresar sin tu usuario. ";
	$todo_bien = FALSE;

}

if ($contrasena == "")
{

	$_SESSION["error"] .= "No puedes ingresar sin tu contraseña. ";
	$todo_bien = FALSE;

}
	//VALIDACIÓN 1
if ($todo_bien == FALSE)
{

	header("
		Location:../login.php?error=1
		");

}

else
{

	$sql2 = 
	"SELECT * FROM usuarios 
	WHERE usuario = '$usuario'";

	$resultado2 = $conexion->query($sql2);

	$row2 = $resultado2->fetch_assoc();

	if (!$row2['usuario'] or $row2['usuario'] == "")
	{

		$_SESSION["error"] .= "El usuario no existe. ";

		header("
			Location:../login.php?error=1
			");

	}

	else
	{

		if ($contrasena !== $row2['contrasena'])
		{

			$_SESSION["error"] .= "La contraseña es incorrecta. ";

			header("
				Location:../login.php?error=1
				");

		}

		else
		{

			$sql = "SELECT * FROM usuarios 
			WHERE usuario = '$usuario'";

			$resultado = $conexion->query($sql);

			if ($resultado->num_rows > 0)
			{

				$row = $resultado->fetch_assoc();

			}

			$nivel = $row['nivel'];

			$_SESSION['sesion_activa'] = TRUE;

			$_SESSION['usuario'] = $usuario;

			$_SESSION['nivel'] = $nivel;

			mysqli_close($conexion);


			if ($nivel == "cliente")
			{

				header("
					Location:../mainpage.php
					");

			}

			if ($nivel == "admin")
			{

				header("
					Location:../admin.php
					");

			}
		}
	}
}
?>