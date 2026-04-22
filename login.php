<?php
$currentPage = basename($_SERVER['PHP_SELF']);
include ('logic/login_process.php');

// Retrieve and clear any login errors from session
$errors = $_SESSION['login_errors'] ?? [];
unset($_SESSION['login_errors']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - LokiMart</title>
    <link rel="stylesheet" href="css/bootstrap-icons.css">
    <link rel="stylesheet" href="styles/style.css"> 
</head>
<body>
    <header>
        <!-- Left: Logo -->
        <img src="images/LM_logo.png" alt="LokiMart Logo" class="logo">

        <!-- Center: Title -->
        <h1>Login to LokiMart</h1>

        <!-- Hamburger icon (only on small screens) -->
        <button class="hamburger" id="hamburgerBtn" aria-label="Toggle Menu">
            <i class="bi bi-list-ul"></i>
        </button>

        <!-- Right: Navigation -->
        <nav id="mainNav">
            <ul class="nav-icons" id="navLinks">
                <?php if (isset($_SESSION['username'])): ?>
                <li><a href="logout.php" title="Logout" class="<?= ($currentPage === 'logout.php') ? 'active-link' : '' ?>">
                    <i class="bi bi-box-arrow-left"></i> <span class="nav-text">Logout</span>
                </a></li>
                <?php else: ?>
                    <li><a href="register.php" title="Register" class="<?= ($currentPage === 'register.php') ? 'active-link' : '' ?>">
                        <i class="bi bi-pencil-square"></i> <span class="nav-text">Register</span>
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
    <main class="auth-container">
        <div class="auth-left">
            <button class="auth-toggle show-right" aria-label="Show Form">
              <i class="bi bi-arrow-right-circle"></i>
            </button>
            <img src="images/cart.png" alt="LokiMart Mascot" class="auth-illustration">
            <div class="auth-welcome">
                <h2>Welcome to LokiMart!</h2>
                <p>Your one-stop shop for everything awesome. <br> Join us and start shopping smart today.</p>
            </div>
            <div class="auth-socials">
                <p>Or connect using:</p>
                <div class="social-buttons">
                    <a href="https://accounts.google.com/" 
                       target="_blank" rel="noopener noreferrer" class="social-btn">
                        <i class="bi bi-google"></i> Google
                    </a>

                    <a href="https://www.facebook.com/profile.php?id=100068722265410" 
                       target="_blank" rel="noopener noreferrer" class="social-btn">
                        <i class="bi bi-facebook"></i> Facebook
                    </a>
                </div>
            </div>
        </div>
        <div class="auth-right">
            <button class="auth-toggle show-left" aria-label="Back to Info">
              <i class="bi bi-arrow-left-circle"></i>
            </button>
        <form method="POST" action="login.php">

            <!-- Show validation errors (username, password, role) -->
            <?php if (!empty($errors)): ?>
                <div class="error">
                    <?php foreach ($errors as $err): ?>
                        <p><?php echo htmlspecialchars($err); ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <!-- form fields -->
            <label for="username">Username:</label>
            <input type="text" name="username" id="username" required autofocus>

            <div class="password-wrapper">
                <label for="password">Password:</label>
                <input type="password" name="password" id="login_password" required>
                <i id="login_togglePassword" class="bi bi-eye-fill"></i>
            </div>

            <label for="role">Role:</label>
            <select class="option" name="role" required>
                <option value="Visitor">Visitor</option>
                <option value="Administrator">Administrator</option>
                <option value="Customer">Customer</option>
                <option value="Vendor">Vendor</option>
            </select>

            <button type="submit">Login</button>

            <p>Don't have an account? <a href="register.php">Register here</a></p>

        </form>
    </div>
    </main>
    <?php include ('includes/footer.php'); ?>
    <script src="scripts/script.js"></script>
</body>
</html>