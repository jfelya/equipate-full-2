<?php
function verFacturaDetallado($id_factura) {
	?>
	<table class="table table-dark table-hover">
		<tbody>
			<tr>
				<th class="tabletitle">
					Productos
				</th>
				<th class="tabletitle">
					Cantidad
				</th>
				<th class="tabletitle">
					Precio
				</th>
			</tr>

			<?php
			include("conexion.php");
			$sql = "SELECT * FROM detalle";
			$resultado = $conexion->query($sql);

			while ($row = $resultado->fetch_assoc()) 
			{

				if ($row["id_factura"] == $id_factura) 
				{

					$id_producto = $row["id_producto"];

					$sql2 = 
					"SELECT * FROM producto 
					WHERE id_producto = $id_producto";

					$resultado2 = $conexion->query($sql2);
					$row2 = $resultado2->fetch_assoc();
					$nombre = $row2["nombre"];
					?>
					<tr>
						<td>
							<?php echo $nombre; ?>
						</td>
						<td>
							<?php echo $row['cantidad']; ?>
						</td>
						<td>
							<?php echo $row['precio']; ?>
						</td>
					</tr>

					<?php
				}
			}
			?>

		</tbody>
	</table>
	<table class="table table-dark table-hover">
		<tbody>
			<tr>
				<th class="tabletitle">
					Id
				</th>
				<th class="tabletitle">
					Fecha
				</th>
				<th class="tabletitle">
					Cliente
				</th>
				<th class="tabletitle">
					Precio Total
				</th>
			</tr>

			<?php
			include("conexion.php");

			$query3 = 
			"SELECT * FROM factura 
			WHERE id_factura = '$id_factura'";

			$resultado3 = $conexion->query($query3);
			$usuario = $_SESSION["usuario"];
			$row3 = $resultado3->fetch_assoc();
			?>

			<tr>
				<td>
					<?php echo $row3['id_factura']; ?></a>
				</td>
				<td>
					<?php echo $row3['fecha']; ?>
				</td>
				<td>
					<?php echo $usuario; ?>
				</td>
				<td>
					<?php echo $row3['preciototal']; ?> <span style="color:#54678F;">bsS</span>
				</td>
			</tr>
		</tbody>
	</table>
	
	<?php
}
?>