<?php
include_once("../Servidor/conexion.php");
$categoria = isset($_GET['categoria']) ? $_GET['categoria'] : '';

// Consulta básica para seleccionar todos los miembros
$query = "SELECT ID_Membresia, Nombre, ApellidoP, ApellidoM, Sexo, Categoria, FechaInicio, FechaFin, Telefono, MesesT, MesesC FROM miembros";

// Si se ha seleccionado un filtro de categoría (Semana o Mes), añadimos la condición al query
if ($categoria != '') {
    $query .= " WHERE Categoria = '$categoria'";
}

// Ejecutar la consulta
$result = mysqli_query($conexion, $query);
// Insertar nuevo miembro
if (!empty($_POST)) {
    if (empty($_POST['nombre']) || empty($_POST['apellido_p']) || empty($_POST['apellido_m']) || empty($_POST['sexo']) || empty($_POST['fecha_inicio']) || empty($_POST['fecha_fin']) || empty($_POST['telefono']) || empty($_POST['meses_t']) || empty($_POST['meses_c'])) {
        $alert = '<div class="alert alert-danger d-flex align-items-center" role="alert">
                  <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Warning:"><use xlink:href="#exclamation-triangle-fill"/></svg>
                  <div>Todos los campos son obligatorios</div>
                  </div>';
    } else {
        $nombre = $_POST['nombre'];
        $apellidoP = $_POST['apellido_p'];
        $apellidoM = $_POST['apellido_m'];
        $sexo = $_POST['sexo'];
        $fechaInicio = $_POST['fecha_inicio'];
        $fechaFin = $_POST['fecha_fin'];
        $telefono = $_POST['telefono'];
        $mesesT = $_POST['meses_t'];
        $mesesC = $_POST['meses_c'];
        $categoria = $_POST['duracion_tipo']; 
    
        // Insertar en la tabla miembros
        $consulta  = "INSERT INTO miembros (Nombre, ApellidoP, ApellidoM, Sexo, Categoria, FechaInicio, FechaFin, Telefono, MesesT, MesesC)
                  VALUES ('$nombre', '$apellidoP', '$apellidoM', '$sexo', '$categoria', '$fechaInicio', '$fechaFin', '$telefono', '$mesesT', '$mesesC')";
        
        $result = mysqli_query($conexion, $consulta );
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
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Miembros</title>
    <link rel="shortcut icon" href="Imagenes/logof.jpg" />
    <style>
        .vencido {
            background-color: #f8d7da; /* Rojo suave para miembros con membresía vencida */
        }
        .vigente {
            background-color: #d4edda; /* Verde suave para miembros con membresía vigente */
        }
    </style>
</head>
<body>

    <!-- ENCABEZADO -->
    <?php include_once("include/encabezado.php"); ?>
    <!-- ENCABEZADO -->
    <br>
    <div class="container">
        <div class="d-flex justify-content-between align-items-center">
            <h2>Miembros</h2>
            <!-- Botón para agregar nuevo miembro -->
            <button type="button" class="btn btn-primary" style="background-color:black;" data-bs-toggle="modal" data-bs-target="#exampleModal">
                <img src="Imagenes/add.png" height="16px" width="16px"> Nuevo Miembro
            </button>
        </div>
        
    </div>
    
    <br>

    <!-- Mostrar alertas -->
    <?php if (isset($alert)) echo $alert; ?>

    <div class="container" style="text-align:center">
        <table class="table">
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
                
                $con = mysqli_query($conexion, "SELECT * FROM miembros");
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
                            <!-- Botón para editar -->
                            <button type="button" class="btn btn-dark editBtn" data-id="<?php echo $datos['ID_Membresia']; ?>" 
                                    data-nombre="<?php echo $datos['Nombre']; ?>" 
                                    data-apellido-p="<?php echo $datos['ApellidoP']; ?>" 
                                    data-apellido-m="<?php echo $datos['ApellidoM']; ?>" 
                                    data-sexo="<?php echo $datos['Sexo']; ?>" 
                                    data-sexo="<?php echo $datos['Categoria']; ?>" 
                                    data-fecha-inicio="<?php echo $datos['FechaInicio']; ?>" 
                                    data-fecha-fin="<?php echo $datos['FechaFin']; ?>" 
                                    data-telefono="<?php echo $datos['Telefono']; ?>" 
                                    data-bs-toggle="modal" data-bs-target="#exampleModaledit">
                                <img src="Imagenes/lapiz.png" height="16px" width="16px">
                            </button>
                            <!-- Botón para eliminar -->
                            <a href="../Servidor/borrar_miembro.php?id=<?php echo $datos['ID_Membresia']; ?>">
                                <button type="button" class="btn btn-danger">
                                    <img src="Imagenes/cruz.png" height="16px" width="16px">
                                </button>
                            </a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
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
                            <input type="text" class="form-control" name="sexo" required>
                        </div>
                        <br>
                        <div class="input-group flex-nowrap">
                            <span class="input-group-text">Fecha de Inicio</span>
                            <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio" required>
                        </div>
                        <br>
                        <div class="input-group flex-nowrap">
                            <span class="input-group-text">Duración</span>
                            <select class="form-select" id="duracion_tipo" name="duracion_tipo" required>
                                <option value="meses">Meses</option>
                                <option value="semanas">Semanas</option>
                            </select>
                            <input type="number" class="form-control" id="duracion_numero" placeholder="Número" required>
                        </div>
                        <br>
                        <div class="input-group flex-nowrap">
                            <span class="input-group-text">Fecha de Fin</span>
                            <input type="date" class="form-control" id="fecha_fin" name="fecha_fin" readonly>
                        </div>
                        <br>
                        <div class="input-group flex-nowrap">
                            <span class="input-group-text">Teléfono</span>
                            <input type="text" class="form-control" name="telefono" required>
                        </div>
                        <br>
                        <div class="input-group flex-nowrap">
                            <span class="input-group-text">Meses Totales</span>
                            <input type="number" class="form-control" name="meses_t" required>
                        </div>
                        <br>
                        <div class="input-group flex-nowrap">
                            <span class="input-group-text">Meses Completados</span>
                            <input type="number" class="form-control" name="meses_c" required>
                        </div>
                        <br>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                            <button type="submit" class="btn btn-primary">Guardar</button>
                        </div>
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
                    <form id="editForm" method="POST" action="../Servidor/editar_miembro.php">
                        <input type="hidden" id="edit-id" name="ID_Membresia">
                        <div class="input-group flex-nowrap">
                            <span class="input-group-text">Nombre</span>
                            <input type="text" class="form-control" id="edit-nombre" name="nombre" required>
                        </div>
                        <br>
                        <div class="input-group flex-nowrap">
                            <span class="input-group-text">Apellido Paterno</span>
                            <input type="text" class="form-control" id="edit-apellido-p" name="apellido_p" required>
                        </div>
                        <br>
                        <div class="input-group flex-nowrap">
                            <span class="input-group-text">Apellido Materno</span>
                            <input type="text" class="form-control" id="edit-apellido-m" name="apellido_m" required>
                        </div>
                        <br>
                        <div class="input-group flex-nowrap">
                            <span class="input-group-text">Sexo</span>
                            <input type="text" class="form-control" id="edit-sexo" name="sexo" required>
                        </div>
                        <br>
                        <div class="input-group flex-nowrap">
                            <span class="input-group-text">Fecha de Inicio</span>
                            <input type="date" class="form-control" id="edit-fecha-inicio" name="fecha_inicio" required>
                        </div>
                        <br>
                        <div class="input-group flex-nowrap">
                            <span class="input-group-text">Fecha de Fin</span>
                            <input type="date" class="form-control" id="edit-fecha-fin" name="fecha_fin" required>
                        </div>
                        <br>
                        <div class="input-group flex-nowrap">
                            <span class="input-group-text">Teléfono</span>
                            <input type="text" class="form-control" id="edit-telefono" name="telefono" required>
                        </div>
                        <br>
                        <div class="input-group flex-nowrap">
                            <span class="input-group-text">Meses Totales</span>
                            <input type="number" class="form-control" id="edit-meses-t" name="meses_t" required>
                        </div>
                        <br>
                        <div class="input-group flex-nowrap">
                            <span class="input-group-text">Meses Completados</span>
                            <input type="number" class="form-control" id="edit-meses-c" name="meses_c" required>
                        </div>
                        <br>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                            <button type="submit" class="btn btn-primary">Guardar cambios</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <footer>
        <?php include_once("include/footer.php"); ?>
    </footer>
    <br><br>
    <script>
    document.querySelectorAll('.editBtn').forEach(button => {
        button.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            const nombre = this.getAttribute('data-nombre');
            const apellidoP = this.getAttribute('data-apellido-p');
            const apellidoM = this.getAttribute('data-apellido-m');
            const sexo = this.getAttribute('data-sexo');
            const fechaInicio = this.getAttribute('data-fecha-inicio');
            const fechaFin = this.getAttribute('data-fecha-fin');
            const telefono = this.getAttribute('data-telefono');

            document.getElementById('edit-id').value = id;
            document.getElementById('edit-nombre').value = nombre;
            document.getElementById('edit-apellido-p').value = apellidoP;
            document.getElementById('edit-apellido-m').value = apellidoM;
            document.getElementById('edit-sexo').value = sexo;
            document.getElementById('edit-fecha-inicio').value = fechaInicio;
            document.getElementById('edit-fecha-fin').value = fechaFin;
            document.getElementById('edit-telefono').value = telefono;
        });
    });

    // Calcular la fecha de fin automáticamente
    document.getElementById('duracion_numero').addEventListener('input', calcularFechaFin);
    document.getElementById('duracion_tipo').addEventListener('change', calcularFechaFin);
    document.getElementById('fecha_inicio').addEventListener('change', calcularFechaFin);

    function calcularFechaFin() {
        const fechaInicio = document.getElementById('fecha_inicio').value;
        const duracionTipo = document.getElementById('duracion_tipo').value;
        const duracionNumero = parseInt(document.getElementById('duracion_numero').value) || 0;

        if (fechaInicio && duracionNumero > 0) {
            const fechaInicioDate = new Date(fechaInicio);
            let fechaFinDate;

            if (duracionTipo === 'meses') {
                fechaFinDate = new Date(fechaInicioDate.setMonth(fechaInicioDate.getMonth() + duracionNumero));
            } else if (duracionTipo === 'semanas') {
                fechaFinDate = new Date(fechaInicioDate.setDate(fechaInicioDate.getDate() + (duracionNumero * 7)));
            }

            const opciones = { year: 'numeric', month: '2-digit', day: '2-digit' };
            document.getElementById('fecha_fin').value = fechaFinDate.toLocaleDateString('fr-CA', opciones); // Formato YYYY-MM-DD
        }
    }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
