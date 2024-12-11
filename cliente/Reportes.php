<?php
include_once("../servidor/conexion.php");

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

if (!$res || !$res2) {
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
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
    <link rel="stylesheet" href="css/Reportes.css">
    <title>Reportes</title>

</head>

<body>

    <!--ENCABEZADO-->
    <div class="container" id="menu">
        <div class="navigation">
            <?php
            include_once("include/encabezado.php")
                ?>
        </div>

        <div class="main">
            <div class="topbar">
                <div class="toggle">
                    <ion-icon name="menu-outline"></ion-icon>
                </div>
                <div class="subMenu">
                    <div class="gastos">
                        <div class="iconoGa">
                            <ion-icon name="wallet-outline"></ion-icon>
                        </div>
                        <div class="enlace">
                            <a href="Gastos.php">Gastos</a>
                        </div>
                    </div>
                    <div class="inventario">
                        <div class="iconoIn">
                            <ion-icon name="archive-outline"></ion-icon>
                        </div>
                        <div class="enlace">
                            <a href="inventario.php">Inventario</a>
                        </div>
                    </div>
                    <div class="adminUsuarios">
                        <div class="iconoAd">
                            <ion-icon name="people-outline"></ion-icon>
                        </div>
                        <div class="enlace">
                            <a href="ControlPersonal.php">Usuarios</a>
                        </div>
                    </div>
                    <div class="reportes">
                        <div class="iconoRe">
                            <ion-icon name="document-attach-outline"></ion-icon>
                        </div>
                        <div class="enlace">
                            <a href="Reportes.php">Reportes</a>
                        </div>
                    </div>
                </div>

                <div class="contenedor">
                    <div class="usuario">
                        <img src="https://i.pinimg.com/originals/a0/14/7a/a0147adf0a983ab87e86626f774785cf.gif" alt="">
                    </div>
                </div>
            </div>

            <div class="container">
                <div class="titulo">
                    <h2>
                        Reportes
                    </h2>
                </div>


                <div class="row" style="text-align:center">
                    <div class="col dropdown">
                        <a href="#"><img src="imgs/pdf.png" alt="" width="200px" height="200px"></a>
                        <div class="dropdown-content">
                            <a href="R_usu_pdf.php">Miembros PDF</a>
                            <a href="R_prod_pdf.php">Productos PDF</a>
                        </div>
                    </div>

                    <div class="col dropdown">
                        <a href="#"><img src="imgs/excel.png" alt="" width="200px" height="200px"></a>
                        <div class="dropdown-content">
                            <a href="R_usu_excel.php">Miembros Excel</a>
                            <a href="R_prod_excel.php">Productos Excel</a>
                        </div>
                    </div>

                    <div class="col dropdown">
                        <a href="#"><img src="imgs/graf.png" alt="" width="200px" height="200px"></a>
                        <div class="dropdown-content">
                            <a href="#" id="miembros-grafico">Miembros Gráfico</a>
                            <a href="#" id="productos-grafico">Productos Gráfico</a>
                            <a href="#" id="categorias-grafico">Categorías Gráfico</a>
                        </div>
                    </div>

                </div>
            </div>

            <div class="titulo">
                <h2>
                    Estadísticas
                </h2>
            </div>

            <div class="graficosR" style="text-align:center;">
                <div class="gra12">
                    <div class="gra1">
                        <div id="chart_div2" ></div>
                    </div>
                    <div class="gra2">
                        <div id="chart_div" ></div>
                    </div>
                </div>
                <div class="gra3">
                    <div id="chart_div3" ></div>
                </div>
            </div>

        </div>
    </div>
</body>

</html>


</html>