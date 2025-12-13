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
$sql = "SELECT * FROM popular_routes WHERE id=$id";
$result = $conn->query($sql);
$row = $result->fetch_assoc();

if (!$row) {
    echo "Route not found.";
    exit;
}

// Handle Update
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_route'])) {
    $source = mysqli_real_escape_string($conn, $_POST['source']);
    $destination = mysqli_real_escape_string($conn, $_POST['destination']);
    $duration = mysqli_real_escape_string($conn, $_POST['duration']);
    $price = mysqli_real_escape_string($conn, $_POST['price']);
    $image_path = $row['image_path']; // Default to old image

    // Image Upload
    if (!empty($_FILES["route_image"]["name"])) {
        $target_dir = "../assets/images/";
        $image_name = basename($_FILES["route_image"]["name"]);
        $target_file = $target_dir . $image_name;
        $uploadOk = 1;

        $check = getimagesize($_FILES["route_image"]["tmp_name"]);
        if($check !== false) {
            if (move_uploaded_file($_FILES["route_image"]["tmp_name"], $target_file)) {
                 $image_path = "assets/images/" . $image_name;
            } else {
                $error = "Sorry, there was an error uploading your file.";
            }
        } else {
            $error = "File is not an image.";
        }
    }

    if ($error == "") {
        $sql = "UPDATE popular_routes SET source='$source', destination='$destination', duration='$duration', price='$price', image_path='$image_path' WHERE id=$id";
        
        if ($conn->query($sql) === TRUE) {
            $success = "Route updated successfully!";
             // Refresh data
            $result = $conn->query("SELECT * FROM popular_routes WHERE id=$id");
            $row = $result->fetch_assoc();
        } else {
            $error = "Error: " . $conn->error;
        }
    }
}
?>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
    <h2>Edit Popular Route</h2>
    <a href="popular_routes.php" class="btn btn-outline">Back to Routes</a>
</div>

<?php if($success) echo "<p style='color: #2ecc71; margin-bottom: 10px;'>$success</p>"; ?>
<?php if($error) echo "<p style='color: red; margin-bottom: 10px;'>$error</p>"; ?>

<div class="card">
    <form method="POST" enctype="multipart/form-data" style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
        <div>
            <label>From</label>
            <input type="text" name="source" value="<?php echo htmlspecialchars($row['source']); ?>" required style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
        </div>
        <div>
            <label>To</label>
            <input type="text" name="destination" value="<?php echo htmlspecialchars($row['destination']); ?>" required style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
        </div>
        <div>
            <label>Duration</label>
            <input type="text" name="duration" value="<?php echo htmlspecialchars($row['duration']); ?>" required style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
        </div>
        <div>
            <label>Price (Rs)</label>
            <input type="number" name="price" value="<?php echo htmlspecialchars($row['price']); ?>" required style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
        </div>
        
        <div style="grid-column: span 2;">
            <label>Current Image</label><br>
            <img src="../<?php echo $row['image_path']; ?>" style="width: 100px; height: 60px; object-fit: cover; border-radius: 4px; margin: 5px 0;">
        </div>

        <div style="grid-column: span 2;">
            <label>Change Image (Optional)</label>
            <input type="file" name="route_image" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
        </div>

        <button type="submit" name="update_route" class="btn btn-primary" style="width: fit-content;">Update Route</button>
    </form>
</div>

</div> <!-- Closing main-content -->
</div> <!-- Closing admin-container -->
</body>
</html>
