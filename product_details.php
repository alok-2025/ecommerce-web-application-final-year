<?php 
include ('logic/product_details_logic.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $product['name']; ?> - LokiMart</title>
    <link rel="stylesheet" href="css/bootstrap-icons.css">
    <link rel="stylesheet" href="styles/style.css">
</head>
<body>
    
    <?php
    $pageTitle = 'Product Details'; 
    include ('includes/header.php') 
    ?>

    <main>
        <section class="product-details">
            <div class="product-wrapper">
                <img src="<?php echo $product['image_url']; ?>" 
                alt="<?php echo $product['name']; ?>" 
                class="product-thumbnail">
                <div class="product-info">
                    <h2><?php echo $product['name']; ?></h2>

                    <p class="price">ZK<?php echo number_format($product['price'], 2); ?></p>
                    <p class="description"><?php echo $product['description']; ?></p>

                    <?php
                    $is_user = isset($_SESSION['role']) && $_SESSION['role'] === 'Visitor';
                    ?>

                    <!-- Ratings Summary -->
                    <div class="rating-summary">
                        <p>⭐ <?php echo $avgRating ?: 'No ratings yet'; ?> 
                           (<?php echo $totalReviews; ?> reviews)</p>
                    </div>

                    <hr>

                    <!-- Add to Cart Form -->
                    <form action="cart.php" method="POST">
                        <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                        <div class="quantity-row">
                            <label for="quantity">Quantity:</label>
                            <input type="number" name="quantity" id="quantity" min="1" value="1" required
                            <?= $is_user ? 'disabled' : '' ?>
                            class="<?= $is_user ? 'input-disabled' : '' ?>">
                        </div>
                        
                        <button type="submit"
                        class=" <?= $is_user ? 'btn-disabled' : '' ?>"
                        <?= $is_user ? 'disabled' : '' ?>>Add to Cart</button>
                    </form>
                    <div class="back-button">
                        <a href="products.php" class="btn">Back</a>
                    </div>

                    
                </div>

                <div class="reviews-wrapper">
                    <!-- Customer Reviews -->
                    <div class="reviews-section">
                        <h3>Customer Reviews</h3>
                        <?php if ($reviews->num_rows > 0): ?>
                            <?php while ($review = $reviews->fetch_assoc()): ?>
                                <div class="review">
                                    <strong><?php echo htmlspecialchars($review['username']); ?></strong>
                                    <?php echo str_repeat('⭐', $review['rating']); ?>
                                    <p><?php echo htmlspecialchars($review['comment']); ?></p>
                                    <small><?php echo $review['created_at']; ?></small>
                                </div>
                                <hr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <p>No reviews yet. Be the first to review!</p>
                        <?php endif; ?>
                    </div>

                    <!-- Review Form -->
                    <?php if (isset($_SESSION['user_id'])): ?>
                    <div class="review-form-section">
                        <h4>Leave a Review</h4>
                        <form action="logic/review_form_logic.php" method="POST">
                            <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">

                            <label for="rating">Rating:</label>
                            <select name="rating" id="rating" required>
                                <option value="">-- Select --</option>
                                <option value="5">⭐⭐⭐⭐⭐</option>
                                <option value="4">⭐⭐⭐⭐</option>
                                <option value="3">⭐⭐⭐</option>
                                <option value="2">⭐⭐</option>
                                <option value="1">⭐</option>
                            </select>

                            <label for="comment">Comment:</label>
                            <textarea name="comment" id="comment" cols="38" rows="5" required></textarea>

                            <button type="submit" class="btn btn-com">Submit Review</button>
                        </form>
                    </div>
                    <?php else: ?>
                        <div class="review-form-section">
                            <p><a href="login.php">Log in</a> to leave a review.</p>
                        </div>
                    <?php endif; ?>
                </div>

            </div>
        </section>
    </main>
    <br><br><br>
    <!-- Custom Image Modal -->
    <div id="customImageModal" class="custom-modal">
      <span id="modalClose" class="custom-modal-close">&times;</span>
      <img id="modalImage" class="custom-modal-content" alt="Full Image">
    </div>

    <?php include ('includes/footer.php'); ?>

<script src="scripts/script.js"></script>
</body>
</html>
