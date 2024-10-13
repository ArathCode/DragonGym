<?php
include_once("../Servidor/conexion.php");

if(!empty($_POST)){
  if(empty($_POST['cam1']) || empty($_POST['cam2']) || empty($_POST['cam3']) || empty($_POST['cam4']) ){
    $alert = '<div class="alert alert-danger d-flex align-items-center" role="alert">
              <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Warning:"><use xlink:href="#exclamation-triangle-fill"/></svg>
              <div>Todos los campos son obligatorios</div>
              </div>';
  } else {
    $c1 = $_POST['cam1'];
    $c2 = $_POST['cam2'];
    $c3 = $_POST['cam3'];
    $c4 = $_POST['cam4'];
    
   

    
      $consulta = mysqli_query($conexion, "INSERT INTO gastos (ID_Gasto, Descripcion, Precio, Fecha, ID_Usuario) 
                                            VALUES (NULL, '$c1', '$c2', '$c3', '$c4')");
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
    <h2>Administración de gastos</h2>
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
                    <th scope="col">Descripcion</th>
                    <th scope="col">Precio</th>
                    <th scope="col">Fecha</th>
                        <th scope="col">Personal</th>
                        
                        <th scope="col">Acciones</th>
                  
                </tr>
            </thead>
            <tbody>
                <?php
                $con = mysqli_query($conexion, "SELECT u.ID_Gasto, u.Descripcion, u.Precio, u.Fecha,u.ID_Usuario, t.NombreUsuario 
                                                FROM gastos u 
                                                INNER JOIN usuario t ON u.ID_Usuario = t.ID_Usuario");
                while($datos = mysqli_fetch_assoc($con)) {
                ?>
                <tr>
                    <td><?php echo $datos['ID_Gasto']; ?></td>
                    <td><?php echo $datos['Descripcion']; ?></td>
                    <td><?php echo $datos['Precio']; ?></td>
                    <td><?php echo $datos['Fecha']; ?></td>
                    
                        <td><?php echo $datos['NombreUsuario']; ?></td>
                        
                        <td>
                            <!-- Botón para editar -->
                            <button type="button" class="btn btn-dark editBtn" 
                                    data-id="<?php echo $datos['ID_Gasto']; ?>" 
                                    data-descripcion="<?php echo $datos['Descripcion']; ?>" 
                                    data-precio="<?php echo $datos['Precio']; ?>" 
                                    data-fecha="<?php echo $datos['Fecha']; ?>" 
                                    data-usu="<?php echo $datos['ID_Usuario']; ?>" 

                                    data-bs-toggle="modal" data-bs-target="#exampleModaledit">
                                <img src="Imagenes/lapiz.png" height="16px" width="16px">
                            </button>
                            <!-- Botón para eliminar -->
                            <a href="../Servidor/borrar_gasto.php?id=<?php echo $datos['ID_Gasto']; ?>">
                                <button type="button" class="btn btn-danger"><img src="Imagenes/cruz.png" height="16px" width="16px"></button>
                            </a>
                        </td>
                   
                </tr>
                <?php } ?>
            </tbody>
        </table>
                    </div>
    </div>
    <br><br>
   
    <!-- Modal Agregar -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Registro de gastos</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST">
                        
                     
                        <div class="input-group flex-nowrap">
                            <span class="input-group-text">Descripcion</span>
                            <input type="text" class="form-control" name="cam1">
                        </div>
                        <br>
                        <div class="input-group flex-nowrap">
                            <span class="input-group-text">Precio</span>
                            <input type="text" class="form-control" name="cam2">
                        </div>
                        <br>
                        <div class="input-group flex-nowrap">
                            <span class="input-group-text">Fecha</span>
                            <input type="date" class="form-control" name="cam3">
                        </div>
                        <br>
                        
                        <select class="form-select" name="cam4">
                            <?php
                            $cone = mysqli_query($conexion, "SELECT * FROM usuario");
                            while($datos = mysqli_fetch_assoc($cone)) {
                            ?>
                            <option value="<?php echo $datos['ID_Usuario']; ?>"><?php echo $datos['NombreUsuario']; ?></option>
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
                    <h5 class="modal-title" id="exampleModalLabel">Editar gasto</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editForm" method="POST" action="../Servidor/editar_gasto.php">
                        <input type="hidden" id="edit-id" name="ID_Gasto">
                        <div class="input-group flex-nowrap">
                            <span class="input-group-text">Descripcion</span>
                            <input type="text" class="form-control" id="edit-nombre" name="cam1" >
                        </div>
                        <br>
                        <div class="input-group flex-nowrap">
                            <span class="input-group-text">Precio</span>
                            <input type="text" class="form-control" id="edit-apaterno" name="cam2" >
                        </div>
                        <br>
                        <div class="input-group flex-nowrap">
                            <span class="input-group-text">Fecha</span>
                            <input type="text" class="form-control" id="edit-amaterno" name="cam3" >
                        </div>
                        
                        <br>
                        <select class="form-select" id="edit-tipo" name="cam4">
                            <?php
                            $cone = mysqli_query($conexion, "SELECT * FROM usuario");
                            while($datos = mysqli_fetch_assoc($cone)) {
                            ?>
                            <option value="<?php echo $datos['ID_Usuario']; ?>"><?php echo $datos['NombreUsuario']; ?></option>
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
     <footer>
        <?php include_once("include/footer.php"); ?>
    </footer>
    <br><br><br>
    <!--FOOTER-->
    
    
    <!--FOOTER-->
    <script>
    document.querySelectorAll('.editBtn').forEach(button => {
        button.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            const descripcion = this.getAttribute('data-descripcion');
            const precio = this.getAttribute('data-precio');
            const fecha = this.getAttribute('data-fecha');
            const personal = this.getAttribute('data-usu');
            

            document.getElementById('edit-id').value = id;
            document.getElementById('edit-nombre').value = descripcion;
            document.getElementById('edit-apaterno').value = precio;
            document.getElementById('edit-amaterno').value = fecha;
            document.getElementById('edit-correo').value = personal;
           
        });
    });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
