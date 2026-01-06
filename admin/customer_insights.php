<?php
include '../includes/db.php';
include 'includes/header.php';

if ($_SESSION['role'] !== 'admin') {
    die("Access Denied");
}

// Logic to fetch frequent customers
$search = isset($_GET['search']) ? $_GET['search'] : '';
$period = isset($_GET['period']) ? $_GET['period'] : 'all';

$where_clause = "WHERE contact_number != ''";

// Search Filter
if (!empty($search)) {
    $search_safe = mysqli_real_escape_string($conn, $search);
    $where_clause .= " AND (passenger_name LIKE '%$search_safe%' OR contact_number LIKE '%$search_safe%')";
}

// Date Filter
if ($period == '1_month') {
    $where_clause .= " AND booking_date >= DATE_SUB(NOW(), INTERVAL 1 MONTH)";
} elseif ($period == '1_year') {
    $where_clause .= " AND booking_date >= DATE_SUB(NOW(), INTERVAL 1 YEAR)";
}

$sql = "SELECT 
            b.passenger_name, 
            b.contact_number, 
            b.email,
            COUNT(b.id) as total_bookings, 
            MAX(b.booking_date) as last_travel_date,
            SUM(b.paid_amount) as total_spent,
            u.profile_image,
            u.blood_group,
            u.citizenship_front,
            u.citizenship_back,
            u.phone_number as user_phone,
            (SELECT COUNT(*) FROM bookings b2 WHERE b2.user_id = b.user_id AND b2.status='confirmed') as confirmed_count
        FROM bookings b
        LEFT JOIN users u ON b.user_id = u.id
        $where_clause
        GROUP BY b.contact_number 
        ORDER BY total_bookings DESC";

$result = $conn->query($sql);
?>

<h2>Customer Insights & Frequent Flyers</h2>
<p>Identify your most loyal customers to offer discounts.</p>

<div class="card" style="margin-bottom: 20px; padding: 20px;">
    <form method="GET" action="" style="display: flex; gap: 15px; align-items: end;">
        <div style="flex: 1;">
            <label>Search Customer</label>
            <input type="text" name="search" class="form-control" placeholder="Enter Name or Phone Number" value="<?php echo htmlspecialchars($search); ?>" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
        </div>
        <div>
            <label>Time Period</label>
            <select name="period" class="form-control" style="padding: 10px; border: 1px solid #ddd; border-radius: 4px; min-width: 150px;">
                <option value="all" <?php if($period == 'all') echo 'selected'; ?>>All Time</option>
                <option value="1_month" <?php if($period == '1_month') echo 'selected'; ?>>Last 1 Month</option>
                <option value="1_year" <?php if($period == '1_year') echo 'selected'; ?>>Last 1 Year</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary" style="padding: 10px 20px; background: var(--primary-color); color: white; border: none; border-radius: 4px; cursor: pointer;">
            <i class="fas fa-search"></i> Filter
        </button>
        <a href="customer_insights.php" class="btn" style="padding: 10px 20px; background: #eee; color: #333; text-decoration: none; border-radius: 4px;">Reset</a>
    </form>
</div>

<div class="card">
    <table id="insightsTable">
        <thead>
            <tr>
                <th>Rank</th>
                <th>Passenger Profile</th>
                <th>Contact Info</th>
                <th>Stats</th>
                <th>ID Status</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                $rank = 1;
                while ($row = $result->fetch_assoc()) {
                    // Determine Member Tier
                    $tier_badge = '<span style="background: #eef; color: #333; padding: 2px 6px; border-radius: 4px;">Regular</span>';
                    if ($row['confirmed_count'] >= 3) {
                         $tier_badge = '<span style="background: gold; color: #333; padding: 2px 6px; border-radius: 4px; font-weight: bold;"><i class="fas fa-crown"></i> Gold Member</span>';
                    }

                    // Verification Documents
                    $docs_html = '';
                    if (!empty($row['citizenship_front']) && !empty($row['citizenship_back'])) {
                        $docs_html = '<span style="color: green; font-size: 0.8rem;"><i class="fas fa-check-circle"></i> ID Verified</span>';
                        // Add link/modal to view docs (simplified here as just text indicating they exist)
                        $docs_html .= '<div style="margin-top: 5px; display: flex; gap: 5px;">
                            <a href="../'.$row['citizenship_front'].'" target="_blank"><img src="../'.$row['citizenship_front'].'" style="width: 30px; height: 20px; border: 1px solid #ccc;"></a>
                            <a href="../'.$row['citizenship_back'].'" target="_blank"><img src="../'.$row['citizenship_back'].'" style="width: 30px; height: 20px; border: 1px solid #ccc;"></a>
                        </div>';
                    } else {
                        $docs_html = '<span style="color: #999; font-size: 0.8rem;">Unverified</span>';
                    }

                    // Profile Image
                    $prof_img = !empty($row['profile_image']) ? '../'.$row['profile_image'] : 'https://via.placeholder.com/50';
                    $blood_group = !empty($row['blood_group']) ? '<span style="color: #d9534f; font-weight: bold;">'.$row['blood_group'].'</span>' : '-';

                    echo "<tr>
                        <td>#{$rank}</td>
                        <td style='display: flex; gap: 10px; align-items: center;'>
                            <img src='{$prof_img}' style='width: 40px; height: 40px; border-radius: 50%; object-fit: cover; border: 1px solid #ddd;'>
                            <div>
                                <div style='font-weight: bold;'>{$row['passenger_name']}</div>
                                <div style='font-size: 0.85rem; opacity: 0.8;'>Blood: {$blood_group}</div>
                            </div>
                        </td>
                        <td>
                            <div><i class='fas fa-phone' style='width: 15px;'></i> {$row['contact_number']}</div>
                            <div style='font-size: 0.9rem; color: #666;'><i class='fas fa-envelope' style='width: 15px;'></i> {$row['email']}</div>
                        </td>
                        <td>
                            <div style='font-weight: bold;'>{$row['total_bookings']} Bookings</div>
                            <div style='color: green;'>Rs. {$row['total_spent']}</div>
                            <div style='font-size: 0.8rem; opacity: 0.7;'>Last: " . date('Y-m-d', strtotime($row['last_travel_date'])) . "</div>
                        </td>
                        <td>
                            <div style='margin-bottom: 5px;'>{$tier_badge}</div>
                            {$docs_html}
                        </td>
                    </tr>";
                    $rank++;
                }
            } else {
                echo "<tr><td colspan='8'>No customer data found.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<?php include 'includes/footer.php'; ?>
