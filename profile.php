<?php
include 'includes/db.php';
include 'includes/header.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$success = "";
$error = "";

// Handle Profile Update
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $blood_group = mysqli_real_escape_string($conn, $_POST['blood_group']);
    $phone_number = mysqli_real_escape_string($conn, $_POST['phone_number']);
    
    // File Upload Handler Function
    function uploadFile($fileInputName) {
        if (isset($_FILES[$fileInputName]) && $_FILES[$fileInputName]['error'] == 0) {
            $target_dir = "uploads/";
            if (!is_dir($target_dir)) {
                mkdir($target_dir, 0777, true);
            }
            $file_extension = pathinfo($_FILES[$fileInputName]["name"], PATHINFO_EXTENSION);
            $new_filename = uniqid() . "_" . $fileInputName . "." . $file_extension;
            $target_file = $target_dir . $new_filename;
            
            if (move_uploaded_file($_FILES[$fileInputName]["tmp_name"], $target_file)) {
                return $target_file;
            }
        }
        return null;
    }

    $profile_image = uploadFile('profile_image');
    $citizenship_front = uploadFile('citizenship_front');
    $citizenship_back = uploadFile('citizenship_back');

    // Build Update Query
    $update_fields = [
        "name='$name'", 
        "blood_group='$blood_group'", 
        "phone_number='$phone_number'"
    ];

    if ($profile_image) $update_fields[] = "profile_image='$profile_image'";
    if ($citizenship_front) $update_fields[] = "citizenship_front='$citizenship_front'";
    if ($citizenship_back) $update_fields[] = "citizenship_back='$citizenship_back'";

    $password = $_POST['password'];
    if (!empty($password)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $update_fields[] = "password='$hashed_password'";
    }

    $sql = "UPDATE users SET " . implode(", ", $update_fields) . " WHERE id='$user_id'";

    if ($conn->query($sql) === TRUE) {
        $success = "Profile details updated successfully!";
        $_SESSION['user_name'] = $name;
    } else {
        $error = "Error updating profile: " . $conn->error;
    }
}

// Fetch current user data
$sql = "SELECT * FROM users WHERE id='$user_id'";
$result = $conn->query($sql);
$user = $result->fetch_assoc();
?>

<div class="page-header">
    <div class="container">
        <h1>My Profile</h1>
    </div>
</div>

