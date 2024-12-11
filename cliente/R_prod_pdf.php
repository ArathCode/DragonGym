<?php
// Incluir la librería de FPDF
require("../lib/fpdf/fpdf.php");

class PDF extends FPDF {
    // Cabecera
    function Header() {
        $this->SetFillColor(0,0,0);
        
        
        
        $this->Image('imgs/logo.jpg', 10, 15, 20); 
            $this->SetFont('Arial', 'B', 16);
            $this->SetTextColor(33, 37, 41);
            $this->Cell(0, 10, 'Reporte de Productos', 0, 1, 'C');
            $this->SetFont('Arial', 'I', 12);
            $this->Cell(0, 10, 'Generado el ' . date('d/m/Y H:i:s'), 0, 1, 'C');
            $this->Ln(10);
        
        $this->SetFont("Arial", 'B', 12);
        $this->SetTextColor(255,255,255);
        $this->Cell(50, 10, 'Nombre', 1, 0, 'C',true);
        $this->Cell(40, 10, 'Cantidad', 1, 0, 'C',true);
        $this->Cell(40, 10, 'Fecha Ingreso', 1, 0, 'C',true);
        $this->Cell(30, 10, 'Categoria', 1, 0, 'C',true);
        $this->Cell(20, 10, 'Precio', 1, 0, 'C',true);
       
    
        $this->Ln(10);
    }

    // Pie de página
    function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->SetTextColor(100);
        $this->Cell(0, 10, 'Página ' . $this->PageNo() . '', 0, 0, 'C');
    }
}

require("../servidor/conexion.php");


if (mysqli_connect_errno()) {
    die('Error de conexión: ' . mysqli_connect_error());
}

$consulta = "SELECT u.ID_Producto, u.Nombre, u.Cantidad, u.FechaIngreso, t.Descripcion, u.Precio     
                                                FROM Inventario u 
                                                INNER JOIN categoriaprod t ON u.ID_Categorial = t.ID_Categorial";
$resultado = mysqli_query($conexion, $consulta);

if (!$resultado) {
    die('Error en la consulta: ' . mysqli_error($conexion));
}

$pdf = new PDF('P');
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 10);

while ($row = mysqli_fetch_assoc($resultado)) {
    $pdf->Cell(50, 10, utf8_decode( $row['Nombre']), 1, 0, 'C');
    $pdf->Cell(40, 10,utf8_decode( $row['Cantidad']), 1, 0, 'C');
    $pdf->Cell(40, 10,utf8_decode( $row['FechaIngreso']), 1, 0, 'C');
    $pdf->Cell(30, 10, utf8_decode($row['Descripcion']), 1, 0, 'C');
    $pdf->Cell(20, 10, utf8_decode($row['Precio']), 1, 0, 'C');

    $pdf->Ln();
}

$pdf->Output();
?>