<?php
include_once("conexion.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $id = $_POST['ID_Membresia'];
  $nombre = $_POST['nombre'];
  $apellidoP = $_POST['apellido_p'];
  $apellidoM = $_POST['apellido_m'];
  $sexo = $_POST['sexo'];
  $telefono = $_POST['telefono'];
  

  $query = "UPDATE miembros SET Nombre='$nombre', ApellidoP='$apellidoP', ApellidoM='$apellidoM',Sexo='$sexo', Telefono='$telefono' WHERE ID_Membresia='$id'";
  $result = mysqli_query($conexion, $query);

  if ($result) {
    header("location:../cliente/Miembros.php");
  } else {
    echo "Error al actualizar el usuario";
  }
}
?>
