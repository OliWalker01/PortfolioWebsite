<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fname = $_POST['fname'];
    $sname = $_POST['sname'];
    $company = $_POST['company'];
    $number = $_POST['pnumber'];
    $email = $_POST['email'];
    $subject = $_POST['subject'];

    // Email address to send the form submission
    $email_to = "oliver.walker01@hotmail.co.uk";
    $email_subject = "New Contact Form Submission";

    // Construct the email message
    $email_message = "Form details:\n\n";
    $email_message .= "First Name: " . $fname . "\n";
    $email_message .= "Second Name: " . $sname . "\n";
    $email_message .= "Company: " . $company . "\n";
    $email_message .= "Phone Number: " . $number . "\n";
    $email_message .= "Email: " . $email . "\n";
    $email_message .= "Subject: " . $subject . "\n";

    // Additional headers
    $headers = "From: " . $email;

    // Send the email
    if (mail($email_to, $email_subject, $email_message, $headers)) {
        echo "<p>Thank you for contacting me. Your message has been sent successfully.</p>";
    } else {
        echo "<p>Sorry, there was an error sending your message. Please try again later.</p>";
    }
}
?>