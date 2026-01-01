<?php
// Default to local configuration
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "quickticket_db";

// Use environment variables if available (common in production/containers)
if (getenv('DB_HOST')) $servername = getenv('DB_HOST');
if (getenv('DB_USER')) $username = getenv('DB_USER');
if (getenv('DB_PASS')) $password = getenv('DB_PASS');
if (getenv('DB_NAME')) $dbname = getenv('DB_NAME');

// Override for manual production config (Uncomment and fill these when deploying if env vars aren't used)
// $servername = "sql123.infinityfree.com";
// $username = "if0_12345678";
// $password = "your_password";
// $dbname = "if0_12345678_quickticket";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
