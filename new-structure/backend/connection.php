<?php

// Database connection
$servername = "localhost";
$username = "creotec_kcab";
$password = "ojd5B#y@^N2r";
$dbname = "creotec_creosales";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>