<?php
include '../includes/db.php';

// Add status column to bookings table
$sql = "ALTER TABLE bookings ADD COLUMN status ENUM('pending', 'confirmed', 'cancelled') DEFAULT 'pending'";

if ($conn->query($sql) === TRUE) {
    echo "Column 'status' added successfully";
} else {
    echo "Error adding column: " . $conn->error;
}

$conn->close();
?>
