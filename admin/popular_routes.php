<?php
include '../includes/db.php';
include 'includes/header.php';

if ($_SESSION['role'] !== 'admin') {
    die("Access Denied");
}


$success = "";
$error = "";

// Handle Add Route
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_route'])) {
    $source = mysqli_real_escape_string($conn, $_POST['source']);
    $destination = mysqli_real_escape_string($conn, $_POST['destination']);
    $duration = mysqli_real_escape_string($conn, $_POST['duration']);
    $price = mysqli_real_escape_string($conn, $_POST['price']);

    // Image Upload
    $target_dir = "../assets/images/";
    $image_name = basename($_FILES["route_image"]["name"]);
    $target_file = $target_dir . $image_name;
    $image_path = "assets/images/" . $image_name; // Path stored in DB relative to root
    $uploadOk = 1;

    // Check if image file is a actual image or fake image
    if(isset($_POST["submit"])) {
        $check = getimagesize($_FILES["route_image"]["tmp_name"]);
        if($check !== false) {
            $uploadOk = 1;
        } else {
            $error = "File is not an image.";
            $uploadOk = 0;
        }
    }

    if ($uploadOk == 1) {
        if (move_uploaded_file($_FILES["route_image"]["tmp_name"], $target_file)) {
            $sql = "INSERT INTO popular_routes (source, destination, duration, price, image_path) VALUES ('$source', '$destination', '$duration', '$price', '$image_path')";
            if ($conn->query($sql) === TRUE) {
                $success = "Route added successfully!";
            } else {
                $error = "Error: " . $conn->error;
            }
        } else {
            $error = "Sorry, there was an error uploading your file.";
        }
    }
}

// Handle Delete
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM popular_routes WHERE id=$id");
    header("Location: popular_routes.php");
}
?>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
    <h2>Manage Popular Routes</h2>
</div>

<?php if($success) echo "<p style='color: #2ecc71; margin-bottom: 10px;'>$success</p>"; ?>
<?php if($error) echo "<p style='color: red; margin-bottom: 10px;'>$error</p>"; ?>

<div class="card">
    <h3>Add New Popular Route</h3>
    <form method="POST" enctype="multipart/form-data" style="display: grid; grid-template-columns: 1fr 1fr 1fr 1fr auto; gap: 10px; align-items: end; margin-top: 15px;">
        <div>
            <label>From</label>
            <input type="text" name="source" required placeholder="e.g. Kathmandu" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
        </div>
        <div>
            <label>To</label>
            <input type="text" name="destination" required placeholder="e.g. Pokhara" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
        </div>
        <div>
            <label>Duration</label>
            <input type="text" name="duration" required placeholder="e.g. 7 Hours" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
        </div>
        <div>
            <label>Price (Rs)</label>
            <input type="number" name="price" required placeholder="800" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
        </div>
        
        <div style="grid-column: span 4;">
            <label>Route Image</label>
            <input type="file" name="route_image" required style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
        </div>

        <button type="submit" name="add_route" class="btn btn-primary" style="height: 35px;">Add Route</button>
    </form>
</div>

<div class="card">
    <h3>Existing Popular Routes</h3>
    <table>
        <thead>
            <tr>
                <th>Image</th>
                <th>Route</th>
                <th>Time</th>
                <th>Price</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $result = $conn->query("SELECT * FROM popular_routes ORDER BY created_at DESC");
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                        <td><img src='../{$row['image_path']}' style='width: 50px; height: 30px; object-fit: cover; border-radius: 4px;'></td>
                        <td>{$row['source']} -> {$row['destination']}</td>
                        <td>{$row['duration']}</td>
                        <td>Rs. {$row['price']}</td>
                        <td>
                            <a href='edit_popular_route.php?id={$row['id']}' style='color: blue; margin-right: 10px;'>Edit</a>
                            <a href='popular_routes.php?delete={$row['id']}' style='color: red;' onclick='return confirm(\"Are you sure?\")'>Delete</a>
                        </td>
                    </tr>";
                }
            } else {
                echo "<tr><td colspan='5' style='text-align:center;'>No routes found. Add one above!</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

</div> <!-- Closing main-content -->
</div> <!-- Closing admin-container -->
</body>
</html>
