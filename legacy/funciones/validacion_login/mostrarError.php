<?php
function mostrarError() {

	if (isset($_REQUEST["error"]) 
		and isset($_REQUEST["error"]))
	{

		if ($_SESSION["error"] !== "" 
			and $_REQUEST["error"] == 1)
		{

			echo "<script>alert('".$_SESSION["error"]."')</script>";
			unset($_SESSION["error"]);
		}
	}
}