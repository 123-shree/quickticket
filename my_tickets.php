<?php
include 'includes/db.php';
include 'includes/header.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
?>

<div class="page-header">
    <div class="container">
        <h1>My Tickets</h1>
    </div>
</div>

<div class="container section-padding">
    <?php
    $sql = "SELECT b.id, v.bus_name, v.bus_number, v.bus_type, r.source, r.destination, r.departure_date, r.departure_time, b.seat_number, b.payment_status, b.transaction_id, b.paid_amount, r.price 
            FROM bookings b 
            LEFT JOIN routes r ON b.route_id = r.id
            LEFT JOIN buses v ON r.bus_id = v.id 
            WHERE b.user_id = $user_id 
            ORDER BY b.id DESC";
    
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        echo '<div class="tickets-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 30px;">';
        while($row = $result->fetch_assoc()) {
            $status_color = 'orange';
            $status_text = 'Pending';
            $verification_icon = '<i class="fas fa-clock"></i>';
            
            if ($row['payment_status'] == 'paid') {
                $status_color = '#2ecc71'; // Green
                $status_text = 'Confirmed';
                $verification_icon = '<i class="fas fa-check-circle"></i>';
            } elseif ($row['payment_status'] == 'partial') {
                $status_color = '#f1c40f'; // Yellow
                $status_text = 'Partial Paid';
            } elseif ($row['payment_status'] == 'cancelled') {
                $status_color = '#95a5a6'; // Gray
                $status_text = 'Cancelled';
                $verification_icon = '<i class="fas fa-ban"></i>';
            } else {
                $status_color = '#e74c3c'; // Red
                $status_text = 'Payment Due'; 
            }
            
            // Dynamic Bus Image Logic
            $bus_image = 'assets/images/bus.png'; // Default
            $bus_type_lower = strtolower($row['bus_type']);
            
            if (strpos($bus_type_lower, 'vip') !== false || strpos($bus_type_lower, 'sofa') !== false) {
                $bus_image = 'assets/images/vip_bus.png';
            } elseif (strpos($bus_type_lower, 'deluxe') !== false) {
                $bus_image = 'assets/images/deluxe_bus.png';
            }
            
            // Should verify if file exists, but for now we trust the assets folder
             
            
            ?>
            <div class="ticket-card-wrap" style="opacity: <?php echo $status_text == 'Cancelled' ? '0.7' : '1'; ?>;">
                <div class="ticket-image" style="height: 180px; overflow: hidden; position: relative;">
                    <img src="<?php echo $bus_image; ?>" style="width: 100%; height: 100%; object-fit: cover; filter: <?php echo $status_text == 'Cancelled' ? 'grayscale(100%)' : 'none'; ?>;">
                    <div style="position: absolute; top: 15px; right: 15px; background: rgba(0,0,0,0.7); color: white; padding: 5px 12px; border-radius: 20px; font-size: 0.8rem; backdrop-filter: blur(4px);">
                        <?php echo $verification_icon . ' ' . $status_text; ?>
                    </div>
                </div>
                
                <div class="ticket-content">
                    <div class="route-line">
                        <span class="city"><?php echo $row['source']; ?></span>
                        <div class="connector">
                            <i class="fas fa-bus-alt"></i>
                            <span class="line"></span>
                        </div>
                        <span class="city"><?php echo $row['destination']; ?></span>
                    </div>
                    
                    <div class="ticket-details">
                        <div class="detail-row">
                            <span class="label"><i class="far fa-calendar"></i> Date</span>
                            <span class="value"><?php echo $row['departure_date']; ?></span>
                        </div>
                        <div class="detail-row">
                            <span class="label"><i class="far fa-clock"></i> Time</span>
                            <span class="value"><?php echo date('h:i A', strtotime($row['departure_time'])); ?></span>
                        </div>
                        <div class="detail-row">
                            <span class="label"><i class="fas fa-chair"></i> Seat</span>
                            <span class="value highlight-seat"><?php echo $row['seat_number']; ?></span>
                        </div>
                         <div class="detail-row">
                            <span class="label"><i class="fas fa-bus"></i> Bus Details</span>
                            <span class="value"><?php echo $row['bus_name']; ?></span>
                            <span style="font-size: 0.8rem; color: var(--primary-color); font-weight: bold;"><?php echo $row['bus_type']; ?></span>
                        </div>
                    </div>
                </div>
                
                <div class="ticket-footer">
                    <span class="price-tag" style="color: var(--primary-color); font-weight: bold;">Rs. <?php echo $row['price']; ?></span>
                    
                    <div style="display: flex; gap: 10px;">
                        <?php if($status_text != 'Cancelled'): ?>
                            <a href="print_ticket.php?id=<?php echo $row['id']; ?>" class="btn-view" style="text-decoration: none; color: inherit; font-size: 0.8rem; padding: 6px 12px; border: 1px solid #ccc; border-radius: 20px;">
                                <i class="fas fa-print"></i> Print
                            </a>
                            <a href="cancel_booking.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure you want to cancel this ticket?')" class="btn-view" style="text-decoration: none; color: red; border-color: red; font-size: 0.8rem; padding: 6px 12px; border-radius: 20px;">
                                <i class="fas fa-times"></i> Cancel
                            </a>
                        <?php else: ?>
                            <span style="color: #999; font-size: 0.9rem;">No actions</span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php
        }
        echo '</div>';
    } else {
        echo '<div style="text-align: center; padding: 60px; background: #fff; border-radius: 15px; box-shadow: 0 10px 25px rgba(0,0,0,0.05); max-width: 600px; margin: 0 auto;">';
        echo '<div style="background: #f0f2f5; width: 100px; height: 100px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; color: #ccc; font-size: 3rem;"><i class="fas fa-ticket-alt"></i></div>';
        echo '<h3 style="color: var(--secondary-color); margin-bottom: 10px;">No Tickets Found</h3>';
        echo '<p style="color: #666; font-size: 1.1rem; margin-bottom: 25px;">You haven\'t booked any trips yet. Your next adventure awaits!</p>';
        echo '<a href="index.php" class="btn btn-primary" style="padding: 12px 30px;">Find Buses</a>';
        echo '</div>';
    }
    ?>

