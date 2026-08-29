<?php
include ('includes/session.php'); // Check if the user is logged in
include ('includes/conn.php'); // Database connection

if (isset($_SESSION['payment_success'])) {
    $payment = $_SESSION['payment_success'];
    unset($_SESSION['payment_success']); // clear after showing
    ?>
    <script>
        alert("Payment Successful!\nOrder ID: <?= $payment['order_id'] ?>\nTotal: ZK<?= number_format($payment['amount'], 2) ?>");
    </script>
    <?php
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LokiMart - Products</title>
    <link rel="stylesheet" href="css/bootstrap-icons.css">
    <link rel="stylesheet" href="styles/style.css">
</head>
<body>
    <?php 
    $pageTitle = 'Products';
    include ('includes/header.php') 
    ?>
    <main>
        <section class="product-list">

            <div class="product-header">
                <h2 class="product-title">Our Products</h2>
                <div class="search-wrapper">
                    <i class="bi bi-search search-icon" id="toggleSearch"></i>
                    <div class="search-bar-container" id="searchBarContainer">
                        <input type="text" id="productSearch" class="search-input" placeholder="Search products...">
                        <i class="bi bi-search search-inside"></i>
                    </div>
                </div>
            </div>


            <div class="product-grid">
                <?php
                // Fetch products from the database
                $search = trim($_GET['search'] ?? '');
                if ($search !== '') {
                    $query = "SELECT * FROM products WHERE name LIKE ?";
                    $stmt = $conn->prepare($query);
                    $like = "%" . $search . "%";
                    $stmt->bind_param("s", $like);
                    $stmt->execute();
                    $result = $stmt->get_result();
                } else {
                    $query = "SELECT * FROM products";
                    $result = mysqli_query($conn, $query);
                }

                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo '<div class="product-card" data-name="' . strtolower($row['name']) . '">';

                        if ($row['quantity'] == 0) {
                            echo '<span class="badge out-of-stock-badge">Out of Stock</span>';
                        } elseif ($row['quantity'] <= 10) {
                            echo '<span class="badge very-low-stock-badge">Very Low Stock</span>';
                        } elseif ($row['quantity'] <= 20) {
                            echo '<span class="badge low-stock-badge">Low Stock</span>';
                        } else {
                            echo '<span class="badge in-stock-badge">In Stock</span>';
                        }

                        echo '<img src="' . $row['image_url'] . '" 
                               alt="' . $row['name'] . '" 
                               class="product-thumbnail">';
                        echo '<h3>' . $row['name'] . '</h3>';
                        echo '<p>ZK' . number_format($row['price'], 2) . '</p>';
                        echo '<a href="product_details.php?id=' . $row['id'] . '" class="btn">View Details</a>';
                        echo '</div>';
                    }
                } else {
                    echo '<p>No products available.</p>';
                }
                ?>
            </div>
            <!-- No Results Message -->
            <p id="noResultsMessage" class="no-results-message text-center mt-4">
                No matching products found.
            </p>
        </section>
    </main>
    <!-- Custom Image Modal -->
    <div id="customImageModal" class="custom-modal">
        <span id="modalClose" class="custom-modal-close">&times;</span>
        <img id="modalImage" class="custom-modal-content" alt="Full Image">
    </div><br><br>
    <?php include ('includes/footer.php'); ?>
    
    <script src="scripts/script.js"></script>
</body>
</html>
