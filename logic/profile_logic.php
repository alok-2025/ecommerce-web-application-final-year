<?php
include (__DIR__ . '/../includes/session.php');
include (__DIR__ . '/../includes/conn.php');

$user_id = $_SESSION['user_id'] ?? null;
$errors = [];
$success = "";

// Fetch user details
$stmt = $conn->prepare("SELECT username, email, profile_pic FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_username = trim($_POST['username'] ?? '');
    $new_email = trim($_POST['email'] ?? '');
    $new_password_raw = $_POST['password'] ?? '';
    $new_confirm_password = $_POST['confirm_password'] ?? '';

    if (!empty($new_password_raw) || !empty($new_confirm_password)) {
        if ($new_password_raw !== $new_confirm_password) {
            echo "<script>alert('Passwords do not match. Please try again.'); window.history.back();</script>";
            exit();
        }
    }
    $hashed_password = password_hash($new_password_raw, PASSWORD_BCRYPT);

    $new_profile_pic = $user['profile_pic']; // default to current pic

    // Handle profile picture upload if a new file was selected
    if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] === UPLOAD_ERR_OK) {
        $filename = time() . '_' . basename($_FILES['profile_pic']['name']);
        $target_path = 'uploads/' . $filename;

        if (move_uploaded_file($_FILES['profile_pic']['tmp_name'], $target_path)) {
            $new_profile_pic = $filename;
        } else {
            $errors[] = "Failed to upload the new profile picture.";
        }
    }

    if (!$errors) {
        $query = "UPDATE users SET username=?, email=?, profile_pic=?";
        $params = [$new_username, $new_email, $new_profile_pic];
        $types = "sss";

        if (!empty($new_password_raw)) {
            $query .= ", password=?";
            $params[] = $hashed_password;
            $types .= "s";
        }

        $query .= " WHERE id=?";
        $params[] = $user_id;
        $types .= "i";

        $stmt = $conn->prepare($query);
        $stmt->bind_param($types, ...$params);

        if ($stmt->execute()) {
            $_SESSION['username'] = $new_username;
            $success = "Profile updated successfully.";
            // Refresh user data
            $user['username'] = $new_username;
            $user['email'] = $new_email;
            $user['profile_pic'] = $new_profile_pic;
        } else {
            $errors[] = "Failed to update profile. Try again.";
        }
    }
}
