<?php

session_start();
include("../funciones/sesionActiva.php");
sesionActiva();
include("../conexion.php");

$nombre = $_POST["nombre"];
$apellido = $_POST["apellido"];
$usuario = $_POST["usuario"];
$correo = $_POST["correo"];
$contrasena = $_POST["contrasena"];
$contrasena2 = $_POST["contrasena2"];
$nivel = "cliente";
$pregunta_secreta = $_POST["pregunta_secreta"];
$respuesta_secreta = strtolower($_POST["respuesta_secreta"]);
$_SESSION["error"] = "";
$todo_bien = TRUE;

$sql_prueba = 
"SELECT * FROM usuarios 
WHERE usuario = '$usuario'";

$resultado_prueba = $conexion->query($sql_prueba);
$row_prueba = $resultado_prueba->fetch_assoc();

if ($row_prueba["usuario"])
{

	$_SESSION["error"] .= "El usuario ya existe. ";

	header("
		Location:../login.php?error=1
		");
}

else
{

	if ($nombre == ""
		or $apellido == ""
		or $usuario == ""
		or $correo == ""
		or $contrasena == ""
		or $contrasena2 == ""
		or $nivel == ""
		or $pregunta_secreta == ""
		or $respuesta_secreta == "")
	{

		$_SESSION["error"] .= "Los campos no pueden estar vacíos. ";

		header("
			Location:../login.php?error=1
			");

	}

	else
	{

				// COMPROBACIÓN DE MÁXIMO DE CARACTERES
		if (strlen($nombre) > 30
			or strlen($apellido) > 30
			or strlen($correo) > 30
			or strlen($contrasena) > 30
			or strlen($contrasena2) > 30
			or strlen($nivel) > 30
			or strlen($pregunta_secreta) > 30
			or strlen($respuesta_secreta) > 30)
		{

			$_SESSION["error"] .= "Los campos no pueden tener más de treinta caractéres. ";
			$todo_bien = FALSE;

		}

		if (strlen($usuario) > 20)
		{

			$_SESSION["error"] .= "El campo usuario no puede tener más de veinte caractéres. ";
			$todo_bien = FALSE;

		}

				// COMPROBACIÓN DE MÍNIMO DE CARACTERES
		if (strlen($nombre) < 2
			or strlen($apellido) < 2
			or strlen($respuesta_secreta) < 2)
		{

			$_SESSION["error"] .= "Los campos no pueden tener menos de dos caractéres. ";
			$todo_bien = FALSE;

		}

		if (strlen($usuario) < 4
			or strlen($contrasena) < 4
			or strlen($contrasena2) < 4) 
		{

			$_SESSION["error"] .= "Los campos usuario y contrasena no pueden tener menos de cuatro caractéres. ";
			$todo_bien = FALSE;

		}

		// COMPROBACIÓN CORREO
		if (filter_var($correo, FILTER_VALIDATE_EMAIL) == FALSE)
		{

			$_SESSION["error"] .= "El formato de correo es inválido. ";
			$todo_bien = FALSE;

		}
		
		// COMPROBACIÓN DE CONTRASEÑA
		if ($contrasena !== $contrasena2)
		{

			$_SESSION["error"] .= "Las contraseñas no coinciden. ";
			$todo_bien = FALSE;

		} 

		else 
		{

			//CONTRASENA EXISTENTE EN OTROS USUARIOS
			$sql_prueba2 = "SELECT * FROM usuarios";
			$resultado_prueba2 = $conexion->query($sql_prueba2);

			while ($row2 = $resultado_prueba2->fetch_assoc())
			{

				if ($contrasena == $row2['contrasena']) 
				{

					$_SESSION["error"] .= "La contraseña ya existe en otro usuario. ";
					$todo_bien = FALSE;

				}
			}
		}

		//COMPROBACIÓN PRINCIPAL
		if ($todo_bien == FALSE)
		{

			header("
				Location:../registrarse.php?error=1
				");
		}

		else
		{

			$sql = 
			"INSERT INTO `usuarios` 
			(`nombre`,
			`apellido`,
			`usuario`,
			`correo`,
			`contrasena`,
			`nivel`,
			`pregunta_secreta`,
			`respuesta_secreta`) 
			VALUES 
			('$nombre',
			'$apellido',
			'$usuario',
			'$correo',
			'$contrasena',
			'$nivel',
			'$pregunta_secreta',
			'$respuesta_secreta')";

			$resultado = $conexion->query($sql);

			if ($resultado)
			{

				mysqli_close($conexion);
				$_SESSION["recuperar_exito"] = "El usuario ha sido registrado con éxito";

				header("
					Location:../login.php?exito=1
					");
			}

			else
			{

				mysqli_close($conexion);
				$_SESSION["error"] .= "Ha sucedido un error inesperado. Por favor, vuelva a intentarlo";

				header("
					Location:../login.php?error=1
					");
			}
		}
	}
}
?>