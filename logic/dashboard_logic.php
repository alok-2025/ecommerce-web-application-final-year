<?php
include (__DIR__ . '/../includes/session.php'); // Check if the user is logged in
include (__DIR__ . '/../includes/conn.php');    // Database connection

// Redirect customers to products page
if ($_SESSION['role'] === 'Customer') {
    header('Location: products.php');
    exit();
}

// Allow access only to Administrator and Vendor
$allowed_roles = ['Administrator', 'Vendor'];
if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], $allowed_roles)) {
    header('Location: login.php');
    exit();
}

// Default values
$totalProducts = 0;
$totalOrders   = 0;
$totalUsers    = 0;
$totalMessages = 0;
$totalRevenue  = 0.00;

// Total products
$res = mysqli_query($conn, "SELECT COUNT(id) AS cnt FROM products");
if ($res && $row = mysqli_fetch_assoc($res)) {
    $totalProducts = (int)$row['cnt'];
}

// Total orders
$res = mysqli_query($conn, "SELECT COUNT(id) AS cnt FROM orders");
if ($res && $row = mysqli_fetch_assoc($res)) {
    $totalOrders = (int)$row['cnt'];
}

// Total users
$res = mysqli_query($conn, "SELECT COUNT(id) AS cnt FROM users");
if ($res && $row = mysqli_fetch_assoc($res)) {
    $totalUsers = (int)$row['cnt'];
}

// Total messages (support)
$res = mysqli_query($conn, "SELECT COUNT(id) AS cnt FROM support");
if ($res && $row = mysqli_fetch_assoc($res)) {
    $totalMessages = (int)$row['cnt'];
}

// Total revenue // add all price values from all rows in the orders table
$res = mysqli_query($conn, "SELECT COALESCE(SUM(total_price), 0) AS total FROM orders");
if ($res && $row = mysqli_fetch_assoc($res)) {
    $totalRevenue = (float)$row['total'];
}

$totalRevenueFormatted = number_format($totalRevenue, 2);

// Fetch recent 4 orders with key info
$recentOrders = [];
$res = mysqli_query($conn, "SELECT id, customer_name, total_price, status, created_at 
    FROM orders 
    ORDER BY created_at 
    DESC LIMIT 4");
if ($res) {
    while ($row = mysqli_fetch_assoc($res)) {
        $recentOrders[] = $row;
    }
}

// Recent Products (latest 4)
$recentProducts = [];
$res = mysqli_query($conn, "SELECT id, name, price, quantity, created_at 
    FROM products 
    ORDER BY id DESC 
    LIMIT 4");

if ($res) {
    while ($row = mysqli_fetch_assoc($res)) {
        $recentProducts[] = $row;
    }
}

// Recent Users (latest 4)
$recentUsers = [];
$resUsers = mysqli_query($conn, "
    SELECT id, username, email, role, created_at
    FROM users
    ORDER BY id DESC
    LIMIT 4
");

if ($resUsers) {
    while ($row = mysqli_fetch_assoc($resUsers)) {
        $recentUsers[] = $row;
    }
}

// Recent Messages (latest 4)
$recentMessages = [];
$resMessages = mysqli_query($conn, "
    SELECT id, fullname, address, email, created_by, created_at
    FROM Support
    ORDER BY id DESC
    LIMIT 4
");

if ($resMessages) {
    while ($row = mysqli_fetch_assoc($resMessages)) {
        $recentMessages[] = $row;
    }
}




