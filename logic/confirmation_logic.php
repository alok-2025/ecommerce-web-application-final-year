<?php
include (__DIR__ . '/../includes/conn.php');
include (__DIR__ . '/../includes/session.php');

// Check for order_id in GET params
if (!isset($_GET['order_id'])) {
    header('Location: index.php');
    exit;
}

$order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;
if ($order_id <= 0) {
    header('Location: ../index.php'); 
    exit;
}

// Fetch order details
$query = "SELECT * FROM orders WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $order_id);
$stmt->execute();
$order_result = $stmt->get_result();
$order = $order_result->fetch_assoc();

if (!$order) {
    header('Location: index.php');
    exit;
}

// Fetch order items
$query = "SELECT * FROM order_items WHERE order_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $order_id);
$stmt->execute();
$order_items_result = $stmt->get_result();

$order_items = [];
while ($row = $order_items_result->fetch_assoc()) {
    $order_items[] = $row;
}

// Include email sending function once
require_once 'send_order_email.php';

$result = sendOrderEmail($order, $order_items, $order_id);
if ($result) {
    $_SESSION['email_sent'] = true;
} else {
    echo "<pre>Email failed to send. Reason: " . ($_SESSION['email_error'] ?? 'Unknown error') . "</pre>";
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['proceed_to_payment'])) {
    header("Location: payment_gateway.php?order_id=$order_id");
    exit;
}


