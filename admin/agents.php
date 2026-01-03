<?php
include '../includes/db.php';
include 'includes/header.php';

// Access Control
if ($_SESSION['role'] !== 'admin') {
    die("Access Denied");
}

$success = "";
$error = "";

// Handle Add Agent
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_agent'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];
    
    // Check if email exists
    $check = $conn->query("SELECT id FROM users WHERE email='$email'");
    if ($check->num_rows > 0) {
        $error = "Email already exists!";
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $role = 'agent';
        $sql = "INSERT INTO users (name, email, password, role) VALUES ('$name', '$email', '$hashed_password', '$role')";
        
        if ($conn->query($sql) === TRUE) {
            $success = "Agent added successfully!";
        } else {
            $error = "Error: " . $conn->error;
        }
    }
}

// Handle Delete
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    // Prevent deleting self (though logic prevents admin deletion here usually)
    if ($id != $_SESSION['user_id']) {
        $conn->query("DELETE FROM users WHERE id=$id AND role='agent'");
        header("Location: agents.php");
    }
}
?>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
    <h2>Manage Agents</h2>
</div>

<?php if($success) echo "<p style='color: #2ecc71; margin-bottom: 10px;'>$success</p>"; ?>
<?php if($error) echo "<p style='color: red; margin-bottom: 10px;'>$error</p>"; ?>

<div class="card">
    <h3>Add New Agent</h3>
    <form method="POST" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; align-items: end;">
        <div>
            <label>Name</label>
            <input type="text" name="name" required>
        </div>
        <div>
            <label>Email</label>
            <input type="email" name="email" required>
        </div>
        <div>
            <label>Password</label>
            <input type="password" name="password" required>
        </div>
        <button type="submit" name="add_agent" class="btn btn-primary" style="height: 42px;">Add Agent</button>
    </form>
</div>

<div class="card">
    <h3>Existing Agents</h3>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Created At</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $result = $conn->query("SELECT * FROM users WHERE role='agent' ORDER BY id DESC");
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                        <td>{$row['id']}</td>
                        <td>{$row['name']}</td>
                        <td>{$row['email']}</td>
                        <td>{$row['created_at']}</td>
                        <td>
                            <a href='edit_agent.php?id={$row['id']}' style='color: blue; margin-right: 10px;'>Edit</a>
                            <a href='agents.php?delete={$row['id']}' style='color: red;' onclick='return confirm(\"Are you sure you want to delete this agent?\")'>Delete</a>
                        </td>
                    </tr>";
                }
            } else {
                echo "<tr><td colspan='5'>No agents found.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<?php include 'includes/footer.php'; ?>
