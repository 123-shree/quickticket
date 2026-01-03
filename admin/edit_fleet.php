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
$sql = "SELECT * FROM fleet WHERE id=$id";
$result = $conn->query($sql);
$row = $result->fetch_assoc();

if (!$row) {
    echo "Fleet item not found.";
    exit;
}

// Handle Update
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_fleet'])) {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $image_path = $row['image_path']; // Default to old image

    // Image Upload
    if (!empty($_FILES["fleet_image"]["name"])) {
        $target_dir = "../assets/images/";
        $image_name = basename($_FILES["fleet_image"]["name"]);
        $image_name = time() . "_" . $image_name; // Timestamp
        $target_file = $target_dir . $image_name;
        $uploadOk = 1;

        $check = getimagesize($_FILES["fleet_image"]["tmp_name"]);
        if($check !== false) {
            if (move_uploaded_file($_FILES["fleet_image"]["tmp_name"], $target_file)) {
                 $image_path = "assets/images/" . $image_name;
            } else {
                $error = "Sorry, there was an error uploading your file.";
            }
        } else {
            $error = "File is not an image.";
        }
    }

    if ($error == "") {
        $sql = "UPDATE fleet SET title='$title', description='$description', image_path='$image_path' WHERE id=$id";
        
        if ($conn->query($sql) === TRUE) {
            $success = "Fleet item updated successfully!";
             // Refresh data
            $result = $conn->query("SELECT * FROM fleet WHERE id=$id");
            $row = $result->fetch_assoc();
        } else {
            $error = "Error: " . $conn->error;
        }
    }
}
?>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
    <h2>Edit Fleet Item</h2>
    <a href="fleet.php" class="btn btn-outline">Back to Fleet</a>
</div>

<?php if($success) echo "<p style='color: #2ecc71; margin-bottom: 10px;'>$success</p>"; ?>
<?php if($error) echo "<p style='color: red; margin-bottom: 10px;'>$error</p>"; ?>

<div class="card">
    <form method="POST" enctype="multipart/form-data" style="display: grid; grid-template-columns: 1fr; gap: 15px;">
        <div>
            <label>Title</label>
            <input type="text" name="title" value="<?php echo htmlspecialchars($row['title']); ?>" required style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
        </div>
        <div>
            <label>Description</label>
            <input type="text" name="description" value="<?php echo htmlspecialchars($row['description']); ?>" required style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
        </div>
        
        <div>
            <label>Current Image</label><br>
            <img src="../<?php echo $row['image_path']; ?>" style="width: 150px; height: 100px; object-fit: cover; border-radius: 4px; margin: 5px 0;">
        </div>

        <div>
            <label>Change Image (Optional)</label>
            <input type="file" name="fleet_image" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
        </div>

        <button type="submit" name="update_fleet" class="btn btn-primary" style="width: fit-content;">Update Fleet Item</button>
    </form>
</div>

</div> <!-- Closing main-content -->
</div> <!-- Closing admin-container -->
</body>
</html>
