<?php
include 'includes/header.php';

if ($_SESSION['role'] !== 'admin') {
    die("Access Denied");
}

include '../includes/db.php';

// Fetch messages
$sql = "SELECT * FROM messages ORDER BY created_at DESC";
$result = $conn->query($sql);
?>

<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h2>User Messages</h2>
    </div>

    <div style="overflow-x: auto;">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Message</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td>#<?php echo $row['id']; ?></td>
                            <td><?php echo htmlspecialchars($row['name']); ?></td>
                            <td><?php echo htmlspecialchars($row['email']); ?></td>
                            <td><?php echo htmlspecialchars($row['phone']); ?></td>
                            <td><?php echo nl2br(htmlspecialchars($row['message'])); ?></td>
                            <td><?php echo date('M d, Y h:i A', strtotime($row['created_at'])); ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" style="text-align: center;">No messages found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

</div> <!-- Closing main-content div from header -->
</div> <!-- Closing admin-container div from header -->
</body>
</html>
