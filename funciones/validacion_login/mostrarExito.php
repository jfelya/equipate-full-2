<?php
function mostrarExito() {

	//EXITO CON LA CONTRASEÑA
	if (isset($_REQUEST["exito"]))
	{

		if ($_SESSION["recuperar_exito"] !== "" 
			and $_REQUEST["exito"] == 1)
		{

			echo "<script>alert('".$_SESSION["recuperar_exito"]."')</script>";
			unset($_SESSION["recuperar_exito"]);

		}
	}
}
