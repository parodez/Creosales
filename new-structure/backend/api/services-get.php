<?php

header('Content-Type: application/json');

require_once __DIR__ . '/../connection.php';
require_once __DIR__ . '/../managers/CacheManager.php';
require_once __DIR__ . '/../managers/ProductManager.php';

$success = true;
$message = 'Successfully Retrieved Data';

try {
    $productManager = new ProductManager($pdo, new CacheManager());
    $data = $productManager->getByType('services');
} catch (Exception $e) {
    $success = false;
    $message = 'Encountered an error: ' . $e;
}

echo json_encode(['status' => $success, 'message' => $message, 'data' => $data]);