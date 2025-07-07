<?php
require_once __DIR__ . '/../../managers/ServiceManager.php';
require_once __DIR__ . '/../../connection.php';

$method = $_SERVER['REQUEST_METHOD'];
$manager = new ServiceManager($pdo);

switch ($method) {
    case 'GET':
        echo json_encode($manager->getServices());
        break;
    default:
        http_response_code(405);
        echo json_encode(['error' => 'Method Not Allowed']);
}