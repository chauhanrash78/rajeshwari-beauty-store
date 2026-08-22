<?php

include "db.php";

$data = json_decode(file_get_contents("php://input"), true);

$email = $data['email'];
$password = password_hash($data['password'], PASSWORD_DEFAULT);

$stmt = $conn->prepare("UPDATE users SET password=? WHERE email=?");
$stmt->bind_param("ss", $password, $email);

if ($stmt->execute()) {
    echo "Password Updated Successfully!";
} else {
    echo "Failed to update password.";
}
?>