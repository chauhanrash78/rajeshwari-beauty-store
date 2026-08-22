<?php

include "db.php";

$data = json_decode(file_get_contents("php://input"), true);
$email = trim($data['email']);

$stmt = $conn->prepare("SELECT id FROM users WHERE email=?");
$stmt->bind_param("s", $email);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo "Success: Email verified. Set your new password.";
} else {
    echo "Error: Email not found in our records.";
}

?>