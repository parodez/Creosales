<?php
include('../connection.php');
$data = json_decode(file_get_contents('php://input'), true);

// Check if data is valid
if (!isset($data['username']) || !isset($data['password'])) {
    http_response_code(404);
    include(__DIR__ . '/../path/404NotFound.html');
    exit();
}

$username = $data['username'];
$password = $data['password'];

// Check if username exists
$query = "SELECT credentials_password, user_id, user_type FROM tbl_credentials WHERE credentials_username = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $hashedPassword = $row['credentials_password'];
    $userId = $row['user_id'];
    $userType = $row['user_type'];
    // Verify password
    if (password_verify($password, $hashedPassword)) {
        // Fetch user details
        $userQuery = "SELECT user_firstname, user_lastname, user_department, user_position FROM tbl_user WHERE user_id = ?";
        $userStmt = $conn->prepare($userQuery);
        $userStmt->bind_param("i", $userId);
        $userStmt->execute();
        $userResult = $userStmt->get_result();
        $userDetails = $userResult->fetch_assoc();

        // Start session and store user details
        session_start();
        $_SESSION['user'] = $userDetails;
        $_SESSION['user_id'] = $userId; // Store user ID in session
        $_SESSION['user_type'] = $userType; // Store user type in session

        echo json_encode(['success' => true, 'user' => $userDetails]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Incorrect password']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Incorrect username']);
}
?>

