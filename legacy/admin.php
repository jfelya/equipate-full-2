<?php
session_start();
include("funciones/sesionInactiva.php");
include("funciones/adminFunciones.php");
mostrarErrorExito();
sesionInactiva();
?>
<!DOCTYPE html>
<html>

<head>
    <title>Página del Admin</title>
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="css/bootstrapguayuco.css">
    <link rel="stylesheet" type="text/css" href="css/guayucostyles.css">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-2"></div>
            <div class="col-sm-8">
                <h1 class="title">Bienvenido al nivel de Administrador</h1>
            </div>
            <div class="col-sm-2">
                <p id="usuarioSesion">Administrador:
                    <span class="usertext">
                        <?php
                        echo $_SESSION["usuario"];
                        ?>
                    </span>
                </p>
            </div>
        </div><br><br><br>
        <div class="row">
            <div class="col-sm-3"></div>
            <div class="col-sm-3">
                <a class="btn btn-outline-primary btn-block" role="button" .disabled href="admin-usuario.php">Registrar
                Usuario</a>
            </div>
            <div class="col-sm-3">
                <a class="btn btn-outline-primary btn-block" role="button" .disabled href="admin-producto.php">Registrar
                Producto</a><br>
            </div>
            <div class="col-sm-3"></div>
        </div>
        <div class="row">
            <div class="col-sm-3"></div>
            <div class="col-sm-6">
                <a class="btn btn-outline-danger btn-block" href="procesos/cerrar_sesion.php">Cerrar Sesión</a>
            </div>
            <div class="col-sm-3"></div>
        </div>
    </div>
</body>

</html>