document.addEventListener('DOMContentLoaded', function () {
    const openModal = document.getElementById('openModal');
    const modalEstadisticas = document.getElementById('modalEstadisticas');
    const agregarVisitaBtn = document.getElementById('agregarVisitaBtn2'); 
    const estadisticasBtn = document.getElementById('estadisticasBtn');

    openModal.close();
    modalEstadisticas.close();

    function abrirModal(modal) {
        const modales = document.querySelectorAll('dialog');
        modales.forEach(mod => {
            if (mod !== modal && mod.open) {
                mod.close();
            }
        });
        modal.showModal();
    }

    agregarVisitaBtn.addEventListener('click', function (event) {
        event.preventDefault(); 
        abrirModal(openModal); 
    });

    estadisticasBtn.addEventListener('click', function () {
        abrirModal(modalEstadisticas); 
    });

    document.querySelectorAll('.close').forEach(closeBtn => {
        closeBtn.addEventListener('click', function () {
            const dialog = closeBtn.closest('dialog');
            if (dialog) {
                dialog.close();
            }
        });
    });
});
