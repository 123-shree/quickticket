<?php
// Default to local configuration
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "quickticket_db";
// Uncomment these and fill with YOUR details
$servername = "sql200.infinityfree.com";  // Your Host Name
$username = "if0_1234567";                // Your User Name
$password = "your_panel_password";        // Your Password
$dbname = "if0_1234567_busticket";        // Your Database Name
// Use environment variables if available (common in production/containers)
if (getenv('DB_HOST')) $servername = getenv('DB_HOST');
if (getenv('DB_USER')) $username = getenv('DB_USER');
if (getenv('DB_PASS')) $password = getenv('DB_PASS');
if (getenv('DB_NAME')) $dbname = getenv('DB_NAME');

// Override for manual production config (Uncomment and fill these when deploying if env vars aren't used)
// Override for manual production config (Uncomment and fill these when deploying if env vars aren't used)
$servername = "sql313.infinityfree.com"; // CHECK YOUR PANEL: It might be sql100, sql200, sql301 etc.
$username = "if0_40804793";
$password = "Shriyog123";
$dbname = "if0_40804793_quickticket"; // Assumes you named the DB 'quickticket'

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
