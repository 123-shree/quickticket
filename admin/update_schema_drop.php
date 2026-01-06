<?php
include '../includes/db.php';

// Add drop_location column to bookings table
$sql = "ALTER TABLE bookings ADD COLUMN drop_location VARCHAR(100) NOT NULL DEFAULT '' AFTER pickup_location";

if ($conn->query($sql) === TRUE) {
    echo "Column 'drop_location' added successfully to 'bookings' table.";
} else {
    echo "Error updating table: " . $conn->error;
}
?>
