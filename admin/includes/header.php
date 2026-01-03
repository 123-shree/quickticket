<?php
session_start();
include_once '../includes/db.php';

if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], ['admin', 'agent'])) {
    header("Location: ../login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quick Ticket Admin</title>
    <link rel="icon" type="image/png" href="../assets/images/logo.png">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/admin_style.css">

    <?php
    // Fetch Notification Counts
    $pending_bookings_count = 0;
    $unread_messages_count = 0;
    $offers_count = 0;
    $agents_count = 0;

    if ($_SESSION['role'] == 'admin') {
        $pending_bookings_count = $conn->query("SELECT COUNT(*) as count FROM bookings WHERE payment_status != 'paid'")->fetch_assoc()['count'];
        
        // Ensure is_read column exists to avoid error if schema update failed silently (fallback)
        $check_col = $conn->query("SHOW COLUMNS FROM messages LIKE 'is_read'");
        if ($check_col->num_rows > 0) {
             $unread_messages_count = $conn->query("SELECT COUNT(*) as count FROM messages WHERE is_read = 0")->fetch_assoc()['count'];
        } else {
             $unread_messages_count = $conn->query("SELECT COUNT(*) as count FROM messages")->fetch_assoc()['count'];
        }

        $offers_count = $conn->query("SELECT COUNT(*) as count FROM offers")->fetch_assoc()['count'];
        $agents_count = $conn->query("SELECT COUNT(*) as count FROM users WHERE role='agent'")->fetch_assoc()['count'];
    }
    ?>

</head>
<body>

<div class="admin-container">
    <div class="sidebar">
        <div class="sidebar-brand">
            <img src="../assets/images/logo.png" alt="Logo" style="width: 30px; height: auto; margin-right: 10px; vertical-align: middle;">
            Quick Ticket
        </div>
        <div class="sidebar-menu">
            <?php if ($_SESSION['role'] == 'admin'): ?>
                <a href="index.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : ''; ?>"><i class="fas fa-home"></i> Dashboard</a>
            <?php endif; ?>
            
            <a href="buses.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'buses.php' ? 'active' : ''; ?>"><i class="fas fa-bus"></i> Buses</a>
            <a href="routes.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'routes.php' ? 'active' : ''; ?>"><i class="fas fa-route"></i> Routes</a>
            
            <?php if ($_SESSION['role'] == 'admin'): ?>
                <a href="bookings.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'bookings.php' ? 'active' : ''; ?>">
                    <div style="display: flex; align-items: center; width: 100%;">
                        <i class="fas fa-ticket-alt"></i> <span style="margin-left: 12px;">Bookings</span>
                        <?php if($pending_bookings_count > 0) echo "<span class='badge badge-warning'>$pending_bookings_count</span>"; ?>
                    </div>
                </a>
                <a href="offers.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'offers.php' ? 'active' : ''; ?>">
                    <div style="display: flex; align-items: center; width: 100%;">
                        <i class="fas fa-gift"></i> <span style="margin-left: 12px;">Offers</span>
                        <?php if($offers_count > 0) echo "<span class='badge badge-info'>$offers_count</span>"; ?>
                    </div>
                </a>
                <a href="popular_routes.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'popular_routes.php' ? 'active' : ''; ?>"><i class="fas fa-map-marked-alt"></i> Popular Routes</a>
                <a href="fleet.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'fleet.php' ? 'active' : ''; ?>"><i class="fas fa-bus-alt"></i> Premium Fleet</a>
                <a href="agents.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'agents.php' ? 'active' : ''; ?>">
                     <div style="display: flex; align-items: center; width: 100%;">
                        <i class="fas fa-user-tie"></i> <span style="margin-left: 12px;">Agents</span>
                        <?php if($agents_count > 0) echo "<span class='badge badge-info'>$agents_count</span>"; ?>
                    </div>
                </a>
                <a href="messages.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'messages.php' ? 'active' : ''; ?>">
                     <div style="display: flex; align-items: center; width: 100%;">
                        <i class="fas fa-envelope"></i> <span style="margin-left: 12px;">Messages</span>
                        <?php if($unread_messages_count > 0) echo "<span class='badge'>$unread_messages_count</span>"; ?>
                    </div>
                </a>
            <?php endif; ?>

            <a href="../logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </div>
    </div>
    <div class="main-content">
