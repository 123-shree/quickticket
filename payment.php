<?php
include 'includes/db.php';
include 'includes/header.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Redirect if accessed directly without POST data
if ($_SERVER["REQUEST_METHOD"] != "POST" || !isset($_POST['seats']) || empty($_POST['seats'])) {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$route_id = $_POST['route_id'];
$selected_seats = $_POST['seats'];
$total_seats = count($selected_seats);

// Passenger Details from previous step
$passenger_name = isset($_POST['passenger_name']) ? $_POST['passenger_name'] : '';
$contact_number = isset($_POST['contact_number']) ? $_POST['contact_number'] : '';
$pickup_location = isset($_POST['pickup_location']) ? $_POST['pickup_location'] : '';

// Fetch route details
$route_query = "SELECT r.*, b.bus_name, b.bus_number 
                FROM routes r 
                JOIN buses b ON r.bus_id = b.id 
                WHERE r.id = $route_id";
$route_result = $conn->query($route_query);
$route = $route_result->fetch_assoc();

$total_price = $total_seats * $route['price'];
$min_payment = $total_price / 2; // Half payment allowed

$error = "";
$success = "";
$ticket_data = [];

// Process Payment & Finalize Booking
if (isset($_POST['confirm_payment'])) {
    $paid_amount = $_POST['paid_amount'];
    $transaction_id = $_POST['transaction_id'];
    $payment_method = $_POST['payment_method'];
    
    $passenger_name = mysqli_real_escape_string($conn, $_POST['passenger_name']);
    $contact_number = mysqli_real_escape_string($conn, $_POST['contact_number']);
    $pickup_location = mysqli_real_escape_string($conn, $_POST['pickup_location']);

    // Server-side validation
    if ($paid_amount < $min_payment) {
        $error = "Minimum payment required is Rs. " . $min_payment;
    } elseif (empty($transaction_id)) {
        $error = "Please enter the Transaction ID.";
    } else {
        // Determine status
        $payment_status = ($paid_amount >= $total_price) ? 'paid' : 'partial';

        // Pre-check Availability
        $seats_available = true;
        $taken_seats = [];
        foreach ($selected_seats as $seat) {
            $check_sql = "SELECT id FROM bookings WHERE route_id = '$route_id' AND seat_number = '$seat' AND payment_status != 'cancelled'";
            $check_result = $conn->query($check_sql);
            if ($check_result->num_rows > 0) {
                $seats_available = false;
                $taken_seats[] = $seat;
            }
        }

        if (!$seats_available) {
            $error = "Error: Some seats (" . implode(", ", $taken_seats) . ") were just booked by another user. Please select different seats.";
        } else {
            // Insert bookings
            foreach ($selected_seats as $seat) {
             $sql = "INSERT INTO bookings (user_id, route_id, seat_number, payment_status, payment_method, transaction_id, paid_amount, passenger_name, contact_number, pickup_location) 
                        VALUES ('$user_id', '$route_id', '$seat', '$payment_status', '$payment_method', '$transaction_id', '$paid_amount', '$passenger_name', '$contact_number', '$pickup_location')";
                $conn->query($sql);
            }
            
            // --- Notification Logic ---
            $msg_user = "Booking Confirmed! Route: " . $route['source'] . " to " . $route['destination'] . " | Seats: " . implode(", ", $selected_seats);
            $conn->query("INSERT INTO notifications (user_id, type, message) VALUES ('$user_id', 'success', '$msg_user')");
            
            $msg_admin = "New Booking Alert! User ID: $user_id booked " . count($selected_seats) . " seat(s) on Route ID: $route_id. Trans ID: $transaction_id";
            $conn->query("INSERT INTO notifications (user_id, type, message) VALUES (NULL, 'info', '$msg_admin')"); // NULL user_id for Admin
            // --------------------------
            
            $success = "Booking Successful! Status: " . ucfirst($payment_status);
            // Prepare ticket data for JS
            $ticket_data = [
                'name' => $passenger_name,
                'contact' => $contact_number,
                'pickup' => $pickup_location,
                'bus' => $route['bus_name'] . ' (' . $route['bus_number'] . ')',
                'route' => $route['source'] . ' - ' . $route['destination'],
                'date' => $route['departure_date'] . ' ' . date('h:i A', strtotime($route['departure_time'])),
                'seats' => implode(", ", $selected_seats),
                'total' => $total_price,
                'paid' => $paid_amount,
                'transaction_id' => $transaction_id
            ];
            
            // Clear post data to prevent resubmission
            $_POST = array();
        }
    }
}
?>

<div class="page-header">
    <div class="container">
        <h1>Complete Payment</h1>
    </div>
</div>

<div class="container section-padding">
    <?php if($success): ?>
        <div class="alert alert-success" style="background: #d4edda; color: #155724; padding: 20px; text-align: center; border-radius: 8px;">
            <i class="fas fa-check-circle" style="font-size: 3rem; margin-bottom: 15px;"></i>
            <h3><?php echo $success; ?></h3>
            <p>Your seats have been booked.</p>
            
            <button onclick="generatePDF()" class="btn btn-primary" style="margin-top: 15px; margin-right: 10px;">
                <i class="fas fa-file-pdf"></i> Download Ticket
            </button>
            <a href="index.php" class="btn btn-outline" style="margin-top: 15px; display: inline-block;">Go to Home</a>
        </div>

        <!-- Hidden Ticket Template -->
        <div id="ticket-template" style="display: none;">
            <div style="padding: 20px; font-family: sans-serif; border: 2px dashed #333; width: 600px; margin: 0 auto;">
                <div style="text-align: center; border-bottom: 2px solid #333; padding-bottom: 10px; margin-bottom: 20px;">
                    <h1 style="color: #4CAF50; margin: 0;">QuickTicket</h1>
                    <p style="margin: 5px 0 0;">Official Bus Ticket</p>
                </div>
                
                <div style="margin-bottom: 15px;">
                    <strong>Passenger:</strong> <?php echo $ticket_data['name']; ?><br>
                    <strong>Contact:</strong> <?php echo $ticket_data['contact']; ?>
                </div>

                <table style="width: 100%; border-collapse: collapse; margin-bottom: 20px;">
                    <tr style="background: #eee;">
                        <td style="padding: 8px; border: 1px solid #ddd;"><strong>Route</strong></td>
                        <td style="padding: 8px; border: 1px solid #ddd;"><?php echo $ticket_data['route']; ?></td>
                    </tr>
                    <tr>
                        <td style="padding: 8px; border: 1px solid #ddd;"><strong>Bus</strong></td>
                        <td style="padding: 8px; border: 1px solid #ddd;"><?php echo $ticket_data['bus']; ?></td>
                    </tr>
                    <tr style="background: #eee;">
                        <td style="padding: 8px; border: 1px solid #ddd;"><strong>Departure</strong></td>
                        <td style="padding: 8px; border: 1px solid #ddd;"><?php echo $ticket_data['date']; ?></td>
                    </tr>
                    <tr>
                        <td style="padding: 8px; border: 1px solid #ddd;"><strong>Pickup Location</strong></td>
                        <td style="padding: 8px; border: 1px solid #ddd;"><?php echo $ticket_data['pickup']; ?></td>
                    </tr>
                    <tr style="background: #eee;">
                        <td style="padding: 8px; border: 1px solid #ddd;"><strong>Seat(s)</strong></td>
                        <td style="padding: 8px; border: 1px solid #ddd;"><strong><?php echo $ticket_data['seats']; ?></strong></td>
                    </tr>
                </table>

                <div style="text-align: right;">
                    <p>Total Amount: Rs. <?php echo $ticket_data['total']; ?></p>
                    <p>Paid Amount: Rs. <?php echo $ticket_data['paid']; ?></p>
                    <p style="font-size: 0.8rem; color: #666;">Transaction ID: <?php echo $ticket_data['transaction_id']; ?></p>
                </div>
                
                <div style="margin-top: 20px; text-align: center; font-size: 0.8rem; border-top: 1px solid #ddd; padding-top: 10px;">
                    Thank you for choosing QuickTicket. Have a safe journey!
                </div>
            </div>
        </div>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
        <script>
            function generatePDF() {
                const element = document.getElementById('ticket-template');
                element.style.display = 'block'; // Temporarily show for rendering
                
                var opt = {
                    margin:       10,
                    filename:     'BusTicket_<?php echo $ticket_data['transaction_id']; ?>.pdf',
                    image:        { type: 'jpeg', quality: 0.98 },
                    html2canvas:  { scale: 2 },
                    jsPDF:        { unit: 'mm', format: 'a4', orientation: 'portrait' }
                };

                html2pdf().set(opt).from(element).save().then(function() {
                    element.style.display = 'none'; // Hide again
                });
            }
        </script>

    <?php else: ?>

    <div class="payment-container" style="display: flex; gap: 40px; flex-wrap: wrap;">
        <!-- Booking Summary -->
        <div class="booking-summary" style="flex: 1; min-width: 300px; background: #fff; padding: 25px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
            <h3>Booking Summary</h3>
            <hr>
            <p><strong>Route:</strong> <?php echo $route['source'] . ' - ' . $route['destination']; ?></p>
            <p><strong>Bus:</strong> <?php echo $route['bus_name']; ?> (<?php echo $route['bus_number']; ?>)</p>
            <p><strong>Date:</strong> <?php echo $route['departure_date'] . ' ' . date('h:i A', strtotime($route['departure_time'])); ?></p>
            <p><strong>Selected Seats:</strong> <?php echo implode(", ", $selected_seats); ?></p>
            <hr>
            <p><strong>Passenger:</strong> <?php echo htmlspecialchars($passenger_name); ?></p>
            <p><strong>Contact:</strong> <?php echo htmlspecialchars($contact_number); ?></p>
            <p><strong>Pickup:</strong> <?php echo htmlspecialchars($pickup_location); ?></p>
            <hr>
            <p style="font-size: 1.2rem;"><strong>Total Price:</strong> <span style="color: var(--primary-color);">Rs. <?php echo $total_price; ?></span></p>
            <p><small class="text-muted">Minimum advance payment required: Rs. <?php echo $min_payment; ?></small></p>
        </div>

        <!-- Payment Details -->
        <div class="payment-details" style="flex: 1; min-width: 300px; background: #fff; padding: 25px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
            
            <?php if($error): ?>
                <div class="alert alert-danger" style="background: #f8d7da; color: #721c24; padding: 10px; margin-bottom: 15px; border-radius: 4px;">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="">
                
                <!-- Passenger Details (Hidden, passed from previous step) -->
                <input type="hidden" name="passenger_name" value="<?php echo htmlspecialchars($passenger_name); ?>">
                <input type="hidden" name="contact_number" value="<?php echo htmlspecialchars($contact_number); ?>">
                <input type="hidden" name="pickup_location" value="<?php echo htmlspecialchars($pickup_location); ?>">

                <h3 style="margin-bottom: 20px; font-size: 1.2rem; border-bottom: 2px solid var(--primary-color); display: inline-block; padding-bottom: 5px;">Payment</h3>

                <div class="qr-code-section" style="text-align: center; margin-bottom: 20px;">
                    <p>Scan to Pay with eSewa</p>
                    <img src="assets/images/esewa%20image%20.jpg" alt="eSewa QR Code" style="width: 200px; border: 1px solid #ddd; padding: 5px; border-radius: 4px;">
                    <p class="small text-muted" style="margin-top: 5px;">Shriyog Sapkota - 9867993001</p>
                    
                     <div style="margin-top: 20px; display: flex; gap: 10px; justify-content: center;">
                        <a href="https://esewa.com.np" target="_blank" class="btn" style="background: #4CAF50; color: white; display: flex; align-items: center; gap: 5px; text-decoration: none; padding: 8px 12px; border-radius: 4px;">
                            <i class="fas fa-external-link-alt"></i> Open eSewa
                        </a>
                        <a href="https://khalti.com" target="_blank" class="btn" style="background: #5C2D91; color: white; display: flex; align-items: center; gap: 5px; text-decoration: none; padding: 8px 12px; border-radius: 4px;">
                            <i class="fas fa-wallet"></i> Open Khalti
                        </a>
                    </div>
                </div>

                <!-- Pass seat data forward -->
                <?php foreach($selected_seats as $seat): ?>
                    <input type="hidden" name="seats[]" value="<?php echo $seat; ?>">
                <?php endforeach; ?>
                <input type="hidden" name="route_id" value="<?php echo $route_id; ?>">

                <div class="form-group" style="margin-bottom: 15px;">
                    <label>Payment Method</label>
                    <select name="payment_method" class="form-control" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
                        <option value="esewa">eSewa</option>
                        <option value="khalti">Khalti</option>
                    </select>
                </div>

                <div class="form-group" style="margin-bottom: 15px;">
                    <label>Amount Paid (Rs.)</label>
                    <input type="number" name="paid_amount" class="form-control" placeholder="Enter amount paid" min="<?php echo $min_payment; ?>" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
                    <small>Min: <?php echo $min_payment; ?> (50%) - Max: <?php echo $total_price; ?> (100%)</small>
                </div>

                <div class="form-group" style="margin-bottom: 20px;">
                    <label>Transaction ID / Ref ID</label>
                    <input type="text" name="transaction_id" class="form-control" placeholder="Enter Reference ID from App" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
                </div>

                <button type="submit" name="confirm_payment" class="btn btn-success" style="width: 100%; background: #28a745; color: white; padding: 12px; border: none; border-radius: 4px; font-size: 1.1rem; cursor: pointer;">
                    Verify & Book
                </button>
            </form>
        </div>
    </div>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>
