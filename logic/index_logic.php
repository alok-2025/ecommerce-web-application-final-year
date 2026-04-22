<?php
include (__DIR__ . '/../includes/session.php'); // Ensure the user is logged in
include (__DIR__ . '/../includes/conn.php');    // Database connection


// Retrieve username from session
$username = $_SESSION['username'] ?? null;

if ($username) {
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if (!$user) {
        header('Location: login.php');
        exit;
    }
} else {
    header('Location: login.php');
    exit;
}
