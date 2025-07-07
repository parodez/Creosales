<?php
require_once __DIR__ . '/../../managers/ProductManager.php';
require_once __DIR__ . '/../../connection.php';

$method = $_SERVER['REQUEST_METHOD'];
$manager = new ProductManager($pdo);

switch ($method) {
    case 'POST':
        echo json_encode($manager->addProduct($_POST));
        break;
    case 'GET':
        echo json_encode($manager->getProducts());
        break;
    case 'DELETE':
        echo json_encode($manager->deleteById($_GET));
        break;
    case 'PATCH':
        $data = json_decode(file_get_contents('php://input'), true);
        echo json_encode($manager->editProduct($data));
        break;
    default:
        http_response_code(405);
        echo json_encode(['error' => 'Method Not Allowed']);
}