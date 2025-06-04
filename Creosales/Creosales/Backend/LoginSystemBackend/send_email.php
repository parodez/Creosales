<?php
require '../PHPMailer/src/PHPMailer.php';
require '../PHPMailer/src/SMTP.php';
require '../PHPMailer/src/Exception.php';
include('../connection.php'); // Database connection

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

header('Content-Type: application/json'); // Ensure the response is JSON

// Decode JSON input
$data = json_decode(file_get_contents('php://input'), true);

$to = isset($data['to']) ? $data['to'] : null;
$subject = isset($data['subject']) ? $data['subject'] : null;
$body = isset($data['body']) ? $data['body'] : null;

// Use a fixed sender email (must match the SMTP email)
$fromEmail = 'creosalesadmin@creosales.creoteconlinelearning.com';
$fromName = 'Creosales';

if ($to && $subject && $body) {
    $mail = new PHPMailer(true);
    try {
        // SMTP Configuration for HostGator
        $mail->isSMTP();
        $mail->Host = 'gator4119.hostgator.com'; // HostGator SMTP server
        $mail->SMTPAuth = true;
        $mail->Username = $fromEmail; // HostGator email (should match sender)
        $mail->Password = 'V&y]cmP^)fh_'; 
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Email Content
        $mail->setFrom($fromEmail, $fromName);
        $mail->addAddress($to);

        $mail->Subject = $subject;
        $mail->Body = $body;
        $mail->isHTML(true); // Enable HTML email formatting

        $mail->send();
        echo json_encode(['success' => true]);

    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Failed to send email', 'error' => $mail->ErrorInfo]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid data received']);
}
?>
