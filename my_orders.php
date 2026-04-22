<?php 
include ('logic/my_orders_logic.php'); 
?>
<!DOCTYPE html>
<html>
<head>
    <title>My Orders - LokiMart</title>
    <!-- Local Bootstrap CSS -->
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/bootstrap-icons.css">
    <link rel="stylesheet" href="styles/style.css">
</head>
<body>

    <?php include ('includes/header.php') ?>
    
        <div class="container mt-5 mb-5">

        <div class="position-relative d-flex justify-content-between align-items-center mb-4">

            <div class="d-flex align-items-center gap-2">
                <!-- Back Button -->
                <a href="cart.php" class="btn btn-secondary">
                    <i class="bi bi-arrow-left-circle"></i>
                </a>
                <div id="searchWrapper" class="d-flex align-items-center ms-2">
                    <!-- Search icon visible initially -->
                    <button id="searchToggle" class="btn btn-outline-secondary" type="button" aria-label="Toggle search">
                        <i class="bi bi-search"></i>
                    </button>

                    <!-- Hidden search bar with input + icon inside -->
                    <div id="searchBarContainer" class="search-container input-group ms-2">
                        <input type="text" id="orderSearch" class="form-control" placeholder="Search products...">
                        <button id="searchClear" class="btn btn-outline-secondary" type="button" aria-label="Clear search">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Page Title -->
            <h2 class="custom-black-css position-absolute top-50 start-50 translate-middle text-center mb-0">My Orders</h2>

            <!-- Download button -->
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#downloadModal">
                <i class="bi bi-download"></i>
        </div>


        <?php if ($orders->num_rows > 0): ?>
        <div class="table-responsive">
        <table class="table table-bordered table-hover">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Customer Name</th>
                    <th>Email</th>
                    <th>Payment Status</th>
                    <th>Total Price</th>
                    <th>Order Status</th>
                    <th>Ordered By</th>
                    <th>Order Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $i = 1; 
                while ($order = $orders->fetch_assoc()):
                 $order_id = $order['id'];
                    $order_items_result = $conn->query("
                        SELECT product_name, quantity, price 
                        FROM order_items 
                        WHERE order_id = $order_id
                    ");
                ?>
                    <tr>
                        <?php
                        // Define icon HTML and color class based on status
                        $status = $order['status'];
                        switch ($status) {
                            case 'Completed':
                                $statusIcon = '<i class="bi bi-check-circle-fill text-success me-1"></i>';
                                break;
                            case 'Shipping':
                                $statusIcon = '<i class="bi bi-truck text-warning me-1"></i>';
                                break;
                            case 'Cancelled':
                                $statusIcon = '<i class="bi bi-x-circle-fill text-danger me-1"></i>';
                                break;
                            default:
                                $statusIcon = '<i class="bi bi-clock-fill text-secondary me-1"></i>'; // e.g. Processing
                                break;
                        }

                        // Fetch payment status for this order
                        $order_id = $order['id'];
                        $payment_status = 'No payment'; // default
                        $paymentResult = $conn->query("SELECT payment_status FROM payments WHERE order_id = $order_id LIMIT 1");
                        if ($paymentResult && $paymentResult->num_rows > 0) {
                            $payment_status = $paymentResult->fetch_assoc()['payment_status'];
                        }

                        // Define icon and color class for payment status
                        switch ($payment_status) {
                            case 'Successful':
                                $paymentIcon = '<i class="bi bi-check-circle-fill text-success me-1"></i>';
                                break;
                            case 'Pending':
                                $paymentIcon = '<i class="bi bi-hourglass-split text-primary me-1"></i>';
                                break;
                            case 'Failed':
                                $paymentIcon = '<i class="bi bi-x-circle-fill text-danger me-1"></i>';
                                break;
                            default:
                                $paymentIcon = '<i class="bi bi-dash-circle text-secondary me-1"></i>'; // no payment
                                break;
                        }
                        ?>

                        <td><?= $i++ ?></td>
                        <td><?= htmlspecialchars($order['customer_name']) ?></td>
                        <td><?= htmlspecialchars($order['customer_email']) ?></td>
                        <td><?= $paymentIcon . htmlspecialchars($payment_status) ?></td>
                        <td>ZK<?= number_format($order['total_price'], 2) ?></td>
                        <td><?= $statusIcon . htmlspecialchars($status) ?></td>
                        <td><?= htmlspecialchars($order['created_by']) ?></td>
                        <td><?= date("d M Y H:i", strtotime($order['created_at'])) ?></td>
                        <td>
                            <button class="btn btn-info" data-bs-toggle="modal" data-bs-target="#orderModal<?= $order['id'] ?>">
                                <i class="bi bi-eye"></i>
                            </button>
                        </td>
                    </tr>

                    <!-- Order Detail Modal -->
                    <div class="modal fade" id="orderModal<?= $order['id'] ?>" tabindex="-1">
                        <div class="modal-dialog modal-dialog-scrollable">
                            <div class="modal-content order-detail-modal customer-modal">
                                <div class="modal-header">
                                    <h5 class="modal-title">Order #<?= $i - 1 ?> (ID: <?= $order['id'] ?>) Details</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div id="print-area-<?= $order['id'] ?>">
                                <div class="modal-body">
                                    
                                    <p><strong>Customer:</strong> <?= htmlspecialchars($order['customer_name']) ?></p>
                                    <p><strong>Email:</strong> <?= htmlspecialchars($order['customer_email']) ?></p>
                                    <p><strong>Address:</strong> <?= htmlspecialchars($order['customer_address']) ?></p>
                                    <p><strong>Total Price:</strong> $<?= number_format($order['total_price'], 2) ?></p>
                                    <p><strong>Order Status:</strong> <?= htmlspecialchars($order['status']) ?></p>
                                    <p><strong>Ordered By:</strong> <?= htmlspecialchars($order['created_by']) ?></p>
                                    <p><strong>Order Date:</strong> <?= date("d M Y H:i", strtotime($order['created_at'])) ?></p>
                                </div>

                                <h6 class="custom-black-css mt-3 text-center">Ordered Items:</h6>
                                <?php
                                    $order_items_result = $conn->query("SELECT product_name, quantity, price FROM order_items WHERE order_id = $order_id");
                                ?>
                                <?php if ($order_items_result && $order_items_result->num_rows > 0): ?>
                                    <ul class="list-group m-4">
                                        <?php while ($item = $order_items_result->fetch_assoc()): ?>
                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                <span><?= htmlspecialchars($item['product_name']) ?></span>
                                                <span>(x<?= $item['quantity'] ?>)</span>
                                                <span>Price per item:</span>
                                                <span>$<?= number_format($item['price'], 2) ?></span>
                                            </li>
                                        <?php endwhile; ?>
                                    </ul>
                                <?php else: ?>
                                    <p class="text-center">No items found for this order.</p>
                                <?php endif; ?>
                                </div>
                                <div class="modal-footer justify-content-center">

                                    <?php if ($order['status'] === 'Processing' && $order['created_by'] === $_SESSION['username']): ?>
                                        <a href="logic/cancel_order.php?order_id=<?= $order['id'] ?>" 
                                           class="btn btn-danger" 
                                           onclick="return confirm('Are you sure you want to cancel this order?');">
                                           Cancel
                                        </a>
                                    <?php endif; ?>

                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </tbody>
        </table>
        <p id="noOrdersMessage" class="custom-black-css text-center mt-3 text-muted" style="display: none;">No matching orders found.</p>
        </div>
        <?php else: ?>
            <p>You haven't placed any orders yet.</p>
        <?php endif; ?>
        <!-- Download Options Modal -->
            <div class="modal fade" id="downloadModal" tabindex="-1">
              <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title">Download Orders Table</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                  </div>
                  <div class="modal-body text-center">
                    <p class="custom-black-css">Select a format to download:</p>
                    <button class="btn btn-outline-primary m-2" onclick="downloadAsCSV()">CSV</button>
                    <button class="btn btn-outline-success m-2" onclick="downloadAsXLS()">XLS</button>
                    <button class="btn btn-outline-dark m-2" onclick="downloadAsXML()">XML</button>
                  </div>
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
</html>