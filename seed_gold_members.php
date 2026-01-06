<?php
include 'includes/db.php';

function seedUser($name, $email, $password, $phone, $blood) {
    global $conn;
    
    // Check if user exists
    $check = $conn->query("SELECT id FROM users WHERE email = '$email'");
    if ($check->num_rows > 0) {
        $row = $check->fetch_assoc();
        $user_id = $row['id'];
        echo "User $name already exists (ID: $user_id). Updating details...<br>";
        
        $sql = "UPDATE users SET 
                name = '$name',
                phone_number = '$phone', 
                blood_group = '$blood',
                profile_image = 'assets/images/bus-1.jpg',
                citizenship_front = 'assets/images/pay_esewa.png', 
                citizenship_back = 'assets/images/pay_khalti.png'
                WHERE id = $user_id";
    } else {
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO users (name, email, password, phone_number, blood_group, profile_image, citizenship_front, citizenship_back) 
                VALUES ('$name', '$email', '$hashed', '$phone', '$blood', 'assets/images/bus-1.jpg', 'assets/images/pay_esewa.png', 'assets/images/pay_khalti.png')";
        if ($conn->query($sql) === TRUE) {
            $user_id = $conn->insert_id;
            echo "Created new user $name (ID: $user_id).<br>";
        } else {
            echo "Error creating user $name: " . $conn->error . "<br>";
            return null;
        }
    }
    
    // Ensure 3 confimed bookings
    $count_sql = "SELECT COUNT(*) as cnt FROM bookings WHERE user_id = $user_id AND status = 'confirmed'";
    $count = $conn->query($count_sql)->fetch_assoc()['cnt'];
    
    if ($count < 3) {
        $needed = 3 - $count;
        echo "Adding $needed confirmed bookings for $name...<br>";
        for ($i = 0; $i < $needed; $i++) {
            $date = date('Y-m-d H:i:s', strtotime("-$i days"));
            // Mock booking
            $ins = "INSERT INTO bookings (user_id, route_id, seat_number, payment_status, status, payment_method, transaction_id, paid_amount, passenger_name, contact_number, pickup_location, email, booking_date)
                    VALUES ($user_id, 1, 'A$i', 'paid', 'confirmed', 'esewa', 'SEED_TRANS_$i', 1500, '$name', '$phone', 'Kathmandu', '$email', '$date')";
            $conn->query($ins);
        }
    }
    
    echo "$name is now a verified Gold Member!<br><hr>";
    return $user_id;
}

// Seed Ram Sharma
seedUser("Ram Sharma", "ram@example.com", "password123", "9800000001", "A+");

// Seed Demo User
seedUser("Demo User", "demo@example.com", "password123", "9800000002", "O+");

?>
