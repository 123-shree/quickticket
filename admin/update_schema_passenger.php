<?php
include '../includes/db.php';

// Add passenger info columns to bookings table
$queries = [
    "ALTER TABLE bookings ADD COLUMN passenger_name VARCHAR(100) NOT NULL DEFAULT ''",
    "ALTER TABLE bookings ADD COLUMN contact_number VARCHAR(20) NOT NULL DEFAULT ''",
    "ALTER TABLE bookings ADD COLUMN pickup_location VARCHAR(100) NOT NULL DEFAULT ''"
];

foreach ($queries as $sql) {
    if ($conn->query($sql) === TRUE) {
        echo "Query executed successfully: $sql<br>";
    } else {
        echo "Error executing query: " . $conn->error . "<br>";
    }
}

echo "Database schema updated for passenger details.";
?>
