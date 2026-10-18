<?php
function mostrarErrorExito()
{

	// ERROR PRODUCTO
	if (isset($_SESSION["producto_error"]) 
		and isset($_REQUEST["producto_error"]))
	{

		if ($_SESSION["producto_error"] !== "" and isset($_REQUEST["producto_error"]))
		{

			echo "<script>alert('" . $_SESSION["producto_error"] . "')</script>";
			unset($_SESSION["producto_error"]);

		}
	}

	// EXITO PRODUCTO
	if (isset($_SESSION["producto_exito"]) 
		and isset($_REQUEST["producto_exito"]))
	{

		if ($_SESSION["producto_exito"] !== "" 
			and isset($_REQUEST["producto_exito"]))
		{

			echo "<script>alert('" . $_SESSION["producto_exito"] . "')</script>";
			unset($_SESSION["producto_exito"]);

		}
	}
}

function mostrarProductos() {

	include("conexion.php");
	$query = "SELECT * FROM producto";
	$resultado = $conexion->query($query);

	while ($row = $resultado->fetch_assoc())
	{
		?>

		<tr>
			<td>
				<?php echo $row['nombre']; ?>
			</td>
			<td>
				<?php echo $row['stock']; ?>
			</td>
			<td>
				<?php echo $row['precio']; ?> <span style="color:#54678F;">bsS</span>
			</td>
			<td>
				<a class="btn btn-outline-secondary" href="admin-producto.php?modificar_producto&id_producto=<?php echo $row['id_producto']; ?>">Modificar</a>
			</td>
			<td>
				<a class="btn btn-outline-warning" href="borrar.php?id_producto=<?php echo $row['id_producto']; ?>">X</a>
			</td>
		</tr>
		
		<?php
	}
}
?>