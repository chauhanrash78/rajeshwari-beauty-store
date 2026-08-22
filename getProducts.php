<?php

header('Content-Type: application/json; charset=utf-8');

include "db.php";

$conn->set_charset("utf8mb4");

// Get filter values
$category = isset($_GET['category']) ? $_GET['category'] : "";
$brand = isset($_GET['brand']) ? $_GET['brand'] : "";

$sql = "SELECT * FROM products WHERE 1=1";

if ($category != "") {
    $sql .= " AND category = '$category'";
}

if ($brand != "") {
    $sql .= " AND brand = '$brand'";
}

$result = $conn->query($sql);

$products = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
}

echo json_encode($products, JSON_UNESCAPED_UNICODE);

?>