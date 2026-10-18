<?php
function sesionActiva() {

	//VARIABLES LÓGICAS
	$haySesion = isset($_SESSION["usuario"]) 
	&& isset($_SESSION["sesion_activa"]);

	$esAdmin = $haySesion && isset($_SESSION["nivel"]) 
	&& $_SESSION["nivel"] == "admin";

	$esCliente = $haySesion && isset($_SESSION["nivel"]) 
	&& $_SESSION["nivel"] == "cliente";

	//SI ES CLIENTE
	if ($esCliente)
	{

		header("
			Location:mainpage.php
			");

	}

	//SI ES ADMIN
	if ($esAdmin)
	{

		header("
			Location:mainpage.php
			");

	}
}
