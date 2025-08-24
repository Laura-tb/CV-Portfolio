<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

header('Content-Type: application/json; charset=utf-8');
error_reporting(E_ALL);
ini_set('display_errors', 0);

// rutas correctas
require __DIR__ . '/../vendor/autoload.php';
$cfg = require __DIR__ . '/config.php';

// recoger campos
$hp      = $_POST['company'] ?? '';
$name    = trim($_POST['name']    ?? '');
$email   = trim($_POST['email']   ?? '');
$subject = trim($_POST['subject'] ?? 'Mensaje del formulario');
$msg     = trim($_POST['message'] ?? '');

// honeypot
if ($hp !== '') {
    echo json_encode(['ok' => true]);
    exit;
}

// validación
if ($name === '' || $email === '' || $msg === '') {
    http_response_code(400);
    echo json_encode(['ok' => false, 'error' => 'Campos obligatorios']);
    exit;
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(['ok' => false, 'error' => 'Email inválido']);
    exit;
}

// preparar cuerpo (evita vacíos)
$safe = fn($v) => nl2br(htmlspecialchars($v, ENT_QUOTES, 'UTF-8'));
$safeName  = $safe($name);
$safeEmail = $safe($email);
$safeSubj  = $safe($subject);
$safeMsg   = $safe($msg);
$host      = $_SERVER['HTTP_HOST'] ?? 'localhost';
$date      = date('d/m/Y H:i');

$bodyHtml = <<<HTML
<!doctype html><html><head><meta charset="utf-8"></head>
<body style="font-family:Arial,Helvetica,sans-serif;color:#11131a;background:#f4f6f8;margin:0;padding:24px">
  <table role="presentation" width="600" cellpadding="0" cellspacing="0" align="center"
         style="width:600px;max-width:100%;background:#fff;border:1px solid #e6e8ef;border-radius:8px">
    <tr><td style="padding:18px 24px;background:#0f1e4d;color:#fff;font-weight:bold;font-size:18px">
      Portfolio Web · Nuevo contacto
    </td></tr>
    <tr><td style="padding:20px 24px;font-size:16px;line-height:1.5">
      <p><strong>Nombre:</strong> {$safeName}</p>
      <p><strong>Email:</strong> {$safeEmail}</p>
      <p><strong>Asunto:</strong> {$safeSubj}</p>
      <p style="margin:16px 0 6px;font-weight:bold;color:#0f1e4d;">Mensaje</p>
      <div style="padding:12px;border:1px solid #e6e8ef;border-radius:6px;background:#fafbfc;">{$safeMsg}</div>
      <p style="margin:16px 0 0;font-size:14px;color:#5c6275;">Enviado el {$date} desde {$host}</p>
    </td></tr>
  </table>
</body></html>
HTML;

$bodyText = "Nuevo contacto\nNombre: $name\nEmail: $email\nAsunto: $subject\n\n$msg";

// envío
$mail = new PHPMailer(true);
try {
    $mail->isSMTP();
    $mail->Host       = $cfg['smtp_host'];
    $mail->SMTPAuth   = true;
    $mail->Username   = $cfg['smtp_user'];
    $mail->Password   = $cfg['smtp_pass'];
    $mail->Port       = (int)$cfg['smtp_port'];
    $mail->CharSet    = 'UTF-8';
    $mail->SMTPSecure = (strtolower($cfg['smtp_secure']) === 'ssl')
        ? PHPMailer::ENCRYPTION_SMTPS
        : PHPMailer::ENCRYPTION_STARTTLS;

    $mail->setFrom($cfg['from_email'], $cfg['from_name']);
    $mail->addAddress($cfg['to_email']);
    $mail->addReplyTo($email, $name);

    $mail->isHTML(true);
    $mail->Subject = ($subject ?: 'Nuevo contacto') . ' — ' . $name;
    $mail->Body    = $bodyHtml;   // <-- ya no está vacío
    $mail->AltBody = $bodyText;   // <-- backup en texto plano

    $mail->send();
    echo json_encode(['ok' => true, 'message' => '¡Gracias! Mensaje enviado.']);
} catch (Exception $e) {
    error_log('Mailer Error: ' . $mail->ErrorInfo);
    http_response_code(500);
    echo json_encode(['ok' => false, 'error' => 'No se pudo enviar el email']);
}
