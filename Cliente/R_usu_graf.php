<?php
session_start();
include_once("../Servidor/conexion.php");

// Ejecución de la consulta y manejo de errores
$sql = "SELECT r.tipousu, COUNT(u.idtipo) as sum 
        FROM usuarios AS u 
        INNER JOIN tipousuarios AS r 
        ON u.idtipo = r.idtipo 
        GROUP BY u.idtipo";

$res = $conexion->query($sql);

if (!$res) {
    die("Error en la consulta SQL: " . $conexion->error);
}
?>
<html>

<head>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
     google.charts.load('current', {'packages':['corechart']});
    
    google.charts.setOnLoadCallback(drawChart);

    function drawChart() {
        var data = google.visualization.arrayToDataTable([
            ['Tipos de usuario', 'Cantidad por tipo'],
            <?php
                $rows = [];
                while ($fila = $res->fetch_assoc()) {
                    $rows[] = "['" . $fila["tipousu"] . "'," . $fila["sum"] . "]";
                }
                echo implode(",", $rows); // Elimina la coma final
                ?>
        ]);

        var options = {
            title: 'TIPOS DE USUARIOS',
           
            pieHole: 0.9,
         
        };

        var chart = new google.visualization.PieChart(document.getElementById('donut_single'));
        chart.draw(data, options);
    }
    </script>
</head>
<body>
    <div class="row">

    
    <div id="donut_single" class="col" style="text-align:center">
        
    </div>
    </div>
</body>