<?php
include (__DIR__ . '/../includes/conn.php');
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $role = trim($_POST['role']);
    $password_raw = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Password match check
    if ($password_raw !== $confirm_password) {
        echo "<script>alert('Passwords do not match. Please try again.'); window.history.back();</script>";
        exit();
    }

    // Check for existing username
    $checkUsername = $conn->prepare("SELECT id FROM users WHERE username = ?");
    $checkUsername->bind_param("s", $username);
    $checkUsername->execute();
    $checkUsername->store_result();
    if ($checkUsername->num_rows > 0) {
        echo "<script>alert('Username already exists. Please choose another one.'); window.history.back();</script>";
        $checkUsername->close();
        exit();
    }
    $checkUsername->close();

    // Check if password is already used
    $checkPassword = $conn->prepare("SELECT password FROM users");
    $checkPassword->execute();
    $result = $checkPassword->get_result();
    $password_taken = false;
    foreach ($result as $row) {
        if (password_verify($password_raw, $row['password'])) {
            $password_taken = true;
            break;
        }
    }
    $checkPassword->close();

    if ($password_taken) {
        echo "<script>alert('Password already taken. Please choose a different password.'); window.history.back();</script>";
        exit();
    }

    $password = password_hash($password_raw, PASSWORD_BCRYPT);

    // Default profile picture
    $profile_pic = "default_img.png";

    // Handle profile picture upload
    if (!empty($_FILES['profile_pic']['name'])) {
        $target_dir = "uploads/";
        $profile_pic = time() . "_" . basename($_FILES['profile_pic']['name']);
        $target_file = $target_dir . $profile_pic;

        if (!move_uploaded_file($_FILES['profile_pic']['tmp_name'], $target_file)) {
            $profile_pic = "default_img.png"; // fallback
        }
    }

    // Insert user
    $sql = "INSERT INTO users (username, email, password, role, profile_pic) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $username, $email, $password, $role, $profile_pic);

    if ($stmt->execute()) {
        echo "<script>
        if (confirm('Registration successful!')) {
            window.location.href = 'login.php';
        }
        </script>";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();