<?php 
include 'includes/db.php';
include 'includes/header.php'; 

$msg = "";
$msg_class = "";

if (isset($_POST['submit_contact'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $message = mysqli_real_escape_string($conn, $_POST['message']);

    if (!empty($name) && !empty($email) && !empty($phone) && !empty($message)) {
        $sql = "INSERT INTO messages (name, email, phone, message) VALUES ('$name', '$email', '$phone', '$message')";
        if (mysqli_query($conn, $sql)) {
            $msg = "Message sent successfully! We will contact you soon.";
            $msg_class = "alert-success";
        } else {
            $msg = "Error: " . mysqli_error($conn);
            $msg_class = "alert-danger";
        }
    } else {
        $msg = "Please fill in all fields.";
        $msg_class = "alert-danger";
    }
}
?>

<div class="page-header" style="background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)), url('assets/images/background.jpg'); background-size: cover; background-position: center; color: white;">
    <div class="container">
        <h1>Contact Us</h1>
        <p>We'd love to hear from you!</p>
    </div>
</div>

<div class="container section-padding">
    <div class="contact-wrapper" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 40px;">
        
        <!-- Contact Info & Map -->
        <div class="contact-info-container">
            <div class="contact-card" style="background: white; padding: 30px; border-radius: 12px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); margin-bottom: 30px;">
                <h3 style="margin-bottom: 20px; color: var(--secondary-color); border-bottom: 2px solid var(--primary-color); display: inline-block; padding-bottom: 5px;">Get in Touch</h3>
                
                <div class="info-item" style="display: flex; gap: 20px; margin-bottom: 20px;">
                    <div class="icon" style="width: 50px; height: 50px; background: #e0f7fa; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: var(--primary-color); font-size: 1.2rem;">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <div>
                        <h4 style="margin-bottom: 5px; color: var(--secondary-color);">Our Location</h4>
                        <p style="color: #666;">Inaruwa Sunsari, Nepal</p>
                    </div>
                </div>

                <div class="info-item" style="display: flex; gap: 20px; margin-bottom: 20px;">
                    <div class="icon" style="width: 50px; height: 50px; background: #e0f7fa; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: var(--primary-color); font-size: 1.2rem;">
                        <i class="fas fa-phone"></i>
                    </div>
                    <div>
                        <h4 style="margin-bottom: 5px; color: var(--secondary-color);">Phone Number</h4>
                        <p style="color: #666;">+977-9867993001</p>
                    </div>
                </div>

                <div class="info-item" style="display: flex; gap: 20px;">
                    <div class="icon" style="width: 50px; height: 50px; background: #e0f7fa; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: var(--primary-color); font-size: 1.2rem;">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <div>
                        <h4 style="margin-bottom: 5px; color: var(--secondary-color);">Email Address</h4>
                        <p style="color: #666;">info@quickticket.com</p>
                    </div>
                </div>
            </div>

            <!-- Map Placeholder -->
            <!-- Map Placeholder with Overlay -->
            <div class="map-container" style="border-radius: 12px; overflow: hidden; box-shadow: 0 5px 15px rgba(0,0,0,0.1); height: 250px; position: relative;">
                <a href="https://www.google.com/maps/place/Inaruwa,+Nepal" target="_blank" style="display: block; width: 100%; height: 100%; text-decoration: none;">
                    <img src="assets/images/inaruwa_offline_map.png" alt="Offline Map: Inaruwa, Sunsari (Downloadable)" style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.5s ease;" class="map-image" loading="lazy">
                    <div class="map-overlay" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.1); display: flex; align-items: center; justify-content: center; opacity: 0; transition: opacity 0.3s ease;">
                        <span style="background: white; padding: 10px 20px; border-radius: 30px; color: var(--primary-color); font-weight: bold; box-shadow: 0 5px 15px rgba(0,0,0,0.2); transform: translateY(10px); transition: transform 0.3s ease;">
                            <i class="fas fa-external-link-alt"></i> View on Google Maps
                        </span>
                    </div>
                </a>
            </div>
            <style>
                .map-container:hover .map-overlay { opacity: 1; }
                .map-container:hover .map-overlay span { transform: translateY(0); }
                .map-container:hover .map-image { transform: scale(1.05); }
                .map-container { background: #eee; animation: skeleton-loading 1.5s infinite linear; }
                @keyframes skeleton-loading {
                    0% { background-color: #eee; }
                    50% { background-color: #f5f5f5; }
                    100% { background-color: #eee; }
                }
            </style>
        </div>
        
        <!-- Contact Form -->
        <div class="contact-form-container" style="background: white; padding: 40px; border-radius: 12px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); height: fit-content;">
            <h3 style="margin-bottom: 10px; color: var(--secondary-color);">Send us a Message</h3>
            <p style="margin-bottom: 30px; color: #666;">We will get back to you within 24 hours.</p>

            <?php if($msg != ""): ?>
                <div class="alert <?php echo $msg_class; ?>" style="padding: 10px; border-radius: 5px; margin-bottom: 20px; <?php echo $msg_class == 'alert-success' ? 'background-color: #d4edda; color: #155724;' : 'background-color: #f8d7da; color: #721c24;'; ?>">
                    <?php echo $msg; ?>
                </div>
            <?php endif; ?>

            <form class="contact-form" method="POST" action="">
                <div class="form-group" style="margin-bottom: 20px;">
                    <label for="name" style="display: block; margin-bottom: 8px; font-weight: 600; color: var(--secondary-color);">Full Name</label>
                    <input type="text" id="name" name="name" required style="width: 100%; padding: 12px; border: 2px solid #eee; border-radius: 8px; transition: 0.3s; outline: none;">
                </div>
                <div class="form-group" style="margin-bottom: 20px;">
                    <label for="email" style="display: block; margin-bottom: 8px; font-weight: 600; color: var(--secondary-color);">Email Address</label>
                    <input type="email" id="email" name="email" required style="width: 100%; padding: 12px; border: 2px solid #eee; border-radius: 8px; transition: 0.3s; outline: none;">
                </div>
                <div class="form-group" style="margin-bottom: 20px;">
                    <label for="phone" style="display: block; margin-bottom: 8px; font-weight: 600; color: var(--secondary-color);">Phone Number (WhatsApp)</label>
                    <input type="tel" id="phone" name="phone" required style="width: 100%; padding: 12px; border: 2px solid #eee; border-radius: 8px; transition: 0.3s; outline: none;">
                </div>
                

                <div class="form-group" style="margin-bottom: 25px;">
                    <label for="message" style="display: block; margin-bottom: 8px; font-weight: 600; color: var(--secondary-color);">Your Message</label>
                    <textarea id="message" name="message" rows="5" required style="width: 100%; padding: 12px; border: 2px solid #eee; border-radius: 8px; transition: 0.3s; outline: none; font-family: inherit;"></textarea>
                </div>
                <button type="submit" name="submit_contact" class="btn btn-primary" style="width: 100%; padding: 15px;">Send Message <i class="fas fa-paper-plane" style="margin-left: 5px;"></i></button>
            </form>
        </div>
    </div>
</div>

<style>
    .form-group input:focus, .form-group textarea:focus {
        border-color: var(--primary-color) !important;
        box-shadow: 0 0 0 4px rgba(106, 236, 225, 0.2);
    }
</style>

<?php include 'includes/footer.php'; ?>
