<?php
include("../servidor/conexion.php");

$categoryFilter = isset($_GET['category']) ? $_GET['category'] : '';
$nameFilter = isset($_GET['name']) ? $_GET['name'] : '';
$dateFilter = isset($_GET['date']) ? $_GET['date'] : '';

$query = "SELECT inventario.ID_Producto, inventario.Nombre, inventario.Cantidad, inventario.Precio, 
          inventario.Lote, inventario.FechaIngreso, inventario.Foto, categoriaprod.ID_Categorial, 
          categoriaprod.Descripcion AS Categoria
          FROM inventario
          LEFT JOIN categoriaprod ON inventario.ID_Categorial = categoriaprod.ID_Categorial
          WHERE 1=1";

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


if (isset($_GET['delete_id']) && is_numeric($_GET['delete_id'])) {
    $deleteId = $_GET['delete_id'];

    $deleteQuery = "DELETE FROM inventario WHERE ID_Producto = ?";
    $stmt = mysqli_prepare($conexion, $deleteQuery);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $deleteId);
        if (mysqli_stmt_execute($stmt)) {
            echo "Producto eliminado correctamente";
        } else {
            echo "Error al eliminar el producto: " . mysqli_error($conexion);
        }
        mysqli_stmt_close($stmt);
    } else {
        echo "Error al preparar la consulta: " . mysqli_error($conexion);
    }

    exit;
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
    <title>Inventario - Dragon GYM</title>
    <link rel="stylesheet" href="css/inventario.css">

</head>

