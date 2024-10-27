<?php
$alert = "";

if (!empty($_SESSION['activa'])) {
    header('location: cliente/home.php');
} else {
    if (!empty($_POST)) {
        if (empty($_POST['nombre']) || empty($_POST['contra'])) {
            $alert = '<div class="alert alert-warning d-flex align-items-center" role="alert">
                        <div>Usuario y/o contraseña son obligatorios</div>
                    </div>';
        } else {
            require_once('servidor/conexion.php');
            $usuario = mysqli_real_escape_string($conexion, $_POST['nombre']);
            $pass = mysqli_real_escape_string($conexion, $_POST['contra']);
            $query = mysqli_query($conexion, "SELECT * FROM usuario WHERE NombreUsuario='$usuario' AND Contraseña='$pass'");
            mysqli_close($conexion);
            $resultado = mysqli_num_rows($query);
            if ($resultado > 0) {
                $dato = mysqli_fetch_array($query);
                $_SESSION['activa'] = true;
                $_SESSION['nombre'] = $dato['NombreUsuario'];
                $_SESSION['rol'] = $dato['ID_Rol'];
                header('location: cliente/home.php');
            } else {
                $alert = '<div class="alert alert-danger d-flex align-items-center" role="alert">
                            <div>Usuario y/o contraseña incorrecta!!!</div>
                          </div>';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Dragon Gym</title>
    <link rel="stylesheet" href="cliente/css/login.css">
</head>
<body>
    <div class="container">
        <div class="left-section">
            <div class="content">
                <img src="cliente/imgs/logo.jpg" alt="Logo" class="logo">
            </div>
        </div>
        <div class="right-section">
            <h2>Hola, Bienvenido</h2>
            <p>Sistema Dragon' Gym</p>
            <form method="POST">
                <div>
                    <?php echo isset($alert) ? $alert : ""; ?>
                </div>
                <div class="input-group">
                    <input type="text" id="nombre" name="nombre" required>
                    <label for="nombre">Usuario</label>
                </div>
                <div class="input-group">
                    <input type="password" id="contra" name="contra" required>
                    <label for="contra">Contraseña</label>
                </div>
                <a href="#">¿Olvidó la contraseña?</a>
                <button type="submit" class="login">
                    <span>Ingresar</span>
                </button>
            </form>
        </div>
    </div>
</body>
</html>
