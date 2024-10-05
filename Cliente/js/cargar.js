// Escuchar el evento de click en los botones de edición
document.querySelectorAll('.editBtn').forEach(button => {
    button.addEventListener('click', function() {
        // Obtener los datos del botón presionado
        const id = this.getAttribute('data-id');
        const nombre = this.getAttribute('data-nombre');
        const correo = this.getAttribute('data-correo');

        // Pasar los datos al formulario dentro del modal
        document.getElementById('edit-id').value = id;
        document.getElementById('edit-nombre').value = nombre;
        document.getElementById('edit-correo').value = correo;
    });
});
