<?php
include('../backend/connection.php');
session_start();

// Check if user is logged in
if (!isset($_SESSION['user'])) {
    header('Location: ../backend/LoginSystem/validate_login.php');
    exit();
}

$user = $_SESSION['user'];
$user_type = isset($_SESSION['user_type']) ? $_SESSION['user_type'] : null; // Ensure user_type is set


// Fetch user details
$userId = $_SESSION['user_id'];
$userQuery = "SELECT user_firstname, user_lastname, user_department, user_position FROM tbl_user WHERE user_id = ?";
$userStmt = $conn->prepare($userQuery);
$userStmt->bind_param("i", $userId);
$userStmt->execute();
$userResult = $userStmt->get_result();
$userDetails = $userResult->fetch_assoc();

// Adjust total clients count based on user type
if ($user_type === 0) {
    // Admin: Fetch all clients
    $totalClientsQuery = "SELECT COUNT(*) as total_clients FROM tbl_client";
    $totalClientsResult = mysqli_query($conn, $totalClientsQuery);
    $totalClientsRow = mysqli_fetch_assoc($totalClientsResult);
    $totalClients = $totalClientsRow['total_clients'];
} else {
    // Regular user: Fetch only clients evaluated by the logged-in user
    $totalClientsQuery = "SELECT COUNT(DISTINCT e.client_id) as total_clients 
                          FROM tbl_evaluation e
                          JOIN tbl_client cl ON e.client_id = cl.client_id
                          WHERE cl.user_id = ?";
    $totalClientsStmt = $conn->prepare($totalClientsQuery);
    $totalClientsStmt->bind_param("i", $userId);
    $totalClientsStmt->execute();
    $totalClientsResult = $totalClientsStmt->get_result();
    $totalClientsRow = $totalClientsResult->fetch_assoc();
    $evaluatedClients = $totalClientsRow['total_clients'];
}

// Query to get the average rating by sector
// prpb
$averageRatingBySector_query = "SELECT s.sector_name, AVG(e.evaluation_rating) as avg_rating 
          FROM tbl_evaluation e
          JOIN tbl_client cl ON e.client_id = cl.client_id
          JOIN tbl_sector s ON cl.sector_id = s.sector_id
          GROUP BY s.sector_name
          ORDER BY s.sector_name";

$averageRatingBySector_result = mysqli_query($conn, $averageRatingBySector_query);

$averageRatingBySector_data = [];
while ($row = mysqli_fetch_assoc($averageRatingBySector_result)) {
    $averageRatingBySector_data[$row['sector_name']] = round($row['avg_rating'], 1);
}

$averageRatingBySector = json_encode($averageRatingBySector_data);

// Query to get the total average rating of all clients
$totalAvgQuery = "SELECT AVG(evaluation_rating) as total_avg_rating FROM tbl_evaluation";
$totalAvgResult = mysqli_query($conn, $totalAvgQuery);
$totalAvgRow = mysqli_fetch_assoc($totalAvgResult);
$totalAvgRating = round($totalAvgRow['total_avg_rating'], 1);

// Query to get the last update date and the user who made the update
$lastUpdateQuery = "SELECT MAX(e.evaluation_date) as last_update, e.client_id, cl.user_id 
                    FROM tbl_evaluation e
                    JOIN tbl_client cl ON e.client_id = cl.client_id";
$lastUpdateResult = mysqli_query($conn, $lastUpdateQuery);
$lastUpdateRow = mysqli_fetch_assoc($lastUpdateResult);
$lastUpdateDate = $lastUpdateRow['last_update'] ? date('M d, Y', strtotime($lastUpdateRow['last_update'])) : 'No updates found';
$lastUpdateUserId = $lastUpdateRow['user_id'];

// Query to get the user details who made the last update
$userQuery = "SELECT user_firstname, user_lastname, user_position 
              FROM tbl_user 
              WHERE user_id = $lastUpdateUserId";
$userResult = mysqli_query($conn, $userQuery);
$userRow = mysqli_fetch_assoc($userResult);
$lastUpdateUserName = $userRow ? $userRow['user_firstname'] . ' ' . $userRow['user_lastname'] : 'Unknown';
$lastUpdateUserPosition = $userRow ? $userRow['user_position'] : 'Unknown';

