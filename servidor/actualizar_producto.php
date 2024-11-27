<?php
include("../servidor/conexion.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $nombre = $_POST['nombre'];
    $cantidad = $_POST['cantidad'];
    $precio = $_POST['precio'];
    $lote = $_POST['lote'];
    $fechaIngreso = $_POST['fechaIngreso'];
    $categoria = $_POST['categoria'];

    // Verifica si se subió una nueva foto
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
        $foto = $_FILES['foto']['name'];
        $fotoPath = "../servidor/img_inventario/" . $foto;
        move_uploaded_file($_FILES['foto']['tmp_name'], $fotoPath);

        // Actualiza con la nueva foto
        $query = "UPDATE inventario SET Nombre='$nombre', Cantidad='$cantidad', Precio='$precio', 
                  Lote='$lote', FechaIngreso='$fechaIngreso', Foto='$foto', ID_Categorial='$categoria' 
                  WHERE ID_Producto='$id'";
    } else {
        // Actualiza sin cambiar la foto
        $query = "UPDATE inventario SET Nombre='$nombre', Cantidad='$cantidad', Precio='$precio', 
                  Lote='$lote', FechaIngreso='$fechaIngreso', ID_Categorial='$categoria' 
                  WHERE ID_Producto='$id'";
    }

    $resultado = mysqli_query($conexion, $query);

    if ($resultado) {
        echo json_encode(["success" => true, "message" => "Producto actualizado correctamente."]);
    } else {
        echo json_encode(["success" => false, "message" => "Error al actualizar el producto."]);
    }
}
?>
