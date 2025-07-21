<?php
// TODO Remove after debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../../managers/ProductManager.php';
require_once __DIR__ . '/../../connection.php';

header("Access-Control-Allow-Origin: *"); // or specify exact origin
header("Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

$method = $_SERVER['REQUEST_METHOD'];
$manager = new ProductManager($pdo);

switch ($method) {
    case 'POST':
        echo json_encode($manager->addProduct($_POST));
        exit;
    case 'GET':
        echo json_encode($manager->getProducts());
        exit;
    case 'DELETE':
        echo json_encode($manager->deleteById($_GET));
        exit;
    case 'PATCH':
        $data = json_decode(file_get_contents('php://input'), true);
        echo json_encode($manager->editProduct($data));
        exit;
    default:
        http_response_code(405);
        echo json_encode(['error' => 'Method Not Allowed']);
}