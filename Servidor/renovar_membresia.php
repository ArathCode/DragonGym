<?php
include_once("conexion.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    $id_membresia = $_POST['id_membresia'];
    $fecha_inicio = $_POST['fecha_inicio'];
    $fecha_fin = $_POST['fecha_fin'];
    $tipo = $_POST['tipoMembresia'];

    $total = $_POST['total'];

        $query = "UPDATE miembros SET FechaInicio = '$fecha_inicio', FechaFin = '$fecha_fin', Categoria = '$tipo' WHERE ID_Membresia = $id_membresia";
        $query2 = "INSERT INTO pagosmembresias (ID_GananciasM, ID_Membresia, Fecha, Total) VALUES (NULL, '$id_membresia', CURDATE(), '$total')";

        $result = mysqli_query($conexion, $query);
        $result2 = mysqli_query($conexion, $query2);

        if ($result) {
            header("location:../Cliente/Categorias.php");
          } else {
            echo "Error al actualizar el usuario";
          }
    
}
?>
