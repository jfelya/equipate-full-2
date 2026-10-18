<?php
function verCarrito() {

	foreach ($_SESSION["carrito"] as $posicion => $posicionvalor) {
		echo "<tr>";
		?>
		
		<td>
			<?php
			echo $posicionvalor["nombre"];
			?>
		</td>
		<td>
			<?php
			echo $posicionvalor["precio"];
			?>
		</td>
		<td>
			<?php
			echo $posicionvalor["cantidad"];
			?>
		</td>
		<td>
			<a class="btn btn-outline-warning" href="procesos/carrito.php?menosuno&posicion=<?php echo $posicion; ?>">-1</a>
		</td>

		<?php
		echo "</tr>";
	}
}
?>