<?php 
include_once("conexion.php");
if(!empty($_GET['id'])){
    $clave=$_GET['id'];
    $consulta=mysqli_query($conexion,"DELETE FROM categorias where id_cat=$clave");
    mysqli_close($conexion);
    header("location:../Cliente/Categorias.php");
}
?>