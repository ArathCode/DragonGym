<?php
include_once("../servidor/conexion.php");

// Obtener los valores de los filtros
$categoryFilter = isset($_GET['category']) ? $_GET['category'] : '';
$nameFilter = isset($_GET['name']) ? $_GET['name'] : '';
$dateFilter = isset($_GET['date']) ? $_GET['date'] : '';

$query = "SELECT inventario.ID_Producto, inventario.Nombre, inventario.Cantidad, inventario.Precio, 
          inventario.Lote, inventario.FechaIngreso, inventario.Foto, categoriaprod.ID_Categorial, 
          categoriaprod.Descripcion AS Categoria
          FROM inventario
          LEFT JOIN categoriaprod ON inventario.ID_Categorial = categoriaprod.ID_Categorial
          WHERE 1=1";

// Aplicar los filtros a la consulta
if ($categoryFilter) {
    $query .= " AND inventario.ID_Categorial = '$categoryFilter'";
}
if ($nameFilter) {
    $query .= " AND inventario.Nombre LIKE '%$nameFilter%'";
}
if ($dateFilter) {
    $query .= " AND inventario.FechaIngreso = '$dateFilter'";
}



$resultado = mysqli_query($conexion, $query);

$fechaHoy = date('Y-m-d');
$ventasQuery = "SELECT detalleventa.Cantidad, detalleventa.Subtotal, inventario.Nombre 
                FROM detalleventa
                INNER JOIN ventas ON detalleventa.ID_Venta = ventas.ID_Venta
                INNER JOIN inventario ON detalleventa.ID_Producto = inventario.ID_Producto
                WHERE ventas.Fecha = '$fechaHoy'";

$ventasResultado = mysqli_query($conexion, $ventasQuery);

?>


<html>

<head>
    <link href="css/style.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
    <div class="container" style="border: solid 5px red;">
        <div class="row" style="border: solid 5px blue;">
            <div class="col-4">
                <img src="../cliente/img/logo.jpeg" width="150px">
            </div>
            <div class="col-8">
                <h2>DRAGON GYM VENTAS</h2>
            </div>
        </div>

        <div class="row" style="border: solid 5px black;">
            <div class="col" style="border: solid 5px orange;">
                <!-- Filtros -->
                <div class="row-4">
                    <div class="">
                        <form method="GET" action="ventas.php">
                            <select name="category" id="categoryFilter">
                                <option value="">Seleccionar categoría</option>
                                <?php
                                $categoryQuery = "SELECT * FROM categoriaprod";
                                $categoryResult = mysqli_query($conexion, $categoryQuery);

                                while ($category = mysqli_fetch_assoc($categoryResult)) {
                                    $selected = ($category['ID_Categorial'] == $categoryFilter) ? 'selected' : '';
                                    echo "<option value='{$category['ID_Categorial']}' $selected>{$category['Descripcion']}</option>";
                                }
                                ?>
                            </select>

                            <input type="text" name="name" id="nameFilter" placeholder="Filtrar por nombre" value="<?php echo $nameFilter; ?>">

                            <button type="submit" id="filterBtn">Filtrar</button>

                            <button type="reset" id="resetBtn" onclick="window.location.href='ventas.php';">Resetear filtros</button>
                        </form>
                    </div>
                </div>
                            
                <!-- Productos -->
                <div class="row-8" style="min-height:500px; overflow-y:auto; overflow-x:hidden; max-height:540px">
                    <table id="productTable" class="table">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Cantidad</th>
                                <th>Precio</th>
                                <th>Imagen</th>
                                <th>Categoría</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (mysqli_num_rows($resultado) > 0) {
                                while ($fila = mysqli_fetch_assoc($resultado)) {
                                    echo "<tr data-id='{$fila['ID_Producto']}' data-nombre='{$fila['Nombre']}' data-precio='{$fila['Precio']}' data-foto='../servidor/img_inventario/{$fila['Foto']}'>";
                                    echo "<td>{$fila['Nombre']}</td>";
                                    echo "<td>{$fila['Cantidad']}</td>";
                                    echo "<td>{$fila['Precio']}</td>";
                                    echo "<td><img src='../servidor/img_inventario/{$fila['Foto']}' alt='Foto de producto' width=50></td>";
                                    echo "<td>{$fila['Categoria']}</td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='5'>No se encontraron productos con los filtros seleccionados.</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>


            
            <div class="col" style="border: solid 5px grey;">
                <!-- Producto seleccionado -->
                <div id="selectedProductDetails" class="row" style="border: solid 5px black;">
                    <h2>Producto Seleccionado</h2>
                    <div id="productInfo">
                        <p>Nombre: <span id="productName"></span></p>
                        <p>Precio: <span id="productPrice"></span></p>
                        <img id="productImage" src="" width="100" style="display:none;">
                        <div>
                            <label for="quantity">Cantidad:</label>
                            <input type="number" id="quantity" value="1" min="1">
                        </div>
                        <button id="confirmPurchase" class="btn btn-primary">Confirmar Compra</button>
                    </div>
                </div>
                
                <!-- Ventas del dia -->
                <div id="ventasDelDia" class="row" style="border: solid 5px black;">
                    <h2>Ventas del Día</h2>
                    <table>
                        <thead>
                            <tr>
                                <th>Nombre Producto</th>
                                <th>Cantidad Vendida</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (mysqli_num_rows($ventasResultado) > 0) {
                                while ($venta = mysqli_fetch_assoc($ventasResultado)) {
                                    echo "<tr>";
                                    echo "<td>{$venta['Nombre']}</td>";
                                    echo "<td>{$venta['Cantidad']}</td>";
                                    echo "<td>{$venta['Subtotal']}</td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='3'>No se registraron ventas el día de hoy.</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('#productTable tr').click(function() {
                const id = $(this).data('id');
                const nombre = $(this).data('nombre');
                const precio = $(this).data('precio');
                const foto = $(this).data('foto');

                $('#productName').text(nombre);
                $('#productPrice').text(precio);
                $('#productImage').attr('src', foto).show();
                $('#confirmPurchase').data('id', id);
            });

            $('#confirmPurchase').click(function() {
                const id = $(this).data('id');
                const cantidad = $('#quantity').val();

                $.ajax({
                    url: 'procesar_compra.php',
                    type: 'POST',
                    data: {
                        id,
                        cantidad
                    },
                    success: function(response) {
                        alert(response);
                        window.location.reload(); 
                    }
                });
            });
        });
    </script>
</body>
</html>