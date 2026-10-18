<?php
session_start();
include("funciones/sesionInactiva.php");
include("funciones/funcionesListaUsuarios.php");
sesionInactiva();
mostrarErrorExito();
?>
<!DOCTYPE html>
<html>

<head>
    <title>Lista de Usuarios</title>
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
                <h2 class="title">Lista de usuarios</h2>
            </div>
            <div class="col-sm-1">
                <a class="btn btn-outline-secondary btn-block" href="admin-usuario.php">
                    <</a>
                </div>
            </div> <br><br>
            <div class="row">
                <div class="col-sm-12">
                    <table class="table table-dark table-hover">
                        <tbody style="color: silver;">
                            <tr>
                                <th class="tabletitle">
                                    Nombre
                                </th>
                                <th class="tabletitle">
                                    Usuario
                                </th>
                                <th class="tabletitle">
                                    Nivel
                                </th>
                                <th class="tabletitle">
                                    Pregunta Secreta
                                </th>
                                <th class="tabletitle">
                                    Respuesta Secreta
                                </th>
                                <th class="tabletitle" colspan="2">
                                    Modificaciones
                                </th>
                            </tr>
                            <?php
                            mostrarUsuarios();
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </body>

    </html>