google.charts.load('current', { packages: ['corechart'] });

google.charts.setOnLoadCallback(init);

function init() {
    // Dibujar gráficos pero mantenerlos ocultos
    drawGenderChart();
    drawMembershipChart();
    drawExpenseChart();

    // Asignar eventos de clic
    document.getElementById('miembros-grafico').addEventListener('click', (e) => {
        e.preventDefault(); // Evita redirección
        showChart('chart_div2');
    });
    document.getElementById('productos-grafico').addEventListener('click', (e) => {
        e.preventDefault();
        showChart('chart_div');
    });
    document.getElementById('categorias-grafico').addEventListener('click', (e) => {
        e.preventDefault();
        showChart('chart_div3');
    });
}

function showChart(chartId) {
    // Ocultar todos los gráficos
    document.querySelectorAll('.graficosR div[id^="chart_div"]').forEach(div => {
        div.style.display = 'none'; // Ocultar todos los gráficos
    });

    // Mostrar el gráfico seleccionado
    const chartElement = document.getElementById(chartId);
    if (chartElement) {
        chartElement.style.display = 'block'; // Mostrar el gráfico correspondiente
    } else {
        console.error(`No se encontró el gráfico con ID: ${chartId}`);
    }
}

function drawGenderChart() {
    var data = google.visualization.arrayToDataTable([
        ['Tipos de usuario', 'Cantidad por tipo'],
        ['Hombres', 30],
        ['Mujeres', 20]
    ]);

    var options = {
        title: 'Conteo de Miembros por Sexo',
        width: 600,
        height: 400,
        pieHole: 0.5,
        colors: ['#67b4f5', '#d823a7'],
        legend: { position: 'bottom' }
    };

    var chart = new google.visualization.PieChart(document.getElementById('chart_div2'));
    chart.draw(data, options);
}

function drawMembershipChart() {
    var data = google.visualization.arrayToDataTable([
        ['Estado', 'Cantidad'],
        ['Vigentes', 15],
        ['Caducados', 5]
    ]);

    var options = {
        title: 'Estado de Membresías',
        width: 600,
        height: 400,
        pieHole: 0.5,
        colors: ['#2ECC71', '#E74C3C'],
        legend: { position: 'bottom' }
    };

    var chart = new google.visualization.PieChart(document.getElementById('chart_div'));
    chart.draw(data, options);
}

function drawExpenseChart() {
    var data = google.visualization.arrayToDataTable([
        ['Mes', 'Total Gasto'],
        ['Enero', 200],
        ['Febrero', 150],
        ['Marzo', 180]
    ]);

    var options = {
        title: 'Gasto Mensual',
        width: 600,
        height: 400,
        hAxis: { title: 'Mes', slantedText: true },
        vAxis: { title: 'Total Gasto' },
        legend: { position: 'none' },
        colors: ['#4285F4']
    };

    var chart = new google.visualization.ColumnChart(document.getElementById('chart_div3'));
    chart.draw(data, options);
}
