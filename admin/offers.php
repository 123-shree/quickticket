<?php
include '../includes/db.php';
include 'includes/header.php';

if ($_SESSION['role'] !== 'admin') {
    die("Access Denied");
}


$success = "";
$error = "";

// Handle Add Offer
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_offer'])) {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $promo_tag = mysqli_real_escape_string($conn, $_POST['promo_tag']);
    $promo_color = mysqli_real_escape_string($conn, $_POST['promo_color']);
    $icon = mysqli_real_escape_string($conn, $_POST['icon']);

    $sql = "INSERT INTO offers (title, description, promo_tag, promo_color, icon) VALUES ('$title', '$description', '$promo_tag', '$promo_color', '$icon')";
    if ($conn->query($sql) === TRUE) {
        $success = "Offer added successfully!";
    } else {
        $error = "Error: " . $conn->error;
    }
}

// Handle Delete
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM offers WHERE id=$id");
    header("Location: offers.php");
}
?>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
    <h2>Manage Offers</h2>
</div>

<?php if($success) echo "<p style='color: #2ecc71; margin-bottom: 10px;'>$success</p>"; ?>
<?php if($error) echo "<p style='color: red; margin-bottom: 10px;'>$error</p>"; ?>

<div class="card">
    <h3>Add New Offer</h3>
    <form method="POST" style="display: grid; grid-template-columns: 1fr 1fr 1fr auto; gap: 10px; align-items: end; margin-top: 15px;">
        <div style="grid-column: span 3;">
            <label>Title</label>
            <input type="text" name="title" required placeholder="e.g. Save 10% on First Ride" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
        </div>
        
        <div style="grid-column: span 3;">
            <label>Description</label>
            <input type="text" name="description" required placeholder="e.g. Use Code: NEWUSER" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
        </div>

        <div>
            <label>Promo Tag</label>
            <input type="text" name="promo_tag" required placeholder="e.g. PROMO" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
        </div>

        <div>
            <label>Tag Color (Hex)</label>
            <input type="color" name="promo_color" value="#ff6b6b" style="width: 100%; height: 35px; padding: 2px; border: 1px solid #ddd; border-radius: 4px;">
        </div>

        <div>
            <label>Icon Class (FontAwesome)</label>
            <input type="text" name="icon" required placeholder="e.g. fas fa-gift" value="fas fa-gift" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
        </div>

        <button type="submit" name="add_offer" class="btn btn-primary" style="height: 35px;">Add Offer</button>
    </form>
</div>

<div class="card">
    <h3>Current Offers</h3>
    <table>
        <thead>
            <tr>
                <th>Title</th>
                <th>Description</th>
                <th>Tag</th>
                <th>Icon</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $result = $conn->query("SELECT * FROM offers ORDER BY created_at DESC");
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                        <td>{$row['title']}</td>
                        <td>{$row['description']}</td>
                        <td><span style='background-color: {$row['promo_color']}; color: white; padding: 2px 6px; border-radius: 4px; font-size: 0.8rem;'>{$row['promo_tag']}</span></td>
                        <td><i class='{$row['icon']}'></i></td>
                        <td>
                            <a href='edit_offer.php?id={$row['id']}' style='color: blue; margin-right: 10px;'>Edit</a>
                            <a href='offers.php?delete={$row['id']}' style='color: red;' onclick='return confirm(\"Are you sure?\")'>Delete</a>
                        </td>
                    </tr>";
                }
            } else {
                echo "<tr><td colspan='5' style='text-align:center;'>No offers found. Add one above!</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

</div> <!-- Closing main-content -->
</div> <!-- Closing admin-container -->
</body>
</html>
