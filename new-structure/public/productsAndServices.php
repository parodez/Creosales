<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start(); // Start session only if not already started
}

// include('../backend/fetch_data_creosales.php'); 
// require_once __DIR__ . '/../backend/connection.php';
// require_once __DIR__ . '/../backend/managers/CacheManager.php';
// require_once __DIR__ . '/../backend/managers/CustomerManager.php';
// require_once __DIR__ . '/../backend/managers/UserManager.php';

$url = 'http://localhost/Creosales/Creosales/new-structure/backend/api/product/';
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);

if (curl_errno($ch)) {
    error_log('cURL Error: ' . curl_error($ch));
    curl_close($ch);
    return false;
}
curl_close($ch);

$data = json_decode($response, true);

$products = $data['data'];

include 'views/productsAndServices_view.php';