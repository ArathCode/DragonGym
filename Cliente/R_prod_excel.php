<?php

require 'lib/vendor/autoload.php'; 

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

include("../Servidor/conexion.php");

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$drawing = new Drawing();
$drawing->setName('Logo');
$drawing->setDescription('Logo del reporte');
$drawing->setPath('imagenes/logo2.png'); 
$drawing->setHeight(80); 
$drawing->setCoordinates('H1'); 
$drawing->setWorksheet($sheet);


$encabezados = ['Nombre', 'Cantidad', 'FechaIngreso', 'Categoria', 'Precio'];
$sheet->fromArray($encabezados, NULL, 'A1');


$query = "SELECT u.ID_Producto, u.Nombre, u.Cantidad, u.FechaIngreso, t.Descripcion , u.Precio    
                                                FROM Inventario u 
                                                INNER JOIN categoriaprod t ON u.ID_CategoriaI = t.ID_CategoriaI";
$resultado = mysqli_query($conexion, $query);

if (!$resultado) {
    die("Error en la consulta: " . mysqli_error($conexion));
}

$fecha_actual = 5;


$fila = 2; 
while ($row = mysqli_fetch_assoc($resultado)) {

    $colorFondo = ($row['Cantidad'] < $fecha_actual) ? 'f46281' : '79ec8e'; 

    
    $sheet->setCellValue("A$fila", $row['Nombre']);
    $sheet->setCellValue("B$fila", $row['Cantidad']);
    $sheet->setCellValue("C$fila", $row['FechaIngreso']);
    
    $sheet->setCellValue("D$fila", $row['Descripcion']);
    $sheet->setCellValue("E$fila", $row['Precio']);
    

    $sheet->getStyle("A$fila:E$fila")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB($colorFondo);

    $fila++;
}

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="ReporteProductos.xlsx"');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
?>
