<?php
include '../includes/db.php';
include 'includes/header.php';

if ($_SESSION['role'] !== 'admin') {
    header("Location: buses.php");
    exit();
}


// Fetch stats
$bus_count = $conn->query("SELECT COUNT(*) as count FROM buses")->fetch_assoc()['count'];
$route_count = $conn->query("SELECT COUNT(*) as count FROM routes")->fetch_assoc()['count'];
$user_count = $conn->query("SELECT COUNT(*) as count FROM users WHERE role='user'")->fetch_assoc()['count'];
$booking_count = $conn->query("SELECT COUNT(*) as count FROM bookings")->fetch_assoc()['count'];
?>

<h2>Dashboard</h2>

<div class="stats-grid">
    <div class="stat-card" onclick="location.href='buses.php'" style="cursor: pointer;">
        <div class="stat-icon"><i class="fas fa-bus"></i></div>
        <div>
            <h3><?php echo $bus_count; ?></h3>
            <p>Total Buses</p>
        </div>
    </div>
    <div class="stat-card" onclick="location.href='routes.php'" style="cursor: pointer;">
        <div class="stat-icon"><i class="fas fa-route"></i></div>
        <div>
            <h3><?php echo $route_count; ?></h3>
            <p>Total Routes</p>
        </div>
    </div>
    <div class="stat-card" onclick="location.href='users.php'" style="cursor: pointer;">
        <div class="stat-icon"><i class="fas fa-users"></i></div>
        <div>
            <h3><?php echo $user_count; ?></h3>
            <p>Registered Users</p>
        </div>
    </div>
    <div class="stat-card" onclick="location.href='bookings.php'" style="cursor: pointer;">
        <div class="stat-icon"><i class="fas fa-ticket-alt"></i></div>
        <div>
            <h3><?php echo $booking_count; ?></h3>
            <p>Total Bookings</p>
        </div>
    </div>
</div>

<div class="card">
    <h3>Quick Actions</h3>
    <div style="margin-top: 15px;">
        <a href="buses.php" class="btn btn-primary">Add New Bus</a>
        <a href="routes.php" class="btn btn-primary">Add New Route</a>
        <a href="offers.php" class="btn btn-primary">Add New Offer</a>
        <a href="popular_routes.php" class="btn btn-primary">Add Popular Route</a>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
