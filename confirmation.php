<?php 
include ('logic/confirmation_logic.php'); 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation - LokiMart</title>
    <link rel="stylesheet" href="css/bootstrap-icons.css">
    <link rel="stylesheet" href="styles/style.css">
</head>
<body>
   
    <?php include ('includes/header.php') ?>

    <main>
        <div id="invoice-print">
        <section id="confirmation">
            <h2>Order Confirmation</h2>
            <p>Thank you for your order, <strong><?php echo htmlspecialchars($order['customer_name']); ?></strong>!</p>
            <p>Your order ID is <strong>#<?php echo $order_id; ?></strong>.</p>
            <p>A confirmation email has been sent to <strong><?php echo htmlspecialchars($order['customer_email']); ?></strong>.</p>
            <p>Ordered by: <strong><?php echo htmlspecialchars($order['created_by']); ?></strong></p>
            
            <h3>Order Summary</h3>
            <table>
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $grand_total = 0;
                    foreach ($order_items as $item):
                        $total = $item['price'] * $item['quantity'];
                        $grand_total += $total;
                    ?>
                    <tr>
                        <td><?php echo htmlspecialchars($item['product_name']); ?></td>
                        <td><?php echo (int)$item['quantity']; ?></td>
                        <td>ZK<?php echo number_format($item['price'], 2); ?></td>
                        <td>ZK<?php echo number_format($total, 2); ?></td>
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
            <h3>Shipping Address</h3>
            <p><?php echo nl2br(htmlspecialchars($order['customer_address'])); ?></p>
            <div class="checkout-buttons">
                <a href="payment_gateway.php?order_id=<?= $order_id ?>" class="btn-po">Proceed to Payment</a>
                <a href="products.php" class="btn-po">Continue Shopping</a>
                <button onclick="printInvoice()" class="btn-po">Print / Save as PDF</button>
            </div>
        </section>
    </div>
    </main>

    <?php include ('includes/footer.php'); ?>

    <script>
        function printInvoice() {
            const contentElement = document.getElementById('invoice-print');

            if (!contentElement) {
                alert("Invoice content not found");
                return;
            }

            const content = contentElement.innerHTML;
            const url = window.location.href;

            const win = window.open('', '', 'height=900,width=1000');
            win.document.write(`
                <html>
                <head>
                    <title>Order Confirmation - LokiMart</title>
                    <link rel="stylesheet" type="text/css" href="/styles/style.css">
                    <style>
                        @font-face {
                            font-family: 'Poppins';
                            src: url('/fonts/Poppins-Regular.woff2') format('woff2'),
                                 url('/fonts/Poppins-Regular.woff') format('woff');
                            font-weight: 400;
                            font-style: normal;
                        }

                        @font-face {
                            font-family: 'Poppins';
                            src: url('/fonts/Poppins-Medium.woff2') format('woff2'),
                                 url('/fonts/Poppins-Medium.woff') format('woff');
                            font-weight: 500;
                            font-style: normal;
                        }

                        @font-face {
                            font-family: 'Poppins';
                            src: url('/fonts/Poppins-Bold.woff2') format('woff2'),
                                 url('/fonts/Poppins-Bold.woff') format('woff');
                            font-weight: 700;
                            font-style: normal;
                        }
                        body {
                            font-family: Arial, sans-serif;
                            padding: 40px;
                            color: #000;
                        }

                        h2, h3 {
                            text-align: center;
                            font-weight: 700;
                        }

                        p {
                            font-size: 16px;
                            margin: 10px 0;
                            text-align: center;
                        }

                        table {
                            width: 100%;
                            border-collapse: collapse;
                            margin-top: 30px;
                        }

                        th, td {
                            padding: 12px 15px;
                            border: 1px solid #ccc;
                            text-align: center;
                        }

                        tfoot td {
                            font-weight: bold;
                            text-align: right;
                        }

                        .checkout-buttons {
                            display: none !important;
                        }

                        .footer-url {
                            position: fixed;
                            bottom: 20px;
                            left: 30px;
                            font-size: 12px;
                            color: #555;
                        }

                        @page {
                            margin: 40px;
                        }

                        @media print {
                            .checkout-buttons, .btn-po {
                                display: none !important;
                            }
                        }
                    </style>
                </head>
                <body>
                    ${content}
                    <div class="footer-url">${url}</div>
                </body>
                </html>
            `);

            win.document.close();
            win.focus();
            win.print();
            win.close();
        }
    </script>
    <script src="scripts/script.js"></script>
</body>
</html>