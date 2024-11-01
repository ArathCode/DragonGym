<?php 
include_once("conexion.php");
if(!empty($_GET['id'])){
    $clave=$_GET['id'];
    $consulta=mysqli_query($conexion,"DELETE FROM Gastos where ID_Gasto=$clave");
    mysqli_close($conexion);
    header("location:../cliente/Gastos.php");
}
?>