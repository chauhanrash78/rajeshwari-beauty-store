<?php

include "db.php";

$data = json_decode(file_get_contents("php://input"), true);

if (!$data) {
    die("Invalid Data");
}

$email = $data['email'];
$orders = $data['orders'];
$address = $data['address'];
$payment = $data['payment'];

// Generate order ID
$order_id = "ORD" . rand(100000, 999999);

$stmt = $conn->prepare(
    "INSERT INTO orders
    (order_id, email, product_id, quantity, address, payment, status)
    VALUES (?, ?, ?, ?, ?, ?, 'Pending')"
);

foreach ($orders as $order) {
    $product_id = $order['id'];
    $quantity = $order['qty'];

    $stmt->bind_param(
        "ssssss",
        $order_id,
        $email,
        $product_id,
        $quantity,
        $address,
        $payment
    );

    $stmt->execute();
}

$stmt->close();
$conn->close();

echo $order_id;
?>