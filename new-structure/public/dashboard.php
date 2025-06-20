<?php
require_once __DIR__ . '/../backend/connection.php';
require_once __DIR__ . '/../backend/managers/CacheManager.php';
require_once __DIR__ . '/../backend/managers/CustomerManager.php';
require_once __DIR__ . '/../backend/managers/UserManager.php';

session_start();

//* Check if user is logged in
if (!isset($_SESSION['user'])) {
    header('Location: ../backend/LoginSystem/validate_login.php');
    exit();
}

//* GET USER DATA FROM SESSION
$currentUser_id = $_SESSION['user_id'];
$currentUserType = isset($_SESSION['user_type']) ? $_SESSION['user_type'] : null;

//* GET DATA FROM CACHE

//* INITIALIZE CACHE MANAGER
$cache = new CacheManager();

//* FETCH USER DATA FROM CACHE OR FROM DB
$userFetcher = new UserManager($pdo);
$users = $cache->getOrSet('users', fn() => $userFetcher->getAllUsers(), 300);

//* FETCH CUSTOMER DATA FROM CACHE OR FROM DB
$customerFetcher = new CustomerManager($pdo);
$potentialCustomers = $cache->getOrSet('potentialCustomers', fn() => $customerFetcher->getAllCustomers(), 300);

//* GET CURRENT USER DATA FROM CACHE
$currentUser = $users[$currentUser_id];

//* INITIALIZE VALUES

//* DEFINE CONSISTENT COLORS FOR SECTORS
$sectorColors = [
    "School" => "#22758e",
    "Government" => "#4729a6",
    "Sponsor" => "#8c6b70",
    "Industry" => "#832d6d"
];
//* INITIALIZE EVALUATION RESULTS
$evaluationResults = [
    'Passed' => 0,
    'Conditional' => 0,
    'Failed' => 0
];
//* INITIALIZE CLIENTS PER SECTOR
$clientsPerSector = [
    'School' => 0,
    'Government' => 0,
    'Sponsor' => 0,
    'Industry' => 0
];
//* INITIALIZE LATEST EVALUATION
$latestEvaluation = [
    'date' => '2000-01-01',
    'user' => '',
    'user_position' => ''
];
//* INITIALIZE TOTAL CLIENTS
$totalClients = 0;
//* INITIALIZE TOTAL CUSTOMERS EVALUATED BY CURRENT USER
$userTotalEvaluatedCustomers = 0;

//* INITIALIZE VALUES END

//* GET DATA

//* LOOP THROUGH ALL THE POTENTIAL CUSTOMERS
foreach ($potentialCustomers as $potentialCustomer) {
    //* GET NUMBER OF CLIENTS GROUPED PER EVALUATION RESULT
    if (array_key_exists($potentialCustomer->evaluation['result'], $evaluationResults)) {
        $evaluationResults[$potentialCustomer->evaluation['result']] += 1;
    }
    //* GET NUMBER OF CLIENTS PER SECTOR AND OF TOTAL CLIENTS
    if (array_key_exists($potentialCustomer->sector, $clientsPerSector)) {
        $clientsPerSector[$potentialCustomer->sector]++;
        $totalClients++;
    }
    //* GET NUMBER OF CUSTOMERS EVALUATED BY CURRENT USER
    if ($potentialCustomer->user_id == $currentUser->id) {
        $userTotalEvaluatedCustomers++;
    }
    //* GET LAST UPDATED EVALUATION
    if ($potentialCustomer->evaluation['date'] > $latestEvaluation['date']) {
        $latestEvaluation['date'] = $potentialCustomer->evaluation['date'];
        $latestEvaluation['user'] = $potentialCustomer->user_id;
    }
}

//* GET USER DETAILS OF LAST UPDATER
$stmt = $pdo->prepare("SELECT user_firstname, user_lastname, user_department, user_position FROM tbl_user WHERE user_id = ?");
$stmt->execute([$latestEvaluation['user']]);
$row = $stmt->fetch();
if ($row) {
    $latestEvaluation['user'] = $row['user_firstname'] . " " . $row['user_lastname'];
    $latestEvaluation['user_position'] = $row['user_position'];
}

//* GET AVERAGE OF ALL EVALUATION RESULTS
$overallResult = 'Conditional';
if ($evaluationResults['Passed'] >= $evaluationResults['Failed'] && $evaluationResults['Passed'] >= $evaluationResults['Conditional']) {
    $overallResult = 'Passed';
} elseif ($evaluationResults['Failed'] > $evaluationResults['Passed'] && $evaluationResults['Failed'] > $evaluationResults['Conditional']) {
    $overallResult = 'Failed';
}

//* GET DATA END

$evaluationResults_json = json_encode($evaluationResults);

include 'views/dashboard_view.php';