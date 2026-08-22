<?php

include "db.php";

$data = json_decode(file_get_contents("php://input"), true);

$order_id = $data['order_id'];

//cancel order
$sql = "UPDATE orders SET status='Cancelled' WHERE order_id='$order_id'";

if(mysqli_query($conn, $sql)){
    echo "Order Cancelled";
}else{
    echo "Error: " . mysqli_error($conn);
}

?>