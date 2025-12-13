<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quick Ticket - Book Bus Tickets Easily</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
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
<body>

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
