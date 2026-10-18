<?php
function calcularPrecioTotal()
{
	if (isset($_SESSION["preciototal"]) 
		|| isset($_SESSION["precio"]) 
		|| isset($_SESSION["cantidad"]))
	{

		unset($_SESSION["preciototal"]);
		unset($_SESSION["precio"]);
		unset($_SESSION["cantidad"]);

	}

	if (isset($_SESSION["carrito"]))
	{

		$_SESSION["preciototal"] = 0;

		foreach ($_SESSION["carrito"] as $posicion => $posicionvalor)
		{
			$_SESSION["precio"] = 0;
			$_SESSION["cantidad"] = 0;

			foreach ($posicionvalor as $campo => $campovalor)
			{

				if ($campo == "precio")
				{

					$_SESSION["precio"] = $campovalor;

				}

				elseif ($campo == "cantidad")
				{

					$_SESSION["cantidad"] = $campovalor;

				}

				$_SESSION["preciototal"] +=
				($_SESSION["precio"] * $_SESSION["cantidad"]);
			}
		}
	}
}

function verPrecioTotal() {

	if (isset($_SESSION["preciototal"]) 
		&& $_SESSION["preciototal"] == 0)
	{

		Location("procesos/vaciar_carrito.php");

	} 

	else 
	{
		?>
		<?php echo 
		"Precio Total: " .
		$_SESSION["preciototal"]; ?>
		<span style="color:#54678F;">bsS</span></td>
		<?php
	}
}
?>