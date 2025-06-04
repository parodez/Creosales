<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start(); // Start session only if not already started
}

include('../backend/fetch_data_creosales.php'); // Ensure this is included

// Debugging: Check session data
if (!isset($_SESSION['user']) || !isset($_SESSION['user_type'])) {
    die("Error: User session not found. Session data: " . print_r($_SESSION, true));
}

if (!isset($_SESSION['user_id'])) { // Corrected to check $_SESSION['user_id']
    die("Error: User ID not found in session. Session data: " . print_r($_SESSION, true));
}

$user = $_SESSION['user'];
$user_id = $_SESSION['user_id']; // Corrected to use $_SESSION['user_id']
$user_type = $_SESSION['user_type'];

include 'views/passedCustomers_view.php';
?>