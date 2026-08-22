<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check admin session
if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    header("Location: login.php");
    exit;
}

// Check IP address
if (
    isset($_SESSION['ip']) &&
    $_SESSION['ip'] !== $_SERVER['REMOTE_ADDR']
) {
    session_destroy();
    header("Location: login.php");
    exit;
}

// Check user agent
if (
    isset($_SESSION['user_agent']) &&
    $_SESSION['user_agent'] !== $_SERVER['HTTP_USER_AGENT']
) {
    session_destroy();
    header("Location: login.php");
    exit;
}

// Session timeout
if (
    isset($_SESSION['last_time']) &&
    (time() - $_SESSION['last_time'] > 1800)
) {
    session_unset();
    session_destroy();
    header("Location: login.php");
    exit;
}

$_SESSION['last_time'] = time();

?>