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
    $mail->AuthType   = 'LOGIN';
    $mail->Username   = trim($_ENV['ZOHO_EMAIL']);
    $mail->Password   = trim($_ENV['ZOHO_PASSWORD']);
    $mail->SMTPSecure = 'ssl';
    $mail->Port       = 465;

    $mail->SMTPOptions = [
        'ssl' => [
            'verify_peer'       => false,
            'verify_peer_name'  => false,
            'allow_self_signed' => true,
        ],
    ];

    $mail->setFrom(trim($_ENV['ZOHO_EMAIL']), 'Test');
    $mail->addAddress(trim($_ENV['TO_EMAIL']));
    $mail->Subject = 'SMTP Test';
    $mail->Body = 'Testing Zoho SMTP.';

    $mail->send();
    echo 'Success!';
} catch (Exception $e) {
    echo "Mailer Error: {$mail->ErrorInfo}";
}