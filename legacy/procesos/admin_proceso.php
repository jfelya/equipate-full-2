<?php
session_start();

include("../funciones/sesionInactiva.php");

sesionInactiva();

if (isset($_POST["boton_modificar"]) 
	and $_REQUEST["modificar"] = "1" 
	and isset($_REQUEST["id_cliente"]))
{

	include("../conexion.php");

	$id_cliente = $_REQUEST["id_cliente"];

	$nombre = $_POST["nombre"];

	$apellido = $_POST["apellido"];

	$usuario = $_POST["usuario"];

	$correo = $_POST["correo"];

	$contrasena = $_POST["contrasena"];

	$contrasena2 = $_POST["contrasena2"];

	$nivel = strtolower($_POST["nivel"]);

	$pregunta_secreta = $_POST["pregunta_secreta"];

	$respuesta_secreta = strtolower($_POST["respuesta_secreta"]);

	$_SESSION["admin_error"] = "";

	$todo_bien = TRUE;

	if ($nombre == "" or $apellido == "" or $usuario == ""
		or $correo == ""
		or $contrasena == ""
		or $contrasena2 == ""
		or $nivel == ""
		or $pregunta_secreta == ""
		or $respuesta_secreta == "")
	{

		$_SESSION["admin_error"] .=
		"Los campos no pueden estar vacíos. ";

		header("
			Location:../admin-usuario.php?modificar_usuario&id_cliente=$id_cliente&admin_error
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

			$_SESSION["admin_error"] .= "Los campos no pueden tener más de treinta caractéres. ";
			$todo_bien = FALSE;

		}

		if (strlen($usuario) > 20)
		{

			$_SESSION["admin_error"] .= "El campo usuario no puede tener más de veinte caractéres. ";
			$todo_bien = FALSE;

		}

		// COMPROBACIÓN DE MÍNIMO DE CARACTERES
		if (strlen($nombre) < 2
			or strlen($apellido) < 2
			or strlen($respuesta_secreta) < 2)
		{

			$_SESSION["admin_error"] .= "Los campos no pueden tener menos de dos caractéres. ";
			$todo_bien = FALSE;

		}

		if (strlen($usuario) < 4
			or strlen($contrasena) < 4
			or strlen($contrasena2) < 4) 
		{

			$_SESSION["admin_error"] .= "Los campos usuario y contraseña no pueden tener menos de cuatro caractéres. ";
			$todo_bien = FALSE;

		}
		// COMPROBACIÓN CORREO
		if (filter_var($correo, FILTER_VALIDATE_EMAIL) == FALSE)
		{

			$_SESSION["admin_error"] .= "El formato de correo es inválido. ";
			$todo_bien = FALSE;

		}

		// COMPROBACIÓN DE CONTRASEÑA
		if ($contrasena !== $contrasena2)
		{

			$_SESSION["admin_error"] .= "Las contraseñas no coinciden. ";
			$todo_bien = FALSE;

		} 
		
		//COMPROBACIÓN PRINCIPAL
		if ($todo_bien == FALSE)
		{

			header("
				Location:../admin.php?modificar_usuario&id_cliente=$id_cliente&admin_error
				");
		} 

		else
		{

			$sql = "UPDATE usuarios 
			SET nombre = '$nombre',
			apellido = '$apellido',
			usuario = '$usuario',
			correo = '$correo',
			contrasena = '$contrasena',
			nivel = '$nivel',
			pregunta_secreta = '$pregunta_secreta',
			respuesta_secreta = '$respuesta_secreta' 
			WHERE id_cliente = '$id_cliente'";

			$resultado = $conexion->query($sql);

			if ($resultado)
			{

				mysqli_close($conexion);

				$_SESSION["admin_exito"] = "El usuario ha sido modificado con éxito";

				header("
					Location:../lista_de_usuarios.php?admin_exito
					");
			} 

			else
			{

				mysqli_close($conexion);

				$_SESSION["admin_error"] .= "Ha sucedido un error inesperado. Por favor, vuelva a intentarlo";

				header("
					Location:../admin-usuario.php?modificar_usuario&id_cliente=$id_cliente&admin_error
					");

			}
		}
	}
}

else
{
	include("../conexion.php");

	$nombre = $_POST["nombre"];

	$apellido = $_POST["apellido"];

	$usuario = $_POST["usuario"];

	$correo = $_POST["correo"];

	$contrasena = $_POST["contrasena"];

	$contrasena2 = $_POST["contrasena2"];

	$nivel = strtolower($_POST["nivel"]);

	$pregunta_secreta = $_POST["pregunta_secreta"];

	$respuesta_secreta = strtolower($_POST["respuesta_secreta"]);

	$_SESSION["admin_error"] = "";

	$todo_bien = TRUE;

	$sql_prueba = 
	"SELECT * FROM usuarios WHERE usuario = '$usuario'";

	$resultado_prueba = $conexion->query($sql_prueba);

	$row_prueba = $resultado_prueba->fetch_assoc();

	if ($row_prueba["usuario"])
	{

		$_SESSION["admin_error"] .= "El usuario ya existe. ";
		header("Location:../admin.php?registrar_usuario&admin_error");

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

			$_SESSION["admin_error"] .= "Los campos no pueden estar vacíos. ";
			header("Location:../admin.php?registrar_usuario&admin_error");

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

				$_SESSION["admin_error"] .= "Los campos no pueden tener más de treinta caractéres. ";
				$todo_bien = FALSE;

			}

			if (strlen($usuario) > 20)
			{

				$_SESSION["admin_error"] .= "El campo usuario no puede tener más de veinte caractéres. ";
				$todo_bien = FALSE;

			}

				// COMPROBACIÓN DE MÍNIMO DE CARACTERES
			if (strlen($nombre) < 2
				or strlen($apellido) < 2
				or strlen($respuesta_secreta) < 2)
			{

				$_SESSION["admin_error"] .= "Los campos no pueden tener menos de dos caractéres. ";
				$todo_bien = FALSE;

			}

			if (strlen($usuario) < 4
				or strlen($contrasena) < 4
				or strlen($contrasena2) < 4) 
			{

				$_SESSION["admin_error"] .= "Los campos usuario y contraseña no pueden tener menos de cuatro caractéres. ";
				$todo_bien = FALSE;

			}

			// COMPROBACIÓN CORREO
			if (filter_var($correo, FILTER_VALIDATE_EMAIL) == FALSE)
			{
				$_SESSION["admin_error"] .= "El formato de correo es inválido. ";
				$todo_bien = FALSE;
			}

			// COMPROBACIÓN DE CONTRASEÑA
			if ($contrasena !== $contrasena2)
			{

				$_SESSION["admin_error"] .= "Las contraseñas no coinciden. ";
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

						$_SESSION["admin_error"] .= "La contraseña ya existe en otro usuario. ";
						$todo_bien = FALSE;

					}
				}
			}

			if ($todo_bien == FALSE)
			{

				header("Location:../admin.php?registrar_usuario&admin_error");
			}

			else
			{

				$sql = "
				INSERT INTO `usuarios` 
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

					$_SESSION["admin_exito"] = "El usuario ha sido registrado con éxito";

					header("
						Location:../lista_de_usuarios.php?admin_exito
						");

				}

				else
				{

					mysqli_close($conexion);

					$_SESSION["admin_error"] .= "Ha sucedido un error inesperado. Por favor, vuelva a intentarlo";

					header(
						"Location:../admin.php?registrar_usuario&admin_error"
					);

				}
			}
		}
	}
}
?>