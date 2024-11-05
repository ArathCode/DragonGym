<?php 
include_once("conexion.php");
if(!empty($_GET['id'])){
    $clave=$_GET['id'];
    $consulta=mysqli_query($conexion,"DELETE FROM Usuario where ID_Usuario=$clave");
    mysqli_close($conexion);
    header("location:../Cliente/ControlPersonal.php");
}
?>