<?php
include_once("../servidor/conexion.php");
$search = isset($_GET['search']) ? $_GET['search'] : '';
$alert = ""; 
$filterQuery = "";


if (isset($_POST['action']) && $_POST['action'] == 'filter') {
    
    if (!empty($_POST['startDate']) && !empty($_POST['endDate'])) {
        $startDate = mysqli_real_escape_string($conexion, $_POST['startDate']);
        $endDate = mysqli_real_escape_string($conexion, $_POST['endDate']);
        $filterQuery = " ";
        $query = "SELECT u.ID_Gasto, u.Descripcion, u.Precio, u.Fecha, u.ID_Usuario, t.NombreUsuario 
              FROM gastos u 
              INNER JOIN usurio t ON u.ID_Usuario = t.ID_Usuario WHERE u.Fecha >= '$startDate' AND u.Fecha <= '$endDate' ";
    } 

    
    $con = mysqli_query($conexion, $query);
    if (!$con) {
        die('Error en la consulta: ' . mysqli_error($conexion));
    }

} elseif (isset($_POST['action']) && $_POST['action'] == 'insert') {
 
    if (!empty($_POST['cam1']) && !empty($_POST['cam2']) && !empty($_POST['cam3']) && !empty($_POST['cam4'])) {
        $c1 = mysqli_real_escape_string($conexion, $_POST['cam1']);
        $c2 = mysqli_real_escape_string($conexion, $_POST['cam2']);
        $c3 = mysqli_real_escape_string($conexion, $_POST['cam3']);
        $c4 = mysqli_real_escape_string($conexion, $_POST['cam4']);
    
        $consulta = "INSERT INTO gastos (ID_Gasto, Descripcion, Precio, Fecha, ID_Usuario) 
                     VALUES (NULL, '$c1', '$c2', '$c3', '$c4')";
        $query = "SELECT u.ID_Gasto, u.Descripcion, u.Precio, u.Fecha, u.ID_Usuario, t.NombreUsuario 
        FROM gastos u 
        INNER JOIN usuario t ON u.ID_Usuario = t.ID_Usuario";
        $resultado = mysqli_query($conexion, $consulta);
        $con = mysqli_query($conexion, $query);
    
        if ($resultado) {
            $alert = '<div class="alert alert-success d-flex align-items-center" role="alert">
                      <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Success:">
                      <use xlink:href="#check-circle-fill"/></svg>
                      <div>Datos guardados</div>
                      </div>';
        } else {
            $alert = '<div class="alert alert-danger d-flex align-items-center" role="alert">
                      <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Error:">
                      <use xlink:href="#exclamation-triangle-fill"/></svg>
                      <div>Error al guardar: ' . mysqli_error($conexion) . '</div>
                      </div>';
        }
    } else {
       
        $alert = '<div class="alert alert-danger d-flex align-items-center" role="alert">
                  <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Warning:">
                  <use xlink:href="#exclamation-triangle-fill"/></svg>
                  <div>Todos los campos son obligatorios</div>
                  </div>';
    }


} else {
    $query = "SELECT u.ID_Gasto, u.Descripcion, u.Precio, u.Fecha, u.ID_Usuario, t.NombreUsuario 
              FROM gastos u 
              INNER JOIN usuario t ON u.ID_Usuario = t.ID_Usuario";


    $con = mysqli_query($conexion, $query);
    if (!$con) {
        die('Error en la consulta: ' . mysqli_error($conexion));
    }
}
?>
<!doctype html>
<html lang="es">

<head>
    <link rel="shortcut icon" href="imgs/logo.jpg" />
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
    <title>Administración de Gastos</title>
    <link rel="stylesheet" href="css/gastos.css">

</head>

