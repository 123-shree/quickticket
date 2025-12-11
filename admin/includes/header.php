<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Quick Ticket</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        .admin-container {
            display: flex;
            min-height: 100vh;
            background-image: url('../assets/img/admin_bg.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }
        .sidebar {
            width: 250px;
            background: rgba(44, 62, 80, 0.9);
            color: #fff;
            padding: 20px 0;
            flex-shrink: 0;
            backdrop-filter: blur(5px);
        }
        .sidebar-brand {
            padding: 0 20px 20px;
            font-size: 1.5rem;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        .sidebar-menu {
            margin-top: 20px;
        }
        .sidebar-menu a {
            display: block;
            padding: 15px 20px;
            color: #ecf0f1;
            transition: all 0.3s;
        }
        .sidebar-menu a:hover, .sidebar-menu a.active {
            background: #2ecc71;
        }
        .main-content {
            flex: 1;
            padding: 20px;
            background: rgba(255, 255, 255, 0.85); /* Semi-transparent white */
            backdrop-filter: blur(5px); /* Nice blur effect */
        }
        .card {
            background: rgba(255, 255, 255, 0.95);
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
        }
        .stat-card {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            display: flex;
            align-items: center;
            gap: 15px;
        }
        .stat-icon {
            width: 50px;
            height: 50px;
            background: rgba(46, 204, 113, 0.1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #2ecc71;
            font-size: 1.5rem;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            text-align: left;
            padding: 12px;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f8f9fa;
        }
    </style>
</head>
<body>

<div class="admin-container">
    <div class="sidebar">
        <div class="sidebar-brand">
            Admin Panel
        </div>
        <div class="sidebar-menu">
            <a href="index.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : ''; ?>"><i class="fas fa-home"></i> Dashboard</a>
            <a href="buses.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'buses.php' ? 'active' : ''; ?>"><i class="fas fa-bus"></i> Buses</a>
            <a href="routes.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'routes.php' ? 'active' : ''; ?>"><i class="fas fa-route"></i> Routes</a>
            <a href="bookings.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'bookings.php' ? 'active' : ''; ?>"><i class="fas fa-ticket-alt"></i> Bookings</a>
            <a href="../logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </div>
    </div>
    <div class="main-content">
