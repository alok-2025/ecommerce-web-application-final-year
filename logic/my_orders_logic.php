<?php
include (__DIR__ . '/../includes/conn.php');
include (__DIR__ . '/../includes/session.php');

// Get username from session
$username = $_SESSION['username'];

// Fetch orders for this user
$query = "SELECT * FROM orders WHERE created_by = ? ORDER BY created_at DESC";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $username);
$stmt->execute();
$orders = $stmt->get_result();