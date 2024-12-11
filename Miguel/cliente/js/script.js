document.addEventListener("DOMContentLoaded", function () {
    const insertForm = document.getElementById('insertForm');
    if (insertForm) {
        insertForm.addEventListener('submit', function (event) {
            event.preventDefault();

            const formData = new FormData(this);

            fetch('insertar_producto.php', { 
                method: 'POST',
                body: formData
            })
                .then(response => response.json())
                .then(data => {
                    console.log(data);
                    if (data.success) {
                        Swal.fire({
                            position: "center",
                            icon: "success",
                            title: "Producto guardado con éxito",
                            showConfirmButton: false,
                            timer: 5000
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: "error",
                            title: "Oops...",
                            text: "Algo salió mal al guardar el producto.",
                            footer: data.message ? `<a href="#">${data.message}</a>` : ''
                        });
                    }

                })
                .catch(error => {
                    console.error("Error:", error);
                    Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        text: "Hubo un error al procesar la solicitud.",
                        footer: '<a href="#">¿Por qué ocurre este problema?</a>'
                    });
                });
        });
    } else {
        console.error("El formulario de inserción no se encontró.");
    }
});


//editar
document.addEventListener("DOMContentLoaded", function () {
    const editButtons = document.querySelectorAll(".edit-btn");
    const editForm = document.getElementById("editForm");

    editButtons.forEach((editBtn) => {
        editBtn.addEventListener("click", function () {
            document.getElementById("editModal").style.display = "block";

            editForm.dataset.productId = editBtn.getAttribute("data-id");
            document.getElementById("editNombre").value = editBtn.getAttribute("data-nombre");
            document.getElementById("editCantidad").value = editBtn.getAttribute("data-cantidad");
            document.getElementById("editPrecio").value = editBtn.getAttribute("data-precio");
            document.getElementById("editLote").value = editBtn.getAttribute("data-lote");
            document.getElementById("editFechaIngreso").value = editBtn.getAttribute("data-fecha");
            document.getElementById("editCategoria").value = editBtn.getAttribute("data-categoria");
        });
    });

    editForm.addEventListener("submit", function (event) {
        event.preventDefault();
        const formData = new FormData(editForm);
        formData.append("id", editForm.dataset.productId);

        fetch("actualizar_producto.php", {
            method: "POST",
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: "success",
                        title: "Éxito",
                        text: data.message
                    }).then(() => {
                        document.getElementById("editModal").style.display = "none";
                        window.location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Error",
                        text: data.message
                    });
                }
            })
            .catch(error => {
                Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: "Hubo un problema al actualizar el producto."
                });
                console.error("Error:", error);
            });
    });
});

//borrar
document.addEventListener("DOMContentLoaded", function () {
    const deleteButtons = document.querySelectorAll('.delete-btn');

    deleteButtons.forEach(button => {
        button.addEventListener('click', function () {
            const productId = this.getAttribute('data-id');

            Swal.fire({
                title: '¿Estás seguro?',
                text: "Este producto se eliminará permanentemente.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, eliminarlo',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`inventario.php?delete_id=${productId}`)
                        .then(response => response.text())
                        .then(data => {
                            if (data.includes('Producto eliminado correctamente')) {
                                Swal.fire(
                                    'Eliminado',
                                    'El producto ha sido eliminado.',
                                    'success'
                                ).then(() => {
                                    location.reload(); 
                                });
                            } else {
                                Swal.fire(
                                    'Error',
                                    'Hubo un error al eliminar el producto.',
                                    'error'
                                );
                            }
                        });
                }
            });
        });
    });
});


//vender
let selectedProduct = {
    name: '',
    image: '',
    price: 0,
    quantity: 1
};

function selectProduct(imageName, productName, productPrice) {
    const selectedProductDetails = document.getElementById('selectedProductDetails');
    selectedProductDetails.innerHTML = `
        <div class="col-6">
            <h3>${productName}</h3>
            <p>Precio: $${productPrice}</p>
            <div>
                <button onclick="updateQuantity('decrease')">-</button>
                <span id="productQuantity">${selectedProduct.quantity}</span>
                <button onclick="updateQuantity('increase')">+</button>
            </div>
        </div>
        <div class="col-6 text-end">
            <img src="../servidor/img_inventario/${imageName}" alt="Imagen del producto" style="width: 200px; height: auto;">
        </div>
    `;

    selectedProduct = {
        name: productName,
        image: imageName,
        price: productPrice,
        quantity: 1 
    };
}

function updateQuantity(action) {
    const quantityDisplay = document.getElementById('productQuantity');

    if (action === 'decrease' && selectedProduct.quantity > 1) {
        selectedProduct.quantity--;
    } else if (action === 'increase') {
        selectedProduct.quantity++;
    }

    // Actualizar la cantidad mostrada
    quantityDisplay.innerText = selectedProduct.quantity;
}

function refreshVentasDelDia() {
    fetch("../servidor/obtenerVentasDia.php")
        .then(response => response.text())
        .then(data => {
            document.getElementById('ventasDelDia').innerHTML = data;
        });
}

function confirmPurchase() {
    const productDetails = selectedProduct;

    // Obtener la fecha y hora actual
    const date = new Date();
    const dateString = date.toISOString().split('T')[0]; 
    const timeString = date.toLocaleTimeString(); 

    fetch("../servidor/confirmarVenta.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify({
            name: productDetails.name,
            image: productDetails.image,
            price: productDetails.price,
            quantity: productDetails.quantity,
            date: dateString,
            time: timeString
        })
    })
        .then(response => response.json())
        .then(data => {
            if (data.status === "success") {
                alert("Compra confirmada y registrada");
                refreshVentasDelDia();
            } else {
                alert("Error: " + data.message);
            }
        })
        .catch(error => {
            console.error("Error al confirmar la compra:", error);
        });


    window.location.reload();

}
