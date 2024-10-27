<?php
session_start();
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dragons Gym</title>

    <!-- ======= Styles ====== -->
</head>

<body>
    <!-- =============== Barra de navegacion ================ -->
            <ul>
                <li>
                    <a href="#">
                        <span class="icon">
                            <img src="imgs/logo.jpg" alt="Logo" class="logo">
                        </span>
                        <span class="title">Dragon Gym</span>
                    </a>
                </li>

                <li>
                    <a href="home.php">
                        <span class="icon">
                            <ion-icon name="home-outline"></ion-icon>
                        </span>
                        <span class="title">Inicio</span>
                    </a>
                </li>

                <li>
                    <a href="Miembros.php">
                        <span class="icon">
                            <ion-icon name="people-outline"></ion-icon>
                        </span>
                        <span class="title">Miembros</span>
                    </a>
                </li>

                <li>
                    <a href="Gastos.php">
                        <span class="icon">
                            <ion-icon name="pricetag-outline"></ion-icon>
                        </span>
                        <span class="title">Productos</span>
                    </a>
                </li>



                <li>
                    <a href="#">
                        <span class="icon">
                            <ion-icon name="settings-outline"></ion-icon>
                        </span>
                        <span class="title">Ajustes</span>
                    </a>
                </li>

                <li>
                    <a href="#">
                        <span class="icon">
                            <ion-icon name="lock-closed-outline"></ion-icon>
                        </span>
                        <span class="title">Administración</span>
                    </a>
                </li>

                <li>
                    <a href="../index.php">
                        <span class="icon">
                            <ion-icon name="log-out-outline"></ion-icon>
                        </span>
                        <span class="title">Salir</span>
                    </a>
                </li>
            </ul>


    
</body>

</html>