<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/vendor/autoload.php';

function sendMail(array $data)
{
    $cfg = require __DIR__ . '/.env.php';
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host       = $cfg['smtp_host'];
        $mail->SMTPAuth   = true;
        $mail->Username   = $cfg['smtp_user'];
        $mail->Password   = $cfg['smtp_pass'];
        $mail->SMTPSecure = $cfg['smtp_secure'];
        $mail->Port       = $cfg['smtp_port'];

        $mail->setFrom($cfg['from_email'], $cfg['from_name']);
        $mail->addAddress($cfg['to_email']);
        if (!empty($data['email'])) {
            $mail->addReplyTo($data['email']); // responder al remitente
        }

        $mail->isHTML(true);
        $mail->Subject = $data['subject'] ?? 'Mensaje del formulario';
        $body  = "Tienes un nuevo mensaje:<br><br>";
        foreach (['name', 'email', 'subject', 'message'] as $k) {
            if (isset($data[$k])) {
                $body .= "<b>" . htmlspecialchars($k) . ":</b> " . nl2br(htmlspecialchars($data[$k])) . "<br>";
            }
        }
        $mail->Body = $body;
        $mail->AltBody = strip_tags(str_replace('<br>', "\n", $body));

        return $mail->send();
    } catch (Exception $e) {
        error_log('Mailer Error: ' . $mail->ErrorInfo);
        return false;
    }
}
