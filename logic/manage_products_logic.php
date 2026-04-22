<?php
include (__DIR__ . '/../includes/conn.php');
include (__DIR__ . '/../includes/session.php');

// Restrict access to vendors and admins
if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], ['Administrator', 'Vendor'])) {
    header('Location: login.php');
    exit();
}

// Handle Add Product
if (isset($_POST['add_product'])) {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $quantity = $_POST['quantity'];
    $desc = $_POST['description'];
    $imageUrl = null;

    if ($_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $imageUrl = 'uploads/' . basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], $imageUrl);
    }

    $stmt = $conn->prepare("INSERT INTO products (name, price, quantity, description, image_url) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sdiss", $name, $price, $quantity, $desc, $imageUrl);
    $stmt->execute();
    header("Location: manage_products.php");
    exit();
}

// Handle Update Product
if (isset($_POST['update_product'])) {
    $id = $_POST['product_id'];
    $name = $_POST['name'];
    $price = $_POST['price'];
    $quantity = $_POST['quantity'];
    $desc = $_POST['description'];

    $sql = "UPDATE products SET name=?, price=?, quantity=?, description=?";
    $params = [$name, $price, $quantity, $desc];
    $types = "sdis";

    if ($_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $imageUrl = 'uploads/' . basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], $imageUrl);
        $sql .= ", image_url=?";
        $params[] = $imageUrl;
        $types .= "s";
    }

    $sql .= " WHERE id=?";
    $params[] = $id;
    $types .= "i";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    header("Location: manage_products.php");
    exit();
}

// Handle Delete Product
if (isset($_POST['delete_product'])) {
    $id = $_POST['product_id'];
    $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    header("Location: manage_products.php");
    exit();
}

// Fetch all products
$products = $conn->query("SELECT id, name, price, quantity, description, image_url FROM products");