<?php
function sesionActiva() {

	if (isset($_SESSION["usuario"]) 
		|| isset($_SESSION["sesion_activa"]))
	{
		header("
			Location:mainpage.php
			");

	}
}
