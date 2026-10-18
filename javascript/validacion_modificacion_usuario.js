function validacion_modificacion_usuario() {
	//LLENADO DE VARIABLES
	var nombre, apellido, usuario, correo, contrasena, contrasena2, 
	nivel, pregunta_secreta, respuesta_secreta, expresion;

	nombre = document.forms["registro_usuario"]["nombre"].value;

	apellido = document.forms["registro_usuario"]["apellido"].value;

	usuario = document.forms["modificacion_usuario"]["usuario"].value;

	correo = document.forms["modificacion_usuario"]["correo"].value;

	contrasena = document.forms["modificacion_usuario"]["contrasena"].value;

	contrasena2 = document.forms["modificacion_usuario"]["contrasena2"].value;

	nivel = document.forms["modificacion_usuario"]["nivel"].value.toLowerCase();

	pregunta_secreta = document.forms["modificacion_usuario"]["pregunta_secreta"].value;

	respuesta_secreta = document.forms["modificacion_usuario"]["respuesta_secreta"].value.toLowerCase();

	//Las expresiones se ponen entre dos slash, es decir: //
	expresion = /\w+@+[a-zA-Z]+\.+[a-zA-Z]{2,3}/;
	// \w Agarra todos los valores del alfabeto y los números
	// [a-z] Agarra todo el alfabeto 

	var ok = true;

	var msg = "";

	if (nombre == "" 
		|| apellido == "" 
		|| usuario == ""
		|| correo == ""
		|| contrasena == ""
		|| contrasena2 == ""
		|| nivel == ""
		|| pregunta_secreta == ""
		|| respuesta_secreta == "")
	{

		msg += "Todos los campos son obligatorios\n";
		ok = false;
		return ok;

	}

	else
	{

		if (nombre.length < 2 
			|| apellido.length < 2
			|| respuesta_secreta.length < 2)
		{

			msg += "Los campos no pueden tener menos de dos caracteres\n";
			ok = false;

		}

		if (usuario.length < 4 
			|| contrasena.length < 4
			|| contrasena2.length < 4)
		{

			msg += "Los campos usuario y contraseña no pueden tener menos de cuatro caracteres\n";
			ok = false;

		}

		if (nombre.length > 30 
			|| apellido.length > 30
			|| correo.length > 30
			|| contrasena.length > 30
			|| contrasena2.length > 20
			|| nivel.length > 30
			|| pregunta_secreta.length > 30
			|| respuesta_secreta.length > 30)
		{

			msg += "Los campos no pueden tener más de treinta caracteres\n";
			ok = false;

		}

		if (usuario.length > 30)
		{

			msg += "El campos nombre no puede tener más de veinte caracteres\n";
			ok = false;

		}

		if (!expresion.test(correo))
		{

			msg += "El correo no es válido\n";
			ok = false;

		}

		if (contrasena !== contrasena2)
		{

			msg += "Las contraseñas no coinciden\n";
			ok = false;

		}

		if (ok == false)
		{

			alert(msg);
			return ok;

		}

		else
		{

			return ok;
			
		}
	}
}