<?php
// Enable strict error reporting (disable in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Configuration
$toEmail   = "krrishna1434@gmail.com";
$fromEmail = "info@newsthetruth.com";
$fromName  = "News The Truth";
$subject   = "Mail from Enquiry Form";
$message   = "This is a test enquiry email.";

// Validate email addresses
if (!filter_var($toEmail, FILTER_VALIDATE_EMAIL)) {
    die("Invalid recipient email address.");
}

if (!filter_var($fromEmail, FILTER_VALIDATE_EMAIL)) {
    die("Invalid sender email address.");
}

// Email headers
$headers  = "MIME-Version: 1.0\r\n";
$headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
$headers .= "From: {$fromName} <{$fromEmail}>\r\n";
$headers .= "Reply-To: {$fromEmail}\r\n";
$headers .= "X-Mailer: PHP/" . phpversion();

// Send mail
$mailSent = mail($toEmail, $subject, $message, $headers);

// Result
if ($mailSent) {
    echo "✅ Mail has been sent successfully.";
} else {
    echo "❌ Failed to send mail. Please try again later.";
}
