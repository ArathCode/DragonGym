document.addEventListener("DOMContentLoaded", () => {
    const renovarBtns = document.querySelectorAll(".renovarBtn");
    renovarBtns.forEach(btn => {
        btn.addEventListener("click", function() {
            const idMembresia = this.getAttribute("data-id");
            const fechaInicio = this.getAttribute("data-fecha-inicio");
            document.getElementById("id_membresia_renovar").value = idMembresia;
            document.getElementById("fecha_inicio").value = fechaInicio;
        });
    });

    const editBtns = document.querySelectorAll(".editBtn");
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
}
