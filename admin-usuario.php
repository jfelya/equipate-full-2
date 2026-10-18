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
    <title>Registro y modificación de Usuarios</title>
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="css/bootstrapguayuco.css">
    <link rel="stylesheet" type="text/css" href="css/guayucostyles.css">
    <script src="javascript/validacion_registro_usuario.js"></script>
    <script src="javascript/validacion_modificacion_usuario.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>

<body>
    <?php
    if (!isset($_REQUEST["modificar_usuario"]) 
        and !isset($_REQUEST["id_cliente"]))
    {
      ?>
      <div class="container-fluid">
        <div class="row">
            <div class="col-sm-1"></div>
            <div class="col-sm-10">
                <h2 class="title">Registro de Usuarios</h2>
            </div>
            <div class="col-sm-1">
                <a class="btn btn-outline-secondary btn-block" href="admin.php">
                    <</a>
                </div>
            </div><br><br>

            <form name="regUser" action="procesos/admin_proceso.php" method="POST"
            onsubmit="return validacion_registro_usuario();">
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
                <label class="col-sm-3 col-form-label" for="usuarioF">
                    Usuario
                </label>
                <div class="col-sm-3">
                    <input class="form-control" id="usuarioF" type="text" name="usuario"
                    required onkeyup="this.value = this.value.toLowerCase();">
                </div>
                <div class="col-sm-3"></div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <p class="mini">Máximo 30 caracteres / Mínimo 4 caracteres</p>
                </div>
            </div>
            <!-- CORREO -->
            <div class="form-group row">
                <div class="col-sm-3"></div>
                <label class="col-sm-3 col-form-label" for="correo">
                    Correo
                </label>
                <div class="col-sm-3">
                    <input class="form-control" id="correo" type="correo" name="correo"
                    required>
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
            <!-- NIVEL  -->
            <div class="form-group row">
                <div class="col-sm-3"></div>
                <label class="col-sm-3 col-form-label" for="nivel">
                    Nivel de Usuario
                </label>
                <div class="col-sm-3">
                    <select class="form-control" name="nivel" id="nivel">
                        <option></option>
                        <option>Cliente</option>
                        <option>Admin</option>
                    </select>
                </div>
                <div class="col-sm-3"></div>
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
                <div class="col-sm-3">
                    <input class="btn btn-outline-primary btn-block" type="submit" name="boton_registrar"
                    value="Registrar" .disabled>
                </div>
                <div class="col-sm-3">
                    <a class="btn btn-outline-secondary btn-block" role="button" .disabled
                    href="lista_de_usuarios.php">Lista de Usuarios</a>
                </div>
                <div class="col-sm-3"></div>
            </div>
        </form>
    </div>
    <?php
} 
else 
{
  $id_cliente = $_REQUEST['id_cliente'];
  include("conexion.php");
  $query = "SELECT * FROM usuarios WHERE id_cliente = '$id_cliente'";
  $resultado = $conexion->query($query);
  $row = $resultado->fetch_assoc();
  ?>

  <div class="container-fluid">
    <div class="row">
        <div class="col-sm-1"></div>
        <div class="col-sm-10">
            <h2 class="title">Modificar Usuario</h2>
        </div>
        <div class="col-sm-1">
            <a class="btn btn-outline-secondary btn-block" href="lista_de_usuarios.php">
                <</a>
            </div>
        </div><br><br>

        <form name="modUser"
        action="procesos/admin_proceso.php?modificar=1&id_cliente=<?php echo $id_cliente; ?>" method="POST"
        onsubmit="return validacion_modificacion_usuario()">
        <!-- NOMBRE -->
        <div class="form-group row">
            <div class="col-sm-3"></div>
            <label class="col-sm-3 col-form-label" for="nombre">
                Nombre
            </label>
            <div class="col-sm-3">
                <input class="form-control" id="nombre" type="text" name="nombre"
                value="<?php echo $row['nombre']; ?>" required>
            </div>
            <div class="col-sm-3"></div>
        </div>
        <p class="mini">Máximo 30 caracteres / Mínimo 2 caracteres</p>
        <!-- APELLIDO -->
        <div class="form-group row">
            <div class="col-sm-3"></div>
            <label class="col-sm-3 col-form-label" for="apellido">
                Apellido
            </label>
            <div class="col-sm-3">
                <input class="form-control" id="apellido" type="text" name="apellido"
                value="<?php echo $row['apellido']; ?>" required>
            </div>
            <div class="col-sm-3"></div>
        </div>
        <p class="mini">Máximo 30 caracteres / Mínimo 2 caracteres</p>
        <!-- USUARIO -->
        <div class="form-group row">
            <div class="col-sm-3"></div>
            <label class="col-sm-3 col-form-label" for="usuarioF">
                Usuario
            </label>
            <div class="col-sm-3">
                <input class="form-control" id="usuarioFR" type="text" name="usuario"
                value="<?php echo $row['usuario']; ?>" required onkeyup="this.value = this.value.toLowerCase();">
            </div>
            <div class="col-sm-3"></div>
        </div>
        <p class="mini">Máximo 20 caracteres / Mínimo 4 caracteres</p>
        <!-- CORREO -->
        <div class="form-group row">
            <div class="col-sm-3"></div>
            <label class="col-sm-3 col-form-label" for="correo">
                Correo
            </label>
            <div class="col-sm-3">
                <input class="form-control" id="correo" type="correo" name="correo"
                value="<?php echo $row['correo']; ?>" required>
            </div>
            <div class="col-sm-3"></div>
        </div>
        <p class="mini">Máximo 30 caracteres</p>
        <!-- CONTRASEÑA -->
        <div class="form-group row">
            <div class="col-sm-3"></div>
            <label class="col-sm-3 col-form-label" for="contrasena">
                Contraseña
            </label>
            <div class="col-sm-3">
                <input class="form-control" id="contrasena" type="password" name="contrasena"
                value="<?php echo $row['contrasena']; ?>" required>
            </div>
            <div class="col-sm-3"></div>
        </div>
        <p class="mini">Máximo 30 caracteres / Mínimo 4 caracteres</p>
        <!-- CONFIRMAR CONTRASEÑA -->
        <div class="form-group row">
            <div class="col-sm-3"></div>
            <label class="col-sm-3 col-form-label" for="contrasena2">
                Confirmar contraseña
            </label>
            <div class="col-sm-3">
                <input class="form-control" id="contrasena2" type="password" name="contrasena2"
                placeholder="Confirme su contraseña" value="<?php echo $row['contrasena']; ?>" required>
            </div>
            <div class="col-sm-3"></div>
        </div>
        <p class="mini">Máximo 30 caracteres / Mínimo 4 caracteres</p>
        <!-- NIVEL  -->
        <div class="form-group row">
            <div class="col-sm-3"></div>
            <label class="col-sm-3 col-form-label" for="nivel">
                Nivel de Usuario
            </label>
            <div class="col-sm-3">
                <select class="form-control" name="nivel" id="nivel">

                    <?php
                    if ($row["nivel"] == "cliente")
                    {
                        ?>
                        <option>Cliente</option>
                        <option>Admin</option>
                        <?php
                    }
                    if ($row["nivel"] == "admin")
                    {
                        ?>
                        <option>Admin</option>
                        <option>Cliente</option>
                        <?php
                    }
                    ?>

                </select>
            </div>
            <div class="col-sm-3"></div>
        </div>
        <!-- PREGUNTA SECRETA -->
        <div class="form-group row">
            <div class="col-sm-3"></div>
            <label class="col-sm-3 col-form-label" for="pregunta_secreta">
                Pregunta secreta
            </label>
            <div class="col-sm-3">
                <select class="form-control" name="pregunta_secreta" id="pregunta_secreta">

                    <?php
                    if ($row["pregunta_secreta"] == "Color Favorito")
                    {
                        ?>
                        <option>Color Favorito</option>
                        <option>Comida Favorita</option>
                        <option>Banda Favorita</option>
                        <option>Animal Favorito</option>
                        <?php
                    }
                    if ($row["pregunta_secreta"] == "Comida Favorita")
                    {
                        ?>
                        <option>Comida Favorita</option>
                        <option>Color Favorito</option>
                        <option>Banda Favorita</option>
                        <option>Animal Favorito</option>
                        <?php
                    }
                    ?>
                    <?php
                    if ($row["pregunta_secreta"] == "Banda Favorita")
                    {
                        ?>
                        <option>Banda Favorita</option>
                        <option>Comida Favorita</option>
                        <option>Color Favorito</option>
                        <option>Animal Favorito</option>
                        <?php
                    }
                    if ($row["pregunta_secreta"] == "Animal Favorito")
                    {
                        ?>
                        <option>Animal Favorito</option>
                        <option>Color Favorito</option>
                        <option>Comida Favorita</option>
                        <option>Banda Favorita</option>
                        <?php
                    }
                    ?>
                    
                </select>
            </div>
            <div class="col-sm-3"></div>
        </div>
        <!-- RESPUESTA SECRETA -->
        <div class="form-group row">
            <div class="col-sm-3"></div>
            <label class="col-sm-3 col-form-label" for="respuesta_secreta">
                Respuesta secreta
            </label>
            <div class="col-sm-3">
                <input class="form-control" id="respuesta_secreta" type="text" name="respuesta_secreta"
                value="<?php echo $row['respuesta_secreta']; ?>" required onkeyup="this.value = this.value.toLowerCase();">
            </div>
            <div class="col-sm-3"></div>
        </div>
        <p class="mini">Máximo 30 caracteres / Mínimo 2 caracteres</p>
        <div class="row">
            <div class="col-sm-3"></div>
            <div class="col-sm-6">
                <input class="btn btn-outline-primary btn-block" type="submit" name="boton_modificar"
                value="Modificar" .disabled>
            </div>
            <div class="col-sm-3"></div>
        </div>
    </form>
</div>
<?php
}
?>
<script src="javascript/lowerCase.js"></script>
</body>

</html>