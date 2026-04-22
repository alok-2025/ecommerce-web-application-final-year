<?php
include (__DIR__ . '/../includes/conn.php');
include (__DIR__ . '/../includes/session.php');

// Only allow logged-in customers
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['order_id'])) {
    $order_id = (int) $_GET['order_id'];
    $username = $_SESSION['username'];

    // Step 1: Get current status of the order
    $stmt = $conn->prepare("SELECT status FROM orders WHERE id = ? AND created_by = ?");
    $stmt->bind_param("is", $order_id, $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $order = $result->fetch_assoc();
    $stmt->close();

    if ($order && $order['status'] === 'Processing') {
        // Step 2: Restock products
        $items = $conn->prepare("SELECT product_name, quantity FROM order_items WHERE order_id = ?");
        $items->bind_param("i", $order_id);
        $items->execute();
        $result = $items->get_result();

        while ($item = $result->fetch_assoc()) {
            $product_name = $item['product_name'];
            $qty = $item['quantity'];

            $update_stock = $conn->prepare("UPDATE products SET quantity = quantity + ? WHERE name = ?");
            $update_stock->bind_param("is", $qty, $product_name);
            $update_stock->execute();
            $update_stock->close();
        }

        $items->close();

        // Step 3: Update the order status to "Cancelled"
        $update = $conn->prepare("UPDATE orders SET status = 'Cancelled' WHERE id = ? AND created_by = ?");
        $update->bind_param("is", $order_id, $username);
        $update->execute();
        $update->close();
    }
}

// Redirect back to my_orders.php
header("Location: ../my_orders.php");
exit;
