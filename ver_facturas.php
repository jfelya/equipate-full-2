<?php
session_start();
include("funciones/sesionInactiva.php");
sesionInactiva();
include("funciones/verFacturas.php");
?>
<!DOCTYPE html>
<html>

<head>
    <title>Ver Facturas</title>
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
                <h2 class="title">Facturas de: <span class="usertext">
                    <?php echo $_SESSION["usuario"]; ?>
                </span></h2>
            </div>
            <div class="col-sm-1">
                <a class="btn btn-outline-secondary btn-block" href="mainpage.php">
                    <</a>
                </div>
            </div> <br>
            <?php
            verFacturas();
            ?>
        </div>
    </body>

    </html>