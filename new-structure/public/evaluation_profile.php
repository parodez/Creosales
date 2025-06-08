<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start(); 
}
include '../Backend/fetch_data_creosales.php';

if (!isset($_SESSION['user'])) {
    header('Location: ../Backend/LoginSystemBackend/validate_login.php');
    exit();
}


$userId = $_SESSION['user_id'];
$userType = $_SESSION['user_type'];

if ($userType === 0) {
    $totalClientsQuery = "SELECT COUNT(*) as total_clients FROM tbl_potentialcustomer";
    $totalClientsResult = mysqli_query($conn, $totalClientsQuery);
    $totalClientsRow = mysqli_fetch_assoc($totalClientsResult);
    $totalClients = $totalClientsRow['total_clients'];
} else {
    $evaluatedClientsQuery = "SELECT COUNT(DISTINCT e.client_id) as evaluated_clients 
                              FROM tbl_evaluation e
                              JOIN tbl_potentialcustomer cl ON e.client_id = cl.client_id
                              WHERE cl.user_id = ?";
    $stmt = $conn->prepare($evaluatedClientsQuery);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $evaluatedClients = $row['evaluated_clients'];
}


$user = $_SESSION['user'];

include 'views/evaluations_profile_view.php';
?>