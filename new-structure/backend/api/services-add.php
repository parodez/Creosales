<?php

header('Content-Type: application/json');

require_once __DIR__ . '/../connection.php';
require_once __DIR__ . '/../managers/CacheManager.php';
require_once __DIR__ . '/../managers/ProductManager.php';

$success = true;
$message = 'Robot Successfully Added';

if (!isset($_POST['services_type'], $_POST['services_cost'])) {
    $success = false;
    $message = 'Missing fields';
} else {
    $productManager = new ProductManager($pdo, new CacheManager());
    $data = $productManager->addService($_POST['services_type'], $_POST['services_cost']);
    if (!$data['success']) {
        $success = false;
        $message = 'Error occurred: ' . $e;
    }
}

echo json_encode(['success' => $success, 'message' => $message]);