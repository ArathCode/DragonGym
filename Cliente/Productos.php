<?php
include_once("../Servidor/conexion.php");

if(!empty($_POST)){
    if(empty($_POST['cam1']) || empty($_POST['cam2']) || empty($_POST['cam3']) || empty($_POST['cam4']) || empty($_POST['cam5']) || empty($_FILES['cam6']['name'])){
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
        
        // Mover imagen al servidor
        $foto = $_FILES['cam6']['name'];
        $ruta_temporal = $_FILES['cam6']['tmp_name'];
        $carpeta_destino = "imagenesc/"; // Carpeta donde se almacenarán las imágenes

        // Verificar que la carpeta existe, si no, crearla
        if (!is_dir($carpeta_destino)) {
            mkdir($carpeta_destino, 0755, true);
        }

        // Crear la ruta final de la imagen
        $ruta_final = $carpeta_destino . $foto;

        // Mover la imagen a la carpeta de destino
        if (move_uploaded_file($ruta_temporal, $ruta_final)) {
            // Insertar los datos y la ruta de la imagen en la base de datos
            $consulta = mysqli_query($conexion, "INSERT INTO productos (ID_Producto, Nombre, Marca, Rodada, Cantidad, id_cat, foto) 
                                                 VALUES (NULL, '$c1', '$c2', '$c3', '$c4', '$c5', '$ruta_final')");

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
        } else {
            $alert = '<div class="alert alert-danger d-flex align-items-center" role="alert">
                      <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Warning:"><use xlink:href="#exclamation-triangle-fill"/></svg>
                      <div>Error al subir la imagen</div>
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
    <title>Productos</title>
    <style>
        .agrandar{
            transition: transform 0.2s ease-out;
        }

        .agrandar:hover{
            -webkit-transform: scale(1.5);
            transform: scale(5.0)
        }
    </style>
</head>
<body>

    <!-- ENCABEZADO -->
    <?php include_once("include/encabezado.php"); ?>
    <!-- ENCABEZADO -->
    <br>
    <div class="container" style="">
  <div class="d-flex justify-content-between align-items-center">
    <h2>Administración de Productos</h2>
    <button type="button" class="btn btn-primary" style="background-color:black;" data-bs-toggle="modal" data-bs-target="#exampleModal"><img src="Imagenes/add.png" height="16px" width="16px">
      Nuevo Producto
    </button>
  </div>
</div>
         <br>
         
    </div>  
    <div class="container" style="text-align:center">
    <?php echo isset($alert) ? $alert : ""; ?>
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Nombre</th>
                    <th scope="col">Marca</th>
                    <th scope="col">Rodada</th>
                    <th scope="col">Cantidad</th>
                    <th scope="col">Categoria</th>
                    <th scope="col">Foto</th>
                    <?php if($_SESSION['rol'] == 1) { ?>
                       
                  
                        <th scope="col">Acciones</th>
                    <?php } ?>
                    
                </tr>
            </thead>
            <tbody>
                <?php
                $con = mysqli_query($conexion, "SELECT u.ID_Producto, u.Nombre, u.Marca, u.Rodada, u.Cantidad,t.nombrec , u.foto    
                                                FROM productos u 
                                                INNER JOIN categorias t ON u.id_cat = t.id_cat");
                while($datos = mysqli_fetch_assoc($con)) {
                ?>
                <tr>
                    <td><?php echo $datos['ID_Producto']; ?></td>
                    <td><?php echo $datos['Nombre']; ?></td>
                    <td><?php echo $datos['Marca']; ?></td>
                    <td><?php echo $datos['Rodada']; ?></td>
                    <td><?php echo $datos['Cantidad']; ?></td>
                    <td><?php echo $datos['nombrec']; ?></td>
                    <td><img class="agrandar" src="<?php echo $datos['foto']; ?>" alt="Foto" width="100" height="70" style="border-radius:10px"></td>
                    <?php if($_SESSION['rol'] == 1) { ?>
                        
                        <td>
                            <!-- Botón para editar -->
                            <button type="button" class="btn btn-dark editBtn" 
                                    data-id="<?php echo $datos['ID_Producto']; ?>" 
                                    data-nombre="<?php echo $datos['Nombre']; ?>" 
                                    data-marca="<?php echo $datos['Marca']; ?>" 
                                    data-rodada="<?php echo $datos['Rodada']; ?>" 
                                    data-cantidad="<?php echo $datos['Cantidad']; ?>" 
                                    data-tipo="<?php echo $datos['nombrec']; ?>" 
                                    data-foto="<?php echo $datos['foto']; ?>" 
                                  
                                    data-bs-toggle="modal" data-bs-target="#exampleModaledit">
                                <img src="Imagenes/lapiz.png" height="16px" width="16px">
                            </button>
                            <!-- Botón para eliminar -->
                            <a href="../Servidor/borrar_producto.php?id=<?php echo $datos['ID_Producto']; ?>">
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
                <form method="POST" enctype="multipart/form-data">

                        <div class="input-group flex-nowrap">
                            <span class="input-group-text">Modelo</span>
                            <input type="text" class="form-control" name="cam1">
                        </div>
                        <br>
                        <div class="input-group flex-nowrap">
                            <span class="input-group-text">Marca </span>
                            <input type="text" class="form-control" name="cam2">
                        </div>
                        <br>
                        <div class="input-group flex-nowrap">
                            <span class="input-group-text">Rodada</span>
                            <input type="text" class="form-control" name="cam3">
                        </div>
                        <br>
                        <div class="input-group flex-nowrap">
                            <span class="input-group-text">Cantidad</span>
                            <input type="text" class="form-control" name="cam4">
                        </div>
                        <br>
                      
                        <select class="form-select" name="cam5">
                            <?php
                            $cone = mysqli_query($conexion, "SELECT * FROM categorias");
                            while($datos = mysqli_fetch_assoc($cone)) {
                            ?>
                            <option value="<?php echo $datos['id_cat']; ?>"><?php echo $datos['nombrec']; ?></option>
                            <?php } ?>
                        </select>
                        <br>
                        <div class="input-group flex-nowrap">
                            <span class="input-group-text">Foto</span>
                            <input type="file" class="form-control" name="cam6">
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
                    <h5 class="modal-title" id="exampleModalLabel">Editar productos</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editForm" method="POST" enctype="multipart/form-data" action="../Servidor/editar_producto.php">
                        <input type="hidden" id="edit-id" name="ID_Producto">
                        <div class="input-group flex-nowrap">
                            <span class="input-group-text">Modelo</span>
                            <input type="text" class="form-control" id="edit-nombre" name="cam1" readonly>
                        </div>
                        <br>
                        <div class="input-group flex-nowrap">
                            <span class="input-group-text">Marca</span>
                            <input type="text" class="form-control" id="edit-apaterno" name="cam2" readonly>
                        </div>
                        <br>
                        <div class="input-group flex-nowrap">
                            <span class="input-group-text">Rodada</span>
                            <input type="text" class="form-control" id="edit-amaterno" name="cam3" readonly>
                        </div>
                        <br>
                        <div class="input-group flex-nowrap">
                            <span class="input-group-text">Cantidad</span>
                            <input type="text" class="form-control" id="edit-correo" name="cam4">
                        </div>
                        
                        <br>
                        <select class="form-select" id="edit-tipo" name="cam5">
                            <?php
                            $cone = mysqli_query($conexion, "SELECT * FROM categorias");
                            while($datos = mysqli_fetch_assoc($cone)) {
                            ?>
                            <option value="<?php echo $datos['id_cat']; ?>"><?php echo $datos['nombrec']; ?></option>
                            <?php } ?>
                        </select>
                        <br>
                        <div class="input-group flex-nowrap">
                            <span class="input-group-text">Foto</span>
                            <input type="file" class="form-control" id="edit-foto" name="cam6" >
                        </div>
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
            const marca = this.getAttribute('data-marca');
            const rodada = this.getAttribute('data-rodada');
            const cantidad = this.getAttribute('data-cantidad');
            const tipo = this.getAttribute('data-tipo');
            const foto = this.getAttribute('data-foto');

            document.getElementById('edit-id').value = id;
            document.getElementById('edit-nombre').value = nombre;
            document.getElementById('edit-apaterno').value = marca;
            document.getElementById('edit-amaterno').value = rodada;
            document.getElementById('edit-correo').value = cantidad;
            document.getElementById('edit-telefono').value = tipo;
            document.getElementById('edit-foto').value = foto;
           
        });
    });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
