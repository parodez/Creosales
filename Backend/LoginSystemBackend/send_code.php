<?php
include('../connection.php'); // Include database connection
header('Content-Type: application/json'); // Ensure the response is JSON

$data = json_decode(file_get_contents('php://input'), true);
$username = $data['username'];
$email = $data['email'];
$from = isset($data['from']) ? $data['from'] : 'default@example.com'; // Admin's email address

// Check if email matches the specific username
$query = "SELECT credentials_email FROM tbl_credentials WHERE credentials_username = ? AND credentials_email = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ss", $username, $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Generate verification code
    $code = rand(100000, 999999);
    
    // Store code in session
    session_start();
    $_SESSION['verification_code'] = $code;
    $_SESSION['email'] = $email;

    // HTML email template
    $email_body = "
    <div style='background-color: #1d1d1d; padding: 20px; text-align: center; font-family: Arial, sans-serif; color: #ffffff;'>
        <div style='max-width: 450px; margin: auto; background: #121212; border-radius: 10px; padding: 20px;'>
            <h2 style='color: #8b5cf6;'>Creosales</h2>
            <p style='font-size: 16px;'>Your Creosales verification code is:</p>
            <h1 style='font-size: 36px; margin: 10px 0; color: #ffffff;'>$code</h1>
            <p style='font-size: 14px; color: #bbbbbb;'>You have requested to reset the password for your Creosales account. Please use the verification code below to proceed with changing your password.</p>
            <p style='font-size: 14px; color: #777777;'>If you are not trying to changed your password on Creosales account, you may ignore this email.</p>
            <hr style='border: 1px solid #333;' />
            <p style='font-size: 12px; color: #555;'>© 2025 Creosales Philippines<br>
            #177-A Technology Avenue Laguna Technopark Biñan, Laguna, Philippines.</p>
        </div>
    </div>";

    // Send email using PHPMailer
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://creosales.creoteconlinelearning.com/Backend/LoginSystemBackend/send_email.php");
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
        'to' => $email,
        'subject' => 'Your Verification Code',
        'body' => $email_body,
        'from' => $from,
        'isHtml' => true // Ensure the email is sent as HTML
    ]));
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($http_code == 200) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to send email', 'response' => $response]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Email not found or does not match the username']);
}
?>
