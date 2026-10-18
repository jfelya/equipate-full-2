<?php
session_start();
include("funciones/mostrarProductosMainpage.php");
include("funciones/sesionInactiva.php");
sesionInactiva();
?>
<!DOCTYPE html>
<html>

<head>
	<title>Página Principal</title>
	<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="css/bootstrapguayuco.css">
	<link rel="stylesheet" type="text/css" href="css/guayucostyles.css">
	<meta name="viewport" content="width=device-width, initial-scale=1">
</head>

<body>
	<div class="container-fluid">
		<div class="row">
			<div class="col-sm-12">
				<h1 class="title">Página Principal de Equipate Full</h1>
			</div>
		</div><br>
		<div class="row">
			<div class="col-sm-8"></div>
			<div class="col-sm-4">
				<p id="cliente">Cliente:
					<span class="usertext">
						<?php
						echo $_SESSION["usuario"];
						?>
					</span>
				</p>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-12">
				<table class="table table-dark table-hover">
					<thead>
						<tr>
							<th class="tabletitletitle" colspan="9">
								Lista de productos
							</th>
						</tr>
					</thead>
					<tbody style="color: #A4978E;">
						<tr>
							<th class="tabletitle">
								Nombre
							</th>
							<th class="tabletitle">
								Stock
							</th>
							<th class="tabletitle">
								Precio
							</th>
							<th colspan="2" class="tabletitle">
								Carrito
							</th>
						</tr>
						<?php
						mostrarProductosMainpage();
						?>
					</tbody>
				</table>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-3"></div>
			<div class="col-sm-3">
				<a class="btn btn-outline-secondary btn-block" href="ver_carrito.php">Ver Carrito</a>
			</div>
			<div class="col-sm-3">
				<a class="btn btn-outline-secondary btn-block" href="ver_facturas.php">Ver Facturas</a>
			</div>
			<div class="col-sm-3"></div>
		</div><br>
		<div class="row">
			<div class="col-sm-3"></div>
			<div class="col-sm-6">
				<a class="btn btn-outline-danger btn-block" data-toggle="tooltip" data-placement="top" title="Cerrar la sesión" href="procesos/cerrar_sesion.php">Cerrar Sesión</a>
			</div>
			<div class="col-sm-3"></div>
		</div>
	</div>
</body>

</html>