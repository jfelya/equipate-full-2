<?php
session_start();
include("funciones/sesionInactiva.php");
include("funciones/verFacturaDetallado.php");
sesionInactiva();

//Validacion de la compra
if (isset($_REQUEST["exito"]))
{

	echo "<script>alert('Su compra se ha realizado con exito')</script>";

}

if (isset($_REQUEST["id_factura"]) 
    && $_REQUEST["id_factura"] !== "")
{
    header("
        Location:mainpage.php
        ");
}
else
{
    ?>
    <!DOCTYPE html>
    <html>

    <head>

        <title>Factura Detallada</title>
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
                    <h2 class="title">Detalle Factura</h2>
                </div>
                <div class="col-sm-1">
                    <a class="btn btn-outline-secondary btn-block" href="ver_facturas.php">
                        <</a>
                    </div>
                </div> <br>
                <div class="row">
                    <div class="col-sm-12">
                        <?php
                        verFacturaDetallado($_REQUEST["id_factura"]);
                        ?>
                    </div>
                </div>
            </div>
        </body>

        </html>
        <?php
    }
    ?>