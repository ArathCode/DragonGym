<?php

require("../lib/fpdf/fpdf.php");

class PDF extends FPDF {
    // Cabecera
    function Header() {
        $this->SetFillColor(0, 0, 0);
        
        $this->Image('imgs/logo.jpg', 10, 15, 20); 
        $this->SetFont('Arial', 'B', 16);
        $this->SetTextColor(33, 37, 41);
        $this->Cell(0, 10, 'Reporte de miembros', 0, 1, 'C');
        $this->SetFont('Arial', 'I', 12);
        $this->Cell(0, 10, 'Generado el ' . date('d/m/Y H:i:s'), 0, 1, 'C');
        $this->Ln(10);
        $this->SetFont("Arial", 'B', 12);
        $this->SetTextColor(255,255,255);
        $this->Cell(30, 10, 'Nombre', 1, 0, 'C', true);
        $this->Cell(30, 10, 'Paterno', 1, 0, 'C', true);
        $this->Cell(30, 10, 'Materno', 1, 0, 'C', true);
        $this->Cell(30, 10, 'FechaInicio', 1, 0, 'C', true);
        $this->Cell(30, 10, 'FechaFin', 1, 0, 'C', true);
        $this->Cell(25, 10, 'Telefono', 1, 0, 'C', true);
        $this->Cell(15, 10, 'Meses', 1, 0, 'C', true);
        $this->Ln(10);
    }

    function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->SetTextColor(100);
        $this->Cell(0, 10, 'Pagina ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }
}


require("../servidor/conexion.php");

if (mysqli_connect_errno()) {
    die('Error de conexión: ' . mysqli_connect_error());
}


$consulta = "SELECT * FROM miembros  ORDER BY FechaFin DESC";
$resultado = mysqli_query($conexion, $consulta);

if (!$resultado) {
    die('Error en la consulta: ' . mysqli_error($conexion));
}

$pdf = new PDF('P');
$pdf->AddPage();
$pdf->SetFont('Arial', '', 10);

$fecha_actual = date('Y-m-d');

while ($row = mysqli_fetch_assoc($resultado)) {
    
    $fecha_fin = $row['FechaFin'];
    if ($fecha_fin < $fecha_actual) {
        $pdf->SetFillColor(241, 176, 190); 
    } else {
        $pdf->SetFillColor(165, 246, 178); 
    }

    $pdf->Cell(30, 10, utf8_decode($row['Nombre']), 1, 0, 'C', true);
    $pdf->Cell(30, 10, utf8_decode($row['ApellidoP']), 1, 0, 'C', true);
    $pdf->Cell(30, 10, utf8_decode($row['ApellidoM']), 1, 0, 'C', true);
    $pdf->Cell(30, 10, utf8_decode($row['FechaInicio']), 1, 0, 'C', true);
    $pdf->Cell(30, 10, utf8_decode($row['FechaFin']), 1, 0, 'C', true);
    $pdf->Cell(25, 10, utf8_decode($row['Telefono']), 1, 0, 'C', true);
    $pdf->Cell(15, 10, utf8_decode($row['MesesT']), 1, 0, 'C', true);
    $pdf->Ln();
}

$pdf->Output();
?>
