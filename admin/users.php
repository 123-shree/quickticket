<?php
include '../includes/db.php';
include 'includes/header.php';

// Handle Delete User
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    // Prevent deleting admin
    $check = $conn->query("SELECT role FROM users WHERE id=$id")->fetch_assoc();
    if ($check['role'] != 'admin') {
        $conn->query("DELETE FROM users WHERE id=$id");
        $success = "User deleted successfully";
    } else {
        $error = "Cannot delete admin user";
    }
}
?>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
    <h2>Registered Users</h2>
    <!-- Could add search here later -->
</div>

<?php if(isset($success)) echo "<p style='color: #6AECE1; margin-bottom: 10px;'>$success</p>"; ?>
<?php if(isset($error)) echo "<p style='color: red; margin-bottom: 10px;'>$error</p>"; ?>

<div class="card">
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Joined Date</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sql = "SELECT * FROM users WHERE role='user' ORDER BY created_at DESC";
            $result = $conn->query($sql);
            
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                        <td>{$row['id']}</td>
                        <td>{$row['name']}</td>
                        <td>{$row['email']}</td>
                        <td><span style='padding: 2px 8px; background: #e3f2fd; color: #1565c0; border-radius: 4px; font-size: 0.85rem;'>{$row['role']}</span></td>
                        <td>{$row['created_at']}</td>
                        <td>
                            <a href='users.php?delete={$row['id']}' style='color: red;' onclick='return confirm(\"Are you sure you want to delete this user?\")'>Delete</a>
                        </td>
                    </tr>";
                }
            } else {
                echo "<tr><td colspan='6' style='text-align:center;'>No registered users found.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<?php include 'includes/footer.php'; ?>
