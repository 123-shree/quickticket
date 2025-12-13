<?php
include '../includes/db.php';
include 'includes/header.php';

$success = "";
$error = "";

// Handle Add Bus
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_bus'])) {
    $bus_name = $_POST['bus_name'];
    $bus_number = $_POST['bus_number'];
    $bus_type = $_POST['bus_type'];
    $total_seats = $_POST['total_seats'];

    $sql = "INSERT INTO buses (bus_name, bus_number, bus_type, total_seats) VALUES ('$bus_name', '$bus_number', '$bus_type', '$total_seats')";
    if ($conn->query($sql) === TRUE) {
        $success = "Bus added successfully!";
        // Redirect to routes page to add routes for this bus immediately
        header("Location: routes.php?msg=" . urlencode("Bus added successfully! Please add routes for it now."));
        exit();
    } else {
        $error = "Error: " . $conn->error;
    }
}

// Handle Delete
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM buses WHERE id=$id");
    header("Location: buses.php");
}
?>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
    <h2>Manage Buses</h2>
</div>

<?php if($success) echo "<p style='color: #6AECE1; margin-bottom: 10px;'>$success</p>"; ?>
<?php if(isset($_GET['msg'])) echo "<p style='color: #6AECE1; margin-bottom: 10px;'>{$_GET['msg']}</p>"; ?>
<?php if($error) echo "<p style='color: red; margin-bottom: 10px;'>$error</p>"; ?>

<div class="card">
    <h3>Add New Bus</h3>
    <form method="POST" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 15px; align-items: end;">
        <div>
            <label>Bus Name</label>
            <input type="text" name="bus_name" required>
        </div>
        <div>
            <label>Bus Number</label>
            <input type="text" name="bus_number" required>
        </div>
        <div>
            <label>Type</label>
            <select name="bus_type">
                <option value="AC">AC</option>
                <option value="Deluxe">Deluxe</option>
                <option value="Sofa">Sofa</option>
            </select>
        </div>
        <div>
            <label>Seats</label>
            <input type="number" name="total_seats" required>
        </div>
        <button type="submit" name="add_bus" class="btn btn-primary" style="height: 42px;">Add Bus</button>
    </form>
</div>

<div class="card">
    <h3>Existing Buses</h3>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Number</th>
                <th>Type</th>
                <th>Seats</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $result = $conn->query("SELECT * FROM buses ORDER BY id DESC");
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                    <td>{$row['id']}</td>
                    <td>{$row['bus_name']}</td>
                    <td>{$row['bus_number']}</td>
                    <td>{$row['bus_type']}</td>
                    <td>{$row['total_seats']}</td>
                    <td><a href='edit_bus.php?id={$row['id']}' style='color: blue; margin-right: 10px;'>Edit</a> <a href='buses.php?delete={$row['id']}' style='color: red;' onclick='return confirm(\"Are you sure?\")'>Delete</a></td>
                </tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<?php include 'includes/footer.php'; ?>
