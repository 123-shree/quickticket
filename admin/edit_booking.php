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
                <label for="route_id">Select New Route:</label>
                <select name="route_id" id="route_id" required style="width: 100%; padding: 10px; border-radius: 5px; border: 1px solid #ddd;">
                    <?php
                    if ($routes_result->num_rows > 0) {
                        while($route = $routes_result->fetch_assoc()) {
                            $selected = ($route['id'] == $booking['route_id']) ? 'selected' : '';
                            echo "<option value='{$route['id']}' $selected>
                                    {$route['source']} - {$route['destination']} 
                                    ({$route['departure_date']} {$route['departure_time']}) - {$route['bus_name']}
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
