<?php
require_once __DIR__ . '/../../backend/connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['action']) && $_GET['action'] == 'fetch_contact_details') {
    $potentialcustomer_id = intval($_GET['client_id']);

    $stmt = $pdo->prepare("
        SELECT 
            contactperson_id,
            contactperson_position,
            contactperson_name,
            contactperson_email,
            contactperson_number
        FROM tbl_contactperson
        WHERE potentialcustomer_id = ?
    ");
    $stmt->execute([$potentialcustomer_id]);

    $contact_details = [];
    while ($row = $stmt->fetch()) {
        $contact_details[] = $row;
    }

    echo json_encode(['status' => 'success', 'data' => $contact_details]);
}