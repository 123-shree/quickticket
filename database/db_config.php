<?php
// Default to local configuration
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "quickticket_db";
$port = 3307;

// Uncomment these and fill with YOUR details for production
// $servername = "sql200.infinityfree.com";  // Your Host Name
// $username = "if0_1234567";                // Your User Name
// $password = "your_panel_password";        // Your Password
// $dbname = "if0_1234567_busticket";        // Your Database Name

// Use environment variables if available (common in production/containers)
if (getenv('DB_HOST')) $servername = getenv('DB_HOST');
if (getenv('DB_USER')) $username = getenv('DB_USER');
if (getenv('DB_PASS')) $password = getenv('DB_PASS');
if (getenv('DB_NAME')) $dbname = getenv('DB_NAME');
if (getenv('DB_PORT')) $port = getenv('DB_PORT');

// Override for manual production config (Uncomment and fill these when deploying if env vars aren't used)
// $servername = "sql313.infinityfree.com"; // CHECK YOUR PANEL
// $username = "if0_40804793";
// $password = "Shriyog123";
// $dbname = "if0_40804793_quickticket"; 
// $port = 3306; // Standard port for production

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname, $port);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
