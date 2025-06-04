<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

$mail = new PHPMailer(true);

try {
    // Configuración del servidor SMTP
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'verificacion467@gmail.com'; // TU CORREO
    $mail->Password   = 'mkjy ejnb yskr xeig'; // CONTRASEÑA O APP PASSWORD
    $mail->SMTPSecure = 'tls';
    $mail->Port       = 587;

    // Remitente y receptor
    $mail->setFrom('tu_correo@gmail.com', 'Soporte');
    $mail->addAddress('verificacion467@gmail.com', 'Administrador');

    // Contenido
    $mail->isHTML(true);
    $mail->Subject = 'Solicitud de desbloqueo de cuenta';
    $mail->Body    = 'El usuario ha solicitado desbloquear su cuenta.';

    $mail->send();
    echo 'Mensaje enviado correctamente.';
} catch (Exception $e) {
    echo "Error al enviar el mensaje: {$mail->ErrorInfo}";
}
