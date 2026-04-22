<?php
include (__DIR__ . '/../includes/session.php');
include (__DIR__ . '/../includes/conn.php');

if (!isset($_GET['id'])) {
    echo "Product ID is missing.";
    exit;
}

$product_id = intval($_GET['id']);

$query = "SELECT * FROM products WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "Product not found.";
    exit;
}

$product = $result->fetch_assoc();
$stmt->close();

// Fetch average rating + total reviews
$avgQuery = $conn->prepare("SELECT AVG(rating) as avg_rating, COUNT(*) as total_reviews FROM reviews WHERE product_id = ?");
$avgQuery->bind_param("i", $product_id);
$avgQuery->execute();
$avgResult = $avgQuery->get_result()->fetch_assoc();

$avgRating = round($avgResult['avg_rating'], 1);
$totalReviews = $avgResult['total_reviews'];

// Fetch all reviews with usernames
$reviewsQuery = $conn->prepare("SELECT r.*, u.username FROM reviews r JOIN users u ON r.user_id = u.id WHERE r.product_id = ? ORDER BY r.created_at DESC");
$reviewsQuery->bind_param("i", $product_id);
$reviewsQuery->execute();
$reviews = $reviewsQuery->get_result();