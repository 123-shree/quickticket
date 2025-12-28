<?php include 'includes/header.php'; ?>

<div class="page-header" style="background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)), url('assets/images/background.jpg'); background-size: cover; background-position: center; color: white; padding: 100px 0; text-align: center;">
    <div class="container">
        <h1 style="font-size: 3rem; margin-bottom: 10px; text-shadow: 2px 2px 4px rgba(0,0,0,0.5);">About QuickTicket</h1>
        <p style="font-size: 1.2rem; opacity: 0.9;">Simplifying Bus Travel Across Nepal</p>
    </div>
</div>

<div class="container section-padding">
    
    <!-- Intro & Mission -->
    <div class="about-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 50px; align-items: center; margin-bottom: 80px;">
        <div>
            <h4 style="color: var(--primary-color); font-weight: 700; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 10px;">Our Story</h4>
            <h2 style="font-size: 2.5rem; color: var(--secondary-color); margin-bottom: 20px; line-height: 1.2;">We Are Changing The Way Nepal Travels</h2>
            <p style="color: #666; font-size: 1.1rem; margin-bottom: 20px; line-height: 1.8;">
                Welcome to <strong>QuickTicket</strong>, Nepal's premier online bus ticketing platform. Founded in 2024, we started with a simple mission: to make travel booking as smooth as the journey itself. 
            </p>
            <p style="color: #666; font-size: 1.1rem; line-height: 1.8;">
                From the bustling streets of Kathmandu to the serene lakes of Pokhara and the plains of Sunsari, we connect you to every corner of the country with safety, reliability, and ease.
            </p>
        </div>
        <div style="position: relative;">
            <div style="background: var(--primary-color); position: absolute; top: -20px; right: -20px; width: 100%; height: 100%; border-radius: 20px; opacity: 0.2; z-index: -1;"></div>
            <img src="assets/images/bus.png" alt="About QuickTicket" style="width: 100%; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); background: white; padding: 10px;">
        </div>
    </div>

    <!-- Why Choose Us -->
    <div style="text-align: center; margin-bottom: 60px;">
        <h2 style="color: var(--secondary-color); margin-bottom: 15px;">Why Choose Us?</h2>
        <p style="color: #666; max-width: 600px; margin: 0 auto;">We don't just sell tickets; we promise a journey. Here is why thousands of travelers trust us.</p>
    </div>

    <div class="features-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 30px; margin-bottom: 80px;">
        <div class="feature-card" style="background: white; padding: 30px; border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.05); text-align: center; transition: 0.3s; border-bottom: 4px solid transparent;">
            <div style="width: 70px; height: 70px; background: #e0f7fa; color: var(--primary-color); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.8rem; margin: 0 auto 20px;">
                <i class="fas fa-shield-alt"></i>
            </div>
            <h3 style="margin-bottom: 15px; color: var(--secondary-color);">Safe & Secure</h3>
            <p style="color: #666;">Verified buses and secure payment gateways (eSewa, Khalti) for your peace of mind.</p>
        </div>

        <div class="feature-card" style="background: white; padding: 30px; border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.05); text-align: center; transition: 0.3s; border-bottom: 4px solid transparent;">
            <div style="width: 70px; height: 70px; background: #fff3e0; color: #ff9800; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.8rem; margin: 0 auto 20px;">
                <i class="fas fa-bolt"></i>
            </div>
            <h3 style="margin-bottom: 15px; color: var(--secondary-color);">Fast Booking</h3>
            <p style="color: #666;">Book your seat in less than 2 minutes. Instant confirmation and digital tickets.</p>
        </div>

        <div class="feature-card" style="background: white; padding: 30px; border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.05); text-align: center; transition: 0.3s; border-bottom: 4px solid transparent;">
            <div style="width: 70px; height: 70px; background: #e8eaf6; color: #3f51b5; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.8rem; margin: 0 auto 20px;">
                <i class="fas fa-headset"></i>
            </div>
            <h3 style="margin-bottom: 15px; color: var(--secondary-color);">24/7 Support</h3>
            <p style="color: #666;">Our dedicated support team is always ready to assist you with your travel needs.</p>
        </div>
    </div>

    <!-- Bus Facilities and Types -->
    <div style="margin-bottom: 80px;">
        <div style="text-align: center; margin-bottom: 50px;">
            <h4 style="color: var(--primary-color); font-weight: 700; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 10px;">Our Fleet</h4>
            <h2 style="color: var(--secondary-color); margin-bottom: 15px;">Bus Types & Facilities</h2>
            <p style="color: #666; max-width: 700px; margin: 0 auto;">
                Several types of buses operate on the Pokhara-Kathmandu route, catering to tourists and providing enhanced comfort.
            </p>
        </div>

        <div class="bus-types-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 40px;">
            <!-- VIP/Sofa Bus -->
            <div class="bus-card" style="background: white; border-radius: 20px; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.08); transition: 0.3s;">
                <div style="height: 250px; overflow: hidden;">
                    <img src="assets/images/vip_bus.png" alt="VIP Sofa Bus" style="width: 100%; height: 100%; object-fit: cover; transition: 0.5s;">
                </div>
                <div style="padding: 30px;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                        <h3 style="color: var(--secondary-color); margin: 0;">VIP / Sofa Bus</h3>
                        <span style="background: #e3f2fd; color: var(--primary-color); padding: 5px 12px; border-radius: 20px; font-size: 0.9rem; font-weight: 600;">Luxury</span>
                    </div>
                    <p style="color: #666; margin-bottom: 20px; line-height: 1.6;">
                        The most comfortable option with 2x1 seating, wide electronic reclining seats, air suspension, and complimentary amenities.
                    </p>
                    <ul style="list-style: none; padding: 0; margin-bottom: 25px;">
                        <li style="margin-bottom: 8px; color: #555;"><i class="fas fa-check-circle" style="color: var(--primary-color); margin-right: 10px;"></i>2x1 Sofa Seating</li>
                        <li style="margin-bottom: 8px; color: #555;"><i class="fas fa-check-circle" style="color: var(--primary-color); margin-right: 10px;"></i>Air Suspension</li>
                        <li style="margin-bottom: 8px; color: #555;"><i class="fas fa-check-circle" style="color: var(--primary-color); margin-right: 10px;"></i>Snacks & Water</li>
                    </ul>
                    <div style="border-top: 1px solid #eee; padding-top: 20px; display: flex; justify-content: space-between; align-items: center;">
                        <div>
                            <span style="display: block; font-size: 0.85rem; color: #999;">Starting from</span>
                            <span style="color: var(--secondary-color); font-weight: 700; font-size: 1.2rem;">NPR 1,600</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Deluxe Bus -->
            <div class="bus-card" style="background: white; border-radius: 20px; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.08); transition: 0.3s;">
                <div style="height: 250px; overflow: hidden;">
                    <img src="assets/images/deluxe_bus.png" alt="Deluxe Bus" style="width: 100%; height: 100%; object-fit: cover; transition: 0.5s;">
                </div>
                <div style="padding: 30px;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                        <h3 style="color: var(--secondary-color); margin: 0;">Deluxe Bus</h3>
                        <span style="background: #fff3e0; color: #ff9800; padding: 5px 12px; border-radius: 20px; font-size: 0.9rem; font-weight: 600;">Popular</span>
                    </div>
                    <p style="color: #666; margin-bottom: 20px; line-height: 1.6;">
                        Features 2x2 mini-sofa seating with AC and comfortable amenities. A perfect balance of comfort and affordability.
                    </p>
                    <ul style="list-style: none; padding: 0; margin-bottom: 25px;">
                        <li style="margin-bottom: 8px; color: #555;"><i class="fas fa-check-circle" style="color: var(--primary-color); margin-right: 10px;"></i>2x2 Mini Sofa Seats</li>
                        <li style="margin-bottom: 8px; color: #555;"><i class="fas fa-check-circle" style="color: var(--primary-color); margin-right: 10px;"></i>Air Conditioning</li>
                        <li style="margin-bottom: 8px; color: #555;"><i class="fas fa-check-circle" style="color: var(--primary-color); margin-right: 10px;"></i>Bottled Water</li>
                    </ul>
                    <div style="border-top: 1px solid #eee; padding-top: 20px; display: flex; justify-content: space-between; align-items: center;">
                        <div>
                            <span style="display: block; font-size: 0.85rem; color: #999;">Price Range</span>
                            <span style="color: var(--secondary-color); font-weight: 700; font-size: 1.2rem;">NPR 1,200 - 1,500</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Standard Bus -->
            <div class="bus-card" style="background: white; border-radius: 20px; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.08); transition: 0.3s;">
                <div style="height: 250px; overflow: hidden;">
                    <img src="assets/images/bus.png" alt="Standard Bus" style="width: 100%; height: 100%; object-fit: cover; transition: 0.5s;">
                </div>
                <div style="padding: 30px;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                        <h3 style="color: var(--secondary-color); margin: 0;">Standard Bus</h3>
                        <span style="background: #e8eaf6; color: #3f51b5; padding: 5px 12px; border-radius: 20px; font-size: 0.9rem; font-weight: 600;">Budget</span>
                    </div>
                    <p style="color: #666; margin-bottom: 20px; line-height: 1.6;">
                        A budget-conscious option with fewer amenities but high frequency. Ideal for quick and affordable travel.
                    </p>
                    <ul style="list-style: none; padding: 0; margin-bottom: 25px;">
                        <li style="margin-bottom: 8px; color: #555;"><i class="fas fa-check-circle" style="color: var(--primary-color); margin-right: 10px;"></i>2x2 Standard Seats</li>
                        <li style="margin-bottom: 8px; color: #555;"><i class="fas fa-check-circle" style="color: var(--primary-color); margin-right: 10px;"></i>Regular Stops</li>
                        <li style="margin-bottom: 8px; color: #555;"><i class="fas fa-check-circle" style="color: var(--primary-color); margin-right: 10px;"></i>Most Frequent</li>
                    </ul>
                    <div style="border-top: 1px solid #eee; padding-top: 20px; display: flex; justify-content: space-between; align-items: center;">
                        <div>
                            <span style="display: block; font-size: 0.85rem; color: #999;">Economical</span>
                            <span style="color: var(--secondary-color); font-weight: 700; font-size: 1.2rem;">Best Value</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats -->
    <div class="stats-container" style="background: var(--secondary-color); padding: 50px; border-radius: 20px; color: white; display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 40px; text-align: center;">
        <div>
            <h2 style="font-size: 3rem; font-weight: 800; color: var(--primary-color); margin-bottom: 5px;">500+</h2>
            <p style="opacity: 0.8; font-size: 1.1rem;">Luxury Buses</p>
        </div>
        <div>
            <h2 style="font-size: 3rem; font-weight: 800; color: var(--primary-color); margin-bottom: 5px;">50+</h2>
            <p style="opacity: 0.8; font-size: 1.1rem;">Major Routes</p>
        </div>
        <div>
            <h2 style="font-size: 3rem; font-weight: 800; color: var(--primary-color); margin-bottom: 5px;">10k+</h2>
            <p style="opacity: 0.8; font-size: 1.1rem;">Happy Travelers</p>
        </div>
        <div>
            <h2 style="font-size: 3rem; font-weight: 800; color: var(--primary-color); margin-bottom: 5px;">4.8</h2>
            <p style="opacity: 0.8; font-size: 1.1rem;">User Rating</p>
        </div>
    </div>

</div>

<style>
    .feature-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 30px rgba(0,0,0,0.1) !important;
        border-bottom: 4px solid var(--primary-color) !important;
    }
</style>

<?php include 'includes/footer.php'; ?>
