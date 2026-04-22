<?php
include ('logic/profile_logic.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Your Profile - LokiMart</title>
    <!-- Local Bootstrap CSS -->
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/bootstrap-icons.css">
    <link rel="stylesheet" href="styles/style.css">
</head>

<body>

    <?php
    $pageTitle = 'Your Profile'; 
    include ('includes/header.php') 
    ?>

    <main class="auth-container">
        <!-- Left Column -->
        <div class="auth-left">
            <button class="auth-toggle show-right" aria-label="Show Form">
                <i class="bi bi-arrow-right-circle"></i>
            </button>
            <img src="images/default_pic.png" alt="Profile Illustration" class="auth-illustration">
            <div class="auth-welcome">
                <h2>Manage Your Profile</h2>
                <p>Update your personal details, change your password, <br>or upload a new profile picture here.</p>
            </div>
            <div class="auth-socials">
                <p>Connect with us:</p>
                <div class="social-buttons">
                    <a href="https://www.instagram.com/alokv.ermaa/" 
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

        <!-- Right Column -->
        <div class="auth-right">
            <button class="auth-toggle show-left" aria-label="Back to Info">
                <i class="bi bi-arrow-left-circle"></i>
            </button>
            <h2 class="mb-4">Update Profile</h2>

            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger">
                    <?php foreach ($errors as $error): ?>
                        <div><?= htmlspecialchars($error) ?></div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <?php if ($success): ?>
                <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
            <?php endif; ?>

            <form method="POST" enctype="multipart/form-data" class="w-75 mx-auto">
                <div class="mb-3 text-center">
                    <img src="uploads/<?= htmlspecialchars($user['profile_pic']) ?>" alt="Profile Picture" class="rounded current-profile-pic" width="120">
                </div>

                <div class="mb-3">
                    <label class="form-label">Username</label>
                    <input type="text" name="username" class="form-control" value="<?= htmlspecialchars($user['username']) ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($user['email']) ?>" required>
                </div>

                <div class="mb-3 password-wrapper">
                    <label for="password" class="form-label">New Password <small>(Leave blank to keep current)</small></label>
                    <input type="password" id="register_password" class="form-control" placeholder="••••••••">
                    <i class="bi bi-eye-fill" id="register_togglePassword"></i>
                </div>

                <div class="mb-3 password-wrapper">
                    <label for="confirm_password" class="form-label">Re-enter Password <small>(Leave blank to keep current)</small></label>
                    <input type="password" id="register_confirm_password" class="form-control" placeholder="••••••••">
                    <i class="bi bi-eye-fill" id="register_toggleConfirmPassword"></i>
                </div>

                <div class="mb-3">
                    <label class="form-label">Change Profile Picture <small>(Leave blank to keep current)</small></label>
                    <input type="file" class="form-control" accept="image/*">
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Update Profile</button>
                    <a href="index.php" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </main>
    <?php include ('includes/footer.php'); ?>
    <script src="scripts/script.js"></script>
</body>
</html>
