document.addEventListener("DOMContentLoaded", () => {
    // Toggle menu functionality
    const toggle = document.querySelector('.toggle');
    const navigation = document.querySelector('.navigation');
    const main = document.querySelector('.main');

    toggle.addEventListener('click', () => {
        navigation.classList.toggle('active');
        main.classList.toggle('active');
    });

    // Modal handling
    const exampleModal = document.getElementById('exampleModal');
    const exampleModaledit = document.getElementById('exampleModaledit');

    // Function to open a modal by ID
    window.abrirModal = function(modalId) {
        const modal = document.getElementById(modalId);
        if (!modal) return;

        const modales = document.querySelectorAll('dialog');
        modales.forEach(mod => {
            if (mod !== modal && mod.open) {
                mod.close();
            }
        });
        modal.showModal();
    };

    // Function to close a modal by ID
    window.cerrarModal = function(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.close();
        }
    };

    // Close modal when clicking on the "×" button
    document.querySelectorAll('.close').forEach(closeBtn => {
        closeBtn.addEventListener('click', function () {
            const dialog = closeBtn.closest('dialog');
            if (dialog) {
                cerrarModal(dialog.id);
            }
        });
    });

    // Event listener for "Agregar gasto" button to open add modal
    const agregarBtn = document.querySelector(".agregarGB");
    agregarBtn.addEventListener("click", function() {
        abrirModal('exampleModal');
    });

    // Event listener for "Editar gasto" buttons to open edit modal and fill in data
    const editBtns = document.querySelectorAll(".editBtn");
    editBtns.forEach(btn => {
        btn.addEventListener("click", function() {
            const idGasto = this.getAttribute("data-id");
            const descripcion = this.getAttribute("data-descripcion");
            const precio = this.getAttribute("data-precio");
            const fecha = this.getAttribute("data-fecha");
            const usuario = this.getAttribute("data-usu");

            // Set modal fields with fetched data
            document.getElementById("edit-id").value = idGasto;
            document.getElementById("edit-nombre").value = descripcion;
            document.getElementById("edit-apaterno").value = precio;
            document.getElementById("edit-amaterno").value = fecha;
            document.getElementById("edit-tipo").value = usuario;

            abrirModal('exampleModaledit');
        });
    });
});