<body>
    <div class="container-fluid">
        <div class="navigation">
            <?php include_once("include/encabezado.php") ?>
        </div>

        <div class="main">
            <div class="topbar">
                <div class="toggle">
                    <ion-icon name="menu-outline"></ion-icon>
                </div>
                <div class="subMenu">
                    <div class="gastos">
                        <div class="iconoGa">
                            <ion-icon name="wallet-outline"></ion-icon>
                        </div>
                        <div class="enlace">
                            <a href="Gastos.php">Gastos</a>
                        </div>
                    </div>
                    <div class="inventario">
                        <div class="iconoIn">
                            <ion-icon name="archive-outline"></ion-icon>
                        </div>
                        <div class="enlace">
                            <a href="inventario.php">Inventario</a>
                        </div>
                    </div>
                    <div class="adminUsuarios">
                        <div class="iconoAd">
                            <ion-icon name="people-outline"></ion-icon>
                        </div>
                        <div class="enlace">
                            <a href="ControlPersonal.php">Usuarios</a>
                        </div>
                    </div>
                    <div class="reportes">
                        <div class="iconoRe">
                            <ion-icon name="document-attach-outline"></ion-icon>
                        </div>
                        <div class="enlace">
                            <a href="Reportes.php">Reportes</a>
                        </div>
                    </div>
                </div>
                <div class="contenedor">
                    <div class="usuario">
                        <img src="https://i.pinimg.com/originals/a0/14/7a/a0147adf0a983ab87e86626f774785cf.gif" alt="">
                    </div>
                </div>
            </div>

            <!-- Filtros -->
            <div class="filter-container">
                <div class="conteidoForm">
                    <form method="GET" action="inventario.php">
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

                        <input type="text" name="name" id="nameFilter" placeholder="Filtrar por nombre"
                            value="<?php echo $nameFilter; ?>">

                        <input type="date" name="date" id="dateFilter" value="<?php echo $dateFilter; ?>">

                        <button type="submit" id="filterBtn">Filtrar</button>

                        <button type="reset" id="resetBtn"
                            onclick="window.location.href='inventario.php';">Limpiar</button>
                    </form>
                </div>

            </div>

            <button class="insert-btn" onclick="document.getElementById('insertModal').style.display='block'">Insertar
                nuevo producto</button>

            <div class="tabla-container">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Cantidad</th>
                            <th>Precio</th>
                            <th>Lote</th>
                            <th>Fecha de Ingreso</th>
                            <th>Foto</th>
                            <th>Categoría</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (mysqli_num_rows($resultado) > 0) {
                            while ($fila = mysqli_fetch_assoc($resultado)) {
                                echo "<tr>";
                                echo "<td>{$fila['ID_Producto']}</td>";
                                echo "<td>{$fila['Nombre']}</td>";
                                echo "<td>{$fila['Cantidad']}</td>";
                                echo "<td>{$fila['Precio']}</td>";
                                echo "<td>{$fila['Lote']}</td>";
                                echo "<td>{$fila['FechaIngreso']}</td>";
                                echo "<td><img src='../servidor/img_inventario/{$fila['Foto']}' alt='Foto de producto' width=80 class='product-image'></td>";
                                echo "<td>{$fila['Categoria']}</td>";
                                echo "<td>
                            <button class='edit-btn' 
                                    data-id='{$fila['ID_Producto']}'
                                    data-nombre='{$fila['Nombre']}'
                                    data-cantidad='{$fila['Cantidad']}'
                                    data-precio='{$fila['Precio']}'
                                    data-lote='{$fila['Lote']}'
                                    data-fecha='{$fila['FechaIngreso']}'
                                    data-foto='{$fila['Foto']}'
                                    data-categoria='{$fila['ID_Categorial']}'>
                                Editar
                            </button>
                            <button class='delete-btn' data-id='{$fila['ID_Producto']}'>Borrar</button>

                        </td>";


                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='8'>No se encontraron productos con los filtros seleccionados.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>


            <!-- Modal para insertar nuevo producto -->
            <div id="insertModal" class="modal">
                <div class="modal-content">
                    <span onclick="document.getElementById('insertModal').style.display='none'"
                        class="close-btn">&times;</span>
                    <h2>Insertar nuevo producto</h2>
                    <form id="insertForm" enctype="multipart/form-data">
                        <label for="nombre">Nombre:</label>
                        <input type="text" id="nombre" name="nombre" required>

                        <label for="cantidad">Cantidad:</label>
                        <input type="number" id="cantidad" name="cantidad" required>

                        <label for="precio">Precio:</label>
                        <input type="number" id="precio" name="precio" required>

                        <label for="lote">Lote:</label>
                        <input type="text" id="lote" name="lote" required>

                        <label for="fechaIngreso">Fecha de Ingreso:</label>
                        <input type="date" id="fechaIngreso" name="fechaIngreso" required>

                        <label for="foto">Foto:</label>
                        <input type="file" id="foto" name="foto" accept="image/*" required>

                        <label for="categoria">Categoría:</label>
                        <select id="categoria" name="categoria" required>
                            <?php
                            $cat_query = "SELECT ID_Categorial, Descripcion FROM categoriaprod";
                            $cat_resultado = mysqli_query($conexion, $cat_query);
                            while ($cat_fila = mysqli_fetch_assoc($cat_resultado)) {
                                echo "<option value='{$cat_fila['ID_Categorial']}'>{$cat_fila['Descripcion']}</option>";
                            }
                            ?>
                        </select>

                        <button type="submit" class="submit-btn">Guardar Producto</button>
                    </form>
                </div>
            </div>

            <!-- Modal para editar producto -->
            <div id="editModal" class="modal">
                <div class="modal-content">
                    <span onclick="document.getElementById('editModal').style.display='none'"
                        class="close-btn">&times;</span>
                    <h2>Editar producto</h2>
                    <form id="editForm">
                        <input type="hidden" id="editProductId" name="id">

                        <label for="editNombre">Nombre:</label>
                        <input type="text" id="editNombre" name="nombre" required>

                        <label for="editCantidad">Cantidad:</label>
                        <input type="number" id="editCantidad" name="cantidad" required>

                        <label for="editPrecio">Precio:</label>
                        <input type="number" id="editPrecio" name="precio" required>

                        <label for="editLote">Lote:</label>
                        <input type="text" id="editLote" name="lote" required>

                        <label for="editFechaIngreso">Fecha de Ingreso:</label>
                        <input type="date" id="editFechaIngreso" name="fechaIngreso" required>

                        <label for="editFoto">Foto:</label>
                        <input type="file" id="editFoto" name="foto" accept="image/*">

                        <label for="editCategoria">Categoría:</label>
                        <select id="editCategoria" name="categoria" required>
                            <?php
                            // Obtener las categorías desde la base de datos
                            $cat_query = "SELECT ID_Categorial, Descripcion FROM categoriaprod";
                            $cat_resultado = mysqli_query($conexion, $cat_query);
                            while ($cat_fila = mysqli_fetch_assoc($cat_resultado)) {
                                echo "<option value='{$cat_fila['ID_Categorial']}'>{$cat_fila['Descripcion']}</option>";
                            }
                            ?>
                        </select>

                        <button type="submit" class="submit-btn">Guardar Cambios</button>
                    </form>
                </div>
            </div>

        </div>


    </div>


    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="js/inventario.js"></script>
</body>

</html>