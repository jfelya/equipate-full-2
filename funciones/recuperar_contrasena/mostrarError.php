<?php
function mostrarError1() {

	// ERROR
	if (isset($_SESSION["mensaje"]) 
		and isset($_REQUEST["error"]))
	{

		if ($_SESSION["mensaje"] !== "" 
			and $_REQUEST["error"] == 1)
		{

			echo "<script>alert('".$_SESSION["mensaje"]."')</script>";
			unset($_SESSION["mensaje"]);
		}
	}
}

function mostrarError2() {

	// ERROR
	if (isset($_SESSION["mensaje"]) 
		and isset($_REQUEST["error2"]))
	{

		if ($_SESSION["mensaje"] !== "" 
			and $_REQUEST["error2"] == 1)
		{

			echo "<script>alert('".$_SESSION["mensaje"]."')</script>";
			unset($_SESSION["mensaje"]);
			
		}
	}
}
