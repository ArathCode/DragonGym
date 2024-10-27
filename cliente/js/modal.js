document.addEventListener('DOMContentLoaded', function () {
    const agregarVisitaBtn = document.getElementById('agregarVisitaBtn');
    const openModal = document.getElementById('openModal');
    const modalEstadisticas = document.getElementById('modalEstadisticas');

    if (agregarVisitaBtn) {
        agregarVisitaBtn.addEventListener('click', function () {
            openModal.style.display = 'block';
        });
    }

    const closeModal = document.getElementById('closeModal');
    if (closeModal) {
        closeModal.addEventListener('click', function () {
            openModal.style.display = 'none';
            modalEstadisticas.style.display = 'block';
        });
    }

    // Abrir el modal de estadísticas al hacer clic en el botón
    const estadisticasBtn = document.getElementById('estadisticasBtn');
    if (estadisticasBtn) {
        estadisticasBtn.addEventListener('click', function () {
            modalEstadisticas.style.display = 'block';
        });
    }

    // Cerrar el modal de estadísticas
    const closeEstadisticas = document.getElementById('closeEstadisticas');
    if (closeEstadisticas) {
        closeEstadisticas.addEventListener('click', function () {
            modalEstadisticas.style.display = 'none';
        });
    }

    // Cerrar el modal al hacer clic fuera de él
    window.onclick = function (event) {
        if (event.target === modalEstadisticas) {
            modalEstadisticas.style.display = 'none';
        }
        if (event.target === openModal) {
            openModal.style.display = 'none';
        }
    };
});
