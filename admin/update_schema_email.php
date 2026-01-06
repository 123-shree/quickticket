<?php
include '../includes/db.php';

// Add email column to bookings table
$sql = "ALTER TABLE bookings ADD COLUMN email VARCHAR(100) NOT NULL DEFAULT '' AFTER contact_number";

if ($conn->query($sql) === TRUE) {
    echo "Column 'email' added successfully to 'bookings' table.";
} else {
    echo "Error updating table: " . $conn->error;
}
?>
