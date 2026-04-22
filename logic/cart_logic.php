<?php
include (__DIR__ . '/../includes/conn.php');
include (__DIR__ . '/../includes/session.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'], $_POST['quantity'])) {
    $product_id = intval($_POST['product_id']);
    $quantity = max(1, intval($_POST['quantity'])); // Ensure quantity is at least 1

    $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();
        $cart_item = [
            'image_url' => $product['image_url'],
            'id' => $product['id'],
            'name' => $product['name'],
            'price' => $product['price'],
            'quantity' => $quantity,
            'total' => $product['price'] * $quantity,
        ];

        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        $found = false;
        foreach ($_SESSION['cart'] as &$item) {
            if ($item['id'] === $product_id) {
                $item['quantity'] += $quantity;
                $item['total'] = $item['quantity'] * $item['price'];
                $found = true;
                break;
            }
        }
        if (!$found) {
            $_SESSION['cart'][] = $cart_item;
        }

        header('Location: cart.php');
        exit;
    } else {
        die("Product not found.");
    }
}

if (isset($_GET['action'], $_GET['id']) && $_GET['action'] === 'remove') {
    $product_id = intval($_GET['id']);
    if (isset($_SESSION['cart'])) {
        $_SESSION['cart'] = array_filter($_SESSION['cart'], fn($item) => $item['id'] !== $product_id);
    }
    header('Location: cart.php');
    exit;
}

if (isset($_GET['action']) && $_GET['action'] === 'clear') {
    unset($_SESSION['cart']);
    header('Location: cart.php');
    exit;
}

// Fetch products for the carousel
$productsPerSlide = 5;
$productsQuery = $conn->query("SELECT * FROM products LIMIT 20"); // adjust limit as needed
$allProducts = $productsQuery->fetch_all(MYSQLI_ASSOC);

// Split products into chunks for carousel slides
$chunks = array_chunk($allProducts, $productsPerSlide);