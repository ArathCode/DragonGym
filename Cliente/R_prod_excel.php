<?php
// Incluir el archivo de conexión
include("../Servidor/conexion.php");


// nombre del archivo y charset
header('Content-Type: text/csv; charset=latin1');
header('Content-Disposition: attachment; filename="ReporteUsu.csv"');

// Salida del archivo
$salida = fopen('php://output', 'w');

// Encabezados del CSV
fputcsv($salida, array('Marca', 'Modelo', 'Rodada', 'Cantidad', 'Categoria'));

// Consulta para obtener los datos
$reporteCsv = mysqli_query($conexion,'SELECT u.ID_Producto, u.Nombre, u.Marca, u.Rodada, u.Cantidad,t.nombrec , u.foto    
                                                FROM productos u 
                                                INNER JOIN categorias t ON u.id_cat = t.id_cat');

// Verificar si la consulta fue exitosa
if (!$reporteCsv) {
    die("Error en la consulta: " . $mysqli->error);
}

// Escribir los datos en el archivo CSV
while ($filaR = $reporteCsv->fetch_assoc()) {
    fputcsv($salida, array(
        $filaR['Nombre'],
        $filaR['Marca'],
        $filaR['Rodada'],
        $filaR['Cantidad'],
        $filaR['nombrec']
    ));
}

// Cerrar la salida
fclose($salida);
?>