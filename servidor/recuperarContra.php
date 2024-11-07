<?php
//CODIGO AGREGADO EN CLASE
if (!empty($_POST)) {
    if (empty($_POST['correo'])) {
        $alert = '<div class="alert alert-warning" role="alert"><p>No se ingreso ningun correo !!! </p> </div>';
    } else {
        require_once("conexion.php");
        $campo1 = mysqli_real_escape_string($conexion, $_POST['correo']);

        $con = mysqli_query($conexion, "SELECT * FROM usuario WHERE correo = '$campo1'");
        $res = mysqli_num_rows($con);

        if ($res > 0) {
            $dato = mysqli_fetch_array($con);
            header("location:enviarCorreo.php?correo=$campo1");
        } else {
            $alert = '<div class="alert alert-warning" role="alert"><p>El correo no existe en la base de datos </p> </div>';
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../cliente/css/recuperacion.css">
    <title>Recuperación</title>
</head>
<body>
    <div class="encabezado">
        <div class="imagen">
            <img src="../cliente/imgs/logo.jpg" alt="" class="logo">
        </div>
    </div>

    <div class="main">
        <div class="titulo">
            <h2>
                Recuperacion de contraseña
            </h2>
        </div>
        
        <div class="formulario">
            <form method="POST">
                <div class="contenidoIn">
                    <div class="input-group">
                        <input type="email" id="correo" name="correo" required>
                        <label for="correo">Correo electronico</label>
                    </div>
                </div>
                <button type="submit">Enviar</button>
                <a href="../index.php" type="button" class="btn btn-danger" > Regresar</a>
            </form>
        </div>
    </div>
</body>
</html>