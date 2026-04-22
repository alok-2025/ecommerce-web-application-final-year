<?php
include (__DIR__ . '/../includes/conn.php');
include (__DIR__ . '/../includes/session.php');

// Restrict access to admins and vendors only
if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], ['Administrator', 'Vendor'])) {
    header('Location: login.php');
    exit();
}

// Fetch all support messages
$supports = $conn->query("
    SELECT id, fullname, phone, address, email, message, created_by, created_at 
    FROM support 
    ORDER BY created_at DESC
");