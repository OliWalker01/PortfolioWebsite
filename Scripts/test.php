<?php
// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Test PHP execution
echo "PHP is working!";

// Optional: test sending a basic email (replace with your email)
$to = "your@email.com";
$subject = "PHP Test Email";
$message = "This is a test email from PHP!";
$headers = "From: test@yourdomain.com";

if(mail($to, $subject, $message, $headers)){
    echo "<br>Email sent successfully!";
} else {
    echo "<br>Email could not be sent.";
}
?>
