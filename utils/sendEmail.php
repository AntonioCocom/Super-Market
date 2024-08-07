<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function sendVerificationEmail($userEmail, $token) {
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; 
        $mail->SMTPAuth = true;
        $mail->Username = 'correoescolar2410@gmail.com';
        $mail->Password = 'ohsy hpyh zlts oere';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587; // Puerto SMTP
        $mail->CharSet = 'UTF-8';

        // Remitente y destinatario
        $mail->setFrom('correoescolar2410@gmail.comm', 'Supermarket');
        $mail->addAddress('jcocom220@gmail.com');

        // Contenido del correo
        $mail->isHTML(true);
        $mail->Subject = 'Código de Verificación';
        $mail->Body = "Tu código de verificación es: <b>$token</b>";

        $mail->send();
        return true;
    } catch (Exception $e) {
        return false;
    }
}

?>