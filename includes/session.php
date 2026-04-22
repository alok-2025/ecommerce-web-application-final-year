<?php // Always start the session at the top of the page
session_start();
// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    $_SESSION['msg'] = "You must log in first";
    // Redirect to login page
    header('Location: login.php');
    exit();
}
if (isset($_GET['logout'])) {
    session_destroy();
    unset($_SESSION['user_id']);
    header("location: login.php");
}
?>
