<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start(); // Start session only if not already started
}

include('../backend/fetch_data_creosales.php'); // Ensure this is included
require_once __DIR__ . '/../backend/connection.php';
require_once __DIR__ . '/../backend/managers/CacheManager.php';
require_once __DIR__ . '/../backend/managers/CustomerManager.php';
require_once __DIR__ . '/../backend/managers/UserManager.php';

// Debugging: Check session data
if (!isset($_SESSION['user']) || !isset($_SESSION['user_type'])) {
    die("Error: User session not found. Session data: " . print_r($_SESSION, true));
}

if (!isset($_SESSION['user_id'])) { // Corrected to check $_SESSION['user_id']
    die("Error: User ID not found in session. Session data: " . print_r($_SESSION, true));
}

// GET USER DATA FROM SESSION
$currentUser_id = $_SESSION['user_id'];
$currentUserType = isset($_SESSION['user_type']) ? $_SESSION['user_type'] : null;

// GET DATA FROM CACHE

// INITIALIZE CACHE MANAGER
$cache = new CacheManager();

// FETCH USER DATA FROM CACHE OR FROM DB
$userFetcher = new UserManager($pdo);
$users = $cache->getOrSet('users', fn() => $userFetcher->getAllUsers(), 300);

// FETCH CUSTOMER DATA FROM CACHE OR FROM DB
$customerFetcher = new CustomerManager($pdo);
$customerFetcher->potentialCustomers = $cache->getOrSet('potentialCustomers', fn() => $customerFetcher->getAllCustomers(), 300);
$potentialCustomers = $customerFetcher->potentialCustomers;

// GET CURRENT USER DATA FROM CACHE
$currentUser = $users[$currentUser_id];

$user = $_SESSION['user'];
$user_id = $_SESSION['user_id']; // Corrected to use $_SESSION['user_id']
$user_type = $_SESSION['user_type'];

include 'views/evaluations_view.php';