// Variables globales para almacenar la fila seleccionada
let filaSeleccionada;

// Abrir y cerrar modales
document.getElementById("btnNuevo").addEventListener("click", function() {
    abrirModal("modalNuevo");
});

document.querySelectorAll(".btnEditar").forEach(button => {
    button.addEventListener("click", function(event) {
        // Almacena la fila seleccionada y sus datos
        filaSeleccionada = event.target.closest("tr");
        cargarDatosEnModalEditar();
        abrirModal("modalEditar");
    });
});

function abrirModal(id) {
    document.getElementById(id).style.display = "block";
}

function cerrarModal(id) {
    document.getElementById(id).style.display = "none";
}

// Cargar datos de la fila seleccionada en el modal de edición
function cargarDatosEnModalEditar() {
    document.getElementById("nombreEditar").value = filaSeleccionada.cells[1].innerText;
    document.getElementById("apellidoPEditar").value = filaSeleccionada.cells[2].innerText;
    document.getElementById("apellidoMEditar").value = filaSeleccionada.cells[3].innerText;
    document.getElementById("sexoEditar").value = filaSeleccionada.cells[4].innerText;
    document.getElementById("telefonoEditar").value = filaSeleccionada.cells[5].innerText;
}

// Guardar un nuevo miembro
function guardarNuevo() {
    const nombre = document.getElementById("nombreNuevo").value;
    const apellidoP = document.getElementById("apellidoPNuevo").value;
    const apellidoM = document.getElementById("apellidoMNuevo").value;
    const sexo = document.getElementById("sexoNuevo").value;
    const telefono = document.getElementById("telefonoNuevo").value;

    const tabla = document.querySelector("table tbody");
    const nuevaFila = tabla.insertRow();
    nuevaFila.innerHTML = `
        <td></td>
        <td>${nombre}</td>
        <td>${apellidoP}</td>
        <td>${apellidoM}</td>
        <td>${sexo}</td>
        <td>${telefono}</td>
        <td><button class="btnEditar">✏️ Editar</button></td>
    `;

    // Añadir evento de edición a la nueva fila
    nuevaFila.querySelector(".btnEditar").addEventListener("click", function(event) {
        filaSeleccionada = event.target.closest("tr");
        cargarDatosEnModalEditar();
        abrirModal("modalEditar");
    });

    cerrarModal("modalNuevo");
    limpiarFormularioNuevo();
}

// Guardar los cambios del miembro editado
function guardarEdicion() {
    filaSeleccionada.cells[1].innerText = document.getElementById("nombreEditar").value;
    filaSeleccionada.cells[2].innerText = document.getElementById("apellidoPEditar").value;
    filaSeleccionada.cells[3].innerText = document.getElementById("apellidoMEditar").value;
    filaSeleccionada.cells[4].innerText = document.getElementById("sexoEditar").value;
    filaSeleccionada.cells[5].innerText = document.getElementById("telefonoEditar").value;

    alert("Cambios guardados");
    cerrarModal("modalEditar");
}

// Limpiar los campos del formulario para agregar un nuevo miembro
function limpiarFormularioNuevo() {
    document.getElementById("nombreNuevo").value = "";
    document.getElementById("apellidoPNuevo").value = "";
    document.getElementById("apellidoMNuevo").value = "";
    document.getElementById("sexoNuevo").value = "";
    document.getElementById("telefonoNuevo").value = "";
}
