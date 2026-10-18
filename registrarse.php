<?php
session_start();
include("funciones/validacion_login/mostrarError.php");
include("funciones/validacion_login/mostrarExito.php");
include("funciones/validacion_login/sesionActiva.php");
mostrarError();
mostrarExito();
sesionActiva();
?>
<!DOCTYPE html>
<html>
<head>

    <title>Registrarse</title>
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
                <h2 class="title">Registrarse por primera vez</h2>
            </div>
            <div class="col-sm-1">
                <a class="btn btn-outline-secondary btn-block" href="login.php"><</a>
            </div>
        </div><br>
        <form name="registrarse" action="procesos/registrarse_proceso.php" method="POST">
            <!-- NOMBRE -->
            <div class="form-group row">
                <div class="col-sm-3"></div>
                <label class="col-sm-3 col-form-label" for="nombre">
                    Nombre
                </label>
                <div class="col-sm-3">
                    <input class="form-control" id="nombre" type="text" name="nombre" required>
                </div>
                <div class="col-sm-3"></div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <p class="mini">Máximo 30 caracteres / Mínimo 2 caracteres</p>
                </div>
            </div>
            <!-- APELLIDO -->
            <div class="form-group row">
                <div class="col-sm-3"></div>
                <label class="col-sm-3 col-form-label" for="apellido">
                    Apellido
                </label>
                <div class="col-sm-3">
                    <input class="form-control" id="apellido" type="text" name="apellido" required>
                </div>
                <div class="col-sm-3"></div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <p class="mini">Máximo 30 caracteres / Mínimo 2 caracteres</p>
                </div>
            </div>
            <!-- USUARIO -->
            <div class="form-group row">
                <div class="col-sm-3"></div>
                <label class="col-sm-3 col-form-label" for="usuario">
                    Usuario
                </label>
                <div class="col-sm-3">
                    <input class="form-control" id="usuario" type="text" name="usuario" required onkeyup="this.value = this.value.toLowerCase();">
                </div>
                <div class="col-sm-3"></div>
                
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <p class="mini">Máximo 20 caracteres / Mínimo 4 caracteres</p>
                </div>
            </div>
            <!-- CORREO -->
            <div class="form-group row">
                <div class="col-sm-3"></div>
                <label class="col-sm-3 col-form-label" for="correo">
                    Correo
                </label>
                <div class="col-sm-3">
                    <input class="form-control" id="correo" type="email" name="correo" required>
                </div>
                <div class="col-sm-3"></div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <p class="mini">Máximo 30 caracteres</p>
                </div>
            </div>
            <!-- CONTRASEÑA -->
            <div class="form-group row">
                <div class="col-sm-3"></div>
                <label class="col-sm-3 col-form-label" for="contrasena">
                    Contraseña
                </label>
                <div class="col-sm-3">
                    <input class="form-control" id="contrasena" type="password" name="contrasena" required>
                </div>
                <div class="col-sm-3"></div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <p class="mini">Máximo 30 caracteres / Mínimo 4 caracteres</p>
                </div>
            </div>
            <!-- CONFIRMAR CONTRASEÑA -->
            <div class="form-group row">
                <div class="col-sm-3"></div>
                <label class="col-sm-3 col-form-label" for="contrasena2">
                    Confirmar contraseña
                </label>
                <div class="col-sm-3">
                    <input class="form-control" id="contrasena2" type="password" name="contrasena2" required>
                </div>
                <div class="col-sm-3"></div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <p class="mini">Máximo 30 caracteres / Mínimo 4 caracteres</p>
                </div>
            </div>
            <!-- PREGUNTA SECRETA -->
            <div class="form-group row">
                <div class="col-sm-3"></div>
                <label class="col-sm-3 col-form-label" for="pregunta_secreta">
                    Pregunta secreta
                </label>
                <div class="col-sm-3">
                    <select class="form-control" name="pregunta_secreta" id="pregunta_secreta">
                        <option></option>
                        <option>Color Favorito</option>
                        <option>Comida Favorita</option>
                        <option>Banda Favorita</option>
                        <option>Animal Favorito</option>
                    </select>
                </div>
                <div class="col-sm-3"></div>
            </div>
            <div class="row">
                <div class="col-sm-12"></div>
            </div>
            <!-- RESPUESTA SECRETA -->
            <div class="form-group row">
                <div class="col-sm-3"></div>
                <label class="col-sm-3 col-form-label" for="respuesta_secreta">
                    Respuesta secreta
                </label>
                <div class="col-sm-3">
                    <input class="form-control" id="respuesta_secreta" type="text" name="respuesta_secreta" required onkeyup="this.value = this.value.toLowerCase();">
                </div>
                <div class="col-sm-3"></div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <p class="mini">Máximo 30 caracteres / Mínimo 2 caracteres</p>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-3"></div>
                <div class="col-sm-6">
                    <input class="btn btn-outline-primary btn-block" type="submit" name="boton_registrar" value="Registrar" .disabled>
                    <div class="col-sm-3"></div>
                </div>
            </div>
        </form>
    </div>
</body>
</html>