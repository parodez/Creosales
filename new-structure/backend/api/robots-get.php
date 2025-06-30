<?php

header('Content-Type: application/json');

require_once __DIR__ . '/../connection.php';
require_once __DIR__ . '/../managers/CacheManager.php';
require_once __DIR__ . '/../managers/ProductManager.php';

$success = true;
$message = 'Data Successfully Retrieved';

try {
    $productManager = new ProductManager($pdo, new CacheManager());
    $data = $productManager->getProduct('robots', 'tbl_robots');
} catch (Exception $e) {
    $success = false;
    $message = 'Error occurred: ' . $e;
}

echo json_encode(['success' => $success, 'message' => $message, 'data' => $data]);