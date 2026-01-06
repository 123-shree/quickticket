<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quick Ticket - Book Bus Tickets Easily</title>
    <link rel="icon" type="image/png" href="assets/images/logo.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css?v=1.1">
    <style>
        /* Force background image via inline style to bypass cache/path issues */
        .hero {
            background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.7)), url('assets/images/background.jpg') !important;
            background-size: cover !important;
            background-position: center center !important;
            background-repeat: no-repeat !important;
            background-attachment: fixed !important;
        }
    </style>
</head>
</head>
<body>

<?php if(isset($_SESSION['flash_msg'])): ?>
    <div id="flash-toast" style="visibility: hidden; min-width: 250px; background-color: var(--primary-color, #333); color: #fff; text-align: center; border-radius: 4px; padding: 16px; position: fixed; z-index: 9999; right: 30px; bottom: 30px; font-size: 17px; box-shadow: 0 4px 12px rgba(0,0,0,0.15); transform: translateY(100px); opacity: 0; transition: all 0.5s ease;">
        <?php 
        echo $_SESSION['flash_msg']; 
        unset($_SESSION['flash_msg']);
        ?>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var x = document.getElementById("flash-toast");
            x.style.visibility = "visible";
            x.style.transform = "translateY(0)";
            x.style.opacity = "1";
            setTimeout(function(){ 
                x.style.opacity = "0";
                x.style.transform = "translateY(100px)";
                setTimeout(function(){ x.style.visibility = "hidden"; }, 500);
            }, 3000);
        });
    </script>
<?php endif; ?>

<nav class="navbar">
    <div class="container navbar-content">
        <a href="index.php" class="brand-logo">
            <img src="assets/images/logo.png" alt="Quick Ticket Logo" style="height: 70px; width: auto;">
            Quick<span class="highlight">Ticket</span>
        </a>
        
        <div class="nav-links">
            <a href="index.php">Home</a>
            <a href="about.php">About</a>
            <a href="contact.php">Contact Us</a>
            <?php if(isset($_SESSION['user_id'])): ?>
                <a href="my_tickets.php">My Tickets</a>
                <?php
                // Fetch profile image for nav
                $nav_profile_img = '';
                if(isset($conn) && isset($_SESSION['user_id'])) {
                    $nav_uid = $_SESSION['user_id'];
                    $nav_res = $conn->query("SELECT profile_image FROM users WHERE id='$nav_uid'");
                    if($nav_res && $nav_row = $nav_res->fetch_assoc()) {
                        $nav_profile_img = $nav_row['profile_image'];
                    }
                }
                ?>
                <a href="profile.php" style="display: flex; align-items: center; gap: 5px;">
                    <?php if(!empty($nav_profile_img)): ?>
                        <img src="<?php echo $nav_profile_img; ?>" style="width: 25px; height: 25px; border-radius: 50%; object-fit: cover; border: 1px solid white;">
                    <?php else: ?>
                        <i class="fas fa-user-circle"></i>
                    <?php endif; ?>
                    Profile
                </a>
                <a href="logout.php" class="btn btn-outline">Logout</a>
            <?php else: ?>
                <a href="login.php" class="btn btn-outline">Login</a>
                <a href="signup.php" class="btn btn-primary">Sign Up</a>
            <?php endif; ?>
        </div>
        
        <button class="mobile-menu-btn">
            <i class="fas fa-bars"></i>
        </button>
    </div>
</nav>
