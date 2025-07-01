<?php

header('Content-Type: application/json');

require_once __DIR__ . '/../connection.php';
require_once __DIR__ . '/../managers/CacheManager.php';
require_once __DIR__ . '/../managers/ProductManager.php';

$success = true;
$message = 'Robot Successfully Added';

if (!isset($_POST['robots_item'], $_POST['robots_desc'], $_POST['robots_cost'], $_POST['robots_srp'])) {
    $success = false;
    $message = 'Missing fields';
} else {
    $productManager = new ProductManager($pdo, new CacheManager());
    $data = $productManager->addRobot($_POST['robots_item'], $_POST['robots_desc'], $_POST['robots_cost'], $_POST['robots_srp']);
    if (!$data['success']) {
        $success = false;
        $message = 'Error occurred: ' . $data['message'];
    }
}

echo json_encode(['success' => $success, 'message' => $message]);