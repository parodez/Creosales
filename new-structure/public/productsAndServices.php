<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (session_status() === PHP_SESSION_NONE) {
    session_start(); // Start session only if not already started
}



$url = 'http://localhost:8080/Creosales/new-structure/backend/api/product/';
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
if (curl_errno($ch)) {
    error_log('cURL Error: ' . curl_error($ch));
    die("Error fetching data from product API" . curl_error($ch));
    curl_close($ch);
    
}
curl_close($ch);
$data = json_decode($response, true);
$products = $data['data'];

$url = 'http://localhost:8080/Creosales/new-structure/backend/api/service/';
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
$services = $data['data'];

require_once __DIR__ . '/views/productsAndServices_view.php';