<?php
include ('logic/cart_logic.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart - LokiMart</title>
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/bootstrap-icons.css">
    <link rel="stylesheet" href="styles/style.css">
</head>
<body>

    <?php
    $pageTitle = 'Cart'; 
    include ('includes/header.php') 
    ?>

   <main>
        <section class="cart">
            <h2 class="mb-4">Shopping Cart</h2>
            <?php if (!empty($_SESSION['cart'])): ?>
                <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Product</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Total</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $grand_total = 0;
                        foreach ($_SESSION['cart'] as $item):
                            $grand_total += $item['total'];
                        ?>
                        <tr>
                            <td>
                                <img src="<?= !empty($item['image_url']) ? $item['image_url'] : 'images/default.png' ?>"
                                     alt="<?= htmlspecialchars($item['name']) ?>"
                                     class="product-image rounded product-thumbnail"
                                     style="width: 50px; height: auto; cursor: pointer;"
                                     data-bs-toggle="modal"
                                     data-bs-target="#imageModal"
                                     data-image="<?= !empty($item['image_url']) ? $item['image_url'] : 'images/default.png' ?>">
                            </td>
                            <td><?php echo $item['name']; ?></td>
                            <td>ZK<?php echo number_format($item['price'], 2); ?></td>
                            <td><?php echo $item['quantity']; ?></td>
                            <td>ZK<?php echo number_format($item['total'], 2); ?></td>
                            <td>
                                <a href="cart.php?action=remove&id=<?php echo $item['id']; ?>" class="btn btn-danger">Remove</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td></td>
                            <td colspan="3" class="text-right">Grand Total:</td>
                            <td>ZK<?php echo number_format($grand_total, 2); ?></td>
                            <td>
                                <a href="cart.php?action=clear" class="btn btn-danger">Clear Cart</a>
                            </td>
                        </tr>
                    </tfoot>
                </table>
                </div>
                <div class="checkout">
                    <div class="container my-5">
                        <div class="row justify-content-center">
                        <div class="d-flex justify-content-center flex-wrap gap-2">
                            <a href="products.php" class="btn btn-primary">
                                <i class="i bi-arrow-left-circle"></i> Back
                            </a>
                            <a href="my_orders.php" class="btn btn-primary">
                                <i class="bi bi-box"></i> View My Orders
                            </a>
                            <a href="checkout.php" class="btn btn-primary">
                                <i class="bi bi-arrow-right-circle"></i> Proceed to Checkout 
                            </a>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <div class="container my-5">
                    <div class="row justify-content-center">
                            <div class="h3-p">
                            <h3 class="fw-bold text-center custom-black-css">Oops! Your cart is feeling lonely</h3>
                            <p class="text-center custom-black-css">Looks like you haven’t added anything yet.<br>Start shopping to fill it up!</p>
                            </div>

                            <div class="d-flex justify-content-center flex-wrap gap-1">
                                <a href="products.php" class="btn btn-primary">
                                    <i class="bi bi-arrow-left-circle"></i> Back
                                </a>
                                <a href="products.php" class="btn btn-primary">
                                    <i class="bi bi-bag"></i> Shop Now
                                </a>
                                <a href="my_orders.php" class="btn btn-primary">
                                    <i class="bi bi-box"></i> View My Orders
                                </a>
                            </div>
                    </div>
                </div>
            <?php endif; ?>
        </section>

        <!-- Product Slider -->
        <div class="container my-4 position-relative">
    <!-- Carousel -->
    <div id="productCarousel" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-inner">
            <?php
            $active = 'active';
            foreach ($chunks as $chunk):
            ?>
            <div class="carousel-item <?= $active ?>">
                <div class="d-flex justify-content-between gap-2">
                    <?php foreach ($chunk as $product): ?>
                        <div class="text-center" style="width:18%;">
                            <a href="product_details.php?id=<?= $product['id'] ?>">
                                <img src="<?= !empty($product['image_url']) ? $product['image_url'] : 'images/default.png' ?>" 
                                     class="d-block rounded mx-auto shadow-sm p-1" 
                                     alt="<?= htmlspecialchars($product['name']) ?>" 
                                     style="height:120px; width:100%; object-fit:contain; cursor: pointer; border: 2px solid #ff69b4;">
                            </a>
                            <p class="custom-black-css mb-0"><?= htmlspecialchars($product['name']) ?></p>
                            <small class="custom-black-css fw-bold">ZK<?= number_format($product['price'],2) ?></small>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php
            $active = ''; 
            endforeach;
            ?>
        </div>
    </div>

    <!-- Controls outside carousel -->
    <button class="btn btn-dark position-absolute top-50 start-0 translate-middle-y d-none d-md-block" type="button" data-bs-target="#productCarousel" data-bs-slide="prev"style="margin-left: -40px;">
        <i class="i bi-chevron-double-left"></i>
    </button>
    <button class="btn btn-dark position-absolute top-50 end-0 translate-middle-y d-none d-md-block" type="button" data-bs-target="#productCarousel" data-bs-slide="next" style="margin-right: -40px;">
        <i class="bi bi-chevron-double-right"></i>
    </button>
    <!-- Small screen buttons (below carousel) -->
    <div class="d-flex justify-content-center mt-2 d-md-none">
        <button class="btn btn-dark me-2" type="button" data-bs-target="#productCarousel" data-bs-slide="prev">
            <i class="i bi-chevron-double-left"></i>
        </button>
        <button class="btn btn-dark" type="button" data-bs-target="#productCarousel" data-bs-slide="next">
            <i class="bi bi-chevron-double-right"></i>
        </button>
    </div>
</div>

    
    </main>
    <!-- Image Modal -->
    <div id="imageModal" class="modal fade" tabindex="-1">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-transparent border-0 text-center">
          <div class="modal-body p-0 d-flex justify-content-center align-items-center" style="min-height: 80vh;">
            <img id="fullImage" src="" class="img-fluid rounded shadow" alt="Full view"
                 style="max-width: 90%; max-height: 80vh; object-fit: contain;">
          </div>
        </div>
      </div>
    </div>
    <!-- Local Bootstrap JS -->
    <script src="js/xlsx.full.min.js"></script>
    <script src="bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="scripts/script.js"></script>
    <?php include ('includes/footer.php'); ?>
</body>