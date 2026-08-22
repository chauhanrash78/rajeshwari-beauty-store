<?php

include "db.php";

$email = $_GET['email'];

// Fetch orders
$sql = "SELECT o.*, p.name, p.image, p.price
        FROM orders o
        JOIN products p ON o.product_id = p.id
        WHERE o.email = '$email'
        ORDER BY o.id DESC";

$result = mysqli_query($conn, $sql);

$orders = [];

while ($row = mysqli_fetch_assoc($result)) {
    $orders[] = $row;
}

echo json_encode($orders);

?>