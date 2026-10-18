function holi() {

	alert("Holi");

}

function formLoginCheck() {

	//LLENADO DE VARIABLES
	let usuario, contrasena, ok, msg;

	usuario = document.forms["formLogin"]["usuario"].value;

	contrasena = document.forms["formLogin"]["contrasena"].value;

	ok = true;

	msg = "";

	if (usuario == "") 
	{

		msg += "You must enter your username\n";
		ok = false;

	}

	if (contrasena == "") 
	{

		msg += "You must enter your password\n";
		ok = false;

	}

	//VALIDACIÓN PRINCIPAl
	if (ok == false) 
	{

		alert(msg);
		return false;

	} 

	else 
	{

		return true;
	}
	
}