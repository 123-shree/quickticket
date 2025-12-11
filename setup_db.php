<?php
$servername = "localhost";
$username = "root";
$password = "";

// Create connection
$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create database
$sql = "CREATE DATABASE IF NOT EXISTS quickticket_db";
if ($conn->query($sql) === TRUE) {
    echo "Database created successfully<br>";
} else {
    echo "Error creating database: " . $conn->error . "<br>";
}

// Select database
$conn->select_db("quickticket_db");

// Users Table
$sql = "CREATE TABLE IF NOT EXISTS users (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    email VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('user', 'admin') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if ($conn->query($sql) === TRUE) {
    echo "Table users created successfully<br>";
} else {
    echo "Error creating table users: " . $conn->error . "<br>";
}

// Insert Default Admin
$admin_email = "admin@quickticket.com";
$check_admin = "SELECT * FROM users WHERE email='$admin_email'";
$result = $conn->query($check_admin);

if ($result->num_rows == 0) {
    $admin_pass = password_hash("admin123", PASSWORD_DEFAULT);
    $sql = "INSERT INTO users (name, email, password, role) VALUES ('Admin', '$admin_email', '$admin_pass', 'admin')";
    if ($conn->query($sql) === TRUE) {
        echo "Default admin created (email: admin@quickticket.com, pass: admin123)<br>";
    }
}

// Buses Table
$sql = "CREATE TABLE IF NOT EXISTS buses (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    bus_name VARCHAR(100) NOT NULL,
    bus_number VARCHAR(50) NOT NULL,
    bus_type VARCHAR(50) NOT NULL,
    total_seats INT(3) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if ($conn->query($sql) === TRUE) {
    echo "Table buses created successfully<br>";
} else {
    echo "Error creating table buses: " . $conn->error . "<br>";
}

// Routes Table
$sql = "CREATE TABLE IF NOT EXISTS routes (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    bus_id INT(6) UNSIGNED,
    source VARCHAR(100) NOT NULL,
    destination VARCHAR(100) NOT NULL,
    departure_date DATE NOT NULL,
    departure_time TIME NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (bus_id) REFERENCES buses(id),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if ($conn->query($sql) === TRUE) {
    echo "Table routes created successfully<br>";
} else {
    echo "Error creating table routes: " . $conn->error . "<br>";
}

// Bookings Table
$sql = "CREATE TABLE IF NOT EXISTS bookings (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT(6) UNSIGNED,
    route_id INT(6) UNSIGNED,
    seat_number INT(3) NOT NULL,
    booking_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (route_id) REFERENCES routes(id)
)";

if ($conn->query($sql) === TRUE) {
    echo "Table bookings created successfully<br>";
} else {
    echo "Error creating table bookings: " . $conn->error . "<br>";
}

$conn->close();
?>
