<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Dotenv\Dotenv;

require __DIR__ . '/vendor/autoload.php';
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

$mail = new PHPMailer(true);
try {
    $mail->SMTPDebug = 2;
    $mail->Debugoutput = 'html';
    $mail->isSMTP();
    $mail->Host = $_ENV['SMTP_HOST'];
    $mail->SMTPAuth = true;
    $mail->Username = $_ENV['ZOHO_EMAIL'];
    $mail->Password = $_ENV['ZOHO_PASSWORD'];
    $mail->SMTPSecure = $_ENV['SMTP_SECURE'];
    $mail->Port = (int)$_ENV['SMTP_PORT'];

    $mail->setFrom($_ENV['ZOHO_EMAIL'], 'Test');
    $mail->addAddress($_ENV['TO_EMAIL']);
    $mail->Subject = 'SMTP Test';
    $mail->Body = 'Testing Zoho SMTP.';

    $mail->send();
    echo 'Success!';
} catch (Exception $e) {
    echo "Mailer Error: {$mail->ErrorInfo}";
}
