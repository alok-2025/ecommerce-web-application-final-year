<?php
include (__DIR__ . '/../includes/conn.php');
include (__DIR__ . '/../includes/session.php');

// Ensure the request is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: cart.php');
    exit;
}

// Get order_id from POST
$order_id = isset($_POST['order_id']) ? intval($_POST['order_id']) : 0;
if ($order_id <= 0) {
    header('Location: cart.php');
    exit;
}

// Fetch order and items from DB
$stmt = $conn->prepare("SELECT * FROM orders WHERE id = ?");
$stmt->bind_param("i", $order_id);
$stmt->execute();
$order = $stmt->get_result()->fetch_assoc();

$stmt = $conn->prepare("SELECT * FROM order_items WHERE order_id = ?");
$stmt->bind_param("i", $order_id);
$stmt->execute();
$order_items = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

if (!$order || empty($order_items)) {
    echo "<div class='alert alert-danger text-center mt-3'>Order not found. <a href='../cart.php'>Go back to cart</a>.</div>";
    exit;
}

// Read and sanitise POST data
$first_name = mysqli_real_escape_string($conn, $_POST['first_name']);
$last_name = mysqli_real_escape_string($conn, $_POST['last_name']);
$email = mysqli_real_escape_string($conn, $_POST['email']);
$mobile_money_operator = mysqli_real_escape_string($conn, $_POST['mobile_money_operator']);
$mobile_money_number = mysqli_real_escape_string($conn, $_POST['mobile_money_number']);

// Calculate total amount and product names from DB
$total_amount = 0;
$product_names = [];
foreach ($order_items as $item) {
    $total_amount += $item['price'] * $item['quantity'];
    $product_names[] = $item['product_name'];
}
$product_name_str = implode(', ', $product_names);
$payment_status = 'Pending';

// Insert into payments table
$sql = "INSERT INTO payments (order_id, first_name, last_name, email, mobile_money_operator, mobile_money_number, amount, payment_status, payment_date, product_names)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW(), ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("isssssdss", $order_id, $first_name, $last_name, $email, $mobile_money_operator, $mobile_money_number, $total_amount, $payment_status, $product_name_str);

if ($stmt->execute()) {
    $_SESSION['payment_id'] = $conn->insert_id;

    // Set session for showing popup on the next page
    $_SESSION['payment_success'] = [
        'order_id' => $order_id,
        'amount' => $total_amount
    ];

    // Redirect back to the same payment gateway page
    header('Location: ../products.php');
    exit;
} else {
    echo "<div class='alert alert-danger text-center mt-3'>Error processing payment: " . htmlspecialchars($stmt->error) . "</div>";
}

$conn->close();
?>
