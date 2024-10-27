<?php

$host = "localhost";
$user = "root";
$clave = "";
$bd = "dragongym";
$conexion=mysqli_connect($host,$user,$clave,$bd);
if(mysqli_connect_error()){
    echo "no se pudo conectar a la base de datos";
    exit();
}

mysqli_select_db($conexion,$bd) or die ("no se encuentra la base de datos");
mysqli_set_charset($conexion, "utf8");

?>