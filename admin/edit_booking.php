<?php
include '../includes/db.php';
include 'includes/header.php';

if (!isset($_GET['id'])) {
    header("Location: bookings.php");
    exit();
}

$booking_id = $_GET['id'];

// Fetch Booking Details
$sql = "SELECT * FROM bookings WHERE id = $booking_id";
$result = $conn->query($sql);
$booking = $result->fetch_assoc();

// Fetch All Routes for Dropdown
$routes_sql = "SELECT r.id, r.source, r.destination, r.departure_date, r.departure_time, b.bus_name 
               FROM routes r 
               JOIN buses b ON r.bus_id = b.id";
$routes_result = $conn->query($routes_sql);
?>

<div class="main-content">
    <h2>Edit Booking Route</h2>
    
    <div class="card" style="max-width: 600px; margin-top: 20px;">
        <form action="process_booking.php" method="POST">
            <input type="hidden" name="booking_id" value="<?php echo $booking_id; ?>">
            <input type="hidden" name="update_route" value="1">
            
            <div class="form-group">
                <label>Current Booking ID: #<?php echo $booking_id; ?></label>
            </div>

            <div class="form-group">
                <label for="passenger_name">Passenger Name:</label>
                <input type="text" name="passenger_name" id="passenger_name" class="form-control" value="<?php echo $booking['passenger_name']; ?>" required style="width: 100%; padding: 10px; margin-bottom: 15px; border-radius: 5px; border: 1px solid #ddd;">
            </div>

            <div class="form-group">
                <label for="contact_number">Contact Number:</label>
                <input type="text" name="contact_number" id="contact_number" class="form-control" value="<?php echo $booking['contact_number']; ?>" required style="width: 100%; padding: 10px; margin-bottom: 15px; border-radius: 5px; border: 1px solid #ddd;">
            </div>

            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" name="email" id="email" class="form-control" value="<?php echo isset($booking['email']) ? $booking['email'] : ''; ?>" style="width: 100%; padding: 10px; margin-bottom: 15px; border-radius: 5px; border: 1px solid #ddd;">
            </div>

            <div class="form-group">
                <label for="status">Booking Status:</label>
                <select name="status" id="status" class="form-control" style="width: 100%; padding: 10px; margin-bottom: 15px; border-radius: 5px; border: 1px solid #ddd;">
                    <option value="pending" <?php if($booking['status'] == 'pending') echo 'selected'; ?>>Pending</option>
                    <option value="confirmed" <?php if($booking['status'] == 'confirmed') echo 'selected'; ?>>Confirmed</option>
                    <option value="cancelled" <?php if($booking['status'] == 'cancelled') echo 'selected'; ?>>Cancelled</option>
                </select>
            </div>

            <div class="form-group">
                <label for="payment_status">Payment Status:</label>
                <select name="payment_status" id="payment_status" class="form-control" style="width: 100%; padding: 10px; margin-bottom: 15px; border-radius: 5px; border: 1px solid #ddd;">
                    <option value="pending" <?php if($booking['payment_status'] == 'pending') echo 'selected'; ?>>Pending</option>
                    <option value="partial" <?php if($booking['payment_status'] == 'partial') echo 'selected'; ?>>Partial (Advance)</option>
                    <option value="paid" <?php if($booking['payment_status'] == 'paid') echo 'selected'; ?>>Paid (Full)</option>
                </select>
            </div>

            <div class="form-group">
                <label for="pickup_location">Pickup Location:</label>
                <input type="text" name="pickup_location" id="pickup_location" class="form-control" value="<?php echo isset($booking['pickup_location']) ? $booking['pickup_location'] : ''; ?>" style="width: 100%; padding: 10px; margin-bottom: 15px; border-radius: 5px; border: 1px solid #ddd;">
            </div>

            <div class="form-group">
                <label for="drop_location">Drop Location:</label>
                <input type="text" name="drop_location" id="drop_location" class="form-control" value="<?php echo isset($booking['drop_location']) ? $booking['drop_location'] : ''; ?>" style="width: 100%; padding: 10px; margin-bottom: 15px; border-radius: 5px; border: 1px solid #ddd;">
            </div>

            <div class="form-group">
                <label for="seat_number">Seat Number:</label>
                <input type="text" name="seat_number" id="seat_number" class="form-control" value="<?php echo $booking['seat_number']; ?>" required style="width: 100%; padding: 10px; margin-bottom: 15px; border-radius: 5px; border: 1px solid #ddd;">
            </div>

            <div class="form-group">
                <label for="route_id">Select New Route:</label>
                <select name="route_id" id="route_id" required style="width: 100%; padding: 10px; border-radius: 5px; border: 1px solid #ddd;">
                    <?php
                    if ($routes_result->num_rows > 0) {
                        while($route = $routes_result->fetch_assoc()) {
                            $selected = ($route['id'] == $booking['route_id']) ? 'selected' : '';
                            echo "<option value='{$route['id']}' $selected>
                                    {$route['source']} - {$route['destination']} 
                                    ({$route['departure_date']} " . date('h:i A', strtotime($route['departure_time'])) . ") - {$route['bus_name']}
                                  </option>";
                        }
                    }
                    ?>
                </select>
            </div>

            <button type="submit" class="btn btn-primary" style="margin-top: 15px;">Update Route</button>
            <a href="bookings.php" class="btn btn-outline" style="margin-left: 10px;">Cancel</a>
        </form>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
