<?php
function mostrarErrorExito() {

	// ERROR PRODUCTO
	if (isset($_SESSION["admin_error"]) and isset($_REQUEST["admin_error"]))
	{

		if ($_SESSION["admin_error"] !== "" and isset($_REQUEST["producto_erradmin_erroroadmin_errorr"]))
		{

			echo "<script>alert('" . $_SESSION["admin_error"] . "')</script>";
			unset($_SESSION["admin_error"]);

		}

	}

	// EXITO PRODUCTO
	if (isset($_SESSION["admin_exito"]) and isset($_REQUEST["admin_exito"]))
	{

		if ($_SESSION["admin_exito"] !== "" and isset($_REQUEST["admin_exito"]))
		{

			echo "<script>alert('" . $_SESSION["admin_exito"] . "')</script>";
			unset($_SESSION["admin_exito"]);

		}
	}
}

function mostrarUsuarios() {

	include("conexion.php");
	$query = "SELECT * FROM usuarios";
	$resultado = $conexion->query($query);

	while ($row = $resultado->fetch_assoc())
	{
		?>

		<tr>
			<td>
				<?php echo $row['nombre']; ?>
			</td>
			<td>
				<?php echo $row['usuario']; ?>
			</td>
			<td>
				<?php echo $row['nivel']; ?>
			</td>
			<td>
				<?php echo $row['pregunta_secreta']; ?>
			</td>
			<td>
				<?php echo $row['respuesta_secreta']; ?>
			</td>
			<td>
				<a class="btn btn-outline-secondary" href="admin-usuario.php?modificar_usuario=1&id_cliente=<?php echo $row['id_cliente']; ?>">Modificar</a>
			</td>
			<td>

				<?php
				if ($row["usuario"] == $_SESSION["usuario"])
				{
					?>
					--------
					<?php
				}
				else
				{
					?>
					<a class="btn btn-outline-warning" href="borrar.php?id_cliente=<?php echo $row['id_cliente']; ?>">X</a>

					<?php
				}
				?>

			</td>
		</tr>
		
		<?php
	}
}
?>