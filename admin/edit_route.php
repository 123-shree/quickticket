<?php
include '../includes/db.php';
include 'includes/header.php';

if (!isset($_GET['id'])) {
    header("Location: routes.php");
    exit();
}

$id = $_GET['id'];
$success = "";
$error = "";

// Handle Update
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_route'])) {
    $bus_id = $_POST['bus_id'];
    $source = $_POST['source'];
    $destination = $_POST['destination'];
    $departure_date = $_POST['departure_date'];
    $departure_time = $_POST['departure_time'];
    $price = $_POST['price'];

    $sql = "UPDATE routes SET bus_id='$bus_id', source='$source', destination='$destination', 
            departure_date='$departure_date', departure_time='$departure_time', price='$price' WHERE id=$id";
    
    if ($conn->query($sql) === TRUE) {
        header("Location: routes.php?msg=Route updated successfully");
        exit();
    } else {
        $error = "Error updating route: " . $conn->error;
    }
}

// Fetch Current Data
$result = $conn->query("SELECT * FROM routes WHERE id=$id");
if ($result->num_rows == 0) {
    echo "Route not found.";
    exit();
}
$route = $result->fetch_assoc();
?>

<div class="main-content">
    <h2>Edit Route</h2>
    
    <?php if($success) echo "<p style='color: green; margin-bottom: 10px;'>$success</p>"; ?>
    <?php if($error) echo "<p style='color: red; margin-bottom: 10px;'>$error</p>"; ?>

    <div class="card" style="max-width: 600px;">
        <form method="POST">
            <div class="form-group">
                <label>Bus</label>
                <select name="bus_id" required style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                    <?php
                    $buses = $conn->query("SELECT * FROM buses");
                    while ($bus = $buses->fetch_assoc()) {
                        $selected = ($bus['id'] == $route['bus_id']) ? 'selected' : '';
                        echo "<option value='{$bus['id']}' $selected>{$bus['bus_name']} ({$bus['bus_number']})</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="form-group" style="margin-top: 10px;">
                <label>Source</label>
                <input type="text" name="source" value="<?php echo $route['source']; ?>" required style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
            </div>
            <div class="form-group" style="margin-top: 10px;">
                <label>Destination</label>
                <input type="text" name="destination" value="<?php echo $route['destination']; ?>" required style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
            </div>
            <div class="form-group" style="margin-top: 10px;">
                <label>Date</label>
                <input type="date" name="departure_date" value="<?php echo $route['departure_date']; ?>" required style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
            </div>
            <div class="form-group" style="margin-top: 10px;">
                <label>Time</label>
                <input type="time" name="departure_time" value="<?php echo $route['departure_time']; ?>" required style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
            </div>
            <div class="form-group" style="margin-top: 10px;">
                <label>Price (NPR)</label>
                <input type="number" name="price" value="<?php echo $route['price']; ?>" required style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
            </div>
            <div style="margin-top: 20px;">
                <button type="submit" name="update_route" class="btn btn-primary">Update Route</button>
                <a href="routes.php" class="btn btn-outline" style="margin-left: 10px;">Back</a>
            </div>
        </form>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
