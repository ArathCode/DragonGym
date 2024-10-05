<?php
include_once("conexion.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $id = $_POST['id_cat'];
  $nombre = $_POST['cam1'];
  $descripcion = $_POST['cam2'];
  

  $query = "UPDATE categorias SET nombrec='$nombre', descripcion='$descripcion' WHERE id_cat='$id'";
  $result = mysqli_query($conexion, $query);

  if ($result) {
    header("location:../Cliente/Categorias.php");
  } else {
    echo "Error al actualizar el usuario";
  }
}
?>
