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
        // Optionally: Logic to release seat in routes (if seat availability is tracked separately by count, but here it's checked by booking table)
        // Since we check `bookings` table to see if seat is taken, changing status to 'cancelled' 
        // implies we should also check status when booking. 
        // IMPORTANT: The `book.php` logic (step 49) checks:
        // $booked_query = "SELECT seat_number FROM bookings WHERE route_id = $route_id";
        // It DOES NOT exclude cancelled bookings.
        // So I must Delete the row OR update the query in book.php.
        // Updating status to 'cancelled' is better for records.
        // I will need to update book.php too to filter out cancelled bookings.
        
        header("Location: my_tickets.php?msg=Ticket cancelled successfully.");
    } else {
        header("Location: my_tickets.php?error=Error cancelling ticket.");
    }
} else {
    header("Location: my_tickets.php?error=Invalid booking.");
}
?>
