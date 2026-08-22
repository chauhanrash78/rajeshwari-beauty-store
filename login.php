<?php

session_start();
include "db.php";

$data = json_decode(file_get_contents("php://input"), true);

$email = trim($data['email']);
$password = trim($data['password']);

if ($email == "" || $password == "") {
    echo "empty";
    exit;
}

$stmt = $conn->prepare(
    "SELECT id, name, email, password, role
     FROM users
     WHERE email=?"
);

$stmt->bind_param("s", $email);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();

    if (password_verify($password, $user['password'])) {

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['role'] = $user['role'];

        echo "success";
    } else {
        echo "wrong";
    }
} else {
    echo "no_user";
}

?>