<?php
session_start();
$data = json_decode(file_get_contents('php://input'), true);
$code = $data['code'];

if ($code == $_SESSION['verification_code']) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false]);
}
?>
