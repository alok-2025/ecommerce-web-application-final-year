<?php 
$currentPage = basename($_SERVER['PHP_SELF']);
$rootPath = dirname($_SERVER['SCRIPT_NAME']);
?>
<header>
    <!-- Left: Logo -->
    <a href="index.php">
        <img src="images/LM_logo.png" alt="LokiMart Logo" class="logo" title="LokiMart">
    </a>

    <!-- Center: Title -->
    <h1><?php echo isset($pageTitle) ? htmlspecialchars($pageTitle) : 'LokiMart'; ?></h1>


    <!-- Hamburger icon (only on small screens) -->
    <button class="hamburger" id="hamburgerBtn" aria-label="Toggle Menu">
        <i class="bi bi-list-ul"></i>
    </button>

    <!-- Right: Navigation -->
    <nav id="mainNav">
        <ul class="nav-icons" id="navLinks">
            <?php if ($_SESSION['role'] === 'Administrator' || $_SESSION['role'] === 'Vendor'): ?>
            <li><a href="dashboard.php" title="Dashboard" class="<?= ($currentPage === 'dashboard.php') ? 'active-link' : '' ?>">
                <i class="bi bi-speedometer"></i> <span class="nav-text">Dashboard</span>
            </a></li>
            <?php endif; ?>
            <li><a href="support.php" title="Support" class="<?= ($currentPage === 'support.php') ? 'active-link' : '' ?>">
                <i class="bi bi-question-circle"></i> <span class="nav-text">Support</span>
            </a></li>
            <li><a href="index.php" title="Home" class="<?= ($currentPage === 'index.php') ? 'active-link' : '' ?>">
                <i class="bi bi-house-door"></i> <span class="nav-text">Home</span>
            </a></li>
            <li><a href="products.php" title="Products" class="<?= ($currentPage === 'products.php') ? 'active-link' : '' ?>">
                <i class="bi bi-shop"></i> <span class="nav-text">Products</span>
            </a></li>
            <?php if (isset($_SESSION['role']) && $_SESSION['role'] !== 'Visitor'): ?>
                <li><a href="cart.php" title="Cart" class="<?= ($currentPage === 'cart.php') ? 'active-link' : '' ?>">
                    <i class="bi bi-cart"></i> <span class="nav-text">Cart</span>
                </a></li>
            <?php endif; ?>

            <?php if (isset($_SESSION['username'])): ?>
                <li><a href="logout.php" title="Logout" class="<?= ($currentPage === 'logout.php') ? 'active-link' : '' ?>">
                    <i class="bi bi-box-arrow-left"></i> <span class="nav-text">Logout</span>
                </a></li>
            <?php else: ?>
                <li><a href="login.php" title="Login" class="<?= ($currentPage === 'login.php') ? 'active-link' : '' ?>">
                    <i class="bi bi-box-arrow-in-right"></i> <span class="nav-text">Login</span>
                </a></li>
            <?php endif; ?>

            <li>
                <a href="profile.php" title="Profile">
                    <img src="uploads/<?php echo htmlspecialchars($_SESSION['profile_pic'] ?? 'default_img.png'); ?>"
                    alt="Profile"
                    width="30" height="30"
                    class="profile-pic">
                </a>
            </li>
            
        </ul>
    </nav>
</header>
<script src="<?= $rootPath ?>/script/script.js"></script>
