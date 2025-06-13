<?php
require_once __DIR__ . '/../backend/connection.php';
// include('../backend/connection.php');
session_start();
include '../backend/fetch_data_creosales.php';

require_once __DIR__ . '/../backend/managers/CacheManager.php';
require_once __DIR__ . '/../backend/managers/CustomerManager.php';

// Check if user is logged in
if (!isset($_SESSION['user'])) {
    header('Location: ../backend/LoginSystem/validate_login.php');
    exit();
}

$user = $_SESSION['user'];
$user_type = isset($_SESSION['user_type']) ? $_SESSION['user_type'] : null; // Ensure user_type is set


// Fetch user details
// $userId = $_SESSION['user_id'];
// $userQuery = "SELECT user_firstname, user_lastname, user_department, user_position FROM tbl_user WHERE user_id = ?";
// $userStmt = $conn->prepare($userQuery);
// $userStmt->bind_param("i", $userId);
// $userStmt->execute();
// $userResult = $userStmt->get_result();
// $userDetails = $userResult->fetch_assoc();

// Adjust total clients count based on user type
if ($user_type === 0) {
    // Admin: Fetch all clients
    $totalClientsQuery = "SELECT COUNT(*) as total_clients FROM tbl_potentialcustomer";
    $totalClientsResult = mysqli_query($conn, $totalClientsQuery);
    $totalClientsRow = mysqli_fetch_assoc($totalClientsResult);
    $totalClients = $totalClientsRow['total_clients'];
} else {
    // Regular user: Fetch only clients evaluated by the logged-in user
    $totalClientsQuery = "SELECT COUNT(DISTINCT e.potentialcustomer_id) as total_clients 
                          FROM tbl_evaluation e
                          JOIN tbl_potentialcustomer cl ON e.potentialcustomer_id = cl.potentialcustomer_id
                          WHERE cl.user_id = ?";
    $totalClientsStmt = $conn->prepare($totalClientsQuery);
    $totalClientsStmt->bind_param("i", $userId);
    $totalClientsStmt->execute();
    $totalClientsResult = $totalClientsStmt->get_result();
    $totalClientsRow = $totalClientsResult->fetch_assoc();
    $evaluatedClients = $totalClientsRow['total_clients'];
}

// DEFINE CONSISTENT COLORS FOR SECTORS
$sectorColors = [
    "School" => "#22758e",
    "Government" => "#4729a6",
    "Sponsor" => "#8c6b70",
    "Industry" => "#832d6d"
];

// GET DATA FROM CACHE
// UPDATE CACHE IF OUTDATED
$cache = new CacheManager();
$fetcher = new CustomerManager($pdo);
$potentialCustomers = $cache->getOrSet('potentialCustomers', fn() => $fetcher->getAllCustomers(), 300);

// INITIALIZE EVALUATION RESULTS
$evaluationResults = [
    'Passed' => 0,
    'Conditional' => 0,
    'Failed' => 0
];
// INITIALIZE CLIENTS PER SECTOR
$clientsPerSector = [
    'School' => 0,
    'Government' => 0,
    'Sponsor' => 0,
    'Industry' => 0
];
// INITIALIZE TOTAL CLIENTS
$totalClients = 0;
// INITIALIZE LATEST EVALUATION
$latestEvaluation = [
    'date' => '2000-01-01',
    'user' => '',
    'user_position' => ''
];

// GET USER DETAILS
$userId = $_SESSION['user_id'];
$query = "SELECT user_firstname, user_lastname, user_department, user_position FROM tbl_user WHERE user_id = ?";
$stmt = $pdo->prepare($query);
$stmt->execute([$userId]);
$userDetails = $stmt->fetch();

echo json_encode($userDetails);

// LOOP THROUGH ALL THE POTENTIAL CUSTOMERS
foreach($potentialCustomers as $potentialCustomer) {
    // GET NUMBER OF CLIENTS GROUPED PER EVALUATION RESULT
    if (array_key_exists($potentialCustomer->evaluation->result, $evaluationResults)) {
        $evaluationResults[$potentialCustomer->evaluation->result] += 1;
    }
    // GET NUMBER OF CLIENTS PER SECTOR AND OF TOTAL CLIENTS
    if (array_key_exists($potentialCustomer->sector, $clientsPerSector)) {
        $clientsPerSector[$potentialCustomer->sector] += 1;
        $totalClients += 1;
    }
    // GET LAST UPDATED EVALUATION
    if ($potentialCustomer->evaluation->date > $latestEvaluation['date']) {
        $latestEvaluation['date'] = $potentialCustomer->evaluation->date;
        $latestEvaluation['user'] = $potentialCustomer->user_id;
    }
}

// GET USER DETAILS OF LAST UPDATER
$stmt = $pdo->prepare( "SELECT user_firstname, user_lastname, user_department, user_position FROM tbl_user WHERE user_id = ?");
$stmt->execute([$latestEvaluation['user']]);
$row = $stmt->fetch();
if ($row) {
    $latestEvaluation['user'] = $row['user_firstname'] . " " . $row['user_lastname'];
    $latestEvaluation['user_position'] = $row['user_position'];
}

// GET AVERAGE OF ALL EVALUATION RESULTS
$overallResult = 'Conditional';
if ($evaluationResults['Passed'] >= $evaluationResults['Failed'] && $evaluationResults['Passed'] >= $evaluationResults['Conditional']) {
    $overallResult = 'Passed';
} elseif ($evaluationResults['Failed'] > $evaluationResults['Passed'] && $evaluationResults['Failed'] > $evaluationResults['Conditional']) {
    $overallResult = 'Failed';
}

$evaluationResults_json = json_encode($evaluationResults);

include 'views/dashboard_view.php';