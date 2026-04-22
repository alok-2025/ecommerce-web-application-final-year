<?php
include(__DIR__ . '/../includes/conn.php');
session_start();

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Null coalescing operator to avoid undefined index
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    $role = $_POST['role'] ?? '';

    $query = "SELECT * FROM users WHERE username = ? LIMIT 1";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if (!$user) {
        $errors[] = "Username not found.";
    } else {
        if (!password_verify($password, $user['password'])) {
            $errors[] = "Incorrect password.";
        }
        if ($user['role'] !== $role) {
            $errors[] = "Incorrect role selected.";
        }
    }

    if (empty($errors)) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['profile_pic'] = $user['profile_pic'];

        if ($role === 'Administrator' || $role === 'Vendor') {
            header('Location: dashboard.php');
        } elseif ($role === 'Customer') {
            header('Location: products.php');
        } else {
            header('Location: index.php');
        }
        exit();
    }

    // Store errors in session to show in login.php
    $_SESSION['login_errors'] = $errors;
}