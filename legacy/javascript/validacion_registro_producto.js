function validacion_registro_producto() {
	//LLENADO DE VARIABLES
	var nombre, stock, precio, imagen;

	nombre = document.forms["registro_producto"]["nombre"].value;

	stock = document.forms["registro_producto"]["stock"].value;

	precio = document.forms["registro_producto"]["precio"].value;

	imagen = document.forms["registro_producto"]["imagen"].value;

	var ok = true;

	var msg = "";

	if (nombre == "" 
		|| stock == "" 
		|| precio == "")
	{

		msg += "Todos los campos son obligatorios\n";
		ok = false;
		alert(msg);
		return ok;

	}

	else
	{

		if (nombre.length < 2)
		{

			msg += "El nombre no puede tener menos de dos caracteres\n";
			ok = false;

		}

		if (nombre.length > 120)
		{

			msg += "El nombre no puede tener más de ciento veinte caracteres\n";
			ok = false;

		} 

		if (isNAN(stock) || isNAN(precio))
		{ 

			msg += "El stock y el precio deben ser numéricos\n";
			ok = false;

		}

		//VALIDACIÓN PRINCIPAl
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