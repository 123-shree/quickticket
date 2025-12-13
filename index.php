<?php 
include 'includes/db.php';
include 'includes/header.php'; 
?>

<div class="hero">
    <div class="container mobile-stack">
        <div class="hero-content">
            <h1>Travel Across Nepal with <span class="highlight">QuickTicket</span></h1>
            <p>Experience the most comfortable bus journeys. Safe, reliable, and affordable tickets at your fingertips.</p>
        </div>
        <div class="hero-form">
            <form action="search.php" method="GET" class="search-form">
                <div class="form-group">
                    <label for="from"><i class="fas fa-map-marker-alt"></i> From</label>
                    <input type="text" id="from" name="from" placeholder="Enter City (e.g., Kathmandu)" required>
                </div>
                <div class="form-group">
                    <label for="to"><i class="fas fa-location-arrow"></i> To</label>
                    <input type="text" id="to" name="to" placeholder="Enter Destination (e.g., Pokhara)" required>
                </div>
                <div class="form-group">
                    <label for="date"><i class="fas fa-calendar-alt"></i> Date</label>
                    <input type="date" id="date" name="date" required>
                </div>
                <button type="submit" class="btn btn-primary search-btn">Search Buses</button>
            </form>
        </div>
    </div>
</div>

<!-- Offers Section -->
<div class="section-padding" style="background-color: var(--light-bg);">
    <div class="container">
        <div class="section-title">
            <h2>Offers</h2>
        </div>
        <div class="offers-grid">
            <?php
            $sql = "SELECT * FROM offers ORDER BY created_at DESC LIMIT 3";
            $result = $conn->query($sql);
            $i = 0;
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    $i++;
                    $promo_class = "promo-" . (($i - 1) % 3 + 1);
            ?>
            <!-- Dynamic Promo Card -->
            <div class="promo-card <?php echo $promo_class; ?>">
                <div class="promo-content">
                    <span class="promo-tag" style="color: <?php echo $row['promo_color']; ?>; background: white;"><?php echo $row['promo_tag']; ?></span>
                    <h3><?php echo $row['title']; ?></h3>
                    <p><?php echo $row['description']; ?></p>
                </div>
                <i class="<?php echo $row['icon']; ?> promo-icon"></i>
            </div>
            <?php 
                }
            } else {
                echo "<p>No offers content available at the moment.</p>";
            }
            ?>
        </div>
    </div>
</div>

<!-- Popular Routes -->
<div class="section-padding bg-white">
    <div class="container">
        <div class="section-title">
            <h2>Popular Routes</h2>
        </div>
        <div class="routes-grid">
            <?php
            // Fetch popular routes from database
            $sql = "SELECT * FROM popular_routes ORDER BY created_at DESC LIMIT 4";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
            ?>
            <a href="search.php?from=<?php echo urlencode($row['source']); ?>&to=<?php echo urlencode($row['destination']); ?>&date=<?php echo date('Y-m-d'); ?>" class="route-card">
                <div class="route-image" style="height: 160px; overflow: hidden; border-radius: 12px 12px 0 0; margin: -25px -25px 15px -25px;">
                    <img src="<?php echo $row['image_path']; ?>" alt="<?php echo $row['destination']; ?>" style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.3s ease;">
                </div>
                <div class="route-header">
                    <span class="route-city"><?php echo $row['source']; ?></span>
                    <i class="fas fa-arrow-right route-arrow"></i>
                    <span class="route-city"><?php echo $row['destination']; ?></span>
                </div>
                <div class="route-details">
                    <span><i class="far fa-clock"></i> <?php echo $row['duration']; ?></span>
                    <span class="route-price">Rs. <?php echo $row['price']; ?></span>
                </div>
            </a>
            <?php 
                }
            } else {
                echo "<p>No popular routes added yet.</p>";
            } 
            ?>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
