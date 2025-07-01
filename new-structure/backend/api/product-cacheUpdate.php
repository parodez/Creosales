<?php

header('Content-Type: application/json');

require_once __DIR__ . '/../connection.php';
require_once __DIR__ . '/../managers/CacheManager.php';
require_once __DIR__ . '/../managers/ProductManager.php';

$success = true;
$message = 'Cache Successfully Updated';

if (!isset($_POST['product'])) {
    $success = false;
    $message = 'Missing fields';
} else {
    $productManager = new ProductManager($pdo, new CacheManager());
    $data = $productManager->updateCache($_POST['product']);
    if (!$data['success']) {
        $success = false;
        $message = 'Error occurred: ' . $data['message'];
    }
}

echo json_encode(['success' => $success, 'message' => $message]);