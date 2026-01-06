<?php
include '../includes/db.php';
include 'includes/header.php';

if ($_SESSION['role'] !== 'admin') {
    die("Access Denied");
}
?>

<h2>Bookings</h2>

<div class="card">
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Passenger</th>
                <th>Contact</th>
                <th>Bus</th>
                <th>Route</th>
                <th>Date/Time</th>
                <th>Seat</th>
                <th>Pickup</th>
                <th>Pay Status</th>
                <th>Trans ID</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Check for status messages
            if (isset($_GET['msg'])) {
                echo "<tr><td colspan='11' style='background: #d4edda; color: #155724; padding: 10px; text-align: center;'>{$_GET['msg']}</td></tr>";
            }

            $sql = "SELECT b.id, b.passenger_name, b.contact_number, b.email, b.pickup_location, b.drop_location, v.bus_name, v.bus_number, r.source, r.destination, r.departure_date, r.departure_time, b.seat_number, b.payment_status, b.status, b.transaction_id, b.paid_amount 
                    FROM bookings b 
                    JOIN routes r ON b.route_id = r.id
                    JOIN buses v ON r.bus_id = v.id 
                    ORDER BY b.id DESC";
            
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {


                    echo "<tr>
                        <td>{$row['id']}</td>
                        <td>
                            <strong>{$row['passenger_name']}</strong><br>
                        </td>
                        <td>
                            {$row['contact_number']}<br>
                            <small class='text-muted'>{$row['email']}</small>
                        </td>
                        <td>{$row['bus_name']} <br><small class='text-muted'>({$row['bus_number']})</small></td>
                        <td>{$row['source']} - {$row['destination']}</td>
                        <td>{$row['departure_date']} <br> <small>" . date('h:i A', strtotime($row['departure_time'])) . "</small></td>
                        <td><span style='background: #eef; padding: 2px 6px; border-radius: 4px;'>{$row['seat_number']}</span></td>
                        <td>
                            <i class='fas fa-map-marker-alt' style='color: #28a745;'></i> {$row['pickup_location']}
                            " . (!empty($row['drop_location']) ? "<br><i class='fas fa-map-marker' style='color: #dc3545;'></i> {$row['drop_location']}" : "") . "
                        </td>

                        <td>
                            <span style='padding: 5px 10px; border-radius: 4px; color: white; background: " . 
                                ($row['payment_status'] == 'paid' ? '#28a745' : ($row['payment_status'] == 'partial' ? '#ffc107' : '#dc3545')) . 
                            "'>{$row['payment_status']}</span><br>
                            <small>Rs. {$row['paid_amount']}</small><br>
                            <small style='color: " . ($row['status'] == 'confirmed' ? 'green' : 'orange') . "; font-weight: bold;'>
                                " . ucfirst($row['status']) . "
                            </small>
                        </td>
                        <td><small>{$row['transaction_id']}</small></td>
                        <td>
                            ";
                            if ($row['payment_status'] != 'paid') {
                                echo "<a href='process_booking.php?action=confirm&id={$row['id']}' style='color: green; margin-right: 10px;' title='Confirm Payment & Booking'><i class='fas fa-check-circle'></i></a>";
                            }
                            
                            // Manual Status Toggle
                            if ($row['status'] == 'pending') {
                                echo "<a href='process_booking.php?action=confirm_booking&id={$row['id']}' style='color: #28a745; margin-right: 10px;' title='Approve/Confirm Booking'><i class='fas fa-check'></i></a>";
                            } else {
                                echo "<a href='process_booking.php?action=pending_booking&id={$row['id']}' style='color: #ffc107; margin-right: 10px;' title='Set Pending'><i class='fas fa-clock'></i></a>";
                            }

                            echo "
                            <a href='edit_booking.php?id={$row['id']}' style='color: blue; margin-right: 10px;' title='Edit Booking'><i class='fas fa-edit'></i></a>
                            <a href='process_booking.php?action=delete&id={$row['id']}' style='color: red;' title='Delete' onclick='return confirm(\"Are you sure?\")'><i class='fas fa-trash'></i></a>
                        </td>
                    </tr>";
                }
            } else {
                echo "<tr><td colspan='11'>No bookings found</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<?php include 'includes/footer.php'; ?>
