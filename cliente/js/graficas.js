let myChart; 

function crearGrafica(dataLabels, dataValues) {
    if (myChart) {
        myChart.destroy(); 
    }

    const ctx = document.getElementById('myChart').getContext('2d'); 

    myChart = new Chart(ctx, {
        type: 'bar', 
        data: {
            labels: dataLabels,
            datasets: [{
                label: 'Número de Visitas y Miembros',
                data: dataValues,
                backgroundColor: [
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(153, 102, 255, 0.2)',
                ],
                borderColor: [
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                ],
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
}

document.getElementById('cargarDatos').onclick = function () {
    const dataLabels = ['Visitas', 'Miembros'];
    const dataValues = [10, 5]; 
    cargarDatos(dataLabels, dataValues);
};

function cargarDatos(dataLabels, dataValues) {
    crearGrafica(dataLabels, dataValues); 
}
