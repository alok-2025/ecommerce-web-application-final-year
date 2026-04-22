<?php
include (__DIR__ . '/../includes/conn.php');
include (__DIR__ . '/../includes/session.php');

// Ensure access control
if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], ['Administrator', 'Vendor'])) {
    header('Location: login.php');
    exit;
}

// Validate input
if (isset($_GET['order_id'], $_GET['new_status'])) {
    $order_id = (int) $_GET['order_id'];
    $new_status = $_GET['new_status'];

    $allowed_statuses = ['Pending', 'Successful', 'Failed'];

    if (in_array($new_status, $allowed_statuses)) {
        // Step 1: Get existing payment_status from payments table
        $check = $conn->prepare("SELECT payment_status FROM payments WHERE order_id = ?");
        $check->bind_param("i", $order_id);
        $check->execute();
        $check_result = $check->get_result();
        $existing_status = $check_result->fetch_assoc()['payment_status'];
        $check->close();

        // Step 2: Update payment_status in payments table
        $stmt = $conn->prepare("UPDATE payments SET payment_status = ? WHERE order_id = ?");
        $stmt->bind_param("si", $new_status, $order_id);
        $stmt->execute();
        $stmt->close();
    }
}

// Redirect back
header("Location: ../view_orders.php");
exit;
?>
