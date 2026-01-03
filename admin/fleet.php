<?php
include '../includes/db.php';
include 'includes/header.php';

if ($_SESSION['role'] !== 'admin') {
    die("Access Denied");
}


$success = "";
$error = "";

// Handle Add Fleet Item
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_fleet'])) {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    // $price = mysqli_real_escape_string($conn, $_POST['price']);

    // Image Upload
    $target_dir = "../assets/images/";
    $image_name = basename($_FILES["fleet_image"]["name"]);
    $image_name = time() . "_" . $image_name; // Add timestamp to prevent overwrites
    $target_file = $target_dir . $image_name;
    $image_path = "assets/images/" . $image_name; // Path stored in DB relative to root
    $uploadOk = 1;

    // Check if image file is a actual image or fake image
    if(isset($_POST["submit"])) {
        $check = getimagesize($_FILES["fleet_image"]["tmp_name"]);
        if($check !== false) {
            $uploadOk = 1;
        } else {
            $error = "File is not an image.";
            $uploadOk = 0;
        }
    }

    if ($uploadOk == 1) {
        if (move_uploaded_file($_FILES["fleet_image"]["tmp_name"], $target_file)) {
            $sql = "INSERT INTO fleet (title, description, image_path) VALUES ('$title', '$description', '$image_path')";
            if ($conn->query($sql) === TRUE) {
                $success = "Fleet item added successfully!";
            } else {
                $error = "Error: " . $conn->error;
            }
        } else {
            $error = "Sorry, there was an error uploading your file. Ensure assets/images is writable.";
        }
    }
}

// Handle Delete
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM fleet WHERE id=$id");
    header("Location: fleet.php");
}
?>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
    <h2>Manage Premium Fleet</h2>
</div>

<?php if($success) echo "<p style='color: #2ecc71; margin-bottom: 10px;'>$success</p>"; ?>
<?php if($error) echo "<p style='color: red; margin-bottom: 10px;'>$error</p>"; ?>

<div class="card">
    <h3>Add New Fleet Item</h3>
    <form method="POST" enctype="multipart/form-data" style="display: grid; grid-template-columns: 1fr 2fr auto; gap: 10px; align-items: end; margin-top: 15px;">
        <div>
            <label>Title</label>
            <input type="text" name="title" required placeholder="e.g. VIP / Sofa Bus" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
        </div>
        <div>
            <label>Description</label>
            <input type="text" name="description" required placeholder="e.g. 2x1 Sofa Seating • Air Suspension" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
        </div>
        
        <div style="grid-column: span 2;">
            <label>Bus Image</label>
            <input type="file" name="fleet_image" required style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
        </div>

        <button type="submit" name="add_fleet" class="btn btn-primary" style="height: 35px;">Add Fleet Item</button>
    </form>
</div>

<div class="card">
    <h3>Existing Fleet</h3>
    <table>
        <thead>
            <tr>
                <th>Image</th>
                <th>Title</th>
                <th>Description</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $result = $conn->query("SELECT * FROM fleet ORDER BY created_at DESC");
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                        <td><img src='../{$row['image_path']}' style='width: 50px; height: 30px; object-fit: cover; border-radius: 4px;'></td>
                        <td>{$row['title']}</td>
                        <td>{$row['description']}</td>
                        <td>
                            <a href='edit_fleet.php?id={$row['id']}' style='color: blue; margin-right: 10px;'>Edit</a>
                            <a href='fleet.php?delete={$row['id']}' style='color: red;' onclick='return confirm(\"Are you sure?\")'>Delete</a>
                        </td>
                    </tr>";
                }
            } else {
                echo "<tr><td colspan='4' style='text-align:center;'>No fleet items found. Add one above!</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

</div> <!-- Closing main-content -->
</div> <!-- Closing admin-container -->
</body>
</html>