// Query to get the number of clients evaluated on the last update date
$clientsEvaluatedQuery = "SELECT COUNT(*) as clients_evaluated 
                          FROM tbl_evaluation 
                          WHERE evaluation_date = '{$lastUpdateRow['last_update']}'";
$clientsEvaluatedResult = mysqli_query($conn, $clientsEvaluatedQuery);
$clientsEvaluatedRow = mysqli_fetch_assoc($clientsEvaluatedResult);
$clientsEvaluatedCount = $clientsEvaluatedRow['clients_evaluated'] ?? 0;

// CLIENTS START
// prpb

// Queries the number of clients per sector
$query = "SELECT s.sector_name, COUNT(cl.client_id) as client_count
          FROM tbl_client cl
          JOIN tbl_sector s ON cl.sector_id = s.sector_id
          GROUP BY s.sector_name
          ORDER BY FIELD(s.sector_name, 'School', 'Government', 'Sponsor', 'Industry')";
$result = mysqli_query($conn, $query);

// Initialize clientData values
$clientData = [
    'School' => 0,
    'Government' => 0,
    'Sponsor' => 0,
    'Industry' => 0
];

// Stores the count of each client per sector
$totalClients = 0;
while ($row = mysqli_fetch_assoc($result)) {
    $clientData[$row['sector_name']] = $row['client_count'];
    $totalClients += $row['client_count'];
}

// CLIENTS END

// EVALUATIONS START
// prpb

// Queries the count of instances of each evaluation_result
$evaluationResultQuery = "SELECT evaluation_result, COUNT(*) as count 
                          FROM tbl_evaluation 
                          GROUP BY evaluation_result";
$evaluationResultResult = mysqli_query($conn, $evaluationResultQuery);

// Initialize evaluationResults values
$evaluationResults = [
    'Passed'=>0,
    'Conditional'=>0,
    'Failed'=>0
];

// Stores the count of each instance per result
while ($row = mysqli_fetch_assoc($evaluationResultResult)) {
    $evaluationResults[$row['evaluation_result']] = $row['count'];
}

$evaluationResults_json = json_encode($evaluationResults);

// EVALUATIONS END

// Determine overall client result
$overallResult = 'Conditional';

if ($evaluationResults['Passed'] > $evaluationResults['Failed'] && $evaluationResults['Passed'] > $evaluationResults['Conditional']) {
    $overallResult = 'Passed';
} elseif ($evaluationResults['Failed'] > $evaluationResults['Passed'] && $evaluationResults['Failed'] > $evaluationResults['Conditional']) {
    $overallResult = 'Failed';
}

$evaluationCounts = [];

// Fetch the number of clients evaluated by the logged-in user
$evaluationCountQuery = "SELECT s.sector_name, COUNT(e.evaluation_id) as evaluated_count
                         FROM tbl_evaluation e
                         JOIN tbl_client cl ON e.client_id = cl.client_id
                         JOIN tbl_sector s ON cl.sector_id = s.sector_id
                         WHERE cl.user_id = ?
                         GROUP BY s.sector_name";
$evaluationCountStmt = $conn->prepare($evaluationCountQuery);
$evaluationCountStmt->bind_param("i", $_SESSION['user_id']);
$evaluationCountStmt->execute();
$evaluationCountResult = $evaluationCountStmt->get_result();

while ($row = $evaluationCountResult->fetch_assoc()) {
    $evaluationCounts[$row['sector_name']] = $row['evaluated_count'];
}

// Fetch all clients for the logged-in user for additional debugging
$clientQuery = "SELECT * FROM tbl_client WHERE user_id = ?";
$clientStmt = $conn->prepare($clientQuery);
$clientStmt->bind_param("i", $_SESSION['user_id']);
$clientStmt->execute();
$clientResult = $clientStmt->get_result();

// Define consistent colors for sectors
$sectorColors = [
    "School" => "#22758e",
    "Government" => "#4729a6",
    "Sponsor" => "#8c6b70",
    "Industry" => "#832d6d"
];

include 'views/dashboard_view.php';