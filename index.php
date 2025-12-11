<?php include 'includes/header.php'; ?>

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
            <!-- Promo Banner 1 -->
            <div class="promo-card promo-1">
                <div class="promo-content">
                    <span class="promo-tag" style="color: #ff6b6b; background: white;">PROMO</span>
                    <h3>Save 10% on First Ride</h3>
                    <p>Use Code: <strong>NEWUSER</strong></p>
                </div>
                <i class="fas fa-gift promo-icon"></i>
            </div>
            
            <!-- Promo Banner 2 -->
            <div class="promo-card promo-2">
                 <div class="promo-content">
                    <span class="promo-tag" style="color: #00b09b; background: white;">CASHBACK</span>
                    <h3>Rs. 500 Cashback</h3>
                    <p>On bookings over Rs. 2000</p>
                </div>
                 <i class="fas fa-wallet promo-icon"></i>
            </div>

             <!-- Promo Banner 3 -->
            <div class="promo-card promo-3">
                 <div class="promo-content">
                    <span class="promo-tag" style="color: #0072ff; background: white;">APP ONLY</span>
                    <h3>Free Meal</h3>
                    <p>On selected deluxe buses</p>
                </div>
                 <i class="fas fa-utensils promo-icon"></i>
            </div>
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
            // Simulated popular routes with images
            $popular_routes = [
                ['from' => 'Kathmandu', 'to' => 'Pokhara', 'time' => '7 Hours', 'price' => '800', 'image' => 'assets/images/pokhara.png'],
                ['from' => 'Kathmandu', 'to' => 'Chitwan', 'time' => '5 Hours', 'price' => '700', 'image' => 'assets/images/volvobus.webp'],
                ['from' => 'Pokhara', 'to' => 'Kathmandu', 'time' => '7 Hours', 'price' => '800', 'image' => 'assets/images/kathmandu1.webp'],
                ['from' => 'Kathmandu', 'to' => 'Lumbini', 'time' => '9 Hours', 'price' => '1200', 'image' => 'assets/images/bus.png']
            ];

            foreach($popular_routes as $route) {
            ?>
            <a href="search.php?from=<?php echo $route['from']; ?>&to=<?php echo $route['to']; ?>&date=<?php echo date('Y-m-d'); ?>" class="route-card">
                <div class="route-image" style="height: 160px; overflow: hidden; border-radius: 12px 12px 0 0; margin: -25px -25px 15px -25px;">
                    <img src="<?php echo $route['image']; ?>" alt="<?php echo $route['to']; ?>" style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.3s ease;">
                </div>
                <div class="route-header">
                    <span class="route-city"><?php echo $route['from']; ?></span>
                    <i class="fas fa-arrow-right route-arrow"></i>
                    <span class="route-city"><?php echo $route['to']; ?></span>
                </div>
                <div class="route-details">
                    <span><i class="far fa-clock"></i> <?php echo $route['time']; ?></span>
                    <span class="route-price">Rs. <?php echo $route['price']; ?></span>
                </div>
            </a>
            <?php } ?>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
