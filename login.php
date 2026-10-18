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
	<title>Equipate Full Login</title>
	<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="css/bootstrapguayuco.css">
	<link rel="stylesheet" type="text/css" href="css/guayucostyles.css">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<script src="javascript/formLogin.js"></script>
</head>

<body>
	<!-- <form action="background1.jpg" class="main-form" method="POST" name="formLogin" onsubmit="return formLoginCheck();">
		User
		<input id="usuario" type="text" name="usuario" autofocus="yes"><br><br>
		Password
		<input id="contrasena" type="password" id="contrasena"><br><br>
		<input type="submit" value="Send">
	</form> -->
	<div class="container-fluid">
		<div class="row">
			<div class="col-sm-12">
				<h1 class="title">Bienvenido a Equipate Full</h1>
			</div>
		</div><br><br>

		<form action="procesos/login_proceso.php" class="main-form" method="POST" name="formLogin" onsubmit="return formLoginCheck();">
			<div class="form-group row">
				<div class="col-sm-3"></div>
				<label class="col-sm-3 col-form-label" for="usuario">Usuario
				</label>
				<div class="col-sm-3">
					<input class="form-control" id="usuario" type="text" name="usuario" autofocus="yes">
				</div>
				<div class="col-sm-3"></div>
			</div>
			<div class="form-group row">
				<div class="col-sm-3"></div>
				<label class="col-sm-3 col-form-label" for="contrasena">Contraseña
				</label>
				<div class="col-sm-3">
					<input class="form-control" id="contrasena" type="password" name="contrasena">
				</div>
				<div class="col-sm-3"></div>
			</div><br>
			<div class="row">
				<div class="col-sm-3"></div>
				<div class="col-sm-6">
					<a class="enlaces" href="recuperar_contrasena.php">Olvidé mi contraseña</a> /
					<a class="enlaces" href="registrarse.php">Registrarse</a>
				</div>
				<div class="col-sm-3"></div>
			</div><br><br>
			<div class="row">
				<div class="col-sm-4"></div>
				<div class="col-sm-4">
					<input class="btn btn-outline-info btn-block" type="submit" name="ingresarBtn" value="Ingresar">
				</div>
				<div class="col-sm-4"></div>
			</div>
		</form>
	</div>
</body>

</html>