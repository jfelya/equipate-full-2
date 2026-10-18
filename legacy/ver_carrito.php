<?php
session_start();
include("funciones/sesionInactiva.php");
sesionInactiva();
include("funciones/verCarrito.php");
include("funciones/verPrecioTotal.php");
?>
<!DOCTYPE html>
<html>

<head>
    <title>Ver Carrito</title>
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="css/bootstrapguayuco.css">
    <link rel="stylesheet" type="text/css" href="css/guayucostyles.css">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-1"></div>
            <div class="col-sm-10">
                <h2 class="title">Carrito de: <span class="usertext">
                        <?php
						echo $_SESSION["usuario"];
						?>
                    </span></h2>
            </div>
            <div class="col-sm-1">
                <a class="btn btn-outline-secondary btn-block" href="mainpage.php">
                    <</a>
            </div>
        </div> <br>
        <?php
						if (isset($_SESSION["carrito"])) {
							if ($_SESSION["carrito"][0] != "") {
						?>
        <div class="row">
            <div class="col-sm-12">
                <table class="table table-dark table-hover">
                    <tbody style="color: silver;">
                        <tr>
                            <th class="tabletitle">
                                Nombre
                            </th>
                            <th class="tabletitle">
                                Precio
                            </th>
                            <th class="tabletitle">
                                Cantidad
                            </th>
                            <th class="tabletitle">
                                Opciones
                            </th>
                        </tr>
                        <?php
												verCarrito();
												?>
                        <tr>
                            <td colspan="4">
                                <?php
														calcularPrecioTotal();
														verPrecioTotal();
														?>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <?php
							} else {
								header("Location:procesos/vaciar_carrito.php");
							}
							?>
        <div class="row">
            <div class="col-sm-2"></div>
            <div class="col-sm-5">
                <a class="btn btn-outline-primary btn-block" href="procesos/compra_proceso.php?compra">Proceder a
                    comprar</a>
            </div>
            <div class="col-sm-3">
                <a class="btn btn-outline-info btn-block" href="procesos/vaciar_carrito.php">Vaciar Carrito</a>
            </div>
            <div class="col-sm-2"></div>
        </div>
        <?php
						} else {
						?>
        <div class="row">
            <div class="col-sm-12">
                <p>Actualmente no tienes ningún artículo agregado a tu carrito</p>
            </div>
        </div>
        <?php
						}
						?>
    </div>
</body>

</html>