<?php
include 'includes/db.php';

echo "<h2>Seeding Daily Buses</h2>";

// 1. Handle Ujyalo Bus (Day 6am)
$bus_name = "Ujyalo Daily";
$bus_number = "BA 4 KHA 5566"; // Placeholder
$bus_type = "Standard";
$total_seats = 40;

// Check if exists
$check = $conn->query("SELECT id FROM buses WHERE bus_name = '$bus_name'");
if ($check->num_rows > 0) {
    $row = $check->fetch_assoc();
    $ujyalo_id = $row['id'];
    echo "Bus '$bus_name' already exists (ID: $ujyalo_id)<br>";
} else {
    $sql = "INSERT INTO buses (bus_name, bus_number, bus_type, total_seats) VALUES ('$bus_name', '$bus_number', '$bus_type', '$total_seats')";
    if ($conn->query($sql) === TRUE) {
        $ujyalo_id = $conn->insert_id;
        echo "Created bus '$bus_name' (ID: $ujyalo_id)<br>";
    } else {
        die("Error creating Ujyalo bus: " . $conn->error);
    }
}

// 2. Handle Premiere Bus (Night 6pm)
$bus_name_2 = "Premiere Deluxe";
$bus_number_2 = "BA 5 KHA 9988"; // Placeholder
$bus_type_2 = "Deluxe";
$total_seats_2 = 30; // Deluxe usually less

$check = $conn->query("SELECT id FROM buses WHERE bus_name = '$bus_name_2'");
if ($check->num_rows > 0) {
    $row = $check->fetch_assoc();
    $premiere_id = $row['id'];
    echo "Bus '$bus_name_2' already exists (ID: $premiere_id)<br>";
} else {
    $sql = "INSERT INTO buses (bus_name, bus_number, bus_type, total_seats) VALUES ('$bus_name_2', '$bus_number_2', '$bus_type_2', '$total_seats_2')";
    if ($conn->query($sql) === TRUE) {
        $premiere_id = $conn->insert_id;
        echo "Created bus '$bus_name_2' (ID: $premiere_id)<br>";
    } else {
        die("Error creating Premiere bus: " . $conn->error);
    }
}

// 3. Generate Routes for Next 30 Days
$start_date = strtotime('today');
$end_date = strtotime('+30 days');
$source = "Dharan";
$destination = "Kathmandu";

$ujyalo_time = "06:00:00";
$ujyalo_price = 1200;

$premiere_time = "18:00:00"; // 6pm
$premiere_price = 1600;

$count = 0;
while ($start_date <= $end_date) {
    $date_str = date('Y-m-d', $start_date);

    // Insert Ujyalo Route if not exists
    $check_route = $conn->query("SELECT id FROM routes WHERE bus_id = $ujyalo_id AND departure_date = '$date_str' AND departure_time = '$ujyalo_time'");
    if ($check_route->num_rows == 0) {
        $sql = "INSERT INTO routes (bus_id, source, destination, departure_date, departure_time, price) VALUES ('$ujyalo_id', '$source', '$destination', '$date_str', '$ujyalo_time', '$ujyalo_price')";
        $conn->query($sql);
        $count++;
    }

    // Insert Premiere Route if not exists
    $check_route = $conn->query("SELECT id FROM routes WHERE bus_id = $premiere_id AND departure_date = '$date_str' AND departure_time = '$premiere_time'");
    if ($check_route->num_rows == 0) {
        $sql = "INSERT INTO routes (bus_id, source, destination, departure_date, departure_time, price) VALUES ('$premiere_id', '$source', '$destination', '$date_str', '$premiere_time', '$premiere_price')";
        $conn->query($sql);
        $count++;
    }

    $start_date = strtotime('+1 day', $start_date);
}

echo "Seeding complete. Added $count new daily routes.<br>";
echo "<a href='index.php'>Go Home</a>";
?>
