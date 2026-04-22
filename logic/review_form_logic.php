<?php
include (__DIR__ . '/../includes/session.php');
include (__DIR__ . '/../includes/conn.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user_id'])) {
    $product_id = intval($_POST['product_id']);
    $user_id = $_SESSION['user_id'];
    $rating = intval($_POST['rating']);
    $comment = trim($_POST['comment']);

    if ($rating >= 1 && $rating <= 5 && !empty($comment)) {
        $stmt = $conn->prepare("INSERT INTO reviews (product_id, user_id, rating, comment) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iiis", $product_id, $user_id, $rating, $comment);
        $stmt->execute();
    }
}

header("Location: ../product_details.php?id=" . $product_id);
exit;
