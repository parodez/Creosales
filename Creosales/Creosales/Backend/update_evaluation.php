<?php
ob_start();
// Enable error reporting for debugging (disable in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Set response header for JSON
header('Content-Type: application/json');

$response = [
    'status' => 'success',
    'message' => 'Data updated successfully'
];

try {
    // Database configuration
    include ('connection.php');

    // Start transaction for multiple updates
    $conn->begin_transaction();

    // Get the logged-in user ID
    session_start();
    $user_id = $_SESSION['user_id'];

    $evaluation_id = isset($_POST['evaluation_id']) ? filter_var($_POST['evaluation_id'], FILTER_VALIDATE_INT) : null;
    $rating = isset($_POST['rating']) ? filter_var($_POST['rating'], FILTER_VALIDATE_FLOAT) : null;
    $result = isset($_POST['result']) ? htmlspecialchars($_POST['result'], ENT_QUOTES, 'UTF-8') : null;

    if ($evaluation_id && ($rating !== null || $result !== null)) {
        $sql = "UPDATE tbl_evaluation SET ";
        $params = [];
        $types = "";

        if ($rating !== null) {
            $sql .= "evaluation_rating = ?, evaluation_date = NOW()";
            $params[] = $rating;
            $types .= "d";
            if ($result !== null) {
                $sql .= ", evaluation_result = ?";
                $params[] = $result;
                $types .= "s";
            }
        } else if ($result !== null) {
            $sql .= "evaluation_result = ?, evaluation_date = NOW()";
            $params[] = $result;
            $types .= "s";
        }

        $sql .= " WHERE evaluation_id = ?";
        $params[] = $evaluation_id;
        $types .= "i";

        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            throw new Exception("Error preparing statement: " . $conn->error);
        }

        $stmt->bind_param($types, ...$params);

        if (!$stmt->execute()) {
            throw new Exception("Error updating evaluation: " . $stmt->error);
        }
        $stmt->close();

        // Update user_id in tbl_client
        $update_user_sql = "UPDATE tbl_client c
                            INNER JOIN tbl_evaluation e ON c.client_id = e.client_id
                            SET c.user_id = ?
                            WHERE e.evaluation_id = ?";
        $stmt = $conn->prepare($update_user_sql);
        if (!$stmt) {
            throw new Exception("Error preparing user update statement: " . $conn->error);
        }
        $stmt->bind_param("ii", $user_id, $evaluation_id);
        if (!$stmt->execute()) {
            throw new Exception("Error updating user_id: " . $stmt->error);
        }
        $stmt->close();
    }

    // PART 2: Update rating notes
    if (isset($_POST['rating_notes']) && is_array($_POST['rating_notes'])) {
        foreach ($_POST['rating_notes'] as $rating_id => $note) {
            $rating_id = intval($rating_id);
            $update_sql = "UPDATE tbl_rating SET rating_notes = ? WHERE rating_id = ?";
            $stmt = $conn->prepare($update_sql);
            if (!$stmt) {
                throw new Exception("Error preparing notes statement: " . $conn->error);
            }
            $stmt->bind_param("si", $note, $rating_id);
            if (!$stmt->execute()) {
                throw new Exception("Error updating notes: " . $stmt->error);
            }
            $stmt->close();

            // Update evaluation_date in tbl_evaluation
            $update_eval_date_sql = "UPDATE tbl_evaluation e
                                     INNER JOIN tbl_rating r ON e.evaluation_id = r.evaluation_id
                                     SET e.evaluation_date = NOW()
                                     WHERE r.rating_id = ?";
            $stmt = $conn->prepare($update_eval_date_sql);
            if (!$stmt) {
                throw new Exception("Error preparing evaluation date update statement: " . $conn->error);
            }
            $stmt->bind_param("i", $rating_id);
            if (!$stmt->execute()) {
                throw new Exception("Error updating evaluation date: " . $stmt->error);
            }
            $stmt->close();

            // Update user_id in tbl_client
            $update_user_sql = "UPDATE tbl_client c
                                INNER JOIN tbl_evaluation e ON c.client_id = e.client_id
                                INNER JOIN tbl_rating r ON e.evaluation_id = r.evaluation_id
                                SET c.user_id = ?
                                WHERE r.rating_id = ?";
            $stmt = $conn->prepare($update_user_sql);
            if (!$stmt) {
                throw new Exception("Error preparing user update statement: " . $conn->error);
            }
            $stmt->bind_param("ii", $user_id, $rating_id);
            if (!$stmt->execute()) {
                throw new Exception("Error updating user_id: " . $stmt->error);
            }
            $stmt->close();
        }
    }

    // Commit transaction if all went well
    $conn->commit();
    $response['status'] = 'success';
    $response['message'] = 'All changes saved successfully';
    
} catch (Exception $e) {
    // Rollback transaction on error
    if (isset($conn)) {
        $conn->rollback();
    }
    $response['status'] = 'error';
    $response['message'] = $e->getMessage();

    // Capture errors for debugging
    error_log("Error: " . $e->getMessage());
} finally {
    if (isset($conn)) {
        $conn->close();
    }
}

// Send JSON response at the very end
ob_end_clean();
echo json_encode($response);
exit;
?>