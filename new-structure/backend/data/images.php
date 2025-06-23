<?php
$stmt = $pdo->prepare('SELECT images_name, images_path, images_date
FROM tbl_images
WHERE evaluation_id IN (
SELECT evaluation_id
FROM tbl_evaluation
WHERE potentialcustomer_id = ?
)');
$stmt->execute([$potentialCustomer->id]);

$images = [];
while ($row = $stmt->fetch()) {
    $images[] = $row;
}