<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Dotenv\Dotenv;

require __DIR__ . '/vendor/autoload.php';

// Load .env variables
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

// ====== 1️⃣ Verify Google reCAPTCHA ======
$recaptcha = $_POST['g-recaptcha-response'] ?? '';
$secret = trim($_ENV['RECAPTCHA_SECRET']);

if (!$recaptcha) {
    exit('Please complete the CAPTCHA.');
}

// Verify with Google
$response = file_get_contents(
    "https://www.google.com/recaptcha/api/siteverify?secret={$secret}&response={$recaptcha}"
);
$responseKeys = json_decode($response, true);

if (empty($responseKeys['success']) || $responseKeys['success'] !== true) {
    exit('CAPTCHA verification failed. Please try again.');
}

// ====== 2️⃣ Get and sanitize form data ======
$fname   = htmlspecialchars(trim($_POST['fname'] ?? ''));
$sname   = htmlspecialchars(trim($_POST['sname'] ?? ''));
$company = htmlspecialchars(trim($_POST['company'] ?? ''));
$pnumber = htmlspecialchars(trim($_POST['pnumber'] ?? ''));
$email   = filter_var(trim($_POST['email'] ?? ''), FILTER_VALIDATE_EMAIL);
$subject = htmlspecialchars(trim($_POST['subject'] ?? ''));

if (!$fname || !$sname || !$pnumber || !$email || !$subject) {
    exit('Please fill in all required fields.');
}

// ====== 3️⃣ Send emails with PHPMailer ======
$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host       = trim($_ENV['SMTP_HOST']);
    $mail->SMTPAuth   = true;
    $mail->Username   = trim($_ENV['ICLOUD_EMAIL']);
    $mail->Password   = trim($_ENV['ICLOUD_APP_PASSWORD']);
    $mail->SMTPSecure = trim($_ENV['SMTP_SECURE']);
    $mail->Port       = (int)$_ENV['SMTP_PORT'];
    
    // optional: stricter SSL verification
    $mail->SMTPOptions = [
        'ssl' => [
            'verify_peer'       => true,
            'verify_peer_name'  => true,
            'allow_self_signed' => false,
        ],
    ];

    // use your alias as the visible From address
    $mail->setFrom($_ENV['SEND_FROM_ADDRESS'], 'Oliver Walker Contact Form');
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

    // Second email to the user (confirmation)
    $mail->clearAddresses();
    $mail->setFrom(trim($_ENV['ZOHO_EMAIL']), 'Oliver Walker Contact Form');
    $mail->addAddress($email);
    $mail->Subject = "Thank you for reaching out!";
    $mail->Body    = "<h3>Contact Form Submission Confirmation</h3>
                      <p>Hi $fname!</p>
                      <p>Thank you for reaching out! This is an email to confirm that your request has been received. I will reply as soon as possible.</p>
                      <p>Many thanks, Oliver</p>";
    $mail->AltBody = "Hi $fname!\n\nThank you for reaching out! This is an email to confirm that your request has been received. I will reply as soon as possible.\n\nMany thanks, Oliver";

    $mail->send();

    // Redirect on success
    header('Location: ../contactmesuccess.html');
    exit;

} catch (Exception $e) {
    echo "Mailer Error: {$mail->ErrorInfo}";
}
?>