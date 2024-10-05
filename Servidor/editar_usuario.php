<?php
include_once("conexion.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $id = $_POST['idusuario'];
  $nombre = $_POST['cam1'];
  $apaterno = $_POST['cam2'];
  $amaterno = $_POST['cam3'];
  $correo = $_POST['cam4'];
  $contra = $_POST['cam5'];
  $telefono = $_POST['cam6'];
  $tipo = $_POST['cam7'];
  $ruta =md5($_POST['cam5']);

  $query = "UPDATE usuarios SET NomUsu='$nombre', ApaUsu='$apaterno', AmaUsu='$amaterno', Correo='$correo', Telefono='$telefono', Contra='$contra', ruta='$ruta', idtipo='$tipo' WHERE idusuario='$id'";
  $result = mysqli_query($conexion, $query);

  if ($result) {
    header("location:../Cliente/Uusaurios.php");
  } else {
    echo "Error al actualizar el usuario";
  }
}
?>
