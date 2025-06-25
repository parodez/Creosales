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

// TODO: REMOVE ONCE DONE DEBUGGING
$sessionData = json_encode($_SESSION);

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
$customerFetcher->potentialCustomers = $cache->getOrSet('potentialCustomers', fn() => $customerFetcher->getAllCustomers(), 300);

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
//* INITIALIZE LATEST EVALUATION

// GET DATA

$passedCustomersCount = $customerFetcher->getEvaluationResultCount('Passed');
$conditionalCustomersCount = $customerFetcher->getEvaluationResultCount('Conditional');
$failedCustomersCount = $customerFetcher->getEvaluationResultCount('Failed');

$customersInSchool = $customerFetcher->getCustomerInSectorCount('School');
$customersInGovernment = $customerFetcher->getCustomerInSectorCount('Government');
$customersInSponsor = $customerFetcher->getCustomerInSectorCount('Sponsor');
$customersInIndustry = $customerFetcher->getCustomerInSectorCount('Industry');

$averageCustomerEvaluation = $customerFetcher->getAverageCustomerEvaluation();

$latestEvaluation = $customerFetcher->getLatestEvaluation();

include 'views/dashboard_view.php';