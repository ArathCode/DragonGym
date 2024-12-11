<?php
include_once("../Servidor/conexion.php");

if (!empty($_POST)) {
    if (empty($_POST['NombreUsuario']) || empty($_POST['Contraseña']) || empty($_POST['ID_Rol']) || empty($_POST['Nombre']) || empty($_POST['ApellidoP']) || empty($_POST['Salario']) || empty($_FILES['Foto']['name'])) {
        $alert = '<p style="color:red;">Todos los campos son obligatorios</p>';
    } else {
        $nombreUsuario = $_POST['NombreUsuario'];
        $contraseña = $_POST['Contraseña'];
        $idRol = $_POST['ID_Rol'];
        $nombre = $_POST['Nombre'];
        $apellidoP = $_POST['ApellidoP'];
        $salario = $_POST['Salario'];

        $foto = $_FILES['Foto']['name'];
        $ruta_temporal = $_FILES['Foto']['tmp_name'];
        $carpeta_destino = "imagenes_usuarios/";

        if (!is_dir($carpeta_destino)) {
            mkdir($carpeta_destino, 0755, true);
        }

        $ruta_final = $carpeta_destino . $foto;

        if (move_uploaded_file($ruta_temporal, $ruta_final)) {

            $consulta = mysqli_query($conexion, "INSERT INTO usuario (ID_Usuario, NombreUsuario, Contraseña, ID_Rol, Nombre, ApellidoP, Salario, Foto) 
                                                 VALUES (NULL, '$nombreUsuario', '$contraseña', '$idRol', '$nombre', '$apellidoP', '$salario', '$ruta_final')");

            if ($consulta) {
                $alert = '<p style="color:green;">Datos guardados correctamente</p>';
            } else {
                $alert = '<p style="color:red;">Error al guardar los datos</p>';
            }
        } else {
            $alert = '<p style="color:red;">Error al subir la imagen</p>';
        }
    }
}
?>

<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
    <title>Usuarios</title>
    <link rel="stylesheet" href="css/controlP.css">
</head>

<body>

    <!-- ENCABEZADO -->

    <div class="container" id="menu">
        <div class="navigation">
            <?php
            include_once("include/encabezado.php")
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


            <div class="titulo">
                <h2>Administración de Usuarios</h2>
            </div>
            
            <div>
                <button onclick="mostrarModal()">Nuevo Usuario</button>
            </div>

            <div>
                <?php echo isset($alert) ? $alert : ""; ?>

                <tbody>
                    <?php
                    $con = mysqli_query($conexion, "SELECT u.ID_Usuario, u.NombreUsuario, u.Contraseña,u.ID_Rol, t.Descripcion, u.Nombre, u.ApellidoP, u.Salario, u.Foto 
                            FROM usuario u 
                            INNER JOIN roles t ON u.ID_Rol = t.ID_Rol");
                    while ($datos = mysqli_fetch_assoc($con)) {
                        ?>
                        <div class="user-card">
                            <div class="card-header">
                                <a href="../Servidor/eliminar_usuario.php?id=<?php echo $datos['ID_Usuario']; ?>"
                                    class="delete-btn">Eliminar</a>
                                <button type="button" class="editUsuarioBtn" data-id="<?php echo $datos['ID_Usuario']; ?>"
                                    data-nombre-usu="<?php echo $datos['NombreUsuario']; ?>"
                                    data-email="<?php echo $datos['Contraseña']; ?>"
                                    data-rol="<?php echo $datos['ID_Rol']; ?>" data-nombre="<?php echo $datos['Nombre']; ?>"
                                    data-ap="<?php echo $datos['ApellidoP']; ?>"
                                    data-sal="<?php echo $datos['Salario']; ?>">

                                    Editar
                                </button>
                            </div>
                            <div class="card-body">
                                <img src="<?php echo $datos['Foto']; ?>" alt="Foto del Usuario" class="user-image">
                                <p>#<?php echo $datos['ID_Usuario']; ?> (<?php echo $datos['Descripcion']; ?>)</p>
                                <h3><?php echo $datos['Nombre'] . " " . $datos['ApellidoP']; ?></h3>
                                <p>Nombre de Usuario: <?php echo $datos['NombreUsuario']; ?></p>
                                <p>Salario: <?php echo $datos['Salario']; ?></p>
                            </div>
                        </div>
                    <?php } ?>


            </div>


            <!-- Modal Agregar -->
            <div id="modalAgregar" class="modal">
                <div class="modal-content">
                    <span class="close" id="closeAgUsuario">&times;</span>
                    <h2>Ageregar Usuario</h2>
                    <form method="POST" enctype="multipart/form-data">
                        <label>Nombre de Usuario:</label>
                        <input type="text" name="NombreUsuario">
                        <br><br>
                        <label>Contraseña:</label>
                        <input type="password" name="Contraseña">
                        <br><br>
                        <label>Rol:</label>
                        <select class="form-select" name="ID_Rol">
                            <?php
                            $cone = mysqli_query($conexion, "SELECT * FROM Roles");
                            while ($datos = mysqli_fetch_assoc($cone)) {
                                ?>
                                <option value="<?php echo $datos['ID_Rol']; ?>"><?php echo $datos['Descripcion']; ?>
                                </option>
                            <?php } ?>
                        </select>
                        <br><br>
                        <label>Nombre:</label>
                        <input type="text" name="Nombre">
                        <br><br>
                        <label>Apellido Paterno:</label>
                        <input type="text" name="ApellidoP">
                        <br><br>
                        <label>Salario:</label>
                        <input type="text" name="Salario">
                        <br><br>
                        <label>Foto:</label>
                        <input type="file" name="Foto">
                        <br><br>
                        <button type="submit">Guardar</button>
                        <button type="button" onclick="cerrarModal()">Cerrar</button>
                    </form>
                </div>
            </div>

            <!-- Modal Editar Usuario -->
            <div id="modalEditUsuario" class="modal">
                <div class="modal-content">
                    <span class="close" id="closeEditUsuario">&times;</span>
                    <h2>Editar Usuario</h2>
                    <form id="editFormUsuario" method="POST" enctype="multipart/form-data"
                        action="../Servidor/editar_usuario.php">
                        <input type="hidden" id="edit-id" name="ID_Usuario">

                        <label for="edit-nombre-usuario">Nombre de Usuario:</label>
                        <input type="text" id="edit-nombre-usuario" name="NombreUsuario" required>

                        <label for="edit-password">Contraseña (Opcional):</label>
                        <input type="password" id="edit-password" name="Contraseña">

                        <label for="edit-rol">Rol:</label>

                        <select class="form-select" id="edit-rol" name="ID_Rol">
                            <?php
                            $cone = mysqli_query($conexion, "SELECT * FROM Roles");
                            while ($datos = mysqli_fetch_assoc($cone)) {
                                ?>
                                <option value="<?php echo $datos['ID_Rol']; ?>"><?php echo $datos['Descripcion']; ?>
                                </option>
                            <?php } ?>
                        </select>
                        <br><br>
                        <label for="edit-nombre">Nombre:</label>
                        <input type="text" id="edit-nombre" name="Nombre" required>

                        <label for="edit-apellido">Apellido Paterno:</label>
                        <input type="text" id="edit-apellido" name="ApellidoP" required>

                        <label for="edit-salario">Salario:</label>
                        <input type="number" id="edit-salario" name="Salario" required>

                        <label for="edit-foto">Foto (Opcional):</label>
                        <input type="file" id="edit-foto" name="Foto">

                        <button type="submit">Guardar Cambios</button>
                    </form>
                </div>
            </div>



        </div>

    </div>

    <script>
        function mostrarModal() {
            document.getElementById('modalAgregar').style.display = 'block';
        }

        function cerrarModal() {
            document.getElementById('modalAgregar').style.display = 'none';
        }
    </script>
    <script>

        document.querySelectorAll('.editUsuarioBtn').forEach(button => {
            button.addEventListener('click', function () {
                const id = this.getAttribute('data-id');
                const nombreUsuario = this.getAttribute('data-nombre-usu');
                const email = this.getAttribute('data-email');
                const rol = this.getAttribute('data-rol');
                const nombre = this.getAttribute('data-nombre');
                const apellido = this.getAttribute('data-ap');
                const salario = this.getAttribute('data-sal');

                document.getElementById('edit-id').value = id;
                document.getElementById('edit-nombre-usuario').value = nombreUsuario;
                document.getElementById('edit-rol').value = rol;
                document.getElementById('edit-nombre').value = nombre;
                document.getElementById('edit-apellido').value = apellido;
                document.getElementById('edit-salario').value = salario;

                document.getElementById('modalEditUsuario').style.display = 'block';
            });
        });

        document.getElementById('closeEditUsuario').onclick = function () {
            document.getElementById('modalEditUsuario').style.display = 'none';
        }
        document.getElementById('closeAgUsuario').onclick = function () {
            document.getElementById('modalAgregar').style.display = 'none';
        }

        window.onclick = function (event) {
            if (event.target == document.getElementById('modalEditUsuario')) {
                document.getElementById('modalEditUsuario').style.display = 'none';
            }
        }
    </script>
    
</body>

</html>