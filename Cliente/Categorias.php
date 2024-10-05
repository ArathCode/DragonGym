<?php
include_once("../Servidor/conexion.php");

if(!empty($_POST)){
  if(empty($_POST['cam1']) || empty($_POST['cam2'])){
    $alert = '<div class="alert alert-danger d-flex align-items-center" role="alert">
              <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Warning:"><use xlink:href="#exclamation-triangle-fill"/></svg>
              <div>Todos los campos son obligatorios</div>
              </div>';
  } else {
    $c1 = $_POST['cam1'];
    $c2 = $_POST['cam2'];
    
   
    
   
      $consulta = mysqli_query($conexion, "INSERT INTO categorias (id_cat, nombrec, descripcion)
                                            VALUES (NULL, '$c1', '$c2')");
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
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Categorias</title>
    <link rel="shortcut icon" href="Imagenes/logof.jpg" />
</head>
<body>

    <!-- ENCABEZADO -->
    <?php include_once("include/encabezado.php"); ?>
    <!-- ENCABEZADO -->
    <br>
    <div class="container" style="">
  <div class="d-flex justify-content-between align-items-center">
    <!-- Título h2 -->
    <h2>Categorias</h2>
    <!-- Botón para Nuevo Usuario -->
    <button type="button" class="btn btn-primary" style="background-color:black;" data-bs-toggle="modal" data-bs-target="#exampleModal"><img src="Imagenes/add.png" height="16px" width="16px">
      Nueva Categoria
    </button>
   
  </div>
</div>
<br>
    <div class="container" style="text-align:center">
     

        <table class="table">
            <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Nombre</th>
                    <th scope="col">Descripcion</th>
                   
                    <?php if($_SESSION['rol'] == 1) { ?>
                        
                        <th scope="col">Acciones</th>
                    <?php } ?>
                </tr>
            </thead>
            <tbody>
                <?php
                $con = mysqli_query($conexion, "SELECT id_cat, nombrec, descripcion
                                                FROM categorias");
                while($datos = mysqli_fetch_assoc($con)) {
                ?>
                <tr>
                    <td><?php echo $datos['id_cat']; ?></td>
                    <td><?php echo $datos['nombrec']; ?></td>
                    <td><?php echo $datos['descripcion']; ?></td>
               
                    <?php if($_SESSION['rol'] == 1) { ?>
                        
                        <td>
                            <!-- Botón para editar -->
                            <button type="button" class="btn btn-dark editBtn" 
                                    data-id="<?php echo $datos['id_cat']; ?>" 
                                    data-nombre="<?php echo $datos['nombrec']; ?>" 
                                    data-descripcion="<?php echo $datos['descripcion']; ?>" 
                                   
                                    data-bs-toggle="modal" data-bs-target="#exampleModaledit">
                                <img src="Imagenes/lapiz.png" height="16px" width="16px">
                            </button>
                            <!-- Botón para eliminar -->
                            <a href="../Servidor/borrar_categorias.php?id=<?php echo $datos['id_cat']; ?>">
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
                    <h5 class="modal-title" id="exampleModalLabel">Registro de Categoria</h5>
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
                            <span class="input-group-text">Descripción</span>
                            <input type="text" class="form-control" name="cam2">
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

    <!-- Modal Editar -->
    <div class="modal fade" id="exampleModaledit" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Editar Categoria</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editForm" method="POST" action="../Servidor/editar_categoria.php">
                        <input type="hidden" id="edit-id" name="id_cat">
                        <div class="input-group flex-nowrap">
                            <span class="input-group-text">Nombre</span>
                            <input type="text" class="form-control" id="edit-nombre" name="cam1" >
                        </div>
                        <br>
                        <div class="input-group flex-nowrap">
                            <span class="input-group-text">Descripcion</span>
                            <input type="text" class="form-control" id="edit-descripcion" name="cam2" readonly>
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
            const descripcion = this.getAttribute('data-descripcion');
            

            document.getElementById('edit-id').value = id;
            document.getElementById('edit-nombre').value = nombre;
            document.getElementById('edit-descripcion').value = descripcion;
            
        });
    });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

