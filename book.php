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
$route_query = "SELECT r.*, b.bus_name, b.total_seats, b.bus_number, b.bus_type 
                FROM routes r 
                JOIN buses b ON r.bus_id = b.id 
                WHERE r.id = $route_id";
$route_result = $conn->query($route_query);
$route = $route_result->fetch_assoc();

// Fetch booked seats
$booked_query = "SELECT seat_number FROM bookings WHERE route_id = $route_id AND payment_status != 'cancelled'";
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

    <div class="booking-container">
        <div class="seat-layout">
            
            <div class="bus-front">
                <div class="entry-door">
                    ENTRY
                </div>
                <div class="driver-cabin">
                    <div class="steering-wheel">
                        <div style="position: absolute; top: 50%; left: 0; right: 0; height: 4px; background: #333; transform: translateY(-50%);"></div>
                        <div style="position: absolute; top: 50%; left: 50%; width: 4px; height: 50%; background: #333; transform: translateX(-50%);"></div>
                    </div>
                    <span>Driver</span>
                </div>
            </div>

            <div style="display: flex; flex-direction: column; gap: 10px;">
                <form method="POST" action="payment.php">
                <input type="hidden" name="route_id" value="<?php echo $route_id; ?>">
                
                <?php
                // Determine Layout based on Bus Type
                $bus_type = strtolower($route['bus_type']);
                $layout_class = 'layout-2-2'; // Default
                $seats_per_row = 4;
                
                if (strpos($bus_type, 'vip') !== false || strpos($bus_type, 'sofa') !== false || strpos($bus_type, 'tourist') !== false) {
                    $layout_class = 'layout-2-1';
                    $seats_per_row = 3; 
                }
                ?>

                <div class="seats-grid <?php echo $layout_class; ?>">
                    <?php
                    $total_seats = $route['total_seats'];
                    
                    for ($i = 1; $i <= $total_seats; $i++) {
                        $is_booked = in_array($i, $booked_seats);
                        $disabled = $is_booked ? 'disabled' : '';
                        
                        // Seat Numbering & Labeling Logic could be enhanced here
                        // For now, straight numbering
                        
                        echo "<label class='seat-wrapper'>";
                        echo "<input type='checkbox' name='seats[]' value='$i' $disabled>";
                        echo "<div class='seat-shape " . ($is_booked ? 'booked' : '') . "'>";
                        echo "<span class='seat-number'>$i</span>";
                        // Added realistic styling elements
                        echo "<div class='seat-headrest'></div>";
                        echo "<div class='seat-armrest left'></div>";
                        echo "<div class='seat-armrest right'></div>";
                        echo "</div>";
                        echo "</label>";
                    }
                    ?>
                </div>

                <div class="legend" style="display: flex; gap: 20px; justify-content: center; margin-top: 30px; font-size: 0.9rem;">
                    <div style="display: flex; align-items: center; gap: 8px;"><div style="width: 20px; height: 20px; background: #e0e0e0; border: 1px solid #ccc; border-radius: 4px;"></div> Available</div>
                    <div style="display: flex; align-items: center; gap: 8px;"><div style="width: 20px; height: 20px; background: #ff5252; border: 1px solid #d32f2f; border-radius: 4px;"></div> Booked</div>
                    <div style="display: flex; align-items: center; gap: 8px;"><div style="width: 20px; height: 20px; background: #4caf50; border: 1px solid #388e3c; border-radius: 4px;"></div> Selected</div>
                </div>

                <div class="booking-summary" style="margin-top: 30px; border-top: 1px solid #eee; padding-top: 20px;">
                    <h4 style="margin-bottom: 15px;">Passenger Details</h4>
                    
                    <div class="form-group" style="margin-bottom: 15px;">
                        <input type="text" name="passenger_name" class="form-control" placeholder="Passenger Name" required style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 6px;">
                    </div>
                    
                    <div class="form-group" style="margin-bottom: 15px;">
                        <input type="tel" name="contact_number" class="form-control" placeholder="Contact Number" required style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 6px;">
                    </div>
                    
                    <div class="form-group" style="margin-bottom: 15px;">
                        <input type="text" name="pickup_location" class="form-control" placeholder="Pickup Location (e.g. Kalanki)" required style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 6px;">
                    </div>
                    
                    <button type="submit" class="btn btn-primary" style="width: 100%; padding: 12px; font-weight: bold; background: var(--primary-color); border: none; border-radius: 6px; cursor: pointer;">Proceed to Payment</button>
                </div>
                </form>
            </div>
        </div>

        <div class="booking-details">
            <h3 style="border-bottom: 2px solid #f0f0f0; padding-bottom: 15px; margin-bottom: 20px;">Trip Details</h3>
            <ul style="margin-top: 20px; list-style: none;">
                <li style="margin-bottom: 15px; display: flex; align-items: center;"><i class="fas fa-map-marker-alt" style="width: 30px; color: #666;"></i> <div><small style="color: #888">From</small><br><strong><?php echo $route['source']; ?></strong></div></li>
                <li style="margin-bottom: 15px; display: flex; align-items: center;"><i class="fas fa-location-arrow" style="width: 30px; color: #666;"></i> <div><small style="color: #888">To</small><br><strong><?php echo $route['destination']; ?></strong></div></li>
                <li style="margin-bottom: 15px; display: flex; align-items: center;"><i class="fas fa-calendar" style="width: 30px; color: #666;"></i> <div><small style="color: #888">Date</small><br><strong><?php echo $route['departure_date']; ?></strong></div></li>
                <li style="margin-bottom: 15px; display: flex; align-items: center;"><i class="fas fa-clock" style="width: 30px; color: #666;"></i> <div><small style="color: #888">Time</small><br><strong><?php echo date('h:i A', strtotime($route['departure_time'])); ?></strong></div></li>
                <li style="margin-top: 25px; padding-top: 15px; border-top: 1px dashed #ddd; font-size: 1.3rem; color: var(--primary-color);"><strong>Rs. <?php echo $route['price']; ?></strong> <small style="font-size: 0.9rem; color: #666;">/ seat</small></li>
            </ul>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
