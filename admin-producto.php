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
    <title>Registro y modificación de Productos</title>
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="css/bootstrapguayuco.css">
    <link rel="stylesheet" type="text/css" href="css/guayucostyles.css">
    <script src="javascript/validacion_registro_producto.js"></script>
    <script src="javascript/validacion_modificacion_producto.js"></script>
    <script src="javascript/letras_minusculas.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>

<body>
    <?php
	//SI SE ELIGE LA FUNCIÓN DE INGRESAR PRODUCTOS
    if (!isset($_REQUEST["modificar_producto"]) and !isset($_REQUEST["id_producto"]))
    {
      ?>
      <div class="container-fluid">
        <div class="row">
            <div class="col-sm-1"></div>
            <div class="col-sm-10">
                <h2 class="title">Registro de Productos</h2>
            </div>
            <div class="col-sm-1">
                <a class="btn btn-outline-secondary btn-block" href="admin.php">
                    <</a>
                </div>
            </div><br><br>
            <form name="registro_producto" action="procesos/producto_proceso.php" method="POST"
            onsubmit="return validacion_registro_producto()">
            <!-- NOMBRE -->
            <div class="form-group row">
                <div class="col-sm-3"></div>
                <label class="col-sm-3 col-form-label" for="nombre">
                    Nombre
                </label>
                <div class="col-sm-3">
                    <input class="form-control" id="nombre" type="text" name="nombre" autofocus="yes">
                </div>
                <div class="col-sm-3"></div>
            </div>
            <p class="mini">Máximo 120 caracteres / Mínimo 4 caracteres</p>
            <!-- STOCK -->
            <div class="form-group row">
                <div class="col-sm-4"></div>
                <label class="col-sm-2 col-form-label" for="stock">
                    Stock
                </label>
                <div class="col-sm-2">
                    <input class="form-control" id="stock" type="number" name="stock" min="7" max="82">
                </div>
                <div class="col-sm-4"></div>
            </div>
            <p class="mini">Máximo 82 / Mínimo 7</p>
            <!-- PRECIO -->
            <div class="form-group row">
                <div class="col-sm-4"></div>
                <label class="col-sm-2 col-form-label" for="precio">
                    Precio
                </label>
                <div class="col-sm-2">
                    <input class="form-control" id="precio" type="number" name="precio" step="500" min="10000"
                    max="7000000">
                </div>
                <div class="col-sm-4"></div>
            </div>
            <p class="mini">Máximo 7,000,000 / Mínimo 10,000</p><br>
            <div class="row">
                <div class="col-sm-3"></div>
                <div class="col-sm-3">
                    <input class="btn btn-outline-primary btn-block" type="submit" name="boton_registrar"
                    value="Registrar" .disabled>
                </div>
                <div class="col-sm-4">
                    <a class="btn btn-outline-secondary btn-block" role="button" .disabled
                    href="lista_de_productos.php">Lista de Productos</a>
                </div>
                <div class="col-sm-2"></div>
            </div>
        </form>
    </div>
    <?php
}
	//SI SE ELIGE LA FUNCIÓN DE MODIFICAR PRODUCTOS
else 
{
  $id_producto = $_REQUEST['id_producto'];
  include("conexion.php");
  $query = "SELECT * FROM producto WHERE id_producto = '$id_producto'";
  $resultado = $conexion->query($query);
  $row = $resultado->fetch_assoc();
  ?>
  <div class="container-fluid">
    <div class="row">
        <div class="col-sm-1"></div>
        <div class="col-sm-10">
            <h2 class="title">Modificación de Productos</h2>
        </div>
        <div class="col-sm-1">
            <a class="btn btn-outline-secondary btn-block" href="lista_de_productos.php">
                <</a>
            </div>
        </div><br><br>
        <form name="modificar_producto"
        action="procesos/producto_proceso.php?id_producto=<?php echo $row['id_producto']; ?>" method="POST"
        onsubmit="return validacion_modificacion_producto()">
        <!-- NOMBRE -->
        <div class="form-group row">
            <div class="col-sm-3"></div>
            <label class="col-sm-3 col-form-label" for="nombre">
                Nombre
            </label>
            <div class="col-sm-3">
                <input class="form-control" id="nombre" type="text" name="nombre" required autofocus="yes"
                value="<?php echo $row['nombre']; ?>">
            </div>
            <div class="col-sm-3"></div>
        </div>
        <p class="mini">Máximo 120 caracteres / Mínimo 4 caracteres</p>
        <!-- STOCK -->
        <div class="form-group row">
            <div class="col-sm-4"></div>
            <label class="col-sm-2 col-form-label" for="stock">
                Stock
            </label>
            <div class="col-sm-2">
                <input class="form-control" id="stock" type="number" name="stock" min="7" max="82" required
                value="<?php echo $row['stock']; ?>">
            </div>
            <div class="col-sm-4"></div>
        </div>
        <p class="mini">Máximo 82 / Mínimo 7</p>
        <!-- PRECIO -->
        <div class="form-group row">
            <div class="col-sm-4"></div>
            <label class="col-sm-2 col-form-label" for="precio">
                Precio
            </label>
            <div class="col-sm-2">
                <input class="form-control" id="precio" type="number" name="precio" step="500" min="10000"
                max="7000000" required value="<?php echo $row['precio']; ?>">
            </div>
            <div class="col-sm-4"></div>
        </div>
        <p class="mini">Máximo 7,000,000 / Mínimo 10,000</p><br>
        <div class="row">
            <div class="col-sm-4"></div>
            <div class="col-sm-4">
                <input class="btn btn-outline-primary btn-block" type="submit" name="boton_modificar"
                value="Modificar" .disabled>
            </div>
            <div class="col-sm-4"></div>
        </div>
    </form>
</div>
<?php
}	
?>
</body>

</html>