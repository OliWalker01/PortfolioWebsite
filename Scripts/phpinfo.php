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
    $mail->Host       = 'smtp.zoho.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'info@oliverwalkerportfolio.co.uk';  // Full email
    $mail->Password   = 'pxPh1AJ6ezv7';
    $mail->SMTPSecure = 'ssl'; // Port 465
    $mail->Port       = 465;

    $mail->setFrom($_ENV['ZOHO_EMAIL'], 'Test');
    $mail->addAddress($_ENV['TO_EMAIL']);
    $mail->Subject = 'SMTP Test';
    $mail->Body = 'Testing Zoho SMTP.';

    $mail->send();
    echo 'Success!';
} catch (Exception $e) {
    echo "Mailer Error: {$mail->ErrorInfo}";
}
