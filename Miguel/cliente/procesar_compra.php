<?php
include_once("../servidor/conexion.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idProducto = $_POST['id'];
    $cantidad = $_POST['cantidad'];

   
    $productoQuery = "SELECT * FROM inventario WHERE ID_Producto = '$idProducto'";
    $productoResult = mysqli_query($conexion, $productoQuery);
    $producto = mysqli_fetch_assoc($productoResult);

    if ($producto && $producto['Cantidad'] >= $cantidad) {
       
        $nuevoStock = $producto['Cantidad'] - $cantidad;
        $updateQuery = "UPDATE inventario SET Cantidad = '$nuevoStock' WHERE ID_Producto = '$idProducto'";
        mysqli_query($conexion, $updateQuery);

       
        $fecha = date('Y-m-d');
        $ventaQuery = "INSERT INTO ventas (Fecha) VALUES ('$fecha')";
        mysqli_query($conexion, $ventaQuery);
        $idVenta = mysqli_insert_id($conexion);

        $subtotal = $cantidad * $producto['Precio'];
        $detalleVentaQuery = "INSERT INTO detalleventa (ID_Venta, Cantidad, Precio, Subtotal, ID_Producto) 
                              VALUES ('$idVenta', '$cantidad', '{$producto['Precio']}', '$subtotal', '$idProducto')";
        mysqli_query($conexion, $detalleVentaQuery);

        echo "Compra realizada con éxito.";
    } else {
        echo "No hay suficiente stock para completar la compra.";
    }
}
?>
