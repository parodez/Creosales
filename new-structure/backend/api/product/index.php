<?php
// TODO Remove after debugging
// error_reporting(E_ALL);
// ini_set('display_errors', 1);
ob_start();

require_once __DIR__ . '/../../managers/ProductManager.php';
require_once __DIR__ . '/../../connection.php';



header("Access-Control-Allow-Origin: *"); // or specify exact origin
header("Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

$method = $_SERVER['REQUEST_METHOD'];
$manager = new ProductManager($pdo);

if ($method === 'OPTIONS') {
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, X-Requested-With");
    header("Access-Control-Max-Age: 86400"); // cache for 1 day
    header("Content-Length: 0");             // prevent body mismatch
    header("Content-Type: text/plain");      // explicitly state type
    http_response_code(204);                 // success with no content
    exit;
}

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
        ob_clean();
        header('Content-Type: application/json');
        $data = json_decode(file_get_contents('php://input'), true);
        echo json_encode($manager->editProduct($data));
        exit;
    default:
        http_response_code(405);
        echo json_encode(['error' => 'Method Not Allowed']);
}