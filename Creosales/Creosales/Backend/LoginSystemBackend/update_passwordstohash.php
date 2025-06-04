<?php
include ('../connection.php'); // Include database connection

// Fetch all users' credentials
$sql = "SELECT credentials_id, credentials_password FROM tbl_credentials";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $id = $row["credentials_id"];
        $plain_password = $row["credentials_password"];

        // Check if the password is already hashed (bcrypt hashes start with '$2y$')
        if (password_needs_rehash($plain_password, PASSWORD_BCRYPT)) {
            // Hash the plain-text password
            $hashed_password = password_hash($plain_password, PASSWORD_BCRYPT);

            // Update the database with the hashed password
            $stmt = $conn->prepare("UPDATE tbl_credentials SET credentials_password = ? WHERE credentials_id = ?");
            $stmt->bind_param("si", $hashed_password, $id);
            $stmt->execute();
            $stmt->close();

            echo "Password for user ID $id has been hashed successfully.<br>";
        } else {
            echo "Password for user ID $id is already hashed.<br>";
        }
    }
} else {
    echo "No users found.";
}

$conn->close();
?>
