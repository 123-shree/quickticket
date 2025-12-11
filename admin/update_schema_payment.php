<?php
include '../includes/db.php';

// Add columns to bookings table
$queries = [
    "ALTER TABLE bookings ADD COLUMN payment_status ENUM('pending', 'partial', 'paid') DEFAULT 'pending'",
    "ALTER TABLE bookings ADD COLUMN payment_method VARCHAR(50) DEFAULT 'cash'",
    "ALTER TABLE bookings ADD COLUMN transaction_id VARCHAR(100) DEFAULT NULL",
    "ALTER TABLE bookings ADD COLUMN paid_amount DECIMAL(10, 2) DEFAULT 0.00"
];

foreach ($queries as $sql) {
    if ($conn->query($sql) === TRUE) {
        echo "Query executed successfully: $sql<br>";
    } else {
        echo "Error executing query: " . $conn->error . "<br>";
    }
}

echo "Database schema updated successfully.";
?>
