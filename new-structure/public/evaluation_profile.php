<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// include '../backend/fetch_data_creosales.php';
require_once __DIR__ . '/../backend/connection.php';
require_once __DIR__ . '/../backend/managers/CacheManager.php';
require_once __DIR__ . '/../backend/managers/CustomerManager.php';
require_once __DIR__ . '/../backend/managers/UserManager.php';

if (!isset($_SESSION['user'])) {
    header('Location: ../Backend/LoginSystemBackend/validate_login.php');
    exit();
}

// //* GET USER DATA FROM SESSION
// $currentUser_id = $_SESSION['user_id'];
// $currentUserType = isset($_SESSION['user_type']) ? $_SESSION['user_type'] : null;

// //* GET DATA FROM CACHE

// //* INITIALIZE CACHE MANAGER
// $cache = new CacheManager();

// //* FETCH USER DATA FROM CACHE OR FROM DB
// $userFetcher = new UserManager($pdo);
// $users = $cache->getOrSet('users', fn() => $userFetcher->getAllUsers(), 300);

// //* FETCH CUSTOMER DATA FROM CACHE OR FROM DB
// $customerFetcher = new CustomerManager($pdo);
// $customerFetcher->potentialCustomers = $cache->getOrSet('potentialCustomers', fn() => $customerFetcher->getAllCustomers(), 300);
// $potentialCustomers = $customerFetcher->potentialCustomers;

// $potentialCustomer = $potentialCustomers[$_GET['id']];

// $eval_summary = [
//     'evaluation_id' => $potentialCustomer->evaluation['id'],
//     'evaluation_rating' => $potentialCustomer->evaluation['rating'],
//     'evaluation_result' => $potentialCustomer->evaluation['result'],
//     'evaluation_date' => $potentialCustomer->evaluation['date']
// ];

//* FETCH EVALUATION DATA
// $stmt = $pdo->prepare("SELECT cr.criteria_criterion,
//                           r.rating_id,
//                           r.rating_score, 
//                           e.evaluation_rating, 
//                           e.evaluation_result, 
//                           e.evaluation_date, 
//                           r.rating_notes 
//                    FROM tbl_evaluation e
//                    INNER JOIN tbl_rating r ON e.evaluation_id = r.evaluation_id
//                    INNER JOIN tbl_criteria cr ON r.criteria_id = cr.criteria_id
//                    WHERE e.potentialcustomer_id = ?
//                    ORDER BY cr.criteria_id ASC");
// $stmt->execute([$potentialCustomer->id]);
// while ($row = $stmt->fetch()) {
//     $row['rating_score'] = number_format((float)$row['rating_score'], 1, '.', '');
//     $evaluations[] = $row;
// }

$user = $_SESSION['user'];

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
$potentialCustomers = $customerFetcher->potentialCustomers;
$potentialCustomer = $potentialCustomers[$_GET['id']];

$imagesData = $customerFetcher->getImageData($_GET['id']);

$eval_summary = $customerFetcher->getCustomerEvalSummary($_GET['id']);

$evaluations = $customerFetcher->getCustomerEvaluation($_GET['id']);

include 'views/evaluations_profile_view.php';