<?php
include 'includes/db.php';
include 'includes/header.php';

if (!isset($_SESSION['user_id'])) {
    $current_url = urlencode("book.php?" . $_SERVER['QUERY_STRING']);
    header("Location: login.php?redirect=$current_url");
    exit();
}

if (!isset($_GET['route_id'])) {
    header("Location: index.php");
    exit();
}

$route_id = $_GET['route_id'];
$route_query = "SELECT r.*, b.bus_name, b.total_seats, b.bus_number 
                FROM routes r 
                JOIN buses b ON r.bus_id = b.id 
                WHERE r.id = $route_id";
$route_result = $conn->query($route_query);
$route = $route_result->fetch_assoc();

// Fetch booked seats
$booked_query = "SELECT seat_number FROM bookings WHERE route_id = $route_id";
$booked_result = $conn->query($booked_query);
$booked_seats = [];
while($row = $booked_result->fetch_assoc()) {
    $booked_seats[] = $row['seat_number'];
}

$success = "";
$error = "";

// Booking logic moved to payment.php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // This block should ideally not be reached if form action is changed, 
    // but just in case, we redirect.
    if (isset($_POST['seats']) && !empty($_POST['seats'])) {
        // Just redirect to payment if logic falls through here (shouldn't happen with action change)
    }
}
?>

<div class="page-header">
    <div class="container">
        <h1>Select Seats</h1>
        <p><?php echo $route['bus_name']; ?> (<?php echo $route['bus_number']; ?>)</p>
    </div>
</div>

<div class="container section-padding">
    <?php if($success): ?>
        <div class="alert alert-success" style="background: #d4edda; color: #155724; padding: 15px; margin-bottom: 20px; border-radius: 4px;">
            <?php echo $success; ?>
            <br><a href="index.php" style="font-weight: bold;">Book Another</a>
        </div>
    <?php elseif($error): ?>
        <div class="alert alert-danger" style="background: #f8d7da; color: #721c24; padding: 15px; margin-bottom: 20px; border-radius: 4px;">
            <?php echo $error; ?>
        </div>
    <?php endif; ?>

    <div class="booking-container" style="display: flex; gap: 40px; flex-wrap: wrap;">
        <div class="seat-layout" style="flex: 1; min-width: 300px; background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
            <h3 style="margin-bottom: 20px; text-align: center;">Driver</h3>
            <div style="display: flex; flex-direction: column; gap: 10px;">
                <form method="POST" action="payment.php">
                <input type="hidden" name="route_id" value="<?php echo $route_id; ?>">
                
                <div class="seats-grid" style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 10px;">
                    <?php
                    // Simplified layout: 2 cols left, aisle, 2 cols right
                    // Total seats loop
                    for ($i = 1; $i <= $route['total_seats']; $i++) {
                        $is_booked = in_array($i, $booked_seats);
                        $seat_class = $is_booked ? 'booked' : 'available';
                        $disabled = $is_booked ? 'disabled' : '';
                        
                        echo "<label class='seat-item $seat_class' style='
                            display: block; 
                            padding: 10px; 
                            border: 1px solid #ddd; 
                            text-align: center; 
                            border-radius: 4px; 
                            cursor: pointer;
                            background: " . ($is_booked ? '#ffcdd2' : '#e8f5e9') . ";
                            " . ($i % 4 == 2 ? 'margin-right: 20px;' : '') . "
                        '>";
                        echo "<input type='checkbox' name='seats[]' value='$i' $disabled style='display: none;'>";
                        echo "<i class='fas fa-chair' style='font-size: 1.2rem; display: block; margin-bottom: 5px; color: " . ($is_booked ? '#c62828' : '#2e7d32') . ";'></i>";
                        echo "$i";
                        echo "</label>";
                    }
                    ?>
                </div>

                <div class="booking-summary" style="margin-top: 30px; border-top: 1px solid #eee; padding-top: 20px;">
                    <h4 style="margin-bottom: 15px;">Passenger Details</h4>
                    
                    <div class="form-group" style="margin-bottom: 15px;">
                        <input type="text" name="passenger_name" class="form-control" placeholder="Passenger Name" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
                    </div>
                    
                    <div class="form-group" style="margin-bottom: 15px;">
                        <input type="tel" name="contact_number" class="form-control" placeholder="Contact Number" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
                    </div>
                    
                    <div class="form-group" style="margin-bottom: 15px;">
                        <input type="text" name="pickup_location" class="form-control" placeholder="Pickup Location (e.g. Kalanki)" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
                    </div>
                    
                    <button type="submit" class="btn btn-primary" style="width: 100%;">Proceed to Payment</button>
                </div>
                </form>
            </div>
        </div>

        <div class="booking-details" style="width: 300px; background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); align-self: flex-start;">
            <h3>Trip Details</h3>
            <ul style="margin-top: 20px; list-style: none;">
                <li style="margin-bottom: 10px;"><i class="fas fa-map-marker-alt" style="width: 20px;"></i> <strong>From:</strong> <?php echo $route['source']; ?></li>
                <li style="margin-bottom: 10px;"><i class="fas fa-location-arrow" style="width: 20px;"></i> <strong>To:</strong> <?php echo $route['destination']; ?></li>
                <li style="margin-bottom: 10px;"><i class="fas fa-calendar" style="width: 20px;"></i> <strong>Date:</strong> <?php echo $route['departure_date']; ?></li>
                <li style="margin-bottom: 10px;"><i class="fas fa-clock" style="width: 20px;"></i> <strong>Time:</strong> <?php echo $route['departure_time']; ?></li>
                <li style="margin-bottom: 10px; font-size: 1.2rem; margin-top: 20px; color: var(--primary-color);"><strong>Price:</strong> Rs. <?php echo $route['price']; ?> / seat</li>
            </ul>
        </div>
    </div>
</div>

<style>
    .seat-item.booked { cursor: not-allowed; opacity: 0.7; }
    .seat-item input:checked + i { color: var(--primary-color); transform: scale(1.2); }
    .seat-item:has(input:checked) { border: 2px solid var(--primary-color); background: #e0f7fa !important; }
</style>

<?php include 'includes/footer.php'; ?>
