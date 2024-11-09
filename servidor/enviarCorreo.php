<?php
include_once("conexion.php");
$correo = $_GET['correo'];
$con = "SELECT * FROM usuario WHERE correo = '$correo'";
$res = mysqli_query($conexion, $con);

if ($data = mysqli_fetch_array($res)) {
    $nom = $data['NombreUsuario'];
    $nomusu = $data['Nombre'];
    $apa = $data['ApellidoP'];
    $pass = $data['Contraseña'];
}

// Incluimos los archivos de la librería PHPMailer
require '../lib/PHPMailer-master/src/Exception.php';
require '../lib/PHPMailer-master/src/PHPMailer.php';
require '../lib/PHPMailer-master/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

// Creamos una instancia de PHPMailer
$mail = new PHPMailer(true);

try {
    // Configurar el servidor SMTP para Gmail
    $mail->isSMTP();
    $mail->CharSet = 'UTF-8';
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'arathsaavedracabrera96@gmail.com';
    $mail->Password = 'cgjyqkeiatrpbxbr';
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    // Configurar los destinatarios
    $mail->setFrom('arathsaavedracabrera96@gmail.com', 'Gestión de Dragon Gym');
    $mail->addAddress($correo, $nom);
    $mail->Subject = 'RECUPERACIÓN DE CONTRASEÑA';

    // Adjuntar la imagen como un archivo embebido
    $mail->addEmbeddedImage('../cliente/imgs/logo.jpg', 'DragonGymLogo');

    // Cuerpo del correo en HTML con la imagen centrada y estilos
    $mail->isHTML(true);
    $mail->Body = "
    <html>
    <body style='font-family: Arial, sans-serif; color: black;'>
        <div style='text-align: center; padding: 20px;'>
            <h1 style='color: #8d2e27;'>Hola usuario $nom</h1>
            <p>_______________________________________________________________________________________________________</p>
            <p>$nomusu $apa, esta es una respuesta a tu solicitud de recuperación de contraseña.</p>
            <p>Tu contraseña es -> <strong>$pass</strong>.</p>
            <p>Por favor, no compartas o muestres este correo electrónico a nadie, por la seguridad de tu información.</p>
            <p style='color: #666;'>Muchas gracias.<br>Equipo de Dragon Gym</p>
            <img src='cid:DragonGymLogo' alt='DragonGym' style='width:100px; height:auto; margin: 0 auto; display: block;'>

        </div>
    </body>
    </html>";

    // Enviar el correo
    $mail->send();
    echo "<script language='JavaScript'>
        alert('Correo enviado con éxito');
        location.assign('../index.php');
    </script>";

} catch (Exception $e) {
    echo "No se pudo enviar el correo: {$mail->ErrorInfo}";
}
?>
