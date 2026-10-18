<?php
function mostrarProductosMainpage() {

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
				<?php echo $row['precio']; ?> <span style="color: #54678F;">bsS</span>
			</td>
			<td>
				<a href="procesos/carrito.php?id_producto=<?php echo $row['id_producto']; ?>&agregar" class="btn btn-outline-primary">Agregar</a>
			</td>
		</tr>
		
		<?php
	}
}
?>