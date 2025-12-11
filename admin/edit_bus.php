<?php
include '../includes/db.php';
include 'includes/header.php';

if (!isset($_GET['id'])) {
    header("Location: buses.php");
    exit();
}

$id = $_GET['id'];
$success = "";
$error = "";

// Handle Update
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_bus'])) {
    $bus_name = $_POST['bus_name'];
    $bus_number = $_POST['bus_number'];
    $bus_type = $_POST['bus_type'];
    $total_seats = $_POST['total_seats'];

    $sql = "UPDATE buses SET bus_name='$bus_name', bus_number='$bus_number', bus_type='$bus_type', total_seats='$total_seats' WHERE id=$id";
    
    if ($conn->query($sql) === TRUE) {
        header("Location: buses.php?msg=Bus updated successfully");
        exit();
        // Refresh data
    } else {
        $error = "Error updating bus: " . $conn->error;
    }
}

// Fetch Current Data
$result = $conn->query("SELECT * FROM buses WHERE id=$id");
if ($result->num_rows == 0) {
    echo "Bus not found.";
    exit();
}
$bus = $result->fetch_assoc();
?>

<div class="main-content">
    <h2>Edit Bus</h2>
    
    <?php if($success) echo "<p style='color: green; margin-bottom: 10px;'>$success</p>"; ?>
    <?php if($error) echo "<p style='color: red; margin-bottom: 10px;'>$error</p>"; ?>

    <div class="card" style="max-width: 600px;">
        <form method="POST">
            <div class="form-group">
                <label>Bus Name</label>
                <input type="text" name="bus_name" value="<?php echo $bus['bus_name']; ?>" required style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
            </div>
            <div class="form-group" style="margin-top: 10px;">
                <label>Bus Number</label>
                <input type="text" name="bus_number" value="<?php echo $bus['bus_number']; ?>" required style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
            </div>
            <div class="form-group" style="margin-top: 10px;">
                <label>Type</label>
                <select name="bus_type" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                    <option value="AC" <?php if($bus['bus_type'] == 'AC') echo 'selected'; ?>>AC</option>
                    <option value="Deluxe" <?php if($bus['bus_type'] == 'Deluxe') echo 'selected'; ?>>Deluxe</option>
                    <option value="Sofa" <?php if($bus['bus_type'] == 'Sofa') echo 'selected'; ?>>Sofa</option>
                </select>
            </div>
            <div class="form-group" style="margin-top: 10px;">
                <label>Seats</label>
                <input type="number" name="total_seats" value="<?php echo $bus['total_seats']; ?>" required style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
            </div>
            <div style="margin-top: 20px;">
                <button type="submit" name="update_bus" class="btn btn-primary">Update Bus</button>
                <a href="buses.php" class="btn btn-outline" style="margin-left: 10px;">Back</a>
            </div>
        </form>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
