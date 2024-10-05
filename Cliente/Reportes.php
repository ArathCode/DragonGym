<?php

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
<!doctype html>
<html lang="en">

<head>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
    google.charts.load('current', {
        'packages': ['corechart']
    });
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
            width: 600,
            height: 400,
            pieHole: 0.5,
        };

        var chart = new google.visualization.PieChart(document.getElementById('chart_div'));
        chart.draw(data, options);
    }
    </script>
    <link rel="shortcut icon" href="Imagenes/logof.jpg" />
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    
    <title>Reportes</title>

    <style>
        .dropdown-content {
            display: none;
            position: absolute;
            background-color: white;
            box-shadow: 0px 8px 16px rgba(0,0,0,0.2);
            z-index: 1;
            min-width: 180px;
            
        }

        .dropdown:hover .dropdown-content {
            display: block;
        }

        .dropdown-content a {
            color: black;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
        }

        .dropdown-content a:hover {
            background-color: black;
            font-weight: bold;
            color:white
        }
    </style>
</head>

<body>

    <!--ENCABEZADO-->
    <?php include_once("include/encabezado.php"); ?>
    <!--ENCABEZADO-->
    <br>
     <!--body-->
    
    <div class="container">
    <h2>
        Reportes
    </h2>
    <br>
        <br>
        <br>
    <div class="row" style="text-align:center">
        
        
        <div class="col dropdown">
            <a href="#"><img src="Imagenes/pdf.png" alt="" width="200px" height="200px"></a>
            <div class="dropdown-content">
                <a href="R_usu_pdf.php">Usuarios PDF</a>
                <a href="R_prod_pdf.php">Productos PDF</a>
                <a href="R_cat_pdf.php">Categorías PDF</a>
            </div>
        </div>

        <div class="col dropdown">
            <a href="#"><img src="Imagenes/excel.png" alt="" width="200px" height="200px"></a>
            <div class="dropdown-content">
                <a href="R_usu_excel.php">Usuarios Excel</a>
                <a href="R_prod_excel.php">Productos Excel</a>
                <a href="R_cat_excel.php">Categorías Excel</a>
            </div>
        </div>

        <div class="col dropdown">
            <a href="#"><img src="Imagenes/graf.png" alt="" width="200px" height="200px"></a>
            <div class="dropdown-content">
                <a href="R_usu_graf.php">Usuarios Gráfico</a>
                <a href="R_prod_gra.php">Productos Gráfico</a>
                <a href="R_cat_gra.php">Categorías Gráfico</a>
            </div>
        </div>
        
    </div>
    </div>
   <!--body-->
    <!--FOOTER-->
    <div  class="row" style="text-aling:center;">
        <div class="col">
        
        </div>
        <div class="col">
        <div id="chart_div">

        </div>
        
        </div>
        <div class="col">
        
        </div>
        
    </div>
    <footer>
        <?php include_once("include/footer.php"); ?>
    </footer>
    <!--FOOTER-->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous">
    </script>

</body>

</html>
