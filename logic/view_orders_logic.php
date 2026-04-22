<?php
include (__DIR__ . '/../includes/conn.php');
include (__DIR__ . '/../includes/session.php');

// Restrict access
if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], ['Administrator', 'Vendor'])) {
    header('Location: login.php');
    exit();
}

// Fetch all orders
$orders = $conn->query("
    SELECT id, customer_name, customer_email, customer_address, total_price, status, created_by, created_at 
    FROM orders 
    ORDER BY created_at ASC
");

// Helper function to fetch order items
function getOrderItems($conn, $order_id) {
    return $conn->query("
        SELECT product_name, quantity, price 
        FROM order_items 
        WHERE order_id = $order_id
    ");
}

// Helper function to fetch payment info
function getPaymentInfo($conn, $order_id) {
    $stmt = $conn->prepare("SELECT payment_status FROM payments WHERE order_id = ?");
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $row = $result->fetch_assoc()) {
        $status = trim($row['payment_status']);
        return $status !== '' ? $status : 'No payment';
    }

    return 'No payment';
}
