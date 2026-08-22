<?php
include "db.php";
if(session_status() == PHP_SESSION_NONE) session_start();

// Data receiving
$data = json_decode(file_get_contents("php://input"), true);

if(!empty($data)){
    $product_id = $conn->real_escape_string($data['product_id']);
    $qty        = $conn->real_escape_string($data['qty']);
    $name       = $conn->real_escape_string($data['name']);
    $email      = $conn->real_escape_string($data['email']);
    $phone      = $conn->real_escape_string($data['phone']);
    $address    = $conn->real_escape_string($data['address']);
    $status     = "Pending";

    
    $sql = "INSERT INTO orders (product_id, quantity, name, email, phone, address, status) 
            VALUES ('$product_id', '$qty', '$name', '$email', '$phone', '$address', '$status')";

    if($conn->query($sql)){
        echo json_encode(["status" => "success", "message" => "Order Placed Successfully"]);
    } else {
        echo json_encode(["status" => "error", "message" => $conn->error]);
    }
}
?>