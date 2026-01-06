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
        <p><?php echo $route['bus_name']; ?> - <?php echo $route['bus_type']; ?> (<?php echo $route['bus_number']; ?>)</p>
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

                <div class="seats-bus-container">
                    <?php
                    // --- NEPAL / INDIA REGIONAL SEAT LAYOUT ---
                    // Layout: 
                    // Side A (Right/Driver Side) - Usually 2 seats [A(Row)1, A(Row)2] or just A1, A2, A3... logic
                    // Side B (Left/Door Side) - Usually 2 seats [B(Row)1, B(Row)2]
                    
                    // We will use "A" for Right Side (Driver) and "B" for Left Side (Door Side)
                    // Rows 1, 2, 3...
                    // Seat Names: A1, A2 (Row 1 Side A) | B1, B2 (Row 1 Side B)
                    // Note: Most local buses use A side as Driver Side.
                    
                    $total_seats = $route['total_seats'];
                    $bus_type = strtolower($route['bus_type']);
                    
                    // Configuration
                    $columns_side_A = 2; // Right Side (Driver)
                    $columns_side_B = 2; // Left Side
                    $seat_type_class = 'type-standard';

                    if (strpos($bus_type, 'vip') !== false || strpos($bus_type, 'sofa') !== false) {
                        $columns_side_A = 2; // Right Side (Double)
                        $columns_side_B = 1; // Left Side (Single)
                        $seat_type_class = 'type-sofa';
                    } elseif (strpos($bus_type, 'deluxe') !== false) {
                        $seat_type_class = 'type-deluxe';
                    }
                    
                    $seats_per_row = $columns_side_A + $columns_side_B;
                    $total_rows = ceil($total_seats / $seats_per_row);
                    
                    // Generate Rows
                    for($row = 1; $row <= $total_rows; $row++) {
                        echo "<div class='bus-row'>";
                        
                        // --- SIDE B (Left / Door Side) --- 
                        echo "<div class='seat-group side-b'>";
                        for($col = 1; $col <= $columns_side_B; $col++) {
                            $seat_num_B = ($row - 1) * $columns_side_B + $col;
                            $seat_label = "B" . $seat_num_B;
                             
                            $is_booked = in_array($seat_label, $booked_seats);
                            $disabled = $is_booked ? 'disabled' : '';
                            
                            echo "<label class='seat-wrapper " . ($seat_type_class == 'type-sofa' ? 'is-sofa' : '') . "'>";
                            echo "<input type='checkbox' name='seats[]' value='$seat_label' $disabled>";
                            echo "<div class='seat-shape $seat_type_class " . ($is_booked ? 'booked' : '') . "'>";
                            echo "<span class='seat-number'>$seat_label</span>";
                            if($seat_type_class == 'type-sofa') echo "<div class='seat-inner-cushion'></div>";
                            echo "</div>";
                            echo "</label>";
                        }
                        echo "</div>";
                        
                        // --- AISLE ---
                        echo "<div class='aisle-gap'></div>";
                        
                        // --- SIDE A (Right / Driver Side) ---
                        echo "<div class='seat-group side-a'>";
                        for($col = 1; $col <= $columns_side_A; $col++) {
                            $seat_num_A = ($row - 1) * $columns_side_A + $col;
                            $seat_label = "A" . $seat_num_A;
                            
                            $is_booked = in_array($seat_label, $booked_seats);
                            $disabled = $is_booked ? 'disabled' : '';
                            
                            echo "<label class='seat-wrapper " . ($seat_type_class == 'type-sofa' ? 'is-sofa' : '') . "'>";
                            echo "<input type='checkbox' name='seats[]' value='$seat_label' $disabled>";
                            echo "<div class='seat-shape $seat_type_class " . ($is_booked ? 'booked' : '') . "'>";
                            echo "<span class='seat-number'>$seat_label</span>";
                            if($seat_type_class == 'type-sofa') echo "<div class='seat-inner-cushion'></div>";
                            echo "</div>";
                            echo "</label>";
                        }
                        echo "</div>";
                        
                        echo "</div>"; // End Bus Row
                    }
                    ?>
                </div>

                <style>
                    /* Custom Bus Layout CSS */
                    .seats-bus-container {
                        display: flex;
                        flex-direction: column;
                        gap: 25px;
                        padding: 40px;
                        background: #fff;
                        border: 2px solid #e0e0e0;
                        border-radius: 20px;
                        width: fit-content;
                        margin: 0 auto;
                        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
                        position: relative;
                    }
                    /* Driver Cabin Indicator */
                    .seats-bus-container::before {
                        content: '';
                        position: absolute;
                        top: -15px;
                        right: 40px;
                        width: 60px;
                        height: 60px;
                        background: url('assets/images/steering-wheel.png') no-repeat center/contain; 
                        /* Fallback if image missing: border radius circle */
                        border: 4px solid #333;
                        border-radius: 50%;
                        opacity: 0.8;
                    }
                    
                    .bus-row {
                        display: flex;
                        justify-content: space-between;
                        gap: 50px; /* Wide Aisle */
                        align-items: center;
                    }
                    .seat-group {
                        display: flex;
                        gap: 15px; 
                    }
                    
                    .seat-wrapper {
                        display: block;
                        position: relative;
                        width: 55px; /* Base Size */
                        height: 55px;
                    }
                    .seat-wrapper input { display: none; }
                    
                    /* --- BASE SEAT SHAPE --- */
                    .seat-shape {
                        width: 100%;
                        height: 100%;
                        background: #f5f5f5;
                        border: 1px solid #ccc;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        cursor: pointer;
                        font-weight: bold;
                        color: #666;
                        transition: all 0.3s ease;
                        position: relative;
                        z-index: 1;
                        font-size: 0.85rem;
                        box-shadow: 0 2px 5px rgba(0,0,0,0.05);
                    }
                    
                    /* --- TYPE: STANDARD (Basic Boxy) --- */
                    .seat-shape.type-standard {
                        border-radius: 6px;
                    }
                    .seat-shape.type-standard::before {
                        content: ''; /* Minimal detail */
                        position: absolute;
                        bottom: 0;
                        width: 100%;
                        height: 5px;
                        background: #e0e0e0;
                    }

                    /* --- TYPE: DELUXE (Rounded, Headrest) --- */
                    .seat-shape.type-deluxe {
                        border-radius: 12px 12px 8px 8px; /* Rounded top */
                        background: #fff;
                        border-color: #bbb;
                    }
                    .seat-shape.type-deluxe::after {
                         /* Headrest */
                         content: '';
                         position: absolute;
                         top: -6px;
                         left: 10%;
                         width: 80%;
                         height: 10px;
                         background: #ddd;
                         border-radius: 4px;
                         border: 1px solid #ccc;
                    }
                    
                    /* --- TYPE: SOFA (Plush, Wide, Armrests) --- */
                    .seat-shape.type-sofa {
                        border-radius: 15px 15px 10px 10px;
                        background: linear-gradient(to bottom, #fff, #f9f9f9);
                        border: 1px solid #aaa;
                        box-shadow: 0 4px 8px rgba(0,0,0,0.08);
                    }
                    /* Sofa Armrests */
                    .seat-shape.type-sofa::before {
                        content: '';
                        position: absolute;
                        left: -4px;
                        bottom: 10%;
                        width: 4px;
                        height: 60%;
                        background: #bbb;
                        border-radius: 4px;
                    }
                    .seat-shape.type-sofa::after {
                        content: '';
                        position: absolute;
                        right: -4px;
                        bottom: 10%;
                        width: 4px;
                        height: 60%;
                        background: #bbb;
                        border-radius: 4px;
                    }
                    /* Sofa Head cushion effect */
                    .seat-wrapper.is-sofa .seat-inner-cushion {
                        position: absolute;
                        top: 5px;
                        left: 50%;
                        transform: translateX(-50%);
                        width: 70%;
                        height: 30%;
                        background: rgba(0,0,0,0.05);
                        border-radius: 4px;
                    }

                    /* STATES */
                    .seat-wrapper input:checked + .seat-shape {
                        background: var(--primary-color);
                        color: white !important;
                        border-color: var(--primary-dark);
                        box-shadow: 0 5px 15px rgba(106, 236, 225, 0.4);
                        transform: translateY(-2px);
                    }
                    .seat-wrapper input:checked + .seat-shape::after,
                    .seat-wrapper input:checked + .seat-shape::before {
                        background-color: rgba(255,255,255,0.3); /* Tint accents */
                        border-color: transparent;
                    }
                    
                    .seat-shape.booked {
                        background: #ffebee;
                        color: #c62828;
                        border-color: #ffcdd2;
                        cursor: not-allowed;
                        opacity: 0.8;
                    }
                    .seat-shape.booked::after { background-color: #ffcdd2; }
                    
                </style>

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

                    <div class="form-group" style="margin-bottom: 15px;">
                        <input type="text" name="drop_location" class="form-control" placeholder="Drop Location (e.g. Bus Park)" required style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 6px;">
                    </div>

                    <div class="form-group" style="margin-bottom: 15px;">
                        <input type="email" name="email" class="form-control" placeholder="Email Address (Optional)" style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 6px;">
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
                <li style="margin-bottom: 15px; display: flex; align-items: center;"><i class="fas fa-bus" style="width: 30px; color: #666;"></i> <div><small style="color: #888">Bus Type</small><br><strong style="color: var(--primary-color); text-transform: uppercase;"><?php echo $route['bus_type']; ?></strong></div></li>
                <li style="margin-bottom: 15px; display: flex; align-items: center;"><i class="fas fa-calendar" style="width: 30px; color: #666;"></i> <div><small style="color: #888">Date</small><br><strong><?php echo $route['departure_date']; ?></strong></div></li>
                <li style="margin-bottom: 15px; display: flex; align-items: center;"><i class="fas fa-clock" style="width: 30px; color: #666;"></i> <div><small style="color: #888">Time</small><br><strong><?php echo date('h:i A', strtotime($route['departure_time'])); ?></strong></div></li>
                <li style="margin-top: 25px; padding-top: 15px; border-top: 1px dashed #ddd; font-size: 1.3rem; color: var(--primary-color);"><strong>Rs. <?php echo $route['price']; ?></strong> <small style="font-size: 0.9rem; color: #666;">/ seat</small></li>
            </ul>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
