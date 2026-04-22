<?php 
include ('logic/checkout_logic.php'); 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - LokiMart</title>
    <link rel="stylesheet" href="css/bootstrap-icons.css">
    <link rel="stylesheet" href="styles/style.css">
</head>
<body>

    <?php include ('includes/header.php') ?>
   
    <main>
        <section id="checkout">
            <h2>Checkout</h2>
            <div class="cart-summary">
                <h3>Order Summary</h3>
                <table>
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $grand_total = 0;
                        foreach ($_SESSION['cart'] as $item):
                            $grand_total += $item['total'];
                        ?>
                        <tr>
                            <td><?php echo $item['name']; ?></td>
                            <td>ZK<?php echo number_format($item['price'], 2); ?></td>
                            <td><?php echo $item['quantity']; ?></td>
                            <td>ZK<?php echo number_format($item['total'], 2); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3" class="text-right">Grand Total:</td>
                            <td>ZK<?php echo number_format($grand_total, 2); ?></td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <h3>Billing Details</h3>
            <form method="post" class="checkout-form">
                <label for="name">Full Name:</label>
                <input type="text" id="name" name="name" required>

                <label for="email">Email Address:</label>
                <input type="email" id="email" name="email" required>

                <label for="address">Shipping Address:</label>
                <textarea id="address" name="address" rows="4" required></textarea>

                <div class="checkout-buttons">
                    <button type="submit" class="btn-po">Place Order</button>
                    <a href="cart.php" class="btn-po">Cancel</a>
                </div>
            </form>
        </section>
    </main>
    
    <?php include ('includes/footer.php'); ?>
    <script src="scripts/script.js"></script>

</body>
</html>
