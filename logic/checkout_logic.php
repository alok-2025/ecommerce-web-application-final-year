<?php
include (__DIR__ . '/../includes/conn.php');
include (__DIR__ . '/../includes/session.php');

// Redirect if cart is empty
if (empty($_SESSION['cart'])) {
    header('Location: cart.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $customer_name = htmlspecialchars($_POST['name']);
    $customer_email = htmlspecialchars($_POST['email']);
    $customer_address = htmlspecialchars($_POST['address']);
    $cart_items = $_SESSION['cart'];
    $order_total = 0;

    // Calculate total
    foreach ($cart_items as $item) {
        $order_total += $item['total'];
    }

    $created_by = $_SESSION['username'];
    $query = "INSERT INTO orders (customer_name, customer_email, customer_address, total_price, created_by, created_at) 
              VALUES (?, ?, ?, ?, ?, NOW())";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sssds", $customer_name, $customer_email, $customer_address, $order_total, $created_by);
    $stmt->execute();
    $order_id = $stmt->insert_id; // Get the new created order ID
    $_SESSION['order_id'] = $order_id; // Save it in session

    $query = "INSERT INTO order_items (order_id, product_id, product_name, quantity, price) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);

    foreach ($cart_items as $item) {
        $stmt->bind_param("iisid", $order_id, $item['id'], $item['name'], $item['quantity'], $item['price']);
        $stmt->execute();

        $updateStmt = $conn->prepare("UPDATE products SET quantity = quantity - ? WHERE id = ?");
        $updateStmt->bind_param("ii", $item['quantity'], $item['id']);
        $updateStmt->execute();
    }

    unset($_SESSION['cart']);
    header("Location: confirmation.php?order_id=$order_id");
    exit;
}
