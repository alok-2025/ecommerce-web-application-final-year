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

    $allowed_statuses = ['Processing', 'Shipping', 'Completed', 'Cancelled'];

    if (in_array($new_status, $allowed_statuses)) {
        // Step 1: Get the existing status before update
        $check = $conn->prepare("SELECT status FROM orders WHERE id = ?");
        $check->bind_param("i", $order_id);
        $check->execute();
        $check_result = $check->get_result();
        $existing_status = $check_result->fetch_assoc()['status'];
        $check->close();

        // Step 2: If going from Cancelled → Active (reverse restock)
        if ($existing_status === 'Cancelled' && $new_status !== 'Cancelled') {
            $items = $conn->prepare("SELECT product_name, quantity FROM order_items WHERE order_id = ?");
            $items->bind_param("i", $order_id);
            $items->execute();
            $result = $items->get_result();

            while ($item = $result->fetch_assoc()) {
                $product_name = $item['product_name'];
                $qty = $item['quantity'];

                // Reduce stock again
                $update_stock = $conn->prepare("UPDATE products SET quantity = quantity - ? WHERE name = ?");
                $update_stock->bind_param("is", $qty, $product_name);
                $update_stock->execute();
                $update_stock->close();
            }

            $items->close();
        }

        // Step 3: If going from Active → Cancelled (restock)
        if ($new_status === 'Cancelled' && $existing_status !== 'Cancelled') {
            $items = $conn->prepare("SELECT product_name, quantity FROM order_items WHERE order_id = ?");
            $items->bind_param("i", $order_id);
            $items->execute();
            $result = $items->get_result();

            while ($item = $result->fetch_assoc()) {
                $product_name = $item['product_name'];
                $qty = $item['quantity'];

                // Add stock back
                $update_stock = $conn->prepare("UPDATE products SET quantity = quantity + ? WHERE name = ?");
                $update_stock->bind_param("is", $qty, $product_name);
                $update_stock->execute();
                $update_stock->close();
            }

            $items->close();
        }

        // Step 4: Update order status
        $stmt = $conn->prepare("UPDATE orders SET status = ? WHERE id = ?");
        $stmt->bind_param("si", $new_status, $order_id);
        $stmt->execute();
        $stmt->close();
    }
}

// Redirect back
header("Location: ../view_orders.php");
exit;
