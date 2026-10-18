<?php
session_start();
include("funciones/sesionActiva.php");
include("funciones/recuperar_contrasena/mostrarError.php");
include("funciones/recuperar_contrasena/idCliente.php");
sesionActiva();
mostrarError1();
mostrarError2();
// idCliente2();
include("conexion.php");
$id_cliente = $_REQUEST["id_cliente"];
$sql = "SELECT * FROM usuarios WHERE id_cliente = '$id_cliente'";
$resultado = $conexion->query($sql);
$row = $resultado->fetch_assoc();
?>
<!DOCTYPE html>
<html>

<head>
    <title>Recuperación de Contraseña 2</title>
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
                <h2 class="title">Usuario a recuperar: <span class="usertext">
                    <?php
                    include("conexion.php");
                    $id_cliente = $_REQUEST["id_cliente"];
                    $sql = "SELECT * FROM usuarios WHERE id_cliente = '$id_cliente'";
                    $resultado = $conexion->query($sql);
                    $row = $resultado->fetch_assoc();
                    echo $row["usuario"];
                    ?>
                </span></h2>
            </div>
            <div class="col-sm-1">
                <a class="btn btn-outline-secondary  btn-block" href="recuperar_contrasena.php"><</a>
            </div>
        </div><br>
        <form method="POST" action="procesos/recuperar_contrasena2_proceso.php?id_cliente=<?php echo $id_cliente; ?>">
            <!-- CONTRASEÑA -->
            <div class="form-group row">
                <div class="col-sm-3"></div>
                <label class="col-sm-3 col-form-label" for="contrasena">
                    Nueva contraseña
                </label>
                <div class="col-sm-3">
                    <input class="form-control" id="contrasena" type="password" name="contrasena"
                    placeholder="Ingrese su nueva contraseña" autofocus="yes">
                </div>
                <div class="col-sm-3"></div>
            </div>
            <!-- CONFIRMAR CONTRASEÑA -->
            <div class="form-group row">
                <div class="col-sm-3"></div>
                <label class="col-sm-3 col-form-label" for="contrasena2">
                    Confirmar nueva contraseña
                </label>
                <div class="col-sm-3">
                    <input class="form-control" id="contrasena2" type="password" name="contrasena2"
                    placeholder="Confirme su nueva contraseña">
                </div>
                <div class="col-sm-3"></div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <p class="mini">Todos los campos son obligatorios<br>
                    Máximo 20 caracteres / Mínimo 2 caracteres</p>
                </div>
            </div><br>
            <div class="row">
                <div class="col-sm-3"></div>
                <div class="col-sm-6">
                    <input class="btn btn-outline-primary btn-block" type="submit" value="Enviar">
                </div>
                <div class="col-sm-3"></div>
            </div>
        </form>
    </div>
</body>
</html>