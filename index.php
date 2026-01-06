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

<!-- AOS Animation CSS -->
<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

<!-- Offers Section -->
<div class="section-padding" style="background-color: var(--light-bg);">
    <div class="container" data-aos="fade-up">
        <div class="section-title">
            <h2>Exclusive <span class="highlight">Offers</span></h2>
            <p class="section-slogan">Unbeatable deals for your next adventure. Grab them now!</p>
        </div>
        <!-- Offers Swiper -->
        <div class="swiper offersSwiper">
            <div class="swiper-wrapper">
                <?php
                $sql = "SELECT * FROM offers ORDER BY created_at DESC LIMIT 5";
                $result = $conn->query($sql);
                $i = 0;
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        $i++;
                        $promo_class = "promo-" . (($i - 1) % 3 + 1);
                ?>
                <div class="swiper-slide">
                    <!-- Promo Card -->
                    <div class="promo-card <?php echo $promo_class; ?>">
                        <div class="promo-content">
                            <span class="promo-tag" style="color: <?php echo $row['promo_color']; ?>; background: white;"><?php echo $row['promo_tag']; ?></span>
                            <h3><?php echo $row['title']; ?></h3>
                            <p><?php echo $row['description']; ?></p>
                        </div>
                        <i class="<?php echo $row['icon']; ?> promo-icon"></i>
                    </div>
                </div>
                <?php 
                    }
                } else {
                    echo "<p>No offers content available at the moment.</p>";
                }
                ?>
            </div>
            <div class="swiper-pagination"></div>
            <!-- Navigation Arrows -->
            <div class="swiper-button-next swiper-button-next-offers"></div>
            <div class="swiper-button-prev swiper-button-prev-offers"></div>
        </div>
    </div>
</div>

<!-- Popular Routes -->
<div class="section-padding bg-white">
    <div class="container" data-aos="fade-up">
        <div class="section-title">
            <h2>Popular Routes</h2>
        </div>
        
        <!-- Swiper -->
        <div class="swiper mySwiper">
            <div class="swiper-wrapper">
                <?php
                $sql = "SELECT * FROM popular_routes ORDER BY created_at DESC LIMIT 10";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        $image = $row['image_path'];
                        if (strpos($image, 'assets/') !== 0 && strpos($image, 'http') !== 0) {
                             $image = 'assets/images/' . $image;
                        }
                ?>
                <div class="swiper-slide">
                    <a href="search.php?from=<?php echo urlencode($row['source']); ?>&to=<?php echo urlencode($row['destination']); ?>&date=<?php echo date('Y-m-d'); ?>" class="route-card" style="display: block; text-decoration: none; height: 100%;">
                        <div class="route-image" style="height: 200px; overflow: hidden; border-radius: 12px 12px 0 0; margin: -25px -25px 15px -25px; position: relative;">
                            <img src="<?php echo $image; ?>" alt="<?php echo $row['destination']; ?>" style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.5s ease; display: block;">
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
                </div>
                <?php 
                    }
                } else {
                     echo "<p class='text-center' style='width:100%;'>No popular routes added yet. Check Admin Panel.</p>";
                } 
                ?>
            </div>
            <div class="swiper-pagination"></div>
            <!-- Navigation arrows -->
            <div class="swiper-button-next swiper-button-next-routes"></div>
            <div class="swiper-button-prev swiper-button-prev-routes"></div>
        </div>
    </div>
</div>

<!-- Swiper CSS & JS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<!-- AOS JS -->
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