<div class="container section-padding">
    <div style="max-width: 600px; margin: 0 auto; background: white; padding: 40px; border-radius: 10px; box-shadow: 0 10px 30px rgba(0,0,0,0.05);">
        
        <?php if($success): ?>
            <div class="alert success" style="background: #d4edda; color: #155724; padding: 15px; border-radius: 5px; margin-bottom: 20px;">
                <?php echo $success; ?>
            </div>
        <?php endif; ?>

        <?php if($error): ?>
            <div class="alert error" style="background: #f8d7da; color: #721c24; padding: 15px; border-radius: 5px; margin-bottom: 20px;">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <?php
        // Loyalty Check
        $loyalty_sql = "SELECT COUNT(*) as confirmed_bookings FROM bookings WHERE user_id='$user_id' AND status='confirmed'";
        $loyalty_result = $conn->query($loyalty_sql);
        $loyalty_data = $loyalty_result->fetch_assoc();
        
        // Show ID Card if 3 or more confirmed bookings
        if ($loyalty_data['confirmed_bookings'] >= 3):
            $id_photo = !empty($user['profile_image']) ? $user['profile_image'] : 'https://via.placeholder.com/150';
        ?>
            <!-- DIGITAL ID CARD -->
            <div class="id-card" style="background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%); color: white; border-radius: 15px; padding: 20px; position: relative; overflow: hidden; margin-bottom: 30px; box-shadow: 0 10px 20px rgba(0,0,0,0.2);">
                <!-- Watermark/bg decoration -->
                <div style="position: absolute; top: -20px; right: -20px; font-size: 10rem; opacity: 0.1; transform: rotate(-15deg);">
                    <i class="fas fa-bus"></i>
                </div>
                
                <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid rgba(255,255,255,0.2); padding-bottom: 15px; margin-bottom: 15px;">
                    <div style="font-size: 1.2rem; font-weight: bold; letter-spacing: 1px;">QUICKTICKET <span style="font-weight: normal; font-size: 0.8rem; background: gold; color: #333; padding: 2px 6px; border-radius: 4px; margin-left: 5px;">GOLD MEMBER</span></div>
                    <div style="text-align: right;">
                        <img src="assets/images/chip.png" alt="Chip" style="width: 40px; opacity: 0.8; background: #ddd; border-radius: 5px;"> <!-- Placeholder for chip -->
                    </div>
                </div>

                <div style="display: flex; align-items: center; gap: 20px;">
                    <div style="flex-shrink: 0;">
                        <img src="<?php echo $id_photo; ?>" alt="Profile" style="width: 100px; height: 100px; object-fit: cover; border-radius: 10px; border: 2px solid white;">
                    </div>
                    <div style="flex-grow: 1;">
                        <h2 style="margin: 0; font-size: 1.5rem; text-transform: uppercase;"><?php echo $user['name']; ?></h2>
                        <p style="margin: 5px 0; opacity: 0.9; font-size: 0.9rem;"><i class="fas fa-phone"></i> <?php echo !empty($user['phone_number']) ? $user['phone_number'] : 'N/A'; ?></p>
                        <p style="margin: 0; opacity: 0.9; font-size: 0.9rem;"><i class="fas fa-tint"></i> Blood Group: <?php echo !empty($user['blood_group']) ? $user['blood_group'] : 'N/A'; ?></p>
                        
                        <?php if(!empty($user['citizenship_front']) && !empty($user['citizenship_back'])): ?>
                            <div style="margin-top: 5px; color: #28a745; font-size: 0.85rem; font-weight: bold;"><i class="fas fa-check-circle"></i> Identity Verified</div>
                        <?php else: ?>
                             <div style="margin-top: 5px; color: #ffc107; font-size: 0.85rem;"><i class="fas fa-exclamation-circle"></i> Upload Docs to Verify</div>
                        <?php endif; ?>
                    </div>
                </div>

                <div style="margin-top: 20px; display: flex; justify-content: space-between; align-items: flex-end;">
                    <div>
                        <div style="font-size: 0.7rem; opacity: 0.7;">MEMBER ID</div>
                        <div style="font-family: monospace; letter-spacing: 2px;">QT-<?php echo str_pad($user_id, 8, '0', STR_PAD_LEFT); ?></div>
                    </div>
                    <div style="background: white; color: #1e3c72; padding: 5px 10px; border-radius: 5px; font-weight: bold; font-size: 0.9rem;">
                        5% OFF
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <?php
        // Calculate Profile Stats
        $stats_period = isset($_GET['period']) ? $_GET['period'] : 'all';
        $stats_where = "WHERE user_id='$user_id' AND payment_status='paid'";
        
        if ($stats_period == '1_month') {
            $stats_where .= " AND booking_date >= DATE_SUB(NOW(), INTERVAL 1 MONTH)";
        } elseif ($stats_period == '1_year') {
            $stats_where .= " AND booking_date >= DATE_SUB(NOW(), INTERVAL 1 YEAR)";
        }

        $stats_sql = "SELECT COUNT(*) as total_bookings, SUM(paid_amount) as total_spent FROM bookings $stats_where";
        $stats_result = $conn->query($stats_sql);
        $user_stats = $stats_result->fetch_assoc();
        $total_spent = $user_stats['total_spent'] ? $user_stats['total_spent'] : 0;
        ?>

        <div class="stats-card" style="background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%); color: white; padding: 25px; border-radius: 12px; margin-bottom: 30px; box-shadow: 0 5px 15px rgba(37, 117, 252, 0.3);">
            <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 20px;">
                <h3 style="margin: 0; font-size: 1.3rem;"><i class="fas fa-chart-line"></i> Travel Overview</h3>
                <form method="GET" action="" style="margin: 0;">
                    <select name="period" onchange="this.form.submit()" style="padding: 5px 10px; border-radius: 20px; border: none; font-size: 0.9rem; color: #333; cursor: pointer;">
                        <option value="all" <?php if($stats_period == 'all') echo 'selected'; ?>>All Time</option>
                        <option value="1_month" <?php if($stats_period == '1_month') echo 'selected'; ?>>Last 1 Month</option>
                        <option value="1_year" <?php if($stats_period == '1_year') echo 'selected'; ?>>Last 1 Year</option>
                    </select>
                </form>
            </div>
            
            <div style="display: flex; gap: 20px;">
                <div style="flex: 1; text-align: center; border-right: 1px solid rgba(255,255,255,0.2);">
                    <div style="font-size: 2rem; font-weight: bold;"><?php echo $user_stats['total_bookings']; ?></div>
                    <div style="font-size: 0.9rem; opacity: 0.9;">Total Trips</div>
                </div>
                <div style="flex: 1; text-align: center;">
                    <div style="font-size: 2rem; font-weight: bold;">Rs. <?php echo number_format($total_spent); ?></div>
                    <div style="font-size: 0.9rem; opacity: 0.9;">Total Invested</div>
                </div>
            </div>
        </div>

        <div style="text-align: center; margin-bottom: 30px;">
            <div style="width: 120px; height: 120px; background: #f0f2f5; border-radius: 50%; border: 3px solid var(--primary-color); display: flex; align-items: center; justify-content: center; margin: 0 auto 15px; font-size: 3rem; color: #ccc; overflow: hidden; position: relative;">
                <?php if (!empty($user['profile_image'])): ?>
                    <img src="<?php echo $user['profile_image']; ?>" alt="Profile" style="width: 100%; height: 100%; object-fit: cover;">
                <?php else: ?>
                    <i class="fas fa-user-circle"></i>
                <?php endif; ?>
            </div>
            <h3 style="margin-bottom: 5px;"><?php echo $user['name']; ?></h3>
            <p style="color: #888;"><?php echo $user['email']; ?></p>
        </div>

        <form method="POST" action="" enctype="multipart/form-data">
            <div class="form-group" style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #555;">Full Name</label>
                <input type="text" name="name" value="<?php echo $user['name']; ?>" required style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 5px; font-size: 1rem;">
            </div>

            <div class="form-group" style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #555;">Email Address</label>
                <input type="email" value="<?php echo $user['email']; ?>" disabled style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 5px; font-size: 1rem; background: #f9f9f9; cursor: not-allowed;">
            </div>

            <div class="form-group" style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #555;">Phone Number</label>
                <input type="text" name="phone_number" value="<?php echo isset($user['phone_number']) ? $user['phone_number'] : ''; ?>" placeholder="Enter your contact number" style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 5px; font-size: 1rem;">
            </div>

            <div class="form-group" style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #555;">Blood Group</label>
                <select name="blood_group" style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 5px; font-size: 1rem;">
                    <option value="">Select Blood Group</option>
                    <?php
                    $groups = ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'];
                    foreach($groups as $g) {
                        $selected = (isset($user['blood_group']) && $user['blood_group'] == $g) ? 'selected' : '';
                        echo "<option value='$g' $selected>$g</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="form-group" style="margin-bottom: 20px; background: #f9f9f9; padding: 15px; border-radius: 5px;">
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #555;"><i class="fas fa-camera"></i> Profile Photo</label>
                <input type="file" name="profile_image" accept="image/*" style="width: 100%;">
                <small style="color: #666;">Clear face photo for ID card.</small>
            </div>

            <div class="form-group" style="margin-bottom: 20px; background: #f9f9f9; padding: 15px; border-radius: 5px;">
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #555;"><i class="fas fa-id-card"></i> Citizenship / ID Proof (To verify Identity)</label>
                <div style="display: flex; gap: 10px;">
                    <div style="flex: 1;">
                        <label style="font-size: 0.9rem;">Front Side</label>
                        <input type="file" name="citizenship_front" accept="image/*" style="width: 100%;">
                    </div>
                    <div style="flex: 1;">
                        <label style="font-size: 0.9rem;">Back Side</label>
                        <input type="file" name="citizenship_back" accept="image/*" style="width: 100%;">
                    </div>
                </div>
            </div>

            <div class="form-group" style="margin-bottom: 30px;">
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #555;">New Password <span style="font-weight: normal; color: #999;">(Leave blank to keep current)</span></label>
                <input type="password" name="password" placeholder="Enter new password" style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 5px; font-size: 1rem;">
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%; padding: 12px; font-size: 1.1rem;">Update Profile & Identity</button>
        </form>

        <div style="margin-top: 30px; text-align: center; border-top: 1px solid #eee; padding-top: 20px;">
            <a href="my_tickets.php" style="color: var(--secondary-color); text-decoration: none; display: flex; align-items: center; justify-content: center; gap: 8px;">
                <i class="fas fa-ticket-alt"></i> View My Tickets
            </a>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
