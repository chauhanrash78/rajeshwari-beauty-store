<?php

header('Content-Type: application/json; charset=utf-8');

include "db.php";

$id = intval($_GET['id']);

// Fetch product
$sql = "SELECT * FROM products WHERE id=$id";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    echo json_encode($row);
} else {
    echo json_encode(["error" => "Product not found"]);
}

?>