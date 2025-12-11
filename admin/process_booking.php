<?php
include '../includes/db.php';
session_start();

// Check if admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

if (isset($_GET['action']) && isset($_GET['id'])) {
    $id = $_GET['id'];
    $action = $_GET['action'];

    if ($action == 'confirm') {
        $sql = "UPDATE bookings SET payment_status='paid' WHERE id=$id";
        if ($conn->query($sql) === TRUE) {
            header("Location: bookings.php?msg=Booking confirmed");
        } else {
            echo "Error updating record: " . $conn->error;
        }
    } elseif ($action == 'delete') {
        $sql = "DELETE FROM bookings WHERE id=$id";
        if ($conn->query($sql) === TRUE) {
            header("Location: bookings.php?msg=Booking deleted");
        } else {
            echo "Error deleting record: " . $conn->error;
        }
    }
}

if (isset($_POST['update_route'])) {
    $booking_id = $_POST['booking_id'];
    $new_route_id = $_POST['route_id'];

    $sql = "UPDATE bookings SET route_id=$new_route_id WHERE id=$booking_id";
    if ($conn->query($sql) === TRUE) {
        header("Location: bookings.php?msg=Route updated successfully");
    } else {
        echo "Error updating route: " . $conn->error;
    }
}

$conn->close();
?>
