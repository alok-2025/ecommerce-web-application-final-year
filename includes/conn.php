<?php
// Database connection details
$host = 'localhost';
$username = 'root';       
$password = '';
$database = 'lokimart_db';

// Create a connection
$conn = new mysqli($host, $username, $password, $database);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

<!-- Include a favicon -->
<link rel="icon" type="image/x-icon" href="images/cart1.png">
