<?php

// Database connection
$servername = "localhost";
$username = "creotec_kcab";
$password = "ojd5B#y@^N2r";
$dbname = "creotec_creosales";
$dsn = 'mysql:host=localhost;dbname=creotec_creosales;charset=utf8';

$conn = new mysqli($servername, $username, $password, $dbname);
$pdo = new PDO($dsn, $username, $password);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

try {
    $pdo = new PDO($dsn, $username, $password);
    // Set error mode to exception for better error handling
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die( "Connection failed: " . $e->getMessage());
}
?>