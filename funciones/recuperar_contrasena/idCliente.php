<?php
//VARIABLES LÓGICAS
$puestoCliente = isset($_REQUEST["id_cliente"]);
$puestoRecuperar = isset($_REQUEST["recuperar"]);
$recuperarUsuario = isset($_REQUEST["recuperar"]) 
&& $_REQUEST["recuperar"] == "usuario";

$recuperarCorreo = isset($_REQUEST["recuperar"]) 
&& $_REQUEST["recuperar"] == "correo";

$cambiarContrasena = isset($_SESSION["cambiar_contrasena"]) 
&& $_SESSION["cambiar_contrasena"] == TRUE;