<?php
include '../includes/db.php';

// Add columns to users table
$columns = [
    "ADD COLUMN profile_image VARCHAR(255) DEFAULT NULL",
    "ADD COLUMN citizenship_front VARCHAR(255) DEFAULT NULL",
    "ADD COLUMN citizenship_back VARCHAR(255) DEFAULT NULL",
    "ADD COLUMN blood_group VARCHAR(5) DEFAULT NULL",
    "ADD COLUMN phone_number VARCHAR(20) DEFAULT NULL"
];

foreach ($columns as $col) {
    $sql = "ALTER TABLE users $col";
    if ($conn->query($sql) === TRUE) {
        echo "Column added successfully: $col<br>";
    } else {
        echo "Error adding column ($col): " . $conn->error . "<br>";
    }
}
?>
