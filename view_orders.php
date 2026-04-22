<?php 
include ('logic/view_orders_logic.php'); 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Orders - LokiMart</title>
    <!-- Local Bootstrap CSS -->
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/bootstrap-icons.css">
    <link rel="stylesheet" href="styles/style.css">

</head>

<body>

    <?php
    include ('includes/header.php') 
    ?>
    
    <div class="container mt-5 mb-5">

        <div class="position-relative d-flex justify-content-between align-items-center mb-4"> 
            
            <div class="d-flex align-items-center gap-2">
            <!-- Back Button (left-aligned) -->
            <a href="dashboard.php" class="btn btn-secondary">
                <i class="bi bi-arrow-left-circle"></i>
            </a>
            <div id="searchWrapper" class="d-flex align-items-center ms-2">
                <!-- Search icon visible initially -->
                <button id="searchToggle" class="btn btn-outline-secondary" type="button" aria-label="Toggle search">
                    <i class="bi bi-search"></i>
                </button>

                <!-- Hidden search bar with input + icon inside -->
                <div id="searchBarContainer" class="search-container input-group ms-2">
                    <input type="text" id="orderSearch" class="form-control" placeholder="Search orders...">
                    <button id="searchClear" class="btn btn-outline-secondary" type="button" aria-label="Clear search">
                        <i class="bi bi-search"></i>
                    </button>
                </div>
            </div>
        </div>

            <!-- Page Title -->
            <h2 class="custom-black-css position-absolute top-50 start-50 translate-middle text-center mb-0">Order Management</h2>

            <!-- Download button (right-aligned) -->
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#downloadModal">
                <i class="bi bi-download"></i>
            </button>
        </div>

        <?php if ($orders->num_rows > 0): ?>
        <div class="table-responsive">
            <table class="table table-bordered table-hover table">
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
                            <td><?= $i++ ?></td>
                            <td><?= htmlspecialchars($order['customer_name']) ?></td>
                            <td><?= htmlspecialchars($order['customer_email']) ?></td>
                            <?php 
                            $payment_status = getPaymentInfo($conn, $order_id); 
                            $isAdmin = ($_SESSION['role'] === 'Administrator');

                            // Set button class based on payment status
                            switch ($payment_status) {
                                case 'Successful':
                                    $payClass = 'text-success';
                                    break;
                                case 'Pending':
                                    $payClass = 'text-primary';
                                    break;
                                case 'Failed':
                                    $payClass = 'text-danger';
                                    break;
                                default:
                                    $payClass = 'text-secondary'; // default pencil color
                                    break;
                            }

                            $payLabel = $payment_status;
                            ?>

                            <td>
                                <!-- Pencil button -->
                                <button
                                    type="button"
                                    class="btn btn-link p-0 ms-2 align-baseline <?= $payClass ?>"
                                    data-bs-toggle="modal"
                                    data-bs-target="#paymentStatusModal<?= $order_id ?>"
                                    aria-label="Edit payment status"
                                    title="Edit payment status"
                                    <?= $isAdmin ? 'disabled style="pointer-events: none; opacity: 0.5;"' : '' ?>
                                >
                                    <i class="bi bi-pencil-fill"></i>
                                </button>..

                                <!-- Text showing payment status -->
                                <span><?= htmlspecialchars($payLabel) ?></span>
                            </td>


                            <td>ZK<?= number_format($order['total_price'], 2) ?></td>
                            <td>
                                
                                <?php
                                // Set a color class based on the status
                                switch ($order['status']) {
                                    case 'Completed':
                                        $statusColorClass = 'text-success'; // green
                                        break;
                                    case 'Shipping':
                                        $statusColorClass = 'text-warning'; // yellow
                                        break;
                                    case 'Cancelled':
                                        $statusColorClass = 'text-danger'; // red
                                        break;
                                    default:
                                        $statusColorClass = 'text-primary'; // blue/default
                                        break;
                                }
                                ?>
                                <?php $isAdmin = ($_SESSION['role'] === 'Administrator'); ?>

                                <button
                                    type="button"
                                    class="btn btn-link p-0 ms-2 align-baseline <?= $statusColorClass ?>"
                                    data-bs-toggle="modal"
                                    data-bs-target="#statusModal<?= $order['id'] ?>"
                                    aria-label="Edit status"
                                    title="Edit status"
                                    <?= $isAdmin ? 'disabled style="pointer-events: none; opacity: 0.5;"' : '' ?>
                                >
                                    <i class="bi bi-pencil-fill"></i>
                                </button>..
                                <?= htmlspecialchars($order['status']) ?>

                            </td>
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
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Order #<?= $i - 1 ?> (ID: <?= $order['id'] ?>) Details</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p><strong>Customer:</strong> <?= htmlspecialchars($order['customer_name']) ?></p>
                                        <p><strong>Email:</strong> <?= htmlspecialchars($order['customer_email']) ?></p>
                                        <p><strong>Address:</strong> <?= htmlspecialchars($order['customer_address']) ?></p>
                                        <p><strong>Total Price:</strong> ZK<?= number_format($order['total_price'], 2) ?></p>
                                        <p><strong>Order Status:</strong> <?= htmlspecialchars($order['status']) ?></p>
                                        <p><strong>Ordered By:</strong> <?= htmlspecialchars($order['created_by']) ?></p>
                                        <p><strong>Order Date:</strong> <?= date("d M Y H:i", strtotime($order['created_at'])) ?></p>
                                    </div>
                                    <h6 class="custom-black-css mt-3 text-center"><strong>Ordered Items:</strong></h6>
                                    <?php if ($order_items_result && $order_items_result->num_rows > 0): ?>
                                        <ul class="list-group m-4">
                                            <?php while ($item = $order_items_result->fetch_assoc()): ?>
                                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                                    <!-- <span>
                                                        <?= htmlspecialchars($item['order_id']) ?>
                                                    </span> -->
                                                    <span>
                                                        <?= htmlspecialchars($item['product_name']) ?> 
                                                    </span>
                                                    <span>
                                                        (x<?= $item['quantity'] ?>)
                                                    </span>
                                                    <span>
                                                        Price per item:
                                                    </span>
                                                    <span>ZK<?= number_format($item['price'], 2) ?></span>
                                                </li>
                                            <?php endwhile; ?>
                                        </ul>
                                    <?php else: ?>
                                        <p>No items found for this order.</p>
                                    <?php endif; ?>
                                    <div class="modal-footer justify-content-center">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Payment Status Action Modal -->
                        <div class="modal fade" id="paymentStatusModal<?= $order['id'] ?>" tabindex="-1">
                            <div class="modal-dialog modal-dialog-scrollable">
                                <div class="modal-content order-detail-modal">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Order #<?= $i - 1 ?> (ID: <?= $order['id'] ?>) Payment Status</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>

                                    <div class="modal-body">
                                        <h6 class="custom-black-css text-center"><strong>Ordered Items:</strong></h6>

                                        <?php
                                        $order_id = $order['id'];
                                        // Re-fetch items
                                        $order_items_result = $conn->query("
                                            SELECT product_name, quantity, price 
                                            FROM order_items 
                                            WHERE order_id = $order_id
                                        ");
                                        ?>
                                        <?php if ($order_items_result && $order_items_result->num_rows > 0): ?>
                                            <ul class="list-group m-4">
                                                <?php while ($item = $order_items_result->fetch_assoc()): ?>
                                                    <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                                                        <span><?= htmlspecialchars($item['product_name']) ?></span>
                                                        <span>(x<?= $item['quantity'] ?>)</span>
                                                        <span>Price per item:</span>
                                                        <span>ZK<?= number_format($item['price'], 2) ?></span>
                                                    </li>
                                                <?php endwhile; ?>
                                            </ul>
                                            <p class="custom-black-css text-center fw-bold">Total Price: ZK<?= number_format($order['total_price'], 2) ?></p>
                                        <?php else: ?>
                                            <p class="text-center">No items found for this order.</p>
                                        <?php endif; ?>
                                    </div>

                                    <div class="modal-footer justify-content-center gap-2 flex-wrap">
                                        <form method="post" class="status-update-form">

                                            <div class="customer_orders">
                                                <a href="logic/update_payment_status.php?order_id=<?= $order['id'] ?>&new_status=Pending"
                                                   class="btn btn-primary btn"
                                                   onclick="return confirm('Set payment to Pending?');">Pending</a>

                                                <a href="logic/update_payment_status.php?order_id=<?= $order['id'] ?>&new_status=Successful"
                                                   class="btn btn-success btn"
                                                   onclick="return confirm('Set payment to Successful?');">Successful</a>

                                                <a href="logic/update_payment_status.php?order_id=<?= $order['id'] ?>&new_status=Failed"
                                                   class="btn btn-danger btn"
                                                   onclick="return confirm('Set payment to Failed?');">Failed</a>
                                            </div>

                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <!-- Status Action Modal (Only shows ordered items + buttons) -->
                        <div class="modal fade" id="statusModal<?= $order['id'] ?>" tabindex="-1">
                            <div class="modal-dialog modal-dialog-scrollable">
                                <div class="modal-content order-detail-modal">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Order #<?= $i - 1 ?> (ID: <?= $order['id'] ?>) Status</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>

                                    <div class="modal-body">
                                        <h6 class="custom-black-css text-center"><strong>Ordered Items:</strong></h6>

                                        <?php
                                        $order_id = $order['id'];
                                        // Re-fetch items because the first query is already exhausted
                                        $order_items_result = $conn->query("
                                            SELECT product_name, quantity, price 
                                            FROM order_items 
                                            WHERE order_id = $order_id
                                        ");
                                        ?>
                                        <?php if ($order_items_result && $order_items_result->num_rows > 0): ?>
                                            <ul class="list-group m-4">
                                                <?php while ($item = $order_items_result->fetch_assoc()): ?>
                                                    <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                                                        <span><?= htmlspecialchars($item['product_name']) ?></span>
                                                        <span>(x<?= $item['quantity'] ?>)</span>
                                                        <span>Price per item:</span>
                                                        <span>ZK<?= number_format($item['price'], 2) ?></span>
                                                    </li>
                                                <?php endwhile; ?>
                                            </ul>
                                            <p class="custom-black-css text-center fw-bold">Total Price: ZK<?= number_format($order['total_price'], 2) ?></p>
                                        <?php else: ?>
                                            <p class="text-center">No items found for this order.</p>
                                        <?php endif; ?>
                                    </div>

                                    <div class="modal-footer justify-content-center gap-2 flex-wrap">
                                        <form method="post" class="status-update-form">

                                            <div class="customer_orders">
                                                <a href="logic/update_order_status.php?order_id=<?= $order['id'] ?>&new_status=Processing"
                                                   class="btn btn-primary btn"
                                                   onclick="return confirm('Set order to Processing?');">Processing</a>

                                                <a href="logic/update_order_status.php?order_id=<?= $order['id'] ?>&new_status=Shipping"
                                                   class="btn btn-warning btn"
                                                   onclick="return confirm('Set order to Shipping?');">Shipping</a>

                                                <a href="logic/update_order_status.php?order_id=<?= $order['id'] ?>&new_status=Completed"
                                                   class="btn btn-success btn"
                                                   onclick="return confirm('Set order to Completed?');">Completed</a>

                                                <a href="logic/update_order_status.php?order_id=<?= $order['id'] ?>&new_status=Cancelled"
                                                   class="btn btn-danger btn"
                                                   onclick="return confirm('Set order to Cancelled?');">Cancelled</a>
                                            </div>


                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                    <?php endwhile; ?>
                </tbody>
            </table>
            <p id="noOrdersMessage" class="custom-black-css display-none text-center mt-3 text-dark">No matching orders found.</p>
        </div>
        <?php else: ?>
            <p>No orders have been placed yet.</p>
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
