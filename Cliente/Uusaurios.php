<?php
include_once("../Servidor/conexion.php");

if(!empty($_POST)){
  if(empty($_POST['cam1']) || empty($_POST['cam2']) || empty($_POST['cam3']) || empty($_POST['cam4']) || empty($_POST['cam5']) || empty($_POST['cam6']) || empty($_POST['cam7'])){
    $alert = '<div class="alert alert-danger d-flex align-items-center" role="alert">
              <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Warning:"><use xlink:href="#exclamation-triangle-fill"/></svg>
              <div>Todos los campos son obligatorios</div>
              </div>';
  } else {
    $c1 = $_POST['cam1'];
    $c2 = $_POST['cam2'];
    $c3 = $_POST['cam3'];
    $c4 = $_POST['cam4'];
    $c5 = $_POST['cam5'];
    $c6 = $_POST['cam6'];
    $c7 = $_POST['cam7'];
    $c8= md5($_POST['cam5']);

    $query = mysqli_query($conexion, "SELECT * FROM usuarios WHERE correo = '$c4'");
    $result = mysqli_fetch_array($query);

    if($result > 0){
      $alert = '<div class="alert alert-danger d-flex align-items-center" role="alert">
                <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Warning:"><use xlink:href="#exclamation-triangle-fill"/></svg>
                <div>El correo ya existe</div>
                </div>';
    } else {
      $consulta = mysqli_query($conexion, "INSERT INTO usuarios (idusuario, NomUsu, ApaUsu, AmaUsu, Correo, Contra, Telefono, idtipo, ruta) 
                                            VALUES (NULL, '$c1', '$c2', '$c3', '$c4', '$c5', '$c6', '$c7',  '$c8')");
      if($consulta){
        $alert = '<div class="alert alert-success d-flex align-items-center" role="alert">
                  <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Warning:"><use xlink:href="#exclamation-triangle-fill"/></svg>
                  <div>Datos guardados</div>
                  </div>';
      } else {
        $alert = '<div class="alert alert-danger d-flex align-items-center" role="alert">
                  <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Warning:"><use xlink:href="#exclamation-triangle-fill"/></svg>
                  <div>Error al guardar</div>
                  </div>';
      }
    }
  }
}
?>
<!doctype html>
<html lang="en">
<head>
    <link rel="shortcut icon" href="Imagenes/logof.jpg" />
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Administración de Usuarios</title>
</head>
<body>

    <!-- ENCABEZADO -->
    <?php include_once("include/encabezado.php"); ?>
    <!-- ENCABEZADO -->
    <br>
    <div class="container" style="">
  <div class="d-flex justify-content-between align-items-center">
    <h2>Administración de Usuarios</h2>
    <button type="button" class="btn btn-primary" style="background-color:black;" data-bs-toggle="modal" data-bs-target="#exampleModal"><img src="Imagenes/add.png" height="16px" width="16px">
      Nuevo Usuario
    </button>
  </div>
</div>
<br>
    <div class="container" style="text-align:center">
       
        <?php echo isset($alert) ? $alert : ""; ?>
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Nombre</th>
                    <th scope="col">Apellido Paterno</th>
                    <th scope="col">Apellido Materno</th>
                    <?php if($_SESSION['rol'] == 1) { ?>
                        <th scope="col">Correo</th>
                        <th scope="col">Teléfono</th>
                        <th scope="col">Tipo</th>
                        <th scope="col">Acciones</th>
                    <?php } ?>
                </tr>
            </thead>
            <tbody>
                <?php
                $con = mysqli_query($conexion, "SELECT u.idusuario, u.NomUsu, u.ApaUsu, u.AmaUsu, u.Correo, u.Telefono, u.Contra,u.idtipo ,t.tipousu 
                                                FROM usuarios u 
                                                INNER JOIN tipousuarios t ON u.idtipo = t.idtipo");
                while($datos = mysqli_fetch_assoc($con)) {
                ?>
                <tr>
                    <td><?php echo $datos['idusuario']; ?></td>
                    <td><?php echo $datos['NomUsu']; ?></td>
                    <td><?php echo $datos['ApaUsu']; ?></td>
                    <td><?php echo $datos['AmaUsu']; ?></td>
                    <?php if($_SESSION['rol'] == 1) { ?>
                        <td><?php echo $datos['Correo']; ?></td>
                        <td><?php echo $datos['Telefono']; ?></td>
                        <?php  $datos['Contra']; ?>
                        <td><?php echo $datos['tipousu']; ?></td>
                        <td>
                            <!-- Botón para editar -->
                            <button type="button" class="btn btn-dark editBtn" 
                                    data-id="<?php echo $datos['idusuario']; ?>" 
                                    data-nombre="<?php echo $datos['NomUsu']; ?>" 
                                    data-apaterno="<?php echo $datos['ApaUsu']; ?>" 
                                    data-amaterno="<?php echo $datos['AmaUsu']; ?>" 
                                    data-correo="<?php echo $datos['Correo']; ?>" 
                                    data-telefono="<?php echo $datos['Telefono']; ?>" 
                                    data-Contra="<?php echo $datos['Contra']; ?>" 
                                    data-tipo="<?php echo $datos['idtipo']; ?>" 
                                    data-bs-toggle="modal" data-bs-target="#exampleModaledit">
                                <img src="Imagenes/lapiz.png" height="16px" width="16px">
                            </button>
                            <!-- Botón para eliminar -->
                            <a href="../Servidor/borrar_usuario.php?id=<?php echo $datos['idusuario']; ?>">
                                <button type="button" class="btn btn-danger"><img src="Imagenes/cruz.png" height="16px" width="16px"></button>
                            </a>
                        </td>
                    <?php } ?>
                </tr>
                <?php } ?>
            </tbody>
        </table>
                    </div>
    </div>

    <!-- Modal Agregar -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Registro de usuarios</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST">
                        <div class="input-group flex-nowrap">
                            <span class="input-group-text">Nombre</span>
                            <input type="text" class="form-control" name="cam1">
                        </div>
                        <br>
                        <div class="input-group flex-nowrap">
                            <span class="input-group-text">Apellido Paterno</span>
                            <input type="text" class="form-control" name="cam2">
                        </div>
                        <br>
                        <div class="input-group flex-nowrap">
                            <span class="input-group-text">Apellido Materno</span>
                            <input type="text" class="form-control" name="cam3">
                        </div>
                        <br>
                        <div class="input-group flex-nowrap">
                            <span class="input-group-text">Correo</span>
                            <input type="text" class="form-control" name="cam4">
                        </div>
                        <br>
                        <div class="input-group flex-nowrap">
                            <span class="input-group-text">Contraseña</span>
                            <input type="password" class="form-control" name="cam5">
                        </div>
                        <br>
                        <div class="input-group flex-nowrap">
                            <span class="input-group-text">Teléfono</span>
                            <input type="text" class="form-control" name="cam6">
                        </div>
                        <br>
                        <select class="form-select" name="cam7">
                            <?php
                            $cone = mysqli_query($conexion, "SELECT * FROM tipousuarios");
                            while($datos = mysqli_fetch_assoc($cone)) {
                            ?>
                            <option value="<?php echo $datos['idtipo']; ?>"><?php echo $datos['tipousu']; ?></option>
                            <?php } ?>
                        </select>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                            <button type="submit" class="btn btn-primary">Guardar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Editar -->
    <div class="modal fade" id="exampleModaledit" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Editar usuarios</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editForm" method="POST" action="../Servidor/editar_usuario.php">
                        <input type="hidden" id="edit-id" name="idusuario">
                        <div class="input-group flex-nowrap">
                            <span class="input-group-text">Nombre</span>
                            <input type="text" class="form-control" id="edit-nombre" name="cam1" readonly>
                        </div>
                        <br>
                        <div class="input-group flex-nowrap">
                            <span class="input-group-text">Apellido Paterno</span>
                            <input type="text" class="form-control" id="edit-apaterno" name="cam2" readonly>
                        </div>
                        <br>
                        <div class="input-group flex-nowrap">
                            <span class="input-group-text">Apellido Materno</span>
                            <input type="text" class="form-control" id="edit-amaterno" name="cam3" readonly>
                        </div>
                        <br>
                        <div class="input-group flex-nowrap">
                            <span class="input-group-text">Correo</span>
                            <input type="email" class="form-control" id="edit-correo" name="cam4">
                        </div>
                        <br>
                        
                        <div class="input-group flex-nowrap">
                            <span class="input-group-text">Contraseña</span>
                            <input type="password" class="form-control" id="edit-contra" name="cam5">
                        </div>
                        <br>
                        <div class="input-group flex-nowrap">
                            <span class="input-group-text">Teléfono</span>
                            <input type="text" class="form-control" id="edit-telefono" name="cam6">
                        </div>
                        <br>
                        <select class="form-select" id="edit-tipo" name="cam7">
                            <?php
                            $cone = mysqli_query($conexion, "SELECT * FROM tipousuarios");
                            while($datos = mysqli_fetch_assoc($cone)) {
                            ?>
                            <option value="<?php echo $datos['idtipo']; ?>"><?php echo $datos['tipousu']; ?></option>
                            <?php } ?>
                        </select>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                            <button type="submit" class="btn btn-primary">Guardar cambios</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <br><br><br>
    <!--FOOTER-->
    
    <footer>
        <?php include_once("include/footer.php"); ?>
    </footer>
    <!--FOOTER-->
    <script>
    document.querySelectorAll('.editBtn').forEach(button => {
        button.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            const nombre = this.getAttribute('data-nombre');
            const apaterno = this.getAttribute('data-apaterno');
            const amaterno = this.getAttribute('data-amaterno');
            const correo = this.getAttribute('data-correo');
            const telefono = this.getAttribute('data-telefono');
            const contra = this.getAttribute('data-Contra');
            const tipo = this.getAttribute('data-tipo');

            document.getElementById('edit-id').value = id;
            document.getElementById('edit-nombre').value = nombre;
            document.getElementById('edit-apaterno').value = apaterno;
            document.getElementById('edit-amaterno').value = amaterno;
            document.getElementById('edit-correo').value = correo;
            document.getElementById('edit-telefono').value = telefono;
            document.getElementById('edit-contra').value = contra;
            document.getElementById('edit-tipo').value = tipo;
        });
    });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
