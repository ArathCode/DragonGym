<?php
include_once("conexion.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $id = $_POST['ID_Gasto'];
  $des = $_POST['cam1'];
  $pre = $_POST['cam2'];
  $fecha = $_POST['cam3'];
  $usu = $_POST['cam4'];
 

  $query = "UPDATE gastos SET Descripcion='$des', Precio='$pre', Fecha='$fecha', ID_Usuario='$usu' WHERE ID_Gasto='$id'";
  $result = mysqli_query($conexion, $query);

  if ($result) {
    header("location:../cliente/Gastos.php");
  } else {
    echo "Error al actualizar el usuario";
  }
}
?>
