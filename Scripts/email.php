<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Dotenv\Dotenv;

require __DIR__ . '/vendor/autoload.php';

// Load .env variables
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Get and sanitize form data
$fname   = htmlspecialchars(trim($_POST['fname'] ?? ''));
$sname   = htmlspecialchars(trim($_POST['sname'] ?? ''));
$company = htmlspecialchars(trim($_POST['company'] ?? ''));
$pnumber = htmlspecialchars(trim($_POST['pnumber'] ?? ''));
$email   = filter_var(trim($_POST['email'] ?? ''), FILTER_VALIDATE_EMAIL);
$subject = htmlspecialchars(trim($_POST['subject'] ?? ''));

if (!$fname || !$sname || !$pnumber || !$email || !$subject) {
    exit('Please fill in all required fields.');
}

$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host       = trim($_ENV['SMTP_HOST']);
    $mail->SMTPAuth   = true;
    $mail->Username   = trim($_ENV['ZOHO_EMAIL']);
    $mail->Password   = trim($_ENV['ZOHO_PASSWORD']);
    $mail->SMTPSecure = trim($_ENV['SMTP_SECURE']);
    $mail->Port       = (int)$_ENV['SMTP_PORT'];

    $mail->SMTPOptions = [
        'ssl' => [
            'verify_peer'       => true,
            'verify_peer_name'  => true,
            'allow_self_signed' => false,
        ],
    ];

    $mail->setFrom(trim($_ENV['ZOHO_EMAIL']), 'Website Contact Form');
    $mail->addAddress(trim($_ENV['TO_EMAIL']));
    $mail->addReplyTo($email, "$fname $sname");

    $mail->isHTML(true);
    $mail->Subject = "Contact Form Submission: $fname $sname";
    $mail->Body    = "<h3>Contact Form Submission</h3>
                      <p><strong>Name:</strong> $fname $sname</p>
                      <p><strong>Company:</strong> $company</p>
                      <p><strong>Phone:</strong> $pnumber</p>
                      <p><strong>Email:</strong> $email</p>
                      <p><strong>Message:</strong><br>$subject</p>";

    $mail->AltBody = "Name: $fname $sname\nCompany: $company\nPhone: $pnumber\nEmail: $email\nMessage: $subject";

    $mail->send();

    // Redirect on success
    header('Location: ../contactmesuccess.html');
    exit;

} catch (Exception $e) {
    echo "Mailer Error: {$mail->ErrorInfo}";
}
