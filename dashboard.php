<?php 
include ('logic/dashboard_logic.php'); 

if (session_status() === PHP_SESSION_NONE) session_start();

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Dashboard - LokiMart</title>
    <!-- Local Bootstrap CSS -->    
    <link rel="stylesheet" href="css/bootstrap-icons.css">
	<link rel="stylesheet" href="styles/style.css">
</head>
<body>
	
    <?php
    $pageTitle = 'Dashboard'; 
    include ('includes/header.php') 
    ?>

	<main>
        <h2>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h2>
        <p>You are logged in as the <?php echo htmlspecialchars($_SESSION['role']); ?>.</p>

        <!-- Stats cards -->
        <section class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon"><i class="bi bi-chat-dots"></i></div>
                <h3>Total Messages</h3>
                <p class="stat-value"><?= htmlspecialchars($totalMessages) ?></p>
            </div>
            <div class="stat-card">
                <div class="stat-icon"><i class="bi bi-box-seam"></i></div>
                <h3>Total Products</h3>
                <p class="stat-value"><?= htmlspecialchars($totalProducts) ?></p>
            </div>

            <div class="stat-card">
                <div class="stat-icon"><i class="bi bi-basket3"></i></div>
                <h3>Total Orders</h3>
                <p class="stat-value"><?= htmlspecialchars($totalOrders) ?></p>
            </div>

            <div class="stat-card">
                <div class="stat-icon"><i class="bi bi-people"></i></div>
                <h3>Total Users</h3>
                <p class="stat-value"><?= htmlspecialchars($totalUsers) ?></p>
            </div>

            <div class="stat-card">
                <div class="stat-icon"><i class="bi bi-cash"></i></div>
                <h3>Total Revenue</h3>
                <p class="stat-value">ZK <?= htmlspecialchars($totalRevenueFormatted) ?></p>
            </div>
        </section>
        
        <section class="hero">
            <h2><?php echo htmlspecialchars($_SESSION['role']); ?> Controls</h2>
            <ul>
                <?php if ($_SESSION['role'] === 'Administrator'): ?>
                    <li><a href="manage_users.php">Manage Users</a></li>
                <?php endif; ?>

                <?php if ($_SESSION['role'] === 'Administrator' || $_SESSION['role'] === 'Vendor'): ?>
                    <li><a href="manage_products.php">Manage Products</a></li>
                    <li><a href="view_orders.php">View Orders</a></li>
                <?php endif; ?>
                    <li><a href="view_support.php">View Messages</a></li>
            </ul>
        </section>

        <section class="recent-activity dashboard-table-wrapper">
            <h2>Recent Orders</h2>
            <table class="dashboard-table-wrapper table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Customer</th>
                        <th>Total Price (ZK)</th>
                        <th>Payment Status</th>
                        <th>Order Status</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 1;
                    if (!empty($recentOrders)): ?>
                        <?php foreach ($recentOrders as $order): ?>
                            <?php
                            // Order Status
                            $status = $order['status'];
                            switch ($status) {
                                case 'Completed':
                                    $statusClass = 'order-completed';
                                    $statusIcon = '<i class="bi bi-check-circle-fill me-1 status-icon"></i>';
                                    break;
                                case 'Shipping':
                                    $statusClass = 'order-shipping';
                                    $statusIcon = '<i class="bi bi-truck me-1 status-icon"></i>';
                                    break;
                                case 'Cancelled':
                                    $statusClass = 'order-cancelled';
                                    $statusIcon = '<i class="bi bi-x-circle-fill me-1 status-icon"></i>';
                                    break;
                                default:
                                    $statusClass = 'order-processing';
                                    $statusIcon = '<i class="bi bi-clock-fill me-1 status-icon"></i>'; // e.g. Processing
                                    break;
                            }

                            // Payment Status
                            $order_id = $order['id'];
                            $payment_status = 'No payment'; // default
                            $paymentResult = $conn->query("SELECT payment_status FROM payments WHERE order_id = $order_id LIMIT 1");
                            if ($paymentResult && $paymentResult->num_rows > 0) {
                                $payment_status = $paymentResult->fetch_assoc()['payment_status'];
                            }

                            switch ($payment_status) {
                                case 'Successful':
                                    $payClass = 'payment-success';
                                    $paymentIcon = '<i class="bi bi-check-circle-fill me-1 status-icon"></i>';
                                    break;
                                case 'Pending':
                                    $payClass = 'payment-pending';
                                    $paymentIcon = '<i class="bi bi-hourglass-split me-1 status-icon"></i>';
                                    break;
                                case 'Failed':
                                    $payClass = 'payment-failed';
                                    $paymentIcon = '<i class="bi bi-x-circle-fill me-1 status-icon"></i>';
                                    break;
                                default:
                                    $payClass = 'payment-none';
                                    $paymentIcon = '<i class="bi bi-dash-circle me-1 status-icon"></i>';
                                    break;
                            }
                            ?>
                            <tr>
                                <td><?= $i++ ?></td>
                                <td><?= htmlspecialchars($order['customer_name']) ?></td>
                                <td><?= number_format($order['total_price'], 2) ?></td>
                                <td><span class="<?= $payClass ?>"><?= $paymentIcon . htmlspecialchars($payment_status) ?></span></td>
                                <td><span class="<?= $statusClass ?>"><?= $statusIcon . htmlspecialchars($status) ?></span></td>
                                <td><?= date("d M Y H:i", strtotime($order['created_at'])) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="6" class="empty-table-message">No recent orders found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>

        </section>

        <section class="recent-activity dashboard-table-wrapper">
            <h2>Recent Products</h2>
            <table class="dashboard-table-wrapper table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Product</th>
                        <th>Price (ZK)</th>
                        <th>Quantity</th>
                        <th>Added At</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($recentProducts)): ?>
                        <?php foreach ($recentProducts as $index => $product): ?>
                            <tr>
                                <td><?= $index + 1 ?></td>
                                <td><?= htmlspecialchars($product['name']) ?></td>
                                <td><?= number_format($product['price'], 2) ?></td>
                                <td><?= (int)$product['quantity'] ?></td>
                                <td><?= date("d M Y H:i", strtotime($product['created_at'])) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="5" style="text-align:center;">No products found</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </section>

        <section class="recent-activity dashboard-table-wrapper">
            <h2>Recent Users</h2>
            <table class="dashboard-table-wrapper table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Registered On</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($recentUsers)): ?>
                        <?php $i = 1; ?>
                        <?php foreach ($recentUsers as $user): ?>
                            <tr>
                                <td><?= $i++ ?></td>
                                <td><?= htmlspecialchars($user['username']) ?></td>
                                <td><?= htmlspecialchars($user['email']) ?></td>
                                <td><?= htmlspecialchars($user['role']) ?></td>
                                <td><?= date("d M Y H:i", strtotime($user['created_at'])) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center">No recent users found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </section>

        <section class="recent-activity dashboard-table-wrapper">
            <h2>Recent Messages</h2>
            <table class="dashboard-table-wrapper table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Full Name</th>
                        <th>Email</th>
                        <th>Address</th>
                        <th>Submitted By</th>
                        <th>Submitted At</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($recentMessages)): ?>
                        <?php $i = 1; ?>
                        <?php foreach ($recentMessages as $message): ?>
                            <tr>
                                <td><?= $i++ ?></td>
                                <td><?= htmlspecialchars($message['fullname']) ?></td>
								<td><?= htmlspecialchars($message['email']) ?></td>
                                <td><?= htmlspecialchars($message['address']) ?></td>
                                <td><?= htmlspecialchars($message['created_by']) ?></td>
                                <td><?= date("d M Y H:i", strtotime($message['created_at'])) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="empty-table-message">No recent messages found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </section>

    </main>
    <?php include ('includes/footer.php'); ?>
    <script src="scripts/script.js"></script>

</body>
</html>
