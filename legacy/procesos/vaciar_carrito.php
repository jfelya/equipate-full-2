<?php

session_start();
unset($_SESSION["carrito"]);
unset($_SESSION["preciototal"]);
unset($_SESSION["precio"]);
unset($_SESSION["cantidad"]);
header("Location:../mainpage.php");

?>