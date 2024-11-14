<?php
include_once("conexion.php");

if (!empty($_POST)) {
    $idUsuario = $_POST['ID_Usuario'];
    $nombreUsuario = $_POST['NombreUsuario'];
    $contraseña = $_POST['Contraseña'];
    $idRol = $_POST['ID_Rol'];
    $nombre = $_POST['Nombre'];
    $apellidoP = $_POST['ApellidoP'];
    $salario = $_POST['Salario'];
    $foto = $_FILES['Foto']['name'] ?? null;

    if ($foto) {
        $ruta_temporal = $_FILES['Foto']['tmp_name'];
        $carpeta_destino = "imagenes_usuarios/";
        if (!is_dir($carpeta_destino)) {
            mkdir($carpeta_destino, 0755, true);
        }
        $ruta_final = $carpeta_destino . $foto;
        move_uploaded_file($ruta_temporal, $ruta_final);
        $fotoSql = ", Foto='$ruta_final'";
    } else {
        $fotoSql = "";
    }

    if (!empty($contraseña)) {
        $contraseñaSql = ", Contraseña='$contraseña'";
    } else {
        $contraseñaSql = "";
    }

    $consulta = mysqli_query($conexion, "UPDATE usuario SET 
        NombreUsuario='$nombreUsuario',
        ID_Rol='$idRol',
        Nombre='$nombre',
        ApellidoP='$apellidoP',
        Salario='$salario'
        $fotoSql
        $contraseñaSql
        WHERE ID_Usuario='$idUsuario'");

    if ($consulta) {
        header("location:../Cliente/ControlPersonal.php");
    } else {
        echo "Error al actualizar los datos.";
    }
}
?>