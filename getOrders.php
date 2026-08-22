<?php

include "db.php";

$email = $_GET['email'];

// Fetch orders
$sql = "SELECT o.id, o.order_id, o.email, o.product_id, o.quantity, o.address,
               o.payment, o.status, o.created_at,
               p.name, p.image, p.price
        FROM orders o
        JOIN products p ON o.product_id = p.id
        WHERE o.email = '$email'
        ORDER BY o.id DESC";

$result = mysqli_query($conn, $sql);

$data = [];

while ($row = mysqli_fetch_assoc($result)) {
    $data[] = $row;
}

echo json_encode($data);

?>