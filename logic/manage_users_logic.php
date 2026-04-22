<?php
include (__DIR__ . '/../includes/conn.php');
include (__DIR__ . '/../includes/session.php');

// Restrict access
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Administrator') {
    header('Location: login.php');
    exit();
}

// Handle Add User
if (isset($_POST['add_user'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $role = $_POST['role'];
    $password_raw = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password_raw !== $confirm_password) {
        echo "<script>alert('Passwords do not match. Please try again.'); window.history.back();</script>";
        exit();
    }

    $password = password_hash($password_raw, PASSWORD_BCRYPT);
    $profilePicName = null;

    if ($_FILES['profile_pic']['error'] === UPLOAD_ERR_OK) {
        $profilePicName = time() . '_' . basename($_FILES['profile_pic']['name']);
        $target_path = 'uploads/' . $profilePicName;
        move_uploaded_file($_FILES['profile_pic']['tmp_name'], $target_path);
    }

    $stmt = $conn->prepare("INSERT INTO users (username, email, role, password, profile_pic) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $username, $email, $role, $password, $profilePicName);
    $stmt->execute();
    header("Location: manage_users.php");
    exit();
}

// Handle Update User
if (isset($_POST['update_user'])) {
    $id = $_POST['user_id'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $role = $_POST['role'];

    $sql = "UPDATE users SET username=?, email=?, role=?";
    $params = [$username, $email, $role];
    $types = "sss";

    if (!empty($_POST['password'])) {
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $sql .= ", password=?";
        $params[] = $password;
        $types .= "s";
    }

    if ($_FILES['profile_pic']['error'] === UPLOAD_ERR_OK) {
        $profilePicName = time() . '_' . basename($_FILES['profile_pic']['name']);
        $target_path = 'uploads/' . $profilePicName;
        move_uploaded_file($_FILES['profile_pic']['tmp_name'], $target_path);

        $sql .= ", profile_pic=?";
        $params[] = $profilePicName;
        $types .= "s";
    }

    $sql .= " WHERE id=?";
    $params[] = $id;
    $types .= "i";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    header("Location: manage_users.php");
    exit();
}

// Handle Delete User
if (isset($_POST['delete_user'])) {
    $id = $_POST['user_id'];
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    header("Location: manage_users.php");
    exit();
}

// Fetch users
$users = $conn->query("SELECT id, username, email, role, profile_pic FROM users");