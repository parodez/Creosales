<?php
include 'connection.php';

$criteria = isset($_GET['criteria']) ? $_GET['criteria'] : '';
$client_id = isset($_GET['client_id']) ? intval($_GET['client_id']) : 0;

if ($criteria && $client_id) {
    $subcriteria_sql = "
        SELECT sc.subcriteria_criterion, sr.subrating_score
        FROM tbl_subcriteria sc
        LEFT JOIN tbl_subrating sr ON sc.subcriteria_id = sr.subcriteria_id
        LEFT JOIN tbl_rating r ON sr.rating_id = r.rating_id
        LEFT JOIN tbl_evaluation e ON r.evaluation_id = e.evaluation_id
        WHERE r.criteria_id = (SELECT criteria_id FROM tbl_criteria WHERE criteria_criterion = ?)
        AND e.client_id = ?
        ORDER BY sc.subcriteria_id
    ";

    $subcriteria_stmt = $conn->prepare($subcriteria_sql);
    $subcriteria_stmt->bind_param("si", $criteria, $client_id);
    $subcriteria_stmt->execute();
    $subcriteria_result = $subcriteria_stmt->get_result();

    $subcriteria = [];
    while ($row = $subcriteria_result->fetch_assoc()) {
        $subcriteria[] = $row;
    }

    echo json_encode($subcriteria);
} else {
    echo json_encode([]);
}

$conn->close();