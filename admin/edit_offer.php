<?php
include '../includes/db.php';
include 'includes/header.php';

if ($_SESSION['role'] !== 'admin') {
    die("Access Denied");
}


$id = $_GET['id'];
$success = "";
$error = "";

// Fetch existing data
$sql = "SELECT * FROM offers WHERE id=$id";
$result = $conn->query($sql);
$row = $result->fetch_assoc();

if (!$row) {
    echo "Offer not found.";
    exit;
}

// Handle Update
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_offer'])) {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $promo_tag = mysqli_real_escape_string($conn, $_POST['promo_tag']);
    $promo_color = mysqli_real_escape_string($conn, $_POST['promo_color']);
    $icon = mysqli_real_escape_string($conn, $_POST['icon']);

    $sql = "UPDATE offers SET title='$title', description='$description', promo_tag='$promo_tag', promo_color='$promo_color', icon='$icon' WHERE id=$id";
    
    if ($conn->query($sql) === TRUE) {
        $success = "Offer updated successfully!";
        // Refresh data
        $result = $conn->query("SELECT * FROM offers WHERE id=$id");
        $row = $result->fetch_assoc();
    } else {
        $error = "Error: " . $conn->error;
    }
}
?>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
    <h2>Edit Offer</h2>
    <a href="offers.php" class="btn btn-outline">Back to Offers</a>
</div>

<?php if($success) echo "<p style='color: #2ecc71; margin-bottom: 10px;'>$success</p>"; ?>
<?php if($error) echo "<p style='color: red; margin-bottom: 10px;'>$error</p>"; ?>

<div class="card">
    <form method="POST" style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
        <div style="grid-column: span 2;">
            <label>Title</label>
            <input type="text" name="title" value="<?php echo htmlspecialchars($row['title']); ?>" required style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
        </div>
        
        <div style="grid-column: span 2;">
            <label>Description</label>
            <input type="text" name="description" value="<?php echo htmlspecialchars($row['description']); ?>" required style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
        </div>

        <div>
            <label>Promo Tag</label>
            <input type="text" name="promo_tag" value="<?php echo htmlspecialchars($row['promo_tag']); ?>" required style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
        </div>

        <div>
            <label>Tag Color (Hex)</label>
            <input type="color" name="promo_color" value="<?php echo htmlspecialchars($row['promo_color']); ?>" style="width: 100%; height: 35px; padding: 2px; border: 1px solid #ddd; border-radius: 4px;">
        </div>

        <div style="grid-column: span 2;">
            <label>Icon Class (FontAwesome)</label>
            <input type="text" name="icon" value="<?php echo htmlspecialchars($row['icon']); ?>" required style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
        </div>

        <button type="submit" name="update_offer" class="btn btn-primary" style="width: fit-content;">Update Offer</button>
    </form>
</div>

</div> <!-- Closing main-content -->
</div> <!-- Closing admin-container -->
</body>
</html>
