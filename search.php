<?php
include 'includes/db.php';
include 'includes/header.php';

$from = isset($_GET['from']) ? $_GET['from'] : '';
$to = isset($_GET['to']) ? $_GET['to'] : '';
$date = isset($_GET['date']) ? $_GET['date'] : '';
?>

<div class="page-header">
    <div class="container">
        <h1>Search Results</h1>
        <p><?php echo ucfirst($from); ?> to <?php echo ucfirst($to); ?> on <?php echo $date; ?></p>
    </div>
</div>


<div class="container section-padding">
    <!-- Filters -->
    <div class="filters">
        <span style="font-weight: bold; color: #555;"><i class="fas fa-filter"></i> Filter:</span>
        <form action="" method="GET">
            <input type="hidden" name="from" value="<?php echo htmlspecialchars($from); ?>">
            <input type="hidden" name="to" value="<?php echo htmlspecialchars($to); ?>">
            <input type="hidden" name="date" value="<?php echo htmlspecialchars($date); ?>">
            
            <select name="type" style="padding: 8px; border: 1px solid #ced4da; border-radius: 4px;">
                <option value="">All Bus Types</option>
                <option value="Deluxe" <?php if(isset($_GET['type']) && $_GET['type'] == 'Deluxe') echo 'selected'; ?>>Deluxe</option>
                <option value="Standard" <?php if(isset($_GET['type']) && $_GET['type'] == 'Standard') echo 'selected'; ?>>Standard</option>
                <option value="VIP" <?php if(isset($_GET['type']) && $_GET['type'] == 'VIP') echo 'selected'; ?>>VIP / Sofa</option>
            </select>
            
            <input type="number" name="max_price" placeholder="Max Price" value="<?php echo isset($_GET['max_price']) ? htmlspecialchars($_GET['max_price']) : ''; ?>" style="padding: 8px; border: 1px solid #ced4da; border-radius: 4px; width: 100px;">
            
            <button type="submit" class="btn btn-primary" style="padding: 8px 15px; font-size: 0.9rem;">Apply</button>
            <?php if(isset($_GET['type']) || isset($_GET['max_price'])): ?>
                <a href="search.php?from=<?php echo urlencode($from); ?>&to=<?php echo urlencode($to); ?>&date=<?php echo urlencode($date); ?>" style="color: red; text-decoration: none; font-size: 0.9rem;">Clear</a>
            <?php endif; ?>
        </form>
    </div>

    <div class="search-results">
        <?php
        if ($from && $to && $date) {
            $filter_type = isset($_GET['type']) ? $_GET['type'] : '';
            $filter_price = isset($_GET['max_price']) ? $_GET['max_price'] : '';

            $sql = "SELECT r.*, b.bus_name, b.bus_type, b.total_seats 
                    FROM routes r 
                    JOIN buses b ON r.bus_id = b.id 
                    WHERE r.source LIKE '%$from%' AND r.destination LIKE '%$to%' AND r.departure_date = '$date'";
            
            if (!empty($filter_type)) {
                $sql .= " AND b.bus_type LIKE '%$filter_type%'";
            }
            if (!empty($filter_price)) {
                $sql .= " AND r.price <= $filter_price";
            }

            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    ?>
                    <div class="bus-card">
                        <div class="bus-info">
                            <h3 style="color: var(--secondary-color); margin-bottom: 5px;"><?php echo $row['bus_name']; ?></h3>
                            <span class="badge" style="background: #e1f5fe; color: #0288d1; padding: 4px 8px; border-radius: 4px; font-size: 0.8rem;"><?php echo $row['bus_type']; ?></span>
                        </div>
                        <div class="route-time">
                            <div style="text-align: center;">
                                <p style="font-size: 1.2rem; font-weight: bold;"><?php echo date('h:i A', strtotime($row['departure_time'])); ?></p>
                                <p style="color: var(--gray); font-size: 0.9rem;">Departure</p>
                            </div>
                        </div>
                        <div class="price-action" style="text-align: right;">
                            <h3 style="color: var(--primary-color); margin-bottom: 10px;">Rs. <?php echo $row['price']; ?></h3>
                            <a href="book.php?route_id=<?php echo $row['id']; ?>" class="btn btn-primary">Select Seat</a>
                        </div>
                    </div>
                    <?php
                }
            } else {
                echo "<div style='text-align: center; padding: 40px;'>
                        <i class='fas fa-search' style='font-size: 3rem; color: #ddd; margin-bottom: 20px;'></i>
                        <h3>No buses found for this route and date.</h3>
                        <p>Try searching for a different date.</p>
                        <a href='index.php' class='btn btn-outline' style='margin-top: 15px;'>Back to Home</a>
                      </div>";
            }
        } else {
            echo "<p>Please provide search criteria.</p>";
        }
        ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
