<?php
include("../servidor/conexion.php");

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $_POST['nombre'];
    $cantidad = $_POST['cantidad'];
    $precio = $_POST['precio'];
    $lote = $_POST['lote'];
    $fechaIngreso = $_POST['fechaIngreso'];
    $categoria = $_POST['categoria'];

    $foto = $_FILES['foto']['name'];
    $foto_temp = $_FILES['foto']['tmp_name'];
    $ruta_foto = "../servidor/img_inventario/" . $foto;

    if (move_uploaded_file($foto_temp, $ruta_foto)) {
        $query = "INSERT INTO inventario (Nombre, Cantidad, Precio, Lote, FechaIngreso, Foto, ID_Categorial) 
                  VALUES ('$nombre', '$cantidad', '$precio', '$lote', '$fechaIngreso', '$foto', '$categoria')";

        if (mysqli_query($conexion, $query)) {
            echo json_encode(["success" => true]);
        } else {
            echo json_encode(["success" => false, "message" => "Error en la consulta: " . mysqli_error($conexion)]);
        }
    } else {
        echo json_encode(["success" => false, "message" => "Error al cargar la imagen"]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Solicitud no válida"]);
}

mysqli_close($conexion);
?>
