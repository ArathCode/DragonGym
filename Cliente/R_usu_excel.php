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


$encabezados = ['Nombre', 'Apellido Paterno', 'Apellido Materno', 'Telefono', 'FechaInicio', 'FechaFin'];
$sheet->fromArray($encabezados, NULL, 'A1');


$query = "SELECT * FROM miembros ORDER BY FechaFin DESC";
$resultado = mysqli_query($conexion, $query);

if (!$resultado) {
    die("Error en la consulta: " . mysqli_error($conexion));
}

$fecha_actual = date('Y-m-d');


$fila = 2; 
while ($row = mysqli_fetch_assoc($resultado)) {

    $colorFondo = ($row['FechaFin'] < $fecha_actual) ? 'f46281' : '79ec8e'; 

    
    $sheet->setCellValue("A$fila", $row['Nombre']);
    $sheet->setCellValue("B$fila", $row['ApellidoP']);
    $sheet->setCellValue("C$fila", $row['ApellidoM']);
    
    $sheet->setCellValue("D$fila", $row['Telefono']);
    $sheet->setCellValue("E$fila", $row['FechaInicio']);
    $sheet->setCellValue("F$fila", $row['FechaFin']);

    $sheet->getStyle("A$fila:F$fila")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB($colorFondo);

    $fila++;
}

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="ReporteUsuarios.xlsx"');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
?>
