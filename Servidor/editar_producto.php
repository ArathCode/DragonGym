<?php
include_once("conexion.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['ID_Producto'];
    $nombre = $_POST['cam1'];
    $marca = $_POST['cam2'];
    $rodada = $_POST['cam3'];
    $cantidad = $_POST['cam4'];
    $tipo = $_POST['cam5'];
    
   
    if (!empty($_FILES['cam6']['name'])) {
        $foto = $_FILES['cam6']['name'];
        $ruta_temporal = $_FILES['cam6']['tmp_name'];
        $carpeta_destino = "imagenesc/";

        if (!is_dir($carpeta_destino)) {
            mkdir($carpeta_destino, 0755, true);
        }

        $ruta_final = $carpeta_destino . $foto;

        // Mover el archivo a la carpeta de destino
        if (move_uploaded_file($ruta_temporal, $ruta_final)) {
            // Actualizar la base de datos con la nueva ruta de la imagen
            $query = "UPDATE Productos SET Nombre='$nombre', Marca='$marca', Rodada='$rodada', Cantidad='$cantidad', id_cat='$tipo', foto='$ruta_final' WHERE ID_Producto='$id'";
        } else {
            echo "Error al subir la nueva imagen";
            exit();
        }
    } else {
        // Si no hay nueva imagen, actualizar solo los otros campos
        $query = "UPDATE Productos SET Nombre='$nombre', Marca='$marca', Rodada='$rodada', Cantidad='$cantidad', id_cat='$tipo' WHERE ID_Producto='$id'";
    }

    $result = mysqli_query($conexion, $query);

    if ($result) {
        header("Location: ../Cliente/Productos.php");
    } else {
        echo "Error al actualizar el producto";
    }
}
?>