<body>
    <div class="container" id="menu">
        <div class="navigation">
             <?php
               include_once ("include/encabezado.php")
            ?>     
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
                        <a href="#">Reportes</a>
                    </div>
                </div>
            </div>
            <div class="contenedor">
                <div class="usuario">
                    <img src="https://i.pinimg.com/originals/a0/14/7a/a0147adf0a983ab87e86626f774785cf.gif" alt="">
                </div>
            </div>
        </div>
        <div class="agregarGasto" >
        <h2>Administración de gastos</h2>
            <div class="gB">
                <button type="button" class="agregarGB" onclick="openModal('exampleModal')">
                    <img src="imgs/add.png" height="16px" width="16px">
                    Nuevo Gasto
                </button>
            </div>
        </div>
        <div class="filtrosG">
            <form method="POST" action="">
                <div class="filtrosI">
                    <input type="hidden" name="action" value="filter">
                    <div >
                        <label for="startDate">Desde:</label>
                        <input type="date" class="form-control" id="startDate" name="startDate">
                    </div>
                    <div >
                        <label for="endDate">Hasta:</label>
                        <input type="date" class="form-control" id="endDate" name="endDate">
                    </div>
                    <div >
                        <label>&nbsp;</label>
                        <button type="submit" class="btFiltrar">Filtrar</button>
                    </div>
                </div>
            </form>
        </div>
        
        <div class="table-container" >
            <?php echo isset($alert) ? $alert : ""; ?>
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Descripcion</th>
                        <th scope="col">Precio</th>
                        <th scope="col">Fecha</th>
                        <th scope="col">Personal</th>
                        <th scope="col">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($datos = mysqli_fetch_assoc($con)) { ?>
                    <tr>
                        <td>
                            <?php echo $datos['ID_Gasto']; ?>
                        </td>
                        <td>
                            <?php echo $datos['Descripcion']; ?>
                        </td>
                        <td>
                            <?php echo $datos['Precio']; ?>
                        </td>
                        <td>
                            <?php echo $datos['Fecha']; ?>
                        </td>
                        <td>
                            <?php echo $datos['NombreUsuario']; ?>
                        </td>
                        <td>
                            <button type="button" class="btn btn-dark editBtn" data-id="<?php echo $datos['ID_Gasto']; ?>"
                                    data-descripcion="<?php echo $datos['Descripcion']; ?>" data-precio="<?php echo $datos['Precio']; ?>"
                                    data-fecha="<?php echo $datos['Fecha']; ?>" data-usu="<?php echo $datos['ID_Usuario']; ?>"
                                    onclick="openModal('exampleModaledit')">
                                <img src="imgs/lapiz.png" height="16px" width="16px">
                            </button>
                            <!-- Botón para eliminar -->
                            <a href="../servidor/borrar_gasto.php?id=<?php echo $datos['ID_Gasto']; ?>">
                                <button type="button" class="btn btn-danger"><img src="imgs/cruz.png" height="16px"
                                        width="16px"></button>
                            </a>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    
        <!-- Modal Agregar -->
        <dialog id="exampleModal">
            <div class="modal-content">
                <div class="cierre">
                    <h2>Registro de gastos</h2>
                    <span class="close" onclick="cerrarModal('exampleModal')">×</span>
                </div>
                <div class="cont">
                    <form method="POST" action="">
                        <input type="hidden" name="action" value="insert">
                        <div class="contenidoIn">
                            <div class="input-group">
                                <input type="text" name="cam1" required>
                                <label>Descripción</label>
                            </div>
                            <div class="input-group">
                                <input type="number" name="cam2" required>
                                <label>Precio</label>
                            </div>
                            <div class="fechaG">
                                <p>Fecha</p>
                                <input type="date" name="cam3" required>
                            </div>
                            <div class="usuarioG">
                                <select name="cam4" required>
                                    <!-- Lista de usuarios -->
                                     <option value="">Usuario</option>
                                    <?php
                                    $cone = mysqli_query($conexion, "SELECT * FROM usuario");
                                    while($datos = mysqli_fetch_assoc($cone)) {
                                    ?>
                                    <option value="<?php echo $datos['ID_Usuario']; ?>">
                                        <?php echo $datos['NombreUsuario']; ?>
                                    </option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        
                        <button class="btnGuardar" type="submit">Guardar</button>
                        <button type="button" class="btnSalir" onclick="cerrarModal('exampleModal')">Cerrar</button>
                    </form>
                </div>
            </div>
        </dialog>

    
        <!-- Modal Editar -->
        <dialog id="exampleModaledit">
            <div class="modal-content">
                <div class="cierre">
                    <h2>Editar gasto</h2>
                    <span class="close" onclick="cerrarModal('exampleModaledit')">×</span>
                </div>
                <div class="cont">
                    <form method="POST" action="../servidor/editar_gasto.php">
                        <input type="hidden" id="edit-id" name="ID_Gasto">
                        <div class="input-group">
                            <input type="text" id="edit-nombre" name="cam1" required>
                            <label>Descripción</label>
                        </div>
                        <div class="input-group">
                            <input type="text" id="edit-apaterno" name="cam2" required>
                            <label>Precio</label>
                        </div>
                        <div class="input-group">
                            <input type="date" id="edit-amaterno" name="cam3" required>
                            <label>Fecha</label>
                        </div>
                        <div class="input-group">
                            <select id="edit-tipo" name="cam4" required>
                                <!-- Lista de usuarios -->
                                 <option value="">Usuario</option>
                                <?php
                                $cone = mysqli_query($conexion, "SELECT * FROM usuario");
                                while($datos = mysqli_fetch_assoc($cone)) {
                                ?>
                                <option value="<?php echo $datos['ID_Usuario']; ?>">
                                    <?php echo $datos['NombreUsuario']; ?>
                                </option>
                                <?php } ?>
                            </select>
                        </div>
                        <button class="btnGuardar" type="submit">Guardar cambios</button>
                        <button type="button" class="btnSalir" onclick="cerrarModal('exampleModaledit')">Cerrar</button>
                    </form>
                </div>
            </div>
        </dialog>
    
    <script src="js/gastos.js"></script>

</body>

</html>