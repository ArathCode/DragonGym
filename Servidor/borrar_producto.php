<?php 
include_once("conexion.php");
if(!empty($_GET['id'])){
    $clave=$_GET['id'];
    $consulta=mysqli_query($conexion,"DELETE FROM Productos where ID_Producto=$clave");
    mysqli_close($conexion);
    header("location:../Cliente/Productos.php");
}
?>