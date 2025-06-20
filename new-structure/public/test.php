<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user'])) {
    header('Location: ../Backend/LoginSystemBackend/validate_login.php');
    exit();
}

require_once __DIR__ . '/../backend/connection.php';
require_once __DIR__ . '/../backend/managers/CacheManager.php';
require_once __DIR__ . '/../backend/managers/CustomerManager.php';
require_once __DIR__ . '/../backend/managers/UserManager.php';

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

foreach ($potentialCustomers as $potentialCustomer) {
    echo $potentialCustomer->name . '<br>';
    echo $potentialCustomer->location->fullAddress . '<br>';
    echo $potentialCustomer->location->municipality . '<br>';
    echo $potentialCustomer->location->province . '<br>';
    echo $potentialCustomer->location->region['name'] . ' - ' . $potentialCustomer->location->region['description'] . '<br>';
    echo '<br>';
}