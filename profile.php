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
    $email = mysqli_real_escape_string($conn, $_POST['email']); // Usually email is immutable or needs verification, let's keep it read-only for now or allow change
    // Let's assume email is unique identifier and keep it read-only for simplicity, or allow change if unique. 
    // For this simple app, let's allow Name and Password change.
    
    $password = $_POST['password'];
    
    if (!empty($password)) {
        // Update with password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $sql = "UPDATE users SET name='$name', password='$hashed_password' WHERE id='$user_id'";
    } else {
        // Update without password
        $sql = "UPDATE users SET name='$name' WHERE id='$user_id'";
    }

    if ($conn->query($sql) === TRUE) {
        $success = "Profile updated successfully!";
        // Update session name if changed
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

        <div style="text-align: center; margin-bottom: 30px;">
            <div style="width: 100px; height: 100px; background: #f0f2f5; border-radius: 50%; border: 3px solid var(--primary-color); display: flex; align-items: center; justify-content: center; margin: 0 auto 15px; font-size: 3rem; color: #ccc;">
                <i class="fas fa-user-circle"></i>
            </div>
            <h3 style="margin-bottom: 5px;"><?php echo $user['name']; ?></h3>
            <p style="color: #888;"><?php echo $user['email']; ?></p>
        </div>

        <form method="POST" action="">
            <div class="form-group" style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #555;">Full Name</label>
                <input type="text" name="name" value="<?php echo $user['name']; ?>" required style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 5px; font-size: 1rem;">
            </div>

            <div class="form-group" style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #555;">Email Address</label>
                <input type="email" value="<?php echo $user['email']; ?>" disabled style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 5px; font-size: 1rem; background: #f9f9f9; cursor: not-allowed;">
                <small style="color: #999;">Email cannot be changed.</small>
            </div>

            <div class="form-group" style="margin-bottom: 30px;">
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #555;">New Password <span style="font-weight: normal; color: #999;">(Leave blank to keep current)</span></label>
                <input type="password" name="password" placeholder="Enter new password" style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 5px; font-size: 1rem;">
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%; padding: 12px; font-size: 1.1rem;">Update Profile</button>
        </form>

        <div style="margin-top: 30px; text-align: center; border-top: 1px solid #eee; padding-top: 20px;">
            <a href="my_tickets.php" style="color: var(--secondary-color); text-decoration: none; display: flex; align-items: center; justify-content: center; gap: 8px;">
                <i class="fas fa-ticket-alt"></i> View My Tickets
            </a>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
