<?php
include('../connection.php');
session_start();

// Decode the JSON input
$data = json_decode(file_get_contents('php://input'), true);
$newPassword = $data['newPassword'];
$email = $_SESSION['email'];

// Hash the new password
$hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);

// Update password in the database
$query = "UPDATE tbl_credentials SET credentials_password = ? WHERE credentials_email = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ss", $hashedPassword, $email);
if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false]);
}
?>
