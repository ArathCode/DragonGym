<?php
include_once("conexion.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $id = $_POST['id_membresia'];
  $nombre = $_POST['nombre'];
  $apellidoP = $_POST['apellido_p'];
  $apellidoM = $_POST['apellido_m'];
  $sexo = $_POST['sexo'];
  $telefono = $_POST['telefono'];
  
  echo "ID Membresia: $id, Nombre: $nombre, ApellidoP: $apellidoP, ApellidoM: $apellidoM, Sexo: $sexo, Teléfono: $telefono";
  
  $query = "UPDATE miembros SET Nombre='$nombre', ApellidoP='$apellidoP', ApellidoM='$apellidoM', Sexo='$sexo', Telefono='$telefono' WHERE ID_Membresia='$id'";
  
  $result = mysqli_query($conexion, $query);

  if (!$result) {
    die("Error en la consulta: " . mysqli_error($conexion));
  } else {
    header("Location: ../cliente/Miembros.php");
    exit();
  }
}
?>