<style>
    .swiper {
        width: 100%;
        padding-bottom: 50px; 
    }
    .swiper-slide {
        background-position: center;
        background-size: cover;
        height: auto;
    }
    .swiper-pagination-bullet-active {
        background: var(--primary-color, #4CAF50);
    }
    .swiper-button-next, .swiper-button-prev {
        color: var(--primary-color, #4CAF50);
    }
</style>

<script>
    // Initialize AOS
    AOS.init({
        duration: 800,
        easing: 'ease-in-out',
        once: true
    });

    // 1. Popular Routes Slider
    var swiperRoutes = new Swiper(".mySwiper", {
        slidesPerView: 1,
        spaceBetween: 30,
        grabCursor: true,
        speed: 800, // Smooth transition
        autoplay: {
            delay: 1000,
            disableOnInteraction: false,
        },
        pagination: {
            el: ".swiper-pagination",
            clickable: true,
        },
        navigation: {
            nextEl: ".swiper-button-next-routes",
            prevEl: ".swiper-button-prev-routes",
        },
        breakpoints: {
            640: { slidesPerView: 2, spaceBetween: 20 },
            768: { slidesPerView: 2, spaceBetween: 30 },
            1024: { slidesPerView: 3, spaceBetween: 30 },
            1200: { slidesPerView: 4, spaceBetween: 30 },
        },
    });

    // 2. Offers Slider
    var swiperOffers = new Swiper(".offersSwiper", {
        slidesPerView: 1,
        spaceBetween: 30,
        grabCursor: true,
        speed: 800,
        autoplay: {
            delay: 1000,
            disableOnInteraction: false,
        },
        pagination: {
            el: ".swiper-pagination",
            clickable: true,
        },
        navigation: {
            nextEl: ".swiper-button-next-offers",
            prevEl: ".swiper-button-prev-offers",
        },
        breakpoints: {
            640: { slidesPerView: 1, spaceBetween: 20 },
            768: { slidesPerView: 2, spaceBetween: 30 },
            1024: { slidesPerView: 3, spaceBetween: 30 },
        },
    });

    // 3. Fleet Slider
    var swiperFleet = new Swiper(".fleetSwiper", {
        slidesPerView: 1,
        spaceBetween: 30,
        grabCursor: true,
        speed: 800,
        autoplay: {
            delay: 1000,
            disableOnInteraction: false,
        },
         navigation: {
            nextEl: ".swiper-button-next-fleet",
            prevEl: ".swiper-button-prev-fleet",
        },
        breakpoints: {
            640: { slidesPerView: 1, spaceBetween: 20 },
            768: { slidesPerView: 2, spaceBetween: 30 },
            1024: { slidesPerView: 3, spaceBetween: 30 },
        },
    });
</script>

<!-- Amenities Section -->
<div class="section-padding" style="background: #f8f9fa;">
    <div class="container">
        <div class="section-title" data-aos="fade-up">
            <h2>Experience the Best</h2>
            <p>We provide top-notch facilities to make your journey memorable.</p>
        </div>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 30px; margin-bottom: 60px;">
            <!-- Amenity Card 1 -->
            <div data-aos="fade-up" data-aos-delay="100" style="background: white; padding: 30px; border-radius: 15px; text-align: center; box-shadow: 0 5px 15px rgba(0,0,0,0.05); transition: 0.3s; border-bottom: 4px solid #4CAF50;">
                <div style="width: 60px; height: 60px; background: #e8f5e9; color: #4CAF50; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; margin: 0 auto 20px;">
                    <i class="fas fa-wifi"></i>
                </div>
                <h3 style="margin-bottom: 10px; color: var(--secondary-color);">Free Wi-Fi</h3>
                <p style="color: #666;">Stay connected with high-speed internet throughout your journey.</p>
            </div>

            <!-- Amenity Card 2 -->
            <div data-aos="fade-up" data-aos-delay="200" style="background: white; padding: 30px; border-radius: 15px; text-align: center; box-shadow: 0 5px 15px rgba(0,0,0,0.05); transition: 0.3s; border-bottom: 4px solid #FF9800;">
                <div style="width: 60px; height: 60px; background: #fff3e0; color: #FF9800; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; margin: 0 auto 20px;">
                    <i class="fas fa-bolt"></i>
                </div>
                <h3 style="margin-bottom: 10px; color: var(--secondary-color);">Charging Hubs</h3>
                <p style="color: #666;">Personal USB charging ports at every seat for your devices.</p>
            </div>

            <!-- Amenity Card 3 -->
            <div data-aos="fade-up" data-aos-delay="300" style="background: white; padding: 30px; border-radius: 15px; text-align: center; box-shadow: 0 5px 15px rgba(0,0,0,0.05); transition: 0.3s; border-bottom: 4px solid #F44336;">
                <div style="width: 60px; height: 60px; background: #ffebee; color: #F44336; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; margin: 0 auto 20px;">
                    <i class="fas fa-utensils"></i>
                </div>
                <h3 style="margin-bottom: 10px; color: var(--secondary-color);">Onboard Snacks</h3>
                <p style="color: #666;">Complimentary water and light snacks on VIP and Deluxe trips.</p>
            </div>

            <!-- Amenity Card 4 -->
            <div data-aos="fade-up" data-aos-delay="400" style="background: white; padding: 30px; border-radius: 15px; text-align: center; box-shadow: 0 5px 15px rgba(0,0,0,0.05); transition: 0.3s; border-bottom: 4px solid #2196F3;">
                <div style="width: 60px; height: 60px; background: #e3f2fd; color: #2196F3; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; margin: 0 auto 20px;">
                    <i class="fas fa-couch"></i>
                </div>
                <h3 style="margin-bottom: 10px; color: var(--secondary-color);">Premium Seats</h3>
                <p style="color: #666;">Extra legroom and sofa-style reclining seats for maximum comfort.</p>
            </div>
        </div>

        </div>

    </div>
</div>

<!-- Premium Fleet Section -->
<div class="section-padding fleet-section">
    <div class="container" data-aos="fade-up">
        <div class="section-title">
            <h2>Our Premium <span class="highlight">Fleet</span></h2>
            <p>Travel in style and comfort with our modern buses.</p>
        </div>
        
        <div class="swiper fleetSwiper">
            <div class="swiper-wrapper">
            <?php
            $fleet_sql = "SELECT * FROM fleet ORDER BY created_at ASC";
            $fleet_result = $conn->query($fleet_sql);
            
            if ($fleet_result->num_rows > 0) {
                while($item = $fleet_result->fetch_assoc()) {
                    $f_image = $item['image_path'];
                    if (strpos($f_image, 'assets/') !== 0 && strpos($f_image, 'http') !== 0) {
                         $f_image = 'assets/images/' . $f_image;
                    }
            ?>
                <div class="swiper-slide">
                    <div class="bus-card">
                        <div class="bus-card-image">
                            <img src="<?php echo $f_image; ?>" alt="<?php echo $item['title']; ?>">
                            <div class="bus-overlay">
                                <span class="bus-badge">Premium</span>
                            </div>
                        </div>
                        <div class="bus-card-content">
                            <h3><?php echo $item['title']; ?></h3>
                            <p><?php echo $item['description']; ?></p>
                            <a href="search.php?date=<?php echo date('Y-m-d'); ?>" class="btn-link">Book Now <i class="fas fa-arrow-right"></i></a>
                        </div>
                    </div>
                </div>
            <?php 
                }
            } else {
                echo "<p>No fleet information available.</p>";
            }
            ?>
            </div>
            <!-- Fleet Pagination & Arrows -->
            <div class="swiper-pagination"></div>
            <div class="swiper-button-next swiper-button-next-fleet"></div>
            <div class="swiper-button-prev swiper-button-prev-fleet"></div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
