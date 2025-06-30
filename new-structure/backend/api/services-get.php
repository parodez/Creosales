<?php

header('Content-Type: application/json');

require_once __DIR__ . '/../connection.php';
require_once __DIR__ . '/../managers/CacheManager.php';
require_once __DIR__ . '/../managers/ProductManager.php';

$status = true;
$message = 'Successfully Retrieved Data';
$data = [];

try {
    $productManager = new ProductManager($pdo, new CacheManager());
    $data = $productManager->getByType('services');
} catch (Exception $e) {
    $status = false;
    $message = 'Encountered an error: ' . $e;
}

echo json_encode(['status' => $status, 'message' => $message, 'data' => $data]);