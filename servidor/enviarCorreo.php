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

    // Cambios para usar Gmail
    $mail->Host = 'smtp.gmail.com';             // Servidor SMTP de Gmail
    $mail->SMTPAuth = true;
    $mail->Username = 'roldankevin086@gmail.com';    // Tu correo de Gmail
    $mail->Password = 'kevinRolce1';          // La contraseña de tu correo o una contraseña de aplicación
    $mail->SMTPSecure = 'tls';                  // Puedes usar 'tls' o 'ssl'
    $mail->Port = 587;                          // 587 para 'tls', 465 para 'ssl'

    // Configurar los destinatarios
    $mail->setFrom('roldankevin086@gmail.com', 'Deserción estudiantil ITSSMT');
    $mail->addAddress($correo, $nom);
    $mail->Subject = 'RECUPERACIÓN DE CONTRASEÑA';
    $mail->Body = "Hola usuario $nom $nomusu $apa , esta es una respuesta a tu solicitud de recuperación de contraseña.

    Tu contraseña es -> $pass.

    Por favor, no compartas o muestres este correo electrónico a nadie, por la seguridad de su información.
    Muchas gracias. ";

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