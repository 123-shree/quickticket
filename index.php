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


<!-- Amenities Section -->
<div class="section-padding" style="background: #f8f9fa;">
    <div class="container">
        <div class="section-title">
            <h2>Experience the Best</h2>
            <p>We provide top-notch facilities to make your journey memorable.</p>
        </div>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 30px; margin-bottom: 60px;">
            <div style="background: white; padding: 30px; border-radius: 15px; text-align: center; box-shadow: 0 5px 15px rgba(0,0,0,0.05); transition: 0.3s; border-bottom: 4px solid #4CAF50;">
                <div style="width: 60px; height: 60px; background: #e8f5e9; color: #4CAF50; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; margin: 0 auto 20px;">
                    <i class="fas fa-wifi"></i>
                </div>
                <h3 style="margin-bottom: 10px; color: var(--secondary-color);">Free Wi-Fi</h3>
                <p style="color: #666;">Stay connected with high-speed internet throughout your journey.</p>
            </div>

            <div style="background: white; padding: 30px; border-radius: 15px; text-align: center; box-shadow: 0 5px 15px rgba(0,0,0,0.05); transition: 0.3s; border-bottom: 4px solid #FF9800;">
                <div style="width: 60px; height: 60px; background: #fff3e0; color: #FF9800; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; margin: 0 auto 20px;">
                    <i class="fas fa-bolt"></i>
                </div>
                <h3 style="margin-bottom: 10px; color: var(--secondary-color);">Charging Hubs</h3>
                <p style="color: #666;">Personal USB charging ports at every seat for your devices.</p>
            </div>

            <div style="background: white; padding: 30px; border-radius: 15px; text-align: center; box-shadow: 0 5px 15px rgba(0,0,0,0.05); transition: 0.3s; border-bottom: 4px solid #F44336;">
                <div style="width: 60px; height: 60px; background: #ffebee; color: #F44336; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; margin: 0 auto 20px;">
                    <i class="fas fa-utensils"></i>
                </div>
                <h3 style="margin-bottom: 10px; color: var(--secondary-color);">Onboard Snacks</h3>
                <p style="color: #666;">Complimentary water and light snacks on VIP and Deluxe trips.</p>
            </div>

            <div style="background: white; padding: 30px; border-radius: 15px; text-align: center; box-shadow: 0 5px 15px rgba(0,0,0,0.05); transition: 0.3s; border-bottom: 4px solid #2196F3;">
                <div style="width: 60px; height: 60px; background: #e3f2fd; color: #2196F3; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; margin: 0 auto 20px;">
                    <i class="fas fa-couch"></i>
                </div>
                <h3 style="margin-bottom: 10px; color: var(--secondary-color);">Premium Seats</h3>
                <p style="color: #666;">Extra legroom and sofa-style reclining seats for maximum comfort.</p>
            </div>
        </div>

        <!-- Mini Fleet Showcase -->
        <div class="section-title">
            <h2>Our Premium Fleet</h2>
        </div>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 30px;">
             <!-- VIP -->
             <div class="bus-card" style="background: white; border-radius: 20px; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.08);">
                <div style="height: 220px; overflow: hidden;">
                    <img src="assets/images/vip_bus.png" alt="VIP Bus" style="width: 100%; height: 100%; object-fit: cover;">
                </div>
                <div style="padding: 25px;">
                    <h3 style="color: var(--secondary-color); margin-bottom: 10px;">VIP / Sofa Bus</h3>
                    <p style="color: #666; font-size: 0.95rem;">2x1 Sofa Seating • Air Suspension</p>
                </div>
            </div>
            <!-- Deluxe -->
            <div class="bus-card" style="background: white; border-radius: 20px; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.08);">
                <div style="height: 220px; overflow: hidden;">
                    <img src="assets/images/deluxe_bus.png" alt="Deluxe Bus" style="width: 100%; height: 100%; object-fit: cover;">
                </div>
                <div style="padding: 25px;">
                    <h3 style="color: var(--secondary-color); margin-bottom: 10px;">Deluxe Bus</h3>
                    <p style="color: #666; font-size: 0.95rem;">2x2 Mini Sofa • AC • Wifi</p>
                </div>
            </div>
            <!-- Standard -->
            <div class="bus-card" style="background: white; border-radius: 20px; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.08);">
                <div style="height: 220px; overflow: hidden;">
                    <img src="assets/images/bus.png" alt="Standard Bus" style="width: 100%; height: 100%; object-fit: cover;">
                </div>
                <div style="padding: 25px;">
                    <h3 style="color: var(--secondary-color); margin-bottom: 10px;">Standard Bus</h3>
                    <p style="color: #666; font-size: 0.95rem;">2x2 Standard • Budget Friendly</p>
                </div>
            </div>
        </div>

    </div>
</div>

<?php include 'includes/footer.php'; ?>
