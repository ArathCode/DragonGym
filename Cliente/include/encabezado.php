<?php 
    session_start();
    if (!isset($_SESSION['tiempo'])) {
        $_SESSION['tiempo']=time();
    }
    else if (time() - $_SESSION['tiempo'] > 1000) {
        
        session_destroy();
        header('location:../');
        die();  
    }
    $_SESSION['tiempo']=time();
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <style>
        html {
  min-height: 100%;
  position: relative;
}
body {
  margin: 0;
  margin-bottom: 50px;
  margin-top:10px;
}
footer {
  background-color: black;
  position: absolute;
  bottom: 0;
  width: 100%;
  height: 40px;
  color: white;
  margin-top:50px
}
    </style>
</head>

<body>

    <div  style="text-align: center; padding-top:15px ;Background-color: black">
        <div class="row">
            <div class="col-3">
                <img src="Imagenes/Logo2.jpg" height="100" width="250" style="padding:3px ; margin-bottom: 10px; margin-left: 90px;">
            </div>
            
            <div class="col">
            
                <div class="btn-group" style="padding-top:38px; font-weight: bold;">
                    <?php if($_SESSION['rol']==1){?>
                    <a href="Inicio.php" class="btn btn-primary " style="background-color: black; border-color:black ;  font-weight: bold;">Inicio</a>
                    <a href="Uusaurios.php" class="btn btn-primary " style="background-color: black ;   border-color:black ;font-weight: bold;">Usuarios</a>
                    <a href="Categorias.php" class="btn btn-primary" style="background-color: black ;border-color:black ;font-weight: bold;">Categorias</a>
                    <a href="Productos.php" class="btn btn-primary" style="background-color: black;  border-color:black;font-weight: bold; ">Productos</a>
                    <a href="Promociones.php" class="btn btn-primary" style="background-color: black;  border-color:black;font-weight: bold; ">Promociones</a>
                    <a href="Reportes.php" class="btn btn-primary" style="background-color: black;  border-color:black;font-weight: bold; ">Reportes</a>
                    <a href="Salir.php" class="btn btn-primary" style="background-color: black;  border-color:black;font-weight: bold; ">Salir</a>
                   <?php } ?>
                   <?php if($_SESSION['rol']==2){?>
                    <a href="Inicio.php" class="btn btn-primary " style="background-color: black; border-color:black ">Inicio</a>
                    <a href="Uusaurios.php" class="btn btn-primary " style="background-color: black ;   border-color:black ;">Usuarios</a>
                    <a href="Productos.php" class="btn btn-primary" style="background-color: black;  border-color:black ">Productos</a>
                    <a href="Promociones.php" class="btn btn-primary" style="background-color: black;  border-color:black ">Promociones</a>
                    <a href="Salir.php" class="btn btn-primary" style="background-color: black;  border-color:black ">Salir</a>
                   <?php } ?>
                </div>
            </div>
           
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
</body>

</html>