<style>
    .ticket-card-wrap {
        background: white;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 10px 20px rgba(0,0,0,0.05);
        transition: transform 0.3s, box-shadow 0.3s;
        border: 1px solid #f0f0f0;
    }
    .ticket-card-wrap:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px rgba(0,0,0,0.1);
    }
    .ticket-content {
        padding: 20px;
    }
    .route-line {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }
    .route-line .city {
        font-weight: 700;
        font-size: 1.1rem;
        color: var(--secondary-color);
    }
    .connector {
        display: flex;
        flex-direction: column;
        align-items: center;
        color: var(--primary-color);
        font-size: 0.9rem;
        flex: 1;
        padding: 0 15px;
    }
    .connector .line {
        width: 100%;
        height: 2px;
        background: #eee; /* Dotted line via BG could be better but solid is clean */
        background-image: linear-gradient(to right, var(--primary-color) 33%, rgba(255,255,255,0) 0%);
        background-position: bottom;
        background-size: 8px 1px;
        background-repeat: repeat-x;
        height: 1px;
        margin-top: 5px;
    }
    .ticket-details {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 15px;
    }
    .detail-row {
        display: flex;
        flex-direction: column;
    }
    .detail-row .label {
        font-size: 0.8rem;
        color: #999;
        margin-bottom: 3px;
    }
    .detail-row .value {
        font-weight: 600;
        color: #444;
        font-size: 0.95rem;
    }
    .highlight-seat {
        color: var(--primary-color);
        font-size: 1.1rem !important;
    }
    .ticket-footer {
        padding: 15px 20px;
        background: #f9f9f9;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-top: 1px solid #eee;
    }
    .price-tag {
        font-weight: 800;
        color: var(--secondary-color);
        font-size: 1.2rem;
    }
    .btn-view {
        background: white;
        border: 1px solid #ddd;
        padding: 8px 15px;
        border-radius: 20px;
        color: #666;
        cursor: pointer;
        transition: 0.2s;
        font-size: 0.9rem;
    }
    .btn-view:hover {
        background: var(--primary-color);
        color: white;
        border-color: var(--primary-color);
    }
</style>
</div>

<?php include 'includes/footer.php'; ?>
