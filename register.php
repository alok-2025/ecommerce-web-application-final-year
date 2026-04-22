<?php
$currentPage = basename($_SERVER['PHP_SELF']);
include('logic/register_process.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - LokiMart</title>
    <link rel="stylesheet" href="css/bootstrap-icons.css">
    <link rel="stylesheet" href="styles/style.css">
</head>
<body>
    <header>
        <!-- Left: Logo -->
        <img src="images/LM_logo.png" alt="LokiMart Logo" class="logo">

        <!-- Center: Title -->
        <h1>Register at LokiMart</h1>

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
    <main class="auth-container">
        <div class="auth-left">
            <button class="auth-toggle show-right" aria-label="Show Form">
              <i class="bi bi-arrow-right-circle"></i>
            </button>
            <img src="images/cart.png" alt="LokiMart Mascot" class="auth-illustration">
            <div class="auth-welcome">
                <h2>Join LokiMart Today!</h2>
                <p>Create your account and <br> enjoy exclusive offers and fast shopping.</p>
            </div>
            <div class="auth-socials">
                <p>Or sign up using:</p>
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
        <form method="POST" enctype="multipart/form-data">
            
            
            <label for="username">Username:</label>
            <input type="text" name="username" required autofocus>
            
            <label for="email">Email:</label>
            <input type="email" name="email" required oninput="validateEmail(this)">
            <script>
                function validateEmail(input) {
                    const msg = document.getElementById('email-msg');
                    if (!input.validity.valid) {
                        msg.textContent = 'Please enter a valid email.';
                    } else {
                        msg.textContent = '';
                    }
                }
            </script>
            
            <div class="password-wrapper">
                <label for="password">Password:</label>
                <input type="password" id="register_password" required>
                <i id="register_togglePassword" class="bi bi-eye-fill"></i>
            </div>
            <div class="password-wrapper">
                <label for="confirm_password">Re-enter Password:</label>
                <input type="password" id="register_confirm_password" required>
                <i id="register_toggleConfirmPassword" class="bi bi-eye-fill"></i>
            </div>

            <label for="role">Role:</label>
            <select class="option" name="role" required>
                <option value="Visitor">Visitor</option>
                <option value="Administrator">Administrator</option>
                <option value="Customer">Customer</option>
                <option value="Vendor">Vendor</option>
            </select>

            <div class="custom-file-input">
                <label for="profile_pic" class="custom-upload-label">Choose File</label>
                <span id="file-name">No file chosen</span>
                <input type="file" name="profile_pic" id="profile_pic" accept="image/*" hidden>
            </div>
            
            <button type="submit">Register</button>
            
            <p>Already have an account? <a href="login.php">Login here</a></p>

        </form>
        </div>
    </main>

    <?php include ('includes/footer.php'); ?>

    <script src="scripts/script.js"></script>
</body>

</html>