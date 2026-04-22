<?php 
include ('logic/support_logic.php'); 
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact - LokiMart</title>
    <link rel="stylesheet" href="css/bootstrap-icons.css">
    <link rel="stylesheet" href="styles/style.css"> 
</head>
<body>
    <?php
    include('includes/header.php'); 
    ?>

    <main class="support-section auth-container">
        <!-- Left column -->
        <div class="auth-left">
            <button class="auth-toggle show-right" aria-label="Show Form">
              <i class="bi bi-arrow-right-circle"></i>
            </button>
            <img src="images/support_headset.png" alt="Support Illustration" class="auth-illustration">
            <div class="auth-welcome">
                <h2>Need Help?</h2>
                <p>Our support team is here to assist you. <br> Please fill out the form to get in touch.</p>
            </div>
            <div class="auth-socials">
                <p>Or reach us via:</p>
                <div class="social-buttons">
                    
                    <a href="https://www.instagram.com/its_alokv_20/" 
                       target="_blank" rel="noopener noreferrer" class="social-btn instagram">
                        <i class="bi bi-instagram"></i> Instagram
                    </a>

                    <a href="https://www.facebook.com/profile.php?id=100068722265410" 
                       target="_blank" rel="noopener noreferrer" class="social-btn">
                        <i class="bi bi-facebook"></i> Facebook
                    </a>
                    
                </div>
            </div>
        </div>
        <!-- Right column -->
        <div class="auth-right">
            <button class="auth-toggle show-left" aria-label="Back to Info">
              <i class="bi bi-arrow-left-circle"></i>
            </button>
        <h2>Contact Support</h2><br>

        <?php if ($message_sent): ?>
            <p style="color: green;">✅ Your message has been sent successfully.</p>
        <?php elseif (!empty($error_message)): ?>
            <p style="color: red;">❌ <?php echo htmlspecialchars($error_message); ?></p>
        <?php endif; ?>

        <form method="post" action="support.php">
            <label for="fullname">Fullname:</label>
            <input type="text" id="fullname" name="fullname" required>

            <label for="phone">Phone #:</label>
            <input type="text" id="phone" name="phone" required>

            <label for="address">Address:</label>
            <input type="text" id="address" name="address" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>

            <label for="message">Message:</label>
            <textarea id="message" name="message" cols="38" rows="5" required></textarea><br><br>

            <button type="submit">Send Message</button>
        </form>
        </div>
    </main>

    <?php include('includes/footer.php');?>
    <script src="scripts/script.js"></script>

</body>
</html>