<?php
include_once("../Servidor/conexion.php");

$fecha_actual = date('Y-m-d');


$query = "SELECT Sexo AS tipousu, COUNT(*) AS sum FROM miembros GROUP BY Sexo";
$res = mysqli_query($conexion, $query);

$query2 = "
    SELECT 
        SUM(CASE WHEN FechaFin >= '$fecha_actual' THEN 1 ELSE 0 END) AS Vigentes,
        SUM(CASE WHEN FechaFin < '$fecha_actual' THEN 1 ELSE 0 END) AS Caducados
    FROM miembros
";
$res2 = mysqli_query($conexion, $query2);
$queryExpense = "
    SELECT 
        DATE_FORMAT(fecha, '%Y-%m') AS Mes,
        SUM(Precio) AS Total
    FROM gastos
    GROUP BY DATE_FORMAT(fecha, '%Y-%m')
    ORDER BY DATE_FORMAT(fecha, '%Y-%m') ASC
";
$resExpense = mysqli_query($conexion, $queryExpense);
if (!$resExpense) {
    die("Error en la consulta SQL: " . $conexion->error);
}

$expenseRows = [];
while ($expense = $resExpense->fetch_assoc()) {
    $month = $expense["Mes"];
    $total = floatval($expense["Total"]);
    $expenseRows[] = "['$month', $total]";
}

if (!$res || !$res2 ) {
    die("Error en la consulta SQL: " . mysqli_error($conexion));
}

$data2 = mysqli_fetch_assoc($res2);


?>
<!doctype html>
<html lang="en">

<head>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

<script type="text/javascript">

    google.charts.load('current', {
        'packages': ['corechart']
    });

    // Gráfica de conteo por sexo
    google.charts.setOnLoadCallback(drawGenderChart);
    function drawGenderChart() {
        var data = google.visualization.arrayToDataTable([
            ['Tipos de usuario', 'Cantidad por tipo'],
            <?php
            $rows = [];
            while ($fila = $res->fetch_assoc()) {
                $rows[] = "['" . $fila["tipousu"] . "'," . $fila["sum"] . "]";
            }
            echo implode(",", $rows);
            ?>
        ]);

        var options = {
            title: 'Conteo de Miembros por Sexo',
            width: 600,
            height: 400,
            pieHole: 0.5,
            colors: ['#67b4f5', '#d823a7'],
            legend: { position: 'bottom' }
        };

        var chart = new google.visualization.PieChart(document.getElementById('chart_div'));
        chart.draw(data, options);
    }

    // Gráfica de vigentes y caducados
    google.charts.setOnLoadCallback(drawMembershipChart);
    function drawMembershipChart() {
        var data = google.visualization.arrayToDataTable([
            ['Estado', 'Cantidad'],
            ['Vigentes', <?php echo $data2['Vigentes']; ?>],
            ['Caducados', <?php echo $data2['Caducados']; ?>]
        ]);

        var options = {
            title: 'Estado de Membresías',
            width: 600,
            height: 400,
            pieHole: 0.5,
            colors: ['#2ECC71', '#E74C3C'],
            legend: { position: 'bottom' }
        };

        var chart = new google.visualization.PieChart(document.getElementById('chart_div2'));
        chart.draw(data, options);
    }

    // Gráfica de barras para gastos mensuales
    google.charts.setOnLoadCallback(drawExpenseChart);

    google.charts.setOnLoadCallback(drawExpenseChart);

function drawExpenseChart() {
    
    console.log([
        ['Mes', 'Total Gasto'],
        <?php echo implode(",", $expenseRows); ?>
    ]);

    var data = google.visualization.arrayToDataTable([
        ['Mes', 'Total Gasto'],
        <?php echo implode(",", $expenseRows); ?>
    ]);

    var options = {
        title: 'Gasto Mensual',
        width: 800,
        height: 400,
        hAxis: {
            title: 'Mes',
            slantedText: true
        },
        vAxis: {
            title: 'Total Gasto'
        },
        legend: { position: 'none' },
        colors: ['#4285F4']
    };

    var chart = new google.visualization.ColumnChart(document.getElementById('chart_div3'));
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
                <a href="R_usu_pdf.php">Miembros PDF</a>
                <a href="R_prod_pdf.php">Productos PDF</a>
               
            </div>
        </div>

        <div class="col dropdown">
            <a href="#"><img src="Imagenes/excel.png" alt="" width="200px" height="200px"></a>
            <div class="dropdown-content">
                <a href="R_usu_excel.php">Miembros Excel</a>
                <a href="R_prod_excel.php">Productos Excel</a>
                
            </div>
        </div>

        <div class="col dropdown">
            <a href="#"><img src="Imagenes/graf.png" alt="" width="200px" height="200px"></a>
            <div class="dropdown-content">
                <a href="R_usu_graf.php">Miembros Gráfico</a>
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
        <div id="chart_div2">

        </div>
        </div>
        <div class="col">
        <div id="chart_div">

        </div>
        
        </div>
        <div class="col">
        <div id="chart_div3">

        </div>
        
        </div>
        
    </div>
    <br><br>
    <footer>
        <?php include_once("include/footer.php"); ?>
    </footer>
    <!--FOOTER-->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous">
    </script>
<br>
</body>

</html>
