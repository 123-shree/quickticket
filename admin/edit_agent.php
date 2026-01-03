<?php
include '../includes/db.php';
include 'includes/header.php';

if ($_SESSION['role'] !== 'admin') {
    die("Access Denied");
}

$id = $_GET['id'];
$agent = $conn->query("SELECT * FROM users WHERE id=$id AND role='agent'")->fetch_assoc();

if (!$agent) {
    die("Agent not found");
}

$success = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    
    // Update basic info
    $sql = "UPDATE users SET name='$name', email='$email' WHERE id=$id";
    if ($conn->query($sql) === TRUE) {
        $success = "Agent updated successfully!";
        // Update object
        $agent['name'] = $name;
        $agent['email'] = $email;
    } else {
        $error = "Error updating agent: " . $conn->error;
    }

    // Update password if provided
    if (!empty($_POST['password'])) {
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $conn->query("UPDATE users SET password='$password' WHERE id=$id");
        $success .= " Password updated.";
    }
}
?>

<div style="margin-bottom: 20px;">
    <a href="agents.php" class="btn btn-secondary">Back to Agents</a>
    <h2>Edit Agent</h2>
</div>

<?php if($success) echo "<p style='color: green;'>$success</p>"; ?>
<?php if($error) echo "<p style='color: red;'>$error</p>"; ?>

<div class="card">
    <form method="POST">
        <div class="form-group" style="margin-bottom: 15px;">
            <label>Name</label>
            <input type="text" name="name" value="<?php echo $agent['name']; ?>" required style="width: 100%; padding: 10px;">
        </div>
        <div class="form-group" style="margin-bottom: 15px;">
            <label>Email</label>
            <input type="email" name="email" value="<?php echo $agent['email']; ?>" required style="width: 100%; padding: 10px;">
        </div>
        <div class="form-group" style="margin-bottom: 15px;">
            <label>New Password (Leave blank to keep current)</label>
            <input type="password" name="password" style="width: 100%; padding: 10px;">
        </div>
        <button type="submit" class="btn btn-primary">Update Agent</button>
    </form>
</div>

<?php include 'includes/footer.php'; ?>
