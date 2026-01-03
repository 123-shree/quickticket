<?php
$host = '127.0.0.1';
$user = 'root';
$pass = '';
$port = 3307;

$conn = new mysqli($host, $user, $pass, "", $port);
if ($conn->connect_error) {
    echo "Failed 127.0.0.1: " . $conn->connect_error . "\n";
} else {
    echo "Success 127.0.0.1\n";
    $conn->close();
}

$host = 'localhost';
$conn = new mysqli($host, $user, $pass, "", $port);
if ($conn->connect_error) {
    echo "Failed localhost: " . $conn->connect_error . "\n";
} else {
    echo "Success localhost\n";
    $conn->close();
}
?>
