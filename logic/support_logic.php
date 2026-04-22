<?php
// Include the database connection and session
include (__DIR__ . '/../includes/conn.php');
include (__DIR__ . '/../includes/session.php');

// Load PHPMailer
require (__DIR__ . '/../phpmailer/PHPMailer.php');
require (__DIR__ . '/../phpmailer/SMTP.php');
require (__DIR__ . '/../phpmailer/Exception.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$message_sent = false;
$error_message = "";

// Ensure user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullname   = $_POST['fullname'] ?? '';
    $phone      = $_POST['phone'] ?? '';
    $address    = $_POST['address'] ?? '';
    $email      = $_POST['email'] ?? '';
    $message    = $_POST['message'] ?? '';
    $created_by = $_SESSION['username'];
    $created_at = date('Y-m-d H:i:s');

    $stmt = $conn->prepare("INSERT INTO support (fullname, phone, address, email, message, created_by, created_at) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssss", $fullname, $phone, $address, $email, $message, $created_by, $created_at);

    if ($stmt->execute()) {
        $mail = new PHPMailer(true);
        try {
            // Admin Email - SMTP settings
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'your-email@gmail.com';
            $mail->Password   = 'your-app-password';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;

            $mail->setFrom('your-email@gmail.com', 'LokiMart Support');
            $mail->addAddress('admin@example.com');
            $mail->addReplyTo($email, $fullname);

            $mail->isHTML(true);
            $mail->Subject = "New Support Message from $fullname";
            $mail->Body    = "
                <h3>Support Inquiry</h3>
                <p><strong>Name:</strong> $fullname</p>
                <p><strong>Phone:</strong> $phone</p>
                <p><strong>Address:</strong> $address</p>
                <p><strong>Email:</strong> $email</p>
                <p><strong>Message:</strong><br>$message</p>
                <p><strong>Submitted by:</strong> $created_by on $created_at</p>
            ";
            $mail->send();

            // User Confirmation Email
            $userMail = new PHPMailer(true);
            $userMail->isSMTP();
            $userMail->Host       = 'smtp.gmail.com';
            $userMail->SMTPAuth   = true;
            $userMail->Username   = 'your-email@gmail.com';
            $userMail->Password   = 'your-app-password';
            $userMail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $userMail->Port       = 587;

            $userMail->setFrom('your-email@gmail.com', 'LokiMart Support');
            $userMail->addAddress($email, $fullname);

            $userMail->isHTML(true);
            $userMail->Subject = "LokiMart Support - We've received your message";
            $userMail->Body    = "
                <p>Dear $fullname,</p>
                <p>Thank you for contacting <strong>LokiMart Support</strong>. We’ve received your message and our team will get back to you shortly.</p>
                <p><strong>Your Message:</strong><br>$message</p>
                <p>If this wasn't you, please let us know immediately.</p>
                <p>Best regards,<br>The LokiMart Team</p>
            ";
            $userMail->send();

            $message_sent = true;

        } catch (Exception $e) {
            $error_message = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }

    } else {
        $error_message = "Failed to submit support message.";
    }

    $stmt->close();
    $conn->close();
}