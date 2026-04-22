<?php
include ('includes/session.php');
include ('includes/conn.php'); 

$order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : (isset($_SESSION['order_id']) ? intval($_SESSION['order_id']) : 0);


if ($order_id <= 0) {
    echo "<div class='alert alert-warning text-center'>Invalid order. <a href='cart.php'>Go back to cart</a>.</div>";
    include('includes/footer.php');
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
    echo "<div class='alert alert-warning text-center'>Order not found. <a href='cart.php'>Go back to cart</a>.</div>";
    include('includes/footer.php');
    exit;
}

// Calculate total and product names
$product_names = array_column($order_items, 'product_name');
$total_amount = 0;
foreach ($order_items as $item) {
    $total_amount += $item['price'] * $item['quantity'];
}
$product_name_str = implode(', ', $product_names);
$formatted_amount = number_format($total_amount, 2);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Payment Gateway - LokiMart</title>
<link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="css/bootstrap-icons.css">
<link rel="stylesheet" href="styles/style.css">
</head>
<body>

<?php include ('includes/header.php'); ?>

<main class="container my-5">
    <section class="card p-4 mx-auto pg-form">
        <h2 class="text-center mb-3 custom-black-css">Payment Gateway</h2>

        <p class="text-center custom-black-css">
            You are about to pay for <strong><?= htmlspecialchars($product_name_str) ?></strong>
            the sum of <strong>ZK<?= $formatted_amount ?></strong>.
        </p>

        <form method="post" action="logic/payment_callback.php" class="mt-4">
            <input type="hidden" name="order_id" value="<?= $order_id ?>">

            <div class="mb-3">
                <label for="first_name" class="form-label">First Name</label>
                <input type="text" name="first_name" id="first_name" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="last_name" class="form-label">Last Name</label>
                <input type="text" name="last_name" id="last_name" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" name="email" id="email" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="mobile_money_operator">Mobile Money Operator</label>
                <select class="option" name="mobile_money_operator" required>
                    <option value="Airtel">Airtel</option>
                    <option value="MTN">MTN</option>
                    <option value="Zamtel">Zamtel</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="mobile_money_number" class="form-label">Mobile Money Number</label>
                <input type="text" name="mobile_money_number" id="mobile_money_number" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-success w-100">Pay</button>
        </form>

        <div class="mt-4 text-center custom-black-css">
            <p class="mb-1"><strong>Mobile Money Providers Supported:</strong></p>
            <p>Airtel | MTN | Zamtel</p>
        </div>
    </section>
</main>

<?php include ('includes/footer.php'); ?>


<script src="bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="scripts/script.js"></script>
</body>
</html>
