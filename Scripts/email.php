<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Dotenv\Dotenv;

require __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Get form data safely
$fname = htmlspecialchars($_POST['fname'] ?? '');
$sname = htmlspecialchars($_POST['sname'] ?? '');
$company = htmlspecialchars($_POST['company'] ?? '');
$pnumber = htmlspecialchars($_POST['pnumber'] ?? '');
$email = htmlspecialchars($_POST['email'] ?? '');
$subject = htmlspecialchars($_POST['subject'] ?? '');

$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host       = $_ENV['SMTP_HOST'];
    $mail->SMTPAuth   = true;
    $mail->Username   = $_ENV['ZOHO_EMAIL'];
    $mail->Password   = $_ENV['ZOHO_PASSWORD'];
    $mail->SMTPSecure = $_ENV['SMTP_SECURE'];
    $mail->Port       = $_ENV['SMTP_PORT'];

    $mail->setFrom($_ENV['ZOHO_EMAIL'], "Website Contact Form");
    $mail->addAddress($_ENV['TO_EMAIL']);
    $mail->addReplyTo($email, "$fname $sname");

    $mail->isHTML(true);
    $mail->Subject = "Contact Form: $fname $sname";
    $mail->Body    = "<h3>Contact Form Submission</h3>
                      <p><strong>Name:</strong> $fname $sname</p>
                      <p><strong>Company:</strong> $company</p>
                      <p><strong>Phone:</strong> $pnumber</p>
                      <p><strong>Email:</strong> $email</p>
                      <p><strong>Message:</strong><br>$subject</p>";

    $mail->AltBody = "Name: $fname $sname\nCompany: $company\nPhone: $pnumber\nEmail: $email\nMessage: $subject";

    $mail->send();
    echo 'Message sent successfully!';
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}