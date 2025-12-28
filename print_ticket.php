<?php
include 'includes/db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['id'])) {
    die("Invalid Ticket ID");
}

$booking_id = $_GET['id'];
$user_id = $_SESSION['user_id'];

// Fetch booking details
$sql = "SELECT b.*, r.source, r.destination, r.departure_date, r.departure_time, v.bus_name, v.bus_number 
        FROM bookings b 
        JOIN routes r ON b.route_id = r.id 
        JOIN buses v ON r.bus_id = v.id 
        WHERE b.id = '$booking_id' AND b.user_id = '$user_id'";

$result = $conn->query($sql);

if ($result->num_rows == 0) {
    die("Ticket not found or access denied.");
}

$ticket = $result->fetch_assoc();

// If cancelled, don't show ticket? Or show VOID?
if ($ticket['payment_status'] == 'cancelled') {
    die("<h2 style='color:red; text-align:center; margin-top:50px;'>This ticket has been cancelled.</h2>");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Ticket - QuickTicket</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <style>
        body { background: #f4f6f8; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .ticket-container {
            max-width: 700px;
            margin: 40px auto;
            background: white;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        .header { text-align: center; border-bottom: 2px dashed #ddd; padding-bottom: 20px; margin-bottom: 30px; }
        .logo { color: var(--primary-color); font-size: 2rem; font-weight: bold; }
        .ticket-info { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 30px; }
        .info-group label { display: block; color: #888; font-size: 0.9rem; }
        .info-group span { font-weight: 600; font-size: 1.1rem; color: #333; }
        .status-badge { 
            display: inline-block; padding: 5px 10px; border-radius: 4px; font-weight: bold; font-size: 0.9rem;
            background: #e8f5e9; color: #2e7d32; 
        }
        .actions { text-align: center; margin-top: 40px; }
        @media (max-width: 600px) {
            .ticket-container { padding: 20px; margin: 10px; }
            .ticket-info { grid-template-columns: 1fr; }
            .logo { font-size: 1.5rem; }
        }
    </style>
</head>
<body>

<div class="ticket-container" id="ticket-content">
    <div class="header">
        <div class="logo"><i class="fas fa-bus-alt"></i> QuickTicket</div>
        <p>Official E-Ticket</p>
        <div class="status-badge" style="<?php echo $ticket['payment_status'] == 'partial' ? 'background:#fff3e0;color:#ef6c00;' : ''; ?>">
            <?php echo strtoupper($ticket['payment_status']); ?>
        </div>
    </div>

    <div class="ticket-info">
        <div class="info-group">
            <label>Passenger Name</label>
            <span><?php echo $ticket['passenger_name']; ?></span>
        </div>
        <div class="info-group">
            <label>Contact Number</label>
            <span><?php echo $ticket['contact_number']; ?></span>
        </div>
        <div class="info-group">
            <label>Bus</label>
            <span><?php echo $ticket['bus_name']; ?> (<?php echo $ticket['bus_number']; ?>)</span>
        </div>
        <div class="info-group">
            <label>Seat Number</label>
            <span style="color: var(--primary-color);"><?php echo $ticket['seat_number']; ?></span>
        </div>
        <div class="info-group">
            <label>Route</label>
            <span><?php echo $ticket['source']; ?> <i class="fas fa-arrow-right" style="font-size: 0.8rem; opacity: 0.5;"></i> <?php echo $ticket['destination']; ?></span>
        </div>
        <div class="info-group">
            <label>Departure</label>
            <span><?php echo $ticket['departure_date'] . ' ' . date('h:i A', strtotime($ticket['departure_time'])); ?></span>
        </div>
        <div class="info-group">
            <label>Boarding Point</label>
            <span><?php echo $ticket['pickup_location']; ?></span>
        </div>
        <div class="info-group">
            <label>Transaction ID</label>
            <span><?php echo $ticket['transaction_id']; ?></span>
        </div>
    </div>

    <div style="text-align: right; border-top: 1px solid #eee; padding-top: 20px;">
        <label style="color: #888;">Paid Amount</label>
        <div style="font-size: 1.5rem; font-weight: bold; color: var(--secondary-color);">Rs. <?php echo $ticket['paid_amount']; ?></div>
    </div>
    
    <div style="margin-top: 30px; font-size: 0.8rem; color: #999; text-align: center;">
        <p>Please show this ticket to the bus staff while boarding.</p>
        <p>QuickTicket Support: 9800000000</p>
    </div>
</div>

<div class="actions">
    <button onclick="downloadPDF()" class="btn btn-primary" style="padding: 12px 25px; cursor: pointer; background: var(--primary-color); color: white; border: none; border-radius: 5px; font-size: 1rem;">
        <i class="fas fa-download"></i> Download PDF
    </button>
    <button onclick="window.print()" class="btn btn-outline" style="padding: 12px 25px; cursor: pointer; background: white; color: #333; border: 1px solid #ddd; border-radius: 5px; font-size: 1rem; margin-left: 10px;">
        <i class="fas fa-print"></i> Print
    </button>
    <br><br>
    <a href="my_tickets.php" style="color: #666; text-decoration: none;">Back to My Tickets</a>
</div>

<script>
function downloadPDF() {
    const element = document.getElementById('ticket-content');
    var opt = {
        margin:       10,
        filename:     'QuickTicket_<?php echo $ticket['id']; ?>.pdf',
        image:        { type: 'jpeg', quality: 0.98 },
        html2canvas:  { scale: 2 },
        jsPDF:        { unit: 'mm', format: 'a4', orientation: 'portrait' }
    };
    html2pdf().set(opt).from(element).save();
}
</script>

</body>
</html>
