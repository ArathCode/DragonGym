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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
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
                <label>
                    <input type="text" placeholder="Buscar miembro">
                    <ion-icon name="search-outline"></ion-icon>
                </label>
            </div>
            <div class="contenedor">
                <div class="usuario">
                    <img src="https://i.pinimg.com/originals/a0/14/7a/a0147adf0a983ab87e86626f774785cf.gif" alt="">
                </div>
            </div>
        </div>

        <div class="container-fluid">
            <div class="d-flex justify-content-between align-items-center">
                <h2>Miembros</h2>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
                    <img src="Imagenes/add.png" height="16px" width="16px"> Nuevo Miembro
                </button>
            </div>
        </div>

        <?php if (isset($alert)) echo $alert; ?>

        <div class="container-fluid">
            <form method="GET" action="">
                <div class="input-group mb-3">
                    <input type="text" class="form-control" placeholder="Buscar por nombre" name="search" value="<?php echo $search; ?>">
                    <button class="btn btn-outline-secondary" type="submit">Buscar</button>
                </div>
            </form>
        </div>

        <div class="container" style="text-align:center">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th scope="col">ID Membresía</th>
                        <th scope="col">Nombre</th>
                        <th scope="col">Apellido Paterno</th>
                        <th scope="col">Apellido Materno</th>
                        <th scope="col">Sexo</th>
                        <th scope="col">Categoria</th>
                        <th scope="col">Fecha Inicio</th>
                        <th scope="col">Fecha Fin</th>
                        <th scope="col">Teléfono</th>
                        <th scope="col">Meses Totales</th>
                        <th scope="col">Acciones</th>
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
                                <button type="button" class="btn btn-dark editBtn" data-id="<?php echo $datos['ID_Membresia']; ?>" 
                                        data-nombre="<?php echo $datos['Nombre']; ?>" 
                                        data-apellido-p="<?php echo $datos['ApellidoP']; ?>" 
                                        data-apellido-m="<?php echo $datos['ApellidoM']; ?>" 
                                        data-sexo="<?php echo $datos['Sexo']; ?>" 
                                        data-telefono="<?php echo $datos['Telefono']; ?>" 
                                        data-bs-toggle="modal" data-bs-target="#exampleModaledit">
                                    <img src="Imagenes/lapiz.png" height="16px" width="16px">
                                </button>
                        
                                <a href="../servidor/borrar_miembro.php?id=<?php echo $datos['ID_Membresia']; ?>">
                                    <button type="button" class="btn btn-danger">
                                        <img src="Imagenes/cruz.png" height="16px" width="16px">
                                    </button>
                                </a>
                                <button type="button" class="btn btn-warning renovarBtn" data-id="<?php echo $datos['ID_Membresia']; ?>"
                                        data-fecha-inicio="<?php echo $datos['FechaInicio']; ?>" 
                                        data-bs-toggle="modal" data-bs-target="#renovarModal">
                                    <img src="Imagenes/renovar.png" height="16px" width="16px">
                                </button>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>

        <!-- Modales (Renovar Membresía, Agregar Miembro, Editar Miembro) -->
        <div class="modal fade" id="renovarModal" tabindex="-1" aria-labelledby="renovarModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="renovarModalLabel">Renovar Membresía</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form method="POST" action="../servidor/renovar_membresia.php">
                        <div class="modal-body">
                            <input type="hidden" name="id_membresia" id="id_membresia_renovar">
                            <div class="input-group flex-nowrap">
                                <span class="input-group-text">Fecha de Inicio</span>
                                <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio" required>
                            </div>
                            <br>
                            <div class="input-group flex-nowrap">
                                <span class="input-group-text">Duración</span>
                                <select class="form-select" id="tipoMembresia" name="tipoMembresia" onchange="calcularTotal()" required>
                                    <option value="Semana">Semana</option>
                                    <option value="Mes">Mes</option>
                                </select>
                                <input type="number" class="form-control" id="cantidad" name="cantidad" placeholder="Número" oninput="calcularTotal()" required>
                            </div>
                            <br>
                            <div class="input-group flex-nowrap">
                                <span class="input-group-text">Fecha de Fin</span>
                                <input type="date" class="form-control" id="fecha_fin" name="fecha_fin" readonly required>
                            </div>
                            <div class="form-group">
                                <label for="total">Total:</label>
                                <input type="text" id="total" name="total" class="form-control" readonly>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                            <button type="submit" class="btn btn-warning">Renovar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Modal Agregar Miembro -->
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Registro de Miembro</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form method="POST" id="memberForm">
                            <div class="input-group flex-nowrap">
                                <span class="input-group-text">Nombre</span>
                                <input type="text" class="form-control" name="nombre" required>
                            </div>
                            <br>
                            <div class="input-group flex-nowrap">
                                <span class="input-group-text">Apellido Paterno</span>
                                <input type="text" class="form-control" name="apellido_p" required>
                            </div>
                            <br>
                            <div class="input-group flex-nowrap">
                                <span class="input-group-text">Apellido Materno</span>
                                <input type="text" class="form-control" name="apellido_m" required>
                            </div>
                            <br>
                            <div class="input-group flex-nowrap">
                                <span class="input-group-text">Sexo</span>
                                <select class="form-select" name="sexo" required>
                                    <option value="Masculino">Masculino</option>
                                    <option value="Femenino">Femenino</option>
                                </select>
                            </div>
                            <br>
                            <div class="input-group flex-nowrap">
                                <span class="input-group-text">Teléfono</span>
                                <input type="text" class="form-control" name="telefono" required>
                            </div>
                            <br>
                            <button type="submit" class="btn btn-primary">Agregar Miembro</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Editar Miembro -->
        <div class="modal fade" id="exampleModaledit" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Editar Miembro</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form method="POST" action="../servidor/editar_miembro.php">
                            <input type="hidden" name="id_membresia" id="id_membresia_edit">
                            <div class="input-group flex-nowrap">
                                <span class="input-group-text">Nombre</span>
                                <input type="text" class="form-control" id="nombre_edit" name="nombre" required>
                            </div>
                            <br>
                            <div class="input-group flex-nowrap">
                                <span class="input-group-text">Apellido Paterno</span>
                                <input type="text" class="form-control" id="apellido_p_edit" name="apellido_p" required>
                            </div>
                            <br>
                            <div class="input-group flex-nowrap">
                                <span class="input-group-text">Apellido Materno</span>
                                <input type="text" class="form-control" id="apellido_m_edit" name="apellido_m" required>
                            </div>
                            <br>
                            <div class="input-group flex-nowrap">
                                <span class="input-group-text">Sexo</span>
                                <select class="form-select" id="sexo_edit" name="sexo" required>
                                    <option value="Masculino">Masculino</option>
                                    <option value="Femenino">Femenino</option>
                                </select>
                            </div>
                            <br>
                            <div class="input-group flex-nowrap">
                                <span class="input-group-text">Teléfono</span>
                                <input type="text" class="form-control" id="telefono_edit" name="telefono" required>
                            </div>
                            <br>
                            <button type="submit" class="btn btn-warning">Actualizar Miembro</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
<footer>
    
</footer>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>
<script src="js/miembros.js"></script>
</body>
</html>
