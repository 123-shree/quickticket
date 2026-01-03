<?php
include 'includes/db.php';
include 'includes/header.php';

$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password !== $confirm_password) {
        $error = "Passwords do not match!";
    } else {
        // Check if email exists
        $check_email = "SELECT id FROM users WHERE email = '$email'";
        $result = $conn->query($check_email);
        
        if ($result->num_rows > 0) {
            $error = "Email already exists!";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $sql = "INSERT INTO users (name, email, password) VALUES ('$name', '$email', '$hashed_password')";
            
            if ($conn->query($sql) === TRUE) {
                $redirect_param = isset($_GET['redirect']) ? "&redirect=" . urlencode($_GET['redirect']) : "";
                if (isset($_POST['redirect'])) {
                     $redirect_param = "&redirect=" . urlencode($_POST['redirect']);
                }
                header("Location: login.php?registered=true" . $redirect_param);
                exit();
            } else {
                $error = "Error: " . $conn->error;
            }
        }
    }
}
?>

<div class="container section-padding">
    <div class="auth-wrapper" style="max-width: 400px; margin: 0 auto;">
        <div class="form-title" style="text-align: center; margin-bottom: 20px;">
            <h2>Sign Up</h2>
            <p>Create your QuickTicket account</p>
        </div>
        
        <?php if($error): ?>
            <div class="alert alert-danger" style="color: red; background: #ffe6e6; padding: 10px; border-radius: 4px; margin-bottom: 15px;">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>
        
        <?php if($success): ?>
            <div class="alert alert-success" style="color: #6AECE1; background: #e6ffe6; padding: 10px; border-radius: 4px; margin-bottom: 15px;">
                <?php echo $success; ?>
            </div>
        <?php endif; ?>

        <form action="" method="POST" class="auth-form" style="background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
            <div class="form-group">
                <label for="name">Full Name</label>
                <input type="text" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <div class="password-wrapper">
                    <input type="password" id="password" name="password" required>
                    <i class="fas fa-eye toggle-password"></i>
                </div>
            </div>
            <div class="form-group">
                <label for="confirm_password">Confirm Password</label>
                <div class="password-wrapper">
                    <input type="password" id="confirm_password" name="confirm_password" required>
                    <i class="fas fa-eye toggle-password"></i>
                </div>
            </div>
            <?php 
            if(isset($_GET['redirect'])) {
                echo '<input type="hidden" name="redirect" value="' . htmlspecialchars($_GET['redirect']) . '">';
            }
            ?>
            <button type="submit" class="btn btn-primary" style="width: 100%;">Create Account</button>
            <p style="margin-top: 15px; text-align: center;">Already have an account? <a href="login.php" style="color: var(--primary-color);">Login</a></p>
        </form>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
