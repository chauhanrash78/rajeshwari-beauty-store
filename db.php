<?php
$conn = new mysqli("localhost:3306", "root", "", "beauty_store_m");

$conn->set_charset("utf8mb4");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?> 
