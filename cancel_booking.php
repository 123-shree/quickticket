<?php
include 'includes/db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: my_tickets.php");
    exit();
}

$booking_id = $_GET['id'];
$user_id = $_SESSION['user_id'];

// Verify ownership
$sql = "SELECT * FROM bookings WHERE id = '$booking_id' AND user_id = '$user_id'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Check if already cancelled
    $row = $result->fetch_assoc();
    if ($row['payment_status'] == 'cancelled') {
        header("Location: my_tickets.php?msg=Ticket already cancelled.");
        exit();
    }

    // Process cancellation
    $update = "UPDATE bookings SET payment_status = 'cancelled' WHERE id = '$booking_id'";
    if ($conn->query($update) === TRUE) {
        
        // --- Notification Logic ---
        $msg_user = "Ticket #$booking_id Cancelled Successfully.";
        $conn->query("INSERT INTO notifications (user_id, type, message) VALUES ('$user_id', 'warning', '$msg_user')");
        
        $msg_admin = "Cancellation Alert! User ID: $user_id cancelled Ticket #$booking_id.";
        $conn->query("INSERT INTO notifications (user_id, type, message) VALUES (NULL, 'danger', '$msg_admin')");
        // --------------------------

        header("Location: my_tickets.php?msg=" . urlencode("Ticket cancelled successfully. Notification sent."));
    } else {
        header("Location: my_tickets.php?error=Error cancelling ticket.");
    }
} else {
    header("Location: my_tickets.php?error=Invalid booking.");
}
?>
