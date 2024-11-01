<?php
include_once("../servidor/conexion.php");
$categoria = isset($_GET['categoria']) ? $_GET['categoria'] : '';
$search = isset($_GET['search']) ? $_GET['search'] : '';


$query = "SELECT ID_Membresia, Nombre, ApellidoP, ApellidoM, Sexo, Categoria, FechaInicio, FechaFin, Telefono, MesesT, MesesC FROM miembros";
$preciosQuery = "SELECT Tipo, Precio FROM preciossubs";
$preciosResult = mysqli_query($conexion, $preciosQuery);

$precios = [];
while ($row = mysqli_fetch_assoc($preciosResult)) {
    $precios[$row['Tipo']] = $row['Precio'];
}

if ($categoria != '') {
    $query .= " WHERE Categoria = '$categoria'";
}


if ($search != '') {
    if (strpos($query, 'WHERE') !== false) {
        $query .= " AND Nombre LIKE '%$search%'";
    } else {
        $query .= " WHERE Nombre LIKE '%$search%'";
    }
}


$result = mysqli_query($conexion, $query);


if (!empty($_POST)) {
    if (empty($_POST['nombre']) || empty($_POST['apellido_p']) || empty($_POST['apellido_m']) || empty($_POST['sexo'])  || empty($_POST['telefono'])) {
        $alert = '<div class="alert alert-danger d-flex align-items-center" role="alert">
                  <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Warning:"><use xlink:href="#exclamation-triangle-fill"/></svg>
                  <div>Todos los campos son obligatorios</div>
                  </div>';
    } else {
        $nombre = $_POST['nombre'];
        $apellidoP = $_POST['apellido_p'];
        $apellidoM = $_POST['apellido_m'];
        $sexo = $_POST['sexo'];
      
        $telefono = $_POST['telefono'];
       
 
        $consulta  = "INSERT INTO miembros (Nombre, ApellidoP, ApellidoM, Sexo, Telefono)
                  VALUES ('$nombre', '$apellidoP', '$apellidoM', '$sexo', '$telefono')";
        
        $result = mysqli_query($conexion, $consulta);
        if ($consulta) {
            $alert = '<div class="alert alert-success d-flex align-items-center" role="alert">
                      <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Warning:"><use xlink:href="#exclamation-triangle-fill"/></svg>
                      <div>Miembro guardado correctamente</div>
                      </div>';
        } else {
            $alert = '<div class="alert alert-danger d-flex align-items-center" role="alert">
                      <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Warning:"><use xlink:href="#exclamation-triangle-fill"/></svg>
                      <div>Error al guardar miembro</div>
                      </div>';
        }
    }
}
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
    <title>Miembros</title>
    <link rel="stylesheet" href="css/miembros.css">
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
            <div class="search">
                <form method="GET" action="">
                    <label>
                        <input type="text" class="input-control" placeholder="Buscar por nombre" name="search" value="<?php echo $search; ?>">
                        <ion-icon name="search-outline"></ion-icon>
                    </label>
                </form>
            </div>
            <div class="contenedor">
                <div class="usuario">
                    <img src="https://i.pinimg.com/originals/a0/14/7a/a0147adf0a983ab87e86626f774785cf.gif" alt="">
                </div>
            </div>
        </div>

        <div class="header-actions">
            <h2>Miembros</h2>
            <button type="button" class="btn-primary" onclick="openModal('exampleModal')">
                <img src="imgs/add.png" height="16px" width="16px"> Nuevo Miembro
            </button>
        </div>

        <?php if (isset($alert)) echo $alert; ?>


        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID Membresía</th>
                        <th>Nombre</th>
                        <th>Apellido Paterno</th>
                        <th>Apellido Materno</th>
                        <th>Sexo</th>
                        <th>Categoria</th>
                        <th>Fecha Inicio</th>
                        <th>Fecha Fin</th>
                        <th>Teléfono</th>
                        <th>Meses Totales</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $con = mysqli_query($conexion, $query);
                    $fecha_actual = date('Y-m-d'); 

                    while ($datos = mysqli_fetch_assoc($con)) {
                        $clase_fila = ($datos['FechaFin'] < $fecha_actual) ? 'vencido' : 'vigente';
                    ?>
                        <tr class="<?php echo $clase_fila; ?>">
                            <td><?php echo $datos['ID_Membresia']; ?></td>
                            <td><?php echo $datos['Nombre']; ?></td>
                            <td><?php echo $datos['ApellidoP']; ?></td>
                            <td><?php echo $datos['ApellidoM']; ?></td>
                            <td><?php echo $datos['Sexo']; ?></td>
                            <td><?php echo $datos['Categoria']; ?></td>
                            <td><?php echo $datos['FechaInicio']; ?></td>
                            <td><?php echo $datos['FechaFin']; ?></td>
                            <td><?php echo $datos['Telefono']; ?></td>
                            <td><?php echo $datos['MesesT']; ?></td>
                            <td>
                                <button type="button" class="btn-dark" 
                                        onclick="openModal('exampleModaledit')" 
                                        data-id="<?php echo $datos['ID_Membresia']; ?>" 
                                        data-nombre="<?php echo $datos['Nombre']; ?>" 
                                        data-apellido-p="<?php echo $datos['ApellidoP']; ?>" 
                                        data-apellido-m="<?php echo $datos['ApellidoM']; ?>" 
                                        data-sexo="<?php echo $datos['Sexo']; ?>" 
                                        data-telefono="<?php echo $datos['Telefono']; ?>">
                                    <img src="imgs/lapiz.png" height="16px" width="16px">
                                </button>
                                <a href="../servidor/borrar_miembro.php?id=<?php echo $datos['ID_Membresia']; ?>">
                                    <button type="button" class="btn-danger">
                                        <img src="imgs/cruz.png" height="16px" width="16px">
                                    </button>
                                </a>
                                <button type="button" class="btn-warning" 
                                        onclick="openModal('renovarModal')" 
                                        data-id="<?php echo $datos['ID_Membresia']; ?>" 
                                        data-fecha-inicio="<?php echo $datos['FechaInicio']; ?>">
                                    <img src="imgs/renovar.png" height="16px" width="16px">
                                </button>
                            </td>

                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>

        <!-- Modal Renovar Membresía -->
        <dialog id="renovarModal">
            <div class="modal-content">
                <div class="cierre">
                <h2>Renovar Membresía</h2>
                    <span class="close" onclick="cerrarModal('renovarModal')">×</span>
                </div>
                
                <form method="POST" action="../servidor/renovar_membresia.php">
                    <input type="hidden" name="id_membresia" id="id_membresia_renovar">
                    <div>
                        <label>Fecha de Inicio</label>
                        <input type="date" id="fecha_inicio" name="fecha_inicio" required>
                    </div>
                    <br>
                    <div>
                        <label>Duración</label>
                        <select id="tipoMembresia" name="tipoMembresia" onchange="calcularTotal()" required>
                            <option value="Semana">Semana</option>
                            <option value="Mes">Mes</option>
                        </select>
                        <input type="number" id="cantidad" name="cantidad" placeholder="Número" oninput="calcularTotal()" required>
                    </div>
                    <div class="input-group">
                        <input type="date" id="fecha_fin" name="fecha_fin" readonly required>
                        <label>Fecha de Fin</label>
                    </div>
                    <div class="input-group">
                        <input type="text" id="total" name="total" readonly>
                        <label>Total:</label>
                    </div>
                    <div class="input-group">
                        <button type="button" onclick="cerrarModal('renovarModal')">Cerrar</button>
                        <button type="submit">Renovar</button>
                    </div>
                </form>
            </div>
        </dialog>

        <!-- Modal Agregar Miembro -->
        <dialog id="exampleModal">
            <div class="modal-content">
                <div class="cierre">
                    <h2>Registro de Miembro</h2>
                    <span class="close" onclick="cerrarModal('exampleModal')">×</span>
                </div>
                
                <form method="POST" id="memberForm">
                    <div class="input-group">
                        <input type="text" name="nombre" required>
                        <label>Nombre</label>
                    </div>
                    <div class="input-group">
                        <input type="text" name="apellido_p" required>
                        <label>Apellido Paterno</label>
                    </div>
                    <div class="input-group">
                        <input type="text" name="apellido_m" required>
                        <label>Apellido Materno</label>
                    </div>
                    <div class="">
                        <label>Sexo</label>
                        <select name="sexo" required>
                            <option value="Masculino">Masculino</option>
                            <option value="Femenino">Femenino</option>
                        </select>
                    </div>
                    <div class="input-group">
                        <input type="text" name="telefono" required>
                        <label>Teléfono</label>
                    </div>
                    <button type="submit">Agregar Miembro</button>
                </form>
            </div>
        </dialog>

        <!-- Modal Editar Miembro -->
        <dialog id="exampleModaledit">
            <div class="modal-content">
                <div class="cierre">
                    <h2>Editar Miembro</h2>
                    <span class="close" onclick="cerrarModal('exampleModaledit')">×</span>
                </div>
                
                <form method="POST" action="../servidor/editar_miembro.php">
                    <input type="hidden" name="id_membresia" id="id_membresia_edit">
                    <div class="input-group">
                        <input type="text" id="nombre_edit" name="nombre" required>
                        <label>Nombre</label>
                    </div>
                    <div class="input-group">
                        <input type="text" id="apellido_p_edit" name="apellido_p" required>
                        <label>Apellido Paterno</label>
                    </div>
                    <div class="input-group">
                        <input type="text" id="apellido_m_edit" name="apellido_m" required>
                        <label>Apellido Materno</label>
                    </div>
                    <div class="">
                        <label>Sexo</label>
                        <select id="sexo_edit" name="sexo" required>
                            <option value="Masculino">Masculino</option>
                            <option value="Femenino">Femenino</option>
                        </select>
                    </div>
                    <div class="input-group">
                        <input type="text" id="telefono_edit" name="telefono" required>
                        <label>Teléfono</label>
                    </div>
                    <button type="submit">Actualizar Miembro</button>
                </form>
            </div>
        </dialog>


    </div>
</div>

<script src="js/miembros.js"></script>
</body>
</html>
