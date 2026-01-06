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
        $sql = "UPDATE bookings SET payment_status='paid', status='confirmed' WHERE id=$id";
        if ($conn->query($sql) === TRUE) {
            header("Location: bookings.php?msg=Booking payment done and confirmed");
        } else {
            echo "Error updating record: " . $conn->error;
        }
    } elseif ($action == 'confirm_booking') {
        // Manually confirm booking without payment change
        $sql = "UPDATE bookings SET status='confirmed' WHERE id=$id";
        if ($conn->query($sql) === TRUE) {
            header("Location: bookings.php?msg=Booking status set to Confirmed");
        } else {
            echo "Error updating record: " . $conn->error;
        }
    } elseif ($action == 'pending_booking') {
        // Manually set booking to pending
        $sql = "UPDATE bookings SET status='pending' WHERE id=$id";
        if ($conn->query($sql) === TRUE) {
            header("Location: bookings.php?msg=Booking status set to Pending");
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
    $passenger_name = $_POST['passenger_name'];
    $contact_number = $_POST['contact_number'];
    $email = $_POST['email'];
    $seat_number = $_POST['seat_number'];
    $status = $_POST['status'];
    $payment_status = $_POST['payment_status'];
    $pickup_location = $_POST['pickup_location'];
    $drop_location = $_POST['drop_location'];

    // Auto-confirm if payment is paid or partial
    if (($payment_status == 'paid' || $payment_status == 'partial') && $status == 'pending') {
        $status = 'confirmed';
    }

    $sql = "UPDATE bookings SET 
            route_id='$new_route_id', 
            passenger_name='$passenger_name', 
            contact_number='$contact_number', 
            email='$email',
            seat_number='$seat_number',
            status='$status',
            payment_status='$payment_status',
            pickup_location='$pickup_location',
            drop_location='$drop_location' 
            WHERE id=$booking_id";

    if ($conn->query($sql) === TRUE) {
        header("Location: bookings.php?msg=Booking updated successfully");
    } else {
        echo "Error updating booking: " . $conn->error;
    }
}

$conn->close();
?>
