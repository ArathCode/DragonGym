document.addEventListener("DOMContentLoaded", () => {
    const renovarModal = document.getElementById('renovarModal');
    const exampleModal = document.getElementById('exampleModal');
    const exampleModaledit = document.getElementById('exampleModaledit');

    const toggle = document.querySelector('.toggle');
    const navigation = document.querySelector('.navigation');
    const main = document.querySelector('.main');

    toggle.addEventListener('click', () => {
        navigation.classList.toggle('active');
        main.classList.toggle('active');
    });

    window.openModal = function(modalId) {
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

    window.cerrarModal = function(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.close();
        }
    };

    document.querySelectorAll('.close').forEach(closeBtn => {
        closeBtn.addEventListener('click', function () {
            const dialog = closeBtn.closest('dialog');
            if (dialog) {
                cerrarModal(dialog.id);
            }
        });
    });

    const renovarBtns = document.querySelectorAll(".btn-warning");
    renovarBtns.forEach(btn => {
        btn.addEventListener("click", function() {
            const idMembresia = this.getAttribute("data-id");
            const fechaInicio = this.getAttribute("data-fecha-inicio");
            document.getElementById("id_membresia_renovar").value = idMembresia;
            document.getElementById("fecha_inicio").value = fechaInicio;
            openModal('renovarModal'); 
        });
    });

    const editBtns = document.querySelectorAll(".btn-dark");
    editBtns.forEach(btn => {
        btn.addEventListener("click", function() {
            const idMembresia = this.getAttribute("data-id");
            const nombre = this.getAttribute("data-nombre");
            const apellidoP = this.getAttribute("data-apellido-p");
            const apellidoM = this.getAttribute("data-apellido-m");
            const sexo = this.getAttribute("data-sexo");
            const telefono = this.getAttribute("data-telefono");

            document.getElementById("id_membresia_edit").value = idMembresia;
            document.getElementById("nombre_edit").value = nombre;
            document.getElementById("apellido_p_edit").value = apellidoP;
            document.getElementById("apellido_m_edit").value = apellidoM;
            document.getElementById("sexo_edit").value = sexo;
            document.getElementById("telefono_edit").value = telefono;
            openModal('exampleModaledit'); 
        });
    });
});

function calcularTotal() {
    const tipoMembresia = document.getElementById("tipoMembresia").value;
    const cantidad = document.getElementById("cantidad").value;
    const fechaInicio = document.getElementById("fecha_inicio").value;

    if (!cantidad || !fechaInicio) return;

    const fechaInicioDate = new Date(fechaInicio);
    let fechaFinDate = new Date(fechaInicioDate);

    if (tipoMembresia === "Semana") {
        fechaFinDate.setDate(fechaFinDate.getDate() + (cantidad * 7));
    } else if (tipoMembresia === "Mes") {
        fechaFinDate.setMonth(fechaFinDate.getMonth() + parseInt(cantidad));
    }

    const fechaFinInput = document.getElementById("fecha_fin");
    fechaFinInput.value = fechaFinDate.toISOString().split('T')[0];

    const total = calcularPrecio(tipoMembresia, cantidad);
    document.getElementById("total").value = total.toFixed(2);
}

function calcularPrecio(tipo, cantidad) {
    let precioPorUnidad;
    if (tipo === "Semana") {
        precioPorUnidad = 100; 
    } else if (tipo === "Mes") {
        precioPorUnidad = 300; 
    }
    return precioPorUnidad * cantidad;
}
