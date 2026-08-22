<?php
include "db.php";

$data = json_decode(file_get_contents("php://input"), true);

$name = trim($data['name']);
$email = trim($data['email']);
$password = trim($data['password']);

if ($name === "" || $email === "" || $password === "") {
    echo "empty";
    exit;
}

// Check if email already exists
$check = $conn->prepare("SELECT id FROM users WHERE email = ?");
$check->bind_param("s", $email);
$check->execute();

$result = $check->get_result();

if ($result->num_rows > 0) {
    echo "Email already exists";
    exit;
}

// Hash password before storing
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

$stmt = $conn->prepare(
    "INSERT INTO users (name, email, password) VALUES (?, ?, ?)"
);
$stmt->bind_param("sss", $name, $email, $hashedPassword);

if ($stmt->execute()) {
    echo "Signup Successful";
} else {
    echo "Error";
}
?>