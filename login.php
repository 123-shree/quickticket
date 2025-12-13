<?php
include 'includes/db.php';
include 'includes/header.php';

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    $sql = "SELECT id, name, password, role FROM users WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['user_name'] = $row['name'];
            $_SESSION['role'] = $row['role'];
            
            if ($row['role'] == 'admin' || $row['role'] == 'agent') {
                header("Location: admin/index.php");
            } else {
                if(isset($_POST['redirect']) && !empty($_POST['redirect'])) {
                    header("Location: " . $_POST['redirect']);
                } else {
                    header("Location: index.php");
                }
            }
            exit();
        } else {
            $error = "Invalid password.";
        }
    } else {
        $error = "No account found with that email.";
    }
}
?>

<div class="container section-padding">
    <div class="auth-wrapper" style="max-width: 400px; margin: 0 auto;">
        <div class="form-title" style="text-align: center; margin-bottom: 20px;">
            <h2>Login</h2>
            <p>Welcome back to QuickTicket</p>
        </div>
        
        <?php if($error): ?>
            <div class="alert alert-danger" style="color: red; background: #ffe6e6; padding: 10px; border-radius: 4px; margin-bottom: 15px;">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <form action="" method="POST" class="auth-form" style="background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
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
            <?php 
            if(isset($_GET['redirect'])) {
                echo '<input type="hidden" name="redirect" value="' . htmlspecialchars($_GET['redirect']) . '">';
            }
            ?>
            <button type="submit" class="btn btn-primary" style="width: 100%;">Login</button>
            <p style="margin-top: 15px; text-align: center;">Don't have an account? <a href="signup.php<?php echo isset($_GET['redirect']) ? '?redirect=' . urlencode($_GET['redirect']) : ''; ?>" style="color: var(--primary-color);">Sign Up</a></p>
        </form>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
