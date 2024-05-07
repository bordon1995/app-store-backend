<?php

use PHPMailer\PHPMailer\PHPMailer;

function gmail($usuario, $asunto)
{

    try {
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->SMTPDebug = 0;
        $mail->Host = $_ENV['EMAIL_HOST'];
        $mail->Port = $_ENV['EMAIL_PORT'];
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->SMTPAuth = true;

        $email = $_ENV['EMAIL_USER'];

        $mail->Username = $email;
        $mail->Password = "bdng tcol bxvr nxgn";

        $mail->setFrom($email, 'Zuni-Store');
        $mail->addAddress($usuario->correo, $usuario->nombre);
        $mail->Subject = $asunto;

        $mail->CharSet = 'UTF-8';
        $mail->isHTML(TRUE);

        $contenido = '<html>';
        $contenido .= '<p><strong>Hola ' . $usuario->nombre . ' confirma tu cuena ingresando al siguiente enlace</strong></p>';
        $contenido .= '<a href="' . $_ENV['DOMAIN_URL_FRONTED'] . '/confirmar-cuenta/' . $usuario->token . '">Confirmar Cuenta</a>';
        $contenido .= '</html>';

        $mail->Body = $contenido;

        if (!$mail->send()) {
            throw new Exception($mail->ErrorInfo);
        }
    } catch (\Throwable $th) {
        throw $th;
    }
}